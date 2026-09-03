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
        Schema::create('statistic_templates', function (Blueprint $table) {
            $table->id();

            $table->string('title'); // Judul Template (misal: "Tabel 1. Jumlah Penduduk")
            $table->text('description')->nullable(); // Deskripsi/petunjuk pengisian dari BPS

            // Toggle utama: menentukan apakah template ini boleh muncul di dropdown Dashboard Peta Publik.
            // Hanya bisa di-set true jika struktur baris memiliki minimal satu node ber-rt_value (divalidasi di Action Class, bukan di DB).
            $table->boolean('is_mapped')->default(false);

            // Status aktif/nonaktif template secara umum (soft toggle, bukan penghapusan)
            $table->boolean('is_active')->default(true);

            // Dibuat oleh Admin BPS mana. Nullable + nullOnDelete agar histori template tidak ikut hilang jika user BPS dihapus.
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes(); // Soft delete: template tidak boleh hilang permanen selama masih dipakai kelurahan
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('statistic_templates');
    }
};
