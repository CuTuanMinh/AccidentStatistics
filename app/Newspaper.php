<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Newspaper extends Model
{
    protected $table = 'newspapers';
    public $timestamp = true;

    public function thumbnail()
    {
    	return $this->hasMany('App\Thumbnail','newspaper_id','id');
    }

}
