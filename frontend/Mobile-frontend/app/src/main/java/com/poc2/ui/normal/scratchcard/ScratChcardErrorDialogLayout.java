package com.poc2.ui.normal.scratchcard;

import android.content.Context;
import android.graphics.Color;
import android.graphics.drawable.GradientDrawable;
import android.support.percent.PercentLayoutHelper;
import android.support.percent.PercentRelativeLayout;
import android.view.Gravity;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.androidlibrary.core.GenerateViewId;
import com.poc2.R;

import me.grantland.widget.AutofitHelper;

/**
 * Created by ameng on 7/19/16.
 */
public class ScratChcardErrorDialogLayout extends PercentRelativeLayout {
    private RelativeLayout relativeLayout;
    public TextView message;
    public TextView confirmButton;
    private PercentLayoutHelper.PercentLayoutInfo info;

    public ScratChcardErrorDialogLayout(Context context) {
        super(context);
        init();
        relativeLayout = relativeLayout();
        message = message();
        confirmButton = confirmButton(message);
        this.addView(relativeLayout);
        relativeLayout.addView(message);
        relativeLayout.addView(confirmButton);
    }

    private void init() {
        LayoutParams params = new LayoutParams(
                ViewGroup.LayoutParams.WRAP_CONTENT,
                ViewGroup.LayoutParams.WRAP_CONTENT);
        info = params.getPercentLayoutInfo();
        GradientDrawable gradientDrawable = new GradientDrawable();
        gradientDrawable.setShape(GradientDrawable.RECTANGLE);
        gradientDrawable.setCornerRadius(50);
        gradientDrawable.setColor(Color.WHITE);
        this.setBackground(gradientDrawable);
        this.setLayoutParams(params);
    }

    private RelativeLayout relativeLayout(){
        LayoutParams params = new LayoutParams(
                ViewGroup.LayoutParams.MATCH_PARENT,
                ViewGroup.LayoutParams.WRAP_CONTENT);
        info = params.getPercentLayoutInfo();
        params.addRule(CENTER_IN_PARENT);
        info.heightPercent = 0.40f;
        RelativeLayout v = new RelativeLayout(getContext());
        v.setId(GenerateViewId.get());
        v.setLayoutParams(params);
        v.setGravity(Gravity.CENTER);

        return v;
    }

    private TextView message() {
        LayoutParams params = new LayoutParams(
                ViewGroup.LayoutParams.WRAP_CONTENT,
                ViewGroup.LayoutParams.WRAP_CONTENT);
        info = params.getPercentLayoutInfo();
        params.addRule(CENTER_HORIZONTAL);

        TextView v = new TextView(getContext());
        v.setId(GenerateViewId.get());
        v.setLayoutParams(params);
        v.setGravity(Gravity.CENTER);
        v.setTextColor(0xFF000000);
        v.setSingleLine();

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
        params.topMargin = 100;
        params.addRule(CENTER_HORIZONTAL);
        info = params.getPercentLayoutInfo();

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
