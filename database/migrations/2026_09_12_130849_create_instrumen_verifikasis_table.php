<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('instrumen_verifikasis', function (Blueprint $table) {
            $table->id();
            $table->string('nama_instrumen');
            $table->text('deskripsi')->nullable();
            $table->enum('tipe', ['teks', 'foto', 'koordinat', 'pilihan'])->default('teks');
            $table->boolean('is_wajib')->default(true);
            $table->integer('urutan')->default(0);
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('instrumen_verifikasis');
    }
};
