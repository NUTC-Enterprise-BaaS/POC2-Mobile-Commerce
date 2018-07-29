package com.poc2.contrube.controllor.gcm;

import android.content.Context;
import android.widget.Toast;

import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.androidlibrary.module.ApiParams;
import com.androidlibrary.module.backend.api.ApiV1NormalPointGet;
import com.androidlibrary.module.backend.api.ApiV1NormalStoreChangePost;
import com.androidlibrary.module.backend.api.ApiV1NormalStoreCreateGet;
import com.androidlibrary.module.backend.api.ApiV1NormalStoreListGet;
import com.androidlibrary.module.backend.api.ApiV1NormalSyncPointGet;
import com.androidlibrary.module.backend.api.ApiV1NormalTokenGet;
import com.androidlibrary.module.backend.api.ApiV1NormalUserPointGet;
import com.androidlibrary.module.backend.data.ApiV1NormalPointGetData;
import com.androidlibrary.module.backend.data.ApiV1NormalStoreChangePostData;
import com.androidlibrary.module.backend.data.ApiV1NormalStoreCreateGetData;
import com.androidlibrary.module.backend.data.ApiV1NormalStoreListGetData;
import com.androidlibrary.module.backend.data.ApiV1NormalSyncPointGetData;
import com.androidlibrary.module.backend.data.ApiV1NormalTokenGetData;
import com.androidlibrary.module.backend.data.ApiV1NormalUserPointGetData;
import com.androidlibrary.module.backend.data.ErrorProcessingData;
import com.androidlibrary.module.backend.params.AccountInjection;
import com.androidlibrary.module.backend.params.ServerInfoInjection;
import com.androidlibrary.module.backend.request.WebRequest;
import com.poc2.R;
import com.poc2.component.notification.StorePromotionsNotification;
import com.poc2.contrube.component.dialog.LoadingDialog;

/**
 * Created by 依杰 on 2016/11/24.
 */

public class FragmentGCMRecordContorllor {
    private Context context;
    private AccountInjection accountInjection;
    private ServerInfoInjection serverInfoInjection;
    private ApiParams apiParams;
    private LoadingDialog loadingDialog;
    private CallBackEvent mCallBackEvent;
    private LoadingDialog blockChainDialog;

    public FragmentGCMRecordContorllor(Context context) {
        this.context = context;
        accountInjection = new AccountInjection(context);
        serverInfoInjection = new ServerInfoInjection();
        loadingDialog = new LoadingDialog(context);
        apiParams = new ApiParams(serverInfoInjection, accountInjection);
        blockChainDialog = new LoadingDialog(context, "等候區塊鏈驗證交易中，請稍後...");

    }

