package com.androidlibrary.module.backend.data;

import org.json.JSONObject;

import java.util.ArrayList;

/**
 * Created by 依杰 on 2016/7/7.
 */
public class ApiV1UserLuckySendPostData extends JsonData {
    public int result;
    public ArrayList<String> messageGroup;

    public ApiV1UserLuckySendPostData(String data) {
        super(data);
    }

    @Override
    protected void processing(JSONObject json) {
        result = getInt(json, "result", 0);
        messageGroup = getStringArray(json, "message", "");
    }

}