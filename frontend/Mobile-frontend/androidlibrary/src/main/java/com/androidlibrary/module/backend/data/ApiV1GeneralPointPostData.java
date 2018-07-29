package com.androidlibrary.module.backend.data;


import android.util.Log;

import org.json.JSONArray;
import org.json.JSONObject;

import java.util.ArrayList;

/**
 * Created by Gary on 2016/5/26.
 */
public class ApiV1GeneralPointPostData extends JsonData {
    public int result;
    public int point;
    public int cost;
    public ArrayList<String> messageGroup;
    public ArrayList<Integer> idGroup;
    public ArrayList<String> storeNameGroup;
    public ArrayList<Long> timestampGroup;
    public ArrayList<String> stateGroup;
    public ArrayList<Integer> bonusGroup;

    public ApiV1GeneralPointPostData(String data) {
        super(data);
        Log.e("data", data);
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

        point = getInt(json, "points", 0);
        cost = getInt(json, "cost", 0);
        idGroup = new ArrayList<>();
        storeNameGroup = new ArrayList<>();
        timestampGroup = new ArrayList<>();
        bonusGroup = new ArrayList<>();
        itemsArray = getJSONArray(json, "items");
        Log.e("SIZE", itemsArray.length() + "");
        iteration(itemsArray, new OnObjectIteration() {
            @Override
            public void get(int index, JSONObject object) {
                int id = getInt(object, "id", 0);
                String storeName = getString(object, "location", "店家名稱");
                Long timestamp = getLong(object, "timestamp", 0);
                int bonus = getInt(object, "bonus_point", 0);
                idGroup.add(id);
                storeNameGroup.add(storeName);
                timestampGroup.add(timestamp);
                bonusGroup.add(bonus);
            }
        });

    }
}
