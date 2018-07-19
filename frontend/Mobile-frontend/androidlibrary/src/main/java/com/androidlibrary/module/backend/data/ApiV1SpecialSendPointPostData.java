package com.androidlibrary.module.backend.data;

import android.util.Log;

import org.json.JSONObject;

import java.util.ArrayList;

/**
 * Created by Gary on 2016/5/28.
 */
public class ApiV1SpecialSendPointPostData extends JsonData {
    public int result;
    public ArrayList<String> messageGroup;

    public ApiV1SpecialSendPointPostData(String data) {
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

    }
}
