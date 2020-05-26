<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Regions extends Model
{
    protected $table = 'region';
    protected $fillable = ['id','region'];

    public function post()
    {
      // return $this->hasOne('App\Posts', 'region_id');

       // return $this->belongsTo('App\Posts', 'region_id');
    }

}
