package com.androidlibrary.module.backend.data;

import com.androidlibrary.module.sql.GCMTable;

import org.json.JSONObject;

import java.util.ArrayList;

/**
 * Created by ameng on 7/10/16.
 */
public class ApiV1GcmLuckyGetData extends JsonData {
    public String type;
    public int result;
    public ArrayList<String> messageGroup;

    public String luckyToken;

    public ApiV1GcmLuckyGetData(String data, GCMTable gcmTable) {
        super(data);
        if (type.equals("scratch") || type.equals("shareScratch")) {
            gcmTable.insert(
                    "",
                    "",
                    "",
                    luckyToken,
                    "限時活動，即括即送紅利點數");
        }
    }

    @Override
    protected void processing(JSONObject json) {
        super.processing(json);
        type = getString(json, "type", "");
//        if (!type.equals("scratch")) {
//            return;
//        }
        result = getInt(json, "result", 0);
        messageGroup = getStringArray(json, "message", "");
        if (result != 0) {
            return;
        }
        luckyToken = getString(json, "lucky_token", "");
    }
}
