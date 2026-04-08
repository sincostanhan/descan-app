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
        Schema::create('abouts', function (Blueprint $table) {
            $table->id();

            $table->text('deskripsi')->nullable();
            // Kolom untuk batas wilayah
            $table->string('batas_utara')->nullable();
            $table->string('batas_barat')->nullable();
            $table->string('batas_selatan')->nullable();
            $table->string('batas_timur')->nullable();
            // Kolom untuk visi & misi
            $table->string('visi')->nullable();
            $table->text('misi')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('abouts');
    }
};
