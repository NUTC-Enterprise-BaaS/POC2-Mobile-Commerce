package com.herbhousesgobuyother.contrube.controllor.register;

import android.content.Context;
import android.content.Intent;
import android.graphics.Bitmap;
import android.net.Uri;
import android.os.Bundle;
import android.util.Base64;
import android.util.Log;
import android.widget.Toast;

import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.androidlibrary.component.dialog.LoginErrorDialog;
import com.androidlibrary.module.ApiParams;
import com.androidlibrary.module.backend.api.ApiV1LoginPost;
import com.androidlibrary.module.backend.api.ApiV1SpecialRegisterPost;
import com.androidlibrary.module.backend.data.ApiV1LoginPostData;
import com.androidlibrary.module.backend.data.ApiV1SpecialRegisterPostData;
import com.androidlibrary.module.backend.data.ErrorProcessingData;
import com.androidlibrary.module.backend.params.AccountInjection;
import com.androidlibrary.module.backend.params.ServerInfoInjection;
import com.androidlibrary.module.backend.request.WebRequest;
import com.androidlibrary.module.consts.AccountConst;
import com.androidlibrary.ui.basicinformation.api.ApiV1UserDetailGet;
import com.androidlibrary.ui.basicinformation.data.ApiV1UserDetailGetData;
import com.herbhousesgobuyother.R;
import com.herbhousesgobuyother.contrube.component.dialog.LoadingDialog;
import com.herbhousesgobuyother.contrube.model.BitmapCompression;
import com.herbhousesgobuyother.contrube.model.LoginHelper;
import com.herbhousesgobuyother.contrube.view.normal.ActivityNormalAdvertisement;

import java.io.ByteArrayOutputStream;
import java.util.ArrayList;

/**
 * Created by Gary on 2016/11/14.
 */

public class SpecialRegisterController {
    private final String TAG = SpecialRegisterController.class.getName();
    private Context context;
    private AccountInjection accountInjection;
    private ServerInfoInjection serverInfoInjection;
    private ApiParams apiParams;
    private LoadingDialog loadingDialog;
    private LoginErrorDialog loginErrorDialog;
    private LoginHelper loginHelper;
    private SpecialRegisterController.CallBackEvent mCallBackEvent;
    private String email;
    private String password;
    private String recommendId;
    private String logoBase64;
    private String contentBase64_1;
    private String contentBase64_2;
    private String contentBase64_3;
    private ByteArrayOutputStream imageStream;

    public SpecialRegisterController(Context context) {
        this.context = context;
        accountInjection = new AccountInjection(context);
        serverInfoInjection = new ServerInfoInjection();
        loadingDialog = new LoadingDialog(context);
        loginErrorDialog = new LoginErrorDialog(context);
        loginHelper = new LoginHelper(context);
        apiParams = new ApiParams(serverInfoInjection, accountInjection);
        imageStream = new ByteArrayOutputStream();

        email = "";
        password = "";
        recommendId = "";
        logoBase64 = "";
        contentBase64_1 = "";
        contentBase64_2 = "";
        contentBase64_3 = "";
    }

