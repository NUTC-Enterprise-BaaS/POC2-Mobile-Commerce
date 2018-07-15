package com.androidlibrary.module.backend.data;

import org.json.JSONObject;

import java.util.ArrayList;

/**
 * Created by 依杰 on 2016/7/12.
 */
public class ApiV1StorePushStoreSendGetData extends JsonData {
    public String id;
    public String name;
    public String photo;
    public String url;
    public String point;
    public String type;
    public int result;
    public ArrayList<String> messageGroup;

    public ApiV1StorePushStoreSendGetData(String data) {
        super(data);
    }

    @Override
    protected void processing(JSONObject json) {
        super.processing(json);
        type = getString(json, "type", "");
        if (!type.equals("storesend")) {
            return;
        }
        result = getInt(json, "result", 0);
        messageGroup = getStringArray(json, "message", "");
        if (result != 0) {
            return;
        }
        id = getString(json, "shop_id", "");
        name = getString(json, "shop_name", "");
        photo = getString(json, "shop_photo", "");
        url = getString(json, "shop_url", "");
        point = getString(json, "shop_point", "");
    }
}
