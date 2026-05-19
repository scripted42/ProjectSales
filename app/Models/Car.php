<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'category',
        'price',
        'description',
        'features',
        'variants',
        'image',
        'hero_image',
        'images',
        'flyer',
        'is_available',
    ];

    protected $casts = [
        'features' => 'array',
        'images' => 'array',
        'variants' => 'array',
        'is_available' => 'boolean',
    ];
}
