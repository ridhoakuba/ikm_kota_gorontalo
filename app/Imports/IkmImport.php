<?php

namespace App\Imports;

use App\Models\DataIkm;
use App\Models\Kelurahan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class IkmImport implements ToModel, WithHeadingRow
{
    /**
     * Transformasi setiap baris Excel menjadi model DataIkm.
     *
     * Kolom Excel yang dibaca:
     *   - nib
     *   - nama_proyek      → disimpan ke nama_usaha
     *   - nama_perusahaan  → disimpan ke nama_pemilik
     *   - alamat_usaha
     *   - kelurahan_usaha
     *   - kecamatan_usaha  → disimpan ke kelurahans.kecamatan
     *   - judul_kbli
     */
    public function model(array $row): ?DataIkm
    {
        // Lewati baris jika kolom 'nib' kosong
        if (empty($row['nib'])) {
            return null;
        }

        // Bersihkan nilai kecamatan dari Excel
        $kecamatan = isset($row['kecamatan_usaha']) ? trim($row['kecamatan_usaha']) : null;

        // 1. Cari atau buat Kelurahan berdasarkan nama kelurahan dari Excel.
        //    Jika dibuat baru, langsung isi kecamatan sekaligus.
        $kelurahan = Kelurahan::firstOrCreate(
            ['nama_kelurahan' => trim($row['kelurahan_usaha'] ?? '')],
            ['kecamatan' => $kecamatan ?: null]
        );

        // Jika kelurahan sudah ada tapi kecamatannya masih kosong, update sekarang.
        if (empty($kelurahan->kecamatan) && !empty($kecamatan)) {
            $kelurahan->update(['kecamatan' => $kecamatan]);
        }

        // 2. Cek apakah data dengan NIB ini sudah ada di database
        $existing = DataIkm::where('nib', $row['nib'])->first();

        // 3. Tentukan status_verifikasi:
        //    - Jika data sudah ada → pertahankan status yang ada
        //    - Jika data baru → default 'menunggu'
        $statusVerifikasi = $existing
            ? $existing->status_verifikasi
            : DataIkm::STATUS_MENUNGGU;

        // 4. Update atau buat data IKM menggunakan kunci 'nib'
        DataIkm::updateOrCreate(
            ['nib' => (string) $row['nib']],
            [
                'kelurahan_id'      => $kelurahan->id,
                'nama_usaha'        => trim($row['nama_proyek'] ?? ''),
                'nama_pemilik'      => trim($row['nama_perusahaan'] ?? ''),
                'alamat'            => trim($row['alamat_usaha'] ?? ''),
                'jenis_usaha'       => trim($row['judul_kbli'] ?? ''),
                'status_verifikasi' => $statusVerifikasi,
            ]
        );

        // Return null karena kita sudah handle insert/update secara manual
        // untuk menghindari duplikasi dari ToModel
        return null;
    }
}
