package com.androidlibrary.module.backend.data;

import android.util.Log;

import org.json.JSONObject;

import java.util.ArrayList;

/**
 * Created by ameng on 7/11/16.
 */
public class ApiV1LuckyMoneyGetData extends JsonData {
    public int result;
    public ArrayList<String> messageGroup;
    public String luckyMoney;

    public ApiV1LuckyMoneyGetData(String data) {
        super(data);
        Log.e("data", data);
    }

    @Override
    protected void processing(JSONObject json) {
        super.processing(json);
        result = getInt(json, "result", 0);
        messageGroup = getStringArray(json, "message", "");
        luckyMoney = getString(json, "lucky_money", "");
    }
}
