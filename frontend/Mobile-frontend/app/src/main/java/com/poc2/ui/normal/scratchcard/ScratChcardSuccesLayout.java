package com.poc2.ui.normal.scratchcard;

import android.content.Context;
import android.graphics.Color;
import android.graphics.drawable.GradientDrawable;
import android.support.percent.PercentLayoutHelper;
import android.support.percent.PercentRelativeLayout;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.TextView;

import com.androidlibrary.core.GenerateViewId;
import com.poc2.R;

import me.grantland.widget.AutofitHelper;

/**
 * Created by ameng on 7/19/16.
 */
public class ScratChcardSuccesLayout extends PercentRelativeLayout {
    private PercentLayoutHelper.PercentLayoutInfo info;
    public TextView tittleTextView;
    public TextView phoneTextView;
    public TextView mailTextView;
    public TextView contentTextView;
    public TextView confirmButton;

    public ScratChcardSuccesLayout(Context context) {
        super(context);
        init();
        tittleTextView = tittleTextView();
        phoneTextView = phoneTextView(tittleTextView);
        mailTextView = mailTextView(phoneTextView);
        contentTextView = contentTextView(mailTextView);
        confirmButton = confirmButton(mailTextView);
        this.addView(tittleTextView);
        this.addView(phoneTextView);
        this.addView(mailTextView);
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

    private TextView tittleTextView() {
        LayoutParams params = new LayoutParams(
                ViewGroup.LayoutParams.WRAP_CONTENT,
                ViewGroup.LayoutParams.WRAP_CONTENT);
        info = params.getPercentLayoutInfo();
        info.leftMarginPercent = 0.08f;
        info.topMarginPercent = 0.04f;

        TextView v = new TextView(getContext());
        v.setId(GenerateViewId.get());
        v.setLayoutParams(params);
        v.setText(R.string.ScratChcardSuccesLayout_tittle);
        v.setTextColor(0xFFF28DC6);
        v.setSingleLine();

        AutofitHelper autofitHelper = AutofitHelper.create(v);
        autofitHelper.setMaxTextSize(25);
        autofitHelper.setMinTextSize(10);
        autofitHelper.setMaxLines(1);
        return v;
    }

    protected TextView phoneTextView(View topView) {
        LayoutParams params = new LayoutParams(
                ViewGroup.LayoutParams.WRAP_CONTENT,
                ViewGroup.LayoutParams.WRAP_CONTENT);
        params.addRule(BELOW, topView.getId());
        info = params.getPercentLayoutInfo();
        info.leftMarginPercent = 0.10f;
        info.topMarginPercent = 0.05f;

        TextView v = new TextView(getContext());
        v.setId(GenerateViewId.get());
        v.setLayoutParams(params);
        v.setTextColor(0xFFB8B8B8);
        v.setSingleLine();

        AutofitHelper autofitHelper = AutofitHelper.create(v);
        autofitHelper.setMaxTextSize(25);
        autofitHelper.setMinTextSize(15);
        autofitHelper.setMaxLines(1);
        return v;
    }

    protected TextView mailTextView(View topView) {
        LayoutParams params = new LayoutParams(
                ViewGroup.LayoutParams.WRAP_CONTENT,
                ViewGroup.LayoutParams.WRAP_CONTENT);
        params.addRule(BELOW, topView.getId());
        info = params.getPercentLayoutInfo();
        info.leftMarginPercent = 0.10f;
        info.topMarginPercent = 0.02f;

        TextView v = new TextView(getContext());
        v.setId(GenerateViewId.get());
        v.setLayoutParams(params);
        v.setTextColor(0xFFB8B8B8);
        v.setSingleLine();

        AutofitHelper autofitHelper = AutofitHelper.create(v);
        autofitHelper.setMaxTextSize(25);
        autofitHelper.setMinTextSize(20);
        autofitHelper.setMaxLines(1);
        return v;
    }

    protected TextView contentTextView(View topView) {
        LayoutParams params = new LayoutParams(
                ViewGroup.LayoutParams.WRAP_CONTENT,
                ViewGroup.LayoutParams.WRAP_CONTENT);
        params.addRule(BELOW, topView.getId());
        info = params.getPercentLayoutInfo();
        info.leftMarginPercent = 0.10f;
        info.topMarginPercent = 0.05f;

        TextView v = new TextView(getContext());
        v.setId(GenerateViewId.get());
        v.setLayoutParams(params);
        v.setTextColor(0xFFF28DC6);

        AutofitHelper autofitHelper = AutofitHelper.create(v);
        autofitHelper.setMaxTextSize(25);
        autofitHelper.setMinTextSize(20);
        autofitHelper.setMaxLines(1);
        return v;
    }

    protected Button confirmButton(View topView){
        LayoutParams params = new LayoutParams(
                ViewGroup.LayoutParams.WRAP_CONTENT,
                ViewGroup.LayoutParams.WRAP_CONTENT);
        params.addRule(BELOW, topView.getId());
        params.addRule(CENTER_HORIZONTAL);
        info = params.getPercentLayoutInfo();
        info.topMarginPercent = 0.05f;
        info.bottomMarginPercent = 0.05f;

        Button v = new Button(getContext());
        v.setId(GenerateViewId.get());
        v.setLayoutParams(params);
        v.setText(R.string.point_dialog_confirm);
        v.setTextColor(0xFFFFFFFF);
        GradientDrawable gradientDrawable = new GradientDrawable();
        gradientDrawable.setShape(GradientDrawable.RECTANGLE);
        gradientDrawable.setCornerRadius(50);
        gradientDrawable.setColor(0xFF1366AA);
        v.setBackground(gradientDrawable);

        AutofitHelper autofitHelper = AutofitHelper.create(v);
        autofitHelper.setMaxTextSize(25);
        autofitHelper.setMinTextSize(15);
        autofitHelper.setMaxLines(1);
        return v;
    }

}
