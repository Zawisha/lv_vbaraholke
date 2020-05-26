<?php

namespace App\Http\Controllers;

use App\RegisterStatus;
use App\Status;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\AuthController as AuthControl;


class VkController extends Controller
{

    public function vk_first(Request $request)
    {
        $id_app=6721477;
        $secret_app= 'aWT2wIraG0afZyQ3QZ4s';
        $code = $request->input('code');
        //change here url
        $url_script   =    'http://localhost/authvk';

        try
        {
            $token = json_decode((file_get_contents('https://oauth.vk.com/access_token?client_id='.$id_app.'&client_secret='.$secret_app.'&code='.$code.'&redirect_uri='.$url_script)), true);
        }
        catch (\Throwable $e) {
            return response([
                'status' => 'failure',
            ], 200);
        }

        $params = array(
            'uids'         => $token['user_id'],
            'fields'       => 'uid,first_name,last_name,screen_name,sex,email,bdate,photo_big',
            'access_token' => $token['access_token'],
            'v' => '5.103');
        try {
            $userInfo = json_decode(file_get_contents('https://api.vk.com/method/users.get' . '?' . urldecode(http_build_query($params))), true);
        }
        catch (\Throwable $e) {
            return response([
                'status' => 'failure',
            ], 200);
        }
            if(isset($userInfo['response'][0]['id']))
            {

                $vk_user = User::where('vk_id', '=', $userInfo['response'][0]['id'])->get();

                // юзер не зарегистрирован в базе через вк
                if($vk_user->isEmpty())
                {
                    $user=auth()->guard('api')->user();
                    //если юзера нет в вк и он не вошёл в систему как обычный пользователь
                    if($user=='') {
                            $user = new User;
                            $user->name = $userInfo['response'][0]['first_name'];
                            $user->vk_id = $userInfo['response'][0]['id'];
                            $user->save();
                            $user = User::find($user['id']);
                            $token = $user->createToken('Token Name')->accessToken;

                        $token_id=random_int( 100, 99999999999 ) ;
                        RegisterStatus::create([
                            'user_id' => $user['id'],
                            'token'=>$token_id,
                            'verified'=>'1'
                        ]);
                        Status::create([
                            'user_id' => $user['id'],
                            'status'=>'3',
                            'banned'=>'0'
                        ]);

                            return response()->json([
                                'status_request' => 'success',
                                'token' => $token,
                                'user' => $user,
                                'status' => 200
                            ]);
                        }
                    else
                    {
                        //если юзера нет в вк но он вошёл в систему как обычный пользователь (привязка )

                        User::where('id', '=', auth()->guard('api')->user()->id)->update([
                            'vk_id' =>$userInfo['response'][0]['id']
                        ]);
//                        $user = User::find($vk_user[0]['id']);
//                        $token = $user->createToken('Token Name')->accessToken;
                        return response()->json([
                            'status_request' => 'success_change',
                            'vk_id' =>$userInfo['response'][0]['id'],
                            'status' => 200
                        ]);

                    }

                }
                //юзер уже зарегистрирован в базе через вк
                else{

                    //если он не авторизован на сайте но зарегистрирован в базе через вк

                        $user = User::find($vk_user[0]['id']);
                        $token = $user->createToken('Token Name')->accessToken;
                        return response()->json([
                            'status_request' => 'success',
                            'token' => $token,
                            'user' => $user,
                            'status' => 200
                        ]);




                }

            }
            else
            {
                return response([
                    'status' => 'failure',
                ], 200);
            }

    }




}
