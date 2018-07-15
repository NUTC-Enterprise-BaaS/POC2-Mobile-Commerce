package com.herbhousesgobuyother.contrube.controllor.forgetpassword;

import android.content.Context;
import android.widget.Toast;

import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.androidlibrary.component.dialog.LoginErrorDialog;
import com.androidlibrary.module.ApiParams;
import com.androidlibrary.module.backend.api.ApiV1UserPasswordForgotVerifyCodePost;
import com.androidlibrary.module.backend.api.ApiV1UserPasswordForgotVerifyCodeSendPost;
import com.androidlibrary.module.backend.data.ApiV1UserPasswordForgotVerifyCodePostData;
import com.androidlibrary.module.backend.data.ApiV1UserPasswordForgotVerifyCodeSendPostData;
import com.androidlibrary.module.backend.data.ErrorProcessingData;
import com.androidlibrary.module.backend.params.AccountInjection;
import com.androidlibrary.module.backend.params.ServerInfoInjection;
import com.androidlibrary.module.backend.request.WebRequest;
import com.herbhousesgobuyother.R;
import com.herbhousesgobuyother.contrube.component.dialog.LoadingDialog;
import com.herbhousesgobuyother.contrube.controllor.browsestore.BrowseStoreController;

/**
 * Created by Gary on 2016/11/20.
 */

public class ForgetPasswordController {
    private final String TAG = BrowseStoreController.class.getName();
    private Context context;
    private AccountInjection accountInjection;
    private ServerInfoInjection serverInfoInjection;
    private ApiParams apiParams;
    private LoadingDialog loadingDialog;
    private LoginErrorDialog loginErrorDialog;

    private ForgetPasswordController.CallBackEvent mCallBackEvent;

    public ForgetPasswordController(Context context) {
        this.context = context;
        accountInjection = new AccountInjection(context);
        serverInfoInjection = new ServerInfoInjection();
        loadingDialog = new LoadingDialog(context);
        loginErrorDialog = new LoginErrorDialog(context);
        apiParams = new ApiParams(serverInfoInjection, accountInjection);
    }

    public void nextRequest(String email, String checkCode) {
        loadingDialog.show();
        apiParams.inputEmail = email;
        apiParams.inputVerify = checkCode;
        WebRequest<ApiV1UserPasswordForgotVerifyCodePostData> request = new ApiV1UserPasswordForgotVerifyCodePost<>(context, apiParams);
        request.processing(new WebRequest.Processing<ApiV1UserPasswordForgotVerifyCodePostData>() {
            @Override
            public ApiV1UserPasswordForgotVerifyCodePostData run(String data) {
                return new ApiV1UserPasswordForgotVerifyCodePostData(data);
            }
        }).failProcess(new WebRequest.FailProcess<ApiV1UserPasswordForgotVerifyCodePostData>() {
            @Override
            public void run(String data, ApiV1UserPasswordForgotVerifyCodePostData information) {
                loadingDialog.dismiss();
                ErrorProcessingData.run(context, data, information);
            }
        }).unknownFailRequest(new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                loadingDialog.dismiss();
            }
        }).successProcess(new WebRequest.SuccessProcess<ApiV1UserPasswordForgotVerifyCodePostData>() {
            @Override
            public void run(String data, ApiV1UserPasswordForgotVerifyCodePostData information) {
                loadingDialog.dismiss();
                mCallBackEvent.onSuccess(information);
            }
        }).start();
    }

    public void sendCodeRequest(String email) {
        apiParams.inputPhoneNumber = "";
        apiParams.inputEmail = email;
        WebRequest<ApiV1UserPasswordForgotVerifyCodeSendPostData> request = new ApiV1UserPasswordForgotVerifyCodeSendPost<>(context, apiParams);
        request.processing(processingData)
                .failProcess(failProcessingData)
                .unknownFailRequest(failUnknownReason)
                .successProcess(successResponse)
                .start();
    }

    private WebRequest.Processing<ApiV1UserPasswordForgotVerifyCodeSendPostData> processingData = new WebRequest.Processing<ApiV1UserPasswordForgotVerifyCodeSendPostData>() {
        @Override
        public ApiV1UserPasswordForgotVerifyCodeSendPostData run(String data) {
            return new ApiV1UserPasswordForgotVerifyCodeSendPostData(data);
        }
    };

    private WebRequest.FailProcess<ApiV1UserPasswordForgotVerifyCodeSendPostData> failProcessingData = new WebRequest.FailProcess<ApiV1UserPasswordForgotVerifyCodeSendPostData>() {
        @Override
        public void run(String data, ApiV1UserPasswordForgotVerifyCodeSendPostData information) {
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

    private WebRequest.SuccessProcess<ApiV1UserPasswordForgotVerifyCodeSendPostData> successResponse = new WebRequest.SuccessProcess<ApiV1UserPasswordForgotVerifyCodeSendPostData>() {
        @Override
        public void run(String data, ApiV1UserPasswordForgotVerifyCodeSendPostData information) {
            loadingDialog.dismiss();
            if (information.result == 0) {
                loadingDialog.dismiss();
                Toast.makeText(context, R.string.send_codes_dialog, Toast.LENGTH_SHORT).show();
            } else if (information.messageGroup.get(0).toString().equals("This email does not exist")) {
                Toast.makeText(context, R.string.email_not_exist, Toast.LENGTH_SHORT).show();
            }
        }
    };


    public void setmCallBackEvent(ForgetPasswordController.CallBackEvent callBackEvent) {
        this.mCallBackEvent = callBackEvent;
    }


    public interface CallBackEvent {
        void onError();

        void onSuccess(ApiV1UserPasswordForgotVerifyCodePostData information);

    }
}
