<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;
use App\User;
use App\Http\Controllers\API\AuthController as Au;
//Broadcast::channel('translation', function()

//{
//    return true;
//}
//Broadcast::channel('translation.{room_id}', function($user, $room_id)
//dd(auth()->guard('api')->user());
//dd($user = User::find('1'));
//Broadcast::channel('translation.{room_id}', \App\Broadcasting\MessagesChannel::class);
Broadcast::channel('translation.{room_id}', function($user, $room_id)
{
//    $us = new Au;
//    $us_parametr = $us -> get_user_info_channel();
//    return $us_parametr == '11';
   return  $user == null;
  // return  $user == null;
//   return  Auth::user() == null;
    return (int) auth()->guard('api')->user() == null;
    if($room_id == '11')
{
    return true;
}
else
{
    return false;
}});

