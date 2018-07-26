package com.poc2.contrube.controllor;

import android.content.Context;
import android.widget.Toast;

import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.androidlibrary.module.ApiParams;
import com.androidlibrary.module.backend.api.ApiV1NormalLDAPAddPost;
import com.androidlibrary.module.backend.api.ApiV1NormalLDAPLoginPost;
import com.androidlibrary.module.backend.api.ApiV1NormalStoreListGet;
import com.androidlibrary.module.backend.data.ApiV1NormalLDAPAddPostData;
import com.androidlibrary.module.backend.data.ApiV1NormalLDAPLoginPostData;
import com.androidlibrary.module.backend.data.ApiV1NormalStoreListGetData;
import com.androidlibrary.module.backend.data.ErrorProcessingData;
import com.androidlibrary.module.backend.params.AccountInjection;
import com.androidlibrary.module.backend.params.ServerInfoInjection;
import com.androidlibrary.module.backend.request.WebRequest;
import com.poc2.R;
import com.poc2.contrube.component.dialog.LoadingDialog;

/**
 * Created by 依杰 on 2018/7/20.
 */

public class NormalLoginBlockChainControllor {
    private Context context;
    private AccountInjection accountInjection;
    private ServerInfoInjection serverInfoInjection;
    private ApiParams apiParams;
    private LoadingDialog loadingDialog;
    private CallBackEvent mCallBackEvent;

    public NormalLoginBlockChainControllor(Context context) {
        this.context = context;
        accountInjection = new AccountInjection(context);
        serverInfoInjection = new ServerInfoInjection();
        loadingDialog = new LoadingDialog(context);
        apiParams = new ApiParams(serverInfoInjection, accountInjection);
    }

    public void loginLDAP(String account, String password) {
        loadingDialog.show();
        apiParams.inputEmail = account;
        apiParams.inputPassword = password;
        WebRequest<ApiV1NormalLDAPLoginPostData> request = new ApiV1NormalLDAPLoginPost<>(context, apiParams);
        request.processing(new WebRequest.Processing<ApiV1NormalLDAPLoginPostData>() {
            @Override
            public ApiV1NormalLDAPLoginPostData run(String data) {
                return new ApiV1NormalLDAPLoginPostData(data);
            }
        }).failProcess(new WebRequest.FailProcess<ApiV1NormalLDAPLoginPostData>() {
            @Override
            public void run(String data, ApiV1NormalLDAPLoginPostData information) {
                loadingDialog.dismiss();
//                ErrorProcessingData.run(context, data, information);
                if (null != mCallBackEvent && data.contains("success")) {
                    mCallBackEvent.onSuccess(information);
                }
            }
        }).unknownFailRequest(new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                loadingDialog.dismiss();
                String content = context.getString(R.string.request_load_fail);
                Toast.makeText(context, content, Toast.LENGTH_LONG).show();
            }
        }).successProcess(new WebRequest.SuccessProcess<ApiV1NormalLDAPLoginPostData>() {
            @Override
            public void run(String data, ApiV1NormalLDAPLoginPostData information) {
                loadingDialog.dismiss();
//                if (null != mCallBackEvent) {
//                    mCallBackEvent.onSuccess(information);
//                }
            }
        }).start();
    }

    public void checkLdapState() {
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
                if (null != mCallBackEvent) {
                    mCallBackEvent.onSuccess(information);
                }
            }
        }).start();
    }

    public void addLDAP(String token) {
        loadingDialog.show();
        apiParams.ldapAuthToken = token;
        WebRequest<ApiV1NormalLDAPAddPostData> request = new ApiV1NormalLDAPAddPost<>(context, apiParams);
        request.processing(new WebRequest.Processing<ApiV1NormalLDAPAddPostData>() {
            @Override
            public ApiV1NormalLDAPAddPostData run(String data) {
                return new ApiV1NormalLDAPAddPostData(data);
            }
        }).failProcess(new WebRequest.FailProcess<ApiV1NormalLDAPAddPostData>() {
            @Override
            public void run(String data, ApiV1NormalLDAPAddPostData information) {
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
        }).successProcess(new WebRequest.SuccessProcess<ApiV1NormalLDAPAddPostData>() {
            @Override
            public void run(String data, ApiV1NormalLDAPAddPostData information) {
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

        void onSuccess(ApiV1NormalStoreListGetData information);

        void onSuccess(ApiV1NormalLDAPLoginPostData information);

        void onSuccess(ApiV1NormalLDAPAddPostData information);
    }

}
