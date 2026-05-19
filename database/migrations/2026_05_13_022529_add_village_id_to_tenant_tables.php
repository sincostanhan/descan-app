<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Daftar tabel yang membutuhkan village_id
    protected $tables = [
        'users', 'histories', 'abouts', 'organizations', 
        'galleries', 'publications', 'infographics', 
        'statistical_tables', 'settings', 'homes'
    ];
    
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Schema::table('tenant_tables', function (Blueprint $table) {
        //     //
        // });
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                // Menambahkan foreign key nullable (untuk antisipasi data lama)
                $table->foreignId('village_id')->nullable()->constrained('villages')->cascadeOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::table('tenant_tables', function (Blueprint $table) {
        //     //
        // });
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropForeign(['village_id']);
                $table->dropColumn('village_id');
            });
        }
    }
};
