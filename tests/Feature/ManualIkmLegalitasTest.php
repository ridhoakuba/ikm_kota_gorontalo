<?php

namespace Tests\Feature;

use App\Models\DataIkm;
use App\Models\Kelurahan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ManualIkmLegalitasTest extends TestCase
{
    use RefreshDatabase;

    private function industri(): User
    {
        return User::create([
            'name'     => 'Admin Industri',
            'email'    => 'industri@test.id',
            'password' => bcrypt('password'),
            'role'     => 'industri',
        ]);
    }

    private function kelurahan(string $nama = 'Limba B', string $kec = 'Kota Selatan'): Kelurahan
    {
        return Kelurahan::create(['nama_kelurahan' => $nama, 'kecamatan' => $kec]);
    }

    private function ikm(Kelurahan $kel, array $attrs = []): DataIkm
    {
        static $n = 0;
        $n++;

        return DataIkm::create($attrs + [
            'nib'               => 'NIB' . $n,
            'kelurahan_id'      => $kel->id,
            'nama_usaha'        => 'Usaha ' . $n,
            'nama_pemilik'      => 'Pemilik ' . $n,
            'alamat'            => 'Jl. Test ' . $n,
            'jenis_usaha'       => 'Industri Roti',
            'status_verifikasi' => 'menunggu',
        ]);
    }

    public function test_store_manual_creates_ikm_with_default_status(): void
    {
        $kel = $this->kelurahan();

        $this->actingAs($this->industri())
            ->post(route('industri.store-manual'), [
                'nib'          => '1234567890123',
                'nama_usaha'   => 'Roti Bunda',
                'nama_pemilik' => 'Siti',
                'alamat'       => 'Jl. Merdeka No. 1',
                'kecamatan'    => 'Kota Selatan',
                'kelurahan_id' => $kel->id,
                'kode_kbli'    => '10710',
                'jenis_usaha'  => 'Industri Roti dan Kue',
            ])
            ->assertRedirect(route('industri.data-ikm'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('data_ikms', [
            'nib'               => '1234567890123',
            'kode_kbli'         => '10710',
            'jenis_usaha'       => 'Industri Roti dan Kue',
            'status_verifikasi' => 'menunggu',
            'updated_by_role'   => 'industri',
            'kelurahan_id'      => $kel->id,
        ]);
    }

    public function test_store_manual_rejects_duplicate_nib_and_non_industri(): void
    {
        $kel = $this->kelurahan();
        $this->ikm($kel, ['nib' => 'DUP-1']);

        $payload = [
            'nib' => 'DUP-1', 'nama_usaha' => 'X', 'nama_pemilik' => 'Y',
            'alamat' => 'Z', 'kelurahan_id' => $kel->id, 'jenis_usaha' => 'K',
        ];

        $this->actingAs($this->industri())
            ->from(route('industri.data-ikm'))
            ->post(route('industri.store-manual'), $payload)
            ->assertSessionHasErrors('nib');
        $this->assertSame(1, DataIkm::where('nib', 'DUP-1')->count());

        $kelUser = User::create([
            'name' => 'Petugas', 'email' => 'kel@test.id', 'password' => bcrypt('password'),
            'role' => 'kelurahan', 'kelurahan_id' => $kel->id,
        ]);
        $this->actingAs($kelUser)
            ->post(route('industri.store-manual'), ['nib' => 'NEW'] + $payload)
            ->assertForbidden();
    }

    public function test_store_manual_rejects_kelurahan_outside_kecamatan(): void
    {
        $kel = $this->kelurahan('Limba B', 'Kota Selatan');

        $this->actingAs($this->industri())
            ->post(route('industri.store-manual'), [
                'nib' => 'N1', 'nama_usaha' => 'X', 'nama_pemilik' => 'Y', 'alamat' => 'Z',
                'kecamatan' => 'Kota Utara', 'kelurahan_id' => $kel->id, 'jenis_usaha' => 'K',
            ])
            ->assertSessionHasErrors('kelurahan_id');
        $this->assertDatabaseMissing('data_ikms', ['nib' => 'N1']);
    }

    public function test_filter_legalitas_and_badges(): void
    {
        $kel = $this->kelurahan();
        $this->ikm($kel, ['nama_usaha' => 'Warung Alfa', 'sertifikat_halal' => 'H-001']);
        $this->ikm($kel, ['nama_usaha' => 'Warung Beta', 'izin_bpom' => 'MD-001']);
        $this->ikm($kel, ['nama_usaha' => 'Warung Gamma', 'sertifikat_halal' => '']);

        $admin = $this->industri();

        $cases = [
            'sudah_halal' => ['Warung Alfa', ['Warung Beta', 'Warung Gamma']],
            'belum_halal' => ['Warung Beta', ['Warung Alfa']],
            'sudah_bpom'  => ['Warung Beta', ['Warung Alfa', 'Warung Gamma']],
            'belum_bpom'  => ['Warung Alfa', ['Warung Beta']],
        ];

        foreach ($cases as $filter => [$dilihat, $takDilihat]) {
            $res = $this->actingAs($admin)->get(route('industri.data-ikm', ['legalitas' => $filter]));
            $res->assertOk()->assertSee($dilihat);
            foreach ($takDilihat as $nama) {
                $res->assertDontSee($nama);
            }
        }

        $this->actingAs($admin)->get(route('industri.data-ikm'))
            ->assertOk()
            ->assertSee('Tambah IKM Manual')
            ->assertSee('Detail / Edit Verifikasi')
            ->assertDontSee('Semua Legalitas')
            ->assertDontSee('✓ Halal', false)
            ->assertDontSee('✕ BPOM', false);
    }

    public function test_industri_can_update_legalitas_directly_without_required_instruments(): void
    {
        $kel  = $this->kelurahan();
        $ikm  = $this->ikm($kel);
        $user = $this->industri();

        $this->actingAs($user)
            ->patch(route('ikm.updateStatus', $ikm), [
                'status_verifikasi' => 'ditemukan',
                'sertifikat_halal'  => 'ID123',
                'izin_bpom'         => 'MD456',
                'pirt'              => '',
                'catatan_petugas'   => 'Dicek via dokumen',
            ])
            ->assertSessionHas('success');

        $ikm->refresh();
        $this->assertSame('ditemukan', $ikm->status_verifikasi);
        $this->assertSame('ID123', $ikm->sertifikat_halal);
        $this->assertSame('MD456', $ikm->izin_bpom);
        $this->assertNull($ikm->pirt);
        $this->assertSame('industri', $ikm->updated_by_role);
        $this->assertSame($user->id, $ikm->last_verified_by);
        $this->assertSame($user->id, $ikm->verified_by);
        $this->assertNull($ikm->data_verifikasi);
    }

    public function test_kelurahan_cannot_change_legalitas_and_is_audited_as_kelurahan(): void
    {
        $kel = $this->kelurahan();
        $ikm = $this->ikm($kel, ['sertifikat_halal' => 'KEEP']);
        $kelUser = User::create([
            'name' => 'Petugas', 'email' => 'kel@test.id', 'password' => bcrypt('password'),
            'role' => 'kelurahan', 'kelurahan_id' => $kel->id,
        ]);

        // Instrumen wajib default akan di-seed; kirim jawaban lengkap
        \App\Models\FormVerifikasi::seedDefaults();
        \Illuminate\Support\Facades\Storage::fake('public');

        $this->actingAs($kelUser)
            ->patch(route('ikm.updateStatus', $ikm), [
                'status_verifikasi' => 'ditemukan',
                'sertifikat_halal'  => 'HACK',
                'izin_bpom'         => 'HACK',
                'jawaban' => [
                    'foto_tempat_usaha'  => \Illuminate\Http\UploadedFile::fake()->image('a.jpg'),
                    'koordinat_lokasi'   => '0.5, 123.0',
                    'status_operasional' => 'Aktif Berproduksi',
                ],
            ])
            ->assertSessionHas('success');

        $ikm->refresh();
        $this->assertSame('KEEP', $ikm->sertifikat_halal);
        $this->assertNull($ikm->izin_bpom);
        $this->assertSame('kelurahan', $ikm->updated_by_role);
        $this->assertSame($kelUser->id, $ikm->last_verified_by);
    }

    public function test_kbli_filter_and_import_parsing(): void
    {
        $kel = $this->kelurahan();
        $importer = new \App\Imports\IkmImport();

        // 1. Kolom 'kbli' (integer/string sesuai header Excel PTSP) & 'judul_kbli'
        $importer->model([
            'nib'              => 'NIB-KBLI-1',
            'nama_proyek'      => 'Usaha Roti',
            'nama_perusahaan'  => 'Budi',
            'alamat_usaha'     => 'Jl. A',
            'kelurahan_usaha'  => $kel->nama_kelurahan,
            'kbli'             => 10710,
            'judul_kbli'       => 'Industri Produk Roti dan Kue',
        ]);

        // 2. Tergabung di kolom judul_kbli
        $importer->model([
            'nib'              => 'NIB-KBLI-2',
            'nama_proyek'      => 'Usaha Konveksi',
            'nama_perusahaan'  => 'Ani',
            'alamat_usaha'     => 'Jl. B',
            'kelurahan_usaha'  => $kel->nama_kelurahan,
            'judul_kbli'       => '14111 - Industri Pakaian Jadi (Konveksi) Dari Tekstil',
        ]);

        // 3. KBLI dengan sufiks versi (16221v2025 -> 16221)
        $importer->model([
            'nib'              => 'NIB-KBLI-3',
            'nama_proyek'      => 'Kusen Jaya',
            'nama_perusahaan'  => 'Dedi',
            'alamat_usaha'     => 'Jl. C',
            'kelurahan_usaha'  => $kel->nama_kelurahan,
            'kbli'             => '16221v2025',
            'judul_kbli'       => 'Industri Barang Bangunan Dari Kayu',
        ]);

        // 4. KBLI kosong tetapi judul_kbli "Industri Barang Bangunan Dari Kayu" -> otomatis 16221
        $importer->model([
            'nib'              => 'NIB-KBLI-4',
            'nama_proyek'      => 'Pintu Kayu Mandiri',
            'nama_perusahaan'  => 'Eko',
            'alamat_usaha'     => 'Jl. D',
            'kelurahan_usaha'  => $kel->nama_kelurahan,
            'judul_kbli'       => 'Industri Barang Bangunan Dari Kayu',
        ]);

        // 5. Variasi judul dengan kode KBLI yang sama (10710) untuk menguji deduplikasi dropdown
        $importer->model([
            'nib'              => 'NIB-KBLI-5',
            'nama_proyek'      => 'Bakeri Lezat',
            'nama_perusahaan'  => 'Rina',
            'alamat_usaha'     => 'Jl. E',
            'kelurahan_usaha'  => $kel->nama_kelurahan,
            'kbli'             => '10710',
            'judul_kbli'       => 'Industri Produk Bakeri',
        ]);

        $this->assertDatabaseHas('data_ikms', [
            'nib'         => 'NIB-KBLI-1',
            'kode_kbli'   => '10710',
            'jenis_usaha' => 'Industri Produk Roti dan Kue',
        ]);
        $this->assertDatabaseHas('data_ikms', [
            'nib'         => 'NIB-KBLI-2',
            'kode_kbli'   => '14111',
            'jenis_usaha' => 'Industri Pakaian Jadi (Konveksi) Dari Tekstil',
        ]);
        $this->assertDatabaseHas('data_ikms', [
            'nib'         => 'NIB-KBLI-3',
            'kode_kbli'   => '16221',
            'jenis_usaha' => 'Industri Barang Bangunan Dari Kayu',
        ]);
        $this->assertDatabaseHas('data_ikms', [
            'nib'         => 'NIB-KBLI-4',
            'kode_kbli'   => '16221',
            'jenis_usaha' => 'Industri Barang Bangunan Dari Kayu',
        ]);

        $admin = $this->industri();

        // Filter menggunakan kode_kbli 10710 menampilkan semua variasi judul 10710 tanpa duplikasi opsi dropdown
        $res = $this->actingAs($admin)->get(route('industri.data-ikm', ['kbli' => '10710']));
        $res->assertOk()
            ->assertSee('Usaha Roti')
            ->assertSee('Bakeri Lezat')
            ->assertDontSee('Usaha Konveksi');

        // Pastikan kode 10710 hanya muncul 1 kali pada daftarKbli view data
        $daftarKbli = $res->viewData('daftarKbli');
        $this->assertSame(1, $daftarKbli->where('kode_kbli', '10710')->count());
        $this->assertSame(1, $daftarKbli->where('kode_kbli', '16221')->count());

        // Filter menggunakan judul_kbli (jenis_usaha)
        $this->actingAs($admin)
            ->get(route('industri.data-ikm', ['kbli' => 'Industri Pakaian Jadi (Konveksi) Dari Tekstil']))
            ->assertOk()
            ->assertSee('Usaha Konveksi')
            ->assertDontSee('Usaha Roti');
    }
}


