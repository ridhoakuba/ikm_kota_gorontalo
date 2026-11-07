<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * - Legalitas usaha disimpan sebagai nomor sertifikat/izin (NULL = belum ada).
     * - updated_by_role & last_verified_by menyimpan riwayat audit pembaruan terakhir.
     */
    public function up(): void
    {
        Schema::table('data_ikms', function (Blueprint $table) {
            $table->string('sertifikat_halal')->nullable()->after('jenis_usaha');
            $table->string('izin_bpom')->nullable()->after('sertifikat_halal');
            $table->string('pirt')->nullable()->after('izin_bpom');

            $table->enum('updated_by_role', ['kelurahan', 'industri'])->nullable()->after('verified_by');
            $table->foreignId('last_verified_by')->nullable()->after('updated_by_role')
                ->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_ikms', function (Blueprint $table) {
            $table->dropForeign(['last_verified_by']);
            $table->dropColumn([
                'sertifikat_halal',
                'izin_bpom',
                'pirt',
                'updated_by_role',
                'last_verified_by',
            ]);
        });
    }
};

