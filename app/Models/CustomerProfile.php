<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomerProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid', 'nama', 'whatsapp', 'alamat', 'latitude', 'longitude', 'provinsi', 'kota'
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function leadRequests(): HasMany
    {
        return $this->hasMany(LeadRequest::class, 'customer_id');
    }

    public function latestMarketingLog()
    {
        return $this->hasOne(MarketingLog::class, 'customer_profile_id')->latestOfMany();
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
