<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah dukungan "Grafik Kategori" (hitung frekuensi kolom teks, mis. "Ada"/"Tidak ada")
     * yang independen dari grafik numerik (Sumbu Y) yang sudah ada — admin bisa aktifkan
     * keduanya sekaligus di 1 grafik yang sama, atau cuma salah satu.
     *
     * chart_type/x_axis_column/y_axis_columns dibuat nullable karena sekarang boleh ada
     * chart yang HANYA berisi grafik kategori (tanpa grafik numerik sama sekali).
     *
     * CATATAN: ->change() butuh paket doctrine/dbal. Kalau muncul error
     * "Please install doctrine/dbal", jalankan dulu: composer require doctrine/dbal
     */
    public function up(): void
    {
        Schema::table('statistic_charts', function (Blueprint $table) {
            $table->json('category_columns')->nullable()->after('y_axis_colors');
        });

        Schema::table('statistic_charts', function (Blueprint $table) {
            $table->string('chart_type')->nullable()->change();
            $table->string('x_axis_column')->nullable()->change();
            $table->json('y_axis_columns')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('statistic_charts', function (Blueprint $table) {
            $table->dropColumn('category_columns');
            $table->string('chart_type')->nullable(false)->change();
            $table->string('x_axis_column')->nullable(false)->change();
            $table->json('y_axis_columns')->nullable(false)->change();
        });
    }
};