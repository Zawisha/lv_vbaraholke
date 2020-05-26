<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RegisterStatus extends Model
{
    protected $table = 'register_status';
    protected $fillable = ['id','user_id', 'token', 'verified'];
}
