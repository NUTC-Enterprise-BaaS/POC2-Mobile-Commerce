package com.androidlibrary.ui.basicinformation;

import android.content.Context;
import android.graphics.Color;
import android.graphics.Typeface;
import android.graphics.drawable.GradientDrawable;
import android.support.v7.widget.Toolbar;
import android.view.Gravity;
import android.view.View;
import android.view.ViewGroup;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.androidlibrary.R;
import com.androidlibrary.component.ReturnButton;
import com.androidlibrary.component.item.BasicInfoItem;
import com.androidlibrary.core.DpToPx;
import com.androidlibrary.core.GenerateViewId;
import com.androidlibrary.core.Ruler;

/**
 * Created by ameng on 2016/6/1.
 */
public class BasicInformationLayout extends RelativeLayout {
    private Ruler ruler;
    private DpToPx equalizer;
    private Toolbar toolbar;
    public ReturnButton toolbarBackImageButton;
    private TextView toolbarTextView;
    public BasicInfoItem account;
    public BasicInfoItem birth;
    public BasicInfoItem country;
    public BasicInfoItem mail;
    public TextView applicationTextView;
    public TextView submit;
    public BasicInfoItem recommend;
    public TextView scanRecommend;


    public BasicInformationLayout(Context context) {
        super(context);
        ruler = new Ruler(getContext());
        equalizer = new DpToPx(getContext());
        toolbar = toolbar();
        toolbarBackImageButton = toolbarBackImageButton();
        toolbarTextView = toolbarTextView();
        account = account(toolbar);
        birth = birth(account);
        country = country(birth);
        recommend = recommend(country);
        mail = mail(recommend);
        applicationTextView = applicationTextView(mail);
        submit = submit(mail);
        scanRecommend = scanRecommend(recommend.leftTextView);


        this.addView(toolbar);
        toolbar.addView(toolbarBackImageButton);
        toolbar.addView(toolbarTextView);
        this.addView(account);
        this.addView(birth);
        this.addView(country);
        this.addView(recommend);
        this.addView(mail);
        this.addView(applicationTextView);
        this.addView(submit);
        recommend.addView(scanRecommend);
        submit.setVisibility(INVISIBLE);
    }

    protected Toolbar toolbar() {
        LayoutParams params = new LayoutParams(
                ViewGroup.LayoutParams.MATCH_PARENT,
                ruler.getH(7));
        Toolbar v = new Toolbar(getContext());
        v.setId(GenerateViewId.get());
        v.setLayoutParams(params);
        v.setBackgroundColor(Color.parseColor("#ff036eb8"));

        return v;
    }

    protected ReturnButton toolbarBackImageButton() {
        LayoutParams params = new LayoutParams(
                equalizer.dp(25),
                equalizer.dp(25));
        params.addRule(CENTER_IN_PARENT);

        ReturnButton v = new ReturnButton(getContext());
        v.setId(GenerateViewId.get());
        v.setLayoutParams(params);
        v.setBackgroundColor(Color.TRANSPARENT);

        return v;
    }


    protected TextView toolbarTextView() {
        Toolbar.LayoutParams params = new Toolbar.LayoutParams(
                ruler.getW(24),
                ruler.getH(5));
        params.gravity = Gravity.CENTER;

        TextView v = new TextView(getContext());
        v.setId(GenerateViewId.get());
        v.setLayoutParams(params);
        v.setText(R.string.basic_information_layout_tittle);
        v.setTextColor(Color.WHITE);
        v.setGravity(Gravity.CENTER);
        ruler.setAudioFit(v, 20, 8, 1);

        return v;
    }

    protected BasicInfoItem account(View topView) {
        LayoutParams params = new LayoutParams(
                ViewGroup.LayoutParams.MATCH_PARENT,
                ViewGroup.LayoutParams.WRAP_CONTENT);
        params.addRule(BELOW, topView.getId());
        params.topMargin = ruler.getH(7);

        BasicInfoItem v = new BasicInfoItem(getContext());
        v.setId(GenerateViewId.get());
        v.setLayoutParams(params);
        v.setLeftText(getContext().getString(R.string.basic_information_layout_account));


        return v;
    }

    protected BasicInfoItem birth(View topView) {
        LayoutParams params = new LayoutParams(
                ViewGroup.LayoutParams.MATCH_PARENT,
                ViewGroup.LayoutParams.WRAP_CONTENT);
        params.addRule(BELOW, topView.getId());
        params.topMargin = ruler.getH(1);

        BasicInfoItem v = new BasicInfoItem(getContext());
        v.setId(GenerateViewId.get());
        v.setLayoutParams(params);
        v.setLeftText(getContext().getString(R.string.basic_information_layout_birth));

        return v;
    }

