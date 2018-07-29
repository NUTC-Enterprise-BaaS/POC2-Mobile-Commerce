<?php
namespace App\Repositories;

use Auth;
use App\Entities\Emoji;
use App\Entities\GobuyUser;
use App\Entities\GobuyUserUsergroupMap;
use App\Entities\GobuyUserProfile;
use App\Entities\GobuyJbusinessdirectoryCompany;
use App\Entities\GobuyJbusinessdirectoryCompanyContact;
use App\Entities\GobuyJbusinessdirectoryCompanyReview;
use App\Entities\Code;
use App\Entities\GraphCode;
use App\Entities\CheckVersion;
use App\Entities\UserLikeShop;
use App\Entities\UserDeviceToken;
use App\Entities\GobuySocialPointsHistory;
use JWTAuth;
use Gregwar\Captcha\CaptchaBuilder;
use DB;
use Curl\Curl;
use App\Entities\Ldap;
use App\Entities\ShopVouchers;

class UserRepository
{
    private $host = '10.0.0.59:8082';
    private $stor = 'LELIGO'; 
    public function __construct(Curl $curl)
    {
      $this->curl = $curl;
    }
    public function getStorRate($params){
      $this->url = 'http://10.0.0.79:8000/api/v1/user/getOriginRate';
      $this->curl->setHeader('Accept', 'application/json');
      $this->curl->setHeader('Content-Type', 'application/json');
      $this->body = '{"store":"'.$params->store.'"}';
      $this->curl->post($this->url,$this->body);
      $data =  json_decode($this->curl->response);
      return $data;
    }
    public function register($params)
    {
        $phone = GobuyUserProfile::where('profile_value', $params['phone'])
                ->where('ordering', 2)
                ->first();
         if (isset($params['qrcode']) && !empty($params['qrcode'])) {
            $recommend = GobuyUser::where('id', $params['qrcode'])->first();
            if (is_null($recommend)) {
                return 2;
            }
        }
        if (!is_null($phone)) {
          return 1;
        }
        GobuyUser::create(['name' => $params['name'],
                           'username' => $params['email'],
                           'email' => $params['email'],
                           'password'=> bcrypt($params['password']),
                           'registerDate' => date("Y-m-d H:i:s")
        ]);
        $userId = GobuyUser::where('username', $params['email'])->first();
        GobuySocialPointsHistory::create(['user_id' => $userId->id,
                                              'points_id' => 8,
                                              'points' => 100,
                                              'created' => date("Y/m/d H:i:s"),
                                              'state' => 1,
                                              'message' => '獲得註冊點數',
            ]);
        Ldap::create(['user_id' => $userId->id,
                        'ldap_status' => 0,
                        'ldap_token' => '',
            ]);
        GobuyUserUsergroupMap::create(['user_id' => $userId->id,
                                       'group_id' => 2
        ]);
        GobuyUserProfile::create(['user_id' => $userId->id,
                                  'profile_key' => 'profile_extend.country',
                                  'profile_value' => $params['country'],
                                  'ordering' => 1
        ]);
        GobuyUserProfile::create(['user_id' => $userId->id,
                                  'profile_key' => 'profile_extend.dob',
                                  'profile_value' => $params['birthday'],
                                  'ordering' => 3
        ]);
        GobuyUserProfile::create(['user_id' => $userId->id,
                                  'profile_key' => 'profile_extend.phone',
                                  'profile_value' => $params['phone'],
                                  'ordering' => 2
        ]);
        if (isset($params['qrcode']) && !empty($params['qrcode'])) {
            $recommend = GobuyUser::where('id', $params['qrcode'])->first();
            if (is_null($recommend)) {
                return 2;
            }
            GobuyUserProfile::create(['user_id' => $userId->id,
                                      'profile_key' => 'profile_extend.recommender',
                                      'profile_value' => $params['qrcode'],
                                      'ordering' => 4
            ]);
            GobuySocialPointsHistory::create(['user_id' => $params['qrcode'],
                                              'points_id' => 7,
                                              'points' => 200,
                                              'created' => date("Y/m/d H:i:s"),
                                              'state' => 1,
                                              'message' => '推薦使用者註冊獲得點數',
            ]);
            GobuySocialPointsHistory::create(['user_id' => $userId->id,
                                              'points_id' => 7,
                                              'points' => 300,
                                              'created' => date("Y/m/d H:i:s"),
                                              'state' => 1,
                                              'message' => '受會員推薦獲得註冊點數',
            ]);
        }
        return 0;
	  }

