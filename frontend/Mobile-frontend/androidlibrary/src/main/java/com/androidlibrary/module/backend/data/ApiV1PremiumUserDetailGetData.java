package com.androidlibrary.module.backend.data;

import org.json.JSONObject;

import java.util.ArrayList;

public class ApiV1PremiumUserDetailGetData extends JsonData {
    public int result;
    public ArrayList<String> messageGroup;
    public String userAccount;
    public String userBirthday;
    public int userCountry;
    public String storeName;
    public String storeAddress;
    public String storeUrl;
    public String contant;
    public String email;
    public String countryCheck;

    public ApiV1PremiumUserDetailGetData(String data) {
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
        storeName = getString(json, "store_name", "");
        storeAddress = getString(json, "store_address", "");
        contant = getString(json, "store_contact", "");
        storeUrl = getString(json, "store_url", "");
        email = getString(json, "user_email", "");

        if (getString(json, "user_country", "").equals("")) {
            countryCheck = "";
        } else {
            userCountry = Integer.parseInt(getString(json, "user_country", ""));
        }
    }
}