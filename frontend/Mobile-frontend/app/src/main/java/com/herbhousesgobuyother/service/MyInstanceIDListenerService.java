package com.herbhousesgobuyother.service;

import android.content.Intent;

import com.google.android.gms.iid.InstanceIDListenerService;

/**
 * Created by ameng on 2016/7/2.
 */
public class MyInstanceIDListenerService extends InstanceIDListenerService {
    public static final String NAME = MyInstanceIDListenerService.class.getName();

    public MyInstanceIDListenerService() {
    }

    @Override
    public void onTokenRefresh() {
        Intent intent = new Intent(this, RegistrationIntentService.class);
        startService(intent);
    }
}
