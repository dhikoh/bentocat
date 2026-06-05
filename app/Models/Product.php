<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama', 'slug', 'thumbnail', 'deskripsi', 'status',
        'label_level_1', 'label_level_2', 'label_level_3'
    ];

    public function getLabelLevel1Attribute($value)
    {
        return $value ?: 'Kategori';
    }

    public function getLabelLevel2Attribute($value)
    {
        return $value ?: 'Varian / Aroma';
    }

    public function getLabelLevel3Attribute($value)
    {
        return $value ?: 'Ukuran / Kemasan';
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class, 'produk_id');
    }

    public function leadRequests(): HasMany
    {
        return $this->hasMany(LeadRequest::class, 'produk_id');
    }
}
