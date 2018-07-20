package com.androidlibrary.module.backend.data;

import android.util.Log;

import org.json.JSONException;
import org.json.JSONObject;

/**
 * Created by 依杰 on 2018/7/14.
 */

public class ApiV1NormalUserPointGetData extends JsonData {
    public String token;
    public String store;
    public int point;
    public String email;

    public ApiV1NormalUserPointGetData(String data) {
        super(data);
        Log.e("data", data);
    }

    @Override
    protected void processing(JSONObject json) {
        super.processing(json);
        email = getString(json, "email", "");
        try {
            point = json.getJSONObject("message").getInt("point");
            store = json.getJSONObject("message").getString("stor");
            token = json.getJSONObject("message").getString("token");
            Log.e("token", token + "token");
        } catch (JSONException e) {
            Log.e("Login ERROR", e.toString());
            e.printStackTrace();
        }
    }
}
