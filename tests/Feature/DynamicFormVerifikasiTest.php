<?php

namespace Tests\Feature;

use App\Models\DataIkm;
use App\Models\FormVerifikasi;
use App\Models\Kelurahan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DynamicFormVerifikasiTest extends TestCase
{
    use RefreshDatabase;

    public function test_industri_can_manage_form_verifikasi_instruments(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin_test@gorontalo.go.id'],
            [
                'name'     => 'Admin Industri Test',
                'password' => bcrypt('password'),
                'role'     => 'industri',
            ]
        );

        // 1. View Index Form Verifikasi
        $response = $this->actingAs($admin)->get(route('industri.form-verifikasi'));
        $response->assertStatus(200);

        // 2. Tambah Instrumen Baru (tipe select)
        $response = $this->actingAs($admin)->post(route('industri.form-verifikasi.store'), [
            'nama_instrumen' => 'Kondisi Bangunan Usaha',
            'deskripsi'      => 'Kondisi fisik tempat produksi',
            'tipe_input'     => 'select',
            'opsi_pilihan'   => "Permanen\nSemi Permanen\nKayu",
            'is_required'    => '1',
            'urutan'         => 10,
        ]);
        $response->assertRedirect(route('industri.form-verifikasi'));

        $this->assertDatabaseHas('form_verifikasis', [
            'nama_instrumen' => 'Kondisi Bangunan Usaha',
            'tipe_input'     => 'select',
            'is_required'    => 1,
        ]);

        $instrumen = FormVerifikasi::where('nama_instrumen', 'Kondisi Bangunan Usaha')->first();
        $this->assertNotNull($instrumen);
        $this->assertIsArray($instrumen->opsi_pilihan);
        $this->assertContains('Permanen', $instrumen->opsi_pilihan);

        // 3. Toggle Status Aktif
        $this->actingAs($admin)->patch(route('industri.form-verifikasi.update', $instrumen), [
            'toggle' => 'is_active',
        ]);
        $this->assertFalse($instrumen->fresh()->is_active);

        // 4. Toggle Status Wajib
        $this->actingAs($admin)->patch(route('industri.form-verifikasi.update', $instrumen), [
            'toggle' => 'is_required',
        ]);
        $this->assertFalse($instrumen->fresh()->is_required);

        // 5. Hapus Instrumen
        $this->actingAs($admin)->delete(route('industri.form-verifikasi.destroy', $instrumen));
        $this->assertDatabaseMissing('form_verifikasis', ['id' => $instrumen->id]);
    }

    public function test_kelurahan_can_submit_dynamic_field_verification(): void
    {
        Storage::fake('public');

        $kelurahan = Kelurahan::firstOrCreate(
            ['nama_kelurahan' => 'Kelurahan Test Dynamic'],
            ['kecamatan' => 'Kota Tengah']
        );

        $petugas = User::firstOrCreate(
            ['email' => 'petugas_test@gorontalo.go.id'],
            [
                'name'         => 'Petugas Kelurahan Test',
                'password'     => bcrypt('password'),
                'role'         => 'kelurahan',
                'kelurahan_id' => $kelurahan->id,
            ]
        );

        $ikm = DataIkm::create([
            'kelurahan_id'      => $kelurahan->id,
            'nama_usaha'        => 'Kue Karawang Test',
            'nama_pemilik'      => 'Ibu Fatimah',
            'alamat'            => 'Jl. Nani Wartabone No. 12',
            'jenis_usaha'       => 'Industri Kue Basah & Kering',
            'status_verifikasi' => 'menunggu',
        ]);

        $fileFoto = UploadedFile::fake()->image('foto_usaha_lapangan.jpg');

        $payload = [
            'status_verifikasi' => 'ditemukan',
            'catatan_petugas'   => 'Usaha aktif beroperasi setiap hari',
            'jawaban'           => [
                'koordinat_lokasi'   => '0.540123, 123.059421',
                'status_operasional' => 'Aktif Berproduksi',
                'catatan_lapangan'   => 'Sudah mempekerjakan 3 karyawan warga lokal',
                'foto_tempat_usaha'  => $fileFoto,
            ],
        ];

        $response = $this->actingAs($petugas)->patch(route('ikm.updateStatus', $ikm), $payload);
        $response->assertSessionHasNoErrors();

        $ikm->refresh();
        $this->assertEquals('ditemukan', $ikm->status_verifikasi);
        $this->assertEquals('Usaha aktif beroperasi setiap hari', $ikm->catatan_petugas);
        $this->assertNotNull($ikm->verified_at);
        $this->assertEquals($petugas->id, $ikm->verified_by);
        $this->assertIsArray($ikm->data_verifikasi);

        // Cek struktur JSON
        $this->assertEquals('0.540123, 123.059421', $ikm->getJawabanVerifikasi('koordinat_lokasi'));
        $this->assertEquals('Aktif Berproduksi', $ikm->getJawabanVerifikasi('status_operasional'));

        // Cek file tersimpan di storage
        $storedFilePath = $ikm->getJawabanVerifikasi('foto_tempat_usaha');
        $this->assertNotNull($storedFilePath);
        Storage::disk('public')->assertExists($storedFilePath);
    }
}
