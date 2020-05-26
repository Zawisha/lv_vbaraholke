<?php

namespace App\Http\Middleware;

use App\Status;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckBan
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */



    public function handle($request, Closure $next)
    {
        if(Auth::user()!=null)
        {
            $user_status = Status::where('user_id', '=', Auth::user()->id)->get();
            if ($user_status['0']['banned'] == '1') {
                return response()->json([
                    'status_request' => 'fail',
                    'message' => 'Ошибка доступа'
                ], 200);
            }
            else
            {
                return $next($request);
            }
        }
    }
}
