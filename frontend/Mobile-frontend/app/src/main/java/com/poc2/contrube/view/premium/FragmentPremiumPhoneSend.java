package com.poc2.contrube.view.premium;

import android.app.AlertDialog;
import android.graphics.Color;
import android.graphics.drawable.ColorDrawable;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v4.app.Fragment;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import com.androidlibrary.module.aes.AesCrypt;
import com.poc2.R;
import com.poc2.contrube.component.dialog.FragmentPremiumPhoneSendDialog;
import com.poc2.contrube.controllor.phone.FragmentPremiumPhoneSendControllor;

import java.util.ArrayList;

/**
 * Created by 依杰 on 2016/11/30.
 */

public class FragmentPremiumPhoneSend extends Fragment {
    private TextView oneButton;
    private TextView twoButton;
    private TextView threeButton;
    private TextView fourButton;
    private TextView fiveButton;
    private TextView sixButton;
    private TextView sevenButton;
    private TextView eightButton;
    private TextView nineButton;
    private TextView zeroButton;
    private TextView inputText;
    private TextView sumButton;
    private Button cancelButton;
    private Button submitButton;
    private ImageView clear;

    private FragmentPremiumPhoneSendControllor controllor;
    private String[] Key = {"0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "+"};
    private ArrayList<TextView> buttonList;
    private StringBuffer stringBuffer;
    private String allNumber = "";
    private AesCrypt crypt;
    private String encryptionResult = "";//加密過後的編碼
    private FragmentPremiumPhoneSendDialog dialog;
    private AlertDialog alertDialog;
    private View back;

    @Nullable
    @Override
    public View onCreateView(LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {

        View layout = inflater.inflate(R.layout.fragment_premium_phone_send, container, false);
        return layout;
    }

    @Override
    public void onViewCreated(View view, @Nullable Bundle savedInstanceState) {
        super.onViewCreated(view, savedInstanceState);
        findView();
        init();
    }

    private void init() {
        stringBuffer = new StringBuffer();
        buttonList = new ArrayList<>();
        controllor = new FragmentPremiumPhoneSendControllor(getContext());
        dialog = new FragmentPremiumPhoneSendDialog(getContext());

        addList();
        setNumberTag();
        setNumberClick();

        back.setOnClickListener(backClick);
        cancelButton.setOnClickListener(backClick);
        clear.setOnClickListener(delete);
        submitButton.setOnClickListener(pointSend);
        controllor.setCallBackEvent(callback);
        dialog.setDialogClickEvent(dialogClick);
        controllor.setSendPointSuccess(sendPointSuccess);

        alertDialog = dialog.create();
        alertDialog.getWindow().setBackgroundDrawable(new ColorDrawable(Color.TRANSPARENT));
        back.setOnClickListener(backClick);
    }

    private FragmentPremiumPhoneSendControllor.SendPointSuccess sendPointSuccess = new FragmentPremiumPhoneSendControllor.SendPointSuccess() {
        @Override
        public void onSuccess() {
            alertDialog.dismiss();
        }

        @Override
        public void onError() {

        }
    };

    private FragmentPremiumPhoneSendDialog.DialogClickEvent dialogClick = new FragmentPremiumPhoneSendDialog.DialogClickEvent() {
        @Override
        public void cancel() {
            alertDialog.dismiss();
        }

        @Override
        public void submit() {
            if (dialog.getPointEdit().getText().length() > 0) {
                controllor.sendPoint(dialog.getPointEdit().getText().toString().trim(), allNumber);
            } else {
                Toast.makeText(getActivity(), R.string.point_dialog_empty, Toast.LENGTH_LONG).show();
            }
        }
    };

    private FragmentPremiumPhoneSendControllor.CallBackEvent callback = new FragmentPremiumPhoneSendControllor.CallBackEvent() {
        @Override
        public void onSuccess() {
            dialog.getTitle().setText(allNumber);
            alertDialog.show();
        }

        @Override
        public void onError() {

        }
    };

    private View.OnClickListener pointSend = new View.OnClickListener() {
        @Override
        public void onClick(View v) {
            if (allNumber.length() > 0) {
                try {
                    crypt = new AesCrypt();
                    encryptionResult = crypt.encrypt(allNumber);
                } catch (Exception e) {
                    e.printStackTrace();
                }
                controllor.checkApi(encryptionResult);

            } else {
                Toast.makeText(getActivity(), R.string.point_dialog_empty, Toast.LENGTH_LONG).show();
            }
        }
    };


    private View.OnClickListener delete = new View.OnClickListener() {
        @Override
        public void onClick(View v) {
            if (stringBuffer.length() > 0) {
                stringBuffer.delete(stringBuffer.length() - 1, stringBuffer.length());
                allNumber = stringBuffer.toString();
            }
            inputText.setText(allNumber);
        }
    };

    private void addList() {
        buttonList.add(zeroButton);
        buttonList.add(oneButton);
        buttonList.add(twoButton);
        buttonList.add(threeButton);
        buttonList.add(fourButton);
        buttonList.add(fiveButton);
        buttonList.add(sixButton);
        buttonList.add(sevenButton);
        buttonList.add(eightButton);
        buttonList.add(nineButton);
        buttonList.add(sumButton);
    }

    private void setNumberTag() {
        for (int count = 0; count <= 10; count++) {
            buttonList.get(count).setTag(Key[count]);
        }
    }

    private void setNumberClick() {
        for (TextView text : buttonList) {
            text.setOnClickListener(event);
        }
    }

    private View.OnClickListener event = new View.OnClickListener() {
        @Override
        public void onClick(View v) {
            String number = String.valueOf(v.getTag());

            if (stringBuffer.length() <= 15) {
                stringBuffer.append(number);
                allNumber = stringBuffer.toString();
            }
            inputText.setText(allNumber);
        }
    };

    private void findView() {
        inputText = (TextView) getView().findViewById(R.id.fragment_special_phone_input_edit);
        oneButton = (TextView) getView().findViewById(R.id.fragment_special_phone_one_edit);
        twoButton = (TextView) getView().findViewById(R.id.fragment_special_phone_two_edit);
        threeButton = (TextView) getView().findViewById(R.id.fragment_special_phone_three_edit);
        fourButton = (TextView) getView().findViewById(R.id.fragment_special_phone_four_edit);
        fiveButton = (TextView) getView().findViewById(R.id.fragment_special_phone_five_edit);
        sixButton = (TextView) getView().findViewById(R.id.fragment_special_phone_six_edit);
        sevenButton = (TextView) getView().findViewById(R.id.fragment_special_phone_seven_edit);
        eightButton = (TextView) getView().findViewById(R.id.fragment_special_phone_eight_edit);
        nineButton = (TextView) getView().findViewById(R.id.fragment_special_phone_nine_edit);
        zeroButton = (TextView) getView().findViewById(R.id.fragment_special_phone_zero_edit);
        cancelButton = (Button) getView().findViewById(R.id.fragment_special_phone_cancel);
        submitButton = (Button) getView().findViewById(R.id.fragment_special_phone_submit);
        clear = (ImageView) getView().findViewById(R.id.fragment_special_phone_back_imag);
        sumButton = (TextView) getView().findViewById(R.id.fragment_special_phone_add_edit);
        back = getView().findViewById(R.id.toolbar_back_touch);
    }

    private View.OnClickListener backClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            getActivity().onBackPressed();
        }
    };

}
