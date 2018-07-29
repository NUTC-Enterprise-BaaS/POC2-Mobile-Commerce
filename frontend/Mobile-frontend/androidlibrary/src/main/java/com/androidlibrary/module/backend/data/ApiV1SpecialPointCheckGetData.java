package com.androidlibrary.module.backend.data;

import org.json.JSONObject;

import java.util.ArrayList;

/**
 * Created by ameng on 2016/6/5.
 */
public class ApiV1SpecialPointCheckGetData extends JsonData {
    public int result;
    public ArrayList<String> meaageGroup;

    public ApiV1SpecialPointCheckGetData(String data) {
        super(data);
    }

    @Override
    protected void processing(JSONObject json) {
        result = getInt(json, "result", 0);
        meaageGroup = getStringArray(json, "message", "");
        if (result != 0) {
            return;
        }
    }

}
