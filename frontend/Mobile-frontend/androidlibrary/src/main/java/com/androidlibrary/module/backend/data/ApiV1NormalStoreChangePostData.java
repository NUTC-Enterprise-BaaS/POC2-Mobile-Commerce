package com.androidlibrary.module.backend.data;

import android.util.Log;

import org.json.JSONException;
import org.json.JSONObject;

/**
 * Created by 依杰 on 2018/7/14.
 */

public class ApiV1NormalStoreChangePostData extends JsonData {
    public String status;
    public String url;

    public ApiV1NormalStoreChangePostData(String data) {
        super(data);
        Log.e("StoreChangePostData", data);
    }

    @Override
    protected void processing(JSONObject json) {
        super.processing(json);
        try {
            status = json.getJSONObject("message").getString("status");
            url = json.getJSONObject("message").getString("notice");
            Log.e("token", status);
        } catch (JSONException e) {
            Log.e("Login ERROR", e.toString());
            e.printStackTrace();
        }
    }
}