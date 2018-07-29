<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Http\Requests;
use App\Entities\GobuyUser;
use App\Entities\GobuyUserUsergroupMap;
use Session;

class AdminController extends Controller
{
    public function adminLogin(Request $request)
    {
        $check = Auth::attempt(['username' => $request->username, 'password' => $request->password]);
        if ($check == false) {
            return redirect('/');
        }
        $user = GobuyUser::where('username', $request->username)
                        ->first();
        $userGroup = GobuyUserUsergroupMap::where('user_id', $user['id'])
                        ->where('group_id', 8)
                        ->first();
        if (is_null($userGroup)) {
            return redirect('/');
        }
        return redirect('gobuynotification');
    }
}
