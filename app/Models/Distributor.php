<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Distributor extends Model
{
    use HasFactory;

    protected $fillable = [
        'kota_id', 'nama', 'pic', 'whatsapp', 'alamat', 'tampil_ke_publik', 'status'
    ];

    protected $casts = [
        'tampil_ke_publik' => 'boolean',
    ];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'kota_id');
    }

    public function outlets(): HasMany
    {
        return $this->hasMany(Outlet::class, 'distributor_id');
    }
}
