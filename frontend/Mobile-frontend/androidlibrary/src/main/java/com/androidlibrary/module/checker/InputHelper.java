package com.androidlibrary.module.checker;

import android.content.Context;
import android.widget.TextView;

import com.androidlibrary.R;
import com.androidlibrary.component.dialog.LoginErrorDialog;

import java.util.ArrayList;
import java.util.HashMap;

/**
 * Created by ameng on 2016/5/23.
 */
public class InputHelper {
    public static final String EMAIL = "email";
    public static final String PASSWORD = "password";
    public static final String PASSWORD_AGAIN = "password_again";
    public static final String AUTHENTICATE = "authenticate";
    public static final String NONE = "none";
    private ArrayList<HashMap<String, TextView>> editGroup;
    private LoginErrorDialog errorDialog;
    private OnEmptyListener onEmptyListener;
    private int passLength;
    private String authenticateCode;
    private String password;

    public interface OnEmptyListener {
        void dealEmpty(int position);
    }

    public InputHelper(Context context) {
        editGroup = new ArrayList<>();
        errorDialog = new LoginErrorDialog(context);
        authenticateCode = "";
    }

    public void addEdit(TextView editText, String patternTag) {
        HashMap<String, TextView> editHashGroup = new HashMap<>();
        editHashGroup.put(patternTag, editText);
        editGroup.add(editHashGroup);
    }

    public void setPasswordLength(int length) {
        this.passLength = length;
    }

    public void setAuthenticate(String authenticate) {
        this.authenticateCode = authenticate;
    }

    public Boolean checked() {
        for (int i = 0; i < editGroup.size(); i++) {
            for (String key : editGroup.get(i).keySet()) {
                TextView editText = editGroup.get(i).get(key);
                if (editText.getText().toString().trim().isEmpty()) {
                    onEmptyListener.dealEmpty(i);
                    return false;
                }
            }
        }

        for (int i = 0; i < editGroup.size(); i++) {
            for (String key : editGroup.get(i).keySet()) {
                TextView editText = editGroup.get(i).get(key);
                if (!checkType(key, editText)) {
                    return false;
                }
            }
        }

        return true;
    }

    public void cleanAllInput() {
        for (int i = 0; i < editGroup.size(); i++) {
            for (String key : editGroup.get(i).keySet()) {
                editGroup.get(i).get(key).setText("");
            }
        }
    }

    private Boolean checkType(String type, TextView value) {
        Boolean isType = true;
        switch (type) {
            case EMAIL:
                if (!android.util.Patterns.EMAIL_ADDRESS.matcher(value.getText().toString()).matches()) {
                    errorDialog.setMessage(R.string.authenticate_dialog_error_mail_style);
                    errorDialog.show();
                    isType = false;
                }
                break;
            case PASSWORD:
                password = value.getText().toString().trim();
                if ((value.getText().toString()).length() < passLength) {
                    errorDialog.setMessage(R.string.authenticate_dialog_error_password_length);
                    errorDialog.show();
                    isType = false;
                }
                break;
            case PASSWORD_AGAIN:
                if (!(value.getText().toString().trim()).equals(password)) {
                    errorDialog.setMessage(R.string.login_error_dialog_password_not_same);
                    errorDialog.show();
                    isType = false;
                }
                break;
            case AUTHENTICATE:
                if (authenticateCode.equals("")) {
                    return true;
                }
                if (!(value.getText().toString().trim()).equals(authenticateCode)) {
                    errorDialog.setMessage(R.string.authenticate_dialog_error_auth);
                    errorDialog.show();
                    isType = false;
                }
                break;
        }
        return isType;
    }

    public void setOnEmptyListener(OnEmptyListener listener) {
        this.onEmptyListener = listener;
    }
}
