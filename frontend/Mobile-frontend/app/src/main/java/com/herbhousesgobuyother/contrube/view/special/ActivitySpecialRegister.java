package com.herbhousesgobuyother.contrube.view.special;

import android.app.AlertDialog;
import android.content.Context;
import android.content.Intent;
import android.content.res.Configuration;
import android.content.res.Resources;
import android.graphics.Color;
import android.graphics.drawable.ColorDrawable;
import android.net.Uri;
import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.CheckBox;
import android.widget.EditText;
import android.widget.RadioButton;
import android.widget.RelativeLayout;
import android.widget.Spinner;
import android.widget.TextView;
import android.widget.Toast;

import com.androidlibrary.module.backend.data.ApiV1SpecialRegisterPostData;
import com.androidlibrary.module.backend.params.AccountInjection;
import com.androidlibrary.ui.basicinformation.data.ApiV1UserDetailGetData;
import com.herbhousesgobuyother.R;
import com.herbhousesgobuyother.contrube.component.dialog.UploadContentDialog;
import com.herbhousesgobuyother.contrube.component.dialog.UploadLogoDialog;
import com.herbhousesgobuyother.contrube.controllor.register.SpecialRegisterController;

import java.util.ArrayList;

/**
 * Created by 依杰 on 2016/11/11.
 */

public class ActivitySpecialRegister extends AppCompatActivity {
    private final int SCAN = 0;
    private final int SELECT_LOGO = 1;
    private final int SELECT_PIC_ALL = 2;
    private final int SELECT_PIC_SINGLE_ONE = 3;
    private final int SELECT_PIC_SINGLE_TWO = 4;
    private final int SELECT_PIC_SINGLE_THREE = 5;

    private Context mContext;
    private CheckBox entityCheckBox;
    private CheckBox virtualCheckBox;
    private RadioButton maleRadioButton;
    private RadioButton femaleRadioButton;
    private Spinner countrySpinner;
    private Spinner jobTypeSpinner;
    private View entityView;
    private View virtualView;
    private View maleView;
    private View femaleView;
    private EditText storeNameEditText;
    private EditText storeAddressEditText;
    private EditText contactPersonEditText;
    private EditText memberPhoneNumberEditText;
    private EditText memberEmailEditText;
    private EditText memberBirthdayEditText;
    private EditText authenticateEdit;
    private Button resetButton;
    private Button submitButton;
    private TextView authenticateButton;
    private EditText scanEdit;
    private Button scanButton;

    private String recommendId;
    private String verifyCode;
    private SpecialRegisterController controller;
    private StringBuffer stringBuffer;
    private AccountInjection accountInjection;
    private View back;
    private RelativeLayout container;

    private View privacy;
    private TextView disAgree;
    private CheckBox checkBox;
    private TextView agree;
    private TextView mainLogoText;
    private TextView subLogoText;
    private ArrayList<String> logoUriPath;
    private ArrayList<String> contentUriPath;

