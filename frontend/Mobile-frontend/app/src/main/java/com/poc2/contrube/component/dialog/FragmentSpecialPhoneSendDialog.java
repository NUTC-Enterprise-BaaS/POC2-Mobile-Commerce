package com.poc2.contrube.component.dialog;

import android.app.AlertDialog;
import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;

import com.poc2.R;

/**
 * Created by 依杰 on 2016/11/24.
 */

public class FragmentSpecialPhoneSendDialog extends AlertDialog.Builder {
    private Context context;
    private View view;
    private TextView titleText;
    private EditText pointEdit;
    private Button cancelButton;
    private Button submitButton;
    private DialogClickEvent clickEvent;

    public FragmentSpecialPhoneSendDialog(Context context) {
        super(context);
        this.context = context;
        view = LayoutInflater.from(context).inflate(R.layout.dialog_special_point_phone_send, null);
        this.setView(view);
        this.setCancelable(false);
        findView();
        init();
    }

    private void init() {
        submitButton.setOnClickListener(submit);
        cancelButton.setOnClickListener(cancel);
    }

    private void findView() {
        titleText = (TextView) view.findViewById(R.id.dialog_special_point_phone_send_title_text);
        pointEdit = (EditText) view.findViewById(R.id.dialog_special_point_phone_send_input_edit);
        cancelButton = (Button) view.findViewById(R.id.dialog_special_point_phone_send_cancle_button);
        submitButton = (Button) view.findViewById(R.id.dialog_special_point_phone_send_submit_button);
    }

    private View.OnClickListener submit = new View.OnClickListener() {
        @Override
        public void onClick(View v) {
            clickEvent.submit();
        }
    };

    private View.OnClickListener cancel = new View.OnClickListener() {
        @Override
        public void onClick(View v) {
            clickEvent.cancel();
        }
    };

    public void setDialogClickEvent(DialogClickEvent event) {
        this.clickEvent = event;
    }

    public interface DialogClickEvent {
        void cancel();

        void submit();
    }

    public TextView getTitle() {
        return titleText;
    }

    public EditText getPointEdit(){
        return pointEdit;
    }
}
