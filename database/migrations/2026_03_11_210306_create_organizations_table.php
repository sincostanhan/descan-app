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
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();

            $table->string('lurah')->nullable();
            $table->string('sekretaris_lurah')->nullable();
            $table->string('kasi_pemerintahan')->nullable();
            $table->string('kasi_ekonomi')->nullable();
            $table->string('kasi_ketentraman')->nullable();

            $table->string('analis_pembangunan')->nullable();
            $table->string('pranata_barang')->nullable();
            $table->string('pengelola_keamanan')->nullable();
            $table->string('pengadministrasian_umum')->nullable();
            $table->string('pengadministrasian_pemerintahan')->nullable();
            $table->string('pengelola_surat')->nullable();

            $table->json('daftar_rw')->nullable();
            $table->json('daftar_rt')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
