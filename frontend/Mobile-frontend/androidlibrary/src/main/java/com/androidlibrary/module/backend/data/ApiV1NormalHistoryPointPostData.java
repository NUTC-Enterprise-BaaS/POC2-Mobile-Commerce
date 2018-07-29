package com.androidlibrary.module.backend.data;

import android.util.Log;

import org.json.JSONArray;
import org.json.JSONObject;

import java.util.ArrayList;

/**
 * Created by 依杰 on 2018/7/25.
 */

public class ApiV1NormalHistoryPointPostData extends JsonData {
    public ArrayList<Integer> idGroup;
    public ArrayList<String> storeNameGroup;
    public ArrayList<Long> timestampGroup;
    public ArrayList<Integer> bonusGroup;

    public ApiV1NormalHistoryPointPostData(String data) {
        super(data);
        Log.e("HistoryPointPostData", data);
    }

    @Override
    protected void processing(JSONObject json) {
        super.processing(json);

        JSONArray itemsArray;
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