    public function validateLogin($params)
    {
        $userPhone = GobuyUserProfile::where('profile_value', $params['account'])
                                ->first();
        if ($userPhone) {
            $user = GobuyUser::where('id', $userPhone['user_id'])->first();
            return $user['username'];
        }
        return 0;
    }
    public function getUserPoint($user)
    {
      $ldap = Ldap::where('user_id',$user->id)->first();
      if($ldap->ldap_status == 0){
        $point['stor'] = $this->stor;
        $point['point'] = GobuySocialPointsHistory::where('user_id',$user->id)
                                          ->sum('points');
        return $point;
      }else{
        $this->url = 'http://'.$this->host.'/api/ldap/getPoint';
        $this->curl->setHeader('Accept', 'application/json');
        $this->curl->setHeader('Content-Type', 'application/json');
        $this->body = '{"stor":"'.$this->stor.'","email":"'.$user->email.'"}';
        $this->curl->post($this->url,$this->body);
        $data =  json_decode($this->curl->response);
        $data->token = $ldap->ldap_token;
        return $data;
      }
    }
    public function ldapUserAdd($user)
    {
        $ldap = Ldap::where('user_id',$user->id)->first();
        if($ldap->ldap_status == 0){
          $point = GobuySocialPointsHistory::where('user_id',$user->id)
                                            ->sum('points');
          $this->url = 'http://'.$this->host.'/api/ldap/useradd';
          $this->curl->setHeader('Accept', 'application/json');
          $this->curl->setHeader('Content-Type', 'application/json');
          $this->body = '{"stor":"'.$this->stor.'","email":"'.$user->email.'","point":'.$point.'}';
          $this->curl->post($this->url,$this->body);
          $data =  json_decode($this->curl->response);
          if($data->token != null){
            Ldap::where('user_id',$user->id)->update(['ldap_status'=>1,'ldap_token'=>$data->token]);
            return $data->token;
          }else{
            return null;
          }
        }else{
            return $ldap->ldap_token;
        }
    }
    public function ldapUserTokenAdd($user,$verify_code)
    {
        $ldap = Ldap::where('user_id',$user->id)->first();
        if($ldap->ldap_status == 0){
        }else if($ldap == null){
            return 'user no exist in Ldap';
        }else{
            $binding = $this->getStor($user);
            if(count($binding->list) > 1){
                return 'already binding';            
            }
        }
        $point = GobuySocialPointsHistory::where('user_id',$user->id)
                                            ->sum('points');
        $this->url = 'http://'.$this->host.'/api/ldap/u2u/useradd';
        $this->curl->setHeader('Accept', 'application/json');
        $this->curl->setHeader('Content-Type', 'application/json');
        $this->body = '{"stor":"'.$this->stor.'","email":"'.$user->email.'","point":'.$point.',"verify_code":"'.$verify_code.'"}';
        $this->curl->post($this->url,$this->body);
        $data =  json_decode($this->curl->response);
        if(isset($data->token)){ 
            Ldap::where('user_id',$user->id)->update(['ldap_status'=>1,'ldap_token'=>$data->token]);
            return $data;
        }else if($data == null){
            return 'api error';
        }else{
            return $data;
        }
    }
    public function cleanBinding($user)
    {
        $ldap = Ldap::where('user_id',$user->id)->first();
        if($ldap != null){
            $this->url = 'http://'.$this->host.'/ldap/binding/clear';
            $this->curl->setHeader('Accept', 'application/json');
            $this->curl->setHeader('Content-Type', 'application/json');
            $this->body = '{"stor":"'.$this->stor.'","email":"'.$user->email.'"}';
            $this->curl->post($this->url,$this->body);
            $data =  json_decode($this->curl->response);
            if(isset($data->token)){ 
              Ldap::where('user_id',$user->id)->update(['ldap_status'=>1,'ldap_token'=>$data->token]);
              return $data->message;
            }else if($data == null){
              return 'api error';
            }else{
              return $data->message;
            }
        }else{
            return 'user no exist in Ldap';
        }
    }
    public function getVerifyCode($user)
    {
      $ldap = Ldap::where('user_id',$user->id)->first();
      $this->url = 'http://'.$this->host.'/api/ldap/getverifycode';
      $this->curl->setHeader('Accept', 'application/json');
      $this->curl->setHeader('Content-Type', 'application/json');
      $this->body = '{"token":"'.$ldap->ldap_token.'"}';
      $this->curl->post($this->url,$this->body);
      $data =  json_decode($this->curl->response);
      if($data->verifycode != null){
        return $data->verifycode;
      }else{
        return null;
      }
    }
    public function ldapUserPointChange($user,$params)
    {
        $ldap = Ldap::where('user_id',$user->id)->first();
        $this->url = 'http://'.$this->host.'/api/ldap/point/change';
        $this->curl->setHeader('Accept', 'application/json');
        $this->curl->setHeader('Content-Type', 'application/json');
        $this->body = '{"token":"'.$ldap->ldap_token.'","txPoint":'.$params->point.',"formUser":"'.$user->email.'","fromStor":"'.$this->stor.'","toUser":"'.$params->toUser.'","toStor":"'.$params->toStor.'"}';
        $this->curl->post($this->url,$this->body);
        $data =  json_decode($this->curl->response);
        if($data != null){
          GobuySocialPointsHistory::create(['user_id' => $user->id,
                                              'points_id' => 9,
                                              'points' => -((int)$params->point),
                                              'created' => date("Y/m/d H:i:s"),
                                              'state' => 1,
                                              'message' => $params->message,
            ]);
        }
        return $data;
    }
    public function receiveLdapPointChange($username)
    {
      $user = GobuyUser::where('username',$username)->first();
      $point = $this->getUserPoint($user);
      if(isset($point->point)){
        $storPoint = GobuySocialPointsHistory::where('user_id',$user->id)
                                        ->sum('points');
        $nowPoint = ($point->point) - $storPoint;
        if($nowPoint != 0){
          GobuySocialPointsHistory::create(['user_id' => $user->id,
                                            'points_id' => 9,
                                            'points' => $nowPoint,
                                            'created' => date("Y/m/d H:i:s"),
                                            'state' => 1,
                                            'message' => '平台點數交易',
          ]);
          return "OK";
        }else{
          return "OK";
        }
      }
      return "NO";
    }
     public function getShopVoucher($user,$params)
     {
       $voucher = ShopVouchers::create(['user_id' => $user->id,
                                 'voucher_status' => 1,
                                 'voucher_message' => $params->message,
            ]);
      $params->message = '購買'.$params->message;
       $point = $this->ldapUserPointChange($user,$params);
       if($point == null || $voucher == null){
         return 'Failed purchase';
       }else{
         return 'Purchase success';
       }
     }
     public function getVoucherList($user)
     {
       $list=[];
       $data = ShopVouchers::where(['user_id'=>$user->id,'voucher_status'=>1])->get();
       foreach ($data as $Data){
         $list[] = [
         "id" => $Data->id,
         "voucher_name" => $Data->voucher_message,
         "buy_date" => $Data->created_at,
         ];
       }
       return $list;
     }
     public function disableVoucherList($user,$params)
     {
       $voucher = ShopVouchers::where(["user_id"=>$user->id,"id"=>$params->voucherid])->update(['voucher_status'=>0]);
       if($voucher != null){
         return 'use voucher';
       }else{
         return 'use fail';
       }
     }
     public function getStor($user){
      $ldap = Ldap::where('user_id',$user->id)->first();
      if($ldap->ldap_status != 0){
        $this->url = 'http://'.$this->host.'/api/ldap/showStor';
        $this->curl->setHeader('Accept', 'application/json');
        $this->curl->setHeader('Content-Type', 'application/json');
        $this->body = '{"token":"'.$ldap->ldap_token.'"}';
        $this->curl->post($this->url,$this->body);
        $data =  json_decode($this->curl->response);
        return $data;
      }else{
        return "no binding";
      }
    }
    public function checkUser($params)
    {
        $user = GobuyUser::where('username', $params['username'])->first();
        if ($user->block == 0) {
            return 0;
        }
        return 1;
    }
    public function login($params, $member)
    {
        if ($member) {
            $userId = GobuyUser::where('username', $member)->first();
            $user = GobuyUserUsergroupMap::where('user_id', $userId->id)->first();
        } else {
            $userId = GobuyUser::where('username', $params['account'])->first();
            $user = GobuyUserUsergroupMap::where('user_id', $userId->id)->first();
        }
        if ($user['group_id'] == 8) {
            $superUserSpe = GobuyJbusinessdirectoryCompany::where('userId', $userId->id)
                              ->where('shop_class', 1)
                              ->first();
            $superUserPre = GobuyJbusinessdirectoryCompany::where('userId', $userId->id)
                              ->where('shop_class', 2)
                              ->first();
            if ($superUserSpe && is_null($superUserPre)) {
                $user['group_id'] = 5;
            }
            if ($superUserPre && is_null($superUserSpe)) {
                $user['group_id'] = 6;
            }
            if ($superUserSpe && $superUserPre) {
                $user['group_id'] = 7;
            }
        }
        switch ($user['group_id']) {
          case 2:
            return 0;   //一般會員
            break;
          case 5:
            return 5;   //超級使用者 註冊特約商店 尚未註冊 優惠商店
            break;
          case 6:
            return 6;   //超級使用者 註冊優惠商店 尚未註冊 特約商店
            break;
          case 7:
            return 7;   //超級使用者 註冊特約、優惠商店
            break;
          case 8:
            return 4;   //超級使用者
            break;
          case 14:
            return 1;   //特約會員
            break;
          case 15:
            return 2;   //優惠會員
            break;
          case 16:
            return 3;   //特約&優惠會員
            break;
          case 17:
            return 0;   //業務&一般會員
            break;
          case 18:
            return 1;   //業務&特約會員
            break;
          case 19:
            return 2;   //業務&優惠會員
            break;
          case 20:
            return 3;   //業務&特約&優惠會員
            break;
        }
    }

