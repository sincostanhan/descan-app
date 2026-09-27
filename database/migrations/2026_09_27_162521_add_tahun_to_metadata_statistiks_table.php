<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tahun data metadata (bukan tanggal upload) — dipakai filter dropdown tahun di halaman publik.
     * Nullable supaya data lama tetap valid; Admin wajib mengisinya saat edit berikutnya
     * (lihat UpdateMetadataStatistikRequest).
     */
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('metadata_statistiks', function (Blueprint $table) {
            $table->unsignedSmallInteger('tahun')->nullable()->after('title')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('metadata_statistiks', function (Blueprint $table) {
            $table->dropIndex(['tahun']);
            $table->dropColumn('tahun');
        });
    }
};
