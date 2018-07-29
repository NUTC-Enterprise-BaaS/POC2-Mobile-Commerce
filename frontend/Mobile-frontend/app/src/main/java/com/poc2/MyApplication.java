package com.poc2;

import android.app.Application;
import android.content.Context;

/**
 * Created by 依杰 on 2018/7/20.
 */

public class MyApplication extends Application {

    private static Context context;

    public void onCreate() {
        super.onCreate();
        MyApplication.context = getApplicationContext();
    }

    public static Context getAppContext() {
        return MyApplication.context;
    }
}