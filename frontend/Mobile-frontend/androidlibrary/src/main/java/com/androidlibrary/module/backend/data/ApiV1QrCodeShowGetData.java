package com.androidlibrary.module.backend.data;


import android.util.Log;

import org.json.JSONObject;

import java.util.ArrayList;

/**
 * Created by Gary on 2016/5/25.
 */
public class ApiV1QrCodeShowGetData extends JsonData {
    public int result;
    public ArrayList<String> messageGroup;
    public String url;

    public ApiV1QrCodeShowGetData(String data) {
        super(data);
        Log.e("data", data);
    }

    @Override
    protected void processing(JSONObject json) {
        super.processing(json);
        result = getInt(json, "result", 0);
        messageGroup = getStringArray(json, "message", "");
        url = getString(json, "url", "");
        if (result != 0) {
            return;
        }
    }
}