<?php
namespace App\Repositories;

use Auth;
use JWTAuth;
use DB;
use App\Entities\GobuyBanner;
use App\Entities\LuckyForm;
use App\Entities\UserDeviceToken;
use App\Entities\GobuyUserProfile;
use App\Entities\GobuyUser;
use App\Entities\GobuyUserUsergroupMap;
use App\Entities\GobuyJbusinessdirectoryCompany;
use App\Entities\GobuySocialPointsHistory;
use App\Entities\GobuyContent;
use PushNotification;

class CommunityRepository
{
    public function advertise()
    {
        $ads = DB::table('gobuy_banners')->get();
        foreach ($ads as $key => $ad) {
          $array[] = ['advertise_id' => $ad->id,
                    'advertise_url' => $ad->params
          ];
        }
        return $array;
    }
    public function pushStore($params)
    {
        $shop = GobuyJbusinessdirectoryCompany::where('id', $params['id'])->first();
        $array = [
            'type' => 'promotions',
            'result' => 0,
            'message' => ['Push Notification Success'],
            'shop_id' => $shop->id,
            'shop_name' => $shop->name,
            'shop_photo' => '',
            'shop_url' => $shop->website,
            'shop_message' => $params['message']
        ];
        return $array;
    }
    //取得全部使用者 裝置token
    public function getAllDevice()
    {
        $devices = DB::table('user_device_tokens')->where('device_token','<>','')->get();
        foreach ($devices as $key => $device) {
            $array[] = ['device' => $device->device,
                        'device_token' => $device->device_token,
            ];
            if (is_null($device->device_token)) {
                unset($array[$key]);    //刪除空的
            }
        }
        $array = array_values($array);  //重整陣列
        return $array;
    }
    //測試取得單一使用者裝置token
    public function getOneDevice()
    {
        $array[] = [
            'device' => 0,
            'device_token' => 'ec437Lcmlxo:APA91bFf8l9gMW_ImHOUrDVrnkSGgpl8zn7xsJfsCeIbpEgRqGOQsavNKBBRWFR0l8dRXm5RY3nbrqE-4hOKHuAqXMPD8NGODOiBEIxBDkMBZFa8FiBkhfl1e9Nr1HSxEqhmp76gm8QY'
        ];
        return $array;
    }
    public function getLucky($params)
    {
        if ($params['start'] < 0 || $params['end'] <= 0 || $params['start'] >= $params['end'] || $params['point'] <= 0 || $params['award_num'] <= 0) {
            return 1;
        }
        $points = DB::table('gobuy_social_points_history')
                    ->join('user_device_tokens', 'gobuy_social_points_history.user_id', '=', 'user_device_tokens.user_id')
                    ->select(DB::raw('sum(gobuy_social_points_history.points) as points, gobuy_social_points_history.user_id, user_device_tokens.device, user_device_tokens.device_token'))
                    ->groupBy('gobuy_social_points_history.user_id')
                    ->havingRaw('sum(gobuy_social_points_history.points) > ' . $params['start'])
                    ->havingRaw('sum(gobuy_social_points_history.points) < ' . $params['end'])
                    ->get();
        $memberCount = count($points);
        if ($memberCount == 0 || $params['award_num'] > $memberCount) {
            return 1;
        }
        for ($i=0; $i < $params['award_num']; $i++) {
            $luckyAward[] = 1;
        }
        for ($j=$memberCount; $j > $params['award_num']; $j--) {
            $luckyAward[] = 0;
        }
        $luckyCount = count($luckyAward) - 1;
        $array = [];
        foreach ($points as $key => $point) {
                $rand = rand(0, $luckyCount);
                if ($luckyAward[$rand] == 1) {
                    $token = md5(rand());   //產生刮刮樂token
                    $money = (int)$params['point']; //亂數刮刮樂金額
                } else {
                    $token = md5(rand());   //產生刮刮樂token
                    $money = 0; //亂數刮刮樂金額
                }
                $array[] = [
                    'user_id' => $point->user_id,
                    'device' => $point->device,
                    'device_token' => $point->device_token,
                    'lucky_token' => $token,
                    'lucky_money' => $money
                ];
                if (empty($point->device_token)) {
                    unset($array[$key]);            //刪除deviceToken　為空的
                }
                $array = array_values($array);  //重整陣列
                LuckyForm::create([
                    'user_id' => $point->user_id,
                    'token' => $token,
                    'money' => $money
                ]);
                unset($luckyAward[$rand]);
                $luckyAward = array_values($luckyAward);  //重整陣列
                $luckyCount -= 1;
        }
        if ($array == []) {
            return 1;
        }
        return $array;
    }
    public function getOneLucky($params)
    {
        
    }
    public function getMoney($params)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $lucky = LuckyForm::where('user_id', $user['id'])
                ->where('token', $params)
                ->where('state', 0)
                ->first();
        if (is_null($lucky)) {
            return 1;
        }
        LuckyForm::where('user_id', $user['id'])
                ->where('token', $params)
                ->update(['state' => 1]);
        if ($lucky['money'] != 0) {
            GobuySocialPointsHistory::create([
                'points_id' => 5,
                'user_id' => $user['id'],
                'points' => $lucky['money'],
                'created' => date("Y-m-d H:i:s"),
                'state' => 1,
                'message' => '參加活動獲得刮刮樂點數 ' . $lucky['money']
            ]);
        }
        return $lucky['money'];
    }
    public function getFriendLucky($params)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $email = GobuyUser::where('email', $params['friend_email'])->first();
        $phone = GobuyUserProfile::where('profile_value', $params['friend_phone'])
                                  ->where('ordering', 2)
                                  ->where('user_id', $email['id'])
                                  ->first();
        $getLucky = LuckyForm::where('token', $params['lucky_token'])
                            ->where('user_id', $user['id'])
                            ->where('state', 0)
                            ->first();
        if (is_null($phone) || is_null($email)) {
            return 1;
        }
        if (is_null($getLucky)) {
            return 2;
        }
        $check = UserDeviceToken::where('email', $params['friend_email'])->first();
        $deviceTokens = UserDeviceToken::where('email', $params['friend_email'])->get();
        if (is_null($check['device_token'])) {
            return 3;
        }
        LuckyForm::where('token', $params['lucky_token'])
                    ->update(['user_id' => $email['id']]);
        $array = [
            'deviceTokens' => $deviceTokens,
            'check' => $check
        ];
        return $array;
    }
    public function pushActivity($params)
    {
        if ($params == 0) {
            $users = GobuyUserUsergroupMap::where('group_id', 14)
                                ->orWhere('group_id', 16)
                                ->orWhere('group_id', 18)
                                ->orWhere('group_id', 20)
                                ->get();
            foreach ($users as $key => $user) {
                $deviceTokens = UserDeviceToken::where('user_id', $user->user_id)
                                                ->whereNotNull('device_token')
                                                ->get();
                foreach ($deviceTokens as $key => $deviceToken) {
                    $devices[] = ['device' => $deviceToken->device,
                                'device_token' => $deviceToken->device_token
                    ];
                }
            }
            return $devices;
        }
        if ($params == 1) {
            $users = GobuyUserUsergroupMap::where('group_id', 15)
                                ->orWhere('group_id', 16)
                                ->orWhere('group_id', 19)
                                ->orWhere('group_id', 20)
                                ->get();
            foreach ($users as $key => $user) {
                $deviceTokens = UserDeviceToken::where('user_id', $user->user_id)
                                                ->whereNotNull('device_token')
                                                ->get();
                foreach ($deviceTokens as $key => $deviceToken) {
                    $devices[] = ['device' => $deviceToken->device,
                                  'device_token' => $deviceToken->device_token
                    ];
                }
            }
            return $devices;
        }
    }
    public function news($start, $take)
    {
        $take +=1;
        $news = DB::table('gobuy_content')
                    ->skip($start)
                    ->take($take)
                    ->where('state', 1)
                    ->where('catid', 2)
                    ->get();
        $new = DB::table('gobuy_content')->lists('id');
        $newNum = count($new);
        $array = [];
        if ($start >= $newNum) {
            return false;
        }
        else {
           foreach ($news as $new) {
                $array[] = ['id' => $new->id,
                            'title' => $new->title,
                            'date' => strtotime($new->created)
                           ];
           }
            return $array;
        }
    }
    public function detail($id)
    {
        $news = GobuyContent::where('id', $id)->first();
        if (is_null($news)) {
            return false;
        }
        $array = ['title' => $news->title,
                  'date' => strtotime($news->created),
                  'url' => "http://ginkerapp.com/news/" . $id . "-" . $news->title . ".htnl"
        ];
        return $array;
    }
    public function newsSpe($start, $take)
    {
        $take +=1;
        $news = DB::table('gobuy_content')
                    ->skip($start)
                    ->take($take)
                    ->where('state', 1)
                    ->where('catid', 13)
                    ->get();
        $new = DB::table('gobuy_content')->lists('id');
        $newNum = count($new);
        $array = [];
        if ($start >= $newNum) {
            return false;
        }
        else {
           foreach ($news as $new) {
                $array[] = ['id' => $new->id,
                            'title' => $new->title,
                            'date' => strtotime($new->created)
                           ];
           }
            return $array;
        }
    }
    public function detailSpe($id)
    {
        $news = GobuyContent::where('id', $id)->first();
        if (is_null($news)) {
            return false;
        }
        $array = ['title' => $news->title,
                  'date' => strtotime($news->created),
                  'url' => "http://ginkerapp.com/news/" . $id . "-" . $news->title . ".htnl"
        ];
        return $array;
    }
    public function newsPre($start, $take)
    {
        $take +=1;
        $news = DB::table('gobuy_content')
                    ->skip($start)
                    ->take($take)
                    ->where('state', 1)
                    ->where('catid', 12)
                    ->get();
        $new = DB::table('gobuy_content')->lists('id');
        $newNum = count($new);
        $array = [];
        if ($start >= $newNum) {
            return false;
        }
        else {
           foreach ($news as $new) {
                $array[] = ['id' => $new->id,
                            'title' => $new->title,
                            'date' => strtotime($new->created)
                           ];
           }
            return $array;
        }
    }
    public function detailPre($id)
    {
        $news = GobuyContent::where('id', $id)->first();
        if (is_null($news)) {
            return false;
        }
        $array = ['title' => $news->title,
                  'date' => strtotime($news->created),
                  'url' => "http://ginkerapp.com/news/" . $id . "-" . $news->title . ".htnl"
        ];
        return $array;
    }
}
