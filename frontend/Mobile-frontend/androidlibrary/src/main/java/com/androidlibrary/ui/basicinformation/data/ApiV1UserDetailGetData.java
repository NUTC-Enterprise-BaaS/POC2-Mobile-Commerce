package com.androidlibrary.ui.basicinformation.data;

import com.androidlibrary.module.backend.data.JsonData;

import org.json.JSONObject;

import java.util.ArrayList;

/**
 * Created by ameng on 2016/6/1.
 */
public class ApiV1UserDetailGetData extends JsonData {
    public int result;
    public ArrayList<String> messageGroup;
    public String userAccount;
    public String userBirthday;
    public int userCountry;
    public String userEmail;
    public String userPhone;
    public String countryCheck;
    public String userState;

    public ApiV1UserDetailGetData(String data) {
        super(data);
    }

    @Override
    protected void processing(JSONObject json) {
        super.processing(json);
        result = getInt(json, "result", 0);
        messageGroup = getStringArray(json, "message", "");
        countryCheck = "1";
        if (result != 0) {
            return;
        }
        userAccount = getString(json, "user_account", "");
        userBirthday = getString(json, "user_birthday", "");
        if (getString(json, "user_country", "").equals("")) {
            countryCheck = "";
        } else {
            userCountry = Integer.parseInt(getString(json, "user_country", ""));
        }
        userEmail = getString(json, "user_email", "");
        userPhone = getString(json, "user_phone", "");
        userState = getString(json, "user_state", "");
    }
}
