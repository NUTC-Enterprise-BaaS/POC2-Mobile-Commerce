package com.androidlibrary.module.backend.data;

import android.util.Log;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;

/**
 * Created by 依杰 on 2018/7/14.
 */

public class ApiV1NormalStoreListGetData extends JsonData {
    public String status;
    public ArrayList<String> storeNameGroup;
    public ArrayList<String> userNameGroup;
    public String message;
    public ApiV1NormalStoreListGetData(String data) {
        super(data);
        Log.e("StoreListGetData", data);
    }

    @Override
    protected void processing(JSONObject json) {
        super.processing(json);
        message = getString(json, "list", "");

        JSONArray itemsArray;
        storeNameGroup = new ArrayList<>();
        userNameGroup = new ArrayList<>();
        try {
            status = json.getJSONObject("list").getString("status");
            itemsArray = json.getJSONObject("list").getJSONArray("list");

            iteration(itemsArray, new OnObjectIteration() {
                @Override
                public void get(int index, JSONObject object) {
                    String storeName = getString(object, "stor", "");
                    String userName = getString(object, "username", "");
                    storeNameGroup.add(storeName);
                    userNameGroup.add(userName);
                }
            });
        } catch (JSONException e) {
            Log.e("Login ERROR", e.toString());
            e.printStackTrace();
        }
    }
}