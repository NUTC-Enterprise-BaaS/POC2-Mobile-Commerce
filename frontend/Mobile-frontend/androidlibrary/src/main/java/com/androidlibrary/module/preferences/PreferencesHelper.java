package com.androidlibrary.module.preferences;

import android.content.Context;
import android.content.SharedPreferences;

import com.androidlibrary.module.backend.params.ParamsConst;

/**
 * Created by ameng on 2016/6/20.
 */
public abstract class PreferencesHelper {
    private Context context;

    public PreferencesHelper(Context context) {
        this.context = context;
    }

    public abstract String getClassName();

    public Context getContext() {
        return context;
    }

    public void save(String key, String account) {
        SharedPreferences store = context.getSharedPreferences(getClassName(), Context.MODE_PRIVATE);
        SharedPreferences.Editor editor = store.edit();
        editor.putString(key, account);
        editor.commit();
    }

    public Object get(String key, ParamsConst.Type type) {

        SharedPreferences store = context.getSharedPreferences(getClassName(), Context.MODE_PRIVATE);
        if (type == ParamsConst.Type.STRING) {
            return store.getString(String.valueOf(key), "");
        } else if (type == ParamsConst.Type.FLOAT) {
            return store.getFloat(String.valueOf(key), 0);
        } else if (type == ParamsConst.Type.DOUBLE) {
            return Double.valueOf(store.getString(String.valueOf(key), String.valueOf(0.0)));
        } else if (type == ParamsConst.Type.INT) {
            return store.getInt(String.valueOf(key), 0);
        } else if (type == ParamsConst.Type.LONG) {
            return store.getLong(String.valueOf(key), 0);
        } else {
            throw new RuntimeException("Must use base type(String, Float, Double, Integer, Long), type from input is " + type.getClass().getName() + ".");
        }
    }
}
