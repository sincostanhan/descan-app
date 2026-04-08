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
        Schema::create('gallery_photos', function (Blueprint $table) {
            $table->id();

            // cascadeOnDelete memastikan jika kegiatan dihapus, fotonya di database ikut terhapus
            $table->foreignId('gallery_id')->constrained('galleries')->cascadeOnDelete();
            $table->string('foto_path');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gallery_photos');
    }
};
