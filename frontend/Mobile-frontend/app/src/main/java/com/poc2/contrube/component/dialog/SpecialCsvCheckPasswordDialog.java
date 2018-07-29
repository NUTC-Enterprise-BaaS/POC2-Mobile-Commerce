package com.poc2.contrube.component.dialog;

import android.app.AlertDialog;
import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;

import com.poc2.R;

/**
 * Created by 依杰 on 2016/12/20.
 */

public class SpecialCsvCheckPasswordDialog extends AlertDialog.Builder {
    private View view;
    private Context context;
    private Button cancle;
    private Button submit;
    private EditText input;
    private CsvCheckPasswordEvent event;

    public SpecialCsvCheckPasswordDialog(Context context) {
        super(context);
        this.context = context;
        view = LayoutInflater.from(context).inflate(R.layout.dialog_special_csv_check_password, null);
        this.setView(view);
        this.setCancelable(true);
        findView();
        init();
    }

    private void findView() {
        input = (EditText) view.findViewById(R.id.dialog_special_csv_check_password_input_edit);
        cancle = (Button) view.findViewById(R.id.dialog_special_point_edit_title_text_cancle_button);
        submit = (Button) view.findViewById(R.id.dialog_special_point_edit_title_text_submit_button);
    }

    private void init() {
        submit.setOnClickListener(submitClick);
        cancle.setOnClickListener(cancleClick);
    }

    private View.OnClickListener submitClick = new View.OnClickListener() {
        @Override
        public void onClick(View v) {
            event.submit();
        }
    };

    private View.OnClickListener cancleClick = new View.OnClickListener() {
        @Override
        public void onClick(View v) {
            event.cancle();
        }
    };

    public EditText getinputEdit() {
        return input;
    }

    public void setCsvEvent(CsvCheckPasswordEvent event) {
        this.event = event;
    }

    public interface CsvCheckPasswordEvent {
        void submit();

        void cancle();
    }
}
