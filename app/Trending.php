<?php

namespace App;

//use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Model;

class Trending extends Model
{
    protected $collection = 'trending';
    protected $connection = 'mongodb';
    protected $fillable = ['category_id', 'type_id', 'date_from', 'date_to', 'weighting', 'duration', 'interval']; //interval is in years
    //weighting out of 4, hosting(h) month will have 4, h-1 month will be 3, h-2 will be 2, h-3 will be 1 and h-4 or more than 3 months will be 0
}
