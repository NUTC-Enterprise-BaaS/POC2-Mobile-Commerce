package com.herbhousesgobuyother.contrube.view.special;

import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;
import android.widget.Toast;

import com.androidlibrary.component.dialog.FinishModifyDialog;
import com.androidlibrary.module.backend.data.ApiV1RecommendShowGetData;
import com.androidlibrary.module.backend.data.ApiV1SpecialUserDetailGetData;
import com.androidlibrary.module.consts.CountryConst;
import com.androidlibrary.ui.basicinformation.data.ApiV1UserDetailPostData;
import com.herbhousesgobuyother.R;
import com.herbhousesgobuyother.contrube.controllor.basic.SpecialBasicController;
import com.herbhousesgobuyother.contrube.core.ActivityLauncher;

/**
 * Created by user on 2016/11/12.
 */

public class FragmentSpecialBasicInformation extends Fragment {
    private TextView account;
    private TextView birth;
    private TextView country;
    private TextView storeName;
    private TextView storeAddress;
    private TextView storeUrl;
    private TextView storeContant;
    private TextView recommend;
    private TextView email;
    private EditText emailEdit;
    private Button recommendButton;
    private TextView change;
    private TextView submit;

    private SpecialBasicController controller;
    private FinishModifyDialog finishModifyDialog;
    private View back;

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        View layout = inflater.inflate(R.layout.fragment_special_basic_information, container, false);
        return layout;
    }

    @Override
    public void onViewCreated(View view, Bundle savedInstanceState) {
        super.onViewCreated(view, savedInstanceState);

    }

    @Override
    public void onStart() {
        super.onStart();
        findView();
        init();
    }

    private void findView() {
        account = (TextView) getView().findViewById(R.id.fragment_basic_information_account_text);
        birth = (TextView) getView().findViewById(R.id.fragment_basic_information_birth_text);
        country = (TextView) getView().findViewById(R.id.fragment_basic_information_country_text);
        storeName = (TextView) getView().findViewById(R.id.fragment_basic_information_store_name_text);
        storeAddress = (TextView) getView().findViewById(R.id.fragment_basic_information_store_address_text);
        storeUrl = (TextView) getView().findViewById(R.id.fragment_basic_information_store_url_text);
        storeContant = (TextView) getView().findViewById(R.id.fragment_basic_information_store_contant_text);
        recommend = (TextView) getView().findViewById(R.id.fragment_basic_information_recommend_text);
        email = (TextView) getView().findViewById(R.id.fragment_basic_information_mail_text);
        recommendButton = (Button) getView().findViewById(R.id.fragment_basic_information_recommend_scanRecommend);
        emailEdit = (EditText) getView().findViewById(R.id.fragment_basic_information_mail_edit);
        change = (TextView) getView().findViewById(R.id.fragment_basic_information_change_text);
        submit = (TextView) getView().findViewById(R.id.fragment_basic_information_change_submit);
        back = getView().findViewById(R.id.toolbar_back_touch);

        account.setSingleLine(true);
        birth.setSingleLine(true);
        country.setSingleLine(true);
        email.setSingleLine(true);
        emailEdit.setSingleLine(true);
        storeName.setSingleLine(true);
        storeAddress.setSingleLine(true);
        storeUrl.setSingleLine(true);
        storeContant.setSingleLine(true);
    }

    private void init() {
        finishModifyDialog = new FinishModifyDialog(getContext());
        controller = new SpecialBasicController(getContext());
        controller.setmCallBackEvent(callBackEvent);
        change.setOnClickListener(changeClick);
        submit.setOnClickListener(submitClick);
        recommendButton.setOnClickListener(recommendClick);

        setLayoutLogic();
        controller.syncRequest();
        controller.syncRecommendRequest();
        back.setOnClickListener(backClick);
    }

    private View.OnClickListener recommendClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            ActivityLauncher.go(getContext(), ActivitySpecialSetRecommend.class, null);
        }
    };

    private void setLayoutLogic() {
        if (recommend.getText().toString().trim().equals("")) {
            recommendButton.setVisibility(View.VISIBLE);
        } else {
            recommendButton.setVisibility(View.INVISIBLE);
        }
    }

    private View.OnClickListener submitClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            if (checkSum()) {
                String email = emailEdit.getText().toString().trim();
                controller.modifyRequest(email);
            }
        }
    };

    private View.OnClickListener changeClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            change.setVisibility(View.INVISIBLE);
            submit.setVisibility(View.VISIBLE);
            email.setVisibility(View.INVISIBLE);
            emailEdit.setVisibility(View.VISIBLE);
            emailEdit.setText(email.getText().toString().trim());
        }
    };

    private SpecialBasicController.CallBackEvent callBackEvent = new SpecialBasicController.CallBackEvent() {
        @Override
        public void onError() {

        }

        @Override
        public void onSuccess(ApiV1SpecialUserDetailGetData information) {
            if (information.result == 0) {
                account.setText(information.userAccount);
                birth.setText(information.userBirthday);
                email.setText(information.email);
                storeName.setText(information.storeName);
                storeAddress.setText(information.storeAddress);
                storeUrl.setText(information.storeUrl);
                storeContant.setText(information.contant);
                if (information.countryCheck.equals("")) {
                    country.setText("");
                } else {
                    country.setText(getString(CountryConst.get(information.userCountry)));
                }
            }
        }

        @Override
        public void onSuccess(ApiV1RecommendShowGetData information) {
            if (information.result == 0) {
                recommend.setText(information.general);
                if (information.special.equals("")) {
                    recommendButton.setVisibility(View.VISIBLE);
                } else {
                    recommendButton.setVisibility(View.INVISIBLE);
                }
            }
        }

        @Override
        public void onSuccess(ApiV1UserDetailPostData information) {
            if (information.result == 0) {
                finishModifyDialog.show();
                change.setVisibility(View.VISIBLE);
                submit.setVisibility(View.INVISIBLE);
                email.setVisibility(View.VISIBLE);
                emailEdit.setVisibility(View.INVISIBLE);
                controller.syncRequest();
            } else if (information.messageGroup.get(0).toString().trim().equals("This email is already registered")) {
                String content = getString(R.string.email_exist);
                Toast.makeText(getActivity(), content, Toast.LENGTH_LONG).show();
            }
        }
    };

    private boolean checkSum() {
        String email = emailEdit.getText().toString().trim();
        if (email.equals("")) {
            String content = getString(R.string.register_dialog_error_register_empty);
            Toast.makeText(getContext(), content, Toast.LENGTH_LONG).show();
            return false;
        } else if (!android.util.Patterns.EMAIL_ADDRESS.matcher(email).matches()) {
            String content = getString(R.string.authenticate_dialog_error_mail_style);
            Toast.makeText(getContext(), content, Toast.LENGTH_LONG).show();
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
