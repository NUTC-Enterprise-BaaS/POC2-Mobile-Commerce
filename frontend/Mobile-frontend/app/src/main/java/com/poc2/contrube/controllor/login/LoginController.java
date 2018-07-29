package com.poc2.contrube.controllor.login;

import android.content.Context;
import android.os.Bundle;
import android.util.Log;
import android.widget.Toast;

import com.android.volley.NetworkResponse;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.androidlibrary.module.ApiParams;
import com.androidlibrary.module.backend.api.ApiV1LoginPost;
import com.androidlibrary.module.backend.data.ApiV1LoginPostData;
import com.androidlibrary.module.backend.data.ErrorProcessingData;
import com.androidlibrary.module.backend.params.AccountInjection;
import com.androidlibrary.module.backend.params.ServerInfoInjection;
import com.androidlibrary.module.backend.request.WebRequest;
import com.androidlibrary.module.consts.AccountConst;
import com.poc2.R;
import com.poc2.contrube.component.dialog.LoadingDialog;
import com.poc2.contrube.component.dialog.LoginErrorDialog;
import com.poc2.contrube.core.ActivityLauncher;
import com.poc2.contrube.model.LoginHelper;
import com.poc2.contrube.view.normal.ActivityNormalAdvertisement;

import org.json.JSONException;
import org.json.JSONObject;

/**
 * Created by Gary on 2016/11/9.
 */

public class LoginController {
    private final String TAG = LoginController.class.getName();
    private Context context;
    private AccountInjection accountInjection;
    private ServerInfoInjection serverInfoInjection;
    private ApiParams apiParams;
    private LoadingDialog loadingDialog;
    private LoginErrorDialog loginErrorDialog;

    private LoginHelper loginHelper;
    private boolean autoLoginState;
    private CallBackEvent mCallBackEvent;

    public LoginController(Context context) {
        this.context = context;
        accountInjection = new AccountInjection(context);
        serverInfoInjection = new ServerInfoInjection();
        loadingDialog = new LoadingDialog(context);
        loginErrorDialog = new LoginErrorDialog(context);
        loginHelper = new LoginHelper(context);
        autoLoginState = false;
    }

    public void loginRequest(String account, String password) {
        apiParams = new ApiParams(serverInfoInjection, accountInjection);
        apiParams.inputEmail = account;
        apiParams.inputPassword = password;

        loadingDialog.show();
        WebRequest<ApiV1LoginPostData> request = new ApiV1LoginPost<>(context, apiParams);
        request.processing(processingData)
                .failProcess(failProcessingData)
                .unknownFailRequest(failUnknownReason)
                .successProcess(successResponse)
                .start();
    }

    public WebRequest.Processing<ApiV1LoginPostData> processingData = new WebRequest.Processing<ApiV1LoginPostData>() {
        @Override
        public ApiV1LoginPostData run(String data) {
            return new ApiV1LoginPostData(data);
        }
    };

    public WebRequest.FailProcess<ApiV1LoginPostData> failProcessingData = new WebRequest.FailProcess<ApiV1LoginPostData>() {
        @Override
        public void run(String data, ApiV1LoginPostData information) {
            loadingDialog.dismiss();
            ErrorProcessingData.run(context, data, information);
        }
    };

    private Response.ErrorListener failUnknownReason = new Response.ErrorListener() {
        @Override
        public void onErrorResponse(VolleyError error) {
            loadingDialog.dismiss();
            String json = null;

            NetworkResponse response = error.networkResponse;
            if (response != null && response.data != null) {
                switch (response.statusCode) {
                    case 401:
                        json = new String(response.data);
                        json = trimMessage(json, "message");
                        if (json != null && json.equals("[\"user blocked\"]")) {
                            loginErrorDialog.showBlockError();
                        } else if (json != null && json.equals("[\"invalid_credentials\"]")) {
                            loginErrorDialog.showEmailOrPasswordError();
                        }
                        Log.e(TAG, "login Request", error);
                        if (null != mCallBackEvent)
                            mCallBackEvent.onError();
                        break;
                }
            } else {
                String content = context.getString(R.string.request_load_fail);
                Toast.makeText(context, content, Toast.LENGTH_SHORT).show();
            }
        }
    };

    private WebRequest.SuccessProcess<ApiV1LoginPostData> successResponse = new WebRequest.SuccessProcess<ApiV1LoginPostData>() {
        @Override
        public void run(String data, ApiV1LoginPostData information) {
            loadingDialog.dismiss();
            if (information.result == 0) {
                successLogin(information);
            }
        }
    };

    public void successLogin(ApiV1LoginPostData information) {
        String token = "bearer " + information.token;
        String account = apiParams.inputEmail;
        String password = apiParams.inputPassword;
        String registerState = information.registeredState;
        save(account, password, autoLoginState, token, registerState);
        accountInjection.save(AccountConst.KEY_TOKEN, token);
        accountInjection.save(AccountConst.Key_REGISTER_STATE, information.registeredState);
        accountInjection.save(AccountConst.KEY_IS_KEEP_LOGIN, autoLoginState);
        accountInjection.save(AccountConst.KEY_ACCOUNT, account);
        accountInjection.save(AccountConst.KEY_PASSWORD, password);
        Bundle args = new Bundle();
        ActivityLauncher.go(context, ActivityNormalAdvertisement.class, args);
        if (null != mCallBackEvent)
            mCallBackEvent.onSuccess();
    }

    public void save(String account, String password, boolean autoLogin, String token, String registerState) {
        loginHelper.saveAccount(account);
        loginHelper.savePassword(password);
        loginHelper.saveToken(token);
        loginHelper.saveRegisterState(registerState);
//        loginHelper.saveAutoLogin(autoLogin);
    }

    public String trimMessage(String json, String key) {
        String trimmedString = null;

        try {
            JSONObject obj = new JSONObject(json);
            trimmedString = obj.getString(key);
        } catch (JSONException e) {
            e.printStackTrace();
            return null;
        }

        return trimmedString;
    }

    public void setAutoLoginState(boolean state) {
        this.autoLoginState = state;
    }

    public void setmCallBackEvent(CallBackEvent callBackEvent) {
        this.mCallBackEvent = callBackEvent;
    }

    public String getAccount() {
        return String.valueOf(loginHelper.getAccount());
    }

    public String getPassword() {
        return String.valueOf(loginHelper.getPassword());
    }

    public String getAutoLogin() {
        return String.valueOf(loginHelper.getAutoLogin());
    }

    public interface CallBackEvent {
        void onError();

        void onSuccess();
    }


}
