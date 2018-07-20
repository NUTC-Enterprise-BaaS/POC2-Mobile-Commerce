package com.androidlibrary.module.backend.data;


import android.util.Log;

import org.json.JSONObject;

import java.util.ArrayList;

/**
 * Created by Gary on 2016/5/25.
 */
public class ApiV1RecommendShowGetData extends JsonData {
    public int result;
    public ArrayList<String> messageGroup;
    public ArrayList<Integer> idGroup;
    public String general;
    public String special;
    public String premium;

    public ApiV1RecommendShowGetData(String data) {
        super(data);
        Log.e("data", data);
    }

    @Override
    protected void processing(JSONObject json) {
        super.processing(json);
        result = getInt(json, "result", 0);
        messageGroup = getStringArray(json, "message", "");

        if (result != 0) {
            return;
        }

        general = getString(json, "general", "");
        special = getString(json, "special", "");
        premium = getString(json, "premium", "");
    }
}