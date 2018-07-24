package com.androidlibrary.module.backend.data;

import org.json.JSONObject;

import java.util.ArrayList;

/**
 * Created by on 2016/6/27.
 */
public class ApiV1SpecialVerifyCodePostData extends JsonData {
    public int result;
    public ArrayList<String> messageGroup;
    public String verifyCodeGroup;
    public int idGroup;

    public ApiV1SpecialVerifyCodePostData(String data) {
        super(data);
    }

    @Override
    protected void processing(JSONObject json) {
        super.processing(json);
        result = getInt(json, "result", 0);
        messageGroup = getStringArray(json, "message", "");
        verifyCodeGroup = getString(json, "verify_code_url", "");
        idGroup = getInt(json, "id", 0);
    }
}
