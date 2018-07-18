package com.herbhousesgobuyother.contrube.view.guest;

import android.Manifest;
import android.app.AlertDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.res.Configuration;
import android.content.res.Resources;
import android.os.Bundle;
import android.provider.Settings;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.SwitchCompat;
import android.text.InputType;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.CompoundButton;
import android.widget.EditText;
import android.widget.RelativeLayout;
import android.widget.TextView;
import android.widget.Toast;

import com.androidlibrary.core.ActivityLauncher;
import com.androidlibrary.module.PermissionsActivity;
import com.androidlibrary.module.PermissionsChecker;
import com.androidlibrary.module.backend.params.AccountInjection;
import com.herbhousesgobuyother.R;
import com.herbhousesgobuyother.component.pre.PreferencesHelperImp;
import com.herbhousesgobuyother.contrube.component.fingerprint.FingerprintAuthenticationDialogFragment;
import com.herbhousesgobuyother.contrube.controllor.login.LoginController;

/**
 * Created by cheng on 2016/11/1.
 */
public class ActivityLogin extends AppCompatActivity {
    private Context mContext;
    private EditText accountEdit;
    private EditText passwordEdit;
    private EditText authenticateEdit;
    private RelativeLayout keepContainer;
    private RelativeLayout httpsContainer;
    private SwitchCompat passwordSwitch;
    private SwitchCompat keepSwitch;
    private SwitchCompat httpsSwitch;
    private TextView forgetText;
    private TextView authenticateButton;
    private TextView authenticateText;
    private TextView httpsText;
    private TextView keepText;
    private Button registerButton;
    private Button submitButton;
    private Button fingerPrintButton;
    private static final String DIALOG_FRAGMENT_TAG = "myFragment";

    private StringBuffer stringBuffer;
    private String verifyCode;
    public Boolean isVerifyCodeShow;
    private LoginController loginController;
    private final String[] permission = new String[]{
            Manifest.permission.VIBRATE,
            Manifest.permission.INTERNET,
            Manifest.permission.CAMERA,
            Manifest.permission.CALL_PHONE,
            Manifest.permission.WAKE_LOCK,
            Manifest.permission.ACCESS_COARSE_LOCATION,
            Manifest.permission.ACCESS_FINE_LOCATION,
            Manifest.permission.WRITE_EXTERNAL_STORAGE
    };
    private static final int REQUEST_CODE_ASK_PERMISSIONS = 0;
    private PermissionsChecker permissionsChecker;
    private AccountInjection accountInjection;

    private PreferencesHelperImp mPreferencesHelperImp;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login);
        permissionsChecker = new PermissionsChecker(this);
        if (permissionsChecker.missingPermissions(permission)) {
            startPermissionsActivity();
        } else {
            findView();
            initSystemFont();
            init();
            Log.e("getSizeName", getSizeName(mContext));
        }
    }

    private void findView() {
        accountEdit = (EditText) findViewById(R.id.login_account_edit);
        passwordEdit = (EditText) findViewById(R.id.login_password_edit);
        authenticateEdit = (EditText) findViewById(R.id.login_authenticate_edit);
        keepContainer = (RelativeLayout) findViewById(R.id.login_keep_container);
        httpsContainer = (RelativeLayout) findViewById(R.id.login_https_container);
        passwordSwitch = (SwitchCompat) findViewById(R.id.login_password_switch);
        keepText = (TextView) findViewById(R.id.login_keep_text);
        httpsText = (TextView) findViewById(R.id.login_https_text);
        keepSwitch = (SwitchCompat) findViewById(R.id.login_keep_switch);
        httpsSwitch = (SwitchCompat) findViewById(R.id.login_https_switch);
        forgetText = (TextView) findViewById(R.id.login_forget);
        authenticateButton = (TextView) findViewById(R.id.login_authenticate_button);
        registerButton = (Button) findViewById(R.id.login_register);
        submitButton = (Button) findViewById(R.id.login_submit);
        authenticateText = (TextView) findViewById(R.id.login_authenticate_text);
        fingerPrintButton = findViewById(R.id.text_finger_print);
        accountEdit.setSingleLine(true);
    }

    private void init() {
        mContext = this;
        verifyCode = "";
        isVerifyCodeShow = false;
        stringBuffer = new StringBuffer();
        generateRandom(4);
        accountInjection = new AccountInjection(mContext);
        loginController = new LoginController(mContext);
        httpsContainer.setOnClickListener(httpsClick);
        keepContainer.setOnClickListener(keepClick);
        forgetText.setOnClickListener(forgetClick);
        authenticateButton.setOnClickListener(authenticateClick);
        registerButton.setOnClickListener(registerClick);
        submitButton.setOnClickListener(submitClick);
        passwordSwitch.setOnCheckedChangeListener(pwdSwitchClick);
        loginController.setmCallBackEvent(callBackEvent);
        fingerPrintButton.setOnClickListener(fingerPrintClick);
        loadAccount();
        //      autoLogin();
        mPreferencesHelperImp = new PreferencesHelperImp(this);
    }

    protected View.OnClickListener fingerPrintClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            if (mPreferencesHelperImp.getIsUseFingerPrint()&&accountInjection.loadIsKeepLogin()) {
                FingerprintAuthenticationDialogFragment fragment
                        = new FingerprintAuthenticationDialogFragment();
                fragment.show(getFragmentManager(), DIALOG_FRAGMENT_TAG);
            } else {
                new AlertDialog.Builder(ActivityLogin.this)
                        .setTitle(R.string.login_finger_print_dialog_title)
                        .setMessage(R.string.login_finger_print_dialog_message)
                        .setNeutralButton(R.string.login_finger_print_dialog_sure, new DialogInterface.OnClickListener() {
                            @Override
                            public void onClick(DialogInterface dialog, int which) {
                                dialog.dismiss();
                            }
                        })
                        .show();
                return;
            }
        }
    };

    public void onPurchased() {
        autoLogin();
    }

    private void autoLogin() {
        if (accountInjection.loadIsKeepLogin()) {
            loginController.setAutoLoginState(true);
            String account = loginController.getAccount();
            String password = loginController.getPassword();
            loginController.loginRequest(account, password);
        }
    }

    private LoginController.CallBackEvent callBackEvent = new LoginController.CallBackEvent() {
        @Override
        public void onError() {
            isVerifyCodeShow = true;
            authenticateShow();
        }

        @Override
        public void onSuccess() {
            finish();
        }
    };

    protected View.OnClickListener registerClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            ActivityLauncher.go(mContext, ActivityRegister.class, null);

        }
    };

    protected View.OnClickListener submitClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            if (checkSum()) {
                String account = accountEdit.getText().toString().trim();
                String password = passwordEdit.getText().toString().trim();
                boolean autoLoginState = keepSwitch.isChecked();
                loginController.setAutoLoginState(autoLoginState);
                loginController.loginRequest(account, password);
            }
        }
    };

    private boolean checkSum() {
        String account = accountEdit.getText().toString().trim();
        String password = passwordEdit.getText().toString().trim();

        if (account.equals("") || password.equals("")) {
            String content = getString(R.string.login_dialog_error_login_empty);
            Toast.makeText(mContext, content, Toast.LENGTH_LONG).show();
            return false;
        } else if ((authenticateEdit.getVisibility() == View.VISIBLE) && authenticateEdit.getText().toString().trim().equals("")) {
            String content = getString(R.string.authenticate_dialog_login_fail);
            Toast.makeText(mContext, content, Toast.LENGTH_LONG).show();
            return false;
        } else if ((authenticateEdit.getVisibility() == View.VISIBLE) && !(authenticateEdit.getText().toString().trim().equals(verifyCode))) {
            String content = getString(R.string.authenticate_dialog_error_tittle);
            Toast.makeText(mContext, content, Toast.LENGTH_LONG).show();
            return false;
        }
        return true;
    }

    private View.OnClickListener authenticateClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            generateRandom(4);

        }
    };

    private View.OnClickListener forgetClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            ActivityLauncher.go(mContext, ActivityForgetPassword.class, null);
        }
    };

    private View.OnClickListener httpsClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            if (httpsSwitch.isChecked()) {
                httpsSwitch.setChecked(false);
            } else {
                httpsSwitch.setChecked(true);
            }
        }
    };

    private View.OnClickListener keepClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            if (keepSwitch.isChecked()) {
                keepSwitch.setChecked(false);
            } else {
                keepSwitch.setChecked(true);
            }
        }
    };


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

    public void authenticateShow() {
        if (isVerifyCodeShow) {
            authenticateText.setVisibility(View.VISIBLE);
            authenticateEdit.setVisibility(View.VISIBLE);
            authenticateButton.setVisibility(View.VISIBLE);
        } else {
            authenticateText.setVisibility(View.INVISIBLE);
            authenticateEdit.setVisibility(View.INVISIBLE);
            authenticateButton.setVisibility(View.INVISIBLE);
        }
    }

    private void loadAccount() {
        accountEdit.setText(loginController.getAccount());
    }

    /***
     * go to ask user turn on permissions page.
     */
    private void startPermissionsActivity() {
        PermissionsActivity.startPermissionsForResult(this, REQUEST_CODE_ASK_PERMISSIONS, permission);
    }

    @Override
    protected void onActivityResult(int requestCode, int resultCode, Intent data) {
        super.onActivityResult(requestCode, resultCode, data);
        if (requestCode == REQUEST_CODE_ASK_PERMISSIONS && resultCode == PermissionsActivity.PERMISSIONS_REFUSE) {
            finish();
        } else {
            findView();
            initSystemFont();
            init();
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

    private static String getSizeName(Context context) {
        int screenLayout = context.getResources().getConfiguration().screenLayout;
        screenLayout &= Configuration.SCREENLAYOUT_SIZE_MASK;

        switch (screenLayout) {
            case Configuration.SCREENLAYOUT_SIZE_SMALL:
                return "small";
            case Configuration.SCREENLAYOUT_SIZE_NORMAL:
                return "normal";
            case Configuration.SCREENLAYOUT_SIZE_LARGE:
                return "large";
            case Configuration.SCREENLAYOUT_SIZE_XLARGE:
                return "xlarge";
            default:
                return "undefined";
        }
    }
}