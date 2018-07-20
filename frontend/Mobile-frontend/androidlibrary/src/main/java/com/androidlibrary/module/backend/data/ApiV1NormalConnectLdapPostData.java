package com.androidlibrary.module.backend.data;

import android.util.Log;

import org.json.JSONException;
import org.json.JSONObject;

/**
 * Created by 依杰 on 2018/7/14.
 */

public class ApiV1NormalConnectLdapPostData extends JsonData {
    public String message;

    public ApiV1NormalConnectLdapPostData(String data) {
        super(data);
        Log.e("LdapPostData", "" + data);
    }

    @Override
    protected void processing(JSONObject json) {
        super.processing(json);
        try {
            message = json.getJSONObject("message").getString("message");
        } catch (JSONException e) {
            Log.e("Check Version ERROR", e.toString());
            e.printStackTrace();
        }
    }
}