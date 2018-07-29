package com.androidlibrary.module.backend.data;

import android.util.Log;

import org.json.JSONObject;

/**
 * Created by 依杰 on 2018/7/17.
 */

public class ApiV1NormalSyncPointGetData extends JsonData {

    public ApiV1NormalSyncPointGetData(String data) {
        super(data);
        Log.e("data", data);
    }

    @Override
    protected void processing(JSONObject json) {
        super.processing(json);
    }
}