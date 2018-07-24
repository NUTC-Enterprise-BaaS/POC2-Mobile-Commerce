package com.herbhousesgobuyother.contrube.component.dialog;

import android.app.Dialog;
import android.content.Context;

/**
 * Created by ameng on 2016/6/14.
 */
public class CodesErrorDialog extends Dialog {
    public CodesErrorDialog(Context context) {
        super(context);
        setTitle(com.androidlibrary.R.string.codes_error_dialog);
    }
}
