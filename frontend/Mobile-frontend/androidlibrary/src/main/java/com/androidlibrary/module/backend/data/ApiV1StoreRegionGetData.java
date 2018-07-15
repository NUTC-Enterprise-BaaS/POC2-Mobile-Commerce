package com.androidlibrary.module.backend.data;

import org.json.JSONArray;
import org.json.JSONObject;

import java.util.ArrayList;

/**
 * Created by ameng on 2016/6/5.
 */
public class ApiV1StoreRegionGetData extends JsonData {
    public int result;
    public ArrayList<String> meaageGroup;
    public ArrayList<String> regionIdGroup;
    public ArrayList<String> regionNameGroup;

    public ApiV1StoreRegionGetData(String data) {
        super(data);
    }

    @Override
    protected void processing(JSONObject json) {
        result = getInt(json, "result", 0);
        meaageGroup = getStringArray(json, "message", "");
        regionIdGroup = new ArrayList<>();
        regionNameGroup = new ArrayList<>();

        if (meaageGroup.get(0).toString().equals("No such data input error")) {
            return;
        }
        JSONArray regionGroup = getJSONArray(json, "regions");

        iteration(regionGroup, new OnObjectIteration() {
            @Override
            public void get(int index, JSONObject object) {
                regionIdGroup.add(getString(object, "region_id", ""));
                regionNameGroup.add(getString(object, "region_name", ""));
            }
        });
    }
}
