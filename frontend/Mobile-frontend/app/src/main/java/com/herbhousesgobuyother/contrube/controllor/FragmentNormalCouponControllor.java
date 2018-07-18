package com.herbhousesgobuyother.contrube.controllor;

import android.content.Context;
import android.widget.Toast;

import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.androidlibrary.module.ApiParams;
import com.androidlibrary.module.backend.api.ApiV1NormalUseVoucherPost;
import com.androidlibrary.module.backend.api.ApiV1NormalVoucherListGet;
import com.androidlibrary.module.backend.data.ApiV1NormalUseVoucherData;
import com.androidlibrary.module.backend.data.ApiV1NormalVoucherListGetData;
import com.androidlibrary.module.backend.data.ErrorProcessingData;
import com.androidlibrary.module.backend.params.AccountInjection;
import com.androidlibrary.module.backend.params.ServerInfoInjection;
import com.androidlibrary.module.backend.request.WebRequest;
import com.herbhousesgobuyother.R;
import com.herbhousesgobuyother.contrube.component.dialog.LoadingDialog;

/**
 * Created by 依杰 on 2018/7/16.
 */

public class FragmentNormalCouponControllor {
    private Context context;
    private AccountInjection accountInjection;
    private ServerInfoInjection serverInfoInjection;
    private ApiParams apiParams;
    private LoadingDialog loadingDialog;
    private CallBackEvent mCallBackEvent;

    public FragmentNormalCouponControllor(Context context) {
        this.context = context;
        accountInjection = new AccountInjection(context);
        serverInfoInjection = new ServerInfoInjection();
        loadingDialog = new LoadingDialog(context);
        apiParams = new ApiParams(serverInfoInjection, accountInjection);
    }

    public void getStoreList() {
        loadingDialog.show();
        WebRequest<ApiV1NormalVoucherListGetData> request = new ApiV1NormalVoucherListGet<>(context, apiParams);
        request.processing(new WebRequest.Processing<ApiV1NormalVoucherListGetData>() {
            @Override
            public ApiV1NormalVoucherListGetData run(String data) {
                return new ApiV1NormalVoucherListGetData(data);
            }
        }).failProcess(new WebRequest.FailProcess<ApiV1NormalVoucherListGetData>() {
            @Override
            public void run(String data, ApiV1NormalVoucherListGetData information) {
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
        }).successProcess(new WebRequest.SuccessProcess<ApiV1NormalVoucherListGetData>() {
            @Override
            public void run(String data, ApiV1NormalVoucherListGetData information) {
                loadingDialog.dismiss();
                if (null != mCallBackEvent) {
                    mCallBackEvent.onSuccess(information);
                }
            }
        }).start();
    }

    public void useCoupon(String id) {
        loadingDialog.show();
        apiParams.voucherid = id;
        WebRequest<ApiV1NormalUseVoucherData> request = new ApiV1NormalUseVoucherPost<>(context, apiParams);
        request.processing(new WebRequest.Processing<ApiV1NormalUseVoucherData>() {
            @Override
            public ApiV1NormalUseVoucherData run(String data) {
                return new ApiV1NormalUseVoucherData(data);
            }
        }).failProcess(new WebRequest.FailProcess<ApiV1NormalUseVoucherData>() {
            @Override
            public void run(String data, ApiV1NormalUseVoucherData information) {
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
        }).successProcess(new WebRequest.SuccessProcess<ApiV1NormalUseVoucherData>() {
            @Override
            public void run(String data, ApiV1NormalUseVoucherData information) {
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
        void onError();

        void onSuccess(ApiV1NormalVoucherListGetData information);

        void onSuccess(ApiV1NormalUseVoucherData information);
    }
}
