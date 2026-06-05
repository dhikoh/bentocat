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

    public function setWhatsappAttribute($value)
    {
        $clean = preg_replace('/[^0-9]/', '', $value);
        if (str_starts_with($clean, '0')) {
            $clean = '62' . substr($clean, 1);
        }
        $this->attributes['whatsapp'] = $clean;
    }

    public function getFormattedWhatsappAttribute()
    {
        $clean = preg_replace('/[^0-9]/', '', $this->whatsapp);
        if (str_starts_with($clean, '0')) {
            $clean = '62' . substr($clean, 1);
        }
        return $clean;
    }
}
