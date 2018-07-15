package com.androidlibrary.module.backend.data;

import org.json.JSONObject;

/**
 * Created by ameng on 8/15/16.
 */
public class ApiV1GeneralPointReceivePostData extends JsonData {
    public String point;
    public String checkId;
    public String receivePhone;
    public String receiveEmail;
    public String sendId;
    public String sendEmail;
    public String state;
    public String Type;

    public ApiV1GeneralPointReceivePostData(String data) {
        super(data);
    }

    @Override
    protected void processing(JSONObject json) {
        point = getString(json, "point", "");
        checkId = getString(json, "check_id", "");
        receivePhone = getString(json, "receive_phone", "");
        receiveEmail = getString(json, "receive_email", "");
        sendId = getString(json, "send_id", "");
        sendEmail = getString(json, "send_email", "");
        state = getString(json, "state", "");
        Type = getString(json, "type", "");
    }
}
