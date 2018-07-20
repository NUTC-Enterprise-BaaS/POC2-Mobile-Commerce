package com.herbhousesgobuyother.contrube.controllor.register;

import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.widget.Toast;

import com.android.volley.NetworkResponse;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.androidlibrary.component.dialog.LoginErrorDialog;
import com.androidlibrary.module.ApiParams;
import com.androidlibrary.module.backend.api.ApiV1LoginPost;
import com.androidlibrary.module.backend.api.ApiV1RegisterPost;
import com.androidlibrary.module.backend.data.ApiV1LoginPostData;
import com.androidlibrary.module.backend.data.ApiV1RegisterPostData;
import com.androidlibrary.module.backend.data.ErrorProcessingData;
import com.androidlibrary.module.backend.params.AccountInjection;
import com.androidlibrary.module.backend.params.ServerInfoInjection;
import com.androidlibrary.module.backend.request.WebRequest;
import com.androidlibrary.module.consts.AccountConst;
import com.herbhousesgobuyother.R;
import com.herbhousesgobuyother.contrube.component.dialog.LoadingDialog;
import com.herbhousesgobuyother.contrube.model.LoginHelper;
import com.herbhousesgobuyother.contrube.view.normal.ActivityNormalAdvertisement;

import org.json.JSONException;
import org.json.JSONObject;

/**
 * Created by Gary on 2016/11/14.
 */

public class RegisterController {
    private final String TAG = RegisterController.class.getName();
    private Context context;
    private AccountInjection accountInjection;
    private ServerInfoInjection serverInfoInjection;
    private ApiParams apiParams;
    private LoadingDialog loadingDialog;
    private LoginErrorDialog loginErrorDialog;
    private LoginHelper loginHelper;
    private RegisterController.CallBackEvent mCallBackEvent;
    private String country;
    private String name;
    private String phone;
    private String email;
    private String password;
    private String birth;
    private String recommendId;

    public RegisterController(Context context) {
        this.context = context;
        accountInjection = new AccountInjection(context);
        serverInfoInjection = new ServerInfoInjection();
        loadingDialog = new LoadingDialog(context);
        loginErrorDialog = new LoginErrorDialog(context);
        loginHelper = new LoginHelper(context);

        country = "";
        name = "";
        phone = "";
        email = "";
        password = "";
        birth = "";
        recommendId = "";
    }

    public void registerRequest() {
        apiParams = new ApiParams(serverInfoInjection);
        apiParams.inputRegisterName = name;
        apiParams.inputRegisterCountry = country;
        apiParams.inputRegisterPhone = phone;
        apiParams.inputRegisterEmail = email;
        apiParams.inputRegisterPassword = password;
        apiParams.inputRegisterBirthday = birth;
//        apiParams.inputRegisterQrCode = recommendId;

        loadingDialog.show();
        WebRequest<ApiV1RegisterPostData> request = new ApiV1RegisterPost<>(context, apiParams);
        request.processing(new WebRequest.Processing<ApiV1RegisterPostData>() {
            @Override
            public ApiV1RegisterPostData run(String data) {
                return new ApiV1RegisterPostData(data);
            }
        }).failProcess(new WebRequest.FailProcess<ApiV1RegisterPostData>() {
            @Override
            public void run(String data, ApiV1RegisterPostData information) {
                loadingDialog.dismiss();
                ErrorProcessingData.run(context, data, information);
            }
        }).unknownFailRequest(new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                loadingDialog.dismiss();
                String json = null;

                NetworkResponse response = error.networkResponse;
                if (response != null && response.data != null) {
                    switch (response.statusCode) {
                        case 422:
                            json = new String(response.data);
                            json = trimMessage(json, "message");
                            if (json != null && json.equals("[\"The email has already been taken.\"]")) {
                                String content = context.getString(R.string.request_registered_fail_email);
                                Toast.makeText(context, content, Toast.LENGTH_SHORT).show();
                            }
                            break;

                        default:
                            String content = context.getString(R.string.request_load_fail);
                            Toast.makeText(context, content, Toast.LENGTH_SHORT).show();
                    }
                }

            }
        }).successProcess(new WebRequest.SuccessProcess<ApiV1RegisterPostData>() {
            @Override
            public void run(String data, ApiV1RegisterPostData information) {
                loadingDialog.dismiss();
                if (information.result == 0) {
                    String content = context.getString(R.string.request_registered_scuess);
                    Toast.makeText(context, content, Toast.LENGTH_SHORT).show();
                    loginRequest(email, password);
                } else if (information.messageGroup.get(0).toString().equals("The phone has already been taken")) {
                    String content = context.getString(R.string.request_registered_fail_phone);
                    Toast.makeText(context, content, Toast.LENGTH_SHORT).show();
                }

            }
        }).start();
    }

    public void loginRequest(String account, String password) {
        apiParams = new ApiParams(serverInfoInjection, accountInjection);
        apiParams.inputEmail = account;
        apiParams.inputPassword = password;
        accountInjection.save(AccountConst.KEY_ACCOUNT, account);
        accountInjection.save(AccountConst.KEY_PASSWORD, password);
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
            loginErrorDialog.showEmailOrPasswordError();

            Log.e(TAG, "login Request", error);
            if (null != mCallBackEvent)
                mCallBackEvent.onError();
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
        String registerState = information.registeredState;
        accountInjection.save(AccountConst.KEY_TOKEN, token);
        accountInjection.save(AccountConst.Key_REGISTER_STATE, information.registeredState);
        accountInjection.save(AccountConst.KEY_IS_KEEP_LOGIN, true);
        loginHelper.saveAccount(email);
        loginHelper.savePassword(password);
        loginHelper.saveToken(token);
        loginHelper.saveRegisterState(registerState);
//        loginHelper.saveAutoLogin("0");
        Bundle args = new Bundle();
        args.putString("recommendId", recommendId);
        args.putString("from", "general");
        Intent intent = new Intent(context, ActivityNormalAdvertisement.class);
        intent.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
        intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TASK);
        intent.putExtras(args);
        context.startActivity(intent);
        if (null != mCallBackEvent)
            mCallBackEvent.onSuccess();
    }


    public void setmCallBackEvent(RegisterController.CallBackEvent callBackEvent) {
        this.mCallBackEvent = callBackEvent;
    }


    public interface CallBackEvent {
        void onError();

        void onSuccess();
    }

    public void setRegisterData(String country, String name, String phone, String email, String password, String birth, String recommendId) {
        this.country = country;
        this.name = name;
        this.phone = phone;
        this.email = email;
        this.password = password;
        this.birth = birth;
        this.recommendId = recommendId;
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
}
