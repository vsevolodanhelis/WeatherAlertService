<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeatherSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'city_id',
        'email',
        'condition_type',
        'condition_value',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'condition_value' => 'float',
    ];

    public const CONDITION_TYPES = [
        'temperature_below' => 'Temperature below',
        'temperature_above' => 'Temperature above',
        'rain' => 'Rain',
        'snow' => 'Snow',
        'wind_speed_above' => 'Wind speed above',
        'thunderstorm' => 'Thunderstorm',
        'tornado' => 'Tornado warning',
        'poor_air_quality' => 'Poor air quality',
        'high_pollution' => 'High pollution',
        'high_uv_index' => 'High UV index',
        'extreme_uv_index' => 'Extreme UV index',
        'high_humidity' => 'High humidity',
        'low_humidity' => 'Low humidity',
        'pressure_increase' => 'Rapid pressure increase',
        'pressure_decrease' => 'Rapid pressure decrease',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
