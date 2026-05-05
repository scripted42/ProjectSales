<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'hook',
        'code',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'end_date' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function leads()
    {
        return $this->hasMany(Lead::class);
    }
}
