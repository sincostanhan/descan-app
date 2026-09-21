<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('metadata_statistiks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('file_path');
            // cover
            $table->string('cover_path')->nullable();
            // village_id langsung disertakan saat create (mengikuti pola terbaru, mis. potensi_wisatas),
            // tidak perlu migration tambahan seperti publications/infographics dulu
            $table->foreignId('village_id')->nullable()->constrained('villages')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('metadata_statistiks');
    }
};