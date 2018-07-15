package com.androidlibrary.module.backend.data;

import android.util.Log;

import org.json.JSONException;
import org.json.JSONObject;

/**
 * Created by 依杰 on 2016/8/16.
 */
public class ApiV1NormalPointReceivePostData extends JsonData {
    public int result;
    public String messageGroup;

    public ApiV1NormalPointReceivePostData(String data) {
        super(data);
    }

    @Override
    protected void processing(JSONObject json) {
        super.processing(json);
        result = getInt(json, "result", 0);
        try {
            messageGroup = json.get("message").toString();
        } catch (JSONException e) {
            Log.e("Check Version ERROR", e.toString());
            e.printStackTrace();
        }
    }
}
