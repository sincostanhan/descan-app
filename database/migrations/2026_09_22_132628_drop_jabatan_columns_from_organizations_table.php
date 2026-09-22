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
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropColumn([
                'kasi_pemerintahan', 'kasi_ekonomi', 'kasi_ketentraman',
                'analis_pembangunan', 'pranata_barang', 'pengelola_keamanan',
                'pengadministrasian_umum', 'pengadministrasian_pemerintahan', 'pengelola_surat',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->string('kasi_pemerintahan')->nullable();
            $table->string('kasi_ekonomi')->nullable();
            $table->string('kasi_ketentraman')->nullable();
            $table->string('analis_pembangunan')->nullable();
            $table->string('pranata_barang')->nullable();
            $table->string('pengelola_keamanan')->nullable();
            $table->string('pengadministrasian_umum')->nullable();
            $table->string('pengadministrasian_pemerintahan')->nullable();
            $table->string('pengelola_surat')->nullable();
        });
    }
};
