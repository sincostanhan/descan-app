<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait BelongsToVillage
{
    protected static function bootBelongsToVillage()
    {
        // 1. Otomatis memfilter data berdasarkan kelurahan yang sedang diakses
        static::addGlobalScope('village', function (Builder $builder) {
            if (app()->bound('current_village_id')) {
                $builder->where('village_id', app('current_village_id'));
            }
        });

        // 2. Otomatis menyisipkan village_id saat menyimpan data baru (Create)
        static::creating(function ($model) {
            if (app()->bound('current_village_id') && empty($model->village_id)) {
                $model->village_id = app('current_village_id');
            }
        });
    }

    public function village()
    {
        return $this->belongsTo(\App\Models\Village::class);
    }
}