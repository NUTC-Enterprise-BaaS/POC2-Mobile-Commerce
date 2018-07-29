package com.androidlibrary.module.backend.data;


import android.util.Log;

import org.json.JSONException;
import org.json.JSONObject;

/**
 * Created by ameng on 2016/5/22.
 */
public class ApiV1LoginPostData extends JsonData {
    public int result;
    public String token;
    /**
     * 0：特約商店以及優惠商店尚未註冊
     * 1：特約商店已註冊優惠商店尚未註冊
     * 2：優惠商店已註冊特約商店尚未註冊
     * 3：優惠商店以及特約商店都已註冊
     */
    public String registeredState;

    public ApiV1LoginPostData(String data) {
        super(data);
        Log.e("data", data);
    }

    @Override
    protected void processing(JSONObject json) {
        result = getInt(json, "result", 0);
        if (result == 0) {
            try {
                token = json.getJSONObject("message").getString("token");
                registeredState = getString(json, "registered", "");
                Log.e("token", token);
                // // TODO: 7/12/16  registeredState = getInt(json, "registered", 3);
            } catch (JSONException e) {
                Log.e("Login ERROR", e.toString());
                e.printStackTrace();
            }
        }
    }
}
