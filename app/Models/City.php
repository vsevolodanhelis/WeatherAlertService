<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'country_code',
        'latitude',
        'longitude',
    ];

    public function weatherSubscriptions()
    {
        return $this->hasMany(WeatherSubscription::class);
    }
}
