package com.androidlibrary.component.dialog;

import android.app.ProgressDialog;
import android.content.Context;

import com.androidlibrary.R;


/**
 * Created by chriske on 2016/3/17.
 */
public class LoadingDialog extends ProgressDialog {

    public LoadingDialog(Context context) {
        super(context);
        setMessage(context.getString(R.string.loading_dialog_message));
    }
}
