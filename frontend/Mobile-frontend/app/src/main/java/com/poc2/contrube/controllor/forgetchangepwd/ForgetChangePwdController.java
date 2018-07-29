package com.poc2.contrube.controllor.forgetchangepwd;

import android.content.Context;
import android.widget.Toast;

import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.androidlibrary.component.dialog.LoginErrorDialog;
import com.androidlibrary.module.ApiParams;
import com.androidlibrary.module.backend.api.ApiV1UserRescuePasswordPost;
import com.androidlibrary.module.backend.data.ApiV1UserRescuePasswordPostData;
import com.androidlibrary.module.backend.data.ErrorProcessingData;
import com.androidlibrary.module.backend.params.AccountInjection;
import com.androidlibrary.module.backend.params.ServerInfoInjection;
import com.androidlibrary.module.backend.request.WebRequest;
import com.poc2.R;
import com.poc2.contrube.component.dialog.LoadingDialog;

/**
 * Created by Gary on 2016/11/20.
 */

public class ForgetChangePwdController {
    private final String TAG = ForgetChangePwdController.class.getName();
    private Context context;
    private AccountInjection accountInjection;
    private ServerInfoInjection serverInfoInjection;
    private ApiParams apiParams;
    private LoadingDialog loadingDialog;
    private LoginErrorDialog loginErrorDialog;

    private ForgetChangePwdController.CallBackEvent mCallBackEvent;

    public ForgetChangePwdController(Context context) {
        this.context = context;
        accountInjection = new AccountInjection(context);
        serverInfoInjection = new ServerInfoInjection();
        loadingDialog = new LoadingDialog(context);
        loginErrorDialog = new LoginErrorDialog(context);
        apiParams = new ApiParams(serverInfoInjection, accountInjection);
    }

    public void restartRequest(String email, String checkCode, String pwd, String pwdCheck) {
        loadingDialog.show();
        apiParams.inputEmail = email;
        apiParams.inputVerify = checkCode;
        apiParams.inputPassword = pwd;
        apiParams.inputPasswordAgain = pwdCheck;

        WebRequest<ApiV1UserRescuePasswordPostData> request = new ApiV1UserRescuePasswordPost<>(context, apiParams);
        request.processing(new WebRequest.Processing<ApiV1UserRescuePasswordPostData>() {
            @Override
            public ApiV1UserRescuePasswordPostData run(String data) {
                return new ApiV1UserRescuePasswordPostData(data);
            }
        }).failProcess(new WebRequest.FailProcess<ApiV1UserRescuePasswordPostData>() {
            @Override
            public void run(String data, ApiV1UserRescuePasswordPostData information) {
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
        }).successProcess(new WebRequest.SuccessProcess<ApiV1UserRescuePasswordPostData>() {
            @Override
            public void run(String data, ApiV1UserRescuePasswordPostData information) {
                loadingDialog.dismiss();
                if (null != mCallBackEvent) {
                    mCallBackEvent.onSuccess(information);
                }
            }
        }).start();
    }

    public void setmCallBackEvent(ForgetChangePwdController.CallBackEvent callBackEvent) {
        this.mCallBackEvent = callBackEvent;
    }


    public interface CallBackEvent {
        void onError();

        void onSuccess(ApiV1UserRescuePasswordPostData information);

    }
}
