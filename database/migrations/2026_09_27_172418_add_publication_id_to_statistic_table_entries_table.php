<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Relasi one-to-many: 1 Publikasi punya banyak tabel, 1 tabel maksimal milik 1 Publikasi.
     * nullable  = tabel boleh tidak masuk publikasi mana pun.
     * nullOnDelete = publikasi dihapus -> tabelnya TETAP ada, hanya lepas dari publikasi.
     */
    public function up(): void
    {
        Schema::table('statistic_table_entries', function (Blueprint $table) {
            $table->foreignId('publication_id')
                ->nullable()
                ->after('statistic_template_id')
                ->constrained('publications')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('statistic_table_entries', function (Blueprint $table) {
            $table->dropConstrainedForeignId('publication_id');
        });
    }
};
