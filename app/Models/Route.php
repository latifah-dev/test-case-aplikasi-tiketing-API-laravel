<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    use HasFactory;

    protected $fillable = [
        'bus_class_id',
        'departure_time',
        'arrival_time'
    ];

    public function busClass()
    {
        return $this->belongsTo(BusClass::class);
    }

    public function routeStops()
    {
        return $this->hasMany(RouteStop::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
