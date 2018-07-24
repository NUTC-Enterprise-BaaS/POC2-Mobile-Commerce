package com.androidlibrary.module.backend.object;


import com.androidlibrary.module.backend.data.JsonData;

import org.json.JSONException;
import org.json.JSONObject;

/**
 * Created by ameng on 2016/5/22.
 */
public class ApiV1LoginPostData extends JsonData {
    public int result;
    public String token;

    public ApiV1LoginPostData(String data) {
        super(data);
    }

    @Override
    protected void processing(JSONObject json) {
        result = getInt(json, "result", 0);
        if (result == 0) {
            try {
                token = json.getJSONObject("message").getString("token");
            } catch (JSONException e) {
                e.printStackTrace();
            }
        }
    }
}
