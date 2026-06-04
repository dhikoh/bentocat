<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = ['produk_id', 'parent_id', 'nama', 'level'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'produk_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(ProductVariant::class, 'parent_id');
    }
}
