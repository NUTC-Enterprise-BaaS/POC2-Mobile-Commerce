package com.poc2.contrube.view.guest;

import android.app.DatePickerDialog;
import android.content.Context;
import android.content.Intent;
import android.content.res.Configuration;
import android.content.res.Resources;
import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.SwitchCompat;
import android.text.InputType;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.CheckBox;
import android.widget.CompoundButton;
import android.widget.DatePicker;
import android.widget.EditText;
import android.widget.RelativeLayout;
import android.widget.Spinner;
import android.widget.TextView;
import android.widget.Toast;

import com.androidlibrary.component.dialog.ChooseDateDialog;
import com.poc2.R;
import com.poc2.component.dialog.PrivacyDialog;
import com.poc2.contrube.controllor.register.RegisterController;
import com.poc2.contrube.view.normal.ActivityNormalRegisterRecommend;

/**
 * Created by cheng on 2016/11/1.
 */
public class ActivityRegister extends AppCompatActivity {
    private final int SCAN = 0;
    private Context mContext;
    private Spinner countrySpinner;
    private EditText nameEdit;
    private EditText emailEdit;
    private EditText phoneEdit;
    private EditText passwordEdit;
    private SwitchCompat passwordSwitch;
    private EditText birthEdit;
    private EditText authenticateEdit;
    private TextView authenticateButton;
    private Button clearButton;
    private Button submitButton;
    private EditText scanEdit;
    private Button scanButton;

