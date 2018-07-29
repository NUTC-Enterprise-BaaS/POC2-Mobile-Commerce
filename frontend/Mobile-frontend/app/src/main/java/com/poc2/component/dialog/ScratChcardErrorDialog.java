package com.poc2.component.dialog;

import android.app.AlertDialog;
import android.content.Context;
import android.view.View;

import com.poc2.ui.normal.scratchcard.ScratChcardErrorDialogLayout;


/**
 * Created by 依杰 on 2016/7/7.
 */
public class ScratChcardErrorDialog extends AlertDialog.Builder {
    public ScratChcardErrorDialogLayout layout;

    public ScratChcardErrorDialog(Context context) {
        super(context);
        layout = new ScratChcardErrorDialogLayout(context);
        this.setView(layout);
    }

    public void setconfirmEvent(View.OnClickListener event){
        layout.confirmButton.setOnClickListener(event);
    }
}