package com.androidlibrary.module.backend.data;

import org.json.JSONObject;

import java.util.ArrayList;

/**
 * Created by 依杰 on 2016/10/27.
 */

public class ApiV1BerecommendGetData extends JsonData {
    public String type;
    public String point;
    public String data;
    public int id;
    public int result;
    public ArrayList<String> messageGroup;

    public ApiV1BerecommendGetData(String data) {
        super(data);
    }

    @Override
    protected void processing(JSONObject json) {
        super.processing(json);
        type = getString(json, "type", "");
        if (!type.equals("berecommend")) {
            return;
        }
        result = getInt(json, "result", 0);
        messageGroup = getStringArray(json, "message", "");
        if (result != 0) {
            return;
        }
        id = getInt(json, "id", 0);
        point = getString(json, "point", "");
        data = getString(json, "data", "");
    }
}
