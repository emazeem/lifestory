<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZoomOauth extends Model
{
    use HasFactory;
    protected $fillable = [
        'provider',
        'access_token',
        'refresh_token',
        'full_token'
    ];

    protected $casts = [
        'full_token' => 'json'
    ];
}
