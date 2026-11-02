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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['industri', 'kelurahan'])->default('kelurahan')->after('password');
            $table->foreignId('kelurahan_id')->nullable()->after('role')->constrained('kelurahans')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['kelurahan_id']);
            $table->dropColumn(['role', 'kelurahan_id']);
        });
    }
};
