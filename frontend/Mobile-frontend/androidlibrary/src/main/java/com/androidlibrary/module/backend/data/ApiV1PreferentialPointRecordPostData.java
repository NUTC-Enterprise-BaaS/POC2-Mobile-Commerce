package com.androidlibrary.module.backend.data;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;

/**
 * Created by 依杰 on 2016/7/14.
 */
public class ApiV1PreferentialPointRecordPostData extends JsonData {
    public int result;
    public ArrayList<String> messageGroup;
    public ArrayList<Integer> idGroup;
    public ArrayList<String> phoneGroup;
    public ArrayList<Integer> moneyGroup;

    public ApiV1PreferentialPointRecordPostData(String data) {
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
        phoneGroup = new ArrayList<>();
        moneyGroup = new ArrayList<>();
        itemsArray = getJSONArray(json, "items");

        for (int i = 0; i < itemsArray.length(); i++) {
            try {
                JSONObject itemsObject = itemsArray.getJSONObject(i);
                int id = getInt(itemsObject, "id", 0);
                String phone = getString(itemsObject, "phone_number", "09XX-XXXXXX");
                int money = getInt(itemsObject, "money", 0);
                idGroup.add(id);
                phoneGroup.add(phone);
                moneyGroup.add(money);
            } catch (JSONException e) {
                e.printStackTrace();
            }
        }
    }
}
