package com.androidlibrary.component.dialog;

import android.app.Dialog;
import android.content.Context;
import android.graphics.Color;
import android.graphics.drawable.ColorDrawable;
import android.graphics.drawable.GradientDrawable;
import android.util.TypedValue;
import android.view.Gravity;
import android.view.View;
import android.view.ViewGroup;
import android.view.Window;
import android.view.WindowManager;
import android.widget.Button;
import android.widget.FrameLayout;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.androidlibrary.R;
import com.androidlibrary.core.GenerateViewId;
import com.androidlibrary.core.Ruler;

/**
 * Created by ameng on 2016/6/2.
 */
public class QrDialog extends Dialog {
    private Context context;
    private Ruler ruler;
    private RelativeLayout baseLayout;
    private FrameLayout frameLayout;
    private TextView tittleTextView;
    private TextView contextTextView;
    public Button submitButton;

    public QrDialog(Context context) {
        super(context);
        this.context = context;
        init();
        setContentView(frameLayout);
    }

    private void init() {
        Window window = getWindow();
        requestWindowFeature(Window.FEATURE_NO_TITLE);
        getWindow().setBackgroundDrawable(new ColorDrawable(Color.TRANSPARENT));

        WindowManager.LayoutParams params = window.getAttributes();
        params.width = ViewGroup.LayoutParams.MATCH_PARENT;
        params.height = ViewGroup.LayoutParams.MATCH_PARENT;
        params.gravity = Gravity.CENTER;
        window.setAttributes(params);

        ruler = new Ruler(context);
        frameLayout = frameLayout();
        baseLayout = baseLayout();
        tittleTextView = tittleTextView();
        contextTextView = contextTextView(tittleTextView);
        submitButton = submitButton(contextTextView);

        frameLayout.addView(baseLayout);
        baseLayout.addView(tittleTextView);
        baseLayout.addView(contextTextView);
        baseLayout.addView(submitButton);
    }

    protected FrameLayout frameLayout() {
        RelativeLayout.LayoutParams params = new RelativeLayout.LayoutParams(
                ViewGroup.LayoutParams.WRAP_CONTENT,
                ViewGroup.LayoutParams.WRAP_CONTENT);
        params.addRule(RelativeLayout.CENTER_IN_PARENT);

        FrameLayout v = new FrameLayout(getContext());
        v.setId(GenerateViewId.get());
        v.setLayoutParams(params);


        return v;
    }

    protected RelativeLayout baseLayout() {
        FrameLayout.LayoutParams params = new FrameLayout.LayoutParams(
                ruler.getW(70),
                ruler.getH(35));

        RelativeLayout v = new RelativeLayout(getContext());
        v.setId(GenerateViewId.get());
        v.setLayoutParams(params);
        GradientDrawable drawable = new GradientDrawable();
        drawable.setShape(GradientDrawable.RECTANGLE);
        drawable.setCornerRadius(50);
        drawable.setColor(Color.WHITE);
        v.setBackground(drawable);

        return v;
    }

    protected TextView tittleTextView() {
        RelativeLayout.LayoutParams params = new RelativeLayout.LayoutParams(
                ViewGroup.LayoutParams.WRAP_CONTENT,
                ViewGroup.LayoutParams.WRAP_CONTENT);
        params.addRule(RelativeLayout.CENTER_HORIZONTAL);
        params.topMargin = ruler.getH(3);

        TextView v = new TextView(getContext());
        v.setId(GenerateViewId.get());
        v.setLayoutParams(params);
        v.setText(getContext().getString(R.string.qr_success_already_send_to_store) + "\n" + getContext().getString(R.string.qr_success_please_contract_in_store));
        v.setTextSize(TypedValue.COMPLEX_UNIT_DIP, 19);
        return v;
    }

    protected TextView contextTextView(View topView) {
        RelativeLayout.LayoutParams params = new RelativeLayout.LayoutParams(
                ViewGroup.LayoutParams.WRAP_CONTENT,
                ViewGroup.LayoutParams.WRAP_CONTENT);
        params.addRule(RelativeLayout.BELOW, topView.getId());
        params.addRule(RelativeLayout.CENTER_HORIZONTAL);
        params.topMargin = ruler.getH(5);

        TextView v = new TextView(getContext());
        v.setId(GenerateViewId.get());
        v.setLayoutParams(params);
        v.setText(R.string.qr_success_please_input_phone_number);
        v.setTextSize(TypedValue.COMPLEX_UNIT_DIP, 17);

        return v;
    }

    protected Button submitButton(View topView) {
        RelativeLayout.LayoutParams params = new RelativeLayout.LayoutParams(
                ViewGroup.LayoutParams.WRAP_CONTENT,
                ViewGroup.LayoutParams.WRAP_CONTENT);
        params.addRule(RelativeLayout.BELOW, topView.getId());
        params.addRule(RelativeLayout.CENTER_HORIZONTAL);
        params.topMargin = ruler.getH(5);

        Button v = new Button(getContext());
        v.setId(GenerateViewId.get());
        v.setLayoutParams(params);
        v.setText(R.string.qr_success_check);
        v.setTextColor(Color.WHITE);
        v.setTextSize(TypedValue.COMPLEX_UNIT_DIP, 17);
        GradientDrawable drawable = new GradientDrawable();
        drawable.setShape(GradientDrawable.RECTANGLE);
        drawable.setCornerRadius(50);
        drawable.setColor(Color.parseColor("#ff1367ab"));
        v.setBackground(drawable);
        v.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                submitClick.onClick(v);
                dismiss();
            }
        });

        return v;
    }

    private View.OnClickListener submitClick;

    public void setSubmitClick(View.OnClickListener event) {
        submitClick = event;
    }

    public void setTitle(String text) {
        tittleTextView.setText(text);
    }

    public void setContext(String text) {
        contextTextView.setText(text);
    }

    public void setButtonColor(int color) {
        GradientDrawable drawable = new GradientDrawable();
        drawable.setShape(GradientDrawable.RECTANGLE);
        drawable.setCornerRadius(50);
        drawable.setColor(color);
        submitButton.setBackground(drawable);
    }

}

