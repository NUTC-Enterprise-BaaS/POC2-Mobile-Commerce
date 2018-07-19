<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterFormRequest;
use JWTAuth;
use Auth;

class AuthController extends Controller
{
	public function registerRules($request){
		$v = Validator::make($request->all(),[
            'name' => 'required|string|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|max:10',
        ]);
	}
    public function register(RegisterFormRequest $request)
	{
	    $user = new User;
	    $user->email = $request->email;
	    $user->name = $request->name;
	    $user->password = bcrypt($request->password);
	    $user->save();
	    $credentials = $request->only('email', 'password');
		$token = JWTAuth::attempt($credentials);
	    return response([
	        'status' => 'success',
	        'token' => $token
	       ], 200);
	}
	public function login(Request $request)
	{
	    $credentials = $request->only('email', 'password');
	    if ( ! $token = JWTAuth::attempt($credentials)) {
	            return response([
	                'status' => 'fail',
	                'msg' => 'Invalid Credentials.'
	            ], 400);
	    }
	    return response([
	            'status' => 'success',
	            'token' => $token
	        ]);
	}
	public function user(Request $request)
	{
	    $user = User::find(Auth::user()->id);
	    return response([
	            'status' => 'success',
	            'data' => $user
	        ]);
	}
	public function logout()
	{
	    JWTAuth::invalidate();
	    return response([
	            'status' => 'success',
	            'msg' => 'Logged out Successfully.'
	        ], 200);
	}
	public function refresh()
    {
        return response([
         'status' => 'success',

        ]);
    }
}
