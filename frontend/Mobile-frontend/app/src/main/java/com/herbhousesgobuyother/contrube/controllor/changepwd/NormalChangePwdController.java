package com.herbhousesgobuyother.contrube.controllor.changepwd;

import android.content.Context;
import android.widget.Toast;

import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.androidlibrary.component.dialog.LoginErrorDialog;
import com.androidlibrary.module.ApiParams;
import com.androidlibrary.module.backend.api.ApiV1UserResetPasswordDataPost;
import com.androidlibrary.module.backend.data.ApiV1UserResetPasswordData;
import com.androidlibrary.module.backend.params.AccountInjection;
import com.androidlibrary.module.backend.params.ServerInfoInjection;
import com.androidlibrary.module.backend.request.WebRequest;
import com.herbhousesgobuyother.R;
import com.herbhousesgobuyother.contrube.component.dialog.LoadingDialog;

/**
 * Created by Gary on 2016/11/20.
 */

public class NormalChangePwdController {
    private final String TAG = NormalChangePwdController.class.getName();
    private Context context;
    private AccountInjection accountInjection;
    private ServerInfoInjection serverInfoInjection;
    private ApiParams apiParams;
    private LoadingDialog loadingDialog;
    private LoginErrorDialog loginErrorDialog;

    private NormalChangePwdController.CallBackEvent mCallBackEvent;

    public NormalChangePwdController(Context context) {
        this.context = context;
        accountInjection = new AccountInjection(context);
        serverInfoInjection = new ServerInfoInjection();
        loadingDialog = new LoadingDialog(context);
        loginErrorDialog = new LoginErrorDialog(context);
        apiParams = new ApiParams(serverInfoInjection, accountInjection);
    }

    public void restartRequest(String oldPwd, String nowPwd) {
        loadingDialog.show();

        apiParams.inputPassword = oldPwd;
        apiParams.inputNewPassword = nowPwd;
        WebRequest<ApiV1UserResetPasswordData> request = new ApiV1UserResetPasswordDataPost<>(context, apiParams);
        request.processing(new WebRequest.Processing<ApiV1UserResetPasswordData>() {
            @Override
            public ApiV1UserResetPasswordData run(String data) {
                return new ApiV1UserResetPasswordData(data);
            }
        }).failProcess(new WebRequest.FailProcess<ApiV1UserResetPasswordData>() {
            @Override
            public void run(String data, ApiV1UserResetPasswordData information) {
                loadingDialog.dismiss();
                String content = context.getString(R.string.request_load_resetpassword_fial);
                Toast.makeText(context, content, Toast.LENGTH_LONG).show();
            }
        }).unknownFailRequest(new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                loadingDialog.dismiss();
                String content = context.getString(R.string.request_load_fail);
                Toast.makeText(context, content, Toast.LENGTH_LONG).show();
            }
        }).successProcess(new WebRequest.SuccessProcess<ApiV1UserResetPasswordData>() {
            @Override
            public void run(String data, ApiV1UserResetPasswordData information) {
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

        void onSuccess(ApiV1UserResetPasswordData information);

    }
}
