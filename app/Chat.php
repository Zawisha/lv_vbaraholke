<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $table = 'chat';
    protected $fillable = ['id', 'from_user_id', 'to_user_id', 'message_text','us_dialogs_id', 'img'];

}
