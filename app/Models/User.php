<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username', 'role_id', 'email', 'mobile', 'password', 'google_id', 'api_token', 'fcm', 'otp', 'status', 'mobile_verified', 'email_verified', 'verification_code'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'api_token', 'otp'
    ];
 
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function address()
    {
        return $this->hasMany(Address::class)->orderBy('id', 'DESC');
    }

    public function defaultAddress()
    {
        return Address::where('user_id', $this->id)->where('default', 1)->first();
    }

}