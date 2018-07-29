package com.poc2.contrube.controllor.advertisement;

import android.content.Context;
import android.widget.Toast;

import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.androidlibrary.module.ApiParams;
import com.androidlibrary.module.backend.api.ApiV1AdveriseShowGet;
import com.androidlibrary.module.backend.api.ApiV1CheckUserIdentityGet;
import com.androidlibrary.module.backend.api.ApiV1PublishPost;
import com.androidlibrary.module.backend.api.ApiV1PushUnregisterGet;
import com.androidlibrary.module.backend.api.ApiV1UserCustomerServiceGet;
import com.androidlibrary.module.backend.api.ApiV1UserInstructionGet;
import com.androidlibrary.module.backend.data.ApiV1AdveriseShowGetData;
import com.androidlibrary.module.backend.data.ApiV1CheckUserIdentityGetData;
import com.androidlibrary.module.backend.data.ApiV1PublishPostData;
import com.androidlibrary.module.backend.data.ApiV1PushUnregisterData;
import com.androidlibrary.module.backend.data.ApiV1UserCustomerServiceGetData;
import com.androidlibrary.module.backend.data.ApiV1UserInstructionGetData;
import com.androidlibrary.module.backend.data.ErrorProcessingData;
import com.androidlibrary.module.backend.params.AccountInjection;
import com.androidlibrary.module.backend.params.ServerInfoInjection;
import com.androidlibrary.module.backend.request.WebRequest;
import com.poc2.R;
import com.poc2.contrube.component.dialog.LoadingDialog;
import com.poc2.contrube.component.dialog.LoginErrorDialog;

/**
 * Created by Gary on 2016/11/10.
 */

public class PremiumAdvertisementController {
    private final String TAG = PremiumAdvertisementController.class.getName();
    private Context context;
    private AccountInjection accountInjection;
    private ServerInfoInjection serverInfoInjection;
    private ApiParams apiParams;
    private LoadingDialog loadingDialog;
    private LoginErrorDialog loginErrorDialog;
    private int reTryCount;
    private int reTryMax;
    private PremiumAdvertisementController.CallBackEvent mCallBackEvent;

    public PremiumAdvertisementController(Context context) {
        this.context = context;
        accountInjection = new AccountInjection(context);
        serverInfoInjection = new ServerInfoInjection();
        loadingDialog = new LoadingDialog(context);
        apiParams = new ApiParams(serverInfoInjection, accountInjection);
        reTryCount = 0;
        reTryMax = 3;
    }

