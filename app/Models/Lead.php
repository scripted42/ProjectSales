<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'whatsapp',
        'email',
        'promo_id',
    ];

    public function promo()
    {
        return $this->belongsTo(Promo::class);
    }
}
