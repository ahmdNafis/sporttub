<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $table = 'news';
    protected $fillable = ['object_id', 'content', 'newslink', 'published_date', 'published_status', 'category_id'];
    public $timestamps = false;
    public function comments() {
        return $this->hasMany('\App\Comment', 'news_id');
    }

    public function category() {
        return $this->belongsTo('\App\Category', 'category_id');
    }

    public function users() {
        return $this->belongsToMany('\App\User', 'news_user', 'user_id', 'news_id');
    }

    public function tags() {
        return $this->belongsToMany('\App\Tag', 'news_tag', 'news_id', 'tag_id');
    }
    
    public function taggings() {
        return $this->morphToMany('\App\Tag', 'taggable');
    }
}
