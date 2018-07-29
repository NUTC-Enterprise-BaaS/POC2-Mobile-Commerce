package com.androidlibrary.module.watcher;

import android.app.AlertDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.view.View;

/**
 * Created by chriske on 2016/5/26.
 */
public abstract class CheckClearOnClickListener implements View.OnClickListener {
    public AlertDialog dialog;

    public CheckClearOnClickListener(Context context, String ok, String cancel, String message) {
        dialog = new AlertDialog.Builder(context)
                .setMessage(message)
                .setPositiveButton(ok, new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int which) {
                        onClickOk();
                    }
                }).setNegativeButton(cancel, new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int which) {
                        onClickCancel();
                    }
                }).create();
    }

    @Override
    public final void onClick(View v) {
        dialog.show();
    }

    public void onClickOk() {
    }

    public void onClickCancel() {
    }
}
