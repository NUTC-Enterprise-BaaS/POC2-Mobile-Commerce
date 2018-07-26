package com.poc2.contrube.controllor.csvchangepasssword;

import android.content.Context;

import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.androidlibrary.component.dialog.LoginErrorDialog;
import com.androidlibrary.module.ApiParams;
import com.androidlibrary.module.backend.api.ApiV1PreferentialCsvResetPasswordPost;
import com.androidlibrary.module.backend.data.ApiV1PreferentialCsvResetPasswordPostData;
import com.androidlibrary.module.backend.data.ErrorProcessingData;
import com.androidlibrary.module.backend.params.AccountInjection;
import com.androidlibrary.module.backend.params.ServerInfoInjection;
import com.androidlibrary.module.backend.request.WebRequest;
import com.poc2.contrube.component.dialog.LoadingDialog;
import com.poc2.contrube.controllor.changepwd.NormalChangePwdController;

/**
 * Created by 依杰 on 2016/11/28.
 */

public class PremiumCsvChangePwdController {
    private final String TAG = NormalChangePwdController.class.getName();
    private Context context;
    private AccountInjection accountInjection;
    private ServerInfoInjection serverInfoInjection;
    private ApiParams apiParams;
    private LoadingDialog loadingDialog;
    private LoginErrorDialog loginErrorDialog;

    private CallBackEvent mCallBackEvent;

    public PremiumCsvChangePwdController(Context context) {
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

        WebRequest<ApiV1PreferentialCsvResetPasswordPostData> request = new ApiV1PreferentialCsvResetPasswordPost<>(context, apiParams);
        request.processing(processingData)
                  .failProcess(failProcessingData)
                  .unknownFailRequest(failUnknownReason)
                  .successProcess(successResponse)
                  .start();
    }

    private WebRequest.Processing<ApiV1PreferentialCsvResetPasswordPostData> processingData = new WebRequest.Processing<ApiV1PreferentialCsvResetPasswordPostData>() {
        @Override
        public ApiV1PreferentialCsvResetPasswordPostData run(String data) {
            return new ApiV1PreferentialCsvResetPasswordPostData(data);
        }
    };
    private WebRequest.FailProcess<ApiV1PreferentialCsvResetPasswordPostData> failProcessingData = new WebRequest.FailProcess<ApiV1PreferentialCsvResetPasswordPostData>() {
        @Override
        public void run(String data, ApiV1PreferentialCsvResetPasswordPostData information) {
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
    private WebRequest.SuccessProcess<ApiV1PreferentialCsvResetPasswordPostData> successResponse = new WebRequest.SuccessProcess<ApiV1PreferentialCsvResetPasswordPostData>() {
        @Override
        public void run(String data, ApiV1PreferentialCsvResetPasswordPostData information) {
            loadingDialog.dismiss();
            if (null != mCallBackEvent) {
                    mCallBackEvent.onSuccess(information);
                }
        }
    };

    public void setmCallBackEvent(CallBackEvent callBackEvent) {
        this.mCallBackEvent = callBackEvent;
    }


    public interface CallBackEvent {
        void onError();

        void onSuccess(ApiV1PreferentialCsvResetPasswordPostData information);

    }
}
