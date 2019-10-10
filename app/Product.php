<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $fillable = ['image_link', 'product_name', 'description', 'product_link', 'color', 'weight', 'weight_units', 'retail_price', 'wholesale_price', 'discount', 'product_status', 'category_id'];
    
    public function category() {
        return $this->belongsTo('\App\Category', 'category_id');
    }

    public function tags() {
        return $this->belongsToMany('\App\Tag', 'product_tag', 'product_id', 'tag_id');
    }

    public function taggings() {
        return $this->morphToMany('\App\Tag', 'taggable');
    }
}
