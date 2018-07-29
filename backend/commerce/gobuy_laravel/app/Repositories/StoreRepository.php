<?php
namespace App\Repositories;

use Auth;
use JWTAuth;
use DB;
use App\Entities\GobuyJbusinessdirectoryCompany;
use App\Entities\GobuyJbusinessdirectoryCompanyContact;
use App\Entities\UserLikeShop;
use App\Entities\GobuyCity;

class StoreRepository
{
	public function store($start, $take, $keyword, $area, $lat, $lng, $distance, $type)
    {
        $area = [$area, $area];
        $user = JWTAuth::parseToken()->authenticate();
        if ($area[0] != '%') {
            $area = $this->cityConvert($area[0]);
        }
        $take +=1;
        if (!$lat == '' && !$lng == '' && !$distance == '') {
            $map = $this->show($distance, $lat, $lng, $start, $take, $keyword, $area, $type);
            if (!$map) {
                return false;
            }
            return $map;
        }
        if (!$lat == '' && !$lng == '') {
            $distance = $this->getDistance($distance, $lat, $lng, $start, $take, $keyword, $area, $type);
            if (!$distance) {
                return false;
            };
            return $distance;
        }
        if ($area[0] == '%') {
            $stores = DB::table('gobuy_jbusinessdirectory_companies')
                    ->where('shop_class', 'like', $type)
                    ->where('name', 'like', '%' . $keyword . '%')
                    ->where('address', 'like', '%' . $area[0] . '%')
                    ->where('approved', 2)
                    ->skip($start)
                    ->take($take)
                    ->get();
        } else {
            $stores = DB::table('gobuy_jbusinessdirectory_companies')
                        ->where('shop_class', 'like', $type)
                        ->where('name', 'like', '%' . $keyword . '%')
                        ->where('approved', 2)
                        ->where('address', 'like', '%' . $area[0] . '%')
                        ->orWhere('address', 'like', '%' . $area[1] . '%')
                        ->where('shop_class', 'like', $type)
                        ->where('name', 'like', '%' . $keyword . '%')
                        ->where('approved', 2)
                        ->skip($start)
                        ->take($take)
                        ->get();
        }
        if ($stores == []) {
            return false;
        }
        $store = DB::table('gobuy_jbusinessdirectory_companies')->lists('id');
        $storeNum = count($store);
        $array = [];
        if ($start >= $storeNum) {
            return false;
        }
        else {
           foreach ($stores as $store) {
                $like = UserLikeShop::where('company_id', $store->id)
                                    ->where('user_id', $user['id'])
                                    ->first();
                $webUrl = urlencode($store->alias);
                $array[] = ['shop_id' => $store->id,
                            'shop_name' => $store->name,
                            'shop_photo' => "http://ginkerapp.com/media/com_jbusinessdirectory/pictures$store->logoLocation",
                            'shop_phone' => $store->phone,
                            'shop_address' => $store->address,
                            'shop_url' => "http://ginkerapp.com/$webUrl",
                            'shop_like' => count($like),
                            'shop_km' => ""
                ];
           }
            return $array;
        }
    }
    //根據 定位 兩點經緯度 算出距離
    public function getDistance($distance, $lat, $lng, $start, $take, $keyword, $area, $type)
    {
        $stores = DB::table('gobuy_jbusinessdirectory_companies')
                    ->where('shop_class', 'like', $type)
                    ->where('name', 'like', '%' . $keyword . '%')
                    ->where('approved', 2)
                    ->where('address', 'like', '%' . $area[0] . '%')
                    ->orWhere('address', 'like', '%' . $area[1] . '%')
                    ->where('shop_class', 'like', $type)
                    ->where('name', 'like', '%' . $keyword . '%')
                    ->where('approved', 2)
                    // ->skip($start)
                    // ->take($take)
                    ->get();
        if ($stores == []) {
            return false;
        }
        $user = JWTAuth::parseToken()->authenticate();
        $store = DB::table('gobuy_jbusinessdirectory_companies')->lists('id');
        $shop = [];
        $storeNum = count($store);
        if ($start >= $storeNum) {
            return false;
        }
        else {
            foreach ($stores as $key => $store) {
                $like = UserLikeShop::where('company_id', $store->id)
                                    ->where('user_id', $user['id'])
                                    ->first();
                $lat2 = $store->latitude;
                $lng2 = $store->longitude;
                $calculatedDistance = $this->distanceFormula($lat, $lng, $lat2, $lng2);
                $webUrl = urlencode($store->alias);
                $shop[] = ['shop_id' => $store->id,
                           'shop_name' => $store->name,
                           'shop_photo' => "http://ginkerapp.com/media/com_jbusinessdirectory/pictures$store->logoLocation",
                           'shop_phone' => $store->phone,
                           'shop_address' => $store->address,
                           'shop_url' => "http://ginkerapp.com/$webUrl",
                           'shop_like' => count($like),
                           'shop_km' => $calculatedDistance
                ];
                $shop_km[$key] = $calculatedDistance;
            }
            array_multisort($shop_km, SORT_ASC, $shop);
            $shop = array_slice($shop, $start, $take);
        }
        return $shop;
    }
    //根據 半徑 distance 畫圓 找範圍店家
    public function show($distance, $lat, $lng, $start, $take, $keyword, $area, $type)
    {
        $dlng = 2 * asin(sin($distance / (2 * 6371)) / cos(deg2rad($lat)));
        $dlng = rad2deg($dlng);//轉換弧度然後是緯度範圍的查詢

        $dlat = $distance / 6371;//EARTH_RADIUS地球半徑 $dlat = rad2deg($dlat);//轉換弧度
        $dlat = rad2deg($dlat);
        $array = [
            'left-top' => ['lat' => $lat + $dlat,'lng' => $lng - $dlng],
            'right-top' => ['lat' => $lat + $dlat, 'lng' => $lng + $dlng],
            'left-bottom' => ['lat' => $lat - $dlat, 'lng' => $lng - $dlng],
            'right-bottom' => ['lat' => $lat - $dlat, 'lng' => $lng + $dlng]
        ];
        if ($area[0] == '%') {
            $stores = GobuyJbusinessdirectoryCompany::where('latitude', '>', $array['right-bottom']['lat'])
                ->where('latitude', '<', $array['left-top']['lat'])
                ->where('longitude', '>', $array['left-top']['lng'])
                ->where('longitude', '<', $array['right-bottom']['lng'])
                ->where('shop_class', 'like', $type)
                ->where('name', 'like', '%' . $keyword . '%')
                ->where('address', 'like', '%' . $area[0] . '%')
                ->where('approved', 2)
                ->skip($start)
                ->take($take)
                ->get();
        } else {
            $stores = GobuyJbusinessdirectoryCompany::where('latitude', '>', $array['right-bottom']['lat'])
                ->where('latitude', '<', $array['left-top']['lat'])
                ->where('longitude', '>', $array['left-top']['lng'])
                ->where('longitude', '<', $array['right-bottom']['lng'])
                ->where('shop_class', 'like', $type)
                ->where('name', 'like', '%' . $keyword . '%')
                ->where('approved', 2)
                ->where('address', 'like', '%' . $area[0] . '%')
                ->orWhere('address', 'like', '%' . $area[1] . '%')
                ->where('shop_class', 'like', $type)
                ->where('name', 'like', '%' . $keyword . '%')
                ->where('approved', 2)
                ->skip($start)
                ->take($take)
                ->get();
        }
        if ($stores == []) {
            return false;
        }
        $user = JWTAuth::parseToken()->authenticate();
        $store = DB::table('gobuy_jbusinessdirectory_companies')->lists('id');
        $shop = [];
        $storeNum = count($store);
        if ($start >= $storeNum) {
            return false;
        }
        else {
           foreach ($stores as $store) {
                $like = UserLikeShop::where('company_id', $store->id)
                                    ->where('user_id', $user['id'])
                                    ->first();
                $lat2 = $store->latitude;
                $lng2 = $store->longitude;
                $calculatedDistance = $this->distanceFormula($lat, $lng, $lat2, $lng2);
                $webUrl = urlencode($store->alias);
                $shop[] = ['shop_id' => $store->id,
                            'shop_name' => $store->name,
                            'shop_photo' => "http://ginkerapp.com/media/com_jbusinessdirectory/pictures$store->logoLocation",
                            'shop_phone' => $store->phone,
                            'shop_address' => $store->address,
                            'shop_url' => "http://ginkerapp.com/$webUrl",
                            'shop_like' => count($like),
                            'shop_km' => $calculatedDistance
                ];
            }
        }
        return $shop;
    }
    //距離公式
    public function distanceFormula($lat, $lng, $lat2, $lng2)
    {
        $earthRadius = 6371;
        $lat1 = ($lat * pi() ) / 180;
        $lng1 = ($lng * pi() ) / 180;
        $lat2 = ($lat2 * pi() ) / 180;
        $lng2 = ($lng2 * pi() ) / 180;
        $calcLongitude = $lng2 - $lng1;
        $calcLatitude = $lat2 - $lat1;
        $stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);  $stepTwo = 2 * asin(min(1, sqrt($stepOne)));
        $calculatedDistance = $earthRadius * $stepTwo;
        return (int)$calculatedDistance;
    }
    //地址轉換經緯度
    public function addressConvert()
    {
        $addr_str_array = ['台中市北區三民路三段129號'];
        $num_addr = count($addr_str_array);

        $addr_latlng_array = []; //用來存抓到的經緯度

        for($i=0; $i<$num_addr ; $i++){
            set_time_limit(10);

            $addr_str = $addr_str_array[$i];
            $addr_str_encode = urlencode($addr_str);
            $url = "http://maps.googleapis.com/maps/api/geocode/json"
                ."?sensor=true&language=zh-TW&region=tw&address=".$addr_str_encode;
            $geo = file_get_contents($url);
            $geo = json_decode($geo,true);
            $geo_status = $geo['status'];
            if($geo_status=="OVER_QUERY_LIMIT"){ die("OVER_QUERY_LIMIT"); }
            if($geo_status!="OK") continue;

            $geo_address = $geo['results'][0]['formatted_address'];
            $num_components = count($geo['results'][0]['address_components']);
            //郵遞區號
            $geo_zip = $geo['results'][0]['address_components'][$num_components-1]['long_name'];
            //緯度
            $geo_lat = $geo['results'][0]['geometry']['location']['lat'];
            //經度
            $geo_lng = $geo['results'][0]['geometry']['location']['lng'];
            $array = [
                'zip' => $geo_zip,
                'lat' => $geo_lat,
                'lng' => $geo_lng
            ];
            return $array;
        }
    }

    public function likseShop($params)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $shop = GobuyJbusinessdirectoryCompany::where('id', $params['shop_id'])
                                              ->first();
        $like = UserLikeShop::where('user_id', $user['id'])
                            ->where('company_id', $params['shop_id'])
                            ->first();
        if (is_null($shop)) {
          return 1;
        }
        if (is_null($like)) {
          UserLikeShop::create(['company_id' => $params['shop_id'],
                                'user_id' => $user['id']
          ]);
          return 0;
        }
        return 2;
    }
    public function cancelShop($params)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $shop = GobuyJbusinessdirectoryCompany::where('id', $params['shop_id'])
                                              ->first();
        $like = UserLikeShop::where('user_id', $user['id'])
                            ->where('company_id', $params['shop_id'])
                            ->first();
        if (is_null($shop)) {
          return 1;
        }
        if (is_null($like)) {
          return 2;
        }
        $like->delete();
        return 0;
    }
    public function browseLikeShop($lat, $lng)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $shops = UserLikeShop::where('user_id', $user['id'])->get();
        $checkShop = UserLikeShop::where('user_id', $user['id'])->first();
        if (is_null($checkShop)) {
          return 1;
        }
        foreach ($shops as $key => $shop) {
            $company = GobuyJbusinessdirectoryCompany::where('id', $shop->company_id)
                                    ->first();
            $calculatedDistance = "";
            if ($lat && $lng) {
                $lat2 = $company->latitude;
                $lng2 = $company->longitude;
                $calculatedDistance = $this->distanceFormula($lat, $lng, $lat2, $lng2);
            }
            $webUrl = urlencode($company->alias);
            $array[] = [
              'shop_id' => $company->id,
              'shop_name' => $company->name,
              'shop_phone' => $company->phone,
              'shop_photo' => "http://ginkerapp.com/media/com_jbusinessdirectory/pictures$company->logoLocation",
              'shop_address' => $company->address,
              'shop_url' => "http://ginkerapp.com/$webUrl",
              'shop_km' => $calculatedDistance
            ];
        }
        return $array;
    }
    //編號轉城市
    public function cityConvert($id)
    {
        $city = GobuyCity::where('id', $id)->first();
        switch ($city['id']) {
            case 1:
                $array = ['台北市', '臺北市'];
                return $array;
                break;
            case 2:
                $array = ['台中市', '臺中市'];
                return $array;
                break;
            case 4:
                $array = ['台南市', '臺南市'];
                return $array;
                break;
            case 20:
                $array = ['台東市', '臺東市'];
                return $array;
                break;
            default:
                $array = [$city['city_name'], $city['city_name']];
                return $array;
                break;
        }
    }
    public function storeRegion()
    {
        $regions = DB::table('gobuy_cities')->get();
        $array[] = ['region_id' => 0,
                    'region_name' => '所有地區'
        ];
        foreach ($regions as $key => $region) {
            $array[] = [
                'region_id' => $region->id,
                'region_name' => $region->city_name
            ];
        }
        return $array;
    }
    public function allSpeStore()
    {
        $stores = DB::table('gobuy_jbusinessdirectory_companies')
                        ->where('shop_class', 1)
                        ->where('approved', 2)
                        ->get();
        foreach ($stores as $store) {
            $array[] = [
                'id' => $store->id,
                'name' => $store->name
            ];
        }
        return $array;
    }
    public function allPreStore()
    {
        $stores = DB::table('gobuy_jbusinessdirectory_companies')
                        ->where('shop_class', 2)
                        ->get();
        foreach ($stores as $store) {
            $array[] = [
                'id' => $store->id,
                'name' => $store->name
            ];
        }
        return $array;
    }
}