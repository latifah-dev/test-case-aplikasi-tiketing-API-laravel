<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'nama', 'telepon', 'alamat', 'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
