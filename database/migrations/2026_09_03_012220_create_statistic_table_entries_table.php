<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Satu baris di tabel ini = satu "instance" template yang sudah dipilih & mulai diisi
     * oleh Admin Kelurahan tertentu (menggantikan alur upload Excel lama pada 'statistical_tables').
     */
    public function up(): void
    {
        Schema::create('statistic_table_entries', function (Blueprint $table) {
            $table->id();

            $table->foreignId('village_id')->constrained()->cascadeOnDelete();

            // restrictOnDelete: BPS tidak boleh menghapus template jika masih ada Kelurahan
            // yang sudah mengisi datanya, mencegah kehilangan data secara tidak sengaja.
            $table->foreignId('statistic_template_id')->constrained()->restrictOnDelete();

            $table->string('title')->nullable(); // Opsional: override judul tampilan, default ke judul template
            $table->string('source')->nullable(); // Sumber data (isian Admin Kelurahan)
            $table->text('description')->nullable(); // Keterangan tambahan (isian Admin Kelurahan)

            $table->timestamps();

            // Satu kelurahan hanya boleh punya satu entry aktif per template
            $table->unique(['village_id', 'statistic_template_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('statistic_table_entries');
    }
};
