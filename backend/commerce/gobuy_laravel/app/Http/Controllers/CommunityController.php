<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\CommunityRepository;
use App\Jobs\PushAppNotification;
use JWTAuth;
use App\Http\Requests;
use App\Http\Requests\SendLuckyRequest;
use PushNotification;

class CommunityController extends Controller
{
    protected $community = null;

    public function __construct(CommunityRepository $community)
    {
        $this->community = $community;
    }
    //常用頁面廣告顯示
    public function getAdvertise()
    {
    	$ad = $this->community->advertise();
    	return response()->json(['result' => 0,
                                 'message' => ['success'],
    							 'item' => $ad
    	]);
    }
    //發出商店促銷推播通知 商店優惠資訊
    public function storeNotification(Request $request)
    {
	if (!isset($request->id) || !isset($request->message)) {
            return response()->json([
                'result' => 1,
                'message' => ['input error']
            ]);
        }
        $array = $this->community->pushStore($request->all());
	$deviceTokens = $this->community->getAllDevice();
	$this->dispatch(new PushAppNotification($array, $deviceTokens));
	return response()->json($array);
    }
    //一般會員  參加紅利點數幸運刮刮卡活動
    public function luckyGet(Request $request)
    {
        $deviceTokens = $this->community->getLucky($request->all());
        if ($deviceTokens == 1) {
            return response([
                'result' => 1,
                'message' => ['error']
            ]);
        }
        $array = [
            'type' => 'scratch',
            'result' => 0,
            'message' => ['立即刮，立即送，快來分享給好友...'],
            'lucky_token' => ''
        ];
        $this->dispatch(new PushAppNotification($array, $deviceTokens));
        return response([
                'result' => 0,
                'message' => ['success']
        ]);
    }
    //測試用推播
    public function testPushLucky(Request $request)
    {
    }
    //一般會員-紅利點數幸運刮刮卡Token轉現金
    public function luckyMoney()
    {
        if (!isset($_GET['lucky_token'])) {
            return response([
                'result' => 1,
                'message' => ['The lucky token  is required']
            ]);
        }
        $token = $_GET['lucky_token'];
        $getMoney = $this->community->getMoney($token);
        if ($getMoney == 1) {
            return response([
                'result' => 1,
                'message' => ['No such data']
            ]);
        }
        return response()->json([
            'result' => 0,
            'message' => ['The prize money'],
            'lucky_money' => $getMoney
        ]);
    }
    //一般會員 將抽獎機會贈送給好友
    public function luckySend(SendLuckyRequest $request)
    {
        $deviceTokens = $this->community->getFriendLucky($request->all());
        if (!is_null($deviceTokens['check'])) {
            $array = [
                'type' => 'shareScratch',
                'result' => 0,
                'message' => ['收取由好友分享的刮刮樂...'],
                'lucky_token' => $request->lucky_token
            ];
            $deviceTokens = $deviceTokens['deviceTokens'];
            $this->dispatch(new PushAppNotification($array, $deviceTokens));
            return response()->json($array);
        }
        switch ($deviceTokens) {
            case 1:
                return response()->json([
                    'result' => 1,
                    'message' => ['No friends data']
                ]);
                break;
            case 2:
                return response()->json([
                    'result' => 1,
                    'message' => ['No the lucky token data']
                ]);
                break;
            case 3:
                return response()->json([
                    'result' => 1,
                    'message' => ['No the user device token']
                ]);
                break;
        }
    }
    // 優惠/特約會員  活動通知
    public function activityPush(Request $request)
    {
        switch ($request->type) {
            case 0:
                $activity = $this->community->pushActivity($request->type);
                $array = [
                    'type' => 'special_activity',
                    'result' => 0,
                    'message' => ['activity'],
                    'title' => '特約店 好禮大放送 活動開跑囉 !!!',
                    'url' => 'http://ginkerapp.com//'
                ];
                $this->dispatch(new PushAppNotification($array, $activity));
                return $array;
                break;
            case 1:
                $activity = $this->community->pushActivity($request->type);
                $array = [
                    'type' => 'promotions_activity',
                    'result' => 0,
                    'message' => ['activity'],
                    'title' => '優惠店 好禮大放送 活動開跑囉 !!!',
                    'url' => 'http://ginkerapp.com//'
                ];
                $this->dispatch(new PushAppNotification($array, $activity));
                return $array;
                break;
            default:
                return response()->json([
                    'result' => 1,
                    'message' => ['system error']
                ]);
                break;
        }
    }
    //使用說明
    public function showInstruction()
    {
        return response()->json([
                    'result' => 0,
                    'message' => ['success'],
                    'url' => 'http://ginkerapp.com/%E6%88%91%E7%9A%84%E8%B3%87%E8%A8%8A/%E5%AE%A2%E6%9C%8D%E4%B8%AD%E5%BF%83.html?view=kb'
        ]);
    }
    //客服中心
    public function showService()
    {
        return response()->json([
                    'result' => 0,
                    'message' => ['success'],
                    'url' => 'http://ginkerapp.com/%E6%88%91%E7%9A%84%E8%B3%87%E8%A8%8A/%E5%AE%A2%E6%9C%8D%E4%B8%AD%E5%BF%83.html'
        ]);
    }
    //瀏覽最新消息
    public function browseNews()
    {
        $start = $_GET['start'];
        $end =  $_GET['end'];
        $take = ($end-$start);
        if ($start > $end || $start < 0) {
            return response()->json(['result' => 1,
                                     'message' => ['No such data input error']
            ]);
        }
        else {
            if ($this->community->news($start, $take)) {
                return response()->json(['result' => 0,
                                         'message' => ['Inquire successful'],
                                         'news_sum' => count($this->community->news($start, $take)),
                                         'items' => $this->community->news($start, $take)
                ]);
            }
            else {
                return response()->json(['result' => 1,
                                         'message' => ['No such data input error']
                ]);
            }
        }
    }
    //一般會員-查詢最新消息的詳細內容
    public function readDetail($id)
    {
        $detail = $this->community->detail($id);
        if ($detail) {
            return response()->json(['result' => 0,
                                     'message' => ['Inquire successful'],
                                     'title' => $detail['title'],
                                     'date' => $detail['date'],
                                     'url' => $detail['url']
            ]);
        }
        return response()->json(['result' => 1,
                                 'message' => ['No such data input error']
        ]);
    }
    //瀏覽特約最新消息
    public function browseNewsSpe()
    {
        $start = $_GET['start'];
        $end =  $_GET['end'];
        $take = ($end-$start);
        if ($start > $end || $start < 0) {
            return response()->json(['result' => 1,
                                     'message' => ['No such data input error']
            ]);
        }
        else {
            if ($this->community->newsSpe($start, $take)) {
                return response()->json(['result' => 0,
                                         'message' => ['Inquire successful'],
                                         'news_sum' => count($this->community->newsSpe($start, $take)),
                                         'items' => $this->community->newsSpe($start, $take)
                ]);
            }
            else {
                return response()->json(['result' => 1,
                                         'message' => ['No such data input error']
                ]);
            }
        }
    }
    //瀏覽特約最新消息  ---- 詳細內容
    public function readSpeDetail($id)
    {
        $detail = $this->community->detailSpe($id);
        if ($detail) {
            return response()->json(['result' => 0,
                                     'message' => ['Inquire successful'],
                                     'title' => $detail['title'],
                                     'date' => $detail['date'],
                                     'url' => $detail['url']
            ]);
        }
        return response()->json(['result' => 1,
                                 'message' => ['No such data input error']
        ]);
    }
    //瀏覽優惠最新消息
    public function browseNewsPre()
    {
        $start = $_GET['start'];
        $end =  $_GET['end'];
        $take = ($end-$start);
        if ($start > $end || $start < 0) {
            return response()->json(['result' => 1,
                                     'message' => ['No such data input error']
            ]);
        }
        else {
            if ($this->community->newsPre($start, $take)) {
                return response()->json(['result' => 0,
                                         'message' => ['Inquire successful'],
                                         'news_sum' => count($this->community->newsPre($start, $take)),
                                         'items' => $this->community->newsPre($start, $take)
                ]);
            }
            else {
                return response()->json(['result' => 1,
                                         'message' => ['No such data input error']
                ]);
            }
        }
    }
    //瀏覽優惠最新消息  ---- 詳細內容
    public function readPreDetail($id)
    {
        $detail = $this->community->detailPre($id);
        if ($detail) {
            return response()->json(['result' => 0,
                                     'message' => ['Inquire successful'],
                                     'title' => $detail['title'],
                                     'date' => $detail['date'],
                                     'url' => $detail['url']
            ]);
        }
        return response()->json(['result' => 1,
                                 'message' => ['No such data input error']
        ]);
    }
}
