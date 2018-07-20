<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Model\LuckyForm;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('AdminAuth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function gobuyPush()
    {
        return view('gobuynotification');
    }
    public function LuckyRecord()
    {
        $datas = DB::table('lucky_forms')
                    ->join('gobuy_jbusinessdirectory_users', 'gobuy_jbusinessdirectory_users.id', '=', 'lucky_forms.user_id')
                    ->select('lucky_forms.*', 'gobuy_jbusinessdirectory_users.*')
                    ->orderBy('lucky_forms.id', 'desc')
                    ->get();
        return view('luckyrecord', compact('datas'));
    }
}
