<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Proyek belum production (belum ada data riil dari Admin Kelurahan),
     * sehingga alur upload Excel & tabel lama dihapus total, digantikan
     * sepenuhnya oleh skema template baru (statistic_templates, dst).
     */
    public function up(): void
    {
        // 1. Lepas dulu FK & kolom lama di statistic_charts yang mengacu ke statistical_tables
        Schema::table('statistic_charts', function (Blueprint $table) {
            $table->dropForeign(['statistical_table_id']);
            $table->dropColumn('statistical_table_id');
        });

        // 2. Baru drop tabel-nya
        Schema::dropIfExists('statistical_tables');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('statistical_tables', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('publication');
            $table->integer('chapter');
            $table->json('columns');
            $table->json('content');
            $table->text('description')->nullable();
            $table->string('source')->nullable();
            $table->foreignId('village_id')->nullable()->constrained()->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::table('statistic_charts', function (Blueprint $table) {
            $table->foreignId('statistical_table_id')->nullable()->constrained()->cascadeOnDelete();
        });
    }
};
