<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'domain',
        'site_name',
        'plan',
        'status',
        'expired_at',
        'token',
        'secret_key',
    ];
}
