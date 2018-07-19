package com.herbhousesgobuyother.contrube.controllor.favorite;

import android.content.Context;
import android.widget.Toast;

import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.androidlibrary.module.ApiParams;
import com.androidlibrary.module.backend.api.ApiV1GeneralShopLikeCancelPost;
import com.androidlibrary.module.backend.api.ApiV1GeneralStoreSaveGet;
import com.androidlibrary.module.backend.data.ApiV1GeneralShopLikeCancelPostData;
import com.androidlibrary.module.backend.data.ApiV1GeneralStoreSaveGetData;
import com.androidlibrary.module.backend.data.ErrorProcessingData;
import com.androidlibrary.module.backend.params.AccountInjection;
import com.androidlibrary.module.backend.params.ServerInfoInjection;
import com.androidlibrary.module.backend.request.WebRequest;
import com.herbhousesgobuyother.R;
import com.herbhousesgobuyother.contrube.component.dialog.LoadingDialog;
import com.herbhousesgobuyother.contrube.view.normal.ActivityNormalAdvertisement;

/**
 * Created by Gary on 2016/11/9.
 */

public class FavoriteController {
    private final String TAG = FavoriteController.class.getName();
    private Context context;
    private AccountInjection accountInjection;
    private ServerInfoInjection serverInfoInjection;
    private ApiParams apiParams;
    private LoadingDialog loadingDialog;
    private FavoriteController.CallBackEvent mCallBackEvent;

    public FavoriteController(Context context) {
        this.context = context;
        accountInjection = new AccountInjection(context);
        serverInfoInjection = new ServerInfoInjection();
        loadingDialog = new LoadingDialog(context);
        apiParams = new ApiParams(serverInfoInjection, accountInjection);
    }

    public void deleteRequest(String shopId) {
        apiParams.inputStoreId = shopId;

        WebRequest<ApiV1GeneralShopLikeCancelPostData> request = new ApiV1GeneralShopLikeCancelPost<>(context, apiParams);
        request.processing(new WebRequest.Processing<ApiV1GeneralShopLikeCancelPostData>() {
            @Override
            public ApiV1GeneralShopLikeCancelPostData run(String data) {
                return new ApiV1GeneralShopLikeCancelPostData(data);
            }
        }).failProcess(new WebRequest.FailProcess<ApiV1GeneralShopLikeCancelPostData>() {
            @Override
            public void run(String data, ApiV1GeneralShopLikeCancelPostData information) {
                ErrorProcessingData.run(context, data, information);
            }
        }).unknownFailRequest(new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                String content = context.getString(R.string.request_load_fail);
                Toast.makeText(context, content, Toast.LENGTH_LONG).show();
            }
        }).successProcess(new WebRequest.SuccessProcess<ApiV1GeneralShopLikeCancelPostData>() {
            @Override
            public void run(String data, ApiV1GeneralShopLikeCancelPostData information) {
                if (null!=mCallBackEvent)
                    mCallBackEvent.onSuccess(information);
            }
        }).start();
    }

    public void syncRequest(final Boolean isFromeDelete) {
        apiParams.inputLatitude = ActivityNormalAdvertisement.lat;
        apiParams.inputLongitude = ActivityNormalAdvertisement.lng;

        if (!isFromeDelete)
            loadingDialog.show();
        WebRequest<ApiV1GeneralStoreSaveGetData> request = new ApiV1GeneralStoreSaveGet<>(context, apiParams);
        request.processing(new WebRequest.Processing<ApiV1GeneralStoreSaveGetData>() {
            @Override
            public ApiV1GeneralStoreSaveGetData run(String data) {
                return new ApiV1GeneralStoreSaveGetData(context, data);
            }
        }).failProcess(new WebRequest.FailProcess<ApiV1GeneralStoreSaveGetData>() {
            @Override
            public void run(String data, ApiV1GeneralStoreSaveGetData information) {
                if (!isFromeDelete)
                    loadingDialog.dismiss();
                ErrorProcessingData.run(context, data, information);
            }
        }).unknownFailRequest(new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                if (!isFromeDelete)
                    loadingDialog.dismiss();
                String content = context.getString(R.string.request_load_fail);
                Toast.makeText(context, content, Toast.LENGTH_LONG).show();
            }
        }).successProcess(new WebRequest.SuccessProcess<ApiV1GeneralStoreSaveGetData>() {
            @Override
            public void run(String data, ApiV1GeneralStoreSaveGetData information) {
                if (!isFromeDelete) {
                    loadingDialog.dismiss();
                    if (null!=mCallBackEvent)
                    mCallBackEvent.onSuccess(information);
                }
            }
        }).start();
    }


    public void setmCallBackEvent(FavoriteController.CallBackEvent callBackEvent) {
        this.mCallBackEvent = callBackEvent;
    }


    public interface CallBackEvent {
        void onError();

        void onSuccess(ApiV1GeneralStoreSaveGetData information);
        void onSuccess(ApiV1GeneralShopLikeCancelPostData information);

    }
}
