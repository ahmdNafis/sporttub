<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
//use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Category extends Model
{
    protected $table = 'categories';
    protected $fillable = ['category_name', 'description', 'category_status'];

    public function news() {
        return $this->hasMany('\App\News', 'category_id');
    }

    public function type() {
        return $this->belongsTo('\App\Type', 'type_id');
    }

    public function products() {
        return $this->hasMany('\App\Product', 'category_id');
    }

    public function tags() {
        return $this->hasMany('\App\Tag', 'category_id');
    }
}
