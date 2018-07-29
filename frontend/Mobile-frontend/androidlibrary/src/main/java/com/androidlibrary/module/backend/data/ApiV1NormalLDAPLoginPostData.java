package com.androidlibrary.module.backend.data;

import android.util.Log;

import org.json.JSONObject;

/**
 * Created by 依杰 on 2018/7/25.
 */

public class ApiV1NormalLDAPLoginPostData extends JsonData {
    public String status;
    public String token;

    public ApiV1NormalLDAPLoginPostData(String data) {
        super(data);
        Log.e("LDAPLoginPostData", "" +data );
    }

    @Override
    protected void processing(JSONObject json) {
        super.processing(json);
        status = getString(json, "email", "");
        token = getString(json, "token", "");
    }
}