    public void getStoreList() {
        loadingDialog.show();
        WebRequest<ApiV1NormalStoreListGetData> request = new ApiV1NormalStoreListGet<>(context, apiParams);
        request.processing(new WebRequest.Processing<ApiV1NormalStoreListGetData>() {
            @Override
            public ApiV1NormalStoreListGetData run(String data) {
                return new ApiV1NormalStoreListGetData(data);
            }
        }).failProcess(new WebRequest.FailProcess<ApiV1NormalStoreListGetData>() {
            @Override
            public void run(String data, ApiV1NormalStoreListGetData information) {
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
        }).successProcess(new WebRequest.SuccessProcess<ApiV1NormalStoreListGetData>() {
            @Override
            public void run(String data, ApiV1NormalStoreListGetData information) {
                loadingDialog.dismiss();
                checkStoreName(information);
            }
        }).start();
    }

    public void getPoint(final String name, final String point, final String user) {
        loadingDialog.show();
        WebRequest<ApiV1NormalPointGetData> request = new ApiV1NormalPointGet<>(context, apiParams);
        request.processing(new WebRequest.Processing<ApiV1NormalPointGetData>() {
            @Override
            public ApiV1NormalPointGetData run(String data) {
                return new ApiV1NormalPointGetData(data);
            }
        }).failProcess(new WebRequest.FailProcess<ApiV1NormalPointGetData>() {
            @Override
            public void run(String data, ApiV1NormalPointGetData information) {
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
        }).successProcess(new WebRequest.SuccessProcess<ApiV1NormalPointGetData>() {
            @Override
            public void run(String data, ApiV1NormalPointGetData information) {
                loadingDialog.dismiss();
                if (Integer.valueOf(point) > Integer.valueOf(information.point)) {
                    if (null != mCallBackEvent) {
                        mCallBackEvent.onError("輸入的點數大於目前所擁有的" + information.point + "點");
                    }
                } else {
                    if (null != mCallBackEvent) {
                        mCallBackEvent.onSuccess(name, point, user);
                    }
                }
            }
        }).start();
    }

    public void checkStoreName(final ApiV1NormalStoreListGetData storeList) {
        loadingDialog.show();
        WebRequest<ApiV1NormalUserPointGetData> request = new ApiV1NormalUserPointGet<>(context, apiParams);
        request.processing(new WebRequest.Processing<ApiV1NormalUserPointGetData>() {
            @Override
            public ApiV1NormalUserPointGetData run(String data) {
                return new ApiV1NormalUserPointGetData(data);
            }
        }).failProcess(new WebRequest.FailProcess<ApiV1NormalUserPointGetData>() {
            @Override
            public void run(String data, ApiV1NormalUserPointGetData information) {
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
        }).successProcess(new WebRequest.SuccessProcess<ApiV1NormalUserPointGetData>() {
            @Override
            public void run(String data, ApiV1NormalUserPointGetData information) {
                loadingDialog.dismiss();
                if (null != mCallBackEvent) {
                    mCallBackEvent.onSuccess(storeList, information);
                }
            }
        }).start();
    }

    public void getRate(String name, final String type) {
        loadingDialog.show();
        apiParams.inputStart = name;
        WebRequest<ApiV1NormalStoreCreateGetData> request = new ApiV1NormalStoreCreateGet<>(context, apiParams);
        request.processing(new WebRequest.Processing<ApiV1NormalStoreCreateGetData>() {
            @Override
            public ApiV1NormalStoreCreateGetData run(String data) {
                return new ApiV1NormalStoreCreateGetData(data);
            }
        }).failProcess(new WebRequest.FailProcess<ApiV1NormalStoreCreateGetData>() {
            @Override
            public void run(String data, ApiV1NormalStoreCreateGetData information) {
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
        }).successProcess(new WebRequest.SuccessProcess<ApiV1NormalStoreCreateGetData>() {
            @Override
            public void run(String data, ApiV1NormalStoreCreateGetData information) {
                loadingDialog.dismiss();
                if (null != mCallBackEvent) {
                    mCallBackEvent.onSuccess(information,type);
                }
            }
        }).start();
    }

    public void changePoint(String name, String point, String user) {
//        blockChainDialog.show();

        apiParams.inputLdapPoint = point;
        apiParams.storeName = name;
        apiParams.userName = user;
        WebRequest<ApiV1NormalStoreChangePostData> request = new ApiV1NormalStoreChangePost<>(context, apiParams);
        request.processing(new WebRequest.Processing<ApiV1NormalStoreChangePostData>() {
            @Override
            public ApiV1NormalStoreChangePostData run(String data) {
                return new ApiV1NormalStoreChangePostData(data);
            }
        }).failProcess(new WebRequest.FailProcess<ApiV1NormalStoreChangePostData>() {
            @Override
            public void run(String data, ApiV1NormalStoreChangePostData information) {
                ErrorProcessingData.run(context, data, information);
            }
        }).unknownFailRequest(new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                String content = context.getString(R.string.request_load_fail);
                Toast.makeText(context, content, Toast.LENGTH_LONG).show();
            }
        }).successProcess(new WebRequest.SuccessProcess<ApiV1NormalStoreChangePostData>() {
            @Override
            public void run(String data, ApiV1NormalStoreChangePostData information) {
                if (null != mCallBackEvent) {
                    mCallBackEvent.onSuccess(information);
                }
            }
        }).start();
    }

    public void checkStorePoint(final String name, final String point, final String user) {
        loadingDialog.show();
        WebRequest<ApiV1NormalUserPointGetData> request = new ApiV1NormalUserPointGet<>(context, apiParams);
        request.processing(new WebRequest.Processing<ApiV1NormalUserPointGetData>() {
            @Override
            public ApiV1NormalUserPointGetData run(String data) {
                return new ApiV1NormalUserPointGetData(data);
            }
        }).failProcess(new WebRequest.FailProcess<ApiV1NormalUserPointGetData>() {
            @Override
            public void run(String data, ApiV1NormalUserPointGetData information) {
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
        }).successProcess(new WebRequest.SuccessProcess<ApiV1NormalUserPointGetData>() {
            @Override
            public void run(String data, ApiV1NormalUserPointGetData information) {
                loadingDialog.dismiss();
                if (Integer.valueOf(point) > Integer.valueOf(information.point)) {
                    if (null != mCallBackEvent) {
                        mCallBackEvent.onError("輸入的點數大於目前所擁有的" + information.point + "點");
                    }
                } else {
                    if (null != mCallBackEvent) {
                        mCallBackEvent.onSuccess(name, point, user);
                    }
                }
            }
        }).start();
    }

    public void getOwnerPoint() {
        loadingDialog.show();
        WebRequest<ApiV1NormalPointGetData> request = new ApiV1NormalPointGet<>(context, apiParams);
        request.processing(new WebRequest.Processing<ApiV1NormalPointGetData>() {
            @Override
            public ApiV1NormalPointGetData run(String data) {
                return new ApiV1NormalPointGetData(data);
            }
        }).failProcess(new WebRequest.FailProcess<ApiV1NormalPointGetData>() {
            @Override
            public void run(String data, ApiV1NormalPointGetData information) {
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
        }).successProcess(new WebRequest.SuccessProcess<ApiV1NormalPointGetData>() {
            @Override
            public void run(String data, ApiV1NormalPointGetData information) {
                loadingDialog.dismiss();
                if (null != mCallBackEvent) {
                    mCallBackEvent.onSuccess(information);
                }
            }
        }).start();
    }

    public void getVerifyCode() {
        loadingDialog.show();
        WebRequest<ApiV1NormalTokenGetData> request = new ApiV1NormalTokenGet<>(context, apiParams);
        request.processing(new WebRequest.Processing<ApiV1NormalTokenGetData>() {
            @Override
            public ApiV1NormalTokenGetData run(String data) {
                return new ApiV1NormalTokenGetData(data);
            }
        }).failProcess(new WebRequest.FailProcess<ApiV1NormalTokenGetData>() {
            @Override
            public void run(String data, ApiV1NormalTokenGetData information) {
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
        }).successProcess(new WebRequest.SuccessProcess<ApiV1NormalTokenGetData>() {
            @Override
            public void run(String data, ApiV1NormalTokenGetData information) {
                loadingDialog.dismiss();
                if (null != mCallBackEvent) {
                    mCallBackEvent.onSuccess(information);
                }
            }
        }).start();
    }

    public void setmCallBackEvent(CallBackEvent callBackEvent) {
        this.mCallBackEvent = callBackEvent;
    }

    public interface CallBackEvent {
        void onError(String message);

        void onSuccess(ApiV1NormalStoreListGetData information, ApiV1NormalUserPointGetData pointData);

        void onSuccess(ApiV1NormalStoreCreateGetData information, String type);

        void onSuccess(ApiV1NormalStoreChangePostData information);

        void onSuccess(ApiV1NormalTokenGetData information);

        void onSuccess(final String name, final String point, final String user);

        void onSuccess(ApiV1NormalPointGetData information);
    }
}
