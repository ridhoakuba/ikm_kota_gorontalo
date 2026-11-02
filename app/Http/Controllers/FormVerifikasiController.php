<?php

namespace App\Http\Controllers;

use App\Models\FormVerifikasi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FormVerifikasiController extends Controller
{
    /**
     * Tampilkan daftar instrumen verifikasi dinamis (Modul 4 Industri).
     */
    public function index(): View
    {
        $this->authorizeIndustri();

        $instrumens = FormVerifikasi::orderBy('urutan')->get();

        // Seed default jika tabel masih kosong sama sekali
        if ($instrumens->isEmpty()) {
            $this->seedDefaultInstruments();
            $instrumens = FormVerifikasi::orderBy('urutan')->get();
        }

        return view('industri.form-verifikasi', compact('instrumens'));
    }

    /**
     * Tambah instrumen verifikasi baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorizeIndustri();

        $request->validate([
            'nama_instrumen' => ['required', 'string', 'max:255'],
            'deskripsi'      => ['nullable', 'string', 'max:500'],
            'tipe_input'     => ['required', 'in:text,file,koordinat,select'],
            'opsi_pilihan'   => ['nullable', 'string'],
            'urutan'         => ['nullable', 'integer', 'min:1'],
        ]);

        $opsiArray = null;
        if ($request->tipe_input === 'select') {
            $opsiArray = $this->parseOptions($request->input('opsi_pilihan'));
        }

        $slug = FormVerifikasi::generateUniqueSlug($request->nama_instrumen);

        FormVerifikasi::create([
            'nama_instrumen' => $request->nama_instrumen,
            'slug'           => $slug,
            'deskripsi'      => $request->deskripsi,
            'tipe_input'     => $request->tipe_input,
            'opsi_pilihan'   => $opsiArray,
            'is_required'    => $request->boolean('is_required'),
            'urutan'         => $request->urutan ?? (FormVerifikasi::max('urutan') + 1),
            'is_active'      => true,
        ]);

        return redirect()->route('industri.form-verifikasi')
            ->with('success', "Instrumen \"{$request->nama_instrumen}\" berhasil ditambahkan ke form verifikasi.");
    }

    /**
     * Update instrumen verifikasi (full update atau toggle).
     */
    public function update(Request $request, FormVerifikasi $formVerifikasi): RedirectResponse
    {
        $this->authorizeIndustri();

        // Handle Quick Toggle Aktif
        if ($request->input('toggle') === 'is_active') {
            $formVerifikasi->update(['is_active' => !$formVerifikasi->is_active]);
            $status = $formVerifikasi->fresh()->is_active ? 'diaktifkan' : 'dinonaktifkan';
            return redirect()->route('industri.form-verifikasi')
                ->with('success', "Instrumen \"{$formVerifikasi->nama_instrumen}\" berhasil {$status}.");
        }

        // Handle Quick Toggle Wajib
        if ($request->input('toggle') === 'is_required') {
            $formVerifikasi->update(['is_required' => !$formVerifikasi->is_required]);
            $status = $formVerifikasi->fresh()->is_required ? 'wajib diisi' : 'bersifat opsional';
            return redirect()->route('industri.form-verifikasi')
                ->with('success', "Instrumen \"{$formVerifikasi->nama_instrumen}\" kini {$status}.");
        }

        // Full Update
        $request->validate([
            'nama_instrumen' => ['required', 'string', 'max:255'],
            'deskripsi'      => ['nullable', 'string', 'max:500'],
            'tipe_input'     => ['required', 'in:text,file,koordinat,select'],
            'opsi_pilihan'   => ['nullable', 'string'],
            'urutan'         => ['nullable', 'integer', 'min:1'],
        ]);

        $opsiArray = null;
        if ($request->tipe_input === 'select') {
            $opsiArray = $this->parseOptions($request->input('opsi_pilihan'));
        }

        $formVerifikasi->update([
            'nama_instrumen' => $request->nama_instrumen,
            'deskripsi'      => $request->deskripsi,
            'tipe_input'     => $request->tipe_input,
            'opsi_pilihan'   => $opsiArray,
            'is_required'    => $request->boolean('is_required'),
            'urutan'         => $request->urutan ?? $formVerifikasi->urutan,
        ]);

        return redirect()->route('industri.form-verifikasi')
            ->with('success', "Instrumen \"{$formVerifikasi->nama_instrumen}\" berhasil diperbarui.");
    }

    /**
     * Hapus instrumen verifikasi.
     */
    public function destroy(FormVerifikasi $formVerifikasi): RedirectResponse
    {
        $this->authorizeIndustri();

        $nama = $formVerifikasi->nama_instrumen;
        $formVerifikasi->delete();

        return redirect()->route('industri.form-verifikasi')
            ->with('success', "Instrumen \"{$nama}\" berhasil dihapus.");
    }

    /**
     * Helper untuk parse teks opsi (baris baru atau koma) menjadi array JSON.
     */
    private function parseOptions(?string $raw): ?array
    {
        if (empty(trim($raw ?? ''))) {
            return null;
        }

        // Pisahkan berdasarkan baris baru atau koma jika satu baris
        $lines = preg_split('/\r\n|\r|\n|,/', $raw);
        $result = [];

        foreach ($lines as $line) {
            $trimmed = trim($line);
            if ($trimmed !== '') {
                $result[] = $trimmed;
            }
        }

        return !empty($result) ? array_values(array_unique($result)) : null;
    }

    /**
     * Seed instrumen verifikasi standar awal jika tabel kosong.
     */
    private function seedDefaultInstruments(): void
    {
        FormVerifikasi::seedDefaults();
    }

    private function authorizeIndustri(): void
    {
        if (auth()->user()->role !== 'industri') {
            abort(403, 'Halaman ini hanya dapat diakses oleh Admin Industri.');
        }
    }
}