    private UploadLogoDialog uploadLogoDialog;
    private AlertDialog alertUploadLogoDialog;
    private ArrayList<String> logoDialogCheck;
    private UploadContentDialog uploadContentDialog;
    private AlertDialog alertUploadContentDialog;
    private ArrayList<String> contentDialogCheck;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_special_register);
        findView();
        initSystemFont();
        init();
    }

    private void init() {
        mContext = this;
        verifyCode = "";
        recommendId = "";
        logoUriPath = new ArrayList<>();
        contentUriPath = new ArrayList<>();
        logoDialogCheck = new ArrayList<>();
        contentDialogCheck = new ArrayList<>();
        accountInjection = new AccountInjection(mContext);
        stringBuffer = new StringBuffer();
        uploadLogoDialog();
        uploadContentDialog();
        generateRandom(4);
        controller = new SpecialRegisterController(mContext);

        setLayoutLogic();
        scanButton.setOnClickListener(scanClick);
        submitButton.setOnClickListener(submitClick);
        authenticateButton.setOnClickListener(authenticateClick);
        controller.setmCallBackEvent(callBackEvent);
        controller.syncRequest();
        back.setOnClickListener(backClick);
        agree.setOnClickListener(agreeClick);
        disAgree.setOnClickListener(disAgreeClick);
        mainLogoText.setOnClickListener(logoSelectEvent);
        subLogoText.setOnClickListener(subLogoSelectEvent);

    }

    private void uploadContentDialog() {
        uploadContentDialog = new UploadContentDialog(mContext);
        uploadContentDialog.setDialogTitle(R.string.special_upload_content_dialog_title);
        uploadContentDialog.setDialogPrompt(R.string.special_upload_content_dialog_prompt);
        uploadContentDialog.setDialogTitleColor(R.color.ColorSpecialTheme);
        uploadContentDialog.setButtonColor(R.drawable.special_circle_button);
        alertUploadContentDialog = uploadContentDialog.create();
        alertUploadContentDialog.getWindow().setBackgroundDrawable(new ColorDrawable(Color.TRANSPARENT));
        uploadContentDialog.setCallBackEvent(contentDialogClick);

    }

    private void uploadLogoDialog() {
        uploadLogoDialog = new UploadLogoDialog(mContext);
        uploadLogoDialog.setDialogTitle(R.string.special_upload_logo_dialog_title);
        uploadLogoDialog.setDialogPrompt(R.string.special_upload_logo_dialog_prompt);
        uploadLogoDialog.setDialogTitleColor(R.color.ColorSpecialTheme);
        uploadLogoDialog.setButtonColor(R.drawable.special_circle_button);
        alertUploadLogoDialog = uploadLogoDialog.create();
        alertUploadLogoDialog.getWindow().setBackgroundDrawable(new ColorDrawable(Color.TRANSPARENT));
        uploadLogoDialog.setCallBackEvent(logoDialogClick);

    }

    private UploadContentDialog.UploadContentDialogClick contentDialogClick = new UploadContentDialog.UploadContentDialogClick() {
        @Override
        public void onSubmitClick() {
            if (contentDialogCheck.size() > 0) {
                contentUriPath = contentDialogCheck;
                alertUploadContentDialog.dismiss();
                String successPic = mContext.getString(R.string.select_sub_logo_success);
                subLogoText.setText(successPic);
            }

        }

        @Override
        public void onCancelClick() {
            alertUploadContentDialog.dismiss();
        }

        @Override
        public void onView1Click() {
            if (contentDialogCheck.size() > 0) {
                Intent intent = new Intent();
                intent.setClass(ActivitySpecialRegister.this, ActivitySpecialSelectPicSingle.class);
                startActivityForResult(intent, SELECT_PIC_SINGLE_ONE);
            } else {
                Intent intent = new Intent();
                intent.setClass(ActivitySpecialRegister.this, ActivitySpecialSelectPicAll.class);
                startActivityForResult(intent, SELECT_PIC_ALL);
            }
        }

        @Override
        public void onView2Click() {
            if (contentDialogCheck.size() > 0) {
                Intent intent = new Intent();
                intent.setClass(ActivitySpecialRegister.this, ActivitySpecialSelectPicSingle.class);
                startActivityForResult(intent, SELECT_PIC_SINGLE_TWO);
            } else {
                Intent intent = new Intent();
                intent.setClass(ActivitySpecialRegister.this, ActivitySpecialSelectPicAll.class);
                startActivityForResult(intent, SELECT_PIC_ALL);
            }
        }

        @Override
        public void onView3Click() {
            if (contentDialogCheck.size() > 0) {
                Intent intent = new Intent();
                intent.setClass(ActivitySpecialRegister.this, ActivitySpecialSelectPicSingle.class);
                startActivityForResult(intent, SELECT_PIC_SINGLE_THREE);
            } else {
                Intent intent = new Intent();
                intent.setClass(ActivitySpecialRegister.this, ActivitySpecialSelectPicAll.class);
                startActivityForResult(intent, SELECT_PIC_ALL);
            }

        }
    };

    private UploadLogoDialog.UploadLogoDialogClick logoDialogClick = new UploadLogoDialog.UploadLogoDialogClick() {
        @Override
        public void onSubmitClick() {
            if (logoDialogCheck.size() > 0) {
                alertUploadLogoDialog.dismiss();
                logoUriPath = logoDialogCheck;
                String successLogo = mContext.getString(R.string.select_logo_success);
                mainLogoText.setText(successLogo);
            }

        }

        @Override
        public void onCancelClick() {
            alertUploadLogoDialog.dismiss();
        }

        @Override
        public void onLogoClick() {
            Intent intent = new Intent();
            intent.setClass(ActivitySpecialRegister.this, ActivitySpecialSelectLogo.class);
            startActivityForResult(intent, SELECT_LOGO);
        }
    };


    private View.OnClickListener subLogoSelectEvent = new View.OnClickListener() {
        @Override
        public void onClick(View v) {
            if (logoUriPath.size() <= 0) {
                String selectFail = ActivitySpecialRegister.this.getString(R.string.request_select_logo_fail);
                Toast.makeText(ActivitySpecialRegister.this, selectFail, Toast.LENGTH_SHORT).show();
                return;
            }
            alertUploadContentDialog.show();
        }
    };

    private View.OnClickListener logoSelectEvent = new View.OnClickListener() {
        @Override
        public void onClick(View v) {
            alertUploadLogoDialog.show();
        }
    };

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
                int storeType = 0;
                storeType += entityCheckBox.isChecked() ? 1 : 0;
                storeType += virtualCheckBox.isChecked() ? 1 : 0;
                int personGender = (maleRadioButton.isChecked()) ? 0 : 1;
                String name = storeNameEditText.getText().toString().trim();
                String address = storeAddressEditText.getText().toString().trim();
                String contact = contactPersonEditText.getText().toString().trim();
                String job = String.valueOf(jobTypeSpinner.getSelectedItemPosition()).trim();
                String email = accountInjection.loadAccount();
                String password = accountInjection.loadPassword();
                controller.setRegisterData(email, password, recommendId);
                controller.setLogoUriPath(logoUriPath);
                controller.setContentUriPath(contentUriPath);
                controller.registerRequest(name, storeType, address, contact, personGender, job);

            } else {
                String content = getString(R.string.privacy_policy_check_button);
                Toast.makeText(mContext, content, Toast.LENGTH_LONG).show();
            }
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

    private SpecialRegisterController.CallBackEvent callBackEvent = new SpecialRegisterController.CallBackEvent() {
        @Override
        public void onError() {

        }

        @Override
        public void onSuccess() {

        }

        @Override
        public void onSuccess(ApiV1UserDetailGetData information) {
            if (information.result == 0) {
                countrySpinner.setSelection(information.userCountry);
                memberPhoneNumberEditText.setText(information.userPhone);
                memberEmailEditText.setText(information.userEmail);
                memberBirthdayEditText.setText(information.userBirthday);
            }
        }

        @Override
        public void onSuccess(ApiV1SpecialRegisterPostData information) {
            if (information.result == 0) {
                Toast.makeText(mContext, R.string.preferential_register_success, Toast.LENGTH_SHORT).show();
                controller.loginRequest();
            }
        }
    };

    private void setLayoutLogic() {
        setCheckBoxState(entityCheckBox, entityView);
        setCheckBoxState(virtualCheckBox, virtualView);
        setRadioButtonState(maleRadioButton, maleView, femaleRadioButton, femaleView);
        setRadioButtonState(femaleRadioButton, femaleView, maleRadioButton, maleView);
        setSpinnerAdapet();
        reset();
    }

    private View.OnClickListener scanClick = new View.OnClickListener() {
        @Override
        public void onClick(View v) {
            Intent intent = new Intent();
            intent.setClass(ActivitySpecialRegister.this, ActivitySpecialRegisterRecommend.class);
            startActivityForResult(intent, SCAN);
        }
    };

    private void reset() {
        resetButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                storeNameEditText.setText("");
                storeAddressEditText.setText("");
                contactPersonEditText.setText("");
                authenticateEdit.setText("");
            }
        });
    }

    private void setSpinnerAdapet() {
        ArrayAdapter countryAdapter = new ArrayAdapter(mContext, R.layout.register_store_spinner_item, getResources().getStringArray(R.array.preferential_register_country_list));
        countryAdapter.setDropDownViewResource(R.layout.register_store_spinner_item);
        countrySpinner.setAdapter(countryAdapter);

        ArrayAdapter job_TypeAdapter = new ArrayAdapter(mContext, R.layout.register_store_spinner_item, getResources().getStringArray(R.array.preferential_register_country_job_list));
        countryAdapter.setDropDownViewResource(R.layout.register_store_spinner_item);
        jobTypeSpinner.setAdapter(job_TypeAdapter);
    }

    private void setRadioButtonState(final RadioButton event, final View view, final RadioButton anotherEvent, final View anotherView) {
        if (event.isChecked()) {
            view.setBackgroundResource(R.drawable.activity_register_checkbox_solid_background);
            anotherView.setBackgroundResource(R.drawable.activity_register_checkbox_background);
            anotherEvent.setChecked(false);
        }
        if (anotherEvent.isChecked()) {
            anotherView.setBackgroundResource(R.drawable.activity_register_checkbox_solid_background);
            view.setBackgroundResource(R.drawable.activity_register_checkbox_background);
            event.setChecked(false);

        }

        event.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if (event.isChecked()) {
                    view.setBackgroundResource(R.drawable.activity_register_checkbox_solid_background);
                    anotherView.setBackgroundResource(R.drawable.activity_register_checkbox_background);
                    anotherEvent.setChecked(false);
                }
                if (anotherEvent.isChecked()) {
                    anotherView.setBackgroundResource(R.drawable.activity_register_checkbox_solid_background);
                    view.setBackgroundResource(R.drawable.activity_register_checkbox_background);
                    event.setChecked(false);

                }
            }
        });
    }

    private void setCheckBoxState(final CheckBox event, final View view) {
        event.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if (event.isChecked())
                    view.setBackgroundResource(R.drawable.activity_register_checkbox_solid_background);
                else
                    view.setBackgroundResource(R.drawable.activity_register_checkbox_background);
            }
        });
    }

    private void findView() {
        resetButton = (Button) findViewById(R.id.activity_register_reset_button);
        submitButton = (Button) findViewById(R.id.activity_register_send_button);
        storeNameEditText = (EditText) findViewById(R.id.activity_register_store_name_editText);
        storeAddressEditText = (EditText) findViewById(R.id.activity_register_store_address_editText);
        contactPersonEditText = (EditText) findViewById(R.id.activity_register_contact_person_editText);
        memberPhoneNumberEditText = (EditText) findViewById(R.id.activity_register_member_account_editText);
        memberEmailEditText = (EditText) findViewById(R.id.activity_register_member_email_editText);
        memberBirthdayEditText = (EditText) findViewById(R.id.activity_register_member_birthday_editText);
        authenticateEdit = (EditText) findViewById(R.id.activity_register_id_editText);
        authenticateButton = (TextView) findViewById(R.id.activity_register_id_img);
        entityCheckBox = (CheckBox) findViewById(R.id.activity_register_store_entity_checkbox);
        virtualCheckBox = (CheckBox) findViewById(R.id.activity_register_store_virtual_checkbox);
        maleRadioButton = (RadioButton) findViewById(R.id.activity_register_sex_male_radiobutton);
        femaleRadioButton = (RadioButton) findViewById(R.id.activity_register_sex_female_radiobutton);
        countrySpinner = (Spinner) findViewById(R.id.activity_register_country_spinner);
        jobTypeSpinner = (Spinner) findViewById(R.id.activity_register_job_type_spinner);
        entityView = findViewById(R.id.activity_register_store_entity_view);
        virtualView = findViewById(R.id.activity_register_store_virtual_view_checkbox);
        maleView = findViewById(R.id.activity_register_sex_male_radiobutton_view);
        femaleView = findViewById(R.id.activity_register_sex_female_radiobutton_view);
        scanButton = (Button) findViewById(R.id.activity_register_recommend_button);
        scanEdit = (EditText) findViewById(R.id.activity_register_recommend_qr_text);
        back = findViewById(R.id.toolbar_back_touch);
        container = (RelativeLayout) findViewById(R.id.container);

        privacy = LayoutInflater.from(ActivitySpecialRegister.this).inflate(R.layout.dialog_special_privacy, null);
        disAgree = (TextView) privacy.findViewById(R.id.disagree);
        agree = (TextView) privacy.findViewById(R.id.agree);
        checkBox = (CheckBox) privacy.findViewById(R.id.check);

        storeNameEditText.setSingleLine(true);
        storeAddressEditText.setSingleLine(true);
        contactPersonEditText.setSingleLine(true);
        memberPhoneNumberEditText.setSingleLine(true);
        memberEmailEditText.setSingleLine(true);
        memberBirthdayEditText.setSingleLine(true);
        authenticateEdit.setSingleLine(true);
        scanEdit.setSingleLine(true);


        mainLogoText = (TextView) findViewById(R.id.activity_register_main_logo_editText);
        subLogoText = (TextView) findViewById(R.id.activity_register_sub_logo_editText);


    }

    @Override
    protected void onActivityResult(int requestCode, int resultCode, Intent data) {
        if (data != null) {
            switch (requestCode) {
                case SCAN:
                    scanEdit.setText(data.getExtras().getString("phone"));
                    recommendId = data.getExtras().getString("id");
                    break;

                case SELECT_LOGO:
                    logoDialogCheck = data.getExtras().getStringArrayList("uri");
                    uploadLogoDialog.setLogoImage(Uri.parse(logoDialogCheck.get(0).toString()));

                    break;

                case SELECT_PIC_ALL:
                    contentDialogCheck = data.getExtras().getStringArrayList("uri");
                    uploadContentDialog.setContentView1Image(Uri.parse(contentDialogCheck.get(0).toString()));
                    uploadContentDialog.setContentView2Image(Uri.parse(contentDialogCheck.get(1).toString()));
                    uploadContentDialog.setContentView3Image(Uri.parse(contentDialogCheck.get(2).toString()));
                    break;

                case SELECT_PIC_SINGLE_ONE:
                    String view1 = data.getExtras().getStringArrayList("uri").get(0).toString();
                    contentDialogCheck.set(0, view1);
                    uploadContentDialog.setContentView1Image(Uri.parse(contentDialogCheck.get(0).toString()));
                    break;

                case SELECT_PIC_SINGLE_TWO:
                    String view2 = data.getExtras().getStringArrayList("uri").get(0).toString();
                    contentDialogCheck.set(1, view2);
                    uploadContentDialog.setContentView2Image(Uri.parse(contentDialogCheck.get(1).toString()));
                    break;

                case SELECT_PIC_SINGLE_THREE:
                    String view3 = data.getExtras().getStringArrayList("uri").get(0).toString();
                    contentDialogCheck.set(2, view3);
                    uploadContentDialog.setContentView3Image(Uri.parse(contentDialogCheck.get(2).toString()));
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

    private boolean checkSum() {
        String name = storeNameEditText.getText().toString().trim();
        String address = storeAddressEditText.getText().toString().trim();
        String contact = contactPersonEditText.getText().toString().trim();

        if (name.equals("") || address.equals("") || contact.equals("")) {
            String content = getString(R.string.register_dialog_error_register_empty);
            Toast.makeText(mContext, content, Toast.LENGTH_LONG).show();
            return false;
        } else if (((!entityCheckBox.isChecked()) && (!virtualCheckBox.isChecked()))) {
            String content = getString(R.string.register_dialog_error_store_empty);
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

    private View.OnClickListener backClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            finish();
        }
    };
}
