<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * 2 toggle khusus mode row_source='rt_rw' — BPS aktifkan sekali di level Template, lalu
     * Total per RW / Total Kelurahan dihitung otomatis (SUM live dari nilai RT yang sudah ada,
     * lihat App\Actions\ComputeRtRwSubtotals) setiap kali tabel ditampilkan. TIDAK PERNAH
     * disimpan sebagai baris/nilai tersendiri, supaya tidak pernah nyasar dari isian asli.
     */
    public function up(): void
    {
        Schema::table('statistic_templates', function (Blueprint $table) {
            $table->boolean('show_rw_subtotal')->default(false)->after('row_source');
            $table->boolean('show_kelurahan_total')->default(false)->after('show_rw_subtotal');
        });
    }

    public function down(): void
    {
        Schema::table('statistic_templates', function (Blueprint $table) {
            $table->dropColumn(['show_rw_subtotal', 'show_kelurahan_total']);
        });
    }
};