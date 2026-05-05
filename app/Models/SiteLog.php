<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteLog extends Model
{
    protected $fillable = [
        'log_type',
        'car_id',
        'ip_address',
        'user_agent',
        'created_at',
    ];

    public $timestamps = false;

    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}
