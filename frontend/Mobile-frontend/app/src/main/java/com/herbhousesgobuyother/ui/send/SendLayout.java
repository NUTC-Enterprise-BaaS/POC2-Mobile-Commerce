package com.herbhousesgobuyother.ui.send;

import android.content.Context;
import android.graphics.drawable.GradientDrawable;
import android.view.Gravity;
import android.view.View;
import android.widget.Button;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.androidlibrary.core.GenerateViewId;
import com.androidlibrary.core.Ruler;
import com.herbhousesgobuyother.R;

/**
 * Created by 依杰 on 2016/8/16.
 */
public class SendLayout extends RelativeLayout {
    private Ruler ruler;
    public RelativeLayout content;
    public TextView sendEmailTextView;
    public TextView sendPointTextView;
    public Button accussButton;
    public Button cancleButton;


    public SendLayout(Context context) {
        super(context);
        ruler = new Ruler(context);
        setBackgroundColor(0xFF989898);

        content = content();
        sendEmailTextView = sendEmailTextView();
        sendPointTextView = sendPointTextView(sendEmailTextView);
        accussButton = accussButton(sendPointTextView);
        cancleButton = cancleButton(sendPointTextView);

        addView(content);
        content.addView(sendEmailTextView);
        content.addView(sendPointTextView);
        content.addView(accussButton);
        content.addView(cancleButton);
    }

    private RelativeLayout content() {
        LayoutParams params = new LayoutParams(
                  ruler.getW(80),
                  ruler.getH(40));
        params.addRule(CENTER_IN_PARENT);

        GradientDrawable background = new GradientDrawable();
        background.setShape(GradientDrawable.RECTANGLE);
        background.setCornerRadius(70);
        background.setColor(0xFFFFFFFF);

        RelativeLayout v = new RelativeLayout(getContext());
        v.setId(GenerateViewId.get());
        v.setLayoutParams(params);
        v.setBackground(background);

        return v;
    }

    private TextView sendEmailTextView() {
        LayoutParams params = new LayoutParams(
                  ruler.getW(70),
                  ruler.getH(10));
        params.addRule(CENTER_HORIZONTAL);
        params.setMargins(0,ruler.getH(5),0,0);

        TextView v = new TextView(getContext());
        v.setId(GenerateViewId.get());
        v.setLayoutParams(params);
        v.setText(R.string.point_layout_send);
        v.setGravity(Gravity.CENTER);
        ruler.setAudioFit(v, 25, 5, 1);

        return v;
    }

    private TextView sendPointTextView(View topView) {
        LayoutParams params = new LayoutParams(
                  ruler.getW(70),
                  ruler.getH(10));
        params.addRule(BELOW, topView.getId());
        params.addRule(ALIGN_LEFT, topView.getId());

        TextView v = new TextView(getContext());
        v.setId(GenerateViewId.get());
        v.setLayoutParams(params);
        v.setText(R.string.point_layout_send);
        v.setGravity(Gravity.CENTER);
        ruler.setAudioFit(v, 25, 5, 1);

        return v;
    }

    private Button accussButton(View topView) {
        LayoutParams params = new LayoutParams(
                  ruler.getW(30),
                  ruler.getH(10));
        params.addRule(ALIGN_LEFT, topView.getId());
        params.addRule(BELOW, topView.getId());

        GradientDrawable background = new GradientDrawable();
        background.setShape(GradientDrawable.RECTANGLE);
        background.setCornerRadius(70);
        background.setColor(0xFF036EB8);


        Button v = new Button(getContext());
        v.setLayoutParams(params);
        v.setId(GenerateViewId.get());
        v.setText(R.string.special_register_yes);
        v.setTextColor(0xFFFFFFFF);
        v.setGravity(Gravity.CENTER);
        v.setBackground(background);
        v.setPadding(0,0,0,0);
        ruler.setAudioFit(v,20,8,1);
        return v;
    }

    private Button cancleButton(View topView) {
        LayoutParams params = new LayoutParams(
                  ruler.getW(30),
                  ruler.getH(10));
        params.addRule(ALIGN_RIGHT, topView.getId());
        params.addRule(BELOW, topView.getId());

        GradientDrawable background = new GradientDrawable();
        background.setShape(GradientDrawable.RECTANGLE);
        background.setCornerRadius(70);
        background.setColor(0xFF036EB8);

        Button v = new Button(getContext());
        v.setLayoutParams(params);
        v.setId(GenerateViewId.get());
        v.setText(R.string.special_register_cancel);
        v.setTextColor(0xFFFFFFFF);
        v.setGravity(Gravity.CENTER);
        v.setBackground(background);
        v.setPadding(0,0,0,0);
        ruler.setAudioFit(v,20,8,1);

        return v;
    }
}
