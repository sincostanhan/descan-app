<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Nilai aktual yang diinput Admin Kelurahan per sel. Diikat ke 'template_cell_id'
     * (bukan langsung ke row_id + column_id terpisah) agar penambahan kolom/baris baru
     * oleh BPS tidak pernah menyentuh baris data yang sudah ada di sini (auto-sync/cascade).
     */
    public function up(): void
    {
        Schema::create('statistic_table_values', function (Blueprint $table) {
            $table->id();

            $table->foreignId('statistic_table_entry_id')->constrained('statistic_table_entries')->cascadeOnDelete();
            $table->foreignId('statistic_template_cell_id')->constrained('statistic_template_cells')->restrictOnDelete();

            $table->text('value')->nullable(); // Disimpan sebagai text, cast tipe dilakukan di Model sesuai data_type header kolom

            $table->timestamps();

            $table->unique(['statistic_table_entry_id', 'statistic_template_cell_id'], 'unique_entry_cell_value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('statistic_table_values');
    }
};
