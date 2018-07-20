package com.herbhousesgobuyother.contrube.component.dialog;

import android.app.AlertDialog;
import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;

import com.herbhousesgobuyother.R;

/**
 * Created by 依杰 on 2017/1/16.
 */

public class PremiumCsvCheckPasswordDialog extends AlertDialog.Builder {
    private View view;
    private Button cancel;
    private Button submit;
    private EditText input;
    private PremiumCsvCheckPasswordEvent event;

    public PremiumCsvCheckPasswordDialog(Context context) {
        super(context);
        view = LayoutInflater.from(context).inflate(R.layout.dialog_premium_csv_check_password, null);
        this.setView(view);
        this.setCancelable(true);
        findView();
        init();
    }

    private void findView() {
        input = (EditText) view.findViewById(R.id.dialog_premium_csv_check_password_input_edit);
        cancel = (Button) view.findViewById(R.id.dialog_premium_point_edit_title_text_cancle_button);
        submit = (Button) view.findViewById(R.id.dialog_premium_point_edit_title_text_submit_button);
    }

    private void init() {
        submit.setOnClickListener(submitClick);
        cancel.setOnClickListener(cancleClick);
    }

    private View.OnClickListener submitClick = new View.OnClickListener() {
        @Override
        public void onClick(View v) {
            if (event != null) {
                event.submit();
            }
        }
    };

    private View.OnClickListener cancleClick = new View.OnClickListener() {
        @Override
        public void onClick(View v) {
            event.cancel();
        }
    };

    public EditText getinputEdit() {
        return input;
    }

    public void setCsvEvent(PremiumCsvCheckPasswordEvent event) {
        if (event != null) {
            this.event = event;
        }
    }

    public interface PremiumCsvCheckPasswordEvent {
        void submit();

        void cancel();
    }
}
