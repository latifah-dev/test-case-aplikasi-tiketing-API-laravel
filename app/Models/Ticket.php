<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'route_id',
        'agent_id',
        'passenger_name',
        'passenger_email',
        'passenger_phone',
        'seat_number',
        'status',
        'departure_time'
    ];

    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }
}
