package com.androidlibrary.module.backend.data;

import org.json.JSONObject;

import java.util.ArrayList;

/**
 * Created by ameng on 7/13/16.
 */
public class ApiV1PushUnregisterData extends JsonData {
    public int result;
    public ArrayList<String> messageGroup;

    public ApiV1PushUnregisterData(String data) {
        super(data);
    }

    @Override
    protected void processing(JSONObject json) {
        super.processing(json);
        result = getInt(json, "result", 0);
    }
}
