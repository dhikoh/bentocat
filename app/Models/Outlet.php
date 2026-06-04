<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Outlet extends Model
{
    use HasFactory;

    protected $fillable = [
        'distributor_id', 'kota_id', 'nama_outlet', 'nama_pic', 'whatsapp',
        'alamat_lengkap', 'latitude', 'longitude', 'google_maps_url',
        'featured', 'status', 'delivery_mode'
    ];

    protected $casts = [
        'featured' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function distributor(): BelongsTo
    {
        return $this->belongsTo(Distributor::class, 'distributor_id');
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'kota_id');
    }

    public function shippingContacts(): BelongsToMany
    {
        return $this->belongsToMany(ShippingContact::class, 'petshop_shipping_contacts', 'petshop_id', 'shipping_contact_id')
                    ->withPivot('urutan')
                    ->withTimestamps()
                    ->orderBy('pivot_urutan');
    }

    public function leadRequests(): HasMany
    {
        return $this->hasMany(LeadRequest::class, 'outlet_id');
    }
}
