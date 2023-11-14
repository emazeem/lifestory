<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'user_id',
        'transaction_id',
        'reference_id',
        'description',
        'deleted_at',
        'credit',
        'debit'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($transaction)
        {
            if($transaction->user->role != \RoleString::SuperAdmin) 
            {
                $adminTransaction = Transaction::where("debit",0)->where("transaction_id", $transaction->transaction_id)->first();
                if($adminTransaction) $adminTransaction->delete();
            }
        });
    }
}
