<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('data_ikms', function (Blueprint $table) {
            $table->json('data_verifikasi')->nullable()->after('catatan_petugas');
            $table->timestamp('verified_at')->nullable()->after('data_verifikasi');
            $table->foreignId('verified_by')->nullable()->after('verified_at')->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_ikms', function (Blueprint $table) {
            $table->dropForeign(['verified_by']);
            $table->dropColumn(['data_verifikasi', 'verified_at', 'verified_by']);
        });
    }
};
