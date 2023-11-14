<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value'
    ];

    public function slots()
    {
        return $this->belongsTo(self::class, 'parent_id', 'id');
    }
}
