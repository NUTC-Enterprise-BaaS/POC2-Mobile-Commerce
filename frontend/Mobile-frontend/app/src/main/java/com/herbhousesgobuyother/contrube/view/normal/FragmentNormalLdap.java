package com.herbhousesgobuyother.contrube.view.normal;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v4.app.Fragment;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

import com.androidlibrary.module.backend.data.ApiV1NormalConnectLdapPostData;
import com.androidlibrary.module.backend.data.ApiV1NormalCreateLdapPostData;
import com.herbhousesgobuyother.R;
import com.herbhousesgobuyother.contrube.controllor.NormalLdapController;
import com.herbhousesgobuyother.contrube.core.FragmentLauncher;

/**
 * Created by 依杰 on 2018/7/13.
 */

public class FragmentNormalLdap extends Fragment {
    private NormalLdapController controller;

    private EditText mTokenEditText;
    private Button mSubmitButton;
    private Button mCreateButton;
    private View back;

    @Nullable
    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        View layout = inflater.inflate(R.layout.fragment_normal_ldap, container, false);
        return layout;
    }

    @Override
    public void onViewCreated(View view, Bundle savedInstanceState) {
        super.onViewCreated(view, savedInstanceState);
        findView();
        init();
    }

    private void init() {
        controller = new NormalLdapController(getContext());
        controller.setmCallBackEvent(callBackEvent);
        back.setOnClickListener(backClick);
    }

    private void findView() {
        back = getView().findViewById(R.id.toolbar_back_touch);
        mTokenEditText = getView().findViewById(R.id.edit_token);
        mSubmitButton = getView().findViewById(R.id.button_submit);
        mCreateButton = getView().findViewById(R.id.button_create);

        mSubmitButton.setOnClickListener(submitClickEvent);
        mCreateButton.setOnClickListener(createClickEvent);
    }

    private View.OnClickListener submitClickEvent = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            if (mTokenEditText.getText().toString().equals("") || mTokenEditText.getText().toString().length() != 10) {
                Toast.makeText(getContext(), "請填寫正確的序號", Toast.LENGTH_LONG).show();
                return;
            }
            controller.connectLdap(mTokenEditText.getText().toString());
        }
    };

    private View.OnClickListener createClickEvent = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            controller.createLdap();
        }
    };

    private NormalLdapController.CallBackEvent callBackEvent = new NormalLdapController.CallBackEvent() {
        @Override
        public void onError() {

        }

        @Override
        public void onSuccess(ApiV1NormalCreateLdapPostData information) {
            if (information.token != null) {
                getActivity().onBackPressed();
                ((ActivityNormalAdvertisement) getActivity()).setAdvertisementEnable(false);
                FragmentLauncher.changeToBack(getContext(), R.id.content_container, null, FragmentGCMRecord.class.getName());
            }
        }

        @Override
        public void onSuccess(ApiV1NormalConnectLdapPostData information) {
            if (information.token != null) {
                getActivity().onBackPressed();
                ((ActivityNormalAdvertisement) getActivity()).setAdvertisementEnable(false);
                FragmentLauncher.changeToBack(getContext(), R.id.content_container, null, FragmentGCMRecord.class.getName());
            }
        }

    };

    private View.OnClickListener backClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            getActivity().onBackPressed();
        }
    };
}
