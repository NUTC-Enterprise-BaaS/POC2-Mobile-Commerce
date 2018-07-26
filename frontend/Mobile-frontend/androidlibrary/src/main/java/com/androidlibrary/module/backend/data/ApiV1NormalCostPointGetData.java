package com.androidlibrary.module.backend.data;

import android.util.Log;

import org.json.JSONObject;

/**
 * Created by 依杰 on 2018/7/25.
 */

public class ApiV1NormalCostPointGetData extends JsonData {
    public int costs;

    public ApiV1NormalCostPointGetData(String data) {
        super(data);
        Log.e("BindingClear", "" + data);
    }

    @Override
    protected void processing(JSONObject json) {
        super.processing(json);
        costs = getInt(json, "costs", 0);
    }
}