package com.herbhousesgobuyother.contrube.component.dialog;

import android.app.AlertDialog;
import android.content.Context;

/**
 * Created by ameng on 2016/5/22.
 */
public class AuthenticateDialogError extends AlertDialog.Builder {
    public AuthenticateDialogError(Context context) {
        super(context);
        this.setTitle(com.androidlibrary.R.string.authenticate_dialog_error_tittle);
        this.setMessage(context.getString(com.androidlibrary.R.string.authenticate_dialog_login_fail));
    }
}
