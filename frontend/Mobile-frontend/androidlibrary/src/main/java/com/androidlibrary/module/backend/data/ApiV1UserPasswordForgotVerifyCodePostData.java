package com.androidlibrary.module.backend.data;

import android.util.Log;

import org.json.JSONObject;

import java.util.ArrayList;

/**
 * Created by ameng on 2016/6/14.
 */
public class ApiV1UserPasswordForgotVerifyCodePostData extends JsonData {
    public int result;
    public ArrayList<String> messageGroup;

    public ApiV1UserPasswordForgotVerifyCodePostData(String data) {
        super(data);
        Log.e("data", data);
    }

    @Override
    protected void processing(JSONObject json) {
        super.processing(json);
        result = getInt(json, "result", 0);
        messageGroup = getStringArray(json, "message", "");
    }
}
