<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PromptHistory extends Model
{
    use HasFactory;

    protected $table = 'prompt_histories';

    protected $fillable = [
        'customer_profile_id',
        'user_id',
        'template_name',
        'chat_input',
        'variables',
        'generated_prompt',
    ];

    protected $casts = [
        'variables' => 'array',
    ];

    /**
     * Get the customer profile associated with the history.
     */
    public function customerProfile(): BelongsTo
    {
        return $this->belongsTo(CustomerProfile::class, 'customer_profile_id');
    }

    /**
     * Get the user that generated the prompt.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
