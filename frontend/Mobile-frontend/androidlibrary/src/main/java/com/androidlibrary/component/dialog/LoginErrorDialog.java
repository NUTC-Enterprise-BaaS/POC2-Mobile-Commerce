package com.androidlibrary.component.dialog;

import android.app.AlertDialog;
import android.content.Context;

import com.androidlibrary.R;


/**
 * Created by ameng on 2016/5/22.
 */
public class LoginErrorDialog extends AlertDialog.Builder {
    public LoginErrorDialog(Context context) {
        super(context);
        this.setTitle(R.string.login_dialog_error_tittle);
    }

    public void showEmailOrVerifyCodeError(){
        setMessage(R.string.login_error_dialog_email_or_verifycode);
        show();
    }

    public void showEmailOrPasswordError(){
        setMessage(R.string.login_error_dialog_correct_email_or_password);
        show();
    }public void showCorrectEmailError(){
        setMessage(R.string.login_error_dialog_correct_email);
        show();
    }

    public void showPasswordLenghtError(){
        setMessage(R.string.login_error_dialog_password_lenght);
        show();
    }

    public void showPasswordNotSameError(){
        setMessage(R.string.login_error_dialog_password_not_same);
        show();
    }

    public void showAuthorError(){
        setMessage(R.string.login_error_dialog_auth_error);
        show();
    }

    public void showEmailPhoneError(){
        this.setTitle("");
        setMessage(R.string.login_error_dialog_phone_email_error);
        show();
    }

    public void showSameEmailPhoneError(){
        this.setTitle("");
        setMessage(R.string.login_error_dialog_same_phone_email_error);
        show();
    }

    public void showTheSamePasswordError(){
        this.setTitle("");
        setMessage(R.string.login_error_dialog_password_same_error);
        show();
    }
    public void showAlreadyRegisterError(){
        this.setTitle("");
        setMessage(R.string.login_error_dialog_already_register);
        show();
    }
}
