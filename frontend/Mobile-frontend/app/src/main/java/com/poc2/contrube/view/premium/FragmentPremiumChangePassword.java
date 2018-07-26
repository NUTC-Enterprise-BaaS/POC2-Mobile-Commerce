package com.poc2.contrube.view.premium;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v4.app.Fragment;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

import com.androidlibrary.module.backend.data.ApiV1UserResetPasswordData;
import com.poc2.R;
import com.poc2.contrube.controllor.changepwd.NormalChangePwdController;
import com.poc2.contrube.controllor.changepwd.PremiumChangePwdController;
import com.poc2.contrube.core.ActivityLauncher;
import com.poc2.contrube.view.guest.ActivityLogin;

/**
 * Created by 依杰 on 2016/11/28.
 */

public class FragmentPremiumChangePassword extends Fragment {
    private EditText nowPwdEdit;
    private EditText newPwdEdit;
    private EditText newPwdCheckEdit;
    private Button clickreloginButton;
    private PremiumChangePwdController controller;
    private String nowPwd;
    private String newPwd;
    private String pwdCheck;
    private View back;

    @Nullable
    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        View layout = inflater.inflate(R.layout.fragment_premium_change_password, container, false);
        return layout;
    }

    @Override
    public void onViewCreated(View view, Bundle savedInstanceState) {
        super.onViewCreated(view, savedInstanceState);
        findView();
        init();
    }

    private void findView() {
        nowPwdEdit = (EditText) getView().findViewById(R.id.fragment_change_password_now_password_edittext);
        newPwdEdit = (EditText) getView().findViewById(R.id.fragment_change_password_new_password_input_edittext);
        newPwdCheckEdit = (EditText) getView().findViewById(R.id.fragment_change_password_new_password_input_check_edittext);
        clickreloginButton = (Button) getView().findViewById(R.id.fragment_change_password_next_Image_Button);
        back = getView().findViewById(R.id.toolbar_back_touch);
        nowPwdEdit.setSingleLine(true);
    }

    private void init() {
        nowPwd = "";
        newPwd = "";
        pwdCheck = "";
        controller = new PremiumChangePwdController(getContext());
        clickreloginButton.setOnClickListener(restartClick);
        controller.setmCallBackEvent(callBackEvent);
        back.setOnClickListener(backClick);

    }

    private NormalChangePwdController.CallBackEvent callBackEvent = new NormalChangePwdController.CallBackEvent() {
        @Override
        public void onError() {

        }

        @Override
        public void onSuccess(ApiV1UserResetPasswordData information) {
            if (information.result == 0) {
                String result = getActivity().getResources().getString(R.string.reset_password_susess);
                Toast.makeText(getActivity(), result, Toast.LENGTH_LONG).show();
                ActivityLauncher.go(getActivity(), ActivityLogin.class, null);
                ((ActivityPremiumAdvertisement) getActivity()).getAccountInjection().clear();
                getActivity().finish();

            } else if (information.result == 2) {
                String content = getString(R.string.request_load_resetpassword_fial);
                Toast.makeText(getActivity(), content, Toast.LENGTH_LONG).show();
            } else if (information.messageGroup.get(0).toString().equals("Update fails, the old and new password are the same")) {
                String content = getString(R.string.request_load_resetpassword_fail);
                Toast.makeText(getActivity(), content, Toast.LENGTH_LONG).show();
            }
        }
    };

    private View.OnClickListener restartClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            if (checkSum()) {
                controller.restartRequest(nowPwd, newPwd);
            }
        }
    };

    private boolean checkSum() {
        nowPwd = nowPwdEdit.getText().toString().trim();
        newPwd = newPwdEdit.getText().toString().trim();
        pwdCheck = newPwdCheckEdit.getText().toString().trim();

        if (newPwd.equals("") || pwdCheck.equals("") || nowPwd.equals("")) {
            String content = getString(R.string.register_dialog_error_register_empty);
            Toast.makeText(getActivity(), content, Toast.LENGTH_LONG).show();
            return false;
        } else if (!newPwd.equals(pwdCheck)) {
            String content = getString(R.string.login_error_dialog_password_not_same);
            Toast.makeText(getActivity(), content, Toast.LENGTH_LONG).show();
            return false;
        } else if (newPwd.length() < 6 || pwdCheck.length() < 6) {
            String content = getString(R.string.register_dialog_error_password_short);
            Toast.makeText(getActivity(), content, Toast.LENGTH_LONG).show();
            return false;
        }
        return true;
    }

    private View.OnClickListener backClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            getActivity().onBackPressed();
        }
    };

}
