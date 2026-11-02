<?php

namespace Database\Seeders;

use App\Models\InstrumenVerifikasi;
use Illuminate\Database\Seeder;

class InstrumenVerifikasiSeeder extends Seeder
{
    public function run(): void
    {
        $instrumens = [
            [
                'nama_instrumen' => 'Status Operasional Usaha',
                'deskripsi'      => 'Pilih status terkini: Aktif, Pindah Lokasi, atau Gulung Tikar',
                'tipe'           => 'pilihan',
                'is_wajib'       => true,
                'urutan'         => 1,
                'aktif'          => true,
            ],
            [
                'nama_instrumen' => 'Foto Tempat Usaha',
                'deskripsi'      => 'Upload minimal 1 foto kondisi fisik tempat usaha terkini',
                'tipe'           => 'foto',
                'is_wajib'       => true,
                'urutan'         => 2,
                'aktif'          => true,
            ],
            [
                'nama_instrumen' => 'Upload Foto KTP Pemilik',
                'deskripsi'      => 'Foto KTP pemilik usaha yang masih berlaku',
                'tipe'           => 'foto',
                'is_wajib'       => true,
                'urutan'         => 3,
                'aktif'          => true,
            ],
            [
                'nama_instrumen' => 'Koordinat / Lokasi GPS Terbaru',
                'deskripsi'      => 'Titik koordinat GPS lokasi usaha saat verifikasi lapangan',
                'tipe'           => 'koordinat',
                'is_wajib'       => true,
                'urutan'         => 4,
                'aktif'          => true,
            ],
            [
                'nama_instrumen' => 'Catatan Tambahan Petugas',
                'deskripsi'      => 'Keterangan atau catatan dari petugas kelurahan (opsional)',
                'tipe'           => 'teks',
                'is_wajib'       => false,
                'urutan'         => 5,
                'aktif'          => true,
            ],
        ];

        foreach ($instrumens as $item) {
            InstrumenVerifikasi::updateOrCreate(
                ['nama_instrumen' => $item['nama_instrumen']],
                $item
            );
        }

        $this->command->info('✓ Instrumen verifikasi berhasil di-seed (' . count($instrumens) . ' item)');
    }
}
