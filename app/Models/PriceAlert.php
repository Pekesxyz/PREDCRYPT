<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PriceAlert extends Model
{
    protected $fillable = [
        'user_id',
        'coin',
        'target_price',
        'is_triggered',
        'direction',
    ];

    protected $casts = [
        'target_price' => 'float',
        'is_triggered' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
