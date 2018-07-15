package com.herbhousesgobuyother.contrube.model;

import android.content.Context;

import com.androidlibrary.module.backend.params.ParamsConst;
import com.androidlibrary.module.preferences.PreferencesHelper;

/**
 * Created by Gary on 2016/11/9.
 */

public class LoginHelper extends PreferencesHelper {
    private static final String FILE_NAME = LoginHelper.class.getName();
    private static final String ACCOUNT = "0";
    private static final String PASSWORD = "1";
    private static final String TOKEN = "2";
    private static final String AUTO_LOGIN_STATE = "3";
    private static final String REGISTER_STATE = "4";


    public LoginHelper(Context context) {
        super(context);
    }

    public void saveAccount(String account) {
        save(ACCOUNT, account);
    }

    public void savePassword(String password) {
        save(PASSWORD, password);
    }

    public void saveAutoLogin(String autoLogin) {
        save(AUTO_LOGIN_STATE, autoLogin);
    }

    public void saveToken(String token) {
        save(TOKEN, token);
    }

    public void saveRegisterState(String state) {
        save(REGISTER_STATE, state);
    }

    public Object getAccount() {
        return get(ACCOUNT, ParamsConst.Type.STRING);
    }



    public Object getPassword() {
        return get(PASSWORD, ParamsConst.Type.STRING);
    }

    public Object getAutoLogin() {
        return get(AUTO_LOGIN_STATE, ParamsConst.Type.STRING);
    }

    public Object getToken() {
        return get(TOKEN, ParamsConst.Type.STRING);
    }

    public Object getRegisterState() {
        return get(REGISTER_STATE, ParamsConst.Type.STRING);
    }

    @Override
    public String getClassName() {
        return FILE_NAME;
    }
}
