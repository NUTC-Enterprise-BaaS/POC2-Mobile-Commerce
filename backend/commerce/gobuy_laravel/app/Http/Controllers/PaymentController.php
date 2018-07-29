<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Entities\GobuyJbusinessdirectoryCompany;
use App\Entities\GobuySocialPointsHistory;
use App\Entities\GobuySpePointsHistory;
use AllInOne;
use PaymentMethod;
use JWTAuth;
require_once 'AllPay.Payment.Integration.php';

class PaymentController extends Controller
{
    public function testShopAllPay()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $shopUser = GobuyJbusinessdirectoryCompany::where('userId', $user->id)
                        ->where('shop_class', 1)
                        ->first();
        $shopSendPoints = GobuySpePointsHistory::where('user_id', $user->id)
                            ->where('state', 1)
                            ->get();
        $pointNum = 0;
        foreach ($shopSendPoints as $key => $shopSendPoint) {
            $pointNum += $shopSendPoint['points'];
        }
        $pointNum = abs($pointNum) * 0.1;
        if ($pointNum < 1) {
            return response()->json([
                'result' => 1,
                'message' => ['no datas']
            ], 404);
        }
        $this->testAllPay($pointNum, $shopUser);
    }
    public function testAllPay($point, $shopUser)
    {
        try
        {
            header("Content-Type:text/html; charset=utf-8");
            $oPayment = new AllInOne();
            /* 服務參數 */
            $oPayment->ServiceURL ="https://payment.allpay.com.tw/Cashier/AioCheckOut/V2";
            $oPayment->HashKey = "OnIXxKKjRkCrWxRD";
            $oPayment->HashIV = "LRQJSKKHZSqRskWy";
            $oPayment->MerchantID = "1495396";

            $time = time();
            /* 基本參數 */
            $oPayment->Send['ReturnURL'] = "http://pay.ginkerapp.com/api/v1/all/return";
            $oPayment->Send['MerchantTradeNo'] = $time;
            $oPayment->Send['MerchantTradeDate'] = date("Y/m/d H:i:s");
            $oPayment->Send['TotalAmount'] = $point;
            $oPayment->Send['TradeDesc'] =  $shopUser->name . ' 商店付款';
            $oPayment->Send['ChoosePayment'] = PaymentMethod::ALL;
            $oPayment->Send['IgnorePayment'] ="Alipay";
            $oPayment->SendExtend['PaymentInfoURL']= "http://pay.ginkerapp.com/api/v1/all/return";
            array_push($oPayment->Send['Items'], [
                'Name' => $shopUser->name . ' 商店付款',
                'Price' => (int)$point,
                'Currency' => "TWD",
                'Quantity' => (int) "1",
                'URL' => "http://ginkerapp.com/"
            ]);
            /* 產生訂單 */
            $oPayment->CheckOut();
            /* 產生產生訂單 Html Code 的方法 */
            $szHtml = $oPayment->CheckOutString();
        }
        catch (Exception $e)
        { // 例外錯誤處理。
            throw $e;
        }
    }
    public function testAllPayReturn()
    {
        header("Content-Type:text/html; charset=utf-8");
        try
        {
            $oPayment = new AllInOne();
            /* 服務參數 */
            $oPayment->HashKey = "OnIXxKKjRkCrWxRD";
            $oPayment->HashIV = "LRQJSKKHZSqRskWy";
            $oPayment->MerchantID = "1495396";
            /* 取得回傳參數 */
            $arFeedback = $oPayment->CheckOutFeedback();
            /* 檢核與變更訂單狀態 */
            if (sizeof($arFeedback) > 0) {
                foreach ($arFeedback as $key => $value) {
                    switch ($key)
                    {
                        /* 支付後的回傳的基本參數 */
                        case "MerchantID": $szMerchantID = $value; break;
                        case "MerchantTradeNo": $szMerchantTradeNo = $value; break;
                        case "PaymentDate": $szPaymentDate = $value; break;
                        case "PaymentType": $szPaymentType = $value; break;
                        case "PaymentTypeChargeFee": $szPaymentTypeChargeFee = $value; break;
                        case "RtnCode": $szRtnCode = $value; break;
                        case "RtnMsg": $szRtnMsg = $value; break;
                        case "SimulatePaid": $szSimulatePaid = $value; break;
                        case "TradeAmt": $szTradeAmt = $value; break;
                        case "TradeDate": $szTradeDate = $value; break;
                        case "TradeNo": $szTradeNo = $value; break;
                        default: break;
                    }
                }
                // 其他資料處理。
                GobuySpePointsHistory::where('id', 2)->update(['state' => 2]);
                print '1|OK';
            } else {
                print '0|Fail';
            }
        }
        catch (Exception $e){
            // 例外錯誤處理。
            print '0|' . $e->getMessage();
        }
    }
    public function testAllPayInfo()
    {
        try
        {
            $oPayment = new AllInOne();
            /* 服務參數 */
            $oPayment->HashKey = "OnIXxKKjRkCrWxRD";//這是測試帳號專用的不用改它
            $oPayment->HashIV = "LRQJSKKHZSqRskWy";
            $oPayment->MerchantID = "1495396";
            /* 取得回傳參數 */
            $arFeedback = $oPayment->CheckOutFeedback();
            /* 檢核與變更訂單狀態 */
            if (sizeof($arFeedback) > 0) {
                foreach ($arFeedback as $key => $value) {
                    switch ($key)
                    {
                        /* 支付後的回傳的基本參數 */
                        case "MerchantID": $szMerchantID = $value; break;
                        case "MerchantTradeNo": $szMerchantTradeNo = $value; break;
                        case "PaymentDate": $szPaymentDate = $value; break;
                        case "PaymentType": $szPaymentType = $value; break;
                        case "PaymentTypeChargeFee": $szPaymentTypeChargeFee = $value; break;
                        case "RtnCode": $szRtnCode = $value; break;
                        case "RtnMsg": $szRtnMsg = $value; break;
                        case "SimulatePaid": $szSimulatePaid = $value; break;
                        case "TradeAmt": $szTradeAmt = $value; break;
                        case "TradeDate": $szTradeDate = $value; break;
                        case "TradeNo": $szTradeNo = $value; break;
                        case "PaymentNo": $szPaymentNo = $value; break;//超商代碼
                        case "vAccount": $szVirtualAccount = $value; break;//ATM 虛擬碼
                        default: break;
                    }
                }
                // 其他資料處理。
                if(substr($szPaymentType, 0, 3)=='CVS') {//若付款方式為 超商代碼
                    //在這裡把超商代碼存進你的訂單資料表中
                } else if(substr($szPaymentType, 0, 3)=='ATM'){//若付款方式為ATM 虛擬碼
                    //在這裡把ATM虛擬碼存進你的訂單資料表中
                } else{
                    //寫入付款方式
                }
                print '1|OK';
            } else {
                print '0|Fail';
            }
        }
        catch (Exception $e){
            // 例外錯誤處理。
            print '0|' . $e->getMessage();
        }
    }
}
