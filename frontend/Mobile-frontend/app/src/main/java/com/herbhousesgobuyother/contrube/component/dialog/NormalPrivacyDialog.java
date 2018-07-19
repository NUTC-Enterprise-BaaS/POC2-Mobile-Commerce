package com.herbhousesgobuyother.contrube.component.dialog;

import android.app.AlertDialog;
import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;

import com.herbhousesgobuyother.R;

/**
 * Created by Gary on 2016/12/19.
 */

public class NormalPrivacyDialog extends AlertDialog.Builder{
    private Context context;
    private EditText inputEdit;
    private Button submitButton;
    private TextView forgotButton;
    private View view;
    private SpecialExportCsvDialog.CsvEvent csvEvent;

    public NormalPrivacyDialog(Context context) {
        super(context);
        this.context = context;
        view = LayoutInflater.from(context).inflate(R.layout.dialog_privacy, null);
        this.setView(view);
        this.setCancelable(true);
        init();
    }

    private void init() {

    }
}
