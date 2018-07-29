package com.androidlibrary.module.backend.data;

import org.json.JSONObject;

import java.util.ArrayList;

/**
 * Created by 依杰 on 2016/10/17.
 */

public class ApiV1SpecialQrCodeShowGetData extends JsonData {
    public int result;
    public ArrayList<String> messageGroup;
    public String url;

    public ApiV1SpecialQrCodeShowGetData(String data) {
        super(data);
    }

    @Override
    protected void processing(JSONObject json) {
        super.processing(json);
        result = getInt(json, "result", 0);
        messageGroup = getStringArray(json, "message", "");
        url= getString(json, "url", "");
        if (result != 0) {
            return;
        }
    }
}
