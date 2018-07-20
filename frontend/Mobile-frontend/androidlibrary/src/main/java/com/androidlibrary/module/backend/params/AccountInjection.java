package com.androidlibrary.module.backend.params;

import android.content.ContentResolver;
import android.content.ContentValues;
import android.content.Context;
import android.database.Cursor;

import com.androidlibrary.module.AccountProvider;
import com.androidlibrary.module.ApiParams;
import com.androidlibrary.module.consts.AccountConst;


/**
 * Created by chriske on 2016/3/17.
 */
public class AccountInjection extends ParamsInjection {
    private Context context;

    public AccountInjection(Context context) {
        this.context = context;
    }

    public void save(String key, String value) {
        ContentValues contentValue = new ContentValues();
        contentValue.put(key, value);
        context.getContentResolver().insert(AccountProvider.URI, contentValue);
    }

    public void save(String key, boolean value) {
        int realValue = value ? 1 : 0;
        save(key, realValue + "");
    }

    public Cursor loadData() {
        ContentResolver resolver = context.getContentResolver();
        Cursor cursor = resolver.query(AccountProvider.URI, null, null, null, null);
        cursor.moveToFirst();
        return cursor;
    }

    public String loadAccount() {
        Cursor cursor = loadData();
        String result = cursor.getString(1);
        return result;
    }

    public String loadPassword() {
        Cursor cursor = loadData();
        String result = cursor.getString(4);
        return result;
    }

    public String loadRegisteredState() {
        Cursor cursor = loadData();
        String result = cursor.getString(cursor.getColumnIndex("registerState"));
        return result;
    }

    public String loadToken() {
        Cursor cursor = loadData();
        String result = cursor.getString(2);
        return result;
    }

    public boolean loadIsLogin() {
        Cursor cursor = loadData();
        int result = cursor.getInt(3);
        return (result == 1);
    }

    public boolean loadIsKeepLogin() {
        Cursor cursor = loadData();
        int result = cursor.getInt(5);
        return (result == 1);
    }

    /**
     * 決定哪些資料要注入進 Api 請求參數中。
     *
     * @param params 要被注入資訊的 Api 請求參數
     */
    @Override
    public void inject(ApiParams params) {
        Cursor cursor = context.getContentResolver().query(AccountProvider.URI, null, null, null, null);
        cursor.moveToNext();
        params.inputEmail = cursor.getString(1);
        params.inputPassword = cursor.getString(4);
        params.headerAuthorization = cursor.getString(2);
        cursor.close();
    }


    /**
     * 清除儲存的所有資料，常用情況如：登出。
     */
    public void clear() {
        ContentValues contentValue = new ContentValues();
        contentValue.put(AccountConst.KEY_TOKEN, "");
        contentValue.put(AccountConst.KEY_PASSWORD, "");
        contentValue.put(AccountConst.KEY_IS_LOGIN, "0");
        contentValue.put(AccountConst.KEY_IS_KEEP_LOGIN, "0");
        contentValue.put(AccountConst.Key_REGISTER_STATE, "0");

        // // TODO: 7/12/16 WHATCHing
        context.getContentResolver().insert(AccountProvider.URI, contentValue);
    }
}
