package com.androidlibrary.component.dialog;

import android.app.Dialog;
import android.content.Context;

import com.androidlibrary.R;

/**
 * Created by ameng on 2016/6/14.
 */
public class CodesErrorDialog extends Dialog {
    public CodesErrorDialog(Context context) {
        super(context);
        setTitle(R.string.codes_error_dialog);
    }
}
