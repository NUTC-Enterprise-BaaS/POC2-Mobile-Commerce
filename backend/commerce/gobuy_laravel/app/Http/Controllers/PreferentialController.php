<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\PreferentialRepository;
use App\Entities\GobuyJbusinessdirectoryCompany;
use App\Http\Requests;
use App\Http\Requests\SendCsvFileRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Jobs\DeleteImage;
use App\Jobs\SendCsvFile;
use Excel;
use Auth;
use JWTAuth;

class PreferentialController extends Controller
{
	protected $preferentials = null;

    public function __construct(PreferentialRepository $preferentials)
    {
        $this->preferentials = $preferentials;
    }
    //優惠匯出csv密碼驗證
    public function checkCsvPassword(Request $request)
    {
        if (!isset($request->csv_password)) {
            return response()->json([
                'result'  => 1,
                'message' => ['The csv password field is required .']
            ]);
        }
        $check = $this->preferentials->checkPrePassword($request->csv_password);
        if ($check == 1) {
          return response()->json([
              'result' => 1,
              'message' => ['Incorrect password']
          ]);
        } else {
            return response()->json([
                'result' => 0,
                'message' => ['success']
            ]);
        }
    }
    //優惠會員  瀏覽基本資料
    public function preUserDetail()
    {
         $preUser = $this->preferentials->preDetail();
         return response()->json([
            'result' => 0,
            'message' => ['User Information'],
            'user_account' => $preUser['user_account'],
            'user_birthday' => $preUser['user_birthday'],
            'user_country' => $preUser['user_country'],
            'user_email' => $preUser['user_email'],
            'store_address' => $preUser['store_address'],
            'store_url' => $preUser['store_url'],
            'store_contact' => $preUser['store_contact'],
            'store_name' => $preUser['store_name']
        ]);
    }
    //優惠會員 檢查APP版本
    public function checkVersion(Request $request)
    {
    	if (!isset($request->version)) {
            return response()->json([
                'result' => 1,
                'message' => ['The version field is required.']
            ]);
        }
        $check = $this->preferentials->version($request->all());
        if ($check) {
            return response()->json([
                'result' => 0,
                'message' => ['Please update APP version']
            ]);
        }
        else {
            return response()->json([
                'result' => 0,
                'message' => ['This APP is the latest version']
            ]);
        }
    }
    //優惠會員-匯出CSV表單發送到EMAIL
    public function downloadCsv(SendCsvFileRequest $request)
    {
        $checks = $this->preferentials->checkPwd($request->all());
        if ($checks == 0) {
            return response()->json([
                'result' => 1,
                'message' => ['Incorrect password']
            ]);
        }
        $fileName = strval(time()).str_random(5);
        $points[] = ['編號', '手機號碼', '消費點數'];
        if ($checks != []) {
            foreach ($checks as $key => $check) {
                $array[] = [
                    $check['id'],
                    $check['phone_number'],
                    $check['money']
                ];
            }
            $points = array_merge($points, $array);
        }
        Excel::create($fileName, function($excel) use ($points, $fileName){
            $excel->sheet('point', function($sheet) use($points){
                $sheet->setSize([
                    'B1' => [
                        'width'     => 15
                    ],
                    'C1' => [
                        'width'     => 20
                    ]
                ]);
                $sheet->rows($points);
            });
        })->save('csv');
        $this->sendEmailWithAttachment($fileName);
        $job = (new DeleteImage())->delay(60 * 5);
        $this->dispatch($job);
        return response()->json([
            'result' => 0,
            'message' => ['send success']
        ]);
    }
    public function sendEmailWithAttachment($fileName)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $csvPath = public_path('exports/') . $fileName . '.csv';
        $user = ['email' => $user['email'], 'name' => $user['name'], 'path' => $csvPath];
        $this->dispatch(new SendCsvFile($user));
    }
    //優惠會員-匯出CSV密碼變更
    public function csvResetPwd(ResetPasswordRequest $request)
    {
        $userPsw = $this->preferentials->resetPwd($request->all());
        switch ($userPsw) {
            case 0:
                return response()->json([
                    'result' => 0,
                    'message' => ['reset password success']
                ]);
                break;
            case 1:
                return response()->json([
                    'result' => 1,
                    'message' => ['Update fails, the new password is null']
                ]);
                break;
            case 2:
                return response()->json([
                    'result' => 2,
                    'message' => ['Update fails, the old password is incorrect']
                ]);
                break;
            case 3:
                return response()->json([
                    'result' => 3,
                    'message' => ['Update fails, the old and new password are the same']
                ]);
                break;
        }
    }
    //優惠會員-優惠活動 假資料
    public function preActivity()
    {
        $start = $_GET['start'];
        $end =  $_GET['end'];
        $take = ($end-$start);
        if ($start > $end || $start < 0) {
            return response()->json([
                'result' => 1,
                'message' => ['No such data input error']
            ]);
        }
        else {
            $activities = $this->preferentials->activityList($start, $take);
            return response()->json([
              'result' => 0,
              'message' => ['preferentials activity'],
              'sum' => count($activities),
              'items' => $activities
          ]);
        }
    }
    //優惠會員 顯示自己的QR code
    public function getpreUserQRcode()
    {
        $userUrl = $this->preferentials->showQRcode();
        if ($userUrl == 1) {
          return response()->json([
              'result' => 1,
              'message' => ['error']
          ]);
        } else {
          return response()->json([
              'result' => 0,
              'message' => ['success'],
              'url' => $userUrl
          ]);
        }
    }
}
