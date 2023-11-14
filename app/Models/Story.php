<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Story extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "deleted_at",
        "thumbnail",
        "backup_1",
        "user_id",
        "file",
        "name",
        "stored_at",
    ];

    public function customer(){
        return $this->belongsTo(User::class, "user_id",'id');
    }

    public function getFileAttribute($file)
    {
        if($this->stored_at != 's3')
        return url('storage/session_recordings/' . $this->customer->id . '/' . $file);
        else return $file;
    }

    protected $appends = ['poster'];

    public function getPosterAttribute()
    {
        if($this->stored_at != 's3')
        return url('storage/session_recordings/' . $this->customer->id . '/' . $this->thumbnail);
        else return $this->thumbnail;
    }
    public function getScreenshotUrl()
    {
        if($this->stored_at != 's3')
        return url('storage/session_recordings/' . $this->customer->id . '/' . $this->thumbnail);
        else return $this->thumbnail;
    }

    public function getScreenName()
    {
        if($this->stored_at == 's3')
        {
            $parts = explode('/', $this->thumbnail);
            return $parts[count($parts)-1];

        }
        else return $this->thumbnail;
    }

}
