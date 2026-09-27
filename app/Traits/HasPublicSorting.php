<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait HasPublicSorting
{
    /**
     * Opsi pengurutan halaman publik (daftar kartu Publikasi & Infografis).
     * Key = nilai query string ?urut=..., value = [kolom, arah].
     */
    protected function publicSortOptions(): array
    {
        return [
            'terbaru'    => ['updated_at', 'desc'],
            'terlama'    => ['updated_at', 'asc'],
            'judul_asc'  => ['title', 'asc'],
            'judul_desc' => ['title', 'desc'],
        ];
    }

    protected function applyPublicSort(Builder $query, Request $request): Builder
    {
        $key = $request->get('urut');
        $options = $this->publicSortOptions();

        // Param kosong/tidak valid (termasuk ?urut[]=...) -> perilaku lama: orderBy id desc
        [$column, $direction] = is_string($key) && isset($options[$key])
            ? $options[$key]
            : ['id', 'desc'];

        $query->orderBy($column, $direction);

        // Tie-breaker: judul/tanggal kembar tetap punya urutan stabil antar halaman pagination
        if ($column !== 'id') {
            $query->orderBy('id', 'desc');
        }

        return $query;
    }
}