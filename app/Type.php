<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    protected $table = 'types';
    protected $fillable = ['type_name', 'type_status'];
    public function categories() {
        return $this->hasMany('\App\Category', 'type_id');
    }
    
    public function news() {
        return $this->hasManyThrough('\App\News', '\App\Category', 'type_id', 'category_id', 'id', 'id');
    }

    public function products() {
        return $this->hasManyThrough('\App\Product', '\App\Category', 'type_id', 'category_id', 'id', 'id');
    }
}