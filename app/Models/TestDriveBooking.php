<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestDriveBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'car_id',
        'booking_date',
        'status',
        'notes',
        'ip_address',
        'source',
    ];

    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}
