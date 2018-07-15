package com.herbhousesgobuyother.contrube.view.normal;

import android.app.AlertDialog;
import android.graphics.Color;
import android.graphics.drawable.ColorDrawable;
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
import com.androidlibrary.core.ActivityLauncher;
import com.androidlibrary.module.backend.data.ApiV1RecommendShowGetData;
import com.androidlibrary.module.backend.data.ApiV1ValidateIdentityCardPostData;
import com.androidlibrary.module.consts.CountryConst;
import com.androidlibrary.ui.basicinformation.data.ApiV1UserDetailGetData;
import com.androidlibrary.ui.basicinformation.data.ApiV1UserDetailPostData;
import com.herbhousesgobuyother.R;
import com.herbhousesgobuyother.contrube.component.dialog.VipDialog;
import com.herbhousesgobuyother.contrube.controllor.basic.NormalBasicController;

/**
 * Created by user on 2016/11/12.
 */

public class FragmentNormalBasicInformation extends Fragment {
    private TextView account;
    private TextView birth;
    private TextView country;
    private TextView recommend;
    private TextView email;
    private EditText emailEdit;
    private Button recommendButton;
    private TextView change;
    private TextView submit;
    private View back;
    private Button vipButton;
    private TextView vip;

    private NormalBasicController controller;
    private FinishModifyDialog finishModifyDialog;
    private VipDialog vipDialog;
    private AlertDialog alertVipDialog;

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        View layout = inflater.inflate(R.layout.fragment_basic_information, container, false);
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
        back = getView().findViewById(R.id.toolbar_back_touch);
        account = (TextView) getView().findViewById(R.id.fragment_basic_information_account_text);
        birth = (TextView) getView().findViewById(R.id.fragment_basic_information_birth_text);
        country = (TextView) getView().findViewById(R.id.fragment_basic_information_country_text);
        recommend = (TextView) getView().findViewById(R.id.fragment_basic_information_recommend_text);
        email = (TextView) getView().findViewById(R.id.fragment_basic_information_mail_text);
        recommendButton = (Button) getView().findViewById(R.id.fragment_basic_information_recommend_scanRecommend);
        emailEdit = (EditText) getView().findViewById(R.id.fragment_basic_information_mail_edit);
        change = (TextView) getView().findViewById(R.id.fragment_basic_information_change_text);
        submit = (TextView) getView().findViewById(R.id.fragment_basic_information_change_submit);
        vipButton = (Button) getView().findViewById(R.id.fragment_basic_information_vip_button);
        vip = (TextView) getView().findViewById(R.id.fragment_basic_information_vip_text);

        account.setSingleLine(true);
        birth.setSingleLine(true);
        country.setSingleLine(true);
        email.setSingleLine(true);
        emailEdit.setSingleLine(true);
    }

    private void init() {
        finishModifyDialog = new FinishModifyDialog(getContext());
        controller = new NormalBasicController(getContext());
        vipDialog();
        controller.setmCallBackEvent(callBackEvent);
        change.setOnClickListener(changeClick);
        submit.setOnClickListener(submitClick);
        recommendButton.setOnClickListener(recommendClick);
        vipButton.setOnClickListener(vipClick);

        setLayoutLogic();
        controller.syncRequest();
        controller.syncRecommendRequest();
        back.setOnClickListener(backClick);
    }

    private void vipDialog() {
        vipDialog = new VipDialog(getContext());
        alertVipDialog = vipDialog.create();
        alertVipDialog.getWindow().setBackgroundDrawable(new ColorDrawable(Color.TRANSPARENT));
        vipDialog.setCallBackEvent(dialogClick);
    }

    private VipDialog.VipDialogClick dialogClick = new VipDialog.VipDialogClick() {
        @Override
        public void onSubmitClick() {
            controller.vipRequest(vipDialog.getInput().getText().toString().trim());
            alertVipDialog.dismiss();
        }

        @Override
        public void onCancelClick() {
            alertVipDialog.dismiss();
        }
    };

    private View.OnClickListener vipClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            alertVipDialog.show();
        }
    };

    private View.OnClickListener recommendClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            ActivityLauncher.go(getContext(), ActivityNormalSetRecommend.class, null);
        }
    };

    private void setLayoutLogic() {
        if (recommend.getText().toString().trim().equals("")) {
            recommendButton.setVisibility(View.VISIBLE);
        } else {
            recommendButton.setVisibility(View.INVISIBLE);
        }
        if (vip.getText().toString().trim().equals("")) {
            vipButton.setVisibility(View.VISIBLE);
        } else {
            vipButton.setVisibility(View.INVISIBLE);
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

    private NormalBasicController.CallBackEvent callBackEvent = new NormalBasicController.CallBackEvent() {
        @Override
        public void onError() {

        }

        @Override
        public void onSuccess(ApiV1UserDetailGetData information) {
            if (information.result == 0) {
                account.setText(information.userAccount);
                birth.setText(information.userBirthday);
                if (information.countryCheck.equals("")) {
                    country.setText("");
                } else {
                    country.setText(getString(CountryConst.get(information.userCountry)));
                }
                email.setText(information.userEmail);
                if (information.userState.equals("0")) {
                    vipButton.setVisibility(View.VISIBLE);
                } else {
                    vipButton.setVisibility(View.INVISIBLE);
                    vip.setText(R.string.basic_information_layout_vip_ok);
                }
            }
        }

        @Override
        public void onSuccess(ApiV1RecommendShowGetData information) {
            if (information.result == 0) {
                recommend.setText(information.general);
                if (information.general.equals("")) {
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

        @Override
        public void onSuccess(ApiV1ValidateIdentityCardPostData information) {
            if (information.result == 0) {
                String content = getString(R.string.vip_success);
                Toast.makeText(getActivity(), content, Toast.LENGTH_LONG).show();
                controller.syncRequest();
            } else if (information.result == 1) {
                String content = getString(R.string.vip_fail);
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
