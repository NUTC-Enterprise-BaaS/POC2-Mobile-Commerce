package com.herbhousesgobuyother.contrube.component.dialog;

import android.app.Dialog;
import android.content.Context;
import android.graphics.Color;
import android.graphics.drawable.ColorDrawable;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

import com.herbhousesgobuyother.R;
import com.herbhousesgobuyother.contrube.controllor.point.NormalPointSendPointDialogController;


/**
 * Created by 依杰 on 2016/11/15.
 */

public class NormalPointSendPointDialog extends Dialog {
    private Button cancelButton;
    private Button nextButton;
    private EditText pointEdit;
    private EditText phoneEdit;
    private EditText emailEdit;
    private NormalPointPasswordDialog passwordDialog;
    private NormalPointSendPointDialogController sendController;
    private NormalPointSendPointDialog dialog;

    public NormalPointSendPointDialog(Context context) {
        super(context);
    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.dialog_normal_point_send_point);
        init();
    }

    private void finView() {
        cancelButton = (Button) findViewById(R.id.fragment_normal_point_dialog_cancle_button);
        nextButton = (Button) findViewById(R.id.fragment_normal_point_dialog_submit_button);
        pointEdit = (EditText) findViewById(R.id.fragment_normal_point_dialog_input_point_edit);
        phoneEdit = (EditText) findViewById(R.id.fragment_normal_point_dialog_input_phone_edit);
        emailEdit = (EditText) findViewById(R.id.fragment_normal_point_dialog_input_email_edit);
        emailEdit.setSingleLine(true);
    }

    private void init() {
        finView();
        sendController = NormalPointSendPointDialogController.getInstance();
        passwordDialog = new NormalPointPasswordDialog(getContext());
        cancelButton.setOnClickListener(cancleClick);
        nextButton.setOnClickListener(submitClick);
        dialog = NormalPointSendPointDialog.this;
        this.getWindow().setBackgroundDrawable(new ColorDrawable(Color.TRANSPARENT));
    }


    private View.OnClickListener submitClick = new View.OnClickListener() {
        @Override
        public void onClick(View v) {
            boolean check = true;
            check &= pointEdit.getText().length() > 0;
            check &= phoneEdit.getText().length() > 0;
            check &= emailEdit.getText().length() > 0;
            if (check) {
                sendController.setEmail(emailEdit.getText().toString().trim());
                sendController.setPhone(phoneEdit.getText().toString().trim());
                sendController.setPoint(pointEdit.getText().toString().trim());
                passwordDialog.show();
                dialog.dismiss();
            } else {
                Toast.makeText(getContext(), R.string.point_dialog_empty, Toast.LENGTH_LONG).show();
            }

        }
    };

    private View.OnClickListener cancleClick = new View.OnClickListener() {
        @Override
        public void onClick(View v) {
            dialog.dismiss();
        }
    };

}
