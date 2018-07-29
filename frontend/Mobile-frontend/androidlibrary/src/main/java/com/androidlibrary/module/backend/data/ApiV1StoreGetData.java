package com.androidlibrary.module.backend.data;

import android.util.Log;

import org.json.JSONArray;
import org.json.JSONObject;

import java.util.ArrayList;

/**
 * Created by ameng on 2016/6/5.
 */
public class ApiV1StoreGetData extends JsonData {
    public int result;
    public int sum;
    public ArrayList<String> meaageGroup;
    public ArrayList<String> shopIdGroup;
    public ArrayList<String> shopNameGroup;
    public ArrayList<String> shopPhotoGroup;
    public ArrayList<String> shopPhoneGroup;
    public ArrayList<String> shopAddressGroup;
    public ArrayList<String> shopUrlGroup;
    public ArrayList<String> shopLikeGroup;
    public ArrayList<String> shopKmGroup;

    public ApiV1StoreGetData(String data) {
        super(data);
        Log.e("data", data);
    }

    @Override
    protected void processing(JSONObject json) {
        result = getInt(json, "result", 0);
        meaageGroup = getStringArray(json, "message", "");
        shopIdGroup = new ArrayList<>();
        shopNameGroup = new ArrayList<>();
        shopPhotoGroup = new ArrayList<>();
        shopPhoneGroup = new ArrayList<>();
        shopAddressGroup = new ArrayList<>();
        shopUrlGroup = new ArrayList<>();
        shopLikeGroup = new ArrayList<>();
        shopKmGroup = new ArrayList<>();

        if (meaageGroup.get(0).toString().equals("No such data input error")) {
            return;
        }
        sum = getInt(json, "shop_sum", 0);
        JSONArray shopGroup = getJSONArray(json, "shops");

        iteration(shopGroup, new OnObjectIteration() {
            @Override
            public void get(int index, JSONObject object) {
                shopIdGroup.add(getString(object, "shop_id", ""));
                shopNameGroup.add(getString(object, "shop_name", ""));
                shopPhotoGroup.add(getString(object, "shop_photo", ""));
                shopPhoneGroup.add(getString(object, "shop_phone", ""));
                shopAddressGroup.add(getString(object, "shop_address", ""));
                shopUrlGroup.add(getString(object, "shop_url", ""));
                shopLikeGroup.add(getString(object, "shop_like", ""));
                shopKmGroup.add(getString(object, "shop_km", ""));
            }
        });
    }

}
