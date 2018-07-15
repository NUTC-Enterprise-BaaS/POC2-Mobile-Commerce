package com.herbhousesgobuyother.service;

import android.app.IntentService;
import android.content.Intent;
import android.os.Binder;
import android.os.IBinder;
import android.support.annotation.NonNull;
import android.support.annotation.Nullable;
import android.util.Log;
import android.widget.Toast;

import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.androidlibrary.module.ApiParams;
import com.androidlibrary.module.backend.api.ApiV1PushRegisterPost;
import com.androidlibrary.module.backend.data.ApiV1PushRegisterPostData;
import com.androidlibrary.module.backend.params.AccountInjection;
import com.androidlibrary.module.backend.params.ServerInfoInjection;
import com.androidlibrary.module.backend.request.WebRequest;
import com.google.android.gms.gcm.GoogleCloudMessaging;
import com.google.android.gms.iid.InstanceID;
import com.herbhousesgobuyother.R;

import java.io.IOException;

/**
 * Created by ameng on 2016/6/29.
 */
public class RegistrationIntentService extends IntentService {
    public static final String SERVICE_NAME = RegistrationIntentService.class.getName();
    public static String GCM_TOKEN = "";
    private ApiParams apiParams;
    private ServerInfoInjection serverInfoInjection;
    private AccountInjection accountInjection;
    private int reTry = 0;
    private int reTryMax = 3;
    private DeviceTokenListener deviceTokenListener;
    private MyBinder myBinder = new MyBinder();

    public static interface DeviceTokenListener {
        void deveiceTokenResult(final boolean result);
    }

    public RegistrationIntentService() {
        super(SERVICE_NAME);
    }

    @Override
    protected void onHandleIntent(Intent intent) {
        InstanceID instanceID = InstanceID.getInstance(this);
        try {
            GCM_TOKEN = instanceID.getToken(getString(R.string.gcm_defaultSenderId),
                    GoogleCloudMessaging.INSTANCE_ID_SCOPE, null);
            registeToServer();
            Log.e("Device Token", GCM_TOKEN);
        } catch (IOException e) {
            e.printStackTrace();
            Log.e("IOException", e.toString());
        }
    }

    private void registeToServer() {
        serverInfoInjection = new ServerInfoInjection();
        accountInjection = new AccountInjection(this);
        apiParams = new ApiParams(serverInfoInjection, accountInjection);
        apiParams.inputInstanceId = GCM_TOKEN;
        apiParams.inputdevice = "0";

        WebRequest<ApiV1PushRegisterPostData> request = new ApiV1PushRegisterPost<>(RegistrationIntentService.this, apiParams);
        request.processing(new WebRequest.Processing<ApiV1PushRegisterPostData>() {
            @Override
            public ApiV1PushRegisterPostData run(String data) {

                return new ApiV1PushRegisterPostData(data);
            }
        }).failProcess(new WebRequest.FailProcess<ApiV1PushRegisterPostData>() {
            @Override
            public void run(String data, ApiV1PushRegisterPostData information) {
            }
        }).unknownFailRequest(new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                String content = getString(R.string.request_load_fail);
                Toast.makeText(RegistrationIntentService.this, content, Toast.LENGTH_LONG).show();
                if (reTry == reTryMax) {
                    return;
                } else {
                    reTry++;
                    registeToServer();
                }
            }
        }).successProcess(new WebRequest.SuccessProcess<ApiV1PushRegisterPostData>() {
            @Override
            public void run(String data, ApiV1PushRegisterPostData information) {
                Log.e("device", "succ");
                deviceTokenListener.deveiceTokenResult(true);
            }
        }).start();
    }

    public void setDeviceTokenListener(@NonNull RegistrationIntentService.DeviceTokenListener event) {
        this.deviceTokenListener = event;
    }

    @Nullable
    @Override
    public IBinder onBind(Intent intent) {
        return myBinder;

    }

    public class MyBinder extends Binder {
        public IntentService getIntentService() {
            return RegistrationIntentService.this;
        }
    }
}
