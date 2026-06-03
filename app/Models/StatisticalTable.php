<?php

namespace App\Models;

use App\Traits\BelongsToVillage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class StatisticalTable extends Model
{
    use HasFactory, BelongsToVillage;

    protected $guarded = ['id'];

    // Cast JSON agar otomatis menjadi Array saat ditarik dari Database
    protected function casts(): array
    {
        return [
            'columns' => 'array',
            'content' => 'array',
        ];
    }

    // Otomatis membuat slug dari title saat data dibuat
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($table) {
            $table->slug = Str::slug($table->title);
        });
    }

    /**
     * Relasi One-to-One ke model StatisticChart.
     * Satu tabel statistik HANYA memiliki SATU grafik.
     */
    public function chart(): HasOne
    {
        return $this->hasOne(StatisticChart::class);
    }
}
