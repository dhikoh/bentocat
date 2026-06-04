<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ShippingContact extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'whatsapp', 'keterangan', 'aktif'];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    public function outlets(): BelongsToMany
    {
        return $this->belongsToMany(Outlet::class, 'petshop_shipping_contacts', 'shipping_contact_id', 'petshop_id')
                    ->withPivot('urutan')
                    ->withTimestamps();
    }
}
