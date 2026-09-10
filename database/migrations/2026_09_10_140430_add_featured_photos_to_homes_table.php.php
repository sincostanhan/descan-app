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
            $table->foreignId('featured_gallery_photo_id')->nullable()
                ->after('show_tim')->constrained('gallery_photos')->nullOnDelete();
            $table->foreignId('featured_potensi_wisata_photo_id')->nullable()
                ->after('featured_gallery_photo_id')->constrained('potensi_wisata_photos')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('homes', function (Blueprint $table) {
            $table->dropForeign(['featured_gallery_photo_id']);
            $table->dropForeign(['featured_potensi_wisata_photo_id']);
            $table->dropColumn(['featured_gallery_photo_id', 'featured_potensi_wisata_photo_id']);
        });
    }
};
