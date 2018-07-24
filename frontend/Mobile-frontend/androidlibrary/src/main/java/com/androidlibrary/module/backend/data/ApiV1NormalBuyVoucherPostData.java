package com.androidlibrary.module.backend.data;

import android.util.Log;

import org.json.JSONObject;

/**
 * Created by 依杰 on 2018/7/18.
 */

public class ApiV1NormalBuyVoucherPostData extends JsonData {
    public String message;

    public ApiV1NormalBuyVoucherPostData(String data) {
        super(data);
        Log.e("BuyVoucherPostData", "" + data);
    }

    @Override
    protected void processing(JSONObject json) {
        super.processing(json);
        message = getString(json, "message", "");
    }
}