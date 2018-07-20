<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// use Log;
use App\Http\Requests;
use App\Entities\Code;
use App\Entities\GobuyUser;
use App\Entities\GobuyUserUsergroupMap;
use App\Repositories\UserRepository;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\CheckRequest;
use App\Http\Requests\RescuePasswordRequest;
use App\Http\Requests\SpecialRegisterRequest;
use App\Http\Requests\FeedBackRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\DeviceRequest;
use App\Http\Requests\SetRecommendRequest;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Entities\Emoji;
use Mail;
use App\Jobs\SendRecommendPoint;
use App\Jobs\SendReminderEmail;
use App\Jobs\DeleteImage;
use App\Http\Controllers\Controller;
use Gregwar\Captcha\CaptchaBuilder;
use App\Jobs\PushAppNotification;
use PushNotification;

class UsersController extends Controller
{
    protected $users = null;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }
	//使用者註冊
    public function postRegister(RegisterRequest $request)
    {
        $register = $this->users->register($request->all());
        if ($register == 1) {
            return response()->json([
                'result' => 1,
                'message' => ['The phone has already been taken']
            ]);
        }
        if ($register == 2) {
            return response()->json([
                'result' => 1,
                'message' => ['The recommender does not exist']
            ]);
        }
    	return response()->json([
            'result' => 0,
            'message' => ['registration success']
        ]);
    }
    //一般會員登入
    public function postLogin(Request $request)
    {
        $member = $this->users->validateLogin($request->all());
        if ($member) {
            $credentials = [
                'username' => $member,
                'password' => $request->password
            ];
        } else {
            $credentials = [
                'username' => $request->account,
                'password' => $request->password
            ];
        }
        // Log::info($credentials);
        try {
            //驗證身分 並產生一組Token 給使用者
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['result'  => 1,
                                         'message' => ['invalid_credentials']], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['result'  => 1,
                                     'message' => ['could_not_create_token']], 500);
        }
        $userBlock = $this->users->checkUser($credentials);
        if ($userBlock) {
            return response()->json([
                'result'  => 1,
                'message' => ['user blocked']], 401);
        }
        $userGroup = $this->users->login($request->all(), $member);
        return response()->json([
            'result'  => 0,
            'registered' => $userGroup,
            'message' => ['token' => $token]
        ]);
    }

    //取得驗證碼
    public function getVerifyCode()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $data = $this->users->getVerifyCode($user);
        return response()->json([
                'email' => $user->email,
                'verify_code' => $data
            ]);
    }
    //取得user 點數
    public function getUserPoint()
    {

        $user = JWTAuth::parseToken()->authenticate();
        $data = $this->users->getUserPoint($user);
        return response()->json([
                'email' => $user->email,
                'message' => $data
            ]);
    }
    public function ldapUserAdd()
    {
        
        $user = JWTAuth::parseToken()->authenticate();
        $token = $this->users->ldapUserAdd($user);
        return response()->json([
                'email' => $user->email,
                'token' => $token
            ]);
    }
    public function ldapUserTokenAdd(Request $request)
    {   
        $user = JWTAuth::parseToken()->authenticate();
        $message = $this->users->ldapUserTokenAdd($user,$request->verify_code);
        return response()->json([
                'email' => $user->email,
                'message' => $message
            ]);
    }
    public function cleanBinding()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $message = $this->users->cleanBinding($user);
        return response()->json([
            'email' => $user->email,
            'message' => $message
        ]);
    }
    public function getStor(){
        $user = JWTAuth::parseToken()->authenticate();
        $stor = $this->users->getStor($user);
        return response()->json([
                'list' => $stor
            ]);
    }
    public function getStorRate(Request $request){
        $message = $this->users->getStorRate($request);
        return response()->json([
                'message' => $message
            ]);
    }
    public function ldapUserPointChange(Request $request)
    {
        $request->message = "平台點數交易";
        $user = JWTAuth::parseToken()->authenticate();
        $message = $this->users->ldapUserPointChange($user,$request);
        return response()->json([
                'message' => $message
            ]);
    }
    public function receiveLdapPointChange($username)
    {
        $message = $this->users->receiveLdapPointChange($username);
        return response()->json([
                'message' => $message
            ]);
    }

    public function getShopVoucher(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $voucher = $this->users->getShopVoucher($user,$request);
        return response()->json([
            'message' => $voucher
        ]);
    }
    public function getVoucherList()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $list = $this->users->getVoucherList($user);
        return response()->json([
            'message' => $list
        ]);
    }
    public function disableVoucherList(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $list = $this->users->disableVoucherList($user,$request);
        return response()->json([
            'message' => $list
        ]);
    }

    //忘記密碼發送驗證碼
    public function postSend(Request $request)
    {
        if (!isset($request->email) && !isset($request->phone_number)) {
            return response()->json([
                'result' => 1,
                'message' => ['The email or phone_number field is required']
            ]);
        }
        while(($authnum=rand()%10000)<1000);
        $userEmail = $this->users->send($request->all(), $authnum);
        if ($userEmail == 3) {
            return response()->json([
                'result' => 3,
                'message' => ['This phone number does not exist']
            ]);
        }
        if ($userEmail == 2) {
            return response()->json([
                'result' => 2,
                'message' => ['This email does not exist']
            ]);
        }
        $this->sendEmail($userEmail, $authnum);
        return response()->json([
            'result'   => 0,
            'message'  => ['Verify_code send successfully']
        ]);
    }
    private function sendEmail($params, $authnum)
    {
        $user = ['email' => $params, 'authnum' => $authnum];
        $this->dispatch(new SendReminderEmail($user));
    }
    //檢查驗證碼
    public function checkVerifyCode(CheckRequest $request)
    {
        if (!isset($request->email) && !isset($request->phone)) {
            return response()->json([
                    'result' => 1,
                    'message' => ['The email or phone_number field is required']
            ]);
        }
        $check = $this->users->check($request->all());
        switch ($check) {
            case 0:
                return response()->json([
                        'result' => 0,
                        'message' => ['Success Validation passed']
                ]);
                break;
            case 1:
                return response()->json([
                        'result' => 1,
                        'message' => ['The verify_code is incorrect']
                ]);
                break;
            case 2:
                return response()->json([
                        'result' => 1,
                        'message' => ['This email does not exist']
                ]);
                break;
            case 3:
                return response()->json([
                        'result' => 1,
                        'message' => ['This phone number does not exist']
                ]);
                break;
        }
    }
    //重設密碼
    public function rescuePassword(RescuePasswordRequest $request)
    {
        if (is_null(GobuyUser::where('email', $request->email)->first())) {
            return response()->json([
                'result'   => 1,
                'message'   => ['Email has not been registered']
            ]);
        }
        $resPassword = $this->users->resPassword($request->all());
        if ($resPassword) {
            return response()->json([
                'result'  => 0,
                'message' => ['Reset password successfully']
            ]);
        }
        else {
            return response()->json([
                'result' => 1,
                'message' => ['Verify_code is incorrect or Email is incorrect']
                ]);
        }
    }
    //註冊特約店會員
    public function specialRegister(SpecialRegisterRequest $request)
    {
        $register = $this->users->specialReg($request->all());
        switch ($register) {
            case 0:
                return response()->json([
                    'result' => 0,
                    'message' => ['registration success']
                ]);
                break;
            case 1:
                return response()->json([
                    'result' => 1,
                    'message' => ['The store address field is required']
                ]);
                break;
            case 2:
                return response()->json([
                    'result' => 1,
                    'message' => ['The store url field is required']
                ]);
                break;
            case 3:
                return response()->json([
                    'result' => 1,
                    'message' => ['Verify_code is incorrect']
                ]);
                break;
            case 4:
                return response()->json([
                    'result' => 1,
                    'message' => ['This account has been registered']
                ]);
                break;
            case 5:
                return response()->json([
                    'result' => 1,
                    'message' => ['The recommender does not exist']
                ]);
                break;
        }
    }
    //註冊特約店會員 產生驗證碼
    public function specialVerifyCode()
    {
        $job = (new DeleteImage())->delay(60 * 15);
        $this->dispatch($job);
        $builder = new CaptchaBuilder;
        $builder->build($width = 100, $height = 40, $font = null);
        $verify_code = $builder->getPhrase();
        $img = strval(time()).str_random(5).'.jpg';
        $builder->save($img);
        $array = $this->users->graphCode($verify_code, $img);
        return response()->json([
            'result' => 0,
            'message' => ['Verify_code generated successfully'],
            'id' => $array['id'],
            'verify_code_url' => $array['verify_code_url']
        ]);
    }
    //一般會員基本資料
    public function userDetail()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $userInfo = $this->users->detail();
        return response()->json([
            'result' => 0,
            'message' => ['User Information'],
            'user_account' => $user['username'],
            'user_birthday' => $userInfo[1],
            'user_country' => $userInfo[0],
            'user_email' => $user['email'],
            'user_phone' => $userInfo[2],
            'user_state' => $userInfo[4]
        ]);
    }
    //一般會員，針對問題提供建議與回饋
    public function feedBack(Request $request)
    {
        // $advices = $this->users->userAdvise();
        return response()->json([
            'result' => 0,
            'message' => ['Reply Success'],
            'url' => 'http://ginkerapp.com/feedback'
        ]);
    }
    //檢查手機版本
    public function checkVersion(Request $request)
    {
        if (!isset($request->version)) {
            return response()->json([
                'result' => 1,
                'message' => ['The version field is required.']
            ]);
        }
        $check = $this->users->version($request->all());
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
    //一般會員-修改基本資料
    public function modifyDetail(Request $request)
    {
        $user = $this->users->modify($request->all());
        switch ($user) {
            case 0:
                return response()->json([
                    'result' => 0,
                    'message' => ['This email successfully modified']
                ]);
                break;
            case 1:
                return response()->json([
                    'result' => 1,
                    'message' => ['This email is already registered']
                ]);
                break;
            case 2:
                return response()->json([
                    'result' => 2,
                    'message' => ['This email field is null']
                ]);
                break;
        }
    }
    //修改使用者密碼
    public function resetPassword(ResetPasswordRequest $request)
    {
        $userPsw = $this->users->resetPwd($request->all());
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
    //特約會員 瀏覽基本資料
    public function specialUserDetail()
    {
        $specialUser = $this->users->specialDetail();
        return response()->json([
            'result' => 0,
            'message' => ['User Information'],
            'user_account' => $specialUser['user_account'],
            'user_birthday' => $specialUser['user_birthday'],
            'user_country' => $specialUser['user_country'],
            'user_email' => $specialUser['user_email'],
            'store_address' => $specialUser['store_address'],
            'store_url' => $specialUser['store_url'],
            'store_contact' => $specialUser['store_contact'],
            'store_name' => $specialUser['store_name']
        ]);
    }
    //一般會員 申請加入優惠會員
    public function preRegister(SpecialRegisterRequest $request)
    {
        $register = $this->users->preReg($request->all());
        switch ($register) {
            case 0:
                return response()->json([
                    'result' => 0,
                    'message' => ['registration success']
                ]);
                break;
            case 1:
                return response()->json([
                    'result' => 1,
                    'message' => ['The store address field is required']
                ]);
                break;
            case 2:
                return response()->json([
                    'result' => 1,
                    'message' => ['The store url field is required']
                ]);
                break;
            case 3:
                return response()->json([
                    'result' => 1,
                    'message' => ['Verify_code is incorrect']
                ]);
                break;
            case 4:
                return response()->json([
                    'result' => 1,
                    'message' => ['This account has been registered']
                ]);
                break;
            case 5:
                return response()->json([
                    'result' => 1,
                    'message' => ['The recommender does not exist']
                ]);
                break;
        }
    }
    //Device Token註冊
    public function deviceRegister(DeviceRequest $request)
    {
        $this->users->getDevice($request->all());
        return response()->json([
            'result' => 0,
            'message' => ['registration device token success']
        ]);
    }
    //一般會員登出系統
    public function deviceLogout()
    {
        $logout = $this->users->deviceLogout();
        if ($logout == 0) {
            return response()->json([
                'result' => 1,
                'message' => ['logout fail no data']
            ]);
        }
        return response()->json([
            'result' => 0,
            'message' => ['logout success']
        ]);
    }
    //一般會員-收到推播通知繳費 假資料
    public function pay2goPayment()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $deviceTokens = $this->users->notifyPayment();
        foreach ($deviceTokens as $key => $deviceToken) {
            if ($deviceToken['user_id'] == $user['id']) {
                $array = [
                    'type' => 'payment',
                    'result' => 0,
                    'message' => ['智付寶Pay2go催繳通知'],
                    'id' => 8,
                    'title' => '請於2016/12/31內繳交訂單費用',
                    'url' => 'https://www.pay2go.com/'
                ];
            }
        }
        $this->dispatch(new PushAppNotification($array, $deviceTokens));
        return response()->json($array);
    }
    //一般會員 顯示自己的QR code
    public function getUserQRcode()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $url = "http://ginkerapp.com/gobuyreg.html?itemId=" . $user['id'];
        return response()->json([
            'result' => 0,
            'message' => ['success'],
            'url' => $url
        ]);
    }
    // ID 轉成電話號碼
    public function getPhone(Request $request)
    {
        if (!isset($request->id)) {
            return response()->json([
                'result' => 1,
                'message' => ['The id field is required.'],
            ]);
        }
        $phone = $this->users->idToPhone($request->id);
        if ($phone) {
            return response()->json([
                'result' => 0,
                'phone' => $phone,
                'message' => ['success']
            ]);
        } else {
            return response()->json([
                'result' => 1,
                'message' => ['No user']
            ]);
        }
    }
    //瀏覽推薦人列表
    public function getRecommendList()
    {
        $data = $this->users->recommendList();
        return response()->json([
            'result' => 0,
            'message' => ['success'],
            'general' => $data['general'],
            'special' => $data['special'],
            'premium' => $data['premium']
        ]);
    }
    //一般會員設定推薦人
    public function setUserRecommend(SetRecommendRequest $request)
    {
        $checkUser = $this->users->setUser($request->qrcode);
        if ($checkUser == 1) {
            return response()->json([
                'result' => 1,
                'message' => ['recommender is blocked']
            ]);
        }
        if ($checkUser == 2) {
            return response()->json([
                'result' => 1,
                'message' => ['duplicate user']
            ]);
        }
        $this->pushBeRecommend();
        $this->pushRecommend($request->qrcode);
        return response()->json([
            'result' => 0,
            'message' => ['success']
        ]);
    }
    //特約會員設定推薦人
    public function setSpeRecommend(SetRecommendRequest $request)
    {
        $speUser = $this->users->setSpe($request->qrcode);
        if ($speUser == 1) {
            return response()->json([
                'result' => 1,
                'message' => ['recommender is blocked']
            ]);
        }
        if ($speUser == 2) {
            return response()->json([
                'result' => 1,
                'message' => ['Store has not been reviewed or Duplicate user']
            ]);
        }
        $this->pushBeRecommend();
        $this->pushRecommend($request->qrcode);
        return response()->json([
            'result' => 0,
            'message' => ['success']
        ]);
    }
    //優惠會員設定推薦人
    public function setPreRecommend(SetRecommendRequest $request)
    {
        $preUser = $this->users->setPre($request->qrcode);
        if ($preUser == 1) {
            return response()->json([
                'result' => 1,
                'message' => ['recommender is blocked']
            ]);
        }
        if ($preUser == 2) {
            return response()->json([
                'result' => 1,
                'message' => ['Store has not been reviewed or Duplicate user']
            ]);
        }
        $this->pushBeRecommend();
        $this->pushRecommend($request->qrcode);
        return response()->json([
            'result' => 0,
            'message' => ['success']
        ]);
    }
    //收到註冊推薦人的點數通知(被推薦)
    public function pushBeRecommend()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $data = $this->users->beRecommend();
        $array = [
            'type' => 'berecommend',
            'result' => 0,
            'message' => ['be recommend point'],
            'id' => $user['id'],
            'data' => '受推薦註冊獲得點數 300',
            'point' => (int)300
        ];
        if (is_null($data[0]['device'])) {
            return response()->json([
                    'result' => 1,
                    'message' => ['no device_token']
            ]);
        }
        $this->dispatch(new SendRecommendPoint($array, $data));
        return response()->json($array);
    }
    //收到註冊推薦人的點數通知(推薦人)
    public function pushRecommend($user_id)
    {
        $data = $this->users->recommend($user_id);
        $array = [
            'type' => 'recommend',
            'result' => 0,
            'message' => ['recommend point'],
            'id' => $user_id,
            'data' => '推薦使用者註冊獲得點數 200',
            'point' => (int)200
        ];
        if (is_null($data[0]['device'])) {
            return response()->json([
                    'result' => 1,
                    'message' => ['no device_token']
            ]);
        }
        $this->dispatch(new SendRecommendPoint($array, $data));
        return response()->json($array);
    }
    //確認使用者身分
    public function checkUserIdentity()
    {
        $data = $this->users->userIdentity();
        return response()->json([
            'result' => 0,
            'message' => ['success'],
            'special' => $data['special'],
            'preferential' => $data['preferential']
        ]);
    }
    //驗證會員是否為公倍會員 身分證驗證
    public function validateIdCard(Request $request)
    {
        if (!isset($request->id_number)) {
            return response()->json([
                'result'  => 1,
                'message' => ['The type id number field is required .']
            ]);
        }
        $data = $this->users->userIdCard($request->id_number);
        if ($data == 1) {
            return response()->json([
                'result' => 1,
                'message' => ['verification failed']
            ]);
        } else {
            return response()->json([
                'result' => 0,
                'message' => ['Validation was successful']
            ]);
        }
    }
    //Emoji example
    public function test(Request $request)
    {
        // $text='\u4e2d\u570b';
        // // $text = '中國';
        // dd (Emoji::Decode($text));
        $name = $request->name;
    }
}


