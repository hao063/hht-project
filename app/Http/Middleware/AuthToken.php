<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthToken
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('authorization');
        $user  = User::where('token_login', $token)->where('token_login', '<>',null)->first();
        if (!is_null($user)) {
            Auth::loginUsingId($user->id);
            return $next($request);
        }
        return response()->json([
            'status'  => 403,
            'message' => 'No login session',
            'data'    => [],
        ], 403);
    }

}
