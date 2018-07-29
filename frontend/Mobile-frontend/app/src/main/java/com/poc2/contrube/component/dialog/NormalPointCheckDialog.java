package com.poc2.contrube.component.dialog;

import android.app.Dialog;
import android.content.Context;
import android.graphics.Color;
import android.graphics.drawable.ColorDrawable;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;

import com.poc2.R;

import static com.poc2.contrube.controllor.point.NormalPointSendPointDialogController.sendPointDialogController;

/**
 * Created by 依杰 on 2016/11/15.
 */

public class NormalPointCheckDialog extends Dialog {
    private TextView pointText;
    private TextView phoneText;
    private TextView emailText;
    private Button confirmButton;
    private NormalPointCheckDialog dialog;

    public NormalPointCheckDialog(Context context) {
        super(context);
    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.dialog_normal_point_check_point);
        findView();
        init();
    }

    private void init() {
        dialog = NormalPointCheckDialog.this;
        setText();
        confirmButton.setOnClickListener(confirmEvent);
        this.getWindow().setBackgroundDrawable(new ColorDrawable(Color.TRANSPARENT));
    }

    private void setText() {
        pointText.setText(getContext().getString(R.string.point_dialog_check_point, Integer.valueOf(sendPointDialogController.getPoint())));
        phoneText.setText(sendPointDialogController.getPhone());
        emailText.setText(sendPointDialogController.getEmail());
    }

    private View.OnClickListener confirmEvent = new View.OnClickListener() {
        @Override
        public void onClick(View v) {
            dialog.dismiss();
        }
    };

    private void findView() {
        pointText = (TextView) findViewById(R.id.fragment_normal_point_dialog_check_point_text);
        phoneText = (TextView) findViewById(R.id.fragment_normal_point_dialog_check_phone_text);
        emailText = (TextView) findViewById(R.id.fragment_normal_point_dialog_check_email_text);
        confirmButton = (Button) findViewById(R.id.fragment_normal_point_dialog_check_confirm_button);
    }

}
