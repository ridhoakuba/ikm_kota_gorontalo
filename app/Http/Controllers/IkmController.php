<?php

namespace App\Http\Controllers;

use App\Exports\LaporanIkmExport;
use App\Imports\IkmImport;
use App\Models\DataIkm;
use App\Models\FormVerifikasi;
use App\Models\InstrumenVerifikasi;
use App\Models\Kelurahan;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class IkmController extends Controller
{
    // =========================================================================
    // DASHBOARD ROUTER — Arahkan sesuai role
    // =========================================================================

    /**
     * Tampilkan dasbor yang sesuai dengan role user yang sedang login.
     * Role industri → redirect ke Beranda modul industri.
     */
    public function dashboard(Request $request): View|RedirectResponse
    {
        $user = auth()->user();

        if ($user->role === 'industri') {
            return redirect()->route('industri.beranda');
        }

        if ($user->role === 'kelurahan') {
            $query = DataIkm::with(['kelurahan', 'verifiedBy'])
                ->where('kelurahan_id', $user->kelurahan_id)
                ->latest();

            if ($request->filled('kbli')) {
                $kbli = $request->kbli;
                $query->where(function ($q) use ($kbli) {
                    $q->where('kode_kbli', 'like', $kbli . '%')
                      ->orWhere('jenis_usaha', $kbli);
                });
            }

            if ($request->filled('status')) {
                $query->where('status_verifikasi', $request->status);
            }

            $dataIkms = $query->get();

            $daftarKbli = $this->buildDaftarKbliQuery($user->kelurahan_id);

            $instrumens = FormVerifikasi::aktif()->get();

            return view('dashboard.kelurahan', compact('dataIkms', 'instrumens', 'daftarKbli'));
        }

        return redirect('/')->with('error', 'Role tidak dikenali.');
    }

    // =========================================================================
    // MODUL 1: BERANDA — Statistik & Rekap Per Kecamatan
    // =========================================================================

    public function beranda(): View
    {
        $this->authorizeIndustri();

        $dataIkms = DataIkm::with('kelurahan')->get();

        // 9 Kecamatan resmi Kota Gorontalo — selalu tampil meski belum ada IKM
        $kecamatanResmi = [
            'Kota Tengah', 'Kota Timur', 'Dungingi', 'Kota Selatan',
            'Kota Barat',  'Kota Utara', 'Sipatana', 'Dumbo Raya', 'Hulonthalangi',
        ];

        // Kelompokkan data IKM berdasarkan kecamatan dari relasi kelurahan
        $grouped = DataIkm::with('kelurahan')
            ->get()
            ->groupBy(fn($ikm) => $ikm->kelurahan?->kecamatan);

        // Bangun rekap — gabungkan kecamatan resmi + kecamatan "lain-lain" dari data
        $rekapMap = [];
        foreach ($kecamatanResmi as $nama) {
            $group = $grouped->get($nama, collect());
            $rekapMap[$nama] = (object) [
                'kecamatan'       => $nama,
                'total'           => $group->count(),
                'ditemukan'       => $group->where('status_verifikasi', 'ditemukan')->count(),
                'tidak_ditemukan' => $group->where('status_verifikasi', 'tidak_ditemukan')->count(),
                'menunggu'        => $group->where('status_verifikasi', 'menunggu')->count(),
            ];
        }

        // Tambahkan kelompok di luar 9 kecamatan resmi (null / lain-lain)
        foreach ($grouped as $kec => $group) {
            if (!in_array($kec, $kecamatanResmi)) {
                $label = $kec ?: 'Belum Terklasifikasi';
                if (!isset($rekapMap[$label])) {
                    $rekapMap[$label] = (object) [
                        'kecamatan'       => $label,
                        'total'           => $group->count(),
                        'ditemukan'       => $group->where('status_verifikasi', 'ditemukan')->count(),
                        'tidak_ditemukan' => $group->where('status_verifikasi', 'tidak_ditemukan')->count(),
                        'menunggu'        => $group->where('status_verifikasi', 'menunggu')->count(),
                    ];
                } else {
                    $rekapMap[$label]->total           += $group->count();
                    $rekapMap[$label]->ditemukan       += $group->where('status_verifikasi', 'ditemukan')->count();
                    $rekapMap[$label]->tidak_ditemukan += $group->where('status_verifikasi', 'tidak_ditemukan')->count();
                    $rekapMap[$label]->menunggu        += $group->where('status_verifikasi', 'menunggu')->count();
                }
            }
        }

        // Urutkan: yang memiliki data lebih banyak tampil di atas
        $rekap = collect(array_values($rekapMap))
            ->sortByDesc('total')
            ->values();

        return view('industri.beranda', compact('dataIkms', 'rekap'));
    }

    // =========================================================================
    // MODUL 2: DATA IKM — Master Tabel + Filter + Import
    // =========================================================================

    public function dataIkm(Request $request): View
    {
        $this->authorizeIndustri();

        $query = DataIkm::with(['kelurahan', 'verifiedBy'])->latest();

        // Filter: Kecamatan
        if ($request->filled('kecamatan')) {
            $kecamatan = $request->kecamatan;
            $query->where(function ($q) use ($kecamatan) {
                $q->whereHas('kelurahan', function ($sub) use ($kecamatan) {
                    $sub->where('kecamatan', $kecamatan);
                });
                if (\Illuminate\Support\Facades\Schema::hasColumn('data_ikms', 'kecamatan')) {
                    $q->orWhere('kecamatan', $kecamatan);
                }
            });
        }

        // Filter: Kelurahan
        if ($request->filled('kelurahan_id')) {
            $query->where('kelurahan_id', $request->kelurahan_id);
        }

        // Filter: Status Verifikasi
        if ($request->filled('status')) {
            $query->where('status_verifikasi', $request->status);
        }

        // Filter: Legalitas (Halal & BPOM)
        if ($request->filled('legalitas')) {
            $query->filterLegalitas($request->legalitas);
        }

        // Filter: KBLI (Kode KBLI atau Judul KBLI / Jenis Usaha)
        if ($request->filled('kbli')) {
            $kbli = $request->kbli;
            $query->where(function ($q) use ($kbli) {
                $q->where('kode_kbli', 'like', $kbli . '%')
                  ->orWhere('jenis_usaha', $kbli);
            });
        }

        $dataIkms = $query->paginate(25)->withQueryString();

        // Daftar unik KBLI berdasarkan kode_kbli (tanpa duplikasi nomor KBLI)
        $daftarKbli = $this->buildDaftarKbliQuery();

        // Data untuk dropdown filter kecamatan (unik dari kelurahan)
        $kecamatans = Kelurahan::whereNotNull('kecamatan')
            ->where('kecamatan', '!=', '')
            ->distinct()
            ->pluck('kecamatan')
            ->sort()
            ->values();

        if ($kecamatans->isEmpty()) {
            $kecamatans = collect([
                'Kota Barat', 'Kota Selatan', 'Kota Tengah', 'Kota Timur',
                'Kota Utara', 'Dumbo Raya', 'Dungingi', 'Hulonthalangi', 'Sipatana',
            ])->sort()->values();
        }

        $kelurahans = Kelurahan::orderBy('nama_kelurahan')->get();

        // Instrumen dinamis untuk modal Detail / Edit Verifikasi (sama dengan milik Kelurahan)
        if (FormVerifikasi::count() === 0) {
            FormVerifikasi::seedDefaults();
        }
        $instrumens = FormVerifikasi::aktif()->get();

        return view('industri.data-ikm', compact('dataIkms', 'kecamatans', 'kelurahans', 'instrumens', 'daftarKbli'));
    }

    /**
     * Tambah data IKM secara manual (tanpa import Excel).
     * Hanya dapat diakses oleh role 'industri'.
     */
    public function storeManual(Request $request): RedirectResponse
    {
        $this->authorizeIndustri();

        // Dukungan alias judul_kbli -> jenis_usaha agar konsisten dengan struktur Excel
        if (!$request->filled('jenis_usaha') && $request->filled('judul_kbli')) {
            $request->merge(['jenis_usaha' => $request->input('judul_kbli')]);
        }

        $validated = $request->validate([
            'nib'               => ['required', 'string', 'max:50', 'unique:data_ikms,nib'],
            'nama_usaha'        => ['required', 'string', 'max:255'],
            'nama_pemilik'      => ['required', 'string', 'max:255'],
            'alamat'            => ['required', 'string', 'max:1000'],
            'kecamatan'         => ['nullable', 'string', 'max:255'],
            'kelurahan_id'      => ['required', 'integer', 'exists:kelurahans,id'],
            'kode_kbli'         => ['nullable', 'string', 'max:20'],
            'jenis_usaha'       => ['required', 'string', 'max:255'],
            'status_verifikasi' => ['nullable', 'in:' . implode(',', [
                DataIkm::STATUS_MENUNGGU,
                DataIkm::STATUS_DITEMUKAN,
                DataIkm::STATUS_TIDAK_DITEMUKAN,
            ])],
        ], [
            'nib.required'          => 'NIB wajib diisi.',
            'nib.unique'            => 'NIB tersebut sudah terdaftar di Master Data IKM.',
            'nama_usaha.required'   => 'Nama usaha wajib diisi.',
            'nama_pemilik.required' => 'Nama pemilik wajib diisi.',
            'alamat.required'       => 'Alamat usaha wajib diisi.',
            'kelurahan_id.required' => 'Silakan pilih kelurahan.',
            'kelurahan_id.exists'   => 'Kelurahan yang dipilih tidak valid.',
            'jenis_usaha.required'  => 'Judul KBLI / Jenis usaha wajib diisi.',
        ]);

        $kelurahan = Kelurahan::findOrFail($validated['kelurahan_id']);
        if (!empty($validated['kecamatan']) && $kelurahan->kecamatan && $kelurahan->kecamatan !== $validated['kecamatan']) {
            return back()
                ->withInput()
                ->withErrors(['kelurahan_id' => 'Kelurahan yang dipilih bukan bagian dari kecamatan tersebut.']);
        }

        $kodeKbli  = isset($validated['kode_kbli']) && trim($validated['kode_kbli']) !== '' ? trim($validated['kode_kbli']) : null;
        $judulKbli = trim($validated['jenis_usaha']);

        // Jika judul_kbli diketik tergabung "10710 : Judul" atau "10710 - Judul", pisahkan secara rapi
        if (preg_match('/^(\d{4,5}[a-zA-Z0-9]*)\s*[-:]\s*(.+)$/u', $judulKbli, $matches)) {
            if ($kodeKbli === null) {
                $kodeKbli = trim($matches[1]);
            }
            $judulKbli = trim($matches[2]);
        }

        $kodeKbli = IkmImport::sanitizeKodeKbli($kodeKbli, $judulKbli);

        $status = $validated['status_verifikasi'] ?? DataIkm::STATUS_MENUNGGU;

        $dataIkm = [
            'nib'               => trim($validated['nib']),
            'kelurahan_id'      => $kelurahan->id,
            'nama_usaha'        => trim($validated['nama_usaha']),
            'nama_pemilik'      => trim($validated['nama_pemilik']),
            'alamat'            => trim($validated['alamat']),
            'kode_kbli'         => $kodeKbli,
            'jenis_usaha'       => $judulKbli,
            'status_verifikasi' => $status,
            'updated_by_role'   => DataIkm::UPDATED_BY_INDUSTRI,
        ];

        // Jika status awal sudah final, catat sebagai verifikasi langsung oleh Bidang Industri
        if ($status !== DataIkm::STATUS_MENUNGGU) {
            $dataIkm += [
                'verified_at'      => now(),
                'verified_by'      => auth()->id(),
                'last_verified_by' => auth()->id(),
            ];
        }

        DataIkm::create($dataIkm);

        return redirect()->route('industri.data-ikm')
            ->with('success', "Data IKM \"{$dataIkm['nama_usaha']}\" berhasil ditambahkan secara manual.");
    }

    /**
     * Proses upload dan import file Excel (.xlsx).
     * Hanya dapat diakses oleh role 'industri'.
     */
    public function import(Request $request): RedirectResponse
    {
        $this->authorizeIndustri();

        $request->validate([
            'file_excel' => ['required', 'file', 'mimes:xlsx,xls', 'max:10240'],
        ], [
            'file_excel.required' => 'Silakan pilih file Excel terlebih dahulu.',
            'file_excel.mimes'    => 'File harus berformat .xlsx atau .xls.',
            'file_excel.max'      => 'Ukuran file maksimal 10 MB.',
        ]);

        try {
            Excel::import(new IkmImport(), $request->file('file_excel'));

            return redirect()->route('industri.data-ikm')
                ->with('success', 'Data IKM berhasil diimport dari file Excel.');
        } catch (\Exception $e) {
            return redirect()->route('industri.data-ikm')
                ->with('error', 'Gagal mengimport data: ' . $e->getMessage());
        }
    }

    // =========================================================================
    // MODUL 3: AKUN KELURAHAN — Manajemen & Reset Password
    // =========================================================================

    public function akunKelurahan(): View
    {
        $this->authorizeIndustri();

        $userKelurahan = User::where('role', 'kelurahan')
            ->with('kelurahan')
            ->orderBy('name')
            ->get();

        return view('industri.akun-kelurahan', compact('userKelurahan'));
    }

    /**
     * Reset password akun kelurahan ke default ('password').
     * Hanya dapat diakses oleh role 'industri'.
     */
    public function resetPasswordKelurahan(Request $request, User $user): RedirectResponse
    {
        $this->authorizeIndustri();

        if ($user->role !== 'kelurahan') {
            abort(403, 'Hanya akun kelurahan yang dapat direset.');
        }

        $user->update(['password' => Hash::make('password')]);

        return redirect()->route('industri.akun-kelurahan')
            ->with('success', 'Password akun "' . $user->name . '" berhasil direset ke default.');
    }

    // =========================================================================
    // MODUL 4: FORM VERIFIKASI — Instrumen
    // =========================================================================

    public function formVerifikasi(): View
    {
        $this->authorizeIndustri();

        $instrumens = InstrumenVerifikasi::orderBy('urutan')->get();

        return view('industri.form-verifikasi', compact('instrumens'));
    }

    public function tambahInstrumen(Request $request): RedirectResponse
    {
        $this->authorizeIndustri();

        $request->validate([
            'nama_instrumen' => ['required', 'string', 'max:255'],
            'deskripsi'      => ['nullable', 'string', 'max:500'],
            'tipe'           => ['required', 'in:teks,foto,koordinat,pilihan'],
            'urutan'         => ['nullable', 'integer', 'min:1'],
        ]);

        InstrumenVerifikasi::create([
            'nama_instrumen' => $request->nama_instrumen,
            'deskripsi'      => $request->deskripsi,
            'tipe'           => $request->tipe,
            'is_wajib'       => $request->boolean('is_wajib'),
            'urutan'         => $request->urutan ?? InstrumenVerifikasi::max('urutan') + 1,
            'aktif'          => true,
        ]);

        return redirect()->route('industri.form-verifikasi')
            ->with('success', 'Instrumen verifikasi baru berhasil ditambahkan.');
    }

    public function updateInstrumen(Request $request, InstrumenVerifikasi $instrumen): RedirectResponse
    {
        $this->authorizeIndustri();

        if ($request->input('toggle') === 'aktif') {
            $instrumen->update(['aktif' => !$instrumen->aktif]);
            $status = $instrumen->fresh()->aktif ? 'diaktifkan' : 'dinonaktifkan';
            return redirect()->route('industri.form-verifikasi')
                ->with('success', "Instrumen \"{$instrumen->nama_instrumen}\" berhasil {$status}.");
        }

        if ($request->input('toggle') === 'wajib') {
            $instrumen->update(['is_wajib' => !$instrumen->is_wajib]);
            $status = $instrumen->fresh()->is_wajib ? 'wajib' : 'opsional';
            return redirect()->route('industri.form-verifikasi')
                ->with('success', "Instrumen \"{$instrumen->nama_instrumen}\" kini berstatus {$status}.");
        }

        // Update lengkap (dari form edit jika ada)
        $request->validate([
            'nama_instrumen' => ['required', 'string', 'max:255'],
            'tipe'           => ['required', 'in:teks,foto,koordinat,pilihan'],
        ]);

        $instrumen->update($request->only(['nama_instrumen', 'deskripsi', 'tipe', 'is_wajib', 'urutan', 'aktif']));

        return redirect()->route('industri.form-verifikasi')
            ->with('success', 'Instrumen berhasil diperbarui.');
    }

    public function hapusInstrumen(InstrumenVerifikasi $instrumen): RedirectResponse
    {
        $this->authorizeIndustri();

        $nama = $instrumen->nama_instrumen;
        $instrumen->delete();

        return redirect()->route('industri.form-verifikasi')
            ->with('success', "Instrumen \"{$nama}\" berhasil dihapus.");
    }

    // =========================================================================
    // MODUL 5: LAPORAN & TINDAK LANJUT
    // =========================================================================

    public function laporan(Request $request): View
    {
        $this->authorizeIndustri();

        $query = DataIkm::with(['kelurahan', 'verifiedBy'])
            ->where('status_verifikasi', DataIkm::STATUS_TIDAK_DITEMUKAN)
            ->latest();

        // Filter Kecamatan
        if ($request->filled('kecamatan')) {
            $query->whereHas('kelurahan', fn($q) => $q->where('kecamatan', $request->kecamatan));
        }

        // Filter Tindak Lanjut
        if ($request->filled('tindak')) {
            $query->where('ditindaklanjuti', (bool) $request->tindak);
        }

        $laporans = $query->paginate(20)->withQueryString();

        $allTidak = DataIkm::where('status_verifikasi', DataIkm::STATUS_TIDAK_DITEMUKAN)->get();
        $totalTidakDitemukan = $allTidak->count();
        $totalBelumTindak    = $allTidak->where('ditindaklanjuti', false)->count();
        $totalSudahTindak    = $allTidak->where('ditindaklanjuti', true)->count();

        $kecamatans = Kelurahan::whereNotNull('kecamatan')
            ->distinct('kecamatan')
            ->orderBy('kecamatan')
            ->pluck('kecamatan');

        return view('industri.laporan', compact(
            'laporans',
            'totalTidakDitemukan',
            'totalBelumTindak',
            'totalSudahTindak',
            'kecamatans'
        ));
    }

    public function tandaiDitindaklanjuti(DataIkm $dataIkm): RedirectResponse
    {
        $this->authorizeIndustri();

        $dataIkm->update(['ditindaklanjuti' => true]);

        return redirect()->route('industri.laporan')
            ->with('success', "IKM \"{$dataIkm->nama_usaha}\" berhasil ditandai sebagai sudah ditindaklanjuti.");
    }

    public function eksporLaporan(): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $this->authorizeIndustri();

        $filename = 'laporan-ikm-tidak-ditemukan-' . now()->format('Ymd-His') . '.xlsx';
        return Excel::download(new LaporanIkmExport(), $filename);
    }

    // =========================================================================
    // KELURAHAN — Update status verifikasi & Ganti password
    // =========================================================================

    /**
     * Update status verifikasi data IKM secara dinamis (Dynamic Form Engine).
     * Dapat diakses oleh Petugas Kelurahan atau Admin Industri.
     */
    public function updateStatus(Request $request, DataIkm $dataIkm): RedirectResponse
    {
        $user       = auth()->user();
        $isIndustri = $user->role === 'industri';

        // Validasi hak akses kelurahan
        if ($user->role === 'kelurahan' && $dataIkm->kelurahan_id !== $user->kelurahan_id) {
            abort(403, 'Anda tidak memiliki akses untuk memverifikasi data di luar kelurahan Anda.');
        }

        // Hanya Kelurahan & Admin Industri yang boleh memperbarui verifikasi
        if (!in_array($user->role, ['kelurahan', 'industri'], true)) {
            abort(403, 'Anda tidak memiliki akses untuk memperbarui verifikasi.');
        }

        $updatedByRole = $isIndustri ? DataIkm::UPDATED_BY_INDUSTRI : DataIkm::UPDATED_BY_KELURAHAN;

        $request->validate([
            'status_verifikasi' => [
                'required',
                'in:' . implode(',', [
                    DataIkm::STATUS_MENUNGGU,
                    DataIkm::STATUS_DITEMUKAN,
                    DataIkm::STATUS_TIDAK_DITEMUKAN,
                ]),
            ],
            'catatan_petugas'  => ['nullable', 'string', 'max:1000'],
            'kode_kbli'        => ['nullable', 'string', 'max:20'],
            'jenis_usaha'      => ['nullable', 'string', 'max:255'],
            'sertifikat_halal' => ['nullable', 'string', 'max:100'],
            'izin_bpom'        => ['nullable', 'string', 'max:100'],
            'pirt'             => ['nullable', 'string', 'max:100'],
        ]);

        // Legalitas (Halal / BPOM / PIRT) & KBLI hanya dapat diubah oleh Bidang Industri
        $legalitas = $isIndustri
            ? [
                'sertifikat_halal' => $request->filled('sertifikat_halal') ? trim($request->sertifikat_halal) : null,
                'izin_bpom'        => $request->filled('izin_bpom') ? trim($request->izin_bpom) : null,
                'pirt'             => $request->filled('pirt') ? trim($request->pirt) : null,
            ]
            : [];

        if ($isIndustri && $request->has('kode_kbli')) {
            $legalitas['kode_kbli'] = IkmImport::sanitizeKodeKbli(
                $request->input('kode_kbli'),
                $request->input('jenis_usaha', $dataIkm->jenis_usaha)
            );
        }
        if ($isIndustri && $request->filled('jenis_usaha')) {
            $legalitas['jenis_usaha'] = trim($request->jenis_usaha);
        }

        // 1. Jika aksi reset kembali ke status 'menunggu' (khusus alur Kelurahan;
        //    Admin Industri menyimpan perubahan tanpa menghapus data yang sudah ada)
        if ($request->status_verifikasi === DataIkm::STATUS_MENUNGGU && !$isIndustri) {
            $dataIkm->update([
                'status_verifikasi' => DataIkm::STATUS_MENUNGGU,
                'catatan_petugas'   => null,
                'data_verifikasi'   => null,
                'verified_at'       => null,
                'verified_by'       => null,
                'updated_by_role'   => $updatedByRole,
                'last_verified_by'  => $user->id,
            ]);

            return redirect()->back()
                ->with('success', "Status verifikasi IKM \"{$dataIkm->nama_usaha}\" berhasil di-reset ke Menunggu.");
        }

        // 2. Ambil seluruh instrumen aktif dari database (seed default jika kosong)
        if (FormVerifikasi::count() === 0) {
            FormVerifikasi::seedDefaults();
        }
        $instrumens = FormVerifikasi::aktif()->get();
        $jawabanInput = $request->input('jawaban', []);
        $existingData = is_array($dataIkm->data_verifikasi) ? $dataIkm->data_verifikasi : [];
        // Admin Industri memperbarui langsung: pertahankan data lama & tidak mewajibkan instrumen lapangan
        $dataVerifikasi = $isIndustri ? $existingData : [];
        $missingRequired = [];

        foreach ($instrumens as $item) {
            $slug = $item->slug;
            $nilai = null;

            if ($item->tipe_input === 'file') {
                if ($request->hasFile("jawaban.{$slug}")) {
                    $file = $request->file("jawaban.{$slug}");
                    // Validasi ukuran file (maksimal 10MB)
                    $path = $file->store('verifikasi', 'public');
                    $nilai = $path;
                } else {
                    // Pertahankan file lama jika sebelumnya sudah ada
                    $prev = $existingData[$slug] ?? null;
                    $nilai = is_array($prev) ? ($prev['nilai'] ?? null) : $prev;
                }

                if (!$isIndustri && $item->is_required && empty($nilai)) {
                    $missingRequired[] = "Instrumen \"{$item->nama_instrumen}\" wajib diunggah.";
                }
            } else {
                $nilai = $jawabanInput[$slug] ?? null;

                if (!$isIndustri && $item->is_required && (is_null($nilai) || trim((string)$nilai) === '')) {
                    $missingRequired[] = "Instrumen \"{$item->nama_instrumen}\" wajib diisi.";
                }
            }

            $dataVerifikasi[$slug] = [
                'nama'  => $item->nama_instrumen,
                'tipe'  => $item->tipe_input,
                'nilai' => $nilai,
            ];
        }

        // Untuk Admin Industri, buang instrumen kosong agar tidak tampil sebagai "bukti"
        if ($isIndustri) {
            $dataVerifikasi = array_filter($dataVerifikasi, function ($v) {
                $nilai = is_array($v) ? ($v['nilai'] ?? null) : $v;
                return !(is_null($nilai) || trim((string) $nilai) === '');
            });
        }

        if (!empty($missingRequired)) {
            return redirect()->back()
                ->withInput()
                ->with('error', implode(' ', $missingRequired));
        }

        // 3. Simpan data verifikasi, legalitas, status, tanggal, verifikator & riwayat audit
        $dataIkm->update([
            'status_verifikasi' => $request->status_verifikasi,
            'catatan_petugas'   => $request->catatan_petugas,
            'data_verifikasi'   => empty($dataVerifikasi) ? null : $dataVerifikasi,
            'verified_at'       => now(),
            'verified_by'       => $user->id,
            'updated_by_role'   => $updatedByRole,
            'last_verified_by'  => $user->id,
        ] + $legalitas);

        $label = match ($request->status_verifikasi) {
            DataIkm::STATUS_DITEMUKAN => 'Ditemukan / Aktif',
            DataIkm::STATUS_MENUNGGU  => 'Menunggu Verifikasi',
            default                   => 'Tidak Ditemukan / Tutup',
        };

        $oleh = $isIndustri ? 'oleh Bidang Industri' : 'lapangan';

        return redirect()->back()
            ->with('success', "Hasil verifikasi {$oleh} IKM \"{$dataIkm->nama_usaha}\" berhasil disimpan ({$label}).");
    }

    /**
     * Ganti password milik user kelurahan yang sedang login.
     */
    public function gantiPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'password_lama' => ['required', 'string'],
            'password_baru' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'password_lama.required'  => 'Password lama wajib diisi.',
            'password_baru.required'  => 'Password baru wajib diisi.',
            'password_baru.min'       => 'Password baru minimal 8 karakter.',
            'password_baru.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $user = auth()->user();

        if (! Hash::check($request->password_lama, $user->password)) {
            return back()
                ->withErrors(['password_lama' => 'Password lama yang Anda masukkan salah.'])
                ->with('modal_open', 'ganti-password');
        }

        $user->update(['password' => Hash::make($request->password_baru)]);

        return redirect()->route('dashboard')
            ->with('success', 'Password berhasil diperbarui.');
    }

    // =========================================================================
    // HELPER PRIVATE
    // =========================================================================

    private function authorizeIndustri(): void
    {
        if (auth()->user()->role !== 'industri') {
            abort(403, 'Halaman ini hanya dapat diakses oleh Admin Industri.');
        }
    }

    /**
     * Ambil daftar unik KBLI untuk dropdown filter:
     * - Dikelompokkan unik berdasarkan kode_kbli (satu judul representatif via MAX(jenis_usaha)).
     * - Menyertakan entri yang belum memiliki kode_kbli jika jenis_usaha-nya belum terwakili.
     */
    private function buildDaftarKbliQuery(?int $kelurahanId = null): \Illuminate\Support\Collection
    {
        $byKode = DataIkm::selectRaw('kode_kbli, MAX(jenis_usaha) as jenis_usaha')
            ->when($kelurahanId, fn ($q) => $q->where('kelurahan_id', $kelurahanId))
            ->whereNotNull('kode_kbli')
            ->where('kode_kbli', '!=', '')
            ->groupBy('kode_kbli')
            ->orderBy('kode_kbli')
            ->get();

        $tanpaKode = DataIkm::selectRaw('NULL as kode_kbli, jenis_usaha')
            ->when($kelurahanId, fn ($q) => $q->where('kelurahan_id', $kelurahanId))
            ->where(fn ($q) => $q->whereNull('kode_kbli')->orWhere('kode_kbli', ''))
            ->whereNotNull('jenis_usaha')
            ->where('jenis_usaha', '!=', '')
            ->when($byKode->isNotEmpty(), fn ($q) => $q->whereNotIn('jenis_usaha', $byKode->pluck('jenis_usaha')))
            ->groupBy('jenis_usaha')
            ->orderBy('jenis_usaha')
            ->get();

        return $byKode->concat($tanpaKode)->values();
    }
}
