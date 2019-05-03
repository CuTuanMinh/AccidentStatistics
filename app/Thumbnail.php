<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Thumbnail extends Model
{
    protected $table = 'thumbnails';
    public $timestamp = true;

    public function newspaper()
    {
    	return $this->belongsTo('App\Newspaper','newspaper_id','id');
    }
}
