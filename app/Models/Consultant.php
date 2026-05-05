<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consultant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'photo',
        'address',
        'bio',
    ];

    public function getFormattedPhoneAttribute()
    {
        $phone = $this->phone;
        // Remove any non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // If it starts with 0, replace with 62
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }
        
        return $phone;
    }
}
