package com.herbhousesgobuyother.contrube.component.dialog;

import android.app.ProgressDialog;
import android.content.Context;


/**
 * Created by chriske on 2016/3/17.
 */
public class LoadingDialog extends ProgressDialog {

    public LoadingDialog(Context context) {
        super(context);
        setMessage(context.getString(com.androidlibrary.R.string.loading_dialog_message));
        this.setCancelable(false);
    }

    public LoadingDialog(Context context,String message) {
        super(context);
        setMessage(message);
        this.setCancelable(false);
    }
}
