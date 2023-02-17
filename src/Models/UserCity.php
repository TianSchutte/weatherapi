<?php

namespace Tian\Weatherapi\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCity extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'city_name'
    ];

    public function weatherStats()
    {
        return $this->hasMany(WeatherStat::class, 'city_id');
    }
}
