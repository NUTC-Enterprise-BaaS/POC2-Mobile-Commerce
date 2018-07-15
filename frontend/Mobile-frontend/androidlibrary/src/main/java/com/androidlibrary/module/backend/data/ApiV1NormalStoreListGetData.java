package com.androidlibrary.module.backend.data;

import android.util.Log;

import org.json.JSONObject;

import java.util.ArrayList;

/**
 * Created by 依杰 on 2018/7/14.
 */

public class ApiV1NormalStoreListGetData extends JsonData {
    public ArrayList<String> messageGroup;

    public ApiV1NormalStoreListGetData(String data) {
        super(data);
        Log.e("data", data);
    }

    @Override
    protected void processing(JSONObject json) {
        super.processing(json);
        messageGroup = getStringArray(json, "stor", "");
    }
}