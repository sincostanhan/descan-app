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
        Schema::table('statistic_template_headers', function (Blueprint $table) {
            $table->string('rw_value')->nullable()->after('rt_value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('statistic_template_headers', function (Blueprint $table) {
            $table->dropColumn('rw_value');
        });
    }
};
