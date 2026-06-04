<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
    use HasFactory;

    protected $fillable = ['provinsi_id', 'nama', 'slug'];

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class, 'provinsi_id');
    }

    public function distributors(): HasMany
    {
        return $this->hasMany(Distributor::class, 'kota_id');
    }

    public function outlets(): HasMany
    {
        return $this->hasMany(Outlet::class, 'kota_id');
    }
}
