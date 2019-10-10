<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comments';
    protected $fillable = ['user_id', 'news_id', 'content', 'flag'];
    
    public function user() {
        return $this->belongsTo('\App\User', 'user_id');
    }

    public function news() {
        return $this->belongsTo('\App\News', 'news_id');
    }   
}
