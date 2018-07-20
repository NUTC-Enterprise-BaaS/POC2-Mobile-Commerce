package com.androidlibrary.module.backend.data;

import android.util.Log;

import org.json.JSONObject;

import java.util.ArrayList;

/**
 * Created by ameng on 7/10/16.
 */
public class ApiV1PushRegisterPostData extends JsonData {
    public int result;
    public ArrayList<String> messageGroup;

    public ApiV1PushRegisterPostData(String data) {
        super(data);
    }

    @Override
    protected void processing(JSONObject json) {
        super.processing(json);
        result = getInt(json, "result", 0);
        messageGroup = getStringArray(json, "message", "");
        if (result == 0) {
            Log.e("Register", "OK");
        }
        if (result != 0) {
            return;
        }
    }
}
