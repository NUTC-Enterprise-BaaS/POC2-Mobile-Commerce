package com.androidlibrary.module.backend.data;

import android.util.Log;

import org.json.JSONException;
import org.json.JSONObject;

/**
 * Created by 依杰 on 2018/7/14.
 */

public class ApiV1NormalStoreCreateGetData extends JsonData {
    public int rate;

    public ApiV1NormalStoreCreateGetData(String data) {
        super(data);
        Log.e("data", data);
    }

    @Override
    protected void processing(JSONObject json) {
        super.processing(json);
        try {
            rate = json.getJSONObject("message").getJSONObject("message").getInt("rate");
            Log.e("token", rate + "rate");
        } catch (JSONException e) {
            Log.e("Login ERROR", e.toString());
            e.printStackTrace();
        }
    }
}
