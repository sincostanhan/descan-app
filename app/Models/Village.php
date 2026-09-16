<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Village extends Model
{
    protected $fillable = ['name', 'subdomain'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function organization()
    {
        return $this->hasOne(Organization::class);
    }

    public function setting()
    {
        return $this->hasOne(Setting::class);
    }

    public function statisticTableEntries()
    {
        return $this->hasMany(StatisticTableEntry::class);
    }

    public function regionGeometries()
    {
        return $this->hasMany(RegionGeometry::class);
    }
}