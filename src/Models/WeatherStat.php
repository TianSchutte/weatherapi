<?php

namespace Tian\Weatherapi\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeatherStat extends Model
{
    use HasFactory;

    protected $fillable = [
        'weather_data',
        'city_id'
    ];

    protected $casts = [
        'weather_data' => 'array',
    ];

    public function userCity()
    {
        return $this->belongsTo(UserCity::class, 'city_id');
    }
}
