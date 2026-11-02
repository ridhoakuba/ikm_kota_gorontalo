<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('data_ikms', function (Blueprint $table) {
            $table->text('catatan_petugas')->nullable()->after('status_verifikasi');
            $table->boolean('ditindaklanjuti')->default(false)->after('catatan_petugas');
        });
    }

    public function down(): void
    {
        Schema::table('data_ikms', function (Blueprint $table) {
            $table->dropColumn(['catatan_petugas', 'ditindaklanjuti']);
        });
    }
};
