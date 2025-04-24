<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'weather_subscription_id',
        'current_value',
        'message',
        'sent_at',
    ];

    protected $casts = [
        'current_value' => 'float',
        'sent_at' => 'datetime',
    ];

    public function weatherSubscription()
    {
        return $this->belongsTo(WeatherSubscription::class);
    }
}
