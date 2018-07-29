package com.androidlibrary.module.backend.data;

import android.util.Log;

import org.json.JSONObject;

/**
 * Created by 依杰 on 2018/7/20.
 */

public class ApiV1NormalBindingClearGetData extends JsonData {
    public String message;

    public ApiV1NormalBindingClearGetData(String data) {
        super(data);
        Log.e("BindingClear", "" + data);
    }

    @Override
    protected void processing(JSONObject json) {
        super.processing(json);
        message = getString(json, "message", "");
    }
}