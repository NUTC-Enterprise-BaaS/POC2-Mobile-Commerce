package com.androidlibrary.module.sql;

import android.content.ContentValues;
import android.content.Context;
import android.database.Cursor;
import android.database.DatabaseUtils;
import android.provider.BaseColumns;
import android.text.format.DateFormat;

import java.util.ArrayList;
import java.util.Calendar;

/**
 * Created by ameng on 7/6/16.
 */
public class GCMTable extends SQLHelper {
    private final static String DB_NAME = "GCMTable.db";
    private final static String TABLE_NAME = "GCMTable";
    private final static int TABLE_VERSION = 1;
    private ContentValues contentValues;

    public GCMTable(Context context) {
        super(context, DB_NAME, TABLE_VERSION);
        contentValues = new ContentValues();
    }

    @Override
    public String setTableName() {
        return TABLE_NAME;
    }

    @Override
    public int setVersion() {
        return TABLE_VERSION;
    }

    public void insert(String id, String name, String photo, String url, String promotions) {
        contentValues.put(GCMData.CREATED_TIME, Calendar.getInstance().getTimeInMillis());
        contentValues.put(GCMData.SHOP_ID, id);
        contentValues.put(GCMData.SHOP_NAME, name);
        contentValues.put(GCMData.SHOP_PHOTO, photo);
        contentValues.put(GCMData.SHOP_URL, url);
        contentValues.put(GCMData.SHOP_PROMOTIONS, promotions);
        database.insert(TABLE_NAME, GCMData.COLUMN_NAME_NULLABLE, contentValues);
    }

    public int getTableCount() {
        int tableCount = (int) DatabaseUtils.queryNumEntries(database, TABLE_NAME);
        return tableCount;
    }

    public ArrayList<String> getColumesItem(int index) {
        ArrayList<String> dataList = new ArrayList<>();
        String[] projection = {
                GCMData.ID,
                GCMData.SHOP_ID,
                GCMData.CREATED_TIME,
                GCMData.SHOP_NAME,
                GCMData.SHOP_PHOTO,
                GCMData.SHOP_URL,
                GCMData.SHOP_PROMOTIONS};

        String sortOrder =
                GCMData.CREATED_TIME + " DESC";

        Cursor cursor = database.query(
                TABLE_NAME,  // The table to query
                projection,                               // The columns to return
                null,                                    // The columns for the WHERE clause
                null,                            // The values for the WHERE clause
                null,                                     // don't group the rows
                null,                                     // don't filter by row groups
                sortOrder                                 // The sort order
        );
        cursor.moveToPosition(index);
        dataList.add(cursor.getString(cursor.getColumnIndex(GCMData.SHOP_PROMOTIONS)));
        dataList.add(setDate(cursor.getLong(cursor.getColumnIndex(GCMData.CREATED_TIME))));
        dataList.add(cursor.getString(cursor.getColumnIndex(GCMData.SHOP_URL)));
        cursor.close();
        return dataList;
    }

    @Override
    public String creatTable() {
        String table =
                "CREATE TABLE " + TABLE_NAME + " (" +
                        GCMData.ID + " INTEGER PRIMARY KEY AUTOINCREMENT, " +
                        GCMData.SHOP_ID + " INTEGER, " +
                        GCMData.CREATED_TIME + " LONG, " +
                        GCMData.SHOP_NAME + " TEXT, " +
                        GCMData.SHOP_PHOTO + " TEXT, " +
                        GCMData.SHOP_URL + " TEXT, " +
                        GCMData.SHOP_PROMOTIONS + " TEXT);";
        return table;
    }

    public static class GCMData implements BaseColumns {
        public static final String ID = "_id";
        public static final String SHOP_ID = "shop_id";
        public static final String CREATED_TIME = "created_time";
        public static final String SHOP_NAME = "shop_name";
        public static final String SHOP_PHOTO = "shop_photo";
        public static final String SHOP_URL = "shop_url";
        public static final String SHOP_PROMOTIONS = "shop_promotions";
        public static final String COLUMN_NAME_NULLABLE = "null";
    }

    public String setDate(Long timestampMillis) {
        Calendar time = Calendar.getInstance();
        time.setTimeInMillis(timestampMillis);
        String date = DateFormat.format("yyyy/MM/dd HH:mm:ss", time).toString();

        return date;
    }
}
