package com.herbhousesgobuyother.component.dialog;

import android.app.AlertDialog;
import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;

import com.herbhousesgobuyother.R;


/**
 * Created by 依杰 on 2016/7/6.
 */
public class ScratChcardShareDialog extends AlertDialog.Builder {
    private View mView;
    private Button cancel;
    private Button submit;
    private EditText phone;
    private EditText email;
    private ScratChcardShareDialogEvent mScratChcardShareDialogEvent;

    public interface ScratChcardShareDialogEvent {
        void onClickCancel();

        void onClickSubmit();
    }

    public ScratChcardShareDialog(Context context) {
        super(context);
        mView = LayoutInflater.from(context).inflate(R.layout.dialog_scratch_card_share, null);
        setView(mView);
        setCancelable(false);
        findView();
        init();
    }

    private void findView() {
        phone = (EditText) mView.findViewById(R.id.dialog_scratch_card_share_phone);
        cancel = (Button) mView.findViewById(R.id.dialog_scratch_card_share_cancel);
        email = (EditText) mView.findViewById(R.id.dialog_scratch_card_share_email);
        submit = (Button) mView.findViewById(R.id.dialog_scratch_card_share_submit);
    }

    private void init() {
        submit.setOnClickListener(submitEvent);
        cancel.setOnClickListener(cancelEvent);
    }

    private View.OnClickListener submitEvent = new View.OnClickListener() {
        @Override
        public void onClick(View v) {
            mScratChcardShareDialogEvent.onClickSubmit();
        }
    };

    private View.OnClickListener cancelEvent = new View.OnClickListener() {
        @Override
        public void onClick(View v) {
            mScratChcardShareDialogEvent.onClickCancel();
        }
    };

    public void setScratChcardShareDialogEvent(ScratChcardShareDialogEvent event) {
        mScratChcardShareDialogEvent = event;
    }

    public EditText getPhoneEdit() {
        return phone;
    }

    public EditText getEmailEdit() {
        return email;
    }
}