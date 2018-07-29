package com.androidlibrary.component;

import android.content.Context;
import android.graphics.Color;
import android.view.Gravity;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.androidlibrary.core.DpToPx;
import com.androidlibrary.core.GenerateViewId;
import com.androidlibrary.core.Ruler;

/**
 * Created by Gary on 2016/5/12.
 */
public class IconButtonSmall extends RelativeLayout {
    private Ruler ruler;
    private DpToPx equalizer;
    public RelativeLayout container;
    public ImageView icon;
    public TextView text;

    public IconButtonSmall(Context context) {
        super(context);
        ruler = new Ruler(getContext());
        equalizer = new DpToPx(getContext());
        container = container();
        icon = icon();
        text = text();

        this.addView(container);
        container.addView(icon);
        container.addView(text);
    }

    private RelativeLayout container() {
        LayoutParams params = new LayoutParams(
                  ruler.getW(44),
                  ruler.getH(18));

        RelativeLayout v = new RelativeLayout(getContext());
        v.setId(GenerateViewId.get());
        v.setLayoutParams(params);
        return v;
    }

    private ImageView icon() {
        LayoutParams params = new LayoutParams(
                  ruler.getW(15),
                  ruler.getW(15));
        params.addRule(CENTER_IN_PARENT);

        ImageView v = new ImageView(getContext());
        v.setId(GenerateViewId.get());
        v.setLayoutParams(params);
        v.setScaleType(ImageView.ScaleType.FIT_XY);
        return v;
    }

    protected TextView text() {
        LayoutParams params = new LayoutParams(
                  ruler.getW(25),
                  ruler.getH(8));
        params.addRule(CENTER_HORIZONTAL);
        params.addRule(BELOW, icon.getId());
        params.topMargin = ruler.getH(1);

        TextView v = new TextView(getContext());
        v.setId(GenerateViewId.get());
        v.setLayoutParams(params);
        v.setTextColor(Color.WHITE);
        ruler.setAudioFit(v, 18, 12, 1);
        v.setGravity(Gravity.CENTER);
        return v;
    }

    public void setImageSize(int width, int height) {
        ViewGroup.LayoutParams params = icon.getLayoutParams();
        params.width = width;
        params.height = height;
        icon.setLayoutParams(params);
    }

    public void setImage(int resource) {
        icon.setImageResource(resource);
    }

    public void setText(int resource) {
        text.setText(resource);
    }

}
