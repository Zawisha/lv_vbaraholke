<?php

namespace App\Http\Middleware;

use App\Status;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckAdmin
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
        $user_status= Status::where('user_id', '=', Auth::user()->id)->get();
        if($user_status['0']['status']=='1') {
            return $next($request);
        }
        else {
            return response()->json([
                'status_request' => 'fail',
                'message' => 'Ошибка доступа'
            ], 200);
        }
    }
}
