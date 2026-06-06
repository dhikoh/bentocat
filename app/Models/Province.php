<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Province extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'is_hidden'];

    protected $casts = [
        'is_hidden' => 'boolean',
    ];

    public function cities(): HasMany
    {
        return $this->hasMany(City::class, 'provinsi_id');
    }
}
