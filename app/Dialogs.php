<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dialogs extends Model
{
    protected $table = 'dialogs';
    //user_dialogs_id будет всегда формироваться от меньшего к большему
    //ВАЖНО ! формат 3_5 6_17
    protected $fillable = ['id','user_id', 'user_dialogs_id'];

    public function cht()
    {
        return $this->hasMany('App\Chat','us_dialogs_id', 'user_dialogs_id');
    }

}
