<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{

    use HasFactory, SoftDeletes;
    protected $appends = ['created_at_h'];
    protected $fillable = [
        'deleted_at',
        'user_id',
        'post_id',
        'comment'
    ];
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function getCreatedAtHAttribute(){
        $created = new Carbon($this->created_at);
        return $created->diffForHumans();
    }
}
