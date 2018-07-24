package com.herbhousesgobuyother.contrube.view.guest;

import android.content.Context;
import android.content.res.Configuration;
import android.content.res.Resources;
import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

import com.androidlibrary.core.ActivityLauncher;
import com.androidlibrary.module.backend.data.ApiV1UserPasswordForgotVerifyCodePostData;
import com.herbhousesgobuyother.R;
import com.herbhousesgobuyother.contrube.controllor.forgetpassword.ForgetPasswordController;

/**
 * Created by ameng on 11/1/16.
 */

public class ActivityForgetPassword extends AppCompatActivity {
    private Context mContext;
    private Button sendcodeButton;
    private Button checkandnextButton;
    private EditText emailEdit;
    private EditText code;

    private ForgetPasswordController controller;
    private String email;
    private String checkCode;
    private View back;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.fragment_forgetpassword);
        findView();
        initSystemFont();
        init();
    }

    private void findView() {
        back = findViewById(R.id.toolbar_back_touch);
        sendcodeButton = (Button) findViewById(R.id.activity_forget_password_send_code_button);
        checkandnextButton = (Button) findViewById(R.id.activity_forget_password_next_button);
        emailEdit = (EditText) findViewById(R.id.activity_forget_password_account_edittext);
        code = (EditText) findViewById(R.id.activity_forget_password_enter_code_edittext);
        emailEdit.setSingleLine(true);
        code.setSingleLine(true);
    }

    private void init() {
        mContext = this;
        email = "";
        checkCode = "";
        controller = new ForgetPasswordController(mContext);
        sendcodeButton.setOnClickListener(sendCodeClick);
        checkandnextButton.setOnClickListener(nextClick);
        controller.setmCallBackEvent(callBackEvent);
        back.setOnClickListener(backClick);

    }

    private ForgetPasswordController.CallBackEvent callBackEvent = new ForgetPasswordController.CallBackEvent() {
        @Override
        public void onError() {

        }

        @Override
        public void onSuccess(ApiV1UserPasswordForgotVerifyCodePostData information) {
            if (information.result == 0) {
                Toast.makeText(mContext, R.string.verify_code_access, Toast.LENGTH_SHORT).show();

                Bundle args = new Bundle();
                args.putString("0", email);
                args.putString("1", checkCode);
                ActivityLauncher.go(mContext, ActivityForgetChangePwd.class, args);
                finish();
            } else if (information.messageGroup.get(0).toString().equals("The verify_code is incorrect")) {
                Toast.makeText(mContext, R.string.verify_code_fail, Toast.LENGTH_SHORT).show();
            } else if (information.messageGroup.get(0).toString().equals("This email does not exist")) {
                Toast.makeText(mContext, R.string.email_not_exist, Toast.LENGTH_SHORT).show();
            }
        }
    };

    private View.OnClickListener nextClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            if (checkCode()) {
                email = emailEdit.getText().toString().trim();
                checkCode = code.getText().toString().trim();
                controller.nextRequest(email, checkCode);
            }
        }
    };

    private View.OnClickListener sendCodeClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            if (checkEmail()) {
                email = emailEdit.getText().toString().trim();
                controller.sendCodeRequest(email);
            }
        }
    };

    private boolean checkEmail() {
        email = emailEdit.getText().toString().trim();

        if (email.equals("")) {
            String content = getString(R.string.register_dialog_error_register_empty);
            Toast.makeText(mContext, content, Toast.LENGTH_LONG).show();
            return false;
        } else if (!android.util.Patterns.EMAIL_ADDRESS.matcher(email).matches()) {
            String content = getString(R.string.authenticate_dialog_error_mail_style);
            Toast.makeText(mContext, content, Toast.LENGTH_LONG).show();
            return false;
        }
        return true;
    }

    private boolean checkCode() {
        checkCode = code.getText().toString().trim();

        if (checkCode.equals("")) {
            String content = getString(R.string.register_dialog_error_register_empty);
            Toast.makeText(mContext, content, Toast.LENGTH_LONG).show();
            return false;
        }
        return true;
    }

    private void initSystemFont() {
        Resources res = getResources();
        Configuration config = new Configuration();
        config.setToDefaults();
        res.updateConfiguration(config, res.getDisplayMetrics());
        float scale = getResources().getConfiguration().fontScale;
        Log.e("scale", scale + "");
    }

    private View.OnClickListener backClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            finish();
        }
    };
}
