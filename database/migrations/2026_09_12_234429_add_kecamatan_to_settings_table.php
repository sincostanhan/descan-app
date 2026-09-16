<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Ditambahkan untuk Dashboard Peta Publik: popup peta perlu menampilkan Nama Kecamatan,
     * tapi tidak ada field terstruktur untuk itu di Village/Organization/Setting sebelumnya
     * (yang ada cuma teks bebas di About.deskripsi, tidak bisa di-query).
     */
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('kecamatan')->nullable()->after('village_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('kecamatan');
        });
    }
};
