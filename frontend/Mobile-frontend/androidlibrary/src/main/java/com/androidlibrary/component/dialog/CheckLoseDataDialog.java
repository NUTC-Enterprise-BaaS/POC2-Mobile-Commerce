package com.androidlibrary.component.dialog;

import android.app.Activity;
import android.app.AlertDialog;
import android.content.DialogInterface;

import com.androidlibrary.R;

/**
 * Created by chriske on 2016/5/26.
 */
public class CheckLoseDataDialog {
    public AlertDialog dialog;

    public CheckLoseDataDialog(final Activity activity) {
        dialog = new AlertDialog.Builder(activity)
                .setMessage(R.string.check_lost_data_dialog_message)
                .setPositiveButton(R.string.check_lost_data_dialog_ok, new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int which) {
                        activity.finish();
                    }
                })
                .setNegativeButton(R.string.check_lost_data_dialog_cancel, new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int which) {
                    }
                })
                .create();
    }

    public void show() {
        dialog.show();
    }
}
