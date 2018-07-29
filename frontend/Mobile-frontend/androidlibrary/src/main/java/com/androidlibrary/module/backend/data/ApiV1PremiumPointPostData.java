package com.androidlibrary.module.backend.data;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;

/**
 * Created by Gary on 2016/5/28.
 */
public class ApiV1PremiumPointPostData extends JsonData {
    public int result;
    public ArrayList<String> messageGroup;
    public ArrayList<Integer> idGroup;
    public ArrayList<String> receiveGroup;
    public ArrayList<Long> timestampGroup;
    public ArrayList<String> moneyGroup;

    public ApiV1PremiumPointPostData(String data) {
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
        receiveGroup = new ArrayList<>();
        timestampGroup = new ArrayList<>();
        moneyGroup = new ArrayList<>();
        itemsArray = getJSONArray(json, "items");


        if (result == 0) {
            for (int i = 0; i < itemsArray.length(); i++) {
                try {
                    JSONObject itemsObject = itemsArray.getJSONObject(i);
                    int id = getInt(itemsObject, "id", 0);
                    String phoneNumber = getString(itemsObject, "phone_number", "09XX-XXXXXX");
                    Long timestamp = getLong(itemsObject, "timestamp", 0);
                    String money = getString(itemsObject, "money", "");

                    idGroup.add(id);
                    receiveGroup.add(phoneNumber);
                    timestampGroup.add(timestamp);
                    moneyGroup.add(money);
                } catch (JSONException e) {
                    e.printStackTrace();
                }

            }
        }

    }
}

