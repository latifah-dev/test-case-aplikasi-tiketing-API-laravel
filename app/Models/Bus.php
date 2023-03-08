<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'departure_city_id',
        'arrival_city_id'
    ];

    public function busClasses()
    {
        return $this->hasMany(BusClass::class);
    }
    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
