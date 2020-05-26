<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Posts extends Model
{
    protected $table = 'posts';
    protected $fillable = ['id', 'title', 'description', 'price', 'region', 'city', 'category', 'condition', 'user_id'];

    public function reg()
    {
        return $this->hasOne('App\Regions','id', 'region');
    }

    public function img()
    {
        return $this->hasMany('App\Images','post_id', 'id');
    }

    public function usr()
    {
        return $this->hasOne('App\User','id', 'user_id');
    }

}
