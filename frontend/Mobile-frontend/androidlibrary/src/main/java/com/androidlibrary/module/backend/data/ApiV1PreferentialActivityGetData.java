package com.androidlibrary.module.backend.data;


import org.json.JSONArray;
import org.json.JSONObject;

import java.util.ArrayList;

/**
 * Created by Gary on 2016/5/25.
 */
public class ApiV1PreferentialActivityGetData extends JsonData {
    public int result;
    public int sum;
    public ArrayList<String> messageGroup;
    public ArrayList<String> titleGroup;
    public ArrayList<Long> timestampGroup;
    public ArrayList<String> urlGroup;

    public ApiV1PreferentialActivityGetData(String data) {
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
        sum = getInt(json, "sum", 0);
        urlGroup = new ArrayList<>();
        titleGroup = new ArrayList<>();
        timestampGroup = new ArrayList<>();
        itemsArray = getJSONArray(json, "items");

        iteration(itemsArray, new OnObjectIteration() {
            @Override
            public void get(int index, JSONObject object) {
                String title = getString(object, "title", "");
                Long timestamp = getLong(object, "date", 0) * 1000;
                String url = getString(object, "url", "");

                urlGroup.add(url);
                titleGroup.add(title);
                timestampGroup.add(timestamp);
            }
        });
    }
}