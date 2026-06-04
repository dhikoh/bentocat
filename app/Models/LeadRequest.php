<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LeadRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id', 'produk_id', 'varian_level_1', 'varian_level_2', 'varian_level_3',
        'kota_id', 'outlet_id', 'distributor_id'
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(CustomerProfile::class, 'customer_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'produk_id');
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'kota_id');
    }

    public function outlet(): BelongsTo
    {
        return $this->belongsTo(Outlet::class, 'outlet_id');
    }

    public function distributor(): BelongsTo
    {
        return $this->belongsTo(Distributor::class, 'distributor_id');
    }

    public function actions(): HasMany
    {
        return $this->hasMany(LeadAction::class, 'lead_id');
    }
}
