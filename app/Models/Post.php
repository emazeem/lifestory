<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_id',
        'deleted_at',
        'stored_at',
        'user_id',
        'caption',
        'actual',
        'image'
    ];
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function comments(){
        return $this->hasMany(Comment::class,'post_id');
    }

    public function getImageAttribute($image)
    {
        if (!$image) return url('default-post.jpg');
        if ($this->stored_at == 'public') return url("storage/memories/{$this->user->id}/{$image}");
        return $image;
    }

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($user) 
        {
            $user->comments()->delete();
        });
    }
}
