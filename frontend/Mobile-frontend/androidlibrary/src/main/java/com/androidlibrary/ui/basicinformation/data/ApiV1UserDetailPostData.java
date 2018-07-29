package com.androidlibrary.ui.basicinformation.data;

import android.util.Log;

import com.androidlibrary.module.backend.data.JsonData;

import org.json.JSONObject;

import java.util.ArrayList;

/**
 * Created by ameng on 2016/6/13.
 */
public class ApiV1UserDetailPostData extends JsonData {
    public int result;
    public ArrayList<String> messageGroup;

    public ApiV1UserDetailPostData(String data) {
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
