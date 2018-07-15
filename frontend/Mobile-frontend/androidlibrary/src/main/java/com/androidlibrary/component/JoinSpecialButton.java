package com.androidlibrary.component;

import android.content.Context;
import android.graphics.Color;
import android.view.Gravity;
import android.view.View;
import android.widget.ImageView;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.androidlibrary.core.DpToPx;
import com.androidlibrary.core.GenerateViewId;
import com.androidlibrary.core.Ruler;

/**
 * Created by Gary on 2016/5/12.
 */
public class JoinSpecialButton extends RelativeLayout {
    private Ruler ruler;
    private DpToPx equalizer;
    public RelativeLayout container;
    public TextView leftText;
    public TextView rightText;
    private ImageView icon;

    public JoinSpecialButton(Context context) {
        super(context);
        ruler = new Ruler(getContext());
        equalizer = new DpToPx(getContext());
        container = container();
        icon = icon();
        leftText = leftText(icon);
        rightText = rightText();


        this.addView(container);
        container.addView(icon);
        container.addView(leftText);
        container.addView(rightText);
    }

    private ImageView icon() {
        LayoutParams params = new LayoutParams(
                  ruler.getH(7),
                  ruler.getH(7));
        params.addRule(CENTER_VERTICAL);

        ImageView v = new ImageView(getContext());
        v.setId(GenerateViewId.get());
        v.setLayoutParams(params);
        v.setScaleType(ImageView.ScaleType.FIT_XY);

        return v;
    }

    private RelativeLayout container() {
        LayoutParams params = new LayoutParams(
                  LayoutParams.WRAP_CONTENT,
                  ruler.getH(11));

        RelativeLayout v = new RelativeLayout(getContext());
        v.setId(GenerateViewId.get());
        v.setLayoutParams(params);
        return v;
    }

    private TextView leftText(View leftView) {
        LayoutParams params = new LayoutParams(
                ruler.getW(18),
                ruler.getH(4));
        params.topMargin = ruler.getH(5);
        params.addRule(RIGHT_OF,leftView.getId());

        TextView v = new TextView(getContext());
        v.setId(GenerateViewId.get());
        v.setLayoutParams(params);
        v.setTextColor(Color.WHITE);
        ruler.setAudioFit(v, 16, 8, 1);
        v.setGravity(Gravity.CENTER);
        return v;
    }

    protected TextView rightText() {
        LayoutParams params = new LayoutParams(
                ruler.getW(23),
                ruler.getH(5));
        params.addRule(RIGHT_OF, leftText.getId());
        params.addRule(ALIGN_BOTTOM, leftText.getId());
        params.bottomMargin = ruler.getH(0.5);

        TextView v = new TextView(getContext());
        v.setId(GenerateViewId.get());
        v.setLayoutParams(params);
        v.setTextColor(Color.WHITE);
        ruler.setAudioFit(v, 22, 8, 1);
        v.setGravity(Gravity.LEFT);
        return v;
    }

    public void setImage(int resource) {
        icon.setImageResource(resource);
    }
}
