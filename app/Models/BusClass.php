<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusClass extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'bus_id',
        'price'
    ];

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    public function routes()
    {
        return $this->hasMany(Route::class);
    }
}
