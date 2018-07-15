package com.androidlibrary.module.backend.data;

import org.json.JSONObject;

/**
 * Created by 依杰 on 2018/7/14.
 */

public class ApiV1NormalConnectLdapPostData extends JsonData {
    public String token;

    public ApiV1NormalConnectLdapPostData(String data) {
        super(data);
    }

    @Override
    protected void processing(JSONObject json) {
        super.processing(json);
        token = getString(json, "token", "");
    }
}