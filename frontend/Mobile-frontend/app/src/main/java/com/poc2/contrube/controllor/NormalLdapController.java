package com.poc2.contrube.controllor;

import android.content.Context;
import android.widget.Toast;

import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.androidlibrary.module.ApiParams;
import com.androidlibrary.module.backend.api.ApiV1NormalConnectLdapPost;
import com.androidlibrary.module.backend.api.ApiV1NormalCreateLdapPost;
import com.androidlibrary.module.backend.api.ApiV1NormalTokenGet;
import com.androidlibrary.module.backend.data.ApiV1NormalConnectLdapPostData;
import com.androidlibrary.module.backend.data.ApiV1NormalCreateLdapPostData;
import com.androidlibrary.module.backend.data.ApiV1NormalTokenGetData;
import com.androidlibrary.module.backend.data.ErrorProcessingData;
import com.androidlibrary.module.backend.params.AccountInjection;
import com.androidlibrary.module.backend.params.ServerInfoInjection;
import com.androidlibrary.module.backend.request.WebRequest;
import com.poc2.R;
import com.poc2.contrube.component.dialog.LoadingDialog;

/**
 * Created by 依杰 on 2018/7/13.
 */

public class NormalLdapController {
    private Context context;
    private AccountInjection accountInjection;
    private ServerInfoInjection serverInfoInjection;
    private ApiParams apiParams;
    private LoadingDialog loadingDialog;
    private CallBackEvent mCallBackEvent;

    public NormalLdapController(Context context) {
        this.context = context;
        accountInjection = new AccountInjection(context);
        serverInfoInjection = new ServerInfoInjection();
        loadingDialog = new LoadingDialog(context);
        apiParams = new ApiParams(serverInfoInjection, accountInjection);
    }

    public void connectLdap(String token,String ldaptoken) {
        final LoadingDialog  loadingDialog = new LoadingDialog(context,"創建區塊鏈帳戶，並整合LDAP帳號中，請耐心等待...");
        loadingDialog.show();
        apiParams.inputLdapToken = token;
        apiParams.ldapAuthToken = ldaptoken;
        WebRequest<ApiV1NormalConnectLdapPostData> request = new ApiV1NormalConnectLdapPost<>(context, apiParams);
        request.processing(new WebRequest.Processing<ApiV1NormalConnectLdapPostData>() {
            @Override
            public ApiV1NormalConnectLdapPostData run(String data) {
                return new ApiV1NormalConnectLdapPostData(data);
            }
        }).failProcess(new WebRequest.FailProcess<ApiV1NormalConnectLdapPostData>() {
            @Override
            public void run(String data, ApiV1NormalConnectLdapPostData information) {
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
        }).successProcess(new WebRequest.SuccessProcess<ApiV1NormalConnectLdapPostData>() {
            @Override
            public void run(String data, ApiV1NormalConnectLdapPostData information) {
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
        void onError();

        void onSuccess(ApiV1NormalConnectLdapPostData information);

        void onSuccess(ApiV1NormalTokenGetData information);
    }
}
