<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Relation extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        "customer_id",
        "subuser_id",
        "relationship",
        "deleted_at"
    ];

    public function customer(){
        return $this->belongsTo(User::class,'customer_id','id')->withDefault();
    }

}
