<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Privacy extends Model
{
    protected $table = 'privacy_profile';
    protected $fillable = ['content_privacy', 'privacy_status'];
}
