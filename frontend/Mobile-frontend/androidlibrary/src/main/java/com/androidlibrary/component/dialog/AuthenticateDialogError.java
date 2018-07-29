package com.androidlibrary.component.dialog;

import android.app.AlertDialog;
import android.content.Context;

import com.androidlibrary.R;

/**
 * Created by ameng on 2016/5/22.
 */
public class AuthenticateDialogError extends AlertDialog.Builder {
    public AuthenticateDialogError(Context context) {
        super(context);
        this.setTitle(R.string.authenticate_dialog_error_tittle);
        this.setMessage(context.getString(R.string.authenticate_dialog_login_fail));
    }
}
