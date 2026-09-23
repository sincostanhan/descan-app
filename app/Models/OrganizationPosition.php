<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrganizationPosition extends Model
{
    // Tidak pakai BelongsToVillage — scoping village otomatis ikut parent Organization.
    protected $fillable = ['organization_id', 'level', 'label', 'name', 'order'];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}