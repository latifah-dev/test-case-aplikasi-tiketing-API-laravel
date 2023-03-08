<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stop extends Model
{
    use HasFactory;

    protected $fillable = [
        'city_id',
        'name'
    ];

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function routeStops()
    {
        return $this->hasMany(RouteStop::class);
    }
}
