package com.androidlibrary.module.backend.data;


import org.json.JSONObject;

import java.util.ArrayList;

/**
 * Created by Gary on 2016/5/26.
 */
public class ApiV1GeneralPhoneSendPointPostData extends JsonData {
    public int result;
    public ArrayList<String> messageGroup;

    public ApiV1GeneralPhoneSendPointPostData(String data) {
        super(data);
    }

    @Override
    protected void processing(JSONObject json) {
        super.processing(json);
        result = getInt(json, "result", 0);
        messageGroup = getStringArray(json, "message", "");

        if (result != 0) {
            return;
        }


    }
}
