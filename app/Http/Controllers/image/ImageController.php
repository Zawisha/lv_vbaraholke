<?php

namespace App\Http\Controllers\image;

use App\Chat;
use App\Dialogs;
use App\Events\TranslationEvent;
use App\Http\Controllers\Controller;
use App\Images;
use App\Posts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ImageController extends Controller
{

    public function del_ava(Request $request)
    {

        $img_path = $request->input('img_path');

        File::delete($img_path);
        return $img_path;
    }

    public function store_ava(Request $request)
    {

        $postData = $request->only('attachment');
        $file = $postData['attachment'];
        $fileArray = array('image' => $file);

        $rules = array(
            'image' => 'mimes:jpeg,jpg,png|required|max:8192' // max 10000kb
        );
        $validator = Validator::make($fileArray, $rules);


        if($validator ->fails()){
            $failed = $validator->messages();
            return response(['mes'=> $failed, 'status' => 'error',],200);
        }
        else
        {
            $user=auth()->user()->id;

            $time=time();
            $path= $request->attachment->move(public_path('/images/avatars'), $user.'_'.$time);

            File::copy((public_path('/images/avatars/'.$user.'_'.$time)),(public_path('/images/avatars/'.$user)));

            return response([
                'status' => 'success',
                'data' => $time
            ], 200);
        }
    }

    public function check_ava()
    {
        $user=auth()->user()->id;
        if(file_exists(public_path('/images/avatars/'.$user)))
        {
            return '1';
        }
        else
        {
            return '0';
        }
    }

    public function store_pic(Request $request)
    {

        $input = $request->except('files');

        $validator = Validator::make($input, [
            'product_name' => ['required', 'string',  'max:50', 'min:3'],
            'product_desc' => ['required', 'string', 'max:50', 'min:3'],
            'product_price' => ['required','numeric', 'max:9999999', 'min:0'],
            'city' => ['required', 'string', 'max:50', 'min:3'],
            'selected_region' => ['required', 'numeric', 'max:6', 'min:1'],
            //set after install categories (max value)
            'selected_category' => ['required', 'numeric', 'max:6', 'min:1'],
            'product_cond' => ['required', 'numeric', 'max:1', 'min:0']

        ]);

        if($validator ->fails()){

            // $failed = $validator->messages();
            $failed = $validator->messages();
            return response([$failed, 'status' => 'fail',],200);
        }


                          $post_cr =  Posts::create([
                   'title' => $request['product_name'],
                   'description' => $request['product_desc'],
                   'price' => $request['product_price'],
                   'city' => $request['city'],
                   'region' => $request['selected_region'],
                   'category' => $request['selected_category'],
                   'condition' => $request['product_cond'],
                              'user_id' => auth()->user()->id
               ]);

        for ($i = 0; $i < count($request['files']); $i++) {

            $file = $request['files'][$i];
            $fileArray = array('image' => $file);

            $rules = array(
                'image' => 'mimes:jpeg,jpg,png|required|max:8192' // max 10000kb
            );
            $validator = Validator::make($fileArray, $rules);

            if ($validator->fails()) {
                $failed = $validator->messages();

            } else {

                    $time = time();
                    $request['files'][$i]->move(public_path('/images/posts'), $time . '_' . auth()->user()->id.'_'.$i);
                    $path = $time . '_' . auth()->user()->id.'_'.$i;
                $img_cr =  Images::create([
                    'post_id' => $post_cr['id'],
                    'path' => $path
                ]);


            }


        }
    }
    public function store_pic_chat(Request $request)
    {
        $input = $request->except('files');
        $resp_chat=[];
        for ($i = 0; $i < count($request['files']); $i++) {

            $file = $request['files'][$i];
            $fileArray = array('image' => $file);

            $rules = array(
                'image' => 'mimes:jpeg,jpg,png|required|max:8192' // max 10000kb
            );
            $validator = Validator::make($fileArray, $rules);

            if ($validator->fails()) {

                $failed = $validator->messages();
                return response([
                    'status' => 'fail',
                    'mes' => $failed,
                ], 200);

            } else {

                $time = time();
                $request['files'][$i]->move(public_path('/images/chat'), $time . '_' . auth()->user()->id.'_'.$i);
                $path = $time . '_' . auth()->user()->id.'_'.$i;


                $user_to = $request->input('user_to');

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
                    'message_text' => $path,
                    'us_dialogs_id' => $user_dialog_id,
                    'img' => 1
                ]);
                array_push($resp_chat, $chat);
                $my_id= (auth()->guard('api')->user()->id);
                event(new TranslationEvent($user_to, $path, $my_id, 1));


            }
        }
        //end for
        return response([
            'status' => 'success',
            'mes' => $resp_chat,
        ], 200);

    }

}
