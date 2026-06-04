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
}
