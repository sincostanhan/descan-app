<?php

namespace App\Models;

use App\Traits\BelongsToVillage;
use Illuminate\Database\Eloquent\Builder;
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
        'publication_id',
        'title',
        'source',
        'description',
    ];

    public function template(): BelongsTo
    {
        return $this->belongsTo(StatisticTemplate::class, 'statistic_template_id');
    }

    /**
     * Publikasi induk tabel ini (opsional). Satu tabel hanya milik satu publikasi.
     */
    public function publication(): BelongsTo
    {
        return $this->belongsTo(Publication::class);
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
     * Urut berdasarkan judul yang TAMPIL di layar: override entry.title (jika ada),
     * fallback ke judul template. Dipakai PublicStatisticController & StatisticTableEntryController.
     * $direction di-whitelist asc/desc sehingga aman dipakai di orderByRaw.
     */
    public function scopeOrderByDisplayTitle(Builder $query, string $direction = 'asc'): Builder
    {
        $direction = strtolower($direction) === 'desc' ? 'desc' : 'asc';

        return $query->orderByRaw(
            "COALESCE(statistic_table_entries.title, (SELECT st.title FROM statistic_templates st WHERE st.id = statistic_table_entries.statistic_template_id)) {$direction}"
        );
    }

    /**
     * Pencarian judul (entry.title ATAU judul template), dibungkus dalam satu grup where
     * agar orWhereHas tidak "membocorkan" kondisi lain seperti filter is_active.
     */
    public function scopeSearchDisplayTitle(Builder $query, string $search): Builder
    {
        return $query->where(function ($q) use ($search) {
            $q->where('statistic_table_entries.title', 'like', "%{$search}%")
              ->orWhereHas('template', fn ($t) => $t->where('title', 'like', "%{$search}%"));
        });
    }

    /**
     * Filter publikasi, dipakai index publik & admin.
     * '' / null = semua, 'tanpa' = tabel tanpa publikasi, angka = ID publikasi.
     */
    public function scopeFilterByPublication(Builder $query, ?string $filter): Builder
    {
        $filter = (string) $filter;

        if ($filter === 'tanpa') {
            return $query->whereNull('publication_id');
        }

        if (ctype_digit($filter)) {
            return $query->where('publication_id', (int) $filter);
        }

        return $query;
    }

    /**
     * Keterangan dipecah per baris (Enter), baris kosong dibuang.
     * Dipakai halaman publik (tiap baris tampil terpisah) dan export Excel
     * (tiap baris keterangan = 1 baris sheet), supaya aturan pemecahannya satu sumber.
     */
    public function getDescriptionLinesAttribute(): array
    {
        return collect(preg_split('/\r\n|\r|\n/', (string) $this->description))
            ->map(fn ($line) => trim($line))
            ->filter(fn ($line) => $line !== '')
            ->values()
            ->all();
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

        // $rowLabelKey = optional($this->template->rowHeaders->first())->label ?? 'Uraian';
        $rowLabelKey = $this->template->isRtRwMode()
            ? 'Wilayah (RT/RW)'
            : (optional($this->template->rowHeaders->first())->label ?? 'Uraian');

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
            ->filter(fn ($h) => is_null($h->village_id) || $h->village_id === $this->village_id)
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