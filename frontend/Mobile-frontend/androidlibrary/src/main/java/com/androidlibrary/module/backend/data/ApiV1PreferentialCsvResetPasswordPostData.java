package com.androidlibrary.module.backend.data;

import org.json.JSONObject;

import java.util.ArrayList;

/**
 * Created by on 2016/6/28.
 */
public class ApiV1PreferentialCsvResetPasswordPostData extends JsonData {
    public int result;
    public ArrayList<String> messageGroup;

    public ApiV1PreferentialCsvResetPasswordPostData(String data) {
        super(data);
    }

    @Override
    protected void processing(JSONObject json) {
        super.processing(json);
        result = getInt(json, "result", 0);
        messageGroup = getStringArray(json, "message", "");
    }
}
