<?php
namespace App\Repositories;

use Auth;
use JWTAuth;
use DB;
use App\Entities\GobuyUser;
use App\Entities\CheckVersion;
use App\Entities\GobuyHikamarketVendor;
use App\Entities\GobuyJbusinessdirectoryCompany;
use App\Entities\GobuyAdData;
use App\Entities\GobuyContent;
use App\Entities\GobuyHikashopUser;
use App\Entities\GobuySocialPointsHistory;
use App\Entities\GobuySpePointsHistory;
use App\Entities\GobuyHikashopOrder;
use App\Entities\GobuyHikashopOrderProduct;
use App\Entities\GobuyUserProfile;

class SpecialUserRepository
{
    public function checkSpePassword($params)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $pwd = GobuyJbusinessdirectoryCompany::where('userId', $user['id'])
                    ->where('shop_class', 1)
                    ->where('csv_password', null)
                    ->first();
        if ($pwd) {
            if (!Auth::attempt(['email' => $user['email'], 'password' => $params]))
            {
                return 1;
            }
            return 0;
        }
        $csvPwd = GobuyJbusinessdirectoryCompany::where('userId', $user['id'])
                    ->where('shop_class', 1)
                    ->where('csv_password', $params)
                    ->first();
        if (is_null($csvPwd)) {
            return 1;
        }
    }
    public function checkPwd($params)
    {
    	$user = JWTAuth::parseToken()->authenticate();
        $pwd = GobuyJbusinessdirectoryCompany::where('userId', $user['id'])
                    ->where('shop_class', 1)
                    ->where('csv_password', null)
                    ->first();
        if ($pwd) {
            if (!Auth::attempt(['email' => $user['email'], 'password' => $params['password']]))
            {
                return 0;
            }
            $records = $this->specialRecord($params['timestamp_start'], $params['timestamp_end'], $user);
            return $records;
        }
        $csvPwd = GobuyJbusinessdirectoryCompany::where('userId', $user['id'])
                    ->where('shop_class', 1)
                    ->where('csv_password', $params['password'])
                    ->first();
        if (is_null($csvPwd)) {
            return 0;
        }
        $records = $this->specialRecord($params['timestamp_start'], $params['timestamp_end'], $user);
        return $records;
    }
    public function version($params)
    {
        $version = $params['version'];
        $versionSplit = str_split($version);
        $check = CheckVersion::where('id', '1')->first();
        $checkSplit = str_split($check->version);
        if ($checkSplit[1] > $versionSplit[1]) {
          return true;
        }
        elseif ($checkSplit[3] > $versionSplit[3]) {
          return true;
        }
        elseif ($checkSplit[4] != '.') {
            if ($checkSplit[4] > $versionSplit[4]) {
                return true;
            }
        }
        elseif ($checkSplit[5] > $versionSplit[5]) {
          return true;
        }
        else {
          return false;
        }
        // CheckVersion::where('id', '1')->update(['version' => $params['version']]);
    }
    public function resetPwd($params)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $checkUser = GobuyJbusinessdirectoryCompany::where('userId', $user['id'])
                        ->where('shop_class', 1)
                        ->first();
        if (!is_null($checkUser->csv_password)) {
            $data = GobuyJbusinessdirectoryCompany::where('userId', $user['id'])
                        ->where('shop_class', 1)
                        ->where('csv_password', $params['old_password'])
                        ->first();
            if (is_null($data)) {
                return 2;
            }
            if (empty($params['new_password'])) {
                return 1;
            }
            if ($params['old_password'] == $params['new_password']) {
                return 3;
            }
            $data->update([
                        'csv_password' => $params['new_password'],
                        'modified'        => date("Y-m-d H:i:s")
            ]);
            return 0;
        }
        $data = Auth::attempt(['email' => $user['email'], 'password' => $params['old_password']]);
        if (empty($params['new_password'])) {
            return 1;
        }
        if ($params['old_password'] == $params['new_password']) {
            return 3;
        }
        if (!$data) {
            return 2;
        }
        else {
            GobuyJbusinessdirectoryCompany::where('userId', $user['id'])
                    ->where('shop_class', 1)
                    ->update([
                        'csv_password' => $params['new_password'],
                        'modified'        => date("Y-m-d H:i:s")
                    ]);
            return 0;
        }
    }
    public function activityList($start, $take)
    {
        $take += 1;
        $activities = DB::table('gobuy_content')
                    ->skip($start)
                    ->take($take)
                    ->get();
        foreach ($activities as $key => $activity) {
            $array[] = [
                'title' => $activity->title,
                'date' => strtotime($activity->created),
                'url' => $activity->urls
            ];
        }
        return $array;
    }
    public function specialRecord($start, $end, $user)
    {
        $orderId = GobuyHikashopUser::where('user_cms_id', $user['id'])->first();
        $vendorId = GobuyHikamarketVendor::where('vendor_admin_id', $orderId->user_id)->first();
        $points = GobuySocialPointsHistory::where('user_id', $user['id'])->get();
        $records = GobuySpePointsHistory::where('user_id', $user['id'])
                                            ->get();
        $array = [];
        if (is_null($orderId) || is_null($vendorId)) {
            foreach ($records as $key => $record) {
                if (strtotime($record->created) >= $start && strtotime($record->created) <= $end) {
                    $array[] = [
                        'id' => $record->id,
                        'phone_number' => mb_substr($record->message, 11),
                        'money' => (int)$record->points
                    ];
                }
            }
        } else {
            $webRecords = GobuyHikashopOrder::where('order_vendor_id', $vendorId->vendor_id)->where('order_created', '>=', $start)
                        ->where('order_created', '<=', $end)
                        ->where('order_vendor_id', '<>', 0)
                        ->get();
            foreach ($records as $key => $record) {
                if (strtotime($record->created) >= $start && strtotime($record->created) <= $end) {
                    $array[] = [
                        'id' => $record->id,
                        'phone_number' => mb_substr($record->message, 11),
                        'money' => (int)$record->points
                    ];
                }
            }
            foreach ($webRecords as $key => $webRecord) {
                $check = GobuyHikashopOrderProduct::where('order_id', $webRecord->order_id)->where('order_product_quantity', 0)->first();
                $orderUser = GobuyHikashopUser::where('user_id', $webRecord->order_user_id)->first();
                $orderPhone = GobuyUserProfile::where('user_id', $orderUser->user_cms_id)->where('ordering', 2)->first();
                if (!$check) {
                    $computePoints = GobuyHikashopOrderProduct::where('order_id', $webRecord->order_id)->get();
                    $allPoints = 0;
                    foreach ($computePoints as $key => $computePoint) {
                        $allPoints += $computePoint->order_product_price;
                    }
                    $array[] = [
                        'id' => $webRecord->order_id -1,
                        'phone_number' => $orderPhone['profile_value'],
                        'money' => (int)-$allPoints
                    ];
                }
            }
        }
        array_multisort($array, SORT_DESC);
        return $array;
    }
    public function showQRcode()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $shop = GobuyJbusinessdirectoryCompany::where('userId', $user['id'])
                                    ->where('shop_class', 1)
                                    ->first();
        if (is_null($shop)) {
            return 1;
        } else {
            $url = "http://chart.apis.google.com/chart?cht=qr&chs=300x300&chl=" .$shop['id'] ."&chld=H|0";
            return $url;
        }
    }
}