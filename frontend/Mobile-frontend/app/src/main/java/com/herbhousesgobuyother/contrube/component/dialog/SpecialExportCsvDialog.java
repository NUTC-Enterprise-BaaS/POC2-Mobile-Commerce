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
 * Created by 依杰 on 2016/11/23.
 */

public class SpecialExportCsvDialog extends AlertDialog.Builder {
    private Context context;
    private EditText inputEdit;
    private Button submitButton;
    private TextView forgotButton;
    private View view;
    private CsvEvent csvEvent;

    public SpecialExportCsvDialog(Context context) {
        super(context);
        this.context = context;
        view = LayoutInflater.from(context).inflate(R.layout.dialog_special_export_csv_password, null);
        this.setView(view);
        this.setCancelable(true);
        init();
    }

    private void init() {
        findView();
        submitButton.setOnClickListener(submit);
        forgotButton.setOnClickListener(forgot);
    }

    private void findView() {
        inputEdit = (EditText) view.findViewById(R.id.dialog_special_export_csv_password_input_edit);
        submitButton = (Button) view.findViewById(R.id.dialog_special_export_csv_password_cancle_button);
        forgotButton = (TextView) view.findViewById(R.id.dialog_special_export_csv_password_forgot_text);
    }

    private View.OnClickListener submit = new View.OnClickListener() {
        @Override
        public void onClick(View v) {
            csvEvent.submit();
        }
    };

    private View.OnClickListener forgot = new View.OnClickListener() {
        @Override
        public void onClick(View v) {
            csvEvent.forgot();
        }
    };

    public EditText getinputEdit() {
        return inputEdit;
    }

    public void setCsvEvent(CsvEvent event) {
        this.csvEvent = event;
    }

    public interface CsvEvent {
        void submit();

        void forgot();
    }
}
