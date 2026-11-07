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
            if (!Schema::hasColumn('data_ikms', 'kode_kbli')) {
                $table->string('kode_kbli', 20)->nullable()->after('alamat');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_ikms', function (Blueprint $table) {
            if (Schema::hasColumn('data_ikms', 'kode_kbli')) {
                $table->dropColumn('kode_kbli');
            }
        });
    }
};

