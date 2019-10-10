<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $table = 'terms_of_service';
    protected $fillable = ['content_service', 'service_status'];
}
