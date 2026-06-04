<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeadAction extends Model
{
    use HasFactory;

    // Disabled timestamps for manual custom creation timestamp as defined in DDL
    public $timestamps = false;

    protected $fillable = ['lead_id', 'action_type', 'created_at'];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function lead(): BelongsTo
    {
        return $this->belongsTo(LeadRequest::class, 'lead_id');
    }
}
