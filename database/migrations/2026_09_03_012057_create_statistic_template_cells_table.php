<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Titik temu (intersection) antara satu leaf header baris dan satu leaf header kolom.
     * Inilah representasi "sel" nyata pada grid yang boleh diisi Admin Kelurahan.
     */
    public function up(): void
    {
        Schema::create('statistic_template_cells', function (Blueprint $table) {
            $table->id();

            $table->foreignId('statistic_template_id')->constrained()->cascadeOnDelete();

            // Wajib mengacu ke header dengan is_leaf = true (divalidasi di Action Class)
            $table->foreignId('row_header_id')->constrained('statistic_template_headers')->restrictOnDelete();
            $table->foreignId('column_header_id')->constrained('statistic_template_headers')->restrictOnDelete();

            // Sel terkunci (misal kolom "Total") tidak bisa diedit manual oleh Admin Kelurahan,
            // biasanya dihitung otomatis di frontend/backend.
            $table->boolean('is_locked')->default(false);

            $table->timestamps();

            $table->unique(['row_header_id', 'column_header_id'], 'unique_row_column_cell');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('statistic_template_cells');
    }
};
