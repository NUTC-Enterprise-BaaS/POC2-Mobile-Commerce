package com.poc2.component.pre;

import android.content.Context;
import android.content.SharedPreferences;

/**
 * Created by 依杰 on 2018/7/13.
 */

public class PreferencesHelperImp {

    private String SharedPreferencesKey = "SharedPreferencesKey";

    private static final String PREF_KEY_ISFIRST = "PREF_KEY_ISFIRST";

    private static final String PREF_KEY_ISTRANSACTION = "PREF_KEY_ISTRANSACTION";

    private static final String PREF_KEY_ACCOUNT = "PREF_KEY_ACCOUNT";

    private static final String PREF_KEY_PASSWORD = "PREF_KEY_PASSWORD";

    private static final String PREF_KEY_LDAPTOKEN = "PREF_KEY_LDAPTOKEN";


    private final SharedPreferences mSharedPreferences;

    public PreferencesHelperImp(Context context) {
        mSharedPreferences = context.getSharedPreferences(SharedPreferencesKey, Context.MODE_PRIVATE);
    }

    public boolean getIsUseFingerPrint() {
        return mSharedPreferences.getBoolean(PREF_KEY_ISFIRST, false);
    }

    public void setIsUseFingerPrint(boolean isUseFingerPrint) {
        mSharedPreferences.edit().putBoolean(PREF_KEY_ISFIRST, isUseFingerPrint).apply();
    }

    public boolean getIsTransAction() {
        return mSharedPreferences.getBoolean(PREF_KEY_ISTRANSACTION, false);
    }

    public void setIsTransAction(boolean isUseFingerPrint) {
        mSharedPreferences.edit().putBoolean(PREF_KEY_ISTRANSACTION, isUseFingerPrint).apply();
    }

    public String getAccount() {
        return mSharedPreferences.getString(PREF_KEY_ACCOUNT, "");
    }

    public void setAccount(String account) {
        mSharedPreferences.edit().putString(PREF_KEY_ACCOUNT, account).apply();
    }

    public String getPassword() {
        return mSharedPreferences.getString(PREF_KEY_PASSWORD, "");
    }

    public void setPassword(String password) {
        mSharedPreferences.edit().putString(PREF_KEY_PASSWORD, password).apply();
    }

    public String getLDAPToken() {
        return mSharedPreferences.getString(PREF_KEY_LDAPTOKEN, "");
    }

    public void setLDAPToken(String token) {
        mSharedPreferences.edit().putString(PREF_KEY_LDAPTOKEN, token).apply();
    }
}
