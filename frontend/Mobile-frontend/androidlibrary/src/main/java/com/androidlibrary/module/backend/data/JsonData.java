package com.androidlibrary.module.backend.data;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;
import org.json.JSONTokener;

import java.util.ArrayList;

/**
 * Created by chriske on 2016/3/14.
 */
public class JsonData extends ProcessingData {

    public JsonData(String data) {
        Object json = getJson(data);
        if (json instanceof JSONObject) {
            processing((JSONObject) json);
        } else if (json instanceof JSONArray) {
            processing((JSONArray) json);
        }
    }

    protected Object getJson(String data) {
        try {
            return new JSONTokener(data).nextValue();
        } catch (JSONException e) {
            addProcessFail(e, "Data can't be decode by json, please check data content: \n" + data);
            return null;
        }
    }

    protected void processing(JSONObject json) {
    }

    protected void processing(JSONArray json) {
    }

    protected boolean getBoolean(JSONObject json, String key, boolean defaultValue) {
        try {
            return json.getBoolean(key);
        } catch (JSONException e) {
            addProcessFail(e, null);
            return defaultValue;
        }
    }

    protected String getString(JSONObject json, String key, String defaultValue) {
        try {
            return json.getString(key);
        } catch (JSONException e) {
            addProcessFail(e, null);
            return defaultValue;
        }
    }

    protected int getInt(JSONObject json, String key, int defaultValue) {
        try {
            return json.getInt(key);
        } catch (JSONException e) {
            addProcessFail(e, null);
            return defaultValue;
        }
    }

    protected long getLong(JSONObject json, String key, long defaultValue) {
        try {
            return json.getLong(key);
        } catch (JSONException e) {
            addProcessFail(e, null);
            return defaultValue;
        }
    }

    protected JSONObject getJSONObject(JSONObject json, String key) {
        try {
            return json.getJSONObject(key);
        } catch (JSONException e) {
            addProcessFail(e, null);
            return new JSONObject();
        }
    }

    protected JSONObject getJSONObject(JSONArray json, int index) {
        try {
            return json.getJSONObject(index);
        } catch (JSONException e) {
            addProcessFail(e, null);
            return new JSONObject();
        }
    }

    protected JSONArray getJSONArray(JSONObject json, String key) {
        try {
            return json.getJSONArray(key);
        } catch (JSONException e) {
            addProcessFail(e, null);
            return new JSONArray();
        }
    }

    protected JSONArray getJSONArray(JSONArray json, int index) {
        try {
            return json.getJSONArray(index);
        } catch (JSONException e) {
            addProcessFail(e, null);
            return new JSONArray();
        }
    }

    protected void iteration(JSONArray json, OnArrayIteration iteration) {
        for (int i = 0; i < json.length(); i++) {
            JSONArray array = getJSONArray(json, i);
            iteration.get(i, array);
        }
    }

    protected void iteration(JSONArray json, OnObjectIteration iteration) {
        for (int i = 0; i < json.length(); i++) {
            JSONObject object = getJSONObject(json, i);
            iteration.get(i, object);
        }
    }

    public static interface OnArrayIteration {
        public void get(int index, JSONArray array);
    }

    public static interface OnObjectIteration {
        public void get(int index, JSONObject object);
    }


    protected ArrayList<String> getStringArray(JSONObject json, String key, String defaultValue) {
        ArrayList<String> stringArray = new ArrayList<>();
        JSONArray array;
        try {
            array = json.getJSONArray(key);
        } catch (JSONException e) {
            addProcessFail(e, null);
            return stringArray;
        }
        int size = array.length();
        try {
            for (int i = 0; i < size; i++) {
                stringArray.add(array.getString(i));
            }
        } catch (JSONException e) {
            addProcessFail(e, null);
            stringArray.add(defaultValue);
        }
        return stringArray;
    }
}
