<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeSlots extends Model
{
    use HasFactory;

    protected $fillable = [
        "parent_id",
        "booked",
        "slot_date",
        "from",
        "day",
        "to"
    ];
    protected $appends=['start'];

    public function slots()
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id', 'id');
    }

    public function getStartAttribute()
    {
        return date('Y-m-d',strtotime($this->slot_date)).' '.$this->from;
    }

}
