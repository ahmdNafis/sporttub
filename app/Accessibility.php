<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Accessibility extends Model
{
    protected $table = 'accessibilities';
    protected $fillable = ['controller_name', 'user_id', 'role_id'];

    public function users() {
        return $this->belongsToMany('App\User', 'access_user', 'access_id', 'user_id');
    }

    public function role() {
        return $this->belongsTo('App\Role', 'role_id');
    }
}
