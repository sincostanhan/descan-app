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
        Schema::table('gallery_photos', function (Blueprint $table) {
            $table->boolean('tampil_beranda')->default(false)->after('foto_path');
        });
        Schema::table('potensi_wisata_photos', function (Blueprint $table) {
            $table->boolean('tampil_beranda')->default(false)->after('foto_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gallery_photos', function (Blueprint $table) {
            $table->dropColumn('tampil_beranda');
        });
        Schema::table('potensi_wisata_photos', function (Blueprint $table) {
            $table->dropColumn('tampil_beranda');
        });
    }
};
