package com.herbhousesgobuyother.service;

import android.os.Bundle;
import android.util.Log;

import com.androidlibrary.module.backend.data.ApiV1BerecommendGetData;
import com.androidlibrary.module.backend.data.ApiV1GcmLuckyGetData;
import com.androidlibrary.module.backend.data.ApiV1GeneralPointReceivePostData;
import com.androidlibrary.module.backend.data.ApiV1ReceivePaymentGetData;
import com.androidlibrary.module.backend.data.ApiV1RecommendGetData;
import com.androidlibrary.module.backend.data.ApiV1StoreGcmSendPostData;
import com.androidlibrary.module.backend.data.ApiV1StorePushStoreSendGetData;
import com.androidlibrary.module.sql.GCMTable;
import com.google.android.gms.gcm.GcmListenerService;
import com.herbhousesgobuyother.component.notification.StorePromotionsNotification;

/**
 * Created by ameng on 2016/6/29.
 */
public class GCMListenerService extends GcmListenerService {

    private static final String serviceName = GCMListenerService.class.getName();
    private StorePromotionsNotification storePromotions;
    private ApiV1StoreGcmSendPostData apiV1StoreGcmSendData;
    private ApiV1GcmLuckyGetData apiV1GcmLuckyGetData;
    private ApiV1ReceivePaymentGetData apiV1ReceivePaymentGetData;
    private GCMTable gcmTable;
    private ApiV1GeneralPointReceivePostData apiV1GeneralPointReceivePostData;
    private ApiV1StorePushStoreSendGetData apiV1StorePushStoreSendGetData;
    private ApiV1BerecommendGetData apiV1BerecommendGetData;
    private ApiV1RecommendGetData apiV1RecommendGetData;

    @Override
    public void onCreate() {
        super.onCreate();
        storePromotions = new StorePromotionsNotification(this);
        gcmTable = new GCMTable(this);
    }

    @Override
    public void onMessageReceived(String from, Bundle data) {
        String message = data.getString("message");
        Log.e("GCM From", "From: " + from);
        Log.e("GCM data", "data: " + data.toString());
        Log.e("GCM Message", "Message: " + message);
        apiV1StoreGcmSendData = new ApiV1StoreGcmSendPostData(message, gcmTable);
        apiV1GcmLuckyGetData = new ApiV1GcmLuckyGetData(message, gcmTable);
        apiV1ReceivePaymentGetData = new ApiV1ReceivePaymentGetData(message, gcmTable);
        apiV1GeneralPointReceivePostData = new ApiV1GeneralPointReceivePostData(message);
        apiV1StorePushStoreSendGetData = new ApiV1StorePushStoreSendGetData(message);
        apiV1BerecommendGetData = new ApiV1BerecommendGetData(message);
        apiV1RecommendGetData = new ApiV1RecommendGetData(message);
        if (apiV1StoreGcmSendData.type.equals("promotions")) {
            storePromotions.setBuilder(apiV1StoreGcmSendData);
        } else if (apiV1StoreGcmSendData.type.equals("payment")) {
            storePromotions.setBuilder(apiV1ReceivePaymentGetData);
        } else if (apiV1StoreGcmSendData.type.equals("scratch")) {
            storePromotions.setBuilder(apiV1GcmLuckyGetData);
        } else if (apiV1StoreGcmSendData.type.equals("transferPoint")) {
            storePromotions.setBuilder(apiV1GeneralPointReceivePostData);
        } else if (apiV1StoreGcmSendData.type.equals("storesend")) {
            storePromotions.setBuilder(apiV1StorePushStoreSendGetData);
        } else if (apiV1StoreGcmSendData.type.equals("berecommend")) {
            storePromotions.setBuilder(apiV1BerecommendGetData);
        } else if (apiV1StoreGcmSendData.type.equals("recommend")) {
            storePromotions.setBuilder(apiV1RecommendGetData);
        }else if (apiV1StoreGcmSendData.type.equals("shareScratch")) {
            storePromotions.setBuilder(apiV1GcmLuckyGetData);
        }
    }
}
