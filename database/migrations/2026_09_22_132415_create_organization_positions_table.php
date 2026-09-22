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
        Schema::create('organization_positions', function (Blueprint $table) {
            $table->id();

            // Child dari Organization (1 organisasi = 1 kelurahan), bukan lewat village_id
            // langsung — konsisten dengan pola GalleryPhoto/PotensiWisataPhoto (child
            // mengikuti scope parent-nya).
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();

            // Bebas berapa pun levelnya, TIDAK dibatasi di kode — beda kelurahan boleh
            // beda jumlah level sesuai kondisi riil.
            $table->unsignedTinyInteger('level');

            $table->string('label');           // "Kasi Pemerintahan", "PPPK Paruh Waktu - Staf Kasi Kesra"
            $table->string('name')->nullable(); // nama pejabat
            $table->unsignedInteger('order')->default(0); // urutan tampil dalam 1 level

            $table->timestamps();
            $table->index(['organization_id', 'level']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organization_positions');
    }
};
