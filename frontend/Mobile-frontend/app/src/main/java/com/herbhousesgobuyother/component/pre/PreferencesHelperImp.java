package com.herbhousesgobuyother.component.pre;

import android.content.Context;
import android.content.SharedPreferences;

/**
 * Created by 依杰 on 2018/7/13.
 */

public class PreferencesHelperImp  {

    private String SharedPreferencesKey = "SharedPreferencesKey";

    private static final String PREF_KEY_ISFIRST = "PREF_KEY_ISFIRST";

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
}
