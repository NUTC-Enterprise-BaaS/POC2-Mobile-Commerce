package com.androidlibrary.module.backend.data;

import android.util.Log;

import org.json.JSONObject;

import java.util.ArrayList;

/**
 * Created by 依杰 on 2016/7/14.
 */
public class ApiV1PreferentialCsvDownloadPostData extends JsonData {
    public int result;
    public ArrayList<String> messageGroup;

    public ApiV1PreferentialCsvDownloadPostData(String data) {
        super(data);
        Log.e("data", data);

    }

    @Override
    protected void processing(JSONObject json) {
        super.processing(json);
        result = getInt(json, "result", 0);
        messageGroup = getStringArray(json, "message", "");

        if (result != 0) {
            return;
        }

    }
}