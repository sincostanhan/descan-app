<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Pivot status "sudah dibaca" per Kelurahan untuk tiap log perubahan template,
     * agar badge notifikasi di admin kelurahan bisa hilang setelah dibuka.
     */
    public function up(): void
    {
        Schema::create('statistic_template_log_reads', function (Blueprint $table) {
            $table->id();

            $table->foreignId('statistic_template_log_id')->constrained('statistic_template_logs')->cascadeOnDelete();
            $table->foreignId('village_id')->constrained()->cascadeOnDelete();

            $table->timestamp('read_at')->nullable();
            
            $table->unique(['statistic_template_log_id', 'village_id'], 'unique_log_village_read');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('statistic_template_log_reads');
    }
};
