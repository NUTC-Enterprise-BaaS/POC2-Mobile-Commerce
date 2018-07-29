package com.androidlibrary.component.dialog;

import android.app.AlertDialog;
import android.content.Context;

import com.androidlibrary.R;

/**
 * Created by ameng on 2016/6/13.
 */
public class FinishModifyDialog extends AlertDialog {
    public FinishModifyDialog(Context context) {
        super(context);
        setMessage(context.getString(R.string.finish_modify_dialog_tittle));
    }
}