    public void registerRequest(String name, int type, String address, String contact, int sex, String job) {
        ApiParams apiParams = new ApiParams(serverInfoInjection, accountInjection);

        apiParams.inputStoreName = name;
        apiParams.inputStoreType = String.valueOf(type).trim();
        apiParams.inputCategoryEmployment = job;
        apiParams.inputStoreAddress = address;
        apiParams.inputStoreUrl = "http://www.google.com".trim();//todo fix none url input
        apiParams.inputContactPersonSex = String.valueOf(sex).trim();
        apiParams.inputContactPerson = contact;
        apiParams.inputRegisterQrCode = recommendId;
        apiParams.inputLogoBase64 = logoBase64;
        apiParams.inputContentBase64_1 = contentBase64_1;
        apiParams.inputContentBase64_2 = contentBase64_2;
        apiParams.inputContentBase64_3 = contentBase64_3;
        Log.e("inputLogoBase64", apiParams.inputLogoBase64);
        Log.e("inputContentBase64_1", apiParams.inputContentBase64_1);
        Log.e("inputContentBase64_2", apiParams.inputContentBase64_2);
        Log.e("inputContentBase64_3", apiParams.inputContentBase64_3);

        loadingDialog.show();
        WebRequest<ApiV1SpecialRegisterPostData> request = new ApiV1SpecialRegisterPost<>(context, apiParams);
        request.processing(new WebRequest.Processing<ApiV1SpecialRegisterPostData>() {
            @Override
            public ApiV1SpecialRegisterPostData run(String data) {
                return new ApiV1SpecialRegisterPostData(data);
            }
        }).failProcess(new WebRequest.FailProcess<ApiV1SpecialRegisterPostData>() {
            @Override
            public void run(String data, ApiV1SpecialRegisterPostData information) {
                loadingDialog.dismiss();
                ErrorProcessingData.run(context, data, information);
                String content = context.getString(R.string.request_load_fail);
                Toast.makeText(context, content, Toast.LENGTH_LONG).show();
            }
        }).unknownFailRequest(new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                loadingDialog.dismiss();
                String content = context.getString(R.string.request_load_fail);
                Toast.makeText(context, content, Toast.LENGTH_LONG).show();
            }
        }).successProcess(new WebRequest.SuccessProcess<ApiV1SpecialRegisterPostData>() {
            @Override
            public void run(String data, ApiV1SpecialRegisterPostData information) {
                loadingDialog.dismiss();

                if (null != mCallBackEvent)
                    mCallBackEvent.onSuccess(information);
            }
        }).start();
    }

    public void syncRequest() {
        WebRequest<ApiV1UserDetailGetData> request = new ApiV1UserDetailGet<>(context, apiParams);
        request.processing(new WebRequest.Processing<ApiV1UserDetailGetData>() {
            @Override
            public ApiV1UserDetailGetData run(String data) {
                return new ApiV1UserDetailGetData(data);
            }
        }).failProcess(new WebRequest.FailProcess<ApiV1UserDetailGetData>() {
            @Override
            public void run(String data, ApiV1UserDetailGetData information) {
                loadingDialog.dismiss();
                ErrorProcessingData.run(context, data, information);
            }
        }).unknownFailRequest(new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                loadingDialog.dismiss();
                String fail = context.getString(R.string.request_load_fail);
                Toast.makeText(context, fail, Toast.LENGTH_LONG).show();
            }
        }).successProcess(new WebRequest.SuccessProcess<ApiV1UserDetailGetData>() {
            @Override
            public void run(String data, ApiV1UserDetailGetData information) {
                if (null != mCallBackEvent)
                    mCallBackEvent.onSuccess(information);
            }
        }).start();
    }

    public void loginRequest() {
        ApiParams apiParams = new ApiParams(serverInfoInjection, accountInjection);

        String account = accountInjection.loadAccount();
        String password = accountInjection.loadPassword();

        accountInjection.save(AccountConst.KEY_ACCOUNT, account);
        accountInjection.save(AccountConst.KEY_PASSWORD, password);
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
        loginHelper.saveAccount(email);
        loginHelper.savePassword(password);
        loginHelper.saveToken(token);
        loginHelper.saveRegisterState(registerState);
        loginHelper.saveAutoLogin("0");
        Bundle args = new Bundle();
        args.putString("recommendId", recommendId);
        args.putString("from", "special");
        Intent intent = new Intent(context, ActivityNormalAdvertisement.class);
        intent.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
        intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TASK);
        intent.putExtras(args);
        context.startActivity(intent);
        if (null != mCallBackEvent)
            mCallBackEvent.onSuccess();
    }


    public void setmCallBackEvent(SpecialRegisterController.CallBackEvent callBackEvent) {
        this.mCallBackEvent = callBackEvent;
    }


    public interface CallBackEvent {
        void onError();

        void onSuccess();

        void onSuccess(ApiV1UserDetailGetData information);

        void onSuccess(ApiV1SpecialRegisterPostData information);
    }

    public void setRegisterData(String email, String password, String recommendId) {
        this.email = email;
        this.password = password;
        this.recommendId = recommendId;
    }

    public void setLogoUriPath(ArrayList<String> logoUriPath) {
        ArrayList<Uri> selectUri = new ArrayList<>();
        for (int i = 0; i < logoUriPath.size(); i++) {
            selectUri.add(Uri.parse(logoUriPath.get(i)));
        }
        if (logoUriPath.size() > 0) {
            Bitmap logo = BitmapCompression.getBitmap(selectUri.get(0), context);
            logoBase64 = getBase64(logo);
            Log.e("logoBase64", logoBase64);
        }

    }

    public void setContentUriPath(ArrayList<String> contentUriPath) {
        ArrayList<Uri> selectUri = new ArrayList<>();
        for (int i = 0; i < contentUriPath.size(); i++) {
            selectUri.add(Uri.parse(contentUriPath.get(i)));
        }
        if (contentUriPath.size() > 0) {
            Bitmap b1 = BitmapCompression.getBitmap(selectUri.get(0), context);
            Bitmap b2 = BitmapCompression.getBitmap(selectUri.get(1), context);
            Bitmap b3 = BitmapCompression.getBitmap(selectUri.get(2), context);
            contentBase64_1 = getBase64(b1);
            contentBase64_2 = getBase64(b2);
            contentBase64_3 = getBase64(b3);
            Log.e("contentBase64_1", contentBase64_1);
            Log.e("contentBase64_2", contentBase64_2);
            Log.e("contentBase64_3", contentBase64_3);
        }

    }

    public String getBase64(Bitmap bitmap) {
//        byte[] toByteArray = imageStream.toByteArray();
//        String encodedImage = "";
//        ByteArrayOutputStream imageStream = new ByteArrayOutputStream();
//        bitmap.compress(Bitmap.CompressFormat.JPEG, 100, imageStream);
//
//        try {
//            System.gc();
//            encodedImage = Base64.encodeToString(toByteArray, Base64.DEFAULT);
//            return encodedImage;
//
//        } catch (Exception e) {
//            e.printStackTrace();
//        } catch (OutOfMemoryError e) {
//            imageStream = new ByteArrayOutputStream();
//            bitmap.compress(Bitmap.CompressFormat.JPEG, 50, imageStream);
//            toByteArray = imageStream.toByteArray();
//            encodedImage = Base64.encodeToString(toByteArray, Base64.DEFAULT);
//            Log.e("EWN", "Out of memory error catched");
//            return encodedImage;
//        }


        imageStream = new ByteArrayOutputStream();
        try {
            bitmap.compress(Bitmap.CompressFormat.JPEG, 100, imageStream);
            byte[] toByteArray = imageStream.toByteArray();
            byte[] encode = Base64.encode(toByteArray, Base64.DEFAULT);

            String encodedImage = new String(encode);
            return encodedImage;

        } catch (Exception e) {
            e.printStackTrace();
        }
        return "";

    }

}
