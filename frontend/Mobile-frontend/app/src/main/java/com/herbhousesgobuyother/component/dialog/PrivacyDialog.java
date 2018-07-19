package com.herbhousesgobuyother.component.dialog;

import android.app.Dialog;
import android.content.Context;
import android.graphics.Color;
import android.support.annotation.NonNull;
import android.support.annotation.StyleRes;
import android.util.TypedValue;
import android.view.View;
import android.view.ViewGroup;
import android.view.Window;
import android.widget.Button;
import android.widget.RelativeLayout;
import android.widget.ScrollView;
import android.widget.TextView;

import com.androidlibrary.core.DpToPx;
import com.androidlibrary.core.GenerateViewId;
import com.androidlibrary.core.Ruler;
import com.herbhousesgobuyother.R;

/**
 * Created by ameng on 2016/5/27.
 */
public class PrivacyDialog extends Dialog {
    private Ruler ruler;
    private DpToPx equalizer;
    private RelativeLayout viewContainer;
    private ScrollView scrollView;
    private RelativeLayout contextRelativeLayout;
    private TextView tittleTextView;
    private View splitView;
    private TextView topTextView;
    private TextView middleTextView;
    private TextView bottomTextView;
    public Button disAgreeButton;
    public Button agreeButton;
    private View lineAboveButton;
    private View lineCenterButton;
    public Boolean isShow;
    private OnAgreeInterface onAgreeInterface;

    public interface OnAgreeInterface {
        void agree();
    }

    public PrivacyDialog(@NonNull Context context, @StyleRes int themeResId) {
        super(context, themeResId);
        ruler = new Ruler(context);
        equalizer = new DpToPx(context);

        init();

        tittleTextView = tittleTextView();
        scrollView = scrollView(tittleTextView);
        contextRelativeLayout = contextRelativeLayout();
        splitView = splitView(tittleTextView);
        topTextView = topTextView();
        middleTextView = middleTextView(topTextView);
        bottomTextView = bottomTextView(middleTextView);
        disAgreeButton = disAgreeButton();
        agreeButton = agreeButton();
        lineAboveButton = lineAboveButton(disAgreeButton);
        lineCenterButton = lineCenterButton(disAgreeButton);

        setContentView(viewContainer);
        viewContainer.addView(scrollView);
        viewContainer.addView(tittleTextView);
        viewContainer.addView(splitView);
        scrollView.addView(contextRelativeLayout);
        contextRelativeLayout.addView(topTextView);
        contextRelativeLayout.addView(middleTextView);
        contextRelativeLayout.addView(bottomTextView);
        viewContainer.addView(disAgreeButton);
        viewContainer.addView(agreeButton);
        viewContainer.addView(lineAboveButton);
        viewContainer.addView(lineCenterButton);
    }

    private void init() {
        isShow = false;
        requestWindowFeature(Window.FEATURE_NO_TITLE);
        viewContainer = new RelativeLayout(getContext());
        viewContainer.setLayoutParams(new ViewGroup.LayoutParams(
                ViewGroup.LayoutParams.MATCH_PARENT,
                ViewGroup.LayoutParams.MATCH_PARENT));
        viewContainer.setBackgroundColor(Color.WHITE);
    }

    protected TextView tittleTextView() {
        RelativeLayout.LayoutParams params = new RelativeLayout.LayoutParams(
                ViewGroup.LayoutParams.WRAP_CONTENT,
                ViewGroup.LayoutParams.WRAP_CONTENT);
        params.setMargins(0, ruler.getH(6), 0, 0);
        params.addRule(RelativeLayout.CENTER_HORIZONTAL);

        TextView v = new TextView(getContext());
        v.setLayoutParams(params);
        v.setId(GenerateViewId.get());
        v.setTextColor(Color.parseColor("#727171"));
        v.setText(R.string.privacy_policy);
        v.setTextSize(TypedValue.COMPLEX_UNIT_DIP, 17);
        return v;
    }

    protected ScrollView scrollView(View topView) {
        RelativeLayout.LayoutParams params = new RelativeLayout.LayoutParams(
                ViewGroup.LayoutParams.MATCH_PARENT,
                ruler.getH(75));
        params.addRule(RelativeLayout.BELOW, topView.getId());
        params.setMargins(0, ruler.getH(1), 0, 0);

        ScrollView v = new ScrollView(getContext());
        v.setLayoutParams(params);
        v.setId(GenerateViewId.get());
        return v;
    }

    protected RelativeLayout contextRelativeLayout() {
        RelativeLayout.LayoutParams params = new RelativeLayout.LayoutParams(
                ViewGroup.LayoutParams.MATCH_PARENT,
                ViewGroup.LayoutParams.MATCH_PARENT);

        RelativeLayout v = new RelativeLayout(getContext());
        v.setLayoutParams(params);
        v.setId(GenerateViewId.get());

        return v;
    }

    protected View splitView(View topView) {
        RelativeLayout.LayoutParams params = new RelativeLayout.LayoutParams(
                ruler.getW(97),
                ruler.getH(0.4));
        params.setMargins(0, ruler.getH(2), 0, 0);
        params.addRule(RelativeLayout.CENTER_HORIZONTAL);
        params.addRule(RelativeLayout.BELOW, topView.getId());

        View v = new View(getContext());
        v.setLayoutParams(params);
        v.setId(GenerateViewId.get());
        v.setBackgroundColor(Color.parseColor("#000000"));

        return v;
    }

