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
        Schema::create('statistic_charts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('village_id')->constrained()->cascadeOnDelete();
            
            // 1. Relasi ke tabel statistik yang sudah diupload
            $table->foreignId('statistical_table_id')->constrained()->cascadeOnDelete();

            $table->string('title'); // Judul Grafik
            $table->string('chart_type'); // Tipe: pie, doughnut, bar, bar_stacked, column, line, dll
            
            // 2. Pemetaan Sumbu
            $table->string('x_axis_column'); // Menyimpan nama kolom untuk Sumbu X (contoh: "nama_rt" atau "tahun")
            $table->json('y_axis_columns'); // Menyimpan nama kolom untuk Sumbu Y (JSON, karena admin bisa pilih lebih dari 1 kolom untuk model Stacked/Clustered.)
                        
            $table->json('y_axis_colors')->nullable();

            $table->boolean('has_total_row')->default(false);
            $table->boolean('is_active')->default(true); // Status tampil di publik
        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('statistic_charts');
    }
};
