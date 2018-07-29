package com.androidlibrary.module.backend.data;

import org.json.JSONObject;

import java.util.ArrayList;

/**
 * Created by ameng on 2016/6/1.
 */
public class ApiV1SpecialNewsIdGetData extends JsonData {
    public int result;
    public ArrayList<String> messageGroup;
    public String tittle;
    public String date;
    public String url;

    public ApiV1SpecialNewsIdGetData(String data) {
        super(data);

    }

    @Override
    protected void processing(JSONObject json) {
        result = getInt(json, "result", 0);
        messageGroup = getStringArray(json, "message", "");
        if (result!=0){
            return;
        }
        tittle = getString(json, "title", "");
        date = getString(json, "date", "");
        url = getString(json, "url", "");
    }
}
