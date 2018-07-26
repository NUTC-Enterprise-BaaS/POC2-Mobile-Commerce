package com.poc2.contrube.controllor.main;

import android.content.Context;
import android.widget.Toast;

import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.androidlibrary.module.ApiParams;
import com.androidlibrary.module.backend.api.ApiV1CheckUserIdentityGet;
import com.androidlibrary.module.backend.api.ApiV1NormalCreateLdapPost;
import com.androidlibrary.module.backend.api.ApiV1NormalStoreListGet;
import com.androidlibrary.module.backend.api.ApiV1NormalUserPointGet;
import com.androidlibrary.module.backend.data.ApiV1CheckUserIdentityGetData;
import com.androidlibrary.module.backend.data.ApiV1NormalCreateLdapPostData;
import com.androidlibrary.module.backend.data.ApiV1NormalStoreListGetData;
import com.androidlibrary.module.backend.data.ApiV1NormalUserPointGetData;
import com.androidlibrary.module.backend.data.ErrorProcessingData;
import com.androidlibrary.module.backend.params.AccountInjection;
import com.androidlibrary.module.backend.params.ServerInfoInjection;
import com.androidlibrary.module.backend.request.WebRequest;
import com.poc2.R;
import com.poc2.contrube.component.dialog.LoadingDialog;

/**
 * Created by Gary on 2016/11/29.
 */

public class NormalMainController {
    private final String TAG = NormalMainController.class.getName();
    private Context context;
    private AccountInjection accountInjection;
    private ServerInfoInjection serverInfoInjection;
    private ApiParams apiParams;
    private LoadingDialog loadingDialog;
    private NormalMainController.CallBackEvent mCallBackEvent;

    public NormalMainController(Context context) {
        this.context = context;
        accountInjection = new AccountInjection(context);
        serverInfoInjection = new ServerInfoInjection();
        loadingDialog = new LoadingDialog(context);
        apiParams = new ApiParams(serverInfoInjection, accountInjection);
    }

    public void checkStateRequest() {
        loadingDialog.show();
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
                    mCallBackEvent.onSuccess(information);
                }
            }
        }).start();
    }

    public void setCallBackEvent(NormalMainController.CallBackEvent callBackEvent) {
        this.mCallBackEvent = callBackEvent;
    }

    public interface CallBackEvent {
        void onError();

        void onSuccess(ApiV1CheckUserIdentityGetData information);
    }
}
