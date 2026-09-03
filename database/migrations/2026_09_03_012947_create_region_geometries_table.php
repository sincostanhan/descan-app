<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Menyimpan poligon GeoJSON per RT/RW suatu kelurahan, dipakai Dashboard Peta Publik.
     * Data RT/RW itu sendiri (nomor RT, nomor RW, nama ketua) tetap bersumber dari
     * tabel 'organizations' yang sudah ada — tabel ini HANYA menyimpan bentuk geometrinya.
     */
    public function up(): void
    {
        Schema::create('region_geometries', function (Blueprint $table) {
            $table->id();

            $table->foreignId('village_id')->constrained()->cascadeOnDelete();

            $table->string('rt'); // Harus match dengan nilai 'rt' pada organizations.daftar_rt
            $table->string('rw'); // Harus match dengan nilai 'rw' pada organizations.daftar_rt

            $table->json('geojson'); // Geometry (Polygon/MultiPolygon) hasil digitasi peta wilayah RT tsb

            $table->timestamps();

            $table->unique(['village_id', 'rt', 'rw']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('region_geometries');
    }
};
