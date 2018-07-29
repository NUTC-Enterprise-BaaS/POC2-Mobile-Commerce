package com.poc2.contrube.view.guest;

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

import com.androidlibrary.module.backend.data.ApiV1UserRescuePasswordPostData;
import com.poc2.R;
import com.poc2.contrube.controllor.forgetchangepwd.ForgetChangePwdController;

/**
 * Created by 依杰 on 2016/11/19.
 */

public class ActivityForgetChangePwd extends AppCompatActivity {
    private Context mContext;

    private Button restartButton;
    private EditText newPasswordEditText;
    private EditText againNewPasswordEditText;
    private String pwd;
    private String pwdCheck;
    private View back;

    private ForgetChangePwdController controller;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.fragment_normal_change_password);
        findView();
        initSystemFont();
        init();
    }

    private void findView() {
        back = findViewById(R.id.toolbar_back_touch);
        restartButton = (Button) findViewById(R.id.fragment_normal_change_password_next_Image_Button);
        newPasswordEditText = (EditText) findViewById(R.id.fragment_normal_change_password_new_password_input_check_edittext);
        againNewPasswordEditText = (EditText) findViewById(R.id.fragment_normal_change_password_new_password_input_edittext);
        newPasswordEditText.setSingleLine(true);
        againNewPasswordEditText.setSingleLine(true);
    }

    private void init() {
        mContext = this;
        controller = new ForgetChangePwdController(mContext);
        restartButton.setOnClickListener(restartClick);
        controller.setmCallBackEvent(callBackEvent);
        back.setOnClickListener(backClick);

    }

    private ForgetChangePwdController.CallBackEvent callBackEvent = new ForgetChangePwdController.CallBackEvent() {
        @Override
        public void onError() {

        }

        @Override
        public void onSuccess(ApiV1UserRescuePasswordPostData information) {
            if (information.result == 0) {
                String result = mContext.getResources().getString(com.androidlibrary.R.string.reset_password_susess);
                Toast.makeText(mContext, result, Toast.LENGTH_LONG).show();
                finish();
            }
        }
    };

    private View.OnClickListener restartClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            if (checkSum()) {
                Bundle args = getIntent().getExtras();
                String email = args.getString("0");
                String checkCode = args.getString("1");
                controller.restartRequest(email, checkCode, pwd, pwdCheck);
            }
        }
    };

    private boolean checkSum() {
        pwd = newPasswordEditText.getText().toString().trim();
        pwdCheck = againNewPasswordEditText.getText().toString().trim();

        if (pwd.equals("") || pwdCheck.equals("")) {
            String content = getString(R.string.register_dialog_error_register_empty);
            Toast.makeText(mContext, content, Toast.LENGTH_LONG).show();
            return false;
        } else if (!pwd.equals(pwdCheck)) {
            String content = getString(R.string.login_error_dialog_password_not_same);
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