    public function send($params, $authnum)
    {
        if (isset($params['phone_number'])) {
            $phone = GobuyUserProfile::where('profile_value', $params['phone_number'])
                                  ->where('ordering', 2)
                                  ->first();
            if (is_null($phone)) {
              return 3;
            }
            $user = GobuyUser::where('id', $phone['user_id'])->first();
        }
        if (isset($params['email'])) {
            $user = GobuyUser::where('email', $params['email'])->first();
            if (is_null($user)) {
              return 2;
            }
        }
        $userEmail = $user['email'];
        if (is_null(Code::where('email', $userEmail)->first())) {
            Code::create(['email' => $userEmail,
                          'verify_code' => $authnum]);
        } else {
            Code::where('email', $userEmail)
                ->update(['verify_code' => $authnum]);
        }
        return $userEmail;
    }

    public function check($params)
    {
        if (isset($params['phone'])) {
            $phone = GobuyUserProfile::where('profile_value', $params['phone'])
                                  ->where('ordering', 2)
                                  ->first();
            if (is_null($phone)) {
              return 3;
            }
            $user = GobuyUser::where('id', $phone['user_id'])->first();
        }
        if (isset($params['email'])) {
            $user = GobuyUser::where('email', $params['email'])->first();
            if (is_null($user)) {
              return 2;
            }
        }
        $userEmail = $user['email'];
        if ((Code::where('verify_code', $params['verify_code'])->first()) &&
            (Code::where('email', $userEmail)->first())) {
                return 0;
        } else {
            return 1;
        }
    }

    public function resPassword($params)
    {
        if ((Code::where('verify_code', $params['verify_code'])->first()) &&
            (Code::where('email', $params['email'])->first())) {
                 GobuyUser::where('email', $params['email'])
                    ->update(['password'    => bcrypt($params['password'])]);
                    return true;
        } else {
            return false;
        }
    }

    public function specialReg($params)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $group = GobuyUserUsergroupMap::where('user_id', $user['id'])->first();
        $check = GobuyJbusinessdirectoryCompany::where('userId', $user['id'])
                                            ->where('shop_class', 1)
                                            ->first();
        if (isset($params['qrcode']) && !empty($params['qrcode'])) {
            $recommend = GobuyUser::where('id', $params['qrcode'])->first();
            if (is_null($recommend)) {
                return 5;
            }
        }
        if (!is_null($check)) {
          return 4;
        }
        if ($params['store_type'] == 0 || $params['store_type'] == 2) {
            if (empty($params['store_address'])) {
                return 1;
            }
        }
        if ($group['group_id'] == 15) {
            GobuyUserUsergroupMap::where('user_id', $user['id'])
                            ->update(['group_id' => 16]);
        } else {
            if ($group['group_id'] != 8) {
              GobuyUserUsergroupMap::where('user_id', $user['id'])
                            ->update(['group_id' => 14]);
            }
        }
        if ($group['group_id'] == 19) {
            GobuyUserUsergroupMap::where('user_id', $user['id'])
                            ->update(['group_id' => 20]);
        } else {
            if ($group['group_id'] != 8 && $group['group_id'] == 17) {
              GobuyUserUsergroupMap::where('user_id', $user['id'])
                            ->update(['group_id' => 18]);
            }
        }
        $phone = GobuyUserProfile::where('user_id', $user['id'])
                  ->where('ordering', 2)
                  ->first();
        GobuyJbusinessdirectoryCompany::create([
            'name' => $params['store_name'],
            'alias' => $params['store_name'] . '-1',
            'registrationCode' => $params['store_type'],
            'typeId' => $params['category_employment'],
            'address' => $params['store_address'],
            'userId' => $user['id'],
            'phone' => $phone['profile_value'],
            'shop_class' => 1,
            'group' => $params['qrcode'],
            'countryId' => 220,
            'creationDate' => date("Y-m-d H:i:s")
        ]);
        $convertAddress = $this->addressConvert($params['store_address']);
        $companyId = GobuyJbusinessdirectoryCompany::where('userId', $user['id'])
                                                ->where('shop_class', 1)
                                                ->first();
        GobuyJbusinessdirectoryCompany::where('userId', $user['id'])
                                    ->where('shop_class', 1)
                                    ->update(['latitude' => $convertAddress['lat'],
                                              'longitude' => $convertAddress['lng']
                                    ]);
        GobuyJbusinessdirectoryCompanyContact::create([
            'companyId' => $companyId->id,
            'contact_name' => $params['contact_person'],
            'contact_email' => $user['email'],
            'contact_phone' => $phone['profile_value'],
            'contact_fax' => $params['contact_person_sex']
        ]);
        if (isset($params['store_logo']) && !empty($params['store_logo'])) {
            $fileName = $this->uploadStoreLogo($params['store_logo'], $companyId->id);
            $storeLogo = GobuyJbusinessdirectoryCompany::where('userId', $user['id'])
                            ->where('shop_class', 1)
                            ->first();
            $storeLogo->update(['logoLocation' => '/companies/' . $companyId->id . '/'. $fileName]);
            if (isset($params['store_image']) && !empty($params['store_image'])) {
                $this->uploadStoreImage($params['store_image'], $companyId->id);
            }
        }
        if (isset($params['qrcode']) && !empty($params['qrcode'])) {
            $recommend = GobuyUser::where('id', $params['qrcode'])->first();
            if (is_null($recommend)) {
                return 5;
            }
            GobuyJbusinessdirectoryCompany::where('userId', $user['id'])
                                        ->where('shop_class', 1)
                                        ->update([
                                            'group' => $params['qrcode']
                                        ]);
        }
        return 0;
    }

    public function graphCode($verify_code, $img)
    {
        $user = JWTAuth::parseToken()->authenticate();
        while(($checkId=rand()%1000)<100);
        if (is_null(GraphCode::where('email', $user['email'])->first())) {
            GraphCode::create(['email' => $user['email'],
                               'check_id' => $checkId,
                               'verify_code' => $verify_code,
                               'address' => "http://106.184.6.69:8080/$img"
            ]);
        } else {
            GraphCode::where('email', $user['email'])
              ->update(['check_id' => $checkId,
                        'verify_code' => $verify_code,
                        'address' => "http://106.184.6.69:8080/$img"
            ]);
        }
        $array = ['id' => $checkId,
                  'verify_code_url' => "http://106.184.6.69:8080/$img"
        ];
        return $array;
    }

    public function detail()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $userId = GobuyUser::where('email', $user['email'])->first()->id;
        $infos = GobuyUserProfile::where('user_id', $userId)->get();
        foreach ($infos as $info) {
           $array[] = $info->profile_value;
        }
        if (!empty($array[0])) {
           $array[0] = $this->countryConvert($array[0]);
        }
        $group = GobuyUserUsergroupMap::where('user_id', $user['id'])->first();
        if ($group->group_id == 17 || $group->group_id == 18 || $group->group_id == 19 || $group->group_id == 20) {
            $array[4] = 1;
        } else {
            $array[4] = 0;
        }
        return $array;
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
    public function modify($params)
    {
        $user = JWTAuth::parseToken()->authenticate();
        if (isset($params['user_email'])) {
          if (empty($params['user_email'])) {
            return 2;
          }
          $email = GobuyUser::where('email', $params['user_email'])->first();
          if (!is_null($email)) {
            return 1;
          }
          GobuyUser::where('id', $user['id'])
                  ->update(['username' => $params['user_email'],
                            'email' => $params['user_email']
                  ]);
          return 0;
        }
    }
    public function resetPwd($params)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $data = Auth::attempt(['email' => $user['email'], 'password' => $params['old_password']]);
        if (empty($params['new_password'])) {
            return 1;
        }
        if ($params['old_password'] === $params['new_password']) {
            return 3;
        }

        if (!$data) {
            return 2;
        }
        else {
            GobuyUser::where('id', $user['id'])
                    ->update(['password'    => bcrypt($params['new_password'])
                            ]);
            return 0;
        }
    }
    public function specialDetail()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $detail = GobuyUser::where('id', $user['id'])->first();
        $userCountry = GobuyUserProfile::where('user_id', $user['id'])
                                ->where('ordering', '1')
                                ->first();
        $userBirthday = GobuyUserProfile::where('user_id', $user['id'])
                                ->where('ordering', '3')
                                ->first();
        $userPhone = GobuyUserProfile::where('user_id', $user['id'])
                                ->where('ordering', '2')
                                ->first();
        $company = GobuyJbusinessdirectoryCompany::where('userId', $user['id'])
                                                ->where('shop_class', 1)
                                                ->first();
        $compacyContact = GobuyJbusinessdirectoryCompanyContact::where('companyId', $company['id'])
                                                            ->first();
        if (!empty($userCountry['profile_value'])) {
           $userCountry = $this->countryConvert($userCountry['profile_value']);
        } else {
            $userCountry = '';
        }
        $array = [
            'user_account' => $detail['username'],
            'user_phone' => $userPhone['profile_value'],
            'user_birthday' => $userBirthday['profile_value'],
            'user_country' => $userCountry,
            'user_email' => $detail['email'],
            'store_address' => $company['address'],
            'store_url' => $company['website'],
            'store_contact' => $compacyContact['contact_name'],
            'store_name' => $company['name']
        ];
        return $array;
    }

    public function preReg($params)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $group = GobuyUserUsergroupMap::where('user_id', $user['id'])->first();
        $check = GobuyJbusinessdirectoryCompany::where('userId', $user['id'])
                                          ->where('shop_class', 2)
                                          ->first();
        if (isset($params['qrcode']) && !empty($params['qrcode'])) {
            $recommend = GobuyUser::where('id', $params['qrcode'])->first();
            if (is_null($recommend)) {
                return 5;
            }
        }
        if (!is_null($check)) {
          return 4;
        }
        if ($params['store_type'] == 0 || $params['store_type'] == 2) {
            if (empty($params['store_address'])) {
                return 1;
            }
        }
        if ($group['group_id'] == 14) {
            GobuyUserUsergroupMap::where('user_id', $user['id'])
                            ->update(['group_id' => 16]);
        } else {
            if ($group['group_id'] != 8) {
              GobuyUserUsergroupMap::where('user_id', $user['id'])
                            ->update(['group_id' => 15]);
            }
        }
        if ($group['group_id'] == 18) {
            GobuyUserUsergroupMap::where('user_id', $user['id'])
                            ->update(['group_id' => 20]);
        } else {
            if ($group['group_id'] != 8 && $group['group_id'] == 17) {
              GobuyUserUsergroupMap::where('user_id', $user['id'])
                            ->update(['group_id' => 19]);
            }
        }
        $phone = GobuyUserProfile::where('user_id', $user['id'])
                  ->where('ordering', 2)
                  ->first();
        GobuyJbusinessdirectoryCompany::create([
            'name' => $params['store_name'],
            'alias' => $params['store_name'] . '-2',
            'registrationCode' => $params['store_type'],
            'typeId' => $params['category_employment'],
            'address' => $params['store_address'],
            'userId' => $user['id'],
            'phone' => $phone['profile_value'],
            'shop_class' => 2,
            'group' => $params['qrcode'],
            'countryId' => 220,
            'creationDate' => date("Y-m-d H:i:s")
        ]);
        $convertAddress = $this->addressConvert($params['store_address']);
        $companyId = GobuyJbusinessdirectoryCompany::where('userId', $user['id'])
                                                ->where('shop_class', 2)
                                                ->first();
        GobuyJbusinessdirectoryCompany::where('userId', $user['id'])
                                    ->where('shop_class', 2)
                                    ->update(['latitude' => $convertAddress['lat'],
                                              'longitude' => $convertAddress['lng']
                                    ]);
        GobuyJbusinessdirectoryCompanyContact::create([
            'companyId' => $companyId->id,
            'contact_name' => $params['contact_person'],
            'contact_email' => $user['email'],
            'contact_phone' => $phone['profile_value'],
            'contact_fax' => $params['contact_person_sex']
        ]);
        if (isset($params['store_logo']) && !empty($params['store_logo'])) {
            $fileName = $this->uploadStoreLogo($params['store_logo'], $companyId->id);
            $storeLogo = GobuyJbusinessdirectoryCompany::where('userId', $user['id'])
                            ->where('shop_class', 2)
                            ->first();
            $storeLogo->update(['logoLocation' => '/companies/' . $companyId->id . '/'. $fileName]);
            if (isset($params['store_image']) && !empty($params['store_image'])) {
                $this->uploadStoreImage($params['store_image'], $companyId->id);
            }
        }
        if (isset($params['qrcode']) && !empty($params['qrcode'])) {
            $recommend = GobuyUser::where('id', $params['qrcode'])->first();
            if (is_null($recommend)) {
                return 5;
            }
            GobuyJbusinessdirectoryCompany::where('userId', $user['id'])
                                        ->where('shop_class', 2)
                                        ->update([
                                            'group' => $params['qrcode']
                                        ]);
        }
        return 0;
    }
    public function getDevice($params)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $device = $params['device'];
        $deviceToken = $params['instance_id'];
        $check = UserDeviceToken::where('user_id', $user['id'])->first();
        if (is_null($check)) {
            UserDeviceToken::create([
                'user_id' => $user['id'],
                'name' => $user['name'],
                'username' => $user['username'],
                'email' => $user['email'],
                'device' => $device,
                'device_token' => $deviceToken
            ]);
            return 0;
        }
        UserDeviceToken::where('user_id', $user['id'])
                ->update(['device_token' => $deviceToken,
                          'device' => $device
                ]);
        return 0;
    }
    public function deviceLogout()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $check = UserDeviceToken::where('user_id', $user['id'])->first();
        if (is_null($check)) {
            return 0;
        }
        UserDeviceToken::where('user_id', $user['id'])
                ->update(['device_token' => null,
                          'device' => null
                ]);
        return 1;
    }
    public function notifyPayment()
    {
        $devices = DB::table('user_device_tokens')->get();
        foreach ($devices as $key => $device) {
            $array[] = ['user_id' => $device->user_id,
                        'device' => $device->device,
                        'device_token' => $device->device_token,
            ];
            if (empty($device->device_token)) {
                unset($array[$key]);            //搂R掳拢deviceToken隆@卢掳陋脜陋潞
            }
            $array = array_values($array);  //颅芦戮茫掳}娄C
        }
        return $array;
    }
    public function idToPhone($userId)
    {
        $phone = GobuyUserProfile::where('user_id', $userId)
                      ->where('ordering', 2)
                      ->first();
        if ($phone) {
          return $phone['profile_value'];
        }
    }
    public function recommendList()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $recommend = GobuyUserProfile::where('user_id', $user['id'])
                        ->where('ordering', 4)
                        ->first();
        $generalPhone = $this->idToPhone($recommend['profile_value']);
        $recommendSpe = GobuyJbusinessdirectoryCompany::where('userId', $user['id'])
                                      ->where('shop_class', 1)
                                      ->first();
        $spePhone = $this->idToPhone($recommendSpe['group']);
        $recommendPre = GobuyJbusinessdirectoryCompany::where('userId', $user['id'])
                                      ->where('shop_class', 2)
                                      ->first();
        $prePhone = $this->idToPhone($recommendPre['group']);
        if (is_null($generalPhone)) {
          $generalPhone = '';
        }
        if (is_null($spePhone)) {
          $spePhone = '';
        }
        if (is_null($prePhone)) {
          $prePhone = '';
        }
        $array = [
          'general' => $generalPhone,
          'special' => $spePhone,
          'premium' => $prePhone
        ];
        return $array;
    }
    public function setUser($qrcode)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $beUser = GobuyUser::where('id', $qrcode)
                      ->where('block', 0)
                      ->first();
        if (is_null($beUser)) {
            return 1;
        }
        if ($user['id'] == $qrcode) {
            return 2;
        }
        GobuyUserProfile::create([
            'user_id' => $user['id'],
            'profile_key' => 'profile_extend.recommender',
            'ordering' => 4,
            'profile_value' => $qrcode
        ]);
        GobuySocialPointsHistory::create([
            'points_id' => 7,
            'user_id' => $qrcode,
            'points' => (int)200,
            'created' => date("Y-m-d H:i:s"),
            'state' => 1,
            'message' => '推者' . $beUser['name'] . '註冊獲得點數'
        ]);
        GobuySocialPointsHistory::create([
            'points_id' => 7,
            'user_id' => $user['id'],
            'points' => (int)300,
            'created' => date("Y-m-d H:i:s"),
            'state' => 1,
            'message' => '受者' . $user['name'] . '獲得註冊點數'
        ]);
    }
    public function setSpe($qrcode)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $beUser = GobuyUser::where('id', $qrcode)
                    ->where('block', 0)
                    ->first();
        if (is_null($beUser)) {
            return 1;
        }
        if ($user['id'] == $qrcode) {
            return 2;
        }
        $company = GobuyJbusinessdirectoryCompany::where('userId', $user['id'])
                                ->where('shop_class', 1)
                                ->where('approved', 2)
                                ->first();
        if (is_null($company)) {
            return 2;
        }
        $company->update([
          'group' => $qrcode,
          'point_state' => 1
        ]);
        GobuySocialPointsHistory::create([
            'points_id' => 7,
            'user_id' => $qrcode,
            'points' => (int)200,
            'created' => date("Y-m-d H:i:s"),
            'state' => 1,
            'message' => '推者' . $beUser['name'] . '註冊特約會員獲得點數'
        ]);
        GobuySocialPointsHistory::create([
            'points_id' => 7,
            'user_id' => $user['id'],
            'points' => (int)300,
            'created' => date("Y-m-d H:i:s"),
            'state' => 1,
            'message' => '受者' . $user['name'] . '推薦獲得註冊特約會員點數'
        ]);
    }
    public function setPre($qrcode)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $beUser = GobuyUser::where('id', $qrcode)
                    ->where('block', 0)
                    ->first();
        if (is_null($beUser)) {
            return 1;
        }
        if ($user['id'] == $qrcode) {
            return 2;
        }
        $company = GobuyJbusinessdirectoryCompany::where('userId', $user['id'])
                                ->where('shop_class', 2)
                                ->where('approved', 2)
                                ->first();
        if (is_null($company)) {
            return 2;
        }
        $company->update([
          'group' => $qrcode,
          'point_state' => 1
        ]);
        GobuySocialPointsHistory::create([
            'points_id' => 7,
            'user_id' => $qrcode,
            'points' => (int)200,
            'created' => date("Y-m-d H:i:s"),
            'state' => 1,
            'message' => '推者' . $beUser['name'] . '註冊優惠會員獲得點數'
        ]);
        GobuySocialPointsHistory::create([
            'points_id' => 7,
            'user_id' => $user['id'],
            'points' => (int)300,
            'created' => date("Y-m-d H:i:s"),
            'state' => 1,
            'message' => '受者' . $user['name'] . '推薦獲得註冊優惠會員點數'
        ]);
    }
    public function beRecommend()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $deviceTokens = UserDeviceToken::where('user_id', $user['id'])->get();
        foreach ($deviceTokens as $key => $deviceToken) {
            $devices[] = [
                'device' => $deviceToken->device,
                'device_token' => $deviceToken->device_token
            ];
        }
        return $devices;
    }
    public function recommend($user_id)
    {
        $deviceTokens = UserDeviceToken::where('user_id', $user_id)->get();
        foreach ($deviceTokens as $key => $deviceToken) {
            $devices[] = [
                'device' => $deviceToken->device,
                'device_token' => $deviceToken->device_token
            ];
        }
        return $devices;
    }
    public function userIdentity()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $spe = GobuyJbusinessdirectoryCompany::where('userId', $user['id'])
                        ->where('shop_class', 1)
                        ->first();
        $pre = GobuyJbusinessdirectoryCompany::where('userId', $user['id'])
                        ->where('shop_class', 2)
                        ->first();
        if (is_null($spe)) {
            $spe = 0;
        } else {
            if ($spe['approved'] != 2) {
                $spe = 1;
            } else {
                $spe = 2;
            }
        }
        if (is_null($pre)) {
            $pre = 0;
        } else {
            if ($pre['approved'] != 2) {
                $pre = 1;
            } else {
                $pre = 2;
            }
        }
        $array = [
            'special' => $spe,
            'preferential' => $pre
        ];
        return $array;
    }
    //掳虏赂锚庐脝麓煤赂脮
    public function userIdCard($id_card_number)
    {
        if ($id_card_number == 'A127479377' || $id_card_number == 'A233199926') {
            $user = JWTAuth::parseToken()->authenticate();
            $group = GobuyUserUsergroupMap::where('user_id', $user['id'])->first();
            switch ($group->group_id) {
                case 2:
                    $group->where('user_id', $user['id'])->update([
                        'group_id' => 17
                    ]);
                    return 0;
                    break;
                case 14:
                    $group->where('user_id', $user['id'])->update([
                        'group_id' => 18
                    ]);
                    return 0;
                    break;
                case 15:
                    $group->where('user_id', $user['id'])->update([
                        'group_id' => 19
                    ]);
                    return 0;
                    break;
                case 16:
                    $group->where('user_id', $user['id'])->update([
                        'group_id' => 20
                    ]);
                    return 0;
                    break;
            }
        } else {
            return 1;
        }
    }
    public function userAdvise($params)
    {
        return $array;
    }
    //國籍別轉換
    private function countryConvert($params)
    {
         switch ($params) {
          case '中國':
              $params = 0;
              return $params;
            break;
          case '馬來西亞':
              $params = 1;
              return $params;
            break;
          case '新加坡':
              $params = 2;
              return $params;
            break;
          case '韓國':
              $params = 3;
              return $params;
            break;
          case '日本':
              $params = 4;
              return $params;
            break;
          case '香港':
              $params = 5;
              return $params;
            break;
          case '緬甸':
              $params = 6;
              return $params;
          break;
          case '台灣':
              $params = 7;
              return $params;
          break;
           case '臺灣':
              $params = 7;
              return $params;
          break;
        }
    }
    //地址轉換經緯度
    public function addressConvert($addr_str_array)
    {
        $addr_str_array = [$addr_str_array];
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
            //露l禄录掳脧赂鹿
            $geo_zip = $geo['results'][0]['address_components'][$num_components-1]['long_name'];
            //陆n芦脳
            $geo_lat = $geo['results'][0]['geometry']['location']['lat'];
            //赂g芦脳
            $geo_lng = $geo['results'][0]['geometry']['location']['lng'];
            $array = [
                'zip' => $geo_zip,
                'lat' => $geo_lat,
                'lng' => $geo_lng
            ];
            return $array;
        }
    }
    //陇W露脟漏卤庐a芦脢颅卤赂锚掳T
    public function uploadStoreLogo($file, $id)
    {
        mkdir("/home/ginkerapp/public_html/media/com_jbusinessdirectory/pictures/companies/$id" , 0777);    //麓脌鹿脧陇霉虏拢楼脥赂锚庐脝搂篓
        $fileName = strval(time()) . str_random(5) . '.jpg';
        $imageDec = base64_decode($file);
        $ifp = fopen($fileName, "wb");
        $data = explode(',', $imageDec);
        fwrite($ifp, $imageDec);
        fclose($ifp);
        rename($fileName, "/home/ginkerapp/public_html/media/com_jbusinessdirectory/pictures/companies/$id/" . $fileName);
        return $fileName;
    }
    //上傳店家封面資訊
    public function uploadStoreImage($files, $id)
    {
        foreach ($files as $key => $file) {
            $fileName = strval(time()).str_random(5).'.jpg';
            $imageDec = base64_decode($file);
            $ifp = fopen($fileName, "wb");
            $data = explode(',', $imageDec);
            fwrite($ifp, $imageDec);
            fclose($ifp);
            rename($fileName, "/home/ginkerapp/public_html/media/com_jbusinessdirectory/pictures/companies/$id/" . $fileName);
            $imageName[] = $fileName;
        }
    }
}


