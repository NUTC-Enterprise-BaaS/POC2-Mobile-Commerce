<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Requests\SendCsvFileRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\PublishAdsRequest;
use App\Repositories\SpecialUserRepository;
use Excel;
use Auth;
use JWTAuth;
use Mail;
use App\Entities\GobuySocialPointsHistory;
use App\Jobs\DeleteImage;
use App\Jobs\SendCsvFile;

class SpecialUsersController extends Controller
{
    protected $specialUsers = null;

    public function __construct(SpecialUserRepository $specialUsers)
    {
        $this->specialUsers = $specialUsers;
    }
    //特約匯出csv密碼驗證
    public function checkCsvPassword(Request $request)
    {
        if (!isset($request->csv_password)) {
            return response()->json([
                'result'  => 1,
                'message' => ['The csv password field is required .']
            ]);
        }
        $check = $this->specialUsers->checkSpePassword($request->csv_password);
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
    //特約會員-匯出CSV表單發送到EMAIL
   	public function downloadCsv(SendCsvFileRequest $request)
   	{
     		$checks = $this->specialUsers->checkPwd($request->all());
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
    //特約會員 檢查APP版本
    public function checkVersion(Request $request)
    {
      if (!isset($request->version)) {
            return response()->json([
                'result' => 1,
                'message' => ['The version field is required.']
            ]);
        }
        $check = $this->specialUsers->version($request->all());
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
    //特約會員-匯出CSV密碼變更
    public function csvResetPwd(ResetPasswordRequest $request)
    {
        $userPsw = $this->specialUsers->resetPwd($request->all());
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
    //特約會員 - 刊登廣告申請
    public function publishAds()
    {
        return response()->json([
            'result' => 0,
            'message' => ['Application is successful'],
            'url' => 'http://ginkerapp.com/createads'
        ]);
    }
    //特約會員 - 特約活動 假資料
    public function speActivity()
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
          $activities = $this->specialUsers->activityList($start, $take);
          return response()->json([
              'result' => 0,
              'message' => ['special activity'],
              'sum' => count($activities),
              'items' => $activities
          ]);
        }
    }
    //特約會員 顯示自己的QR CODE
    public function getspeUserQRcode()
    {
        $userUrl = $this->specialUsers->showQRcode();
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
