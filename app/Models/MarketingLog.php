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
        'outlet_id',
        'customer_profile_id',
        'agenda',
        'rating',
        'notes',
        'followup_feedback',
        'potential_closing',
        'crm_stage',
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

    /**
     * Get the outlet associated with the log.
     */
    public function outlet()
    {
        return $this->belongsTo(Outlet::class);
    }

    /**
     * Get the customer profile associated with the log.
     */
    public function customerProfile()
    {
        return $this->belongsTo(CustomerProfile::class, 'customer_profile_id');
    }
}
