package com.androidlibrary.module.backend;


import com.androidlibrary.module.ApiParams;

/**
 * Created by ameng on 2016/5/22.
 */
public class ApiUrls {
    public static String apiV1ValidateIdentityCard(ApiParams params) {
        return getDomain(params) + "api/v1/validate/identity/card";
    }

    public static String apiV1ShopPay(ApiParams params) {
        return getDomain(params) + "api/v1/shop/pay";
    }

    public static String apiV1PremiumUserDetail(ApiParams params) {
        return getDomain(params) + "api/v1/preferential/user/detail";
    }

    public static String apiV1CheckUserIdentity(ApiParams params) {
        return getDomain(params) + "api/v1/check/user/identity";
    }

    public static String apiV1UserInstruction(ApiParams params) {
        return getDomain(params) + "api/v1/user/instruction";
    }

    public static String apiV1UserCustomerService(ApiParams params) {
        return getDomain(params) + "api/v1/user/customer/service";
    }

    public static String apiV1SpecialNewsId(ApiParams params) {
        return getDomain(params) + "api/v1/general/newsSpe/" + params.inputId;
    }

    public static String apiV1PremiumNewsId(ApiParams params) {
        return getDomain(params) + "api/v1/general/newsPre/" + params.inputId;
    }

    public static String apiV1PremiumNews(ApiParams params) {
        return getDomain(params) + "api/v1/general/newsPre";
    }

    public static String apiV1SpecialNews(ApiParams params) {
        return getDomain(params) + "api/v1/general/newsSpe";
    }

    public static String apiV1PremiumRecommendSet(ApiParams params) {
        return getDomain(params) + "api/v1/premium/recommend/set";
    }

    public static String apiV1SpecialRecommendSet(ApiParams params) {
        return getDomain(params) + "api/v1/special/recommend/set";
    }

    public static String apiV1GeneralRecommendSet(ApiParams params) {
        return getDomain(params) + "api/v1/general/recommend/set";
    }

    public static String apiV1RecommendShow(ApiParams params) {
        return getDomain(params) + "api/v1/recommend/show";
    }

    public static String apiV1IdPhone(ApiParams params) {
        return getDomain(params) + "api/v1/id/phone";
    }

    public static String apiV1QrCodeShow(ApiParams params) {
        return getDomain(params) + "api/v1/qrcode/show";
    }

    public static String apiV1PremiumQrCodeShowGet(ApiParams params) {
        return getDomain(params) + "api/v1/preferential/qrcode/show";
    }

    public static String apiV1SpecialQrCodeShowGet(ApiParams params) {
        return getDomain(params) + "api/v1/special/qrcode/show";
    }

    public static String apiV1StoreRegion(ApiParams params) {
        return getDomain(params) + "api/v1/store/region";
    }

    public static String apiV1PreferentialActivity(ApiParams params) {
        return getDomain(params) + "api/v1/preferential/activity";
    }

    public static String apiV1SpecialActivity(ApiParams params) {
        return getDomain(params) + "api/v1/special/activity";
    }

    public static String apiV1UserPreferentialCsvResetPassword(ApiParams params) {
        return getDomain(params) + "api/v1/user/preferential/csv/reset/password";
    }

    public static String apiV1PreferentialPhonePointSend(ApiParams params) {
        return getDomain(params) + "api/v1/preferential/point/phone/send";
    }

    public static String apiV1PreferentialPoint(ApiParams params) {
        return getDomain(params) + "api/v1/preferential/point";
    }

    public static String apiV1PreferentialUserDetail(ApiParams params) {
        return getDomain(params) + "api/v1/preferential/user/detail";
    }

    public static String apiV1SpecialAdvertisePublish(ApiParams params) {
        return getDomain(params) + "api/v1/special/advertise/publish";
    }

    public static String apiV1NormalPointReceive(ApiParams params) {
        return getDomain(params) + "api/v1/general/point/receive";
    }

    public static String apiV1GeneralStoreSave(ApiParams params) {
        return getDomain(params) + "api/v1/store/save";
    }

    public static String apiV1SpecialCheckVersion(ApiParams params) {
        return getDomain(params) + "api/v1/special/check/version";
    }

    public static String apiV1PreferentialCheckVersion(ApiParams params) {
        return getDomain(params) + "api/v1/preferential/check/version";
    }

    public static String apiV1UserCheckVersion(ApiParams params) {
        return getDomain(params) + "api/v1/user/check/version";
    }

    public static String apiV1SpecialUserDetail(ApiParams params) {
        return getDomain(params) + "api/v1/special/user/detail";
    }

    public static String apiV1SpecialCsvDownload(ApiParams params) {
        return getDomain(params) + "api/v1/special/csv/download";
    }

    public static String apiV1UserLuckySend(ApiParams params) {
        return getDomain(params) + "api/v1/lucky/send";
    }

    public static String apiV1PreferentialCsvDownloadPost(ApiParams params) {
        return getDomain(params) + "api/v1/preferential/csv/download";
    }

    public static String apiV1AdveriseShow(ApiParams params) {
        return getDomain(params) + "api/v1/advertise/show";
    }

    public static String apiV1GeneralRecommendSetPost(ApiParams params) {
        return getDomain(params) + "api/v1/general/recommend/set";
    }

    public static String apiV1PremiumRecommendSetPost(ApiParams params) {
        return getDomain(params) + "api/v1/premium/recommend/set";
    }

    public static String apiV1SpecialRecommendSetPost(ApiParams params) {
        return getDomain(params) + "api/v1/special/recommend/set";
    }

    public static String apiV1GeneralShopLikeCancel(ApiParams params) {
        return getDomain(params) + "api/v1/general/shop/like/cancel";
    }

