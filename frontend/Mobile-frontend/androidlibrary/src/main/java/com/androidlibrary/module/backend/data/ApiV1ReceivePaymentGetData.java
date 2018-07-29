package com.androidlibrary.module.backend.data;

import com.androidlibrary.module.sql.GCMTable;

import org.json.JSONObject;

import java.util.ArrayList;

/**
 * Created by 依杰 on 2016/7/12.
 */
public class ApiV1ReceivePaymentGetData extends JsonData {
    public String type;
    public String name;
    public String url;
    public String id;
    public int result;
    public ArrayList<String> messageGroup;

    public ApiV1ReceivePaymentGetData(String data, GCMTable gcmTable) {
        super(data);
        if (type.equals("payment")) {
            gcmTable.insert(
                      id,
                      name,
                      "",
                      url,
                      "繳費通知");
        }
    }

    @Override
    protected void processing(JSONObject json) {
        super.processing(json);
        type = getString(json, "type", "");
        if (!type.equals("payment")) {
            return;
        }
        result = getInt(json, "result", 0);
        messageGroup = getStringArray(json, "message", "");
        if (result != 0) {
            return;
        }
        id = getString(json, "id", "");
        name = getString(json, "title", "");
        url = getString(json, "url", "");
    }
}
