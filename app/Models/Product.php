<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'slug', 'thumbnail', 'deskripsi', 'status'];

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class, 'produk_id');
    }

    public function leadRequests(): HasMany
    {
        return $this->hasMany(LeadRequest::class, 'produk_id');
    }
}
