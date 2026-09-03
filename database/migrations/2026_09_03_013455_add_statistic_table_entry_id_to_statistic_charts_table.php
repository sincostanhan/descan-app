<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Grafik (statistic_charts) sekarang bisa bersumber dari tabel template BARU
     * ('statistic_table_entries') maupun tabel Excel LAMA ('statistical_tables').
     * Kedua kolom foreign key dibuat nullable agar tidak breaking terhadap chart lama
     * yang masih mengacu ke 'statistical_table_id'. Logika & tampilan grafik itu sendiri TIDAK diubah.
     */
    public function up(): void
    {
        Schema::table('statistic_charts', function (Blueprint $table) {
            $table->foreignId('statistic_table_entry_id')
                ->nullable()
                ->after('statistical_table_id')
                ->constrained('statistic_table_entries')
                ->cascadeOnDelete();
        });

        // // Kolom lama dibuat nullable karena chart baru tidak lagi wajib mengisi ini
        // Schema::table('statistic_charts', function (Blueprint $table) {
        //     $table->foreignId('statistical_table_id')->nullable()->change();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('statistic_charts', function (Blueprint $table) {
            $table->dropForeign(['statistic_table_entry_id']);
            $table->dropColumn('statistic_table_entry_id');
            // $table->foreignId('statistical_table_id')->nullable(false)->change();
        });
    }
};
