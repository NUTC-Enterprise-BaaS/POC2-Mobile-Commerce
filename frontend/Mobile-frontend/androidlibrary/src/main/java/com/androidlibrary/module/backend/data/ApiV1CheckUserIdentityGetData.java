package com.androidlibrary.module.backend.data;

import android.util.Log;

import org.json.JSONObject;

import java.util.ArrayList;

public class ApiV1CheckUserIdentityGetData extends JsonData {
    public int result;
    public ArrayList<String> messageGroup;
    public String special;
    public String preferential;

    public ApiV1CheckUserIdentityGetData(String data) {
        super(data);
        Log.e("data", data);
    }

    @Override
    protected void processing(JSONObject json) {
        super.processing(json);
        result = getInt(json, "result", 0);
        messageGroup = getStringArray(json, "message", "");
        if (result != 0) {
            return;
        }
        special = getString(json, "special", "");
        preferential = getString(json, "preferential", "");
    }
}