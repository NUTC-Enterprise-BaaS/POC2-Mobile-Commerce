package com.androidlibrary.module.backend.data;

import android.util.Log;

import org.json.JSONObject;

import java.util.ArrayList;

/**
 * Created by ameng on 2016/5/23.
 */
public class ApiV1SpecialRecommendSetData extends JsonData {
    public int result;
    public ArrayList<String> messageGroup;

    public ApiV1SpecialRecommendSetData(String data) {
        super(data);
        Log.e("RecommendSet", data);
    }

    @Override
    protected void processing(JSONObject json) {
        super.processing(json);
        result = getInt(json, "result", 0);
        if (result != 0) {
            return;
        }
        messageGroup = getStringArray(json, "message", "");
    }
}
