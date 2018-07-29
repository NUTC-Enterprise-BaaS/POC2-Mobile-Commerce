<?php

namespace App\Http\Middleware;

use Closure;
use App\Entities\GoBuyUser;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class tokenAuth
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
        $token = null;
        try {
             if (!$token = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['result' => 1,
                                         'message' => ['error token_not_provided']
                ]);
            }
        } catch (JWTException $e) {
            return response()->json(['result' => 1,
                                     'message' => ['could_not_create_token or token not enough']
            ], 400);
        }
        return $next($request);
    }
}
