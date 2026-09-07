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
        Schema::table('statistic_template_logs', function (Blueprint $table) {
            // Sengaja TANPA foreign key constraint. Header yang di-soft-delete tetap ada secara fisik
            // (untuk fitur Pulihkan), tapi kita tidak mau riwayat log ini rusak/ikut kena efek
            // apa pun kalau suatu saat siklus hidup header berubah. Validitas ID dicek manual
            // di Action (RestoreTemplateHeader), bukan mengandalkan constraint DB.
            $table->unsignedBigInteger('affected_header_id')->nullable()->after('statistic_template_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('statistic_template_logs', function (Blueprint $table) {
            $table->dropColumn('affected_header_id');
        });
    }
};
