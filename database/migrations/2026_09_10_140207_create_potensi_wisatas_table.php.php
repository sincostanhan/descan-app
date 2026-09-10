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
        Schema::create('potensi_wisatas', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->enum('kategori', ['situs_bersejarah', 'umum'])->default('umum');
            $table->foreignId('village_id')->nullable()->constrained('villages')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('potensi_wisatas');
    }
};
