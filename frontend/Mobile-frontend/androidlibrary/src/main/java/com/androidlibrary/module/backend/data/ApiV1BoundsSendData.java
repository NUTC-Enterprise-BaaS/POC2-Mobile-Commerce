package com.androidlibrary.module.backend.data;

import android.util.Log;

import org.json.JSONObject;

import java.util.ArrayList;

/**
 * Created by ameng on 2016/6/2.
 */
public class ApiV1BoundsSendData extends JsonData {
    public int result;
    public ArrayList<String> messageGroup;

    public ApiV1BoundsSendData(String data) {
        super(data);
        Log.e("apidata", data);
    }

    @Override
    protected void processing(JSONObject json) {
        super.processing(json);
        result = getInt(json, "result", 0);
        messageGroup = getStringArray(json, "message", "");
    }
}
