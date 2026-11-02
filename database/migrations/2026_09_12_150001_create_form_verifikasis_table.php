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
        Schema::create('form_verifikasis', function (Blueprint $table) {
            $table->id();
            $table->string('nama_instrumen');
            $table->string('slug')->unique();
            $table->text('deskripsi')->nullable();
            $table->enum('tipe_input', ['text', 'file', 'koordinat', 'select'])->default('text');
            $table->json('opsi_pilihan')->nullable();
            $table->boolean('is_required')->default(true);
            $table->integer('urutan')->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_verifikasis');
    }
};
