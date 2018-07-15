package com.herbhousesgobuyother.contrube.component.dialog;

import android.app.AlertDialog;
import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;

import com.herbhousesgobuyother.R;

/**
 * Created by Gary on 2017/1/17.
 */

public class VipDialog extends AlertDialog.Builder {
    private View view;
    private Context context;
    private Button cancle;
    private Button submit;
    private EditText input;
    private VipDialog.VipDialogClick dialogClick;

    public VipDialog(Context context) {
        super(context);
        this.context = context;
        view = LayoutInflater.from(context).inflate(R.layout.dialog_vip, null);
        this.setView(view);
        this.setCancelable(true);
        findView();
        init();
    }

    private void findView() {
        input = (EditText) view.findViewById(R.id.input);
        cancle = (Button) view.findViewById(R.id.cancel);
        submit = (Button) view.findViewById(R.id.submit);
    }

    private void init() {
        submit.setOnClickListener(submitClick);
        cancle.setOnClickListener(cancleClick);
    }

    private View.OnClickListener submitClick = new View.OnClickListener() {
        @Override
        public void onClick(View v) {
            dialogClick.onSubmitClick();
        }
    };

    private View.OnClickListener cancleClick = new View.OnClickListener() {
        @Override
        public void onClick(View v) {
            dialogClick.onCancelClick();
        }
    };

    public EditText getInput() {
        return input;
    }

    public void setCallBackEvent(VipDialog.VipDialogClick dialogClick) {
        this.dialogClick = dialogClick;
    }

    public interface VipDialogClick {
        void onSubmitClick();

        void onCancelClick();
    }
}
