<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\RegisterStatus;
use App\Status;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    public function register(Request $request)
    {


        $input = $request->all();
        $validator = Validator::make($input, [
            'username' => ['required', 'string',  'max:30', 'min:2', 'alpha'],
            'email' => ['required', 'string', 'email', 'max:50', 'unique:users'],
            'password' => ['required', 'string', 'min:6','max:30'],
            'mobile' => ['required', 'digits:12'],
        ]);

        if($validator ->fails()){

            // $failed = $validator->messages();
            $failed = $validator->messages();
            return response([$failed, 'status' => 'fail',],200);
        }
        else
        {
            //if OK
            $token=random_int( 100, 99999999999 ) ;
//            $new_mail = new MailCont;
//            $new_mail-> send($token, $email_user);

            $user = new User;
            $user->email = $request->email;
            $user->name = $request->username;
            $user->mobile = $request->mobile;
            $user->password = Hash::make($request->password);
            $user->vk_id = '';
            $user->save();


            RegisterStatus::create([
                'user_id' => $user['id'],
                'token'=>$token,
                'verified'=>'0'
            ]);

            Status::create([
                'user_id' => $user['id'],
                'status'=>'3',
                'banned'=>'0'
            ]);

            return response([
                'status' => 'success',
                'data' => $user
            ], 200);
        }

    }

    public function login()
    {

        //dodelatb proverku na pustoi logon ili parol
        $email= request('email');
        // Проверяем существует ли пользователь с указанным email адресом

        $user = User::where('email','=',$email)->first();


        if (!$user) {
            return response()->json([
                'message' => 'Не верный логин или пароль',
                'status' => 422
            ], 422);
        }

        // Если пользователь с таким email адресом найден - проверим совпадает
        // ли его пароль с указанным
        if (!Hash::check(request('password'), $user->password)) {
            return response()->json([
                'message' => 'Не верный логин или пароль',
                'status' => 422
            ], 422);
        }

        $user_valid = Status::where('user_id', '=',  $user['id'])->first();
        if($user_valid['banned']==1) {
            return response()->json([
                'message' => 'Пользователь забанен',
                'status' => 422
            ], 422);
        }

        $user_valid = RegisterStatus::where('user_id', '=',  $user['id'])->first();
        if($user_valid['verified']==0) {
            return response()->json([
                'message' => 'Пользователь не подтвердил email',
                'status' => 422
            ], 422);
        }

        // Внутренний API запрос для получения токенов
        $client = DB::table('oauth_clients')
            ->where('password_client', true)
            ->first();

        // Убедимся, что Password Client существует в БД (т.е. Laravel Passport
        // установлен правильно)
        if (!$client) {
            return response()->json([
                'message' => 'Laravel Passport is not setup properly.',
                'status' => 500
            ], 500);
        }

        $data = [
            'grant_type' => 'password',
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'username' => request('email'),
            'password' => request('password'),
        ];

        $request = Request::create('/oauth/token', 'POST', $data);

        $response = app()->handle($request);

        // Проверяем был ли внутренний запрос успешным
        if ($response->getStatusCode() != 200) {
            return response()->json([
                'message' => 'Не верный логин или пароль',
                'status' => 422
            ], 422);
        }

        // Вытаскиваем данные из ответа
        $data = json_decode($response->getContent());

        // Формируем окончательный ответ в нужном формате
        return response()->json([
            'token' => $data->access_token,
            'user' => $user,
            'status' => 200
        ]);
    }

    public function logout()
    {
        $accessToken = Auth::user()->token();
        DB::table('oauth_refresh_tokens')
            ->where('access_token_id', $accessToken->id)
            ->update([
                'revoked' => true
            ]);
        $accessToken->revoke();
        return response()->json([
            'user' => 'logout',
            'status' => 200
        ]);
        return response()->json(null, 200);
    }
    public function getUser()
    {

        return Auth::user();

    }

    public function is_pas()
    {
        //$pas_list = User::select('password')->where('id', '=', auth()->guard('api')->user()->id)->get();
        $pas_list = User::whereNull('password')->where('id', '=', auth()->guard('api')->user()->id)->get();
        return $pas_list;
    }

    public function get_user_info()
    {
             $posts_list = User::where('id', '=', auth()->user()->id)
             -> with('reg')->get();
    }
    public function get_user_info_channel()
    {
       // return User::class;
      return $user = User::where('id', '=', auth()->guard('api')->user()->id)->get();

       // return $user =auth()->guard('api')->user()->id;
    }

    public function confirm_token(Request $request)
    {
        $user_id = $request->input('user_id');
        $token = $request->input('token');
        $user_valid = RegisterStatus::where('user_id', '=',  $user_id)->where('token', '=', $token)->first();
        if($user_valid)
        {
       if($user_valid['verified']==0)
       {
           RegisterStatus::where('user_id', '=', $user_id)->update(['verified' =>'1']);

           //auth user
           $user = User::find($user_id);
           $token = $user->createToken('Token Name')->accessToken;

           return response()->json([
               'status_request' => 'success',
               'token' => $token,
               'user' => $user,
               'status' => 200
           ]);
       }
          else
          {
              return response()->json([
                  'status_request' => 'fail',
                  'message' => 'Ошибка токена'
              ], 200);
          }

        }
        else
        {
                return response()->json([
                    'status_request' => 'fail',
                    'message' => 'Ошибка токена'
                ], 200);
        }

    }

    public function reset_password(Request $request)
    {

        $password = $request->input('password');

        $user=User::where('id', '=', auth()->user()->id)->get();


        if ( Hash::check($password, $user[0]['password']))
        {
            $check_password = $request ->all();

            $validator = Validator::make($check_password, [
                'new_password' => ['required', 'string', 'min:6','max:30'],
                'email' => ['required', 'string', 'email', 'max:50', 'unique:users']
            ]);

            if($validator ->fails()){

                $failed = $validator->messages();
                return response(['message' =>$failed, 'status_request' => 'er_message',],200);
            }

            else
            {
                $new_password = $request->input('new_password');
                $email = $request->input('email');
                $new_password = Hash::make($new_password);
                User::where('id', '=', auth()->user()->id)->update(['password' =>$new_password]);
                User::where('id', '=', auth()->user()->id)->update(['email' =>$email]);
                return response()->json([
                    'status_request' => 'success',
                    'message' => 'Пароль изменён'
                ], 200);
            }


        }
        else
        {
            return response()->json([
                'status_request' => 'fail',
                'message' => 'Не верный старый пароль'
            ], 200);
        }


    }

    public function set_password(Request $request)
    {
        $check_password = $request ->all();

        $validator = Validator::make($check_password, [
            'password' => ['required', 'string', 'min:6','max:30'],
            'email' => ['required', 'string', 'email', 'max:50', 'unique:users']
        ]);
        if($validator ->fails()){
            $failed = $validator->messages();
            return response(['message' =>$failed, 'status_request' => 'er_message',],200);
        }
        else
        {
            $new_password = $request->input('password');
            $email = $request->input('email');
            $new_password = Hash::make($new_password);
            User::where('id', '=', auth()->user()->id)->update(['password' =>$new_password]);
            User::where('id', '=', auth()->user()->id)->update(['email' =>$email]);
            return response()->json([
                'status_request' => 'success',
                'message' => 'Пароль изменён'
            ], 200);
        }
    }


    public function change_mobile(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'mobile' => ['required', 'digits:12'],
        ]);

        if($validator ->fails()){
            $failed = $validator->messages();
            return response([$failed, 'status' => 'fail',],200);
        }
        else
        {
            User::where('id', '=', auth()->user()->id)->update([
                'mobile' =>$request->input('mobile')
            ]);
            return response([
                'status' => 'success',
            ], 200);
        }
    }

    public function change_email(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'email' => ['required', 'string', 'email', 'max:50', 'unique:users']
        ]);

        if($validator ->fails()){
            $failed = $validator->messages();
            return response([$failed, 'status' => 'fail',],200);
        }
        else
        {
            User::where('id', '=', auth()->user()->id)->update([
                'email' =>$request->input('email')
            ]);
            return response([
                'status' => 'success',
            ], 200);
        }
    }

    public function is_ban()
    {
        $user_valid = Status::where('user_id', '=',  auth()->guard('api')->user()->id)->first();
        if($user_valid['banned']==1) {
            return response()->json([
                'message' => 'Пользователь забанен',
                'status' => 'fail'
            ], 200);
        }
        else
        {
            return response([
                'status' => 'success',
            ], 200);
        }
    }


}
