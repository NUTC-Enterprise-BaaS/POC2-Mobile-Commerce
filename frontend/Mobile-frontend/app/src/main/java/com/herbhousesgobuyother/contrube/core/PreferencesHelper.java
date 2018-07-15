package com.herbhousesgobuyother.contrube.core;

import android.content.Context;
import android.content.SharedPreferences;

import java.util.HashSet;

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

    public void save(Type type, String key, Object vale) {
        SharedPreferences store = context.getSharedPreferences(getClassName(), Context.MODE_PRIVATE);
        SharedPreferences.Editor editor = store.edit();
        if (type == Type.STRING) {
            editor.putString(key, (String) vale);
        } else if (type == Type.FLOAT) {
            editor.putFloat(key, (Float) vale);
        } else if (type == Type.INT) {
            editor.putInt(key, (Integer) vale);
        } else if (type == Type.LONG) {
            editor.putLong(key, (Long) vale);
        } else if (type == type.BOOLEAN) {
            editor.putBoolean(key, (Boolean) vale);
        } else if (type == type.STRING_SET) {
            editor.putStringSet(key, (HashSet<String>) vale);
        } else {
            throw new RuntimeException("Must use base type(String, Float, Double, Integer, Long), type from input is " + type.getClass().getName() + ".");
        }
        editor.commit();
    }

    public Object get(Type type, String key) {
        SharedPreferences store = context.getSharedPreferences(getClassName(), Context.MODE_PRIVATE);
        if (type == Type.STRING) {
            return store.getString(String.valueOf(key), "");
        } else if (type == Type.FLOAT) {
            return store.getFloat(String.valueOf(key), 0);
        } else if (type == Type.DOUBLE) {
            return Double.valueOf(store.getString(String.valueOf(key), String.valueOf(0.0)));
        } else if (type == Type.INT) {
            return store.getInt(String.valueOf(key), 0);
        } else if (type == Type.LONG) {
            return store.getLong(String.valueOf(key), 0);
        } else if (type == type.BOOLEAN) {
            return store.getBoolean(key, false);
        } else if (type == type.STRING_SET) {
            return store.getStringSet(key, new HashSet<String>());
        } else {
            throw new RuntimeException("Must use base type(String, Float, Double, Integer, Long), type from input is " + type.getClass().getName() + ".");
        }
    }

    public static class Type {
        public static final Type STRING = new Type();
        public static final Type FLOAT = new Type();
        public static final Type DOUBLE = new Type();
        public static final Type INT = new Type();
        public static final Type LONG = new Type();
        public static final Type BOOLEAN = new Type();
        public static final Type STRING_SET = new Type();
    }
}
