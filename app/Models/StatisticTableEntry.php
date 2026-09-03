<?php

namespace App\Models;

use App\Traits\BelongsToVillage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Model;

class StatisticTableEntry extends Model
{
    use HasFactory, BelongsToVillage;

    protected $fillable = [
        'village_id',
        'statistic_template_id',
        'title',
        'source',
        'description',
    ];

    public function template(): BelongsTo
    {
        return $this->belongsTo(StatisticTemplate::class, 'statistic_template_id');
    }

    public function values(): HasMany
    {
        return $this->hasMany(StatisticTableValue::class, 'statistic_table_entry_id');
    }

    /**
     * Satu entry HANYA memiliki SATU grafik (mengikuti relasi lama StatisticalTable::chart()).
     */
    public function chart(): HasOne
    {
        return $this->hasOne(StatisticChart::class, 'statistic_table_entry_id');
    }

    /**
     * PENTING: Accessor ini membentuk ulang data ternormalisasi menjadi bentuk lama
     * ['Nama Kolom' => ...] agar resources/views/statistic/show.blade.php dan seluruh
     * kode Chart.js (yang loop $statistic->columns / $row[$col]) TIDAK PERLU diubah sama sekali.
     *
     * Untuk menghindari N+1, eager-load: ->with(['template.headers', 'values.templateCell.columnHeader'])
     * sebelum accessor ini diakses dari controller.
     */
    public function getColumnsAttribute(): array
    {
        if (!$this->relationLoaded('template') || !$this->template) {
            $this->load('template.headers');
        }

        $rowLabelKey = optional($this->template->rowHeaders->first())->label ?? 'Uraian';

        $columnLeaves = $this->template->headers
            ->where('axis', 'column')
            ->where('is_leaf', true)
            ->sortBy('order')
            ->pluck('label')
            ->values();

        return array_merge([$rowLabelKey], $columnLeaves->all());
    }

    public function getContentAttribute(): array
    {
        $columns = $this->columns;
        $rowLabelKey = $columns[0] ?? 'Uraian';

        $rowLeaves = $this->template->headers
            ->where('axis', 'row')
            ->where('is_leaf', true)
            ->sortBy('order');

        if (!$this->relationLoaded('values')) {
            $this->load('values.templateCell.columnHeader');
        }
        $valuesByRow = $this->values->groupBy(fn ($v) => $v->templateCell->row_header_id);

        $rows = [];
        foreach ($rowLeaves as $rowLeaf) {
            $rowData = [$rowLabelKey => $rowLeaf->label];

            foreach ($valuesByRow->get($rowLeaf->id, collect()) as $value) {
                $rowData[$value->templateCell->columnHeader->label] = $value->value;
            }

            $rows[] = $rowData;
        }

        return $rows;
    }
}