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

import com.androidlibrary.module.backend.data.ApiV1GeneralSendPointPostData;
import com.herbhousesgobuyother.R;
import com.herbhousesgobuyother.contrube.controllor.point.NormalPointPasswordDialogController;

import static com.herbhousesgobuyother.contrube.controllor.point.NormalPointSendPointDialogController.sendPointDialogController;

/**
 * Created by 依杰 on 2016/11/15.
 */

public class NormalPointPasswordDialog extends Dialog {
    private Button cancelButton;
    private Button submitButton;
    private EditText passwordEdit;
    private NormalPointPasswordDialog dialog;
    private NormalPointCheckDialog checkDialog;
    private NormalPointPasswordDialogController pointPasswordDialogController;
    private String password;

    public NormalPointPasswordDialog(Context context) {
        super(context);
    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.dialog_normal_point_password);
        init();
    }

    private void init() {
        findView();
        dialog = NormalPointPasswordDialog.this;
        pointPasswordDialogController = new NormalPointPasswordDialogController(getContext());
        checkDialog = new NormalPointCheckDialog(getContext());
        submitButton.setOnClickListener(submitClick);
        cancelButton.setOnClickListener(cancelClick);
        pointPasswordDialogController.setCallBackEvent(callBackEcent);
        this.getWindow().setBackgroundDrawable(new ColorDrawable(Color.TRANSPARENT));
    }

    private void findView() {
        passwordEdit = (EditText) findViewById(R.id.fragment_normal_point_dialog_password_input_point_edit);
        cancelButton = (Button) findViewById(R.id.fragment_normal_point_dialog_password_cancle_button);
        submitButton = (Button) findViewById(R.id.fragment_normal_point_dialog_password_submit_button);
    }

    private NormalPointPasswordDialogController.CallBackEvent callBackEcent = new NormalPointPasswordDialogController.CallBackEvent() {
        @Override
        public void onError() {

        }

        @Override
        public void onSuccess(ApiV1GeneralSendPointPostData information) {
            dialog.dismiss();
            checkDialog.show();
        }
    };

    private View.OnClickListener submitClick = new View.OnClickListener() {
        @Override
        public void onClick(View v) {
            if (passwordEdit.getText().length() > 0) {
                password = passwordEdit.getText().toString().trim();
                pointPasswordDialogController.sendPoint(password, sendPointDialogController.getPoint(), sendPointDialogController.getPhone(), sendPointDialogController.getEmail());
            } else {
                Toast.makeText(getContext(), R.string.point_dialog_empty, Toast.LENGTH_LONG).show();
            }
        }
    };

    private View.OnClickListener cancelClick = new View.OnClickListener() {
        @Override
        public void onClick(View v) {
            dialog.dismiss();
        }
    };

}
