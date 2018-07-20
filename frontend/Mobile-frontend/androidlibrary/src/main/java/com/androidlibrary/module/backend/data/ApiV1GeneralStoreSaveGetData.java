package com.androidlibrary.module.backend.data;


import android.content.Context;
import android.util.Log;

import org.json.JSONArray;
import org.json.JSONObject;

import java.util.ArrayList;

/**
 * Created by Gary on 2016/5/25.
 */
public class ApiV1GeneralStoreSaveGetData extends JsonData {
    public int result;
    public ArrayList<String> messageGroup;
    public ArrayList<Integer> idGroup;
    public ArrayList<String> nameGroup;
    public ArrayList<String> phoneGroup;
    public ArrayList<String> addressGroup;
    public ArrayList<String> distanceGroup;
    public ArrayList<String> photoGroup;
    public ArrayList<String> urlGroup;
    public ArrayList<String> kmGroup;

    private Context context;

    public ApiV1GeneralStoreSaveGetData(Context context, String data) {
        super(data);
        Log.e("data", data);
    }

    @Override
    protected void processing(JSONObject json) {
        super.processing(json);
        result = getInt(json, "result", 0);
        messageGroup = getStringArray(json, "message", "");

        JSONArray itemsArray;
        idGroup = new ArrayList<>();
        nameGroup = new ArrayList<>();
        phoneGroup = new ArrayList<>();
        addressGroup = new ArrayList<>();
        distanceGroup = new ArrayList<>();
        photoGroup = new ArrayList<>();
        urlGroup = new ArrayList<>();
        kmGroup = new ArrayList<>();

        if (messageGroup.get(0).toString().equals("There is no collection of stores")) {
            return;
        }

        itemsArray = getJSONArray(json, "shops");

        iteration(itemsArray, new OnObjectIteration() {
            @Override
            public void get(int index, JSONObject object) {
                int id = getInt(object, "shop_id", 0);
                String name = getString(object, "shop_name", "");
                String phone = getString(object, "shop_phone", "");
                String address = getString(object, "shop_address", "");
                String photo = getString(object, "shop_photo", "");
                String url = getString(object, "shop_url", "");
                String km = getString(object, "shop_km", "");

                idGroup.add(id);
                nameGroup.add(name);
                phoneGroup.add(phone);
                addressGroup.add(address);
                photoGroup.add(photo);
                urlGroup.add(url);
                kmGroup.add(km);

            }
        });
    }
}