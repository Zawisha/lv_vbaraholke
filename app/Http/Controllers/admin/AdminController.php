<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Images;
use App\Posts;
use App\Status;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AdminController extends Controller
{
    public function is_admin()
    {
        $status = Status::where('user_id', '=', auth()->user()->id)->get();
        return $status;
    }


    public function users_list_pagination(Request $request)
    {
        $offset = ($request->input('offset')-1)*10;
        $users_list = User::with('user_status')->where('id', '>', '0')->offset($offset)->limit(10)->get();
        return $users_list;
    }

    public function users_list()
    {
        $users_list = User::with('user_status')->where('id', '>', '0')->get();
        return $users_list;
    }

    public function change_user_role(Request $request)
    {
        $user_id = $request->input('user_id');
        $user_role = $request->input('user_role');
        Status::where('user_id', '=', $user_id)->update(['status' =>$user_role]);
    }

    public function change_user_banned(Request $request)
    {
        $user_id = $request->input('user_id');
        $user_banned = $request->input('user_banned');
        Status::where('user_id', '=', $user_id)->update(['banned' =>$user_banned]);
    }

    public function render_posts_list_admin(Request $request)
    {
        $offset = ($request->input('offset')-1)*10;
        $posts_list = Posts::where('id', '>', 0)->where('moderation', '=', '0')
            ->offset($offset)->limit(10)-> with('reg')-> with('img')->get();
        return $posts_list;
    }
    public function count_posts_admin(Request $request)
    {
        $posts_list = Posts::where('id', '>', 0)->where('moderation', '=', '0')->get();
        return $posts_list;
    }
    public function save_posts_admin(Request $request)
    {
        $posts = $request->input('posts');

        foreach ($posts as $post) {
            Posts::where('id', '=', $post['id'])->update(['moderation'=> $post['moderation']]);
        }
        return response([
            'status' => 'success',
        ], 200);
    }
    public function admin_delete_post(Request $request)
    {
        $post_id = $request->input('post_id');
        $posts_list = Posts::where('id', '=',$post_id)->delete();
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

}
