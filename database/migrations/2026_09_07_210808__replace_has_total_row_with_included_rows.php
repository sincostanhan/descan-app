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
        Schema::table('statistic_charts', function (Blueprint $table) {
            // Menyimpan INDEX baris (0-based, sesuai urutan di StatisticTableEntry->content)
            // yang dipilih untuk ditampilkan di grafik. null/kosong = tampilkan semua baris.
            $table->json('included_rows')->nullable()->after('y_axis_colors');
            $table->dropColumn('has_total_row');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('statistic_charts', function (Blueprint $table) {
            $table->dropColumn('included_rows');
            $table->boolean('has_total_row')->default(false);
        });
    }
};
