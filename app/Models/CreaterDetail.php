<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CreaterDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "user_id",
        "contact",
        "city",
        "state",
        "country",
        "dob",
        "zip_code"
    ];
}
