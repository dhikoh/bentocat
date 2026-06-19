<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketingTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'target_audience',
        'tone',
        'placeholders',
        'base_prompt',
    ];
}
