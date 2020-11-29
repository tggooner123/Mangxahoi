<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
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
        'name', 'email', 'password', 'avatar', 'cover'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function posts() {
        return $this->hasMany('App\Models\Post', 'user_id');
    }

    public function comments() {
        return $this->hasMany('App\Models\Comment', 'user_id');
    }

    public function friendRequests() {
        return $this->hasMany('App\Models\FriendRequest', 'user_send_request');
    }
    public function friends() {
        return $this->belongsToMany('App\Models\Friend');
    }

    public function messages() {
        return $this->hasMany('App\Models\Message');
    }
}
