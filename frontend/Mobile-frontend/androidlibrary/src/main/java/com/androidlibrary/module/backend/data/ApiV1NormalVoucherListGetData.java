package com.androidlibrary.module.backend.data;

import android.util.Log;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;

/**
 * Created by 依杰 on 2018/7/18.
 */

public class ApiV1NormalVoucherListGetData extends JsonData {
    public ArrayList<String> storeNameGroup;
    public ArrayList<String> idGroup;

    public ApiV1NormalVoucherListGetData(String data) {
        super(data);
        Log.e("data", data);
    }

    @Override
    protected void processing(JSONObject json) {
        super.processing(json);
        JSONArray itemsArray;
        storeNameGroup = new ArrayList<>();
        idGroup = new ArrayList<>();
        try {
            itemsArray = json.getJSONArray("message");

            iteration(itemsArray, new OnObjectIteration() {
                @Override
                public void get(int index, JSONObject object) {
                    String id = getString(object, "id", "");
                    String storeName = getString(object, "voucher_name", "");
                    storeNameGroup.add(storeName);
                    idGroup.add(id);
                }
            });
        } catch (JSONException e) {
            Log.e("Login ERROR", e.toString());
            e.printStackTrace();
        }
    }
}