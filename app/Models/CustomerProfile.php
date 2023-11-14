<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerProfile extends Model
{
    protected $table="customer_profiles";
    use HasFactory, SoftDeletes;
    protected $fillable = [
        "deleted_at",
        "user_id",
        "contact",
        "city",
        "state",
        "country",
        "dob",
        "bio",
        "zip_code"
    ];

    public function getDobAttribute($dob)
    {
        return date('Y-m-d',strtotime($dob));
    }
}
