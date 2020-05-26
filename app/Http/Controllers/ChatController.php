<?php

namespace App\Http\Controllers;

use App\Chat;
use App\Dialogs;
use App\Events\TranslationEvent;
use App\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function get_users_with_dialogs(Request $request)
    {
        $result=[];
        $users = Dialogs::where('user_id', '=', auth()->guard('api')->user()->id)
            ->get();

        foreach ($users as $user) {
          $id=$user['user_dialogs_id'];
            $keywords = preg_split("/[\_]+/", $id);
            foreach ($keywords as $key) {
                if($key != auth()->guard('api')->user()->id)
                {
                    $result[]=$key;
                }
            }
        }

        $users = User::whereIn('id', $result)
            ->get();

        foreach ($result as $numb=>$res)

        {
                    if(file_exists(public_path('/images/avatars/'.$res)))
        {

            foreach ($users as $numb1=>$user)
            {
                if($user['id']==$res)
                {
                    $users[$numb1]['img_ava'] ='/images/avatars/'.$res;
                }
            }


        }
        else
        {
            foreach ($users as $numb1=>$user)
            {
                if($user['id']==$res)
                {
                    $users[$numb1]['img_ava'] ='/images/avatars/0';
                }
            }
        }

        if(auth()->guard('api')->user()->id > $res)
        {
            $user_dialog_id = $res.'_'.auth()->guard('api')->user()->id;
        }
        else
        {
            $user_dialog_id = auth()->guard('api')->user()->id.'_'.$res;
        }

           $offset_arr = $request->input('offset_arr');
         //   $offset=0;
          //  $offset_arr['us_dialogs_id'];
            if($offset_arr != [])
            {
                foreach ($offset_arr as $off_arr)
                {
                    return $off_arr;
                }
            }
            else
            {
                $chat=Chat::where('us_dialogs_id', '=', $user_dialog_id)->offset(0)->limit(10)->orderBy('id','DESC')
                    ->get();
                //переворачиваю в правильный порядок
                $result_arr=[];

                for ($i=count($chat)-1;$i>=0; $i--) {
                    array_push($result_arr, $chat[$i]);
                }
                $chat= $result_arr;
            }




            foreach ($users as $numb2=>$user)
            {
                if($user['id']==$res)
                {

                    $users[$numb2]['chat']=$chat;

                }
            }
        }

        return $users;
    }

    public function get_data_scroll_user(Request $request)
    {
        $us_dialogs_id = $request->input('us_dialogs_id');
        $offset = $request->input('offset');

        $chat=Chat::where('us_dialogs_id', '=', $us_dialogs_id)->offset($offset)->limit(10)->orderBy('id','DESC')
            ->get();
        //переворачиваю в правильный порядок
        $result_arr=[];

        for ($i=count($chat)-1;$i>=0; $i--) {
            array_push($result_arr, $chat[$i]);
        }
        $chat= $result_arr;
        return $chat;

    }


    public function get_data_props_user(Request $request)
    {
        $to_user_props = $request->input('to_user_props');

        $user = User::where('id', '=' ,$to_user_props)
            ->get();


        if(file_exists(public_path('/images/avatars/'.$to_user_props)))
        {
            $user[0]['img_ava'] ='/images/avatars/'.$to_user_props;
        }
        else
        {
            $user[0]['img_ava'] ='/images/avatars/0';
        }

        if(auth()->guard('api')->user()->id > $to_user_props)
        {
            $user_dialog_id = $to_user_props.'_'.auth()->guard('api')->user()->id;
        }
        else
        {
            $user_dialog_id = auth()->guard('api')->user()->id.'_'.$to_user_props;
        }

        $chat=Chat::where('us_dialogs_id', '=', $user_dialog_id)
            ->get();
        $user[0]['chat']=$chat;

        return $user;

    }


    public function save_message_chat(Request $request)
    {
        $user_to = $request->input('user_to');
        $message = $request->input('message');

        if(auth()->guard('api')->user()->id > $user_to)
        {
            $user_dialog_id = $user_to.'_'.auth()->guard('api')->user()->id;
        }
        else
        {
            $user_dialog_id = auth()->guard('api')->user()->id.'_'.$user_to;
        }


        if (Dialogs::where('user_dialogs_id', '=', $user_dialog_id)->where('user_id', '=', auth()->guard('api')->user()->id)->count() < 1) {
                Dialogs::create([
                'user_id' => auth()->guard('api')->user()->id,
                'user_dialogs_id' => $user_dialog_id
            ]);
        }

        if (Dialogs::where('user_dialogs_id', '=', $user_dialog_id)->where('user_id', '=', $user_to)->count() < 1) {
            Dialogs::create([
                'user_id' => $user_to,
                'user_dialogs_id' => $user_dialog_id
            ]);
        }


        $chat =  Chat::create([
            'from_user_id' => auth()->guard('api')->user()->id,
            'to_user_id' => $user_to,
            'message_text' => $message,
            'us_dialogs_id' => $user_dialog_id,
            'img' => 0
        ]);


        $my_id= (auth()->guard('api')->user()->id);
        event(new TranslationEvent($user_to, $message, $my_id, 0));

        return response([
            'status' => 'success',
            'mes' => $chat,
        ], 200);
    }


}