    public void checkStateRequest() {
        loadingDialog.show();
        ApiParams apiParams = new ApiParams(serverInfoInjection, accountInjection);
        WebRequest<ApiV1CheckUserIdentityGetData> request = new ApiV1CheckUserIdentityGet<>(context, apiParams);
        request.processing(new WebRequest.Processing<ApiV1CheckUserIdentityGetData>() {
            @Override
            public ApiV1CheckUserIdentityGetData run(String data) {
                return new ApiV1CheckUserIdentityGetData(data);
            }
        }).failProcess(new WebRequest.FailProcess<ApiV1CheckUserIdentityGetData>() {
            @Override
            public void run(String data, ApiV1CheckUserIdentityGetData information) {
                loadingDialog.dismiss();
                ErrorProcessingData.run(context, data, information);
            }
        }).unknownFailRequest(new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                loadingDialog.dismiss();
                String content = context.getString(R.string.request_load_fail);
                Toast.makeText(context, content, Toast.LENGTH_LONG).show();
            }
        }).successProcess(new WebRequest.SuccessProcess<ApiV1CheckUserIdentityGetData>() {
            @Override
            public void run(String data, ApiV1CheckUserIdentityGetData information) {
                loadingDialog.dismiss();
                if (null != mCallBackEvent) {
                    mCallBackEvent.onSuccessCheckState(information);
                }
            }
        }).start();
    }


    public void advertiseApplyRequest() {
        loadingDialog.show();
        ApiParams params = new ApiParams(serverInfoInjection, accountInjection);
        WebRequest<ApiV1PublishPostData> request = new ApiV1PublishPost<>(context, params);
        request.processing(new WebRequest.Processing<ApiV1PublishPostData>() {
            @Override
            public ApiV1PublishPostData run(String data) {
                return new ApiV1PublishPostData(data);
            }
        }).failProcess(new WebRequest.FailProcess<ApiV1PublishPostData>() {
            @Override
            public void run(String data, ApiV1PublishPostData information) {
                ErrorProcessingData.run(context, data, information);
                loadingDialog.dismiss();
            }
        }).unknownFailRequest(new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                String content = context.getString(R.string.request_load_fail);
                loadingDialog.dismiss();
                Toast.makeText(context, content, Toast.LENGTH_LONG).show();
            }
        }).successProcess(new WebRequest.SuccessProcess<ApiV1PublishPostData>() {
            @Override
            public void run(String data, ApiV1PublishPostData information) {
                loadingDialog.dismiss();
                if (null != mCallBackEvent) {
                    mCallBackEvent.onSuccessAdvertiseApply(information);
                }
            }
        }).start();

    }

    public void introductionRequest() {
        loadingDialog.show();
        ApiParams params = new ApiParams(serverInfoInjection, accountInjection);
        WebRequest<ApiV1UserInstructionGetData> request = new ApiV1UserInstructionGet<>(context, params);
        request.processing(new WebRequest.Processing<ApiV1UserInstructionGetData>() {
            @Override
            public ApiV1UserInstructionGetData run(String data) {
                return new ApiV1UserInstructionGetData(data);
            }
        }).failProcess(new WebRequest.FailBackgroundProcess<ApiV1UserInstructionGetData>() {
            @Override
            public void run(String data, ApiV1UserInstructionGetData information) {
                loadingDialog.dismiss();
                ErrorProcessingData.run(context, data, information);
            }
        }).unknownFailRequest(new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                loadingDialog.dismiss();
                String content = context.getString(R.string.request_load_fail);
                Toast.makeText(context, content, Toast.LENGTH_LONG).show();
            }
        }).successProcess(new WebRequest.SuccessProcess<ApiV1UserInstructionGetData>() {
            @Override
            public void run(String data, ApiV1UserInstructionGetData information) {
                loadingDialog.dismiss();
                if (null != mCallBackEvent) {
                    mCallBackEvent.onSuccessIntroduction(information);
                }
            }
        }).start();
    }

    public void customerServiceRequest() {
        loadingDialog.show();
        ApiParams params = new ApiParams(serverInfoInjection, accountInjection);
        WebRequest<ApiV1UserCustomerServiceGetData> request = new ApiV1UserCustomerServiceGet<>(context, params);
        request.processing(new WebRequest.Processing<ApiV1UserCustomerServiceGetData>() {
            @Override
            public ApiV1UserCustomerServiceGetData run(String data) {
                return new ApiV1UserCustomerServiceGetData(data);
            }
        }).failProcess(new WebRequest.FailBackgroundProcess<ApiV1UserCustomerServiceGetData>() {
            @Override
            public void run(String data, ApiV1UserCustomerServiceGetData information) {
                loadingDialog.dismiss();
                ErrorProcessingData.run(context, data, information);
            }
        }).unknownFailRequest(new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                loadingDialog.dismiss();
                String content = context.getString(R.string.request_load_fail);
                Toast.makeText(context, content, Toast.LENGTH_LONG).show();
            }
        }).successProcess(new WebRequest.SuccessProcess<ApiV1UserCustomerServiceGetData>() {
            @Override
            public void run(String data, ApiV1UserCustomerServiceGetData information) {
                loadingDialog.dismiss();
                if (null != mCallBackEvent) {
                    mCallBackEvent.onSuccessCustomerService(information);
                }
            }
        }).start();
    }


    public void logoutRequest() {
        WebRequest<ApiV1PushUnregisterData> request = new ApiV1PushUnregisterGet<>(context, apiParams);
        request.processing(new WebRequest.Processing<ApiV1PushUnregisterData>() {
            @Override
            public ApiV1PushUnregisterData run(String data) {
                return new ApiV1PushUnregisterData(data);
            }
        }).failProcess(new WebRequest.FailProcess<ApiV1PushUnregisterData>() {
            @Override
            public void run(String data, ApiV1PushUnregisterData information) {
                ErrorProcessingData.run(context, data, information);
            }
        }).unknownFailRequest(new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                String content = context.getString(R.string.request_load_fail);
                Toast.makeText(context, content, Toast.LENGTH_LONG).show();
            }
        }).successProcess(new WebRequest.SuccessProcess<ApiV1PushUnregisterData>() {
            @Override
            public void run(String data, ApiV1PushUnregisterData information) {
                if (null != mCallBackEvent) {
                    mCallBackEvent.onSuccessLogout(information);
                }
            }
        }).start();
    }


    public void advertisementRequest() {
        WebRequest<ApiV1AdveriseShowGetData> request = new ApiV1AdveriseShowGet<>(context, apiParams);
        request.processing(new WebRequest.Processing<ApiV1AdveriseShowGetData>() {
            @Override
            public ApiV1AdveriseShowGetData run(String data) {
                return new ApiV1AdveriseShowGetData(data);
            }
        }).failProcess(new WebRequest.FailProcess<ApiV1AdveriseShowGetData>() {
            @Override
            public void run(String data, ApiV1AdveriseShowGetData information) {
                ErrorProcessingData.run(context, data, information);
                reRun("advertiseShow");
            }
        }).unknownFailRequest(new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                reRun("advertiseShow");
            }
        }).successProcess(new WebRequest.SuccessProcess<ApiV1AdveriseShowGetData>() {
            @Override
            public void run(String data, ApiV1AdveriseShowGetData information) {
                if (information.result == 0) {
                    update(information);
                    reTryCount = 0;
                }
            }
        }).start();
    }

    /**
     * 更新廣告失敗時重跑
     *
     * @param function 檢查 傳入的function 名稱重跑對應的函數
     */
    public void reRun(String function) {
        if (reTryCount < reTryMax) {
            reTryCount++;
            if (function == "advertiseShow")
                advertisementRequest();
        } else {
            mCallBackEvent.onError();
        }
    }

    /**
     * 下方廣告佇列更新
     */
    private void update(ApiV1AdveriseShowGetData information) {
        mCallBackEvent.onSuccess(information);
    }


    public void setCallBackEvent(CallBackEvent callBackEvent) {
        this.mCallBackEvent = callBackEvent;
    }

    public interface CallBackEvent {
        void onError();

        void onSuccess(ApiV1AdveriseShowGetData information);

        void onSuccessLogout(ApiV1PushUnregisterData information);

        void onSuccessIntroduction(ApiV1UserInstructionGetData information);

        void onSuccessCustomerService(ApiV1UserCustomerServiceGetData information);

        void onSuccessAdvertiseApply(ApiV1PublishPostData information);

        void onSuccessCheckState(ApiV1CheckUserIdentityGetData information);

    }
}