    private StringBuffer stringBuffer;
    private String verifyCode;
    public Boolean isVerifyCodeShow;
    private ArrayAdapter<String> countryList;
    private ChooseDateDialog chooseDateDialog;
    private PrivacyDialog privacyDialog;
    private RegisterController registerController;
    private String recommendId;
    private RelativeLayout container;
    private View privacy;
    private TextView disAgree;
    private TextView agree;
    private CheckBox checkBox;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_register);
        findView();
        initSystemFont();
        init();
    }

    private void findView() {
        container = (RelativeLayout) findViewById(R.id.container);
        countrySpinner = (Spinner) findViewById(R.id.register_spinner_country);
        nameEdit = (EditText) findViewById(R.id.register_name_edit);
        phoneEdit = (EditText) findViewById(R.id.register_account_edit);
        emailEdit = (EditText) findViewById(R.id.register_mail);
        passwordEdit = (EditText) findViewById(R.id.register_password_edit);
        passwordSwitch = (SwitchCompat) findViewById(R.id.register_password_switch);
        birthEdit = (EditText) findViewById(R.id.register_birthday_edit);
        authenticateEdit = (EditText) findViewById(R.id.register_authenticate_edit);
        authenticateButton = (TextView) findViewById(R.id.register_authenticate_button);
        clearButton = (Button) findViewById(R.id.register_reset);
        submitButton = (Button) findViewById(R.id.register_send);
        scanButton = (Button) findViewById(R.id.register_recommend_button);
        scanEdit = (EditText) findViewById(R.id.register_recommend_edit);
        nameEdit.setSingleLine(true);
        emailEdit.setSingleLine(true);

        privacy = LayoutInflater.from(ActivityRegister.this).inflate(R.layout.dialog_privacy, null);
        disAgree = (TextView) privacy.findViewById(R.id.disagree);
        agree = (TextView) privacy.findViewById(R.id.agree);
        checkBox = (CheckBox) privacy.findViewById(R.id.check);
    }

    private void init() {
        mContext = this;
        verifyCode = "";
        recommendId = "";
        isVerifyCodeShow = false;
        stringBuffer = new StringBuffer();
        generateRandom(4);

        privacyDialog = new PrivacyDialog(mContext, android.R.style.Theme_Light);
        chooseDateDialog = new ChooseDateDialog(mContext);
        chooseDateDialog.setdateSetListener(dateSetListener);
        countryList = new ArrayAdapter<String>(mContext, R.layout.register_spinner_item, mContext.getResources().getStringArray(R.array.registered_layout_country));
        countryList.setDropDownViewResource(R.layout.register_spinner_item);
        countrySpinner.setAdapter(countryList);
        passwordSwitch.setOnCheckedChangeListener(pwdSwitchClick);
        submitButton.setOnClickListener(submitClick);
        authenticateButton.setOnClickListener(authenticateClick);
        birthEdit.setOnClickListener(birthClick);
        clearButton.setOnClickListener(clearClick);
        registerController = new RegisterController(mContext);
        registerController.setmCallBackEvent(callBackEvent);
        scanButton.setOnClickListener(scanClick);
        agree.setOnClickListener(agreeClick);
        disAgree.setOnClickListener(disAgreeClick);
    }

    private View.OnClickListener disAgreeClick = new View.OnClickListener() {
        @Override
        public void onClick(View v) {
            container.removeView(privacy);
        }
    };

    private View.OnClickListener agreeClick = new View.OnClickListener() {
        @Override
        public void onClick(View v) {
            if (checkBox.isChecked()) {
                String country = countrySpinner.getSelectedItem().toString();
                String name = nameEdit.getText().toString().trim();
                String phone = phoneEdit.getText().toString().trim();
                String email = emailEdit.getText().toString().trim();
                String password = passwordEdit.getText().toString().trim();
                String birth = birthEdit.getText().toString().trim();
                registerController.setRegisterData(country, name, phone, email, password, birth, recommendId);
                registerController.registerRequest();
            } else {
                String content = getString(R.string.privacy_policy_check_button);
                Toast.makeText(mContext, content, Toast.LENGTH_LONG).show();
            }
        }
    };

    private View.OnClickListener scanClick = new View.OnClickListener() {
        @Override
        public void onClick(View v) {
            Intent intent = new Intent();
            intent.setClass(ActivityRegister.this, ActivityNormalRegisterRecommend.class);
            startActivityForResult(intent, SCAN);
        }
    };

    private View.OnClickListener clearClick = new View.OnClickListener() {
        @Override
        public void onClick(View v) {
            nameEdit.setText("");
            phoneEdit.setText("");
            emailEdit.setText("");
            passwordEdit.setText("");
            birthEdit.setText("");
            authenticateEdit.setText("");
        }
    };

    private RegisterController.CallBackEvent callBackEvent = new RegisterController.CallBackEvent() {
        @Override
        public void onError() {

        }

        @Override
        public void onSuccess() {
            finish();
        }
    };

    private View.OnClickListener birthClick = new View.OnClickListener() {
        @Override
        public void onClick(View v) {
            chooseDateDialog.show();
        }
    };

    private DatePickerDialog.OnDateSetListener dateSetListener = new DatePickerDialog.OnDateSetListener() {
        @Override
        public void onDateSet(DatePicker view, int year, int monthOfYear, int dayOfMonth) {
            birthEdit.setText(year + "/" + (monthOfYear + 1) + "/" + dayOfMonth);
        }
    };

    protected View.OnClickListener submitClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {

            if (checkSum()) {
                container.addView(privacy);
            }
        }
    };

    private boolean checkSum() {
        String name = nameEdit.getText().toString().trim();
        String password = passwordEdit.getText().toString().trim();
        String email = emailEdit.getText().toString().trim();
        String phone = phoneEdit.getText().toString().trim();
        String birth = birthEdit.getText().toString().trim();


        if (name.equals("") || password.equals("") || email.equals("") || phone.equals("")) {
            String content = getString(R.string.register_dialog_error_register_empty);
            Toast.makeText(mContext, content, Toast.LENGTH_LONG).show();
            return false;
        } else if (!android.util.Patterns.EMAIL_ADDRESS.matcher(email).matches()) {
            String content = getString(R.string.authenticate_dialog_error_mail_style);
            Toast.makeText(mContext, content, Toast.LENGTH_LONG).show();
            return false;
        } else if (passwordEdit.getText().length() < 6) {
            String content = getString(R.string.register_dialog_error_password_short);
            Toast.makeText(mContext, content, Toast.LENGTH_LONG).show();
            return false;
        } else if (authenticateEdit.getText().toString().trim().equals("")) {
            String content = getString(R.string.authenticate_dialog_login_fail);
            Toast.makeText(mContext, content, Toast.LENGTH_LONG).show();
            return false;
        } else if (!(authenticateEdit.getText().toString().trim().equals(verifyCode))) {
            String content = getString(R.string.authenticate_dialog_error_tittle);
            Toast.makeText(mContext, content, Toast.LENGTH_LONG).show();
            return false;
        }
        return true;
    }

    private CompoundButton.OnCheckedChangeListener pwdSwitchClick = new CompoundButton.OnCheckedChangeListener() {
        @Override
        public void onCheckedChanged(CompoundButton compoundButton, boolean isChecked) {
            if (isChecked) {
                passwordEdit.setInputType(InputType.TYPE_CLASS_TEXT | InputType.TYPE_TEXT_VARIATION_VISIBLE_PASSWORD);
            } else {
                passwordEdit.setInputType(InputType.TYPE_CLASS_TEXT | InputType.TYPE_TEXT_VARIATION_PASSWORD);
            }
        }
    };

    private View.OnClickListener authenticateClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            generateRandom(4);
        }
    };

    public void generateRandom(int count) {
        int random = 0;
        stringBuffer.setLength(0);
        for (int i = 0; i < count; i++) {
            random = (int) (Math.random() * 10);
            stringBuffer.append(String.valueOf(random));
        }
        verifyCode = stringBuffer.toString();
        authenticateButton.setText(verifyCode);
    }

    @Override
    protected void onActivityResult(int requestCode, int resultCode, Intent data) {
        super.onActivityResult(requestCode, resultCode, data);
        if (data != null) {
            switch (requestCode) {
                case SCAN:
                    scanEdit.setText(data.getExtras().getString("phone"));
                    recommendId = data.getExtras().getString("id");
                    break;
            }
        }
    }

    private void initSystemFont() {
        Resources res = getResources();
        Configuration config = new Configuration();
        config.setToDefaults();
        res.updateConfiguration(config, res.getDisplayMetrics());
        float scale = getResources().getConfiguration().fontScale;
        Log.e("scale", scale + "");
    }
}