<?php

namespace App\Http\Controllers;

use App\Broadcasting\MessagesChannel;
use App\Categories;
use App\Events\TranslationEvent;
use App\Posts;
use App\Regions;
use App\Images;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use App\User;



class PostsController extends Controller
{
    public function render_posts_list(Request $request)
    {


        $offset = ($request->input('offset')-1)*10;
        $sort_by = $request->input('sort_by');
        $selected_reg = $request->input('selected_reg');
        $selected_cat = $request->input('selected_cat');
        if($sort_by == 1)
        {
            $order_by = 'created_at';
            $desc_asc = 'DESC';
        }
        else
        {
            $order_by = 'price';
            $desc_asc = 'ASC';
        }

        $region_flag_0= false;
        $region_flag_all = false;
        if($selected_reg == 0)
        {
            $region_flag_0 = true;
        }
        else
        {
            $region_flag_all = true;
        }
        $cat_flag_0= false;
        $cat_flag_all = false;
        if($selected_cat == 0)
        {
            $cat_flag_0 = true;
        }
        else
        {
            $cat_flag_all = true;

        }
        $posts_list = Posts::where('id', '>', 0)->where('moderation', '=', '1')
            ->when($region_flag_0 , function ($query){return $query ->where('region', '>', '0');})
            ->when($region_flag_all, function ($query) use ($selected_reg){return $query ->where('region', '=', $selected_reg);})
            ->when($cat_flag_0 , function ($query){return $query ->where('category', '>', '0');})
            ->when($cat_flag_all, function ($query) use ($selected_cat){return $query ->where('category', '=', $selected_cat);})
            ->offset($offset)->limit(10)-> with('reg')-> with('img')->orderBy($order_by, $desc_asc) ->get();
        return $posts_list;
    }

    public function count_posts(Request $request)
    {
        $posts_list = Posts::where('id', '>', 0)->where('moderation', '=', '1')->get();
        return $posts_list;
    }

    public function count_posts_user()
    {
        $posts_list = Posts::where('user_id', '=', auth()->user()->id)->get();
        return $posts_list;
    }

    public function get_post_data(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'post_id' => ['required','numeric', 'max:9999999', 'min:1'],
        ]);

        if($validator ->fails()){
            $failed = $validator->messages();
            return response([$failed, 'status' => 'fail',],200);
        }

        $post_id = $request->input('post_id');


        $post_data = Posts::where('id', '=',$post_id)
          -> with('reg')-> with('img')-> with('usr')->get();

        return $post_data;
    }

    public function render_user_posts_list(Request $request)
    {
        $offset = ($request->input('offset')-1)*10;
        $posts_list = Posts::where('user_id', '=', auth()->user()->id)
            ->offset($offset)->limit(10)-> with('reg')-> with('img') ->get();
        return $posts_list;
    }

    public function user_delete_post(Request $request)
    {
        $post_id = $request->input('post_id');
     $posts_list = Posts::where('user_id', '=', auth()->user()->id)->where('id', '=',$post_id)->delete();

     if($posts_list == 1){
        $img_list = Images::where('post_id', '=',$post_id)->get();
         foreach ($img_list as $img) {
             File::delete((public_path('/images/posts/'.$img['path'])));
         }
         Images::where('post_id', '=',$post_id)->delete();
         return response([
             'status' => 'success',
         ], 200);
     }
     else
    {
        return response([
            'status' => 'failure',
        ], 200);
    }
    }

//    public function my_event()
//    {
//
//        $my_id= (auth()->guard('api')->user()->id);
////        event(new TranslationEvent('ITS A BRITISH GRENADIRE', '11'));
//        event(new TranslationEvent('6', 'ITS A BRITISH GRENADIRE', $my_id));
//    }



}
