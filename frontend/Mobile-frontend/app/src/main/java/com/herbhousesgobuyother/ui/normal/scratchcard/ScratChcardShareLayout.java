package com.herbhousesgobuyother.ui.normal.scratchcard;

import android.app.AlertDialog;
import android.content.Context;
import android.graphics.Color;
import android.graphics.drawable.GradientDrawable;
import android.support.percent.PercentLayoutHelper;
import android.support.percent.PercentRelativeLayout;
import android.text.InputType;
import android.view.View;
import android.view.ViewGroup;
import android.view.WindowManager;
import android.widget.Button;
import android.widget.TextView;

import com.androidlibrary.component.AutoCleanFocusEditText;
import com.androidlibrary.core.GenerateViewId;
import com.herbhousesgobuyother.R;
import com.herbhousesgobuyother.component.dialog.ScratChcardErrorDialog;

import me.grantland.widget.AutofitHelper;

/**
 * Created by ameng on 7/18/16.
 */
public class ScratChcardShareLayout extends PercentRelativeLayout {
    public TextView tittleTextView;
    public AutoCleanFocusEditText phoneEditText;
    public AutoCleanFocusEditText mailEditText;
    public Button cancelButton;
    public Button confirmButton;
    private PercentLayoutHelper.PercentLayoutInfo info;
    private ScratChcardErrorDialog loginScratChcardErrorDialog;
    private AlertDialog alert;

    public ScratChcardShareLayout(Context context) {
        super(context);
        loginScratChcardErrorDialog = new ScratChcardErrorDialog(getContext());
        alert = loginScratChcardErrorDialog.create();
        alert.getWindow().setType(WindowManager.LayoutParams.TYPE_SYSTEM_ALERT);//設定提示框為系統提示框

        init();
        tittleTextView = tittleTextView();
        phoneEditText = phoneEditText(tittleTextView);
        mailEditText = mailEditText(phoneEditText);
        cancelButton = cancelButton(mailEditText);
        confirmButton = confirmButton(cancelButton);
        this.addView(tittleTextView);
        this.addView(phoneEditText);
        this.addView(mailEditText);
        this.addView(cancelButton);
        this.addView(confirmButton);
    }

    private void init() {
        LayoutParams params = new LayoutParams(
                ViewGroup.LayoutParams.WRAP_CONTENT,
                ViewGroup.LayoutParams.WRAP_CONTENT);
        info = params.getPercentLayoutInfo();
        info.heightPercent = 0.75f;
        info.widthPercent = 0.45f;
        GradientDrawable gradientDrawable = new GradientDrawable();
        gradientDrawable.setShape(GradientDrawable.RECTANGLE);
        gradientDrawable.setCornerRadius(50);
        gradientDrawable.setColor(Color.WHITE);
        this.setBackground(gradientDrawable);
        this.setLayoutParams(params);
    }

    private void setAutofitHelper(TextView autoView) {
        AutofitHelper autofitHelper = AutofitHelper.create(autoView);
        autofitHelper.setMaxTextSize(25);
        autofitHelper.setMinTextSize(20);
        autofitHelper.setMaxLines(1);
    }

    protected TextView tittleTextView() {
        LayoutParams params = new LayoutParams(
                ViewGroup.LayoutParams.WRAP_CONTENT,
                ViewGroup.LayoutParams.WRAP_CONTENT);
        info = params.getPercentLayoutInfo();
        info.heightPercent = 0.06f;
        info.widthPercent = 0.80f;
        info.leftMarginPercent = 0.15f;
        info.topMarginPercent = 0.05f;

        TextView v = new TextView(getContext());
        v.setId(GenerateViewId.get());
        v.setLayoutParams(params);
        v.setText(R.string.ScratChcardShareLayout_tittle);
        v.setTextColor(0xFFB8B8B8);
        setAutofitHelper(v);
        return v;
    }

    protected AutoCleanFocusEditText phoneEditText(View topView) {
        LayoutParams params = new LayoutParams(
                ViewGroup.LayoutParams.WRAP_CONTENT,
                ViewGroup.LayoutParams.WRAP_CONTENT);
        params.addRule(BELOW, topView.getId());
        params.addRule(CENTER_HORIZONTAL);
        info = params.getPercentLayoutInfo();
        info.heightPercent = 0.13f;
        info.widthPercent = 0.75f;
        info.topMarginPercent = 0.04f;

        AutoCleanFocusEditText v = new AutoCleanFocusEditText(getContext());
        v.setId(GenerateViewId.get());
        v.setLayoutParams(params);
        v.setHint(R.string.registered_layout_account_phone_hint);
        v.setTextColor(0xFFB8B8B8);
        v.setHintTextColor(0xFFB8B8B8);
        v.setInputType(InputType.TYPE_CLASS_NUMBER);
        GradientDrawable gradientDrawable = new GradientDrawable();
        gradientDrawable.setShape(GradientDrawable.RECTANGLE);
        gradientDrawable.setCornerRadius(50);
        gradientDrawable.setStroke(2, 0xFFB8B8B8);
        v.setBackground(gradientDrawable);
        v.setSingleLine();
        setAutofitHelper(v);
        return v;
    }

