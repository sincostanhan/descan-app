<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Tabel ini menyimpan header KOLOM dan header BARIS dalam satu tabel yang sama,
     * dibedakan lewat kolom 'axis'. Struktur berbentuk TREE (self-referencing 'parent_id')
     * agar mendukung header bertingkat (multi-kolom kunci / multi-baris kunci)
     * serta rowspan & colspan (dihitung on-the-fly dari jumlah leaf descendant, tidak disimpan statis).
     */
    public function up(): void
    {
        Schema::create('statistic_template_headers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('statistic_template_id')->constrained()->cascadeOnDelete();

            // 'row'    = header di sisi kiri tabel (bisa multi-level, misal Kecamatan > Kelurahan > RT)
            // 'column' = header di bagian atas tabel (bisa multi-level, misal Tahun > Bulan)
            $table->enum('axis', ['row', 'column']);

            // Self-referencing untuk membentuk hierarki header bertingkat
            $table->foreignId('parent_id')->nullable()->constrained('statistic_template_headers')->cascadeOnDelete();

            $table->string('label'); // Teks yang ditampilkan di tabel (contoh: "RT 01", "Laki-laki")
            $table->string('key')->nullable(); // Slug unik, wajib diisi jika is_leaf = true (dipakai sistem, bukan tampilan)

            // Tipe data HANYA relevan untuk axis='column' & is_leaf=true.
            // Menentukan validasi input Admin Kelurahan pada kolom tsb.
            $table->enum('data_type', ['numeric', 'text', 'both'])->nullable();

            // true = node ini adalah titik ujung (leaf) yang benar-benar berpotongan dengan grid data.
            // false = node ini murni header pengelompok (parent), tidak punya sel data langsung.
            $table->boolean('is_leaf')->default(false);

            // HANYA dipakai di axis='row'. Diisi MANUAL oleh Admin BPS pada level manapun di tree
            // (boleh di level leaf atau parent), menandai bahwa node ini merepresentasikan sebuah RT.
            // Dipakai untuk sinkronisasi Dashboard Peta. Tidak di-generate otomatis dari 'label'.
            $table->string('rt_value')->nullable();

            $table->unsignedInteger('order')->default(0); // Urutan tampil di level yang sama

            $table->timestamps();
            $table->softDeletes(); // Soft delete: hapus kolom/baris oleh BPS tidak menghilangkan nilai historis Kelurahan
        });

        Schema::table('statistic_template_headers', function (Blueprint $table) {
            $table->index(['statistic_template_id', 'axis']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('statistic_template_headers');
    }
};
