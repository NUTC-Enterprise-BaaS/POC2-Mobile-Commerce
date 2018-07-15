package com.androidlibrary.module.backend.data;


import org.json.JSONArray;
import org.json.JSONObject;

import java.util.ArrayList;

/**
 * Created by Gary on 2016/5/25.
 */
public class ApiV1AdveriseShowGetData extends JsonData {
    public int result;
    public ArrayList<String> messageGroup;
    public ArrayList<Integer> idGroup;
    public ArrayList<String> urlGroup;

    public ApiV1AdveriseShowGetData(String data) {
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

        idGroup = new ArrayList<>();
        urlGroup = new ArrayList<>();
        itemsArray = getJSONArray(json, "item");

        iteration(itemsArray, new OnObjectIteration() {
            @Override
            public void get(int index, JSONObject object) {
                int id = getInt(object, "advertise_id", 0);
                String url = getString(object, "advertise_url", "");
                idGroup.add(id);
                urlGroup.add(url);
            }
        });
    }
}