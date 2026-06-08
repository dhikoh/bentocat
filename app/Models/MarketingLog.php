<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketingLog extends Model
{
    use HasFactory;

    protected $table = 'marketing_logs';

    protected $fillable = [
        'user_id',
        'log_date',
        'activity_title',
        'activity_details',
    ];

    protected $casts = [
        'log_date' => 'date',
    ];

    /**
     * Get the user that created the log.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
