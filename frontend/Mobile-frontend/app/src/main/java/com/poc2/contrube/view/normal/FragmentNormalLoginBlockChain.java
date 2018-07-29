package com.poc2.contrube.view.normal;

import android.app.AlertDialog;
import android.content.DialogInterface;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v4.app.Fragment;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.EditText;

import com.androidlibrary.module.backend.data.ApiV1NormalLDAPAddPostData;
import com.androidlibrary.module.backend.data.ApiV1NormalLDAPLoginPostData;
import com.androidlibrary.module.backend.data.ApiV1NormalStoreListGetData;
import com.poc2.R;
import com.poc2.component.pre.PreferencesHelperImp;
import com.poc2.contrube.component.dialog.LoginErrorDialog;
import com.poc2.contrube.component.fingerprint.FingerprintAuthenticationDialogFragment;
import com.poc2.contrube.controllor.NormalLoginBlockChainControllor;

/**
 * Created by 依杰 on 2018/7/20.
 */

public class FragmentNormalLoginBlockChain extends Fragment {
    private NormalLoginBlockChainControllor controller;
    private EditText mAccountEditText;
    private EditText mPasswordEditText;
    private EditText mAuthEditText;
    private Button mSubmitButton;
    private Button mFingerPrintButton;
    private View back;
    private PreferencesHelperImp mPreferencesHelperImp;
    private static final String DIALOG_FRAGMENT_TAG = "myFragment";
    private LoginErrorDialog mLoginErrorDialog;

    @Nullable
    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        View layout = inflater.inflate(R.layout.fragment_login_block_chain, container, false);
        return layout;
    }

    @Override
    public void onViewCreated(View view, Bundle savedInstanceState) {
        super.onViewCreated(view, savedInstanceState);
        findView();
        init();
    }

    private void init() {
        controller = new NormalLoginBlockChainControllor(getContext());
        back.setOnClickListener(backClick);
        mPreferencesHelperImp = new PreferencesHelperImp(getContext());
        mLoginErrorDialog = new LoginErrorDialog(getContext());
        controller.setmCallBackEvent(callBackEvent);
    }

    private void findView() {
        back = getView().findViewById(R.id.toolbar_back_touch);
        mAccountEditText = getView().findViewById(R.id.edit_token);
        mPasswordEditText = getView().findViewById(R.id.edit_password);
        mAuthEditText = getView().findViewById(R.id.edit_auth);
        mSubmitButton = getView().findViewById(R.id.button_submit);
        mFingerPrintButton = getView().findViewById(R.id.button_fingerprint);
        mSubmitButton.setOnClickListener(submitClickEvent);
        mFingerPrintButton.setOnClickListener(authClickEvent);
    }

    private View.OnClickListener submitClickEvent = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            if (mAccountEditText.getText().toString().isEmpty()) {
                mLoginErrorDialog.setTitle("注意").setMessage("請填寫帳號").show();
                return;
            }
            if (mPasswordEditText.getText().toString().isEmpty()) {
                mLoginErrorDialog.setTitle("注意").setMessage("請填寫密碼").show();
                return;
            }
            if (mAuthEditText.getText().toString().isEmpty()) {
                mLoginErrorDialog.setTitle("注意").setMessage("請填寫身分證字號").show();
                return;
            }
            Log.d("mAuthEditText", mAuthEditText.getText().toString()+"////");
            if (!mAuthEditText.getText().toString().equals("A129128127")) {
                mLoginErrorDialog.setTitle("注意").setMessage("身分證字號錯誤").show();
                return;
            }

            controller.loginLDAP(mAccountEditText.getText().toString(),mPasswordEditText.getText().toString());
        }
    };

    private View.OnClickListener authClickEvent = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            if (mPreferencesHelperImp.getPassword().equals("") || mPreferencesHelperImp.getAccount().equals("")) {
                mLoginErrorDialog.setTitle("注意").setMessage("請先使用LDAP帳號密碼登入後，再到功能設置頁面啟用指紋認證登入功能").show();
            } else {
                if (mPreferencesHelperImp.getIsUseFingerPrint()) {
                    FingerprintAuthenticationDialogFragment fragment
                            = new FingerprintAuthenticationDialogFragment();
                    fragment.show(getActivity().getFragmentManager(), DIALOG_FRAGMENT_TAG);
                } else {
                    new AlertDialog.Builder(getContext())
                            .setTitle(R.string.login_finger_print_dialog_title)
                            .setMessage(R.string.login_finger_print_dialog_message)
                            .setNeutralButton(R.string.login_finger_print_dialog_sure, new DialogInterface.OnClickListener() {
                                @Override
                                public void onClick(DialogInterface dialog, int which) {
                                    dialog.dismiss();
                                }
                            })
                            .show();
                }
            }
        }
    };

    private NormalLoginBlockChainControllor.CallBackEvent callBackEvent = new NormalLoginBlockChainControllor.CallBackEvent() {
        @Override
        public void onError() {

        }

        @Override
        public void onSuccess(ApiV1NormalStoreListGetData information) {
             if (information.message.contains("no")){
                controller.addLDAP(mPreferencesHelperImp.getLDAPToken());
            } else if (information.storeNameGroup.size() == 1) {
                 mPreferencesHelperImp.setAccount(mAccountEditText.getText().toString());
                 mPreferencesHelperImp.setPassword(mPasswordEditText.getText().toString());
                 getActivity().onBackPressed();
                 ((ActivityNormalAdvertisement) getActivity()).goToLdap();
            } else if (information.storeNameGroup.size() > 1){
                 mPreferencesHelperImp.setAccount(mAccountEditText.getText().toString());
                 mPreferencesHelperImp.setPassword(mPasswordEditText.getText().toString());
                 getActivity().onBackPressed();
                 ((ActivityNormalAdvertisement) getActivity()).goToLoginBlockChain();
            }
        }

        @Override
        public void onSuccess(ApiV1NormalLDAPLoginPostData information) {
            if ( information.token != null) {
                controller.checkLdapState();
                mPreferencesHelperImp.setLDAPToken(information.token);
            } else {
                mLoginErrorDialog.setTitle("注意").setMessage("帳號或密碼錯誤").show();
            }
        }

        @Override
        public void onSuccess(ApiV1NormalLDAPAddPostData information) {
            controller.checkLdapState();
        }

    };

    private View.OnClickListener backClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            getActivity().onBackPressed();
        }
    };
}
