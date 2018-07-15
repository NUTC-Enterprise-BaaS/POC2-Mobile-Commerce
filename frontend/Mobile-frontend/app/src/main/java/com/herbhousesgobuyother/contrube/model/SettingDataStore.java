package com.herbhousesgobuyother.contrube.model;

import android.content.Context;

import com.herbhousesgobuyother.contrube.core.DataStore;


public class SettingDataStore extends DataStore {

    /**
     * 檔案名稱直接使用用類別名稱
     */
    private static final String FILE_NAME = SettingDataStore.class.getName();

    /**
     * 儲存欄位名稱，變數名稱已經充份表達意思，不需再撰寫有意義的字串，這裡使用數字取代
     */
    public static final String KEY_IS_SHOW_JOIN = "0";

    public SettingDataStore(Context context) {
        super(context, FILE_NAME, Context.MODE_PRIVATE);
    }

    /**
     * 讀取是否顯示加入其他會員按鈕
     * @return 是否顯示，預設值是 true
     */
    public boolean loadIsShowJoin() {
        return getSharedPreferences().getBoolean(KEY_IS_SHOW_JOIN, true);
    }
}