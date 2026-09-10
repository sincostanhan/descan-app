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
            $table->dropForeign(['featured_gallery_photo_id']);
            $table->dropForeign(['featured_potensi_wisata_photo_id']);
            $table->dropColumn(['featured_gallery_photo_id', 'featured_potensi_wisata_photo_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('homes', function (Blueprint $table) {
            //
        });
    }
};
