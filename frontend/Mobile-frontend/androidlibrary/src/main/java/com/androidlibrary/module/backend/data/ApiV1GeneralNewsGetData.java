package com.androidlibrary.module.backend.data;


import org.json.JSONArray;
import org.json.JSONObject;

import java.util.ArrayList;

/**
 * Created by Gary on 2016/5/25.
 */
public class ApiV1GeneralNewsGetData extends JsonData {
    public int result;
    public int sum;
    public ArrayList<String> messageGroup;
    public ArrayList<Integer> idGroup;
    public ArrayList<String> titleGroup;
    public ArrayList<Long> timestampGroup;

    public ApiV1GeneralNewsGetData(String data) {
        super(data);
    }

    @Override
    protected void processing(JSONObject json) {
        super.processing(json);
        result = getInt(json, "result", 0);
        messageGroup = getStringArray(json, "message", "");

        JSONArray itemsArray;
        if (result != 0) {
            return;
        }
        sum = getInt(json, "news_sum", 0);
        idGroup = new ArrayList<>();
        titleGroup = new ArrayList<>();
        timestampGroup = new ArrayList<>();
        itemsArray = getJSONArray(json, "items");

        iteration(itemsArray, new OnObjectIteration() {
            @Override
            public void get(int index, JSONObject object) {
                int id = getInt(object, "id", 0);
                String title = getString(object, "title", "標題");
                Long timestamp = getLong(object, "date", 0) * 1000;
                idGroup.add(id);
                titleGroup.add(title);
                timestampGroup.add(timestamp);
            }
        });
    }
}