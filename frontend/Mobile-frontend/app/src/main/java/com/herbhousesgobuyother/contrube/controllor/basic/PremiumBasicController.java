package com.herbhousesgobuyother.contrube.controllor.basic;

import android.content.Context;

import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.androidlibrary.module.ApiParams;
import com.androidlibrary.module.backend.api.ApiV1PremiumUserDetailGet;
import com.androidlibrary.module.backend.api.ApiV1RecommendShowGet;
import com.androidlibrary.module.backend.data.ApiV1PremiumUserDetailGetData;
import com.androidlibrary.module.backend.data.ApiV1RecommendShowGetData;
import com.androidlibrary.module.backend.data.ErrorProcessingData;
import com.androidlibrary.module.backend.params.AccountInjection;
import com.androidlibrary.module.backend.params.ServerInfoInjection;
import com.androidlibrary.module.backend.request.WebRequest;
import com.androidlibrary.ui.basicinformation.api.ApiV1UserDetailPost;
import com.androidlibrary.ui.basicinformation.data.ApiV1UserDetailPostData;
import com.herbhousesgobuyother.contrube.component.dialog.LoadingDialog;

/**
 * Created by Gary on 2016/11/9.
 */

public class PremiumBasicController {
    private final String TAG = PremiumBasicController.class.getName();
    private Context context;
    private AccountInjection accountInjection;
    private ServerInfoInjection serverInfoInjection;
    private ApiParams apiParams;
    private LoadingDialog loadingDialog;
    private PremiumBasicController.CallBackEvent mCallBackEvent;

    public PremiumBasicController(Context context) {
        this.context = context;
        accountInjection = new AccountInjection(context);
        serverInfoInjection = new ServerInfoInjection();
        loadingDialog = new LoadingDialog(context);
        apiParams = new ApiParams(serverInfoInjection, accountInjection);
    }

    public void modifyRequest(String email) {
        apiParams.inputEmail = email;
        loadingDialog.show();
        WebRequest<ApiV1UserDetailPostData> request = new ApiV1UserDetailPost<>(context, apiParams);
        request.processing(new WebRequest.Processing<ApiV1UserDetailPostData>() {
            @Override
            public ApiV1UserDetailPostData run(String data) {
                return new ApiV1UserDetailPostData(data);
            }
        }).failProcess(new WebRequest.FailProcess<ApiV1UserDetailPostData>() {
            @Override
            public void run(String data, ApiV1UserDetailPostData information) {
                loadingDialog.dismiss();
                ErrorProcessingData.run(context, data, information);
            }
        }).unknownFailRequest(new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                loadingDialog.dismiss();
            }
        }).successProcess(new WebRequest.SuccessProcess<ApiV1UserDetailPostData>() {
            @Override
            public void run(String data, ApiV1UserDetailPostData information) {
                loadingDialog.dismiss();
                if (null != mCallBackEvent) {
                    mCallBackEvent.onSuccess(information);
                }
            }
        }).start();
    }

    public void syncRecommendRequest() {
        WebRequest<ApiV1RecommendShowGetData> request = new ApiV1RecommendShowGet<>(context, apiParams);
        request.processing(new WebRequest.Processing<ApiV1RecommendShowGetData>() {
            @Override
            public ApiV1RecommendShowGetData run(String data) {
                return new ApiV1RecommendShowGetData(data);
            }
        }).failProcess(new WebRequest.FailProcess<ApiV1RecommendShowGetData>() {
            @Override
            public void run(String data, ApiV1RecommendShowGetData information) {
                loadingDialog.dismiss();
                ErrorProcessingData.run(context, data, information);
            }
        }).unknownFailRequest(new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                loadingDialog.dismiss();
            }
        }).successProcess(new WebRequest.SuccessProcess<ApiV1RecommendShowGetData>() {
            @Override
            public void run(String data, ApiV1RecommendShowGetData information) {
                if (null != mCallBackEvent) {
                    mCallBackEvent.onSuccess(information);
                }
            }
        }).start();
    }


    public void syncRequest() {
        loadingDialog.show();
        WebRequest<ApiV1PremiumUserDetailGetData> request = new ApiV1PremiumUserDetailGet<>(context, apiParams);
        request.processing(processingData)
                .failProcess(failProcessingData)
                .unknownFailRequest(failUnknownReason)
                .successProcess(successResponse)
                .start();
    }

    private WebRequest.Processing<ApiV1PremiumUserDetailGetData> processingData = new WebRequest.Processing<ApiV1PremiumUserDetailGetData>() {
        @Override
        public ApiV1PremiumUserDetailGetData run(String data) {
            return new ApiV1PremiumUserDetailGetData(data);
        }
    };

    private WebRequest.FailProcess<ApiV1PremiumUserDetailGetData> failProcessingData = new WebRequest.FailProcess<ApiV1PremiumUserDetailGetData>() {
        @Override
        public void run(String data, ApiV1PremiumUserDetailGetData information) {
            loadingDialog.dismiss();
            ErrorProcessingData.run(context, data, information);
        }
    };

    private Response.ErrorListener failUnknownReason = new Response.ErrorListener() {
        @Override
        public void onErrorResponse(VolleyError error) {
            loadingDialog.dismiss();
        }
    };

    private WebRequest.SuccessProcess<ApiV1PremiumUserDetailGetData> successResponse = new WebRequest.SuccessProcess<ApiV1PremiumUserDetailGetData>() {
        @Override
        public void run(String data, ApiV1PremiumUserDetailGetData information) {
            loadingDialog.dismiss();
            if (null != mCallBackEvent) {
                mCallBackEvent.onSuccess(information);
            }
        }
    };

    public void setmCallBackEvent(PremiumBasicController.CallBackEvent callBackEvent) {
        this.mCallBackEvent = callBackEvent;
    }


    public interface CallBackEvent {
        void onError();

        void onSuccess(ApiV1PremiumUserDetailGetData information);

        void onSuccess(ApiV1RecommendShowGetData information);

        void onSuccess(ApiV1UserDetailPostData information);
    }
}