    protected BasicInfoItem country(View topView) {
        LayoutParams params = new LayoutParams(
                ViewGroup.LayoutParams.MATCH_PARENT,
                ViewGroup.LayoutParams.WRAP_CONTENT);
        params.addRule(BELOW, topView.getId());
        params.topMargin = ruler.getH(1);

        BasicInfoItem v = new BasicInfoItem(getContext());
        v.setId(GenerateViewId.get());
        v.setLayoutParams(params);
        v.setLeftText(getContext().getString(R.string.basic_information_layout_country));

        return v;
    }

    protected BasicInfoItem recommend(View topView) {
        LayoutParams params = new LayoutParams(
                ViewGroup.LayoutParams.MATCH_PARENT,
                ViewGroup.LayoutParams.WRAP_CONTENT);
        params.addRule(BELOW, topView.getId());
        params.topMargin = ruler.getH(1);

        BasicInfoItem v = new BasicInfoItem(getContext());
        v.setId(GenerateViewId.get());
        v.setLayoutParams(params);
        v.setLeftText(getContext().getString(R.string.basic_information_layout_recommend));

        return v;
    }

    protected BasicInfoItem mail(View topView) {
        LayoutParams params = new LayoutParams(
                ViewGroup.LayoutParams.MATCH_PARENT,
                ViewGroup.LayoutParams.WRAP_CONTENT);
        params.addRule(BELOW, topView.getId());
        params.topMargin = ruler.getH(1);

        BasicInfoItem v = new BasicInfoItem(getContext());
        v.setId(GenerateViewId.get());
        v.setLayoutParams(params);
        v.setLeftText(getContext().getString(R.string.basic_information_layout_mail));

        return v;
    }

    protected TextView applicationTextView(View topView) {
        LayoutParams params = new LayoutParams(
                ViewGroup.LayoutParams.WRAP_CONTENT,
                ViewGroup.LayoutParams.WRAP_CONTENT);
        params.addRule(BELOW, topView.getId());
        params.addRule(ALIGN_RIGHT, topView.getId());
        params.topMargin = ruler.getH(1);
        params.rightMargin = ruler.getW(2);

        TextView v = new TextView(getContext());
        v.setId(GenerateViewId.get());
        v.setLayoutParams(params);
        v.setText(R.string.basic_information_layout_applaction);
        v.setTypeface(Typeface.DEFAULT_BOLD, 1);
        v.setTextColor(Color.BLACK);
        ruler.setAudioFit(v, 20, 12, 1);
        v.setOnClickListener(applicationClick);
        return v;
    }

    protected TextView submit(View topView) {
        LayoutParams params = new LayoutParams(
                ViewGroup.LayoutParams.WRAP_CONTENT,
                ViewGroup.LayoutParams.WRAP_CONTENT);
        params.addRule(BELOW, topView.getId());
        params.addRule(ALIGN_RIGHT, topView.getId());
        params.topMargin = ruler.getH(1);
        params.rightMargin = ruler.getW(2);

        TextView v = new TextView(getContext());
        v.setId(GenerateViewId.get());
        v.setLayoutParams(params);
        v.setText(R.string.basic_information_layout_submit);
        v.setTypeface(Typeface.DEFAULT_BOLD, 1);
        v.setTextColor(Color.BLACK);
        ruler.setAudioFit(v, 20, 12, 1);
        v.setOnClickListener(submitEvent);
        return v;
    }

    protected TextView scanRecommend(View leftView) {
        LayoutParams params = new LayoutParams(
                ruler.getW(65),
                ruler.getH(4));
        params.addRule(RIGHT_OF, leftView.getId());
        params.addRule(ALIGN_BASELINE, leftView.getId());
        params.leftMargin = ruler.getW(9);

        TextView v = new TextView(getContext());
        v.setId(GenerateViewId.get());
        v.setGravity(Gravity.CENTER);
        GradientDrawable drawable = new GradientDrawable();
        drawable.setShape(GradientDrawable.RECTANGLE);
        drawable.setCornerRadius(20);
        drawable.setColor(Color.parseColor("#ff036eb8"));

        v.setBackground(drawable);
        v.setLayoutParams(params);
        v.setText(getContext().getString(R.string.basic_information_layout_scan));
        v.setTextColor(Color.WHITE);
        ruler.setAudioFit(v, 15, 10, 1);
        v.setVisibility(GONE);

        return v;
    }

    private View.OnClickListener applicationClick = new OnClickListener() {
        @Override
        public void onClick(View v) {
            mail.click(true);
            applicationTextView.setVisibility(INVISIBLE);
            submit.setVisibility(VISIBLE);
        }
    };

    private View.OnClickListener submitEvent = new OnClickListener() {
        @Override
        public void onClick(View v) {
            if (mail.click(false)) {
                submit.setVisibility(INVISIBLE);
                applicationTextView.setVisibility(VISIBLE);
                submitClick.onClick(v);
            }
        }
    };
    private View.OnClickListener submitClick;

    public void setSubmitClick(View.OnClickListener submitEvent) {
        submitClick = submitEvent;
    }
}
