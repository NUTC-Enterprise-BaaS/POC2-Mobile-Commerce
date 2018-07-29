package com.androidlibrary.core;

import android.content.Context;
import android.content.SharedPreferences;

/**
 * Created by chriske on 2016/6/7.
 * 簡單儲存資料用的基底類別。
 */
public class DataStore {
    private Context context;
    private String fileName;
    private int mode;

    /**
     * 直接設定好檔案名稱和存取模式
     * @param context 取得 SharedPreferences 要使用 Context
     * @param fileName SharedPreferences 檔案名稱
     * @param mode SharedPreferences 存取模式
     */
    public DataStore(Context context, String fileName, int mode) {
        this.context = context;
        this.fileName = fileName;
        this.mode = mode;
    }

    /**
     * 取得讀取物件
     * @return
     */
    protected SharedPreferences getSharedPreferences() {
        return context.getSharedPreferences(fileName, mode);
    }

    /**
     * 取得寫入物件
     * @return
     */
    protected SharedPreferences.Editor getEditor() {
        return context.getSharedPreferences(fileName, mode).edit();
    }

    /**
     * 儲存 String 型態的資料
     * @param key 指定欄位名稱
     * @param value 指定數值
     */
    public void save(String key, String value) {
        SharedPreferences.Editor editor = getEditor();
        editor.putString(key, value);
        editor.commit();
    }

    /**
     * 儲存 Int 型態的資料
     * @param key 指定欄位名稱
     * @param value 指定數值
     */
    public void save(String key, boolean value) {
        SharedPreferences.Editor editor = getEditor();
        editor.putBoolean(key, value);
        editor.commit();
    }
}
