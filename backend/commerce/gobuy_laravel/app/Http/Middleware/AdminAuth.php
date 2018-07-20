<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use App\Entities\GoBuyUser;
use App\Entities\GobuyUserUsergroupMap;
class AdminAuth
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
        if (is_null($request->user())) {
            return redirect('/');
        }
        $userGroup = GobuyUserUsergroupMap::where('user_id', $request->user()->id)
                        ->where('group_id', 8)
                        ->first();
        if (is_null($userGroup)) {
            return redirect('/');
        }
        return $next($request);
    }
}
