package com.poc2.contrube.controllor.scan;

import android.content.Context;
import android.widget.Toast;

import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.androidlibrary.module.ApiParams;
import com.androidlibrary.module.backend.api.ApiV1BoundsSendPost;
import com.androidlibrary.module.backend.api.ApiV1NormalBuyVoucherPost;
import com.androidlibrary.module.backend.api.ApiV1NormalUserPointGet;
import com.androidlibrary.module.backend.data.ApiV1BoundsSendData;
import com.androidlibrary.module.backend.data.ApiV1NormalBuyVoucherPostData;
import com.androidlibrary.module.backend.data.ApiV1NormalUserPointGetData;
import com.androidlibrary.module.backend.data.ErrorProcessingData;
import com.androidlibrary.module.backend.params.AccountInjection;
import com.androidlibrary.module.backend.params.ServerInfoInjection;
import com.androidlibrary.module.backend.request.WebRequest;
import com.poc2.R;
import com.poc2.contrube.component.dialog.LoadingDialog;
import com.poc2.contrube.component.dialog.LoginErrorDialog;

/**
 * Created by Gary on 2016/11/9.
 */

public class QRCodeScanController {
    private final String TAG = QRCodeScanController.class.getName();
    private Context context;
    private AccountInjection accountInjection;
    private ServerInfoInjection serverInfoInjection;
    private ApiParams apiParams;
    private LoadingDialog loadingDialog;
    private QRCodeScanController.CallBackEvent mCallBackEvent;
    private LoginErrorDialog loginErrorDialog;

    public QRCodeScanController(Context context) {
        this.context = context;
        accountInjection = new AccountInjection(context);
        serverInfoInjection = new ServerInfoInjection();
        loadingDialog = new LoadingDialog(context);
        apiParams = new ApiParams(serverInfoInjection, accountInjection);
        loginErrorDialog = new LoginErrorDialog(context);
    }

    public void scanRequest(String shopId) {
        loadingDialog.show();
        apiParams.inputStoreId = shopId;
        WebRequest<ApiV1BoundsSendData> request = new ApiV1BoundsSendPost<>(context, apiParams);
        request.processing(processingData)
                .failProcess(failProcessingData)
                .unknownFailRequest(failUnknownReason)
                .successProcess(successResponse)
                .start();
    }

    public WebRequest.Processing<ApiV1BoundsSendData> processingData = new WebRequest.Processing<ApiV1BoundsSendData>() {
        @Override
        public ApiV1BoundsSendData run(String data) {
            return new ApiV1BoundsSendData(data);
        }
    };

    public WebRequest.FailProcess<ApiV1BoundsSendData> failProcessingData = new WebRequest.FailProcess<ApiV1BoundsSendData>() {
        @Override
        public void run(String data, ApiV1BoundsSendData information) {
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

    private WebRequest.SuccessProcess<ApiV1BoundsSendData> successResponse = new WebRequest.SuccessProcess<ApiV1BoundsSendData>() {
        @Override
        public void run(String data, ApiV1BoundsSendData information) {
            loadingDialog.dismiss();
            if (null != mCallBackEvent)
                mCallBackEvent.onSuccess(information);
        }
    };

    public void checkStorePoint(final String message, final String point, final String store, final String user) {
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
                if (null != mCallBackEvent) {
                    mCallBackEvent.onError();
                }
            }
        }).successProcess(new WebRequest.SuccessProcess<ApiV1NormalUserPointGetData>() {
            @Override
            public void run(String data, ApiV1NormalUserPointGetData information) {
                loadingDialog.dismiss();
                if (Integer.valueOf(point) > Integer.valueOf(information.point)) {
                    loginErrorDialog.setMessage("剩餘點數不足").show();
                } else {
                    buyCoupon(message, point, store, user);
                }
            }
        }).start();
    }

    public void buyCoupon(String message, String point, String store, String user) {
        loadingDialog.show();
        apiParams.inputLdapPoint = point;
        apiParams.storeName = store;
        apiParams.userName = user;
        apiParams.message = message;
        WebRequest<ApiV1NormalBuyVoucherPostData> request = new ApiV1NormalBuyVoucherPost<>(context, apiParams);
        request.processing(new WebRequest.Processing<ApiV1NormalBuyVoucherPostData>() {
            @Override
            public ApiV1NormalBuyVoucherPostData run(String data) {
                return new ApiV1NormalBuyVoucherPostData(data);
            }
        }).failProcess(new WebRequest.FailProcess<ApiV1NormalBuyVoucherPostData>() {
            @Override
            public void run(String data, ApiV1NormalBuyVoucherPostData information) {
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
        }).successProcess(new WebRequest.SuccessProcess<ApiV1NormalBuyVoucherPostData>() {
            @Override
            public void run(String data, ApiV1NormalBuyVoucherPostData information) {
                loadingDialog.dismiss();
                if (null != mCallBackEvent) {
                    mCallBackEvent.onSuccess(information);
                }
            }
        }).start();
    }

    public void setmCallBackEvent(QRCodeScanController.CallBackEvent callBackEvent) {
        this.mCallBackEvent = callBackEvent;
    }


    public interface CallBackEvent {
        void onError();

        void onSuccess(ApiV1BoundsSendData information);

        void onSuccess(ApiV1NormalBuyVoucherPostData information);
    }
}
