package com.herbhousesgobuyother.contrube.core;

import android.content.Context;
import android.database.sqlite.SQLiteDatabase;
import android.database.sqlite.SQLiteOpenHelper;

/**
 * Created by ameng on 7/6/16.
 */
public abstract class SQLHelper extends SQLiteOpenHelper {
    protected SQLiteDatabase database;
    private boolean mIsNewData;

    public SQLHelper(Context context, String name, int version, boolean isNewData) {
        super(context, name, null, version);
        this.mIsNewData = isNewData;
        database = getWritableDatabase();
    }

    // 資料庫名稱
    public abstract String setTableName();

    // 資料庫版本，資料結構改變的時候要更改這個數字，通常是加一
    public abstract int setVersion();

    // 創造資料表與欄位
    public abstract String creatTable();

    @Override
    public void onCreate(SQLiteDatabase db) {
        db.execSQL(creatTable());
    }

    @Override
    public void onUpgrade(SQLiteDatabase db, int oldVersion, int newVersion) {
        if (mIsNewData) {
            // 刪除原有的表格
            db.execSQL("DROP TABLE IF EXISTS " + setTableName());
            // 呼叫onCreate建立新版的表格
            onCreate(db);
        }
    }

    @Override
    public void onDowngrade(SQLiteDatabase db, int oldVersion, int newVersion) {
        if (mIsNewData)
            onUpgrade(db, oldVersion, newVersion);
    }
}
