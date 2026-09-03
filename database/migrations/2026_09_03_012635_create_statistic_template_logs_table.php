<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Mencatat setiap perubahan struktur template oleh Admin BPS,
     * ditampilkan sebagai notifikasi/log ke Admin Kelurahan.
     */
    public function up(): void
    {
        Schema::create('statistic_template_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('statistic_template_id')->constrained()->cascadeOnDelete();
            $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();

            // Contoh nilai: column_added, column_removed, row_added, row_removed, header_relabeled
            $table->string('change_type');

            $table->text('description'); // Ringkasan perubahan dalam bahasa manusia (untuk ditampilkan di modal)

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('statistic_template_logs');
    }
};
