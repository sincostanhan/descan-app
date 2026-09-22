<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * ADDITIVE — kolom lama di 'organizations' TIDAK diubah/dihapus.
     * Menampung jabatan staf yang tidak mengikuti struktur staf tetap Kelurahan Baadia
     * (Bendahara Barang, Bendahara Pembantu Pengeluaran, Pengurus Barang, PPPK Paruh Waktu,
     * Analis Pengawasan Masyarakat, dst — jenis & jumlahnya bervariasi antar Kelurahan,
     * jadi disimpan sebagai daftar {jabatan, nama} yang bebas panjangnya, bukan kolom tetap).
     */
    public function up(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->json('staf_tambahan')->nullable()->after('pengelola_surat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropColumn('staf_tambahan');
        });
    }
};