package com.androidlibrary.module.backend.data;

import com.androidlibrary.module.sql.GCMTable;

import org.json.JSONObject;

import java.util.ArrayList;

/**
 * Created by ameng on 2016/6/30.
 */
public class ApiV1StoreGcmSendPostData extends JsonData {
    public String type;
    public int result;
    public ArrayList<String> messageGroup;
    public String shopId;
    public String shopName;
    public String shopPhoto;
    public String shopUrl;
    public String storePromotions;

    public ApiV1StoreGcmSendPostData(String data, GCMTable gcmTable) {
        super(data);
        if (type.equals("promotions")) {
            gcmTable.insert(
                    shopId,
                    shopName,
                    shopPhoto,
                    shopUrl,
                    storePromotions);
        }
    }

    @Override
    protected void processing(JSONObject json) {
        type = getString(json, "type", "");
        if (!type.equals("promotions")) {
            return;
        }
        result = getInt(json, "result", 0);
        messageGroup = getStringArray(json, "message", "");
        if (result != 0) {
            return;
        }
        shopId = (getString(json, "shop_id", ""));
        shopName = (getString(json, "shop_name", ""));
        shopPhoto = (getString(json, "shop_photo", ""));
        shopUrl = (getString(json, "shop_url", ""));
        storePromotions = (getString(json, "shop_message", ""));
    }
}
