<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;

class Session extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'time_slot_id',
        'uuid',
        'meeting_id',
        'host_email',
        'topic',
        'start_time',
        'reminder',
        'end_time',
        'duration',
        'start_url',
        'join_url',
        'password',
        'payment',
        'status',
        'deleted_at'
    ];

    protected $appends = ['link'];

    public function setStartTimeAttribute($value)
    {
        $this->attributes['start_time'] = date('Y-m-d H:i:s', strtotime($value));
    }

    public function customer()
    {
        return $this->belongsTo(User::class,'user_id','id')->withDefault();
    }

    public function getLinkAttribute()
    {
        if (array_key_exists('meeting_id', $this->attributes)) {
            return url('payment/?meeting_id=' . $this->attributes['meeting_id']);
        }
        return null;
    }

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($session) {
            $timeSlot = TimeSlots::where("booked",$session->id)->first();
            if ($timeSlot) {
                $timeSlot->booked = NULL;
                $timeSlot->save();
            }
        });
    }
}
