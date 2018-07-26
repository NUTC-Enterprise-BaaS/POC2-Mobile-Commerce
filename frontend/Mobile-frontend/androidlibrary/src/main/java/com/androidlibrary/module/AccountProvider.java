package com.androidlibrary.module;

import android.content.ContentProvider;
import android.content.ContentValues;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.database.sqlite.SQLiteOpenHelper;
import android.net.Uri;

import com.androidlibrary.module.consts.AccountConst;

/**
 * Created by chriske on 2016/5/27.
 */
public class AccountProvider extends ContentProvider {
    public static final Uri URI = Uri.parse("content://com.poc2");

    private SQLiteOpenHelper databaseHelper;

    @Override
    public boolean onCreate() {
        databaseHelper = new SQLiteOpenHelper(getContext(), AccountProvider.class.getName(), null, 2) {
            @Override
            public void onCreate(SQLiteDatabase db) {
                String script = "CREATE TABLE account_info(" +
                        "id INTEGER PRIMARY KEY," +
                        "account TEXT," +
                        "token TEXT," +
                        "is_login TINYINT," +
                        "is_keep_login TINYINT," +
                        "registerState TEXT," +
                        "password TEXT);";
                db.execSQL(script);

                String insert = "INSERT INTO account_info VALUES (0, '', '', 0, 0,'', '');";
                db.execSQL(insert);
            }

            @Override
            public void onUpgrade(SQLiteDatabase db, int oldVersion, int newVersion) {
                String script = "DROP TABLE account_info;";
                db.execSQL(script);
            }
        };
        return true;
    }

    @Override
    public Cursor query(Uri uri, String[] projection, String selection, String[] selectionArgs, String sortOrder) {
        String script = "SELECT id, account, token, is_login, password, is_keep_login, registerState FROM account_info WHERE id = 0;";
        return databaseHelper.getReadableDatabase().rawQuery(script, null);
    }

    @Override
    public String getType(Uri uri) {
        return null;
    }

    @Override
    public Uri insert(Uri uri, ContentValues values) {
        checkKeys(values);

        String account = values.getAsString(AccountConst.KEY_ACCOUNT);
        String token = values.getAsString(AccountConst.KEY_TOKEN);
        String isLogin = values.getAsString(AccountConst.KEY_IS_LOGIN);
        String password = values.getAsString(AccountConst.KEY_PASSWORD);
        String isKeepLogin = values.getAsString(AccountConst.KEY_IS_KEEP_LOGIN);
        String registerState = values.getAsString(AccountConst.Key_REGISTER_STATE);

        String script = "UPDATE account_info SET ";
        script += (account != null) ? " account = '" + account + "'," : "";
        script += (token != null) ? " token = '" + token + "'," : "";
        script += (isLogin != null) ? " is_login = " + isLogin + "," : "";
        script += (isKeepLogin != null) ? " is_keep_login = " + isKeepLogin + "," : "";
        script += (password != null) ? " password = '" + password + "'," : "";
        script += (registerState != null) ? " registerState = '" + registerState + "'," : "";
        script = script.substring(0, script.length() - 1);
        script += " WHERE id = 0;";
        databaseHelper.getWritableDatabase().execSQL(script);
        return null;
    }

    @Override
    public int delete(Uri uri, String selection, String[] selectionArgs) {
        throw new RuntimeException("Not supported delete method in this content provider.");
    }

    @Override
    public int update(Uri uri, ContentValues values, String selection, String[] selectionArgs) {
        throw new RuntimeException("Not supported update method in this content provider.");
    }

    private void checkKeys(ContentValues values) {
        boolean result = values.containsKey(AccountConst.KEY_ACCOUNT);
        result |= values.containsKey(AccountConst.KEY_TOKEN);
        result |= values.containsKey(AccountConst.KEY_IS_LOGIN);
        result |= values.containsKey(AccountConst.KEY_PASSWORD);
        result |= values.containsKey(AccountConst.KEY_IS_KEEP_LOGIN);
        result |= values.containsKey(AccountConst.Key_REGISTER_STATE);
        // // TODO: 7/12/16 WHATCHing
        if (!result) {
            throw new RuntimeException("No any valid key in ContentValues, " +
                    "please choice any one by following: " +
                    "KEY_ACCOUNT, KEY_TOKEN, KEY_IS_LOGIN, KEY_PASSWORD, KEY_IS_KEEP_LOGIN.");
        }
    }
}
