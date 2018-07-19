<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('signup', 'AuthController@register');
Route::post('login', 'AuthController@login');
Route::post('ldap/getPoint', 'LdapController@ldapUserPoint');
Route::post('ldap/useradd', 'LdapController@ldapAdd');
Route::post('ldap/point/change', 'LdapController@ldapUserPointChange');
Route::post('ldap/u2u/useradd', 'LdapController@addLdapU2U');
Route::post('ldap/showStor', 'LdapController@showUserStor');
Route::post('ldap/getverifycode', 'LdapController@getverifycode');

Route::group(['middleware' => 'jwt.auth'], function(){
  Route::get('auth/user', 'AuthController@user');
});

Route::group(['middleware' => 'jwt.auth'], function(){
   Route::post('auth/logout', 'AuthController@logout');
});

Route::middleware('jwt.refresh')->get('/token/refresh', 'AuthController@refresh');

