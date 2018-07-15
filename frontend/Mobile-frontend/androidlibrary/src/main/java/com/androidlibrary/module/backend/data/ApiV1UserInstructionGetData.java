package com.androidlibrary.module.backend.data;

import android.util.Log;

import org.json.JSONObject;

import java.util.ArrayList;

/**
 * Created by Gary on 2016/6/3.
 */
public class ApiV1UserInstructionGetData extends JsonData {
    public int result;
    public ArrayList<String> messageGroup;
    public String url;

    public ApiV1UserInstructionGetData(String data) {
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
        url = getString(json, "url", "");

    }
}
