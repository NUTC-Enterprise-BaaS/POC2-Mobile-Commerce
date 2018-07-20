<?php
namespace App\Repositories;

use Auth;
use JWTAuth;
use App\Entities\GobuyUser;
use App\Entities\UserDeviceToken;
use App\Entities\GobuyUserProfile;
use App\Entities\GobuyContent;
use App\Entities\GobuyJbusinessdirectoryCompany;
use App\Entities\GobuySocialPointsHistory;
use App\Entities\GobuyHikashopOrder;
use App\Entities\GobuyHikashopOrderProduct;
use App\Entities\GobuyHikashopUser;
use App\Entities\GobuyHikamarketVendor;
use App\Entities\UserTransferPoint;
use App\Entities\WaitSendPoint;
use App\Entities\GobuySpePointsHistory;
use App\Entities\GobuyPrePointsHistory;
use DB;

class PointRepository
{
	public function speReadPoint($params)
	{
		$user = JWTAuth::parseToken()->authenticate();
		$shop = GobuyJbusinessdirectoryCompany::where('userId', $user['id'])
											->where('shop_class', 1)
											->first();
		$waitPoints = WaitSendPoint::where('store_id', $shop['id'])
									->where('state', 0)
									->where('created', '>=', $params['timestamp_start'])
									->where('created', '<=', $params['timestamp_end'])
									->get();
		$array = [];
		foreach ($waitPoints as $key => $waitPoint) {
				$array[] = [
					'id' => $waitPoint['id'],
					'phone_number' => $waitPoint['user_phone'],
					'timestamp' => strtotime($waitPoint['created_at']),
					'money' => ''
				];
			}
		return $array;
	}
	public function SendUserPoints($params)
	{
		$user = JWTAuth::parseToken()->authenticate();
		$check = WaitSendPoint::where('id', $params['id'])
							->where('user_phone' , $params['phone_number'])
							->where('state', 0)
							->first();
		if (is_null($check)) {
			return 1;
		}
		// $points = GobuySocialPointsHistory::where('user_id', $user['id'])->get();
		// $pointNum = 0;
		// foreach ($points as $key => $point) {
		// 	$pointNum += $point['points'];
		// }
		// if ($pointNum < $params['bonus']) {
		// 	return 2;
		// }
		$storeCheck = GobuyJbusinessdirectoryCompany::where('id', $check['store_id'])
											->where('shop_class', 1)
											->where('approved', 2)
											->first();
		if (is_null($storeCheck)) {
			return 1;
		}
		$store = GobuyJbusinessdirectoryCompany::where('id', $check['store_id'])
											->where('shop_class', 1)
											->first();
		GobuySpePointsHistory::create([
			'points_id' => 1,
			'user_id' => $user['id'],
			'points' =>  (int)-$params['bonus'],
			'created' => date("Y-m-d H:i:s"),
			'state' => 1,
			'message' => '商店發送點數給使用者:' . $check['user_phone']
		]);
		GobuySocialPointsHistory::create([
			'points_id' => 2,
			'user_id' => $check['user_id'],
			'points' => (int)$params['bonus'],
			'created' => date("Y-m-d H:i:s"),
			'state' => 1,
			'message' => '商店發送點數:' . $store['name']
		]);
		$check->update([
			'state' => 1,
			'points' => (int)$params['bonus']
		]);
		$array = [
			'type' => 'storesend',
			'result' => 0,
			'message' => ['success'],
			'shop_id' => $store['id'],
			'shop_name' => $store['name'],
			'shop_photo' => '',
			'shop_url' => $store['website'],
			'shop_point' => (int)$params['bonus']
		];
		return $array;
	}
	public function record($params)
	{
		$user = JWTAuth::parseToken()->authenticate();
		$orderId = GobuyHikashopUser::where('user_cms_id', $user['id'])->first();
		$points = GobuySocialPointsHistory::where('user_id', $user['id'])->get();
		$costs = GobuySocialPointsHistory::where('user_id', $user['id'])
										->where('points_id' , '<>', 5)
										->where('points_id' , '<>', 6)
										->where('points_id' , '<>', 7)
										->get();
		$records = GobuySocialPointsHistory::where('user_id', $user['id'])
											->where('points_id', '<>' , 0)
											->get();
		$array = [];
		if (is_null($orderId)) {
			foreach ($records as $key => $record) {
				if (strtotime($record->created) >= $params['timestamp_start'] && strtotime($record->created) <= $params['timestamp_end']) {
					if ($record->points_id == 7 || $record->points_id == 9) {
						$array[] = [
							'id' => $record->id,
							'location' => $record->message,
							'timestamp' => strtotime($record->created),
							'bonus_point' => (int)$record->points
						];
					} else {
						$array[] = [
							'id' => $record->id,
							'location' => mb_substr($record->message, 6),
							'timestamp' => strtotime($record->created),
							'bonus_point' => (int)$record->points
						];
					}
				}
			}
		} else {
			$webRecords = GobuyHikashopOrder::where('order_user_id', $orderId->user_id)->where('order_created', '>=', $params['timestamp_start'])
						->where('order_created', '<=', $params['timestamp_end'])
						// ->where('order_vendor_id', '<>', 0)
						->orderBy('order_id', 'desc')
						->get();
			foreach ($records as $key => $record) {
				if (strtotime($record->created) >= $params['timestamp_start'] && strtotime($record->created) <= $params['timestamp_end']) {
					if ($record->points_id == 7) {
						$array[] = [
							'id' => $record->id,
							'location' => $record->message,
							'timestamp' => strtotime($record->created),
							'bonus_point' => (int)$record->points
						];
					} else {
						$array[] = [
							'id' => $record->id,
							'location' => mb_substr($record->message, 6),
							'timestamp' => strtotime($record->created),
							'bonus_point' => (int)$record->points
						];
					}
				}
			}
			foreach ($webRecords as $key => $webRecord) {
				if ($webRecord->order_vendor_id == 0) {
					// $webRecord->order_vendor_id += 1;
					continue;
				}
				$vendor = GobuyHikamarketVendor::where('vendor_id', $webRecord->order_vendor_id)->first();
				$check = GobuyHikashopOrderProduct::where('order_id', $webRecord->order_id)->where('order_product_quantity', 0)->first();
				if ($check) {
					$point = GobuyHikashopOrderProduct::where('order_id', $webRecord->order_id)->first();
					$array[] = [
						'id' => $webRecord->order_id -1,
						'location' => $vendor->vendor_name,
						'timestamp' => $webRecord->order_created,
						'bonus_point' => (int)$point->order_product_price
					];
				}
				else {
					$computePoints = GobuyHikashopOrderProduct::where('order_id', $webRecord->order_id)->get();
					$allPoints = 0;
					foreach ($computePoints as $key => $computePoint) {
						$allPoints += $computePoint->order_product_price;
					}
					$array[] = [
						'id' => $webRecord->order_id -1,
						'location' => $vendor->vendor_name,
						'timestamp' => $webRecord->order_created,
						'bonus_point' => (int)$allPoints
					];
				}
			}
		}
        $num = 0;
    	$numCosts = 0;
    	foreach ($costs as $key => $cost) {
    		if ($cost->points_id == 0 && mb_substr($cost->message, 0, 4) == '訂單編號') {
    				if ($cost->points < 0) {
    					$numCosts += $cost['points'];
    				}
    			}
    		if ($cost->points_id != 0) {
    			if ($cost->points < 0) {
    				$numCosts += $cost['points'];
    			}
    		}
    	}
        foreach ($points as $key => $point) {
            $num += $point['points'];
        }
        $array = [
        	'array' => $array,
        	'num' => $num,
        	'costs' => $numCosts
        ];
        foreach ($array['array'] as $key => $row) {
        	$timestamp[$key] = $row['timestamp'];
        }
        if ($array['array'] != []) {
        	array_multisort($timestamp, SORT_DESC, $array['array']);
        }
		return $array;
	}
	public function transferPoints($params)
	{
		$user = JWTAuth::parseToken()->authenticate();
		$points = GobuySocialPointsHistory::where('user_id', $user['id'])->get();
		$pointSum = 0;
        foreach ($points as $key => $point) {
            $pointSum += $point['points'];
        }
        $checkUser = GobuyUser::where('email', $params['email'])
        				->where('block', 0)
        				->first();
		$recipients = GobuyUser::where('email', $params['email'])
							->first();
		if (is_null($recipients)) {
			return 1;
		}
		else {
			if (is_null($checkUser)) {
        		return 6;
        	}
			if (GobuyUserProfile::where('user_id', $recipients->id)
								->where('profile_value', $params['phone_number'])
								->first()
				) {
					if (Auth::attempt(['email' => $user['email'], 'password' => $params['password']])) {
						if ($params['point'] > $pointSum) {
							return 4;
						}
						$deviceToken = UserDeviceToken::where('email', $params['email'])->get();
						$checkToken = UserDeviceToken::where('email', $params['email'])->first();
						if (empty($checkToken['device_token'])) {
							return 5;
						}
						while(($checkId=rand()%1000)<100);
						$device = ['deviceToken' => $deviceToken,
								   'type' => 'transferPoint'
						];
						$array = [
							'point' => $params['point'],
							'check_id' => $checkId,
							'receive_phone' => $params['phone_number'],
							'receive_email' => $params['email'],
							'send_id' => $user['id'],
							'send_email' => $user['email'],
							'state' => 0
						];
						UserTransferPoint::create($array);
						$array = array_merge($array, $device);
						return $array;
					}
					else {
						return 3;
					}
			}
			else {
				return 2;
			}
		}
	}
	public function receiveUserPoints($params)
	{
		$points = UserTransferPoint::where('check_id', $params['check_id'])
										->where('state', 0)
										->first();
		$deviceToken = UserDeviceToken::where('email', $params['send_email'])->get();
		$checkToken = UserDeviceToken::where('email', $params['send_email'])->first();
		if (empty($checkToken['device_token'])) {
			return 0;
		}
		if ($params['state'] == 1) {
			$send = GobuyUser::where('email', $params['send_email'])->first();
			$receive = GobuyUser::where('email', $params['receive_email'])->first();
			GobuySocialPointsHistory::create([
				'points_id' => 6,
				'user_id' => $send['id'],
				'points' => (int)-$points['point'],
				'created' => date("Y-m-d H:i:s"),
				'state' => 1,
				'message' => '點數轉讓給 ' . $params['receive_email'] . ' 使用者'
			]);
			GobuySocialPointsHistory::create([
				'points_id' => 6,
				'user_id' => $receive['id'],
				'points' => (int)$points['point'],
				'created' => date("Y-m-d H:i:s"),
				'state' => 1,
				'message' => '點數獲得於 ' . $params['send_email'] . ' 使用者'
			]);
			UserTransferPoint::where('check_id', $params['check_id'])
										->where('state', 0)
										->update(['state' => 1]);
			$array = ['points' => $points['point'],
				  	  'deviceToken' => $deviceToken,
				  	  'state' => 1,
				  	  'type' => 'confirmPoint',
				  	  'receive_email' => $params['receive_email'],
				  	  'send_email' => $params['send_email']
			];
			return $array;
		}
		else {
			// UserTransferPoint::where('check_id', $params['check_id'])
			// 							->where('state', 0)
			// 							->delete();
			$array = ['points' => $points['point'],
				  	  'deviceToken' => $deviceToken,
				  	  'state' => 0,
				  	  'type' => 'confirmPoint',
				  	  'receive_email' => $params['receive_email'],
				  	  'send_email' => $params['send_email']
			];
			return $array;
		}
	}
	public function waitShopPoints($params)
	{
		$user = JWTAuth::parseToken()->authenticate();
		$store = GobuyJbusinessdirectoryCompany::where('id', $params['store_id'])
											->first();
		$userPhone = GobuyUserProfile::where('user_id', $user['id'])
									->where('ordering', 2)
									->first();
		if (is_null($store)) {
			return 1;
		}
		WaitSendPoint::create([
			'user_id' => $user['id'],
			'user_phone' => $userPhone['profile_value'],
			'store_id' => $store['id'],
			'points' => 100,
			'state' => 0,
			'message' => $store['name'] . ' 商店待送點數',
			'created' => strtotime(date("Y-m-d H:i:s"))
		]);
	}
	public function checkPhone($params)
	{
		$phone = GobuyUserProfile::where('profile_value', $params)
								->where('ordering', 2)
								->first();
		return $phone;
	}
	public function specialRecord($params)
	{
		$user = JWTAuth::parseToken()->authenticate();
		$orderId = GobuyHikashopUser::where('user_cms_id', $user['id'])->first();
		if (!is_null($orderId)) {
			$vendorId = GobuyHikamarketVendor::where('vendor_admin_id', $orderId->user_id)->first();
		}
		$points = GobuySocialPointsHistory::where('user_id', $user['id'])->get();
		$records = GobuySpePointsHistory::where('user_id', $user['id'])
											->get();
		$array = [];
		if (is_null($orderId) || is_null($vendorId)) {
			foreach ($records as $key => $record) {
				if (strtotime($record->created) >= $params['timestamp_start'] && strtotime($record->created) <= $params['timestamp_end']) {
					$array[] = [
						'id' => $record->id,
						'phone_number' => mb_substr($record->message, 11),
						'money' => (int)$record->points
					];
				}
			}
		} else {
			$webRecords = GobuyHikashopOrder::where('order_vendor_id', $vendorId->vendor_id)->where('order_created', '>=', $params['timestamp_start'])
						->where('order_created', '<=', $params['timestamp_end'])
						->where('order_vendor_id', '<>', 0)
						->get();
			foreach ($records as $key => $record) {
				if (strtotime($record->created) >= $params['timestamp_start'] && strtotime($record->created) <= $params['timestamp_end']) {
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
	public function spePhoneSend($params)
	{
		$user = JWTAuth::parseToken()->authenticate();
		$phone = GobuyUserProfile::where('profile_value', $params['phone_number'])
								->where('ordering', 2)
								->first();
		if (is_null($phone)) {
			return 1;
		}
		$receiveUser = GobuyUser::where('id', $phone['user_id'])->first();
		// $points = GobuySocialPointsHistory::where('user_id', $user['id'])->get();
		// $pointNum = 0;
		// foreach ($points as $key => $point) {
		// 	$pointNum += $point['points'];
		// }
		// if ($pointNum < $params['bonus']) {
		// 	return 2;
		// }
		$storeCheck = GobuyJbusinessdirectoryCompany::where('userId', $user['id'])
											->where('shop_class', 1)
											->where('approved', 2)
											->first();
		if (is_null($storeCheck)) {
			return 1;
		}
		$store = GobuyJbusinessdirectoryCompany::where('userId', $user['id'])
											->where('shop_class', 1)
											->first();
		GobuySpePointsHistory::create([
			'points_id' => 1,
			'user_id' => $user['id'],
			'points' =>  (int)-$params['bonus'],
			'created' => date("Y-m-d H:i:s"),
			'state' => 1,
			'message' => '商店發送點數給使用者:' . $params['phone_number']
		]);
		GobuySocialPointsHistory::create([
			'points_id' => 2,
			'user_id' => $receiveUser['id'],
			'points' => (int)$params['bonus'],
			'created' => date("Y-m-d H:i:s"),
			'state' => 1,
			'message' => '商店發送點數:' . $store['name']
		]);
		$array = [
			'type' => 'storesend',
			'result' => 0,
			'message' => ['success'],
			'shop_id' => $store['id'],
			'shop_name' => $store['name'],
			'shop_photo' => '',
			'shop_url' => $store['website'],
			'shop_point' => (int)$params['bonus']
		];
		return $array;
	}
	public function preReadPoint($params)
	{
		$user = JWTAuth::parseToken()->authenticate();
		$shop = GobuyJbusinessdirectoryCompany::where('userId', $user['id'])
											->where('shop_class', 2)
											->first();
		$waitPoints = WaitSendPoint::where('store_id', $shop['id'])
									->where('state', 0)
									->where('created', '>=', $params['timestamp_start'])
									->where('created', '<=', $params['timestamp_end'])
									->get();
		$array = [];
		foreach ($waitPoints as $key => $waitPoint) {
				$array[] = [
					'id' => $waitPoint['id'],
					'phone_number' => $waitPoint['user_phone'],
					'timestamp' => strtotime($waitPoint['created_at']),
					'money' => ''
				];
			}
		return $array;
	}
	public function sendPreUserPoints($params)
	{
		$user = JWTAuth::parseToken()->authenticate();
		$points = GobuySocialPointsHistory::where('user_id', $user['id'])->get();
		$check = WaitSendPoint::where('id', $params['id'])
							->where('user_phone' , $params['phone_number'])
							->where('state', 0)
							->first();
		if (is_null($check)) {
			return 1;
		}
		// $points = GobuySocialPointsHistory::where('user_id', $check['user_id'])->get();
		// $pointNum = 0;
		// foreach ($points as $key => $point) {
		// 	$pointNum += $point['points'];
		// }
		// if ($pointNum < $params['bonus']) {
		// 	return 2;
		// }
		$storeCheck = GobuyJbusinessdirectoryCompany::where('id', $check['store_id'])
											->where('shop_class', 2)
											->where('approved', 2)
											->first();
		if (is_null($storeCheck)) {
			return 1;
		}
		$store = GobuyJbusinessdirectoryCompany::where('id', $check['store_id'])
											->where('shop_class', 2)
											->first();
		GobuyPrePointsHistory::create([
			'points_id' => 3,
			'user_id' => $user['id'],
			'points' =>  (int)$params['bonus'],
			'created' => date("Y-m-d H:i:s"),
			'state' => 1,
			'message' => '商店獲得點數於使用者:' . $check['user_phone']
		]);
		GobuySocialPointsHistory::create([
			'points_id' => 2,
			'user_id' => $check['user_id'],
			'points' => (int)-$params['bonus'],
			'created' => date("Y-m-d H:i:s"),
			'state' => 1,
			'message' => '扣除點數藉由:' . $store['name']
		]);
		$check->update([
			'state' => 1,
			'points' => (int)$params['bonus']
		]);
		$array = [
			'type' => 'storesend',
			'result' => 0,
			'message' => ['success'],
			'shop_id' => $store['id'],
			'shop_name' => $store['name'],
			'shop_photo' => '',
			'shop_url' => $store['website'],
			'shop_point' => (int)-$params['bonus']
		];
		return $array;
	}
	public function prePhoneSend($params)
	{
		$user = JWTAuth::parseToken()->authenticate();
		$phone = GobuyUserProfile::where('profile_value', $params['phone_number'])
								->where('ordering', 2)
								->first();
		if (is_null($phone)) {
			return 1;
		}
		$receiveUser = GobuyUser::where('id', $phone['user_id'])->first();
		// $points = GobuySocialPointsHistory::where('user_id', $receiveUser['id'])->get();
		// $pointNum = 0;
		// foreach ($points as $key => $point) {
		// 	$pointNum += $point['points'];
		// }
		// if ($pointNum < $params['bonus']) {
		// 	return 2;
		// }
		$storeCheck = GobuyJbusinessdirectoryCompany::where('userId', $user['id'])
											->where('shop_class', 2)
											->where('approved', 2)
											->first();
		if (is_null($storeCheck)) {
			return 1;
		}
		$store = GobuyJbusinessdirectoryCompany::where('userId', $user['id'])
											->where('shop_class', 2)
											->first();
		GobuyPrePointsHistory::create([
			'points_id' => 3,
			'user_id' => $user['id'],
			'points' =>  (int)$params['bonus'],
			'created' => date("Y-m-d H:i:s"),
			'state' => 1,
			'message' => '商店獲得點數於使用者:' . $params['phone_number']
		]);
		GobuySocialPointsHistory::create([
			'points_id' => 2,
			'user_id' => $receiveUser['id'],
			'points' => (int)-$params['bonus'],
			'created' => date("Y-m-d H:i:s"),
			'state' => 1,
			'message' => '扣除點數藉由:' . $store['name']
		]);
		$array = [
			'type' => 'storesend',
			'result' => 0,
			'message' => ['success'],
			'shop_id' => $store['id'],
			'shop_name' => $store['name'],
			'shop_photo' => '',
			'shop_url' => $store['website'],
			'shop_point' => (int)-$params['bonus']
		];
		return $array;
	}
	public function preRecord($params)
	{
		$user = JWTAuth::parseToken()->authenticate();
		$orderId = GobuyHikashopUser::where('user_cms_id', $user['id'])->first();
		if (!is_null($orderId)) {
			$vendorId = GobuyHikamarketVendor::where('vendor_admin_id', $orderId->user_id)->first();
		}
		$points = GobuySocialPointsHistory::where('user_id', $user['id'])->get();
		$records = GobuyPrePointsHistory::where('user_id', $user['id'])
											->where('points_id', 3)
											->get();
		$array = [];
		if (is_null($orderId) || is_null($vendorId)) {
			foreach ($records as $key => $record) {
				if (strtotime($record->created) >= $params['timestamp_start'] && strtotime($record->created) <= $params['timestamp_end']) {
					$array[] = [
						'id' => $record->id,
						'phone_number' => mb_substr($record->message, 11),
						'money' => (int)$record->points
					];
				}
			}
		} else {
			$webRecords = GobuyHikashopOrder::where('order_vendor_id', $vendorId->vendor_id)->where('order_created', '>=', $params['timestamp_start'])
						->where('order_created', '<=', $params['timestamp_end'])
						->where('order_vendor_id', '<>', 0)
						->get();
			foreach ($records as $key => $record) {
				if (strtotime($record->created) >= $params['timestamp_start'] && strtotime($record->created) <= $params['timestamp_end']) {
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
				if ($check) {
					$point = GobuyHikashopOrderProduct::where('order_id', $webRecord->order_id)->first();
					$array[] = [
						'id' => $webRecord->order_id -1,
						'phone_number' => $orderPhone['profile_value'],
						'money' => (int)-$point->order_product_price
					];
				}
			}
		}
        array_multisort($array, SORT_DESC);
		return $array;
	}
	//待送點數會員 電話轉deviceToken
	public function phoneToDeviceToken($params)
	{
		$user = GobuyUserProfile::where('profile_value', $params)
								->where('ordering', 2)
								->first();
		$deviceToken = UserDeviceToken::where('user_id', $user['user_id'])->get();
		return $deviceToken;
	}
}
