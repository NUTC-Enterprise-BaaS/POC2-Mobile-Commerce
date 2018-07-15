package com.herbhousesgobuyother.contrube.service;

import android.app.Service;
import android.content.Intent;
import android.os.IBinder;
import android.util.Log;

import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.androidlibrary.module.ApiParams;
import com.androidlibrary.module.backend.api.ApiV1LoginPost;
import com.androidlibrary.module.backend.data.ApiV1LoginPostData;
import com.androidlibrary.module.backend.params.AccountInjection;
import com.androidlibrary.module.backend.params.ServerInfoInjection;
import com.androidlibrary.module.backend.request.WebRequest;
import com.androidlibrary.module.consts.AccountConst;

import java.util.Timer;
import java.util.TimerTask;

public class TokenGetService extends Service {
    private AccountInjection accountInjection;
    private ServerInfoInjection serverInfoInjection;

    @Override
    public IBinder onBind(Intent intent) {
        return null;
    }

    @Override
    public int onStartCommand(Intent intent, int flags, int startId) {

        accountInjection = new AccountInjection(this);
        serverInfoInjection = new ServerInfoInjection();

        // 設定每次時間觸發時執行的動作，將這些動作包成物件，放進 TimerTask 型態的參考中。
        TimerTask action = new TimerTask() {
            @Override
            public void run() {
                requestLogin();
            }
        };

        // 將定時器物件建立出來。
        Timer timer = new Timer();
        // 利用 schedule() 方法，將執行動作、延遲時間(1秒)、間隔時間(1秒) 輸入方法中。
        // 執行此方法後將會定時執行動作。
        timer.schedule(action, 0, 2400000);


        return super.onStartCommand(intent, flags, startId);
    }

    private void requestLogin() {
        String account = accountInjection.loadAccount();
        String password = accountInjection.loadPassword();

        ApiParams apiParams = new ApiParams(serverInfoInjection, accountInjection);
        apiParams.inputEmail = account;
        apiParams.inputPassword = password;

        WebRequest<ApiV1LoginPostData> request = new ApiV1LoginPost<>(this, apiParams);
        request.processing(new WebRequest.Processing<ApiV1LoginPostData>() {
            @Override
            public ApiV1LoginPostData run(String data) {
                return new ApiV1LoginPostData(data);
            }
        }).failProcess(new WebRequest.FailBackgroundProcess<ApiV1LoginPostData>() {
            @Override
            public void run(String data, ApiV1LoginPostData information) {
            }
        }).unknownFailRequest(new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
            }
        }).successProcess(new WebRequest.SuccessBackgroundProcess<ApiV1LoginPostData>() {
            @Override
            public void run(String data, ApiV1LoginPostData information) {
                successLogin(information);
            }
        }).start();
    }

    private void successLogin(ApiV1LoginPostData information) {
        String token = "bearer " + information.token;

        accountInjection.save(AccountConst.KEY_IS_LOGIN, true);
        accountInjection.save(AccountConst.KEY_TOKEN, token);
        accountInjection.save(AccountConst.KEY_IS_KEEP_LOGIN, true);
        accountInjection.save(AccountConst.Key_REGISTER_STATE, information.registeredState);

        Log.e("token", token);
    }
}