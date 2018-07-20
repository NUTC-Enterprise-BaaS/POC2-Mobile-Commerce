<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\PointRepository;
use App\Http\Requests;
use App\Http\Requests\ReadPointsRequest;
use App\Http\Requests\SendPointsRequest;
use App\Http\Requests\SendSpecialPointsRequest;
use App\Http\Requests\WaitPointsRequest;
use App\Http\Requests\PhoneSendRequest;
use App\Http\Requests\ReceivePointsRequest;
use App\Entities\MCrypt;
use App\Entities\GobuyUserProfile;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Jobs\PushAppNotification;
use App\Jobs\StoreSendPoints;
use PushNotification;

class PointsController extends Controller
{
    protected $points = null;

    public function __construct(PointRepository $points)
    {
        $this->points = $points;
    }

    //特約店會員 瀏覽待送點數會員列表
    public function specialBrowserPoint(ReadPointsRequest $request)
    {
        if ($request->timestamp_start > $request->timestamp_end ||
            $request->timestamp_start < 0
            ) {
                return response()->json(['result' => 1,
                                         'message' => ['No such data input error']
                ]);
        }
        $items = $this->points->speReadPoint($request->all());
        return response()->json(['result' => 0,
                                 'message' => ['Inquire successful'],
                                 'items' => $items
        ]);
    }
    //優惠會員 瀏覽待送點數會員列表
    public function preBrowserPoint(ReadPointsRequest $request)
    {
        if ($request->timestamp_start > $request->timestamp_end ||
            $request->timestamp_start < 0
            ) {
                return response()->json(['result' => 1,
                                         'message' => ['No such data input error']
                ]);
        }
        $items = $this->points->preReadPoint($request->all());
        return response()->json(['result' => 0,
                                 'message' => ['Inquire successful'],
                                 'items' => $items
        ]);
    }
    //一般會員瀏覽點數 增減紀錄
    public function browsePointRecord(ReadPointsRequest $request)
    {
        if ($request->timestamp_start > $request->timestamp_end ||
            $request->timestamp_start < 0
            ) {
                return response()->json(['result' => 1,
                                         'message' => ['No such data input error']
                ]);
        }
        $items = $this->points->record($request->all());
        return response()->json(['result' => 0,
                                 'message' => ['Inquire successful'],
                                 'points' => $items['num'],
                                 'cost' => abs($items['costs']),
                                 'items' => $items['array']
        ]);
    }
    //一般會員-紅利點數轉讓 推播
    public function sendPoints(SendPointsRequest $request)
    {
        $points = $this->points->transferPoints($request->all());
        switch ($points) {
            case 1:
                return response()->json(['result' => 1,
                                         'message' => ["Recipient's email error"]
                ]);
                break;
            case 2:
                return response()->json(['result' => 1,
                                         'message' => ["Recipient's phone error"]
                ]);
                break;
            case 3:
                return response()->json(['result' => 1,
                                         'message' => ["The user's password is incorrect"]
                ]);
                break;
            case 4:
                return response()->json(['result' => 1,
                                         'message' => ["The user is not enough points"]
                ]);
                break;
            case 5:
                return response()->json(['result' => 1,
                                         'message' => ["The user's device is incorrect"]
                ]);
                break;
            case 6:
                return response()->json(['result' => 1,
                                         'message' => ["Recipient is blocked"]
                ]);
                break;
            default:
		$this->dispatch(new PushAppNotification($points, $points['deviceToken']));
                return response()->json(['type' => 'transferPoint',
                                         'result' => 0,
                                         'message' => ['Transfer Success']
                ]);
                break;
        }
    }
    //一般會員等待領取紅利點數 推播 確認 或 取消
    public function receivePoints(ReceivePointsRequest $request)
    {
        $points = $this->points->receiveUserPoints($request->all());
        if ($points == 0) {
            return response()->json([
                'type' => 'confirmPoint',
                'result' => 1,
                'message' => ["The user's device is incorrect"]
            ]);
        }
        $this->dispatch(new PushAppNotification($points, $points['deviceToken']));
        return response()->json([
            'type' => 'confirmPoint',
            'result' => 0,
            'message' => ['success']
        ]);
    }
    //特約會員，給最新一筆消費紀錄發送點數 推播
    public function sendSpecialPoints(SendSpecialPointsRequest $request)
    {
        $sendPoints = $this->points->SendUserPoints($request->all());
        if ($sendPoints == 1) {
            return response()->json(['result' => 1,
                                     'message' => ['Input data errors']
            ]);
        }
        if ($sendPoints == 2) {
            return response()->json(['result' => 1,
                                     'message' => ['Points are not enough']
            ]);
        }
        $deviceTokens = $this->points->phoneToDeviceToken($request->phone_number);
        $this->dispatch(new StoreSendPoints($sendPoints, $deviceTokens));
        return response()->json($sendPoints);
    }
    //一般會員等待領取紅利點數
    public function waitPoints(WaitPointsRequest $request)
    {
        $check = $this->points->waitShopPoints($request->all());
        if ($check == 1) {
            return response()->json(['result' => 1,
                                     'message' => ['No such store information']
            ]);
        }
        return response()->json(['result' => 0,
                                 'message' => ['success']
        ]);
    }
    //特約會員-發送點數-確認會員是否存在
    public function checkSepcialPoints()
    {
        $encryption = new  MCrypt();
        $AESCode = $_GET['aes_encode'];
        $AESCode = str_replace(' ', '+', $AESCode);
        $phone = $encryption->decrypt($AESCode);
        $check = $this->points->checkPhone($phone);
        if (is_null($check)) {
            return response()->json(['result' => 1,
                                     'message' => ['This member does not exist']
            ]);
        }
        return response()->json(['result' => 0,
                                 'message' => ['This member exists']
        ]);
    }
    //特約會員-瀏覽發送紅利點數紀錄
    public function browseSpecialSendPoints(ReadPointsRequest $request)
    {
        if ($request->timestamp_start > $request->timestamp_end ||
            $request->timestamp_start < 0
            ) {
                return response()->json(['result' => 1,
                                         'message' => ['No such data input error']
                ]);
        }
        $items = $this->points->specialRecord($request->all());
        return response()->json(['result' => 0,
                                 'message' => ['Inquire successful'],
                                 'items' => $items
        ]);
    }
    //特約會員-直接透過號碼發送點數
    public function spePhoneSendPoints(PhoneSendRequest $request)
    {
        $points = $this->points->spePhoneSend($request->all());
        if ($points == 1) {
            return response()->json(['result' => 1,
                                     'message' => ['Failed to send the number does not exist or store fail']
            ]);
        }
        if ($points == 2) {
            return response()->json(['result' => 1,
                                     'message' => ['Points are not enough']
            ]);
        }
        $deviceTokens = $this->points->phoneToDeviceToken($request->phone_number);
        $this->dispatch(new StoreSendPoints($points, $deviceTokens));
        return response()->json(['result' => 0,
                                 'message' => ['Sent successfully']
        ]);
    }
    //優惠會員-直接透過號碼扣除點數
    public function prePhoneSendPoints(PhoneSendRequest $request)
    {
        $points = $this->points->prePhoneSend($request->all());
        if ($points == 1) {
            return response()->json(['result' => 1,
                                     'message' => ['Failed to send the number does not exist or store fail']
            ]);
        }
        if ($points == 2) {
            return response()->json(['result' => 1,
                                     'message' => ['Points are not enough']
            ]);
        }
        $deviceTokens = $this->points->phoneToDeviceToken($request->phone_number);
        $this->dispatch(new StoreSendPoints($points, $deviceTokens));
        return response()->json(['result' => 0,
                                 'message' => ['Sent successfully']
        ]);
    }
    //優惠會員-瀏覽扣除紅利點數紀錄
    public function browsePreSendPoints(ReadPointsRequest $request)
    {
        if ($request->timestamp_start < 0) {
                return response()->json(['result' => 1,
                                         'message' => ['No such data input error']
                ]);
        }
        $items = $this->points->preRecord($request->all());
        return response()->json(['result' => 0,
                                 'message' => ['Inquire successful'],
                                 'items' => $items
        ]);
    }
    //優惠會員_給最新一筆消費紀錄扣除點數
    public function sendPrePoints(SendSpecialPointsRequest $request)
    {
        $sendPoints = $this->points->sendPreUserPoints($request->all());
        if ($sendPoints == 1) {
            return response()->json(['result' => 1,
                                     'message' => ['Input data errors']
            ]);
        }
        if ($sendPoints == 2) {
            return response()->json(['result' => 1,
                                     'message' => ['Points are not enough']
            ]);
        }
        $deviceTokens = $this->points->phoneToDeviceToken($request->phone_number);
        $this->dispatch(new StoreSendPoints($sendPoints, $deviceTokens));
        return response()->json($sendPoints);
    }
}