    protected TextView topTextView() {
        RelativeLayout.LayoutParams params = new RelativeLayout.LayoutParams(
                ViewGroup.LayoutParams.WRAP_CONTENT,
                ViewGroup.LayoutParams.WRAP_CONTENT);
        params.setMargins(0, ruler.getH(1.5), 0, 0);
        params.addRule(RelativeLayout.CENTER_HORIZONTAL);

        TextView v = new TextView(getContext());
        v.setLayoutParams(params);
        v.setId(GenerateViewId.get());
        v.setTextColor(Color.parseColor("#595757"));
        v.setText(R.string.privacy_policy_content_top);
        v.setTextSize(TypedValue.COMPLEX_UNIT_DIP, 15);
        v.setPadding(ruler.getW(1), ruler.getH(1), ruler.getW(1), ruler.getH(1));

        return v;
    }


    protected TextView middleTextView(View topView) {
        RelativeLayout.LayoutParams params = new RelativeLayout.LayoutParams(
                ViewGroup.LayoutParams.WRAP_CONTENT,
                ViewGroup.LayoutParams.WRAP_CONTENT);
        params.setMargins(0, ruler.getH(1.5), 0, 0);
        params.addRule(RelativeLayout.CENTER_HORIZONTAL);
        params.addRule(RelativeLayout.BELOW, topView.getId());

        TextView v = new TextView(getContext());
        v.setLayoutParams(params);
        v.setId(GenerateViewId.get());
        v.setTextColor(Color.parseColor("#595757"));
        v.setText(R.string.privacy_policy_content_middle);
        v.setTextSize(TypedValue.COMPLEX_UNIT_DIP, 15);
        v.setPadding(ruler.getW(1), ruler.getH(1), ruler.getW(1), ruler.getH(1));
        return v;
    }

    protected TextView bottomTextView(View topView) {
        RelativeLayout.LayoutParams params = new RelativeLayout.LayoutParams(
                ViewGroup.LayoutParams.WRAP_CONTENT,
                ViewGroup.LayoutParams.WRAP_CONTENT);
        params.setMargins(0, ruler.getH(1.5), 0, 0);
        params.addRule(RelativeLayout.CENTER_HORIZONTAL);
        params.addRule(RelativeLayout.BELOW, topView.getId());

        TextView v = new TextView(getContext());
        v.setLayoutParams(params);
        v.setId(GenerateViewId.get());
        v.setTextColor(Color.parseColor("#595757"));
        v.setText(R.string.privacy_policy_content_bottom);
        v.setTextSize(TypedValue.COMPLEX_UNIT_DIP, 15);
        v.setPadding(ruler.getW(1), ruler.getH(1), ruler.getW(1), ruler.getH(1));

        return v;
    }

    protected Button disAgreeButton() {
        RelativeLayout.LayoutParams params = new RelativeLayout.LayoutParams(
                ruler.getW(50),
                ruler.getH(10));
        params.addRule(RelativeLayout.ALIGN_PARENT_BOTTOM);
        params.addRule(RelativeLayout.ALIGN_PARENT_LEFT);

        Button v = new Button(getContext());
        v.setLayoutParams(params);
        v.setId(GenerateViewId.get());
        v.setTextColor(Color.parseColor("#818181"));
        v.setText(R.string.privacy_policy_disagree_button);
        v.setTextSize(TypedValue.COMPLEX_UNIT_DIP, 15);
        v.setOnClickListener(disAgreeClick);
        v.setBackgroundColor(Color.WHITE);

        return v;
    }

    protected Button agreeButton() {
        RelativeLayout.LayoutParams params = new RelativeLayout.LayoutParams(
                ruler.getW(50),
                ruler.getH(10));
        params.addRule(RelativeLayout.ALIGN_PARENT_BOTTOM);
        params.addRule(RelativeLayout.ALIGN_PARENT_RIGHT);

        Button v = new Button(getContext());
        v.setLayoutParams(params);
        v.setId(GenerateViewId.get());
        v.setTextColor(Color.parseColor("#818181"));
        v.setText(R.string.privacy_policy_agree_button);
        v.setTextSize(TypedValue.COMPLEX_UNIT_DIP, 15);
        v.setOnClickListener(agreeClick);
        v.setBackgroundColor(Color.WHITE);

        return v;
    }

    protected View lineAboveButton(View bottomView) {
        RelativeLayout.LayoutParams params = new RelativeLayout.LayoutParams(
                ViewGroup.LayoutParams.MATCH_PARENT,
                equalizer.dp(1));
        params.addRule(RelativeLayout.ALIGN_TOP, bottomView.getId());

        View v = new View(getContext());
        v.setLayoutParams(params);
        v.setId(GenerateViewId.get());
        v.setBackgroundColor(Color.parseColor("#9a9899"));

        return v;
    }

    protected View lineCenterButton(View bottomView) {
        RelativeLayout.LayoutParams params = new RelativeLayout.LayoutParams(
                equalizer.dp(1),
                ruler.getH(10));
        params.addRule(RelativeLayout.ALIGN_TOP, bottomView.getId());
        params.addRule(RelativeLayout.RIGHT_OF, bottomView.getId());

        View v = new View(getContext());
        v.setLayoutParams(params);
        v.setId(GenerateViewId.get());
        v.setBackgroundColor(Color.parseColor("#9a9899"));

        return v;
    }

    private View.OnClickListener agreeClick = new View.OnClickListener() {
        @Override
        public void onClick(View v) {
            onAgreeInterface.agree();
            dismiss();
        }
    };

    private View.OnClickListener disAgreeClick = new View.OnClickListener() {
        @Override
        public void onClick(View v) {
            dismiss();
        }
    };

    @Override
    public void show() {
        super.show();
        isShow = true;
    }

    @Override
    public void dismiss() {
        super.dismiss();
        isShow = false;
    }

    public void setOnAgreeInterface(OnAgreeInterface event) {
        this.onAgreeInterface = event;
    }
}
