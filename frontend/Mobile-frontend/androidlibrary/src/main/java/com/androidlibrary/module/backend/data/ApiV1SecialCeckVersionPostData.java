package com.androidlibrary.module.backend.data;

import android.util.Log;

import org.json.JSONException;
import org.json.JSONObject;

/**
 * Created by ameng on 2016/6/21.
 */
public class ApiV1SecialCeckVersionPostData extends JsonData {
    public int result;
    public String messageGroup;

    public ApiV1SecialCeckVersionPostData(String data) {
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
