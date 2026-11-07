<?php

use App\Imports\IkmImport;
use App\Models\DataIkm;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Bersihkan & normalisasi kode_kbli pada data_ikms:
     * 1. Ubah kode_kbli yang memiliki sufiks (seperti 16221v2025) menjadi 5 digit murni (16221).
     * 2. Isi kode_kbli yang masih null/kosong (termasuk "Industri Barang Bangunan Dari Kayu" -> 16221).
     */
    public function up(): void
    {
        // 1. Normalisasi kode_kbli yang sudah terisi namun memiliki sufiks / panjang > 5
        $nonStandard = DataIkm::whereNotNull('kode_kbli')
            ->where('kode_kbli', '!=', '')
            ->select('kode_kbli')
            ->distinct()
            ->pluck('kode_kbli');

        foreach ($nonStandard as $rawKode) {
            $cleanKode = IkmImport::sanitizeKodeKbli((string) $rawKode);
            if ($cleanKode !== null && $cleanKode !== $rawKode) {
                DataIkm::where('kode_kbli', $rawKode)
                    ->update(['kode_kbli' => $cleanKode]);
            }
        }

        // 2. Isi baris yang kode_kbli-nya masih null atau kosong berdasarkan jenis_usaha
        $emptyKodeJenis = DataIkm::where(fn ($q) => $q->whereNull('kode_kbli')->orWhere('kode_kbli', ''))
            ->whereNotNull('jenis_usaha')
            ->where('jenis_usaha', '!=', '')
            ->select('jenis_usaha')
            ->distinct()
            ->pluck('jenis_usaha');

        foreach ($emptyKodeJenis as $jenis) {
            $cleanKode = IkmImport::sanitizeKodeKbli(null, (string) $jenis);
            if ($cleanKode !== null) {
                DataIkm::where(fn ($q) => $q->whereNull('kode_kbli')->orWhere('kode_kbli', ''))
                    ->where('jenis_usaha', $jenis)
                    ->update(['kode_kbli' => $cleanKode]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tidak membatalkan normalisasi data
    }
};

