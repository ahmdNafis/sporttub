<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password', 'avatar'
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

    public function roles() {
        return $this->belongsToMany('\App\Role', 'role_user', 'user_id', 'role_id');
    }

    public function comments() {
        return $this->hasMany('\App\Comment', 'user_id');
    }

    public function news() {
        return $this->belongsToMany('\App\News', 'news_user', 'user_id', 'news_id');
    }

    public function accesses() {
        return $this->belongsToMany('App\Accessibility', 'access_user', 'user_id', 'access_id');
    }
}