    public static String apiV1GeneralShopLike(ApiParams params) {
        return getDomain(params) + "api/v1/general/shop/like";
    }

    public static String apiV1SpecialPointRecord(ApiParams params) {
        return getDomain(params) + "api/v1/special/point/send/record";
    }

    public static String apiV1PreferentialPointRecordPost(ApiParams params) {
        return getDomain(params) + "api/v1/preferential/point/send/record";
    }

    public static String apiV1SpecialPhonePointCheck(ApiParams params) {
        return getDomain(params) + "api/v1/special/point/check";
    }

    public static String apiV1SpecialPhonePointSend(ApiParams params) {
        return getDomain(params) + "api/v1/special/point/phone/send";
    }

    public static String apiV1Point(ApiParams params) {
        return getDomain(params) + "api/v1/general/bonus_point";
    }

    public static String apiV1GeneralNews(ApiParams params) {
        return getDomain(params) + "api/v1/general/news";
    }

    public static String apiV1LuckyMoney(ApiParams params) {
        return getDomain(params) + "api/v1/lucky/money";
    }

    public static String apiV1PushUnregister(ApiParams params) {
        return getDomain(params) + "api/v1/push/unregister";
    }

    public static String apiV1Login(ApiParams params) {
        return getDomain(params) + "api/v1/login";
    }

    public static String apiV1register(ApiParams params) {
        return getDomain(params) + "api/v1/register";
    }

    public static String apiV1UserRescuePassword(ApiParams params) {
        return getDomain(params) + "api/v1/user/rescue/password";
    }

    public static String apiV1UserResetPassword(ApiParams params) {
        return getDomain(params) + "api/v1/user/reset/password";
    }

    public static String apiV1PreferentialRegister(ApiParams params) {
        return getDomain(params) + "api/v1/user/preferential/register";
    }

    public static String apiV1SpecialRegister(ApiParams params) {
        return getDomain(params) + "api/v1/user/special/register";
    }

    public static String apiV1UserSpecialCsvResetPassword(ApiParams params) {
        return getDomain(params) + "api/v1/user/special/csv/reset/password";
    }

    public static String apiV1UserDetail(ApiParams params) {
        return getDomain(params) + "api/v1/user/detail";
    }

    public static String apiV1GeneralNewsId(ApiParams params) {
        return getDomain(params) + "api/v1/general/news/" + params.inputId;
    }

    public static String apiV1SendPoint(ApiParams params) {
        return getDomain(params) + "api/v1/general/point/send";
    }

    public static String apiV1BoundsSend(ApiParams params) {
        return getDomain(params) + "api/v1/bounds/send";
    }

    public static String apiV1SpecialVerifyCode(ApiParams params) {
        return getDomain(params) + "api/v1/user/special/verify_code";
    }

    public static String apiV1PushRegister(ApiParams params) {
        return getDomain(params) + "api/v1/push/register";
    }

    public static String apiV1FeedbBack(ApiParams params) {
        return getDomain(params) + "api/v1/feedback";
    }

    public static String apiV1Store(ApiParams params) {
        return getDomain(params) + "api/v1/store";
    }

    public static String apiV1SpecialPointSend(ApiParams params) {
        return getDomain(params) + "api/v1/special/point/send";
    }

    public static String apiV1SpecialCsvCheckPost(ApiParams params) {
        return getDomain(params) + "api/v1/user/special/csv/check";
    }

    public static String apiV1PremiumCsvCheckPost(ApiParams params) {
        return getDomain(params) + "api/v1/user/preferential/csv/check";
    }

    public static String apiV1PreferentialPointDeduct(ApiParams params) {
        return getDomain(params) + "api/v1/preferential/point/deduct";
    }

    public static String apiV1SpecialPoint(ApiParams params) {
        return getDomain(params) + "api/v1/special/point";
    }

    public static String apiV1UserPasswordForgotVerifyCodeSendPost(ApiParams params) {
        return getDomain(params) + "api/v1/user/password/forgot/verify_code/send";
    }

    public static String apiV1UserPasswordForgotVerifyCode(ApiParams params) {
        return getDomain(params) + "api/v1/user/password/forgot/verify_code";
    }

    public static String apiV1UserLdapadd(ApiParams params) {
        return getDomain(params) + "api/v1/user/ldapadd";
    }

    public static String apiV1UserPoint(ApiParams params) {
        return getDomain(params) + "api/v1/user/point";
    }

    public static String apiV1StoreList(ApiParams params) {
        return getDomain(params) + "api/v1/token/stor";
    }

    public static String apiV1ConnectLdap(ApiParams params) {
        return getDomain(params) + "api/v1/user/ldapadd/token";
    }

    public static String apiV1ChangePoint(ApiParams params) {
        return getDomain(params) + "api/v1/user/point/change";
    }

    public static String apiV1UserStoreRate(ApiParams params) {
        return getDomain(params) + "api/v1/stor/rate";
    }

    public static String apiV1Token(ApiParams params) {
        return getDomain(params) + "api/v1/get/getverifycode";
    }

    public static String apiV1BuyVoucher(ApiParams params) {
        return getDomain(params) + "api/v1/buy/voucher";
    }

    public static String apiVoucherList(ApiParams params) {
        return getDomain(params) + "api/v1/voucher/list";
    }

    public static String apiUseVoucher(ApiParams params) {
        return getDomain(params) + "api/v1/use/voucher";
    }

    public static String getDomain(ApiParams params) {
        String protocol = (params.isSSL) ? "https" : "http";
        String domain = protocol + "://" + params.domainHost + ":" + params.domainPort + "/";
        return domain;
    }
}
