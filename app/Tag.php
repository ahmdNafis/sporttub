<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = 'tags';
    protected $fillable = ['tag_name', 'tag_status', 'category_id'];

    public function news() {
        return $this->belongsToMany('\App\News', 'news_tag', 'tag_id', 'news_id');
    }

    public function products() {
        return $this->belongsToMany('\App\Product', 'product_tag', 'tag_id', 'product_id');
    }

    public function category() {
        return $this->belongsTo('\App\Category', 'category_id');
    }

    public function news_taggings() {
        return $this->morphedByMany('\App\News', 'taggable');
    }

    public function products_taggings() {
        return $this->morphedByMany('\App\Product', 'taggable');
    }
}
