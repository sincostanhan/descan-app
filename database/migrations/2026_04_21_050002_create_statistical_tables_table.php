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
        Schema::create('statistical_tables', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Untuk Judul
            $table->string('slug')->unique(); // Untuk URL ramah SEO
            $table->string('publication'); // Nama Publikasi
            $table->integer('chapter'); // Bab ke-X
            $table->json('columns'); // Header tabel
            $table->json('content'); // Isi data tabel
            $table->text('description')->nullable(); // Keterangan tabel jika ada
            $table->string('source')->nullable();    // Sumber data
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('statistical_tables');
    }
};
