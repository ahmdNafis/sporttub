<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';
    protected $fillable = ['role_name', 'role_status', 'permission'];
    public function users() {
        return $this->belongsToMany('\App\User', 'role_user', 'role_id', 'user_id');
    }

    public function access() {
        return $this->hasOne('App\Accessibility', 'role_id');
    }
}
