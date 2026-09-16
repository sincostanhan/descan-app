<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Menggantikan wizard "initial setup" (SetupController) yang di-drop: begitu Admin BPS
     * membuat Kelurahan baru, situs publiknya default TIDAK ter-publish (false) sampai
     * Admin Kelurahan sendiri yang menekan tombol "Publish Website" di halaman Pengaturan,
     * setelah mengisi konten secukupnya.
     */
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->boolean('is_published')->default(false)->after('theme_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('is_published');
        });
    }
};
