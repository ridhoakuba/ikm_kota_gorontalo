<?php

use App\Imports\IkmImport;
use App\Models\DataIkm;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Backfill kode_kbli untuk data IKM yang sudah diimpor sebelum kolom kode_kbli tersedia.
     */
    public function up(): void
    {
        $jenisList = DataIkm::whereNull('kode_kbli')
            ->whereNotNull('jenis_usaha')
            ->where('jenis_usaha', '!=', '')
            ->distinct()
            ->pluck('jenis_usaha');

        foreach ($jenisList as $jenis) {
            $kode = IkmImport::resolveKodeKbliFromJudul($jenis);
            if ($kode !== null) {
                DataIkm::whereNull('kode_kbli')
                    ->where('jenis_usaha', $jenis)
                    ->update(['kode_kbli' => $kode]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tidak menghapus kode_kbli yang sudah terisi
    }
};

