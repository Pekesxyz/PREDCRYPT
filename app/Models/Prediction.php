<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Prediction extends Model
{
    protected $fillable = [
        'user_id',
        'coin',
        'current_price',
        'predicted_price',
        'mae',
        'rmse',
    ];

    protected $casts = [
        'current_price' => 'float',
        'predicted_price' => 'float',
        'mae' => 'float',
        'rmse' => 'float',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