    protected AutoCleanFocusEditText mailEditText(View topView) {
        LayoutParams params = new LayoutParams(
                ViewGroup.LayoutParams.WRAP_CONTENT,
                ViewGroup.LayoutParams.WRAP_CONTENT);
        params.addRule(BELOW, topView.getId());
        params.addRule(CENTER_HORIZONTAL);
        info = params.getPercentLayoutInfo();
        info.heightPercent = 0.13f;
        info.widthPercent = 0.75f;
        info.topMarginPercent = 0.04f;

        AutoCleanFocusEditText v = new AutoCleanFocusEditText(getContext());
        v.setId(GenerateViewId.get());
        v.setLayoutParams(params);
        v.setHint(R.string.registered_layout_account_email_hint);
        v.setTextColor(0xFFB8B8B8);
        v.setHintTextColor(0xFFB8B8B8);
        GradientDrawable gradientDrawable = new GradientDrawable();
        gradientDrawable.setShape(GradientDrawable.RECTANGLE);
        gradientDrawable.setCornerRadius(50);
        gradientDrawable.setStroke(2, 0xFFB8B8B8);
        v.setBackground(gradientDrawable);
        v.setSingleLine();
        setAutofitHelper(v);
        return v;
    }

    protected Button cancelButton(View topView) {
        LayoutParams params = new LayoutParams(
                ViewGroup.LayoutParams.WRAP_CONTENT,
                ViewGroup.LayoutParams.WRAP_CONTENT);
        params.addRule(BELOW, topView.getId());
        info = params.getPercentLayoutInfo();
        info.heightPercent = 0.11f;
        info.widthPercent = 0.25f;
        info.topMarginPercent = 0.04f;
        info.leftMarginPercent = 0.15f;
        info.bottomMarginPercent = 0.10f;

        Button v = new Button(getContext());
        v.setId(GenerateViewId.get());
        v.setLayoutParams(params);
        v.setText(R.string.check_clear_dialog_cancel);
        v.setTextColor(0xFFFFFFFF);
        GradientDrawable gradientDrawable = new GradientDrawable();
        gradientDrawable.setShape(GradientDrawable.RECTANGLE);
        gradientDrawable.setCornerRadius(50);
        gradientDrawable.setColor(0xFF1366AA);
        v.setBackground(gradientDrawable);
        setAutofitHelper(v);
        return v;
    }

    protected Button confirmButton(View leftView) {
        LayoutParams params = new LayoutParams(
                ViewGroup.LayoutParams.WRAP_CONTENT,
                ViewGroup.LayoutParams.WRAP_CONTENT);
        params.addRule(ALIGN_TOP, leftView.getId());
        params.addRule(ALIGN_PARENT_RIGHT, leftView.getId());
        info = params.getPercentLayoutInfo();
        info.heightPercent = 0.11f;
        info.widthPercent = 0.25f;
        info.rightMarginPercent = 0.15f;
        info.bottomMarginPercent = 0.10f;

        Button v = new Button(getContext());
        v.setId(GenerateViewId.get());
        v.setLayoutParams(params);
        v.setText(R.string.point_dialog_submit);
        v.setTextColor(0xFFFFFFFF);
        GradientDrawable gradientDrawable = new GradientDrawable();
        gradientDrawable.setShape(GradientDrawable.RECTANGLE);
        gradientDrawable.setCornerRadius(50);
        gradientDrawable.setColor(0xFF1366AA);
        v.setBackground(gradientDrawable);
        setAutofitHelper(v);
        v.setOnClickListener(new OnClickListener() {
            @Override
            public void onClick(View v) {
                if (phoneEditText.getText().toString().trim().isEmpty()){
                    loginScratChcardErrorDialog.layout.message.setText(getContext().getResources().getString(R.string.scratchcard_share_layout_empty_error_0));
                    alert.show();
                }
                else if (mailEditText.getText().toString().trim().isEmpty()){
                    loginScratChcardErrorDialog.layout.message.setText(getContext().getResources().getString(R.string.scratchcard_share_layout_empty_error_1));
                    alert.show();
                }
                else if (!android.util.Patterns.EMAIL_ADDRESS.matcher(mailEditText.getText().toString()).matches()){
                    loginScratChcardErrorDialog.layout.message.setText(getContext().getResources().getString(R.string.scratchcard_share_layout_empty_error_1));
                    alert.show();
                }
                confirmEvent.onClick(v);
            }
        });
        return v;
    }

    private OnClickListener confirmEvent;
    public void setCancel(OnClickListener event) {
        cancelButton.setOnClickListener(event);
    }

    public void setConfirm(OnClickListener event) {
        confirmEvent=event;
    }
}
