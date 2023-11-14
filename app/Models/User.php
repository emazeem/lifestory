<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;
    protected $fillable = [
        'fname',
        'lname',
        'role',
        'profile',
        'contact',
        'created_for',
        'is_active',
        'password',
        'email',
        'show_notification',
        'deleted_at',
        'stored_at',
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected $appends = ['fullName'];

    public function fullName()
    {
        return $this->fname . ' ' . $this->lname;
    }
    public function getFullNameAttribute()
    {
        return $this->fname . ' ' . $this->lname;
    }

    public function details()
    {
        if ($this->role == \Role::Customer) {
            return $this->belongsTo(CustomerProfile::class, 'id', 'user_id')->withDefault();
        } else if ($this->role == 'Sub User') {
            return $this->belongsTo(CustomerProfile::class)->withDefault();
        } else {
            return $this->belongsTo(CustomerProfile::class, 'id', 'user_id')->withDefault();
        }

    }
    public function getPrimaryRoleAttribute($role)
    {
        if ($this->attributes['role'] == \Role::Customer) {
            return 'Customer';
        } elseif ($this->attributes['role'] == \Role::SuperAdmin) {
            return 'Super Admin';
        } elseif ($this->attributes['role'] == \Role::Developer) {
            return 'Developer';
        } elseif ($this->attributes['role'] == \Role::SubUser) {
            return 'Sub User';
        } else {
            return 'Unknown';
        }

    }
    public function getRoleAttribute($role)
    {
        $currentRole = CurrentRole::where('user_id', $this->id)->first();
        if ($role == \Role::Customer) {
            return ($currentRole) ? $currentRole->role : 'Customer';
        } elseif ($role == \Role::SuperAdmin) {
            return 'Super Admin';
        } elseif ($role == \Role::Developer) {
            return 'Developer';
        } elseif ($role == \Role::SubUser) {
            return 'Sub User';
        } else {
            return 'Unknown';
        }

    }

    public function getProfileAttribute($profile)
    {
        if ($profile == '' || $profile == null) {
            return url('dummy.png');
        } else if($this->stored_at!='s3') {
            return url('storage/profile_images/'.$this->id.'/'.$profile);
        }
        else return $profile;

    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'user_id', 'id');
    }
    public function paymentMethods()
    {
        return $this->hasMany(BankAccount::class, 'user_id', 'id');
    }

    public function customer()
    {
        return $this->hasOne(Session::class)->withDefault();
    }

    public function session()
    {
        return $this->hasOne(Session::class)->withDefault();
    }

    public function subUsers()
    {
        return $this->belongsToMany(User::class, 'relations', 'customer_id', 'subuser_id')->withPivot('relationship');
    }
    public function customerOfSubUser()
    {
        $relation = Relation::with('customer')->where('subuser_id', $this->id)->first();
        return $relation->customer;
    }
    public function customersOfSubUser()
    {
        $relation = Relation::with('customer')->where('subuser_id', $this->id)->get();
        $customers = [];
        foreach ($relation as $rel) {$customers[] = $rel->customer;}
        return $customers;
    }

    public function getCustomerOfSubUser()
    {
        $relation = Relation::with('customer')->where('subuser_id', $this->id)->first();
        return $relation->customer;
    }
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
    public function video()
    {
        return $this->hasOne(Story::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($user) {
            $session = Session::where("user_id", $user->id)->first();
            if ($session) {

                $timeSlot = TimeSlots::where("booked", $session->id)->first();
                if ($timeSlot) {
                    $timeSlot->booked = null;
                    $timeSlot->save();
                }
                $session->delete();
            }
            $user->details()->delete();
            $user->video()->delete();
            $user->posts()->delete();
            $user->comments()->delete();
            $user->transactions()->delete();
            $user->paymentMethods()->delete();
            if ($user->role == \RoleString::Customer) {
                $user->subscriptions()->delete();
            }

            Relation::where("customer_id", $user->id)->orWhere("subuser_id", $user->id)->delete();
        });
    }

}
