package com.herbhousesgobuyother.component.dialog;

import android.app.AlertDialog;
import android.content.Context;
import android.view.View;

import com.herbhousesgobuyother.ui.normal.scratchcard.ScratChcardSuccesLayout;


/**
 * Created by 依杰 on 2016/7/6.
 */
public class ScratChcardSuccesDialog extends AlertDialog.Builder {
    public ScratChcardSuccesLayout layout;

    public ScratChcardSuccesDialog(Context context) {
        super(context);
        layout = new ScratChcardSuccesLayout(context);
        this.setView(layout);
    }

    public void setConfirm(View.OnClickListener event) {
        layout.confirmButton.setOnClickListener(event);
    }
}