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
        Schema::table('statistic_templates', function (Blueprint $table) {
            // 'manual' = BPS menyusun baris sendiri di form builder, SAMA untuk semua Kelurahan.
            // 'rt_rw'  = baris "RT 00X RW 00X" digenerate OTOMATIS per Kelurahan dari
            //            organizations.daftar_rt. BPS tidak menyusun & Kelurahan tidak bisa mengedit baris ini.
            $table->enum('row_source', ['manual', 'rt_rw'])->default('manual')->after('is_mapped');
        });

        Schema::table('statistic_template_headers', function (Blueprint $table) {
            // NULL   = header milik TEMPLATE (shared ke semua Kelurahan) — kolom selalu begini, baris mode manual juga begini.
            // Terisi = header eksklusif milik SATU Kelurahan — dipakai baris mode rt_rw.
            $table->foreignId('village_id')->nullable()->after('statistic_template_id')->constrained()->cascadeOnDelete();
        });

        Schema::table('statistic_template_cells', function (Blueprint $table) {
            $table->foreignId('village_id')->nullable()->after('statistic_template_id')->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('statistic_template_cells', function (Blueprint $table) {
            $table->dropForeign(['village_id']);
            $table->dropColumn('village_id');
        });

        Schema::table('statistic_template_headers', function (Blueprint $table) {
            $table->dropForeign(['village_id']);
            $table->dropColumn('village_id');
        });

        Schema::table('statistic_templates', function (Blueprint $table) {
            $table->dropColumn('row_source');
        });
    }
};
