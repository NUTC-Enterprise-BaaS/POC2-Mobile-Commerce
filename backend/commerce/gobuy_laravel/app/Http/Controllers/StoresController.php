<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\StoreRepository;
use App\Entities\GobuyJbusinessdirectoryCompany;
use App\Http\Requests;
use App\Http\Requests\TestRequest;
// require_once '\AllPay.Payment.Integration.php';
// use Services_Twilio;

// require_once '\Services_Twilio\Services\Twilio.php'; 加這行 會導致server端 白屏

class StoresController extends Controller
{
	protected $stores = null;

    public function __construct(StoreRepository $stores)
    {
        $this->stores = $stores;
    }

    //一般會員-瀏覽全部的店家列表
    public function browseStore()
    {
        $start = $_GET['start'];
        $end =  $_GET['end'];
        $type = $_GET['type'];
        $keyword = (isset($_GET['keyword'])) ? $_GET['keyword'] : '%';
        $area = (isset($_GET['area'])) ? $_GET['area'] : '%';
        $lat = (isset($_GET['latitude'])) ? $_GET['latitude'] : '';
        $lng = (isset($_GET['longitude'])) ? $_GET['longitude'] : '';
        $distance = (isset($_GET['km'])) ? $_GET['km'] : '';
        if ($type == 0) {
            $type = '%';
        }
        if ($area == 0) {
            $area = '%';
        }
        $take = ($end-$start);
        if ($start > $end || $start < 0) {
            return response()->json([
                'result' => 0,
                'message' => ['No such data input error'],
                'shop_sum' => 0,
                'shops' => []
            ]);
        }
        else {
            if ($this->stores->store($start, $take, $keyword, $area, $lat, $lng, $distance, $type)) {
                return response()->json([
                    'result' => 0,
                    'message' => ['Inquire successful'],
                    'shop_sum' => count( $this->stores->store($start, $take, $keyword, $area, $lat, $lng, $distance, $type)),
                    'shops' => $this->stores->store($start, $take, $keyword, $area, $lat, $lng, $distance, $type)
                ]);
            }
            else {
                return response()->json([
                    'result' => 0,
                    'message' => ['No such data input error'],
                    'shop_sum' => 0,
                    'shops' => []
                ]);
            }
        }
    }
    //一般會員-收藏喜愛店家
    public function userLikeShop(Request $request)
    {
        if (!isset($request->shop_id)) {
            return response()->json([
                'result' => 1,
                'message' => ['This store does not exist']
            ]);
        }
        $likeShop = $this->stores->likseShop($request->all());
        switch ($likeShop) {
            case 0:
                return response()->json([
                    'result' => 0,
                    'message' => ['Success']
                ]);
                break;
            case 1:
                return response()->json([
                    'result' => 1,
                    'message' => ['This store does not exist']
                ]);
                break;
            case 2:
                return response()->json([
                    'result' => 2,
                    'message' => ['This store has been collected']
                ]);
                break;
        }
    }
    //一般會員-取消收藏喜愛店家
    public function userCancelShop(Request $request)
    {
        if (!isset($request->shop_id)) {
            return response()->json([
                'result' => 1,
                'message' => ['This store does not exist']
            ]);
        }
        $cancelShop = $this->stores->cancelShop($request->all());
        switch ($cancelShop) {
            case 0:
                return response()->json([
                    'result' => 0,
                    'message' => ['Success']
                ]);
                break;
            case 1:
                return response()->json([
                    'result' => 1,
                    'message' => ['This store does not exist']
                ]);
                break;
            case 2:
                return response()->json([
                    'result' => 1,
                    'message' => ['This store has not been collected']
                ]);
                break;
        }
    }
     //一般會員 瀏覽已收藏的店家
    public function browseUserLikeShop()
    {
        $lat = (isset($_GET['latitude'])) ? $_GET['latitude'] : '';
        $lng = (isset($_GET['longitude'])) ? $_GET['longitude'] : '';
        $shop = $this->stores->browseLikeShop($lat, $lng);
        if ($shop == 1) {
            return response()->json([
                'result' => 0,
                'message' => ['There is no collection of stores']
            ]);
        }
        return response()->json([
            'result' => 0,
            'message' => ['shop list'],
            'shops' => $shop
        ]);
    }
    //一般會員_瀏覽全部店家，地區下拉式選單資料
    public function browseStoreRegion()
    {
        $regions = $this->stores->storeRegion();
        return response()->json([
            'result' => 0,
            'message' => ['all Region'],
            'regions' => $regions
        ]);
    }
    // 提供推播後台 下拉式選單 特約商店
    public function shopAllSpeStore()
    {
        $stores = $this->stores->allSpeStore();
        return response()->json([
            'stores' => $stores
        ]);
    }
    // 提供推播後台 下拉式選單 優惠商店
    public function shopAllPreStore()
    {
        $stores = $this->stores->allPreStore();
        return response()->json([
            'stores' => $stores
        ]);
    }
    public function showMap()
    {
        $map = $this->stores->show();
    }
}
