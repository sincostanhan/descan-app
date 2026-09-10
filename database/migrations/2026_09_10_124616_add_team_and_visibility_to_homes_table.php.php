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
        Schema::table('homes', function (Blueprint $table) {
            $table->longText('tim_kelurahan')->nullable()->after('output');

            $table->boolean('show_latar_belakang')->default(true)->after('tim_kelurahan');
            $table->boolean('show_tujuan')->default(true)->after('show_latar_belakang');
            $table->boolean('show_output')->default(true)->after('show_tujuan');
            $table->boolean('show_tim')->default(true)->after('show_output');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('homes', function (Blueprint $table) {
            $table->dropColumn([
                'tim_kelurahan',
                'show_latar_belakang',
                'show_tujuan',
                'show_output',
                'show_tim',
            ]);
        });
    }
};
