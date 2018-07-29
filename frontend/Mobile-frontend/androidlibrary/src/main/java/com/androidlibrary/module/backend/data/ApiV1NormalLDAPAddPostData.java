package com.androidlibrary.module.backend.data;

import android.util.Log;

import org.json.JSONObject;

/**
 * Created by 依杰 on 2018/7/25.
 */

public class ApiV1NormalLDAPAddPostData extends JsonData {

    public ApiV1NormalLDAPAddPostData(String data) {
        super(data);
        Log.e("LDAPAddPostData", "" + data);
    }

    @Override
    protected void processing(JSONObject json) {
        super.processing(json);
    }
}