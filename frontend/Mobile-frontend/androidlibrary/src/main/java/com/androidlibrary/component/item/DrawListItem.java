package com.androidlibrary.component.item;

import android.content.Context;
import android.graphics.Color;
import android.view.Gravity;
import android.view.View;
import android.widget.ImageView;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.androidlibrary.core.GenerateViewId;
import com.androidlibrary.core.Ruler;

/**
 * Created by ameng on 2016/6/30.
 */
public class DrawListItem extends RelativeLayout {
    private Ruler ruler;
    public TextView itemName;
    public ImageView itemIcon;
    private View split;
    public RelativeLayout buttonContainer;


    public DrawListItem(Context context) {
        super(context);
        ruler = new Ruler(getContext());
        buttonContainer = buttonContainer();
        itemIcon = itemIcon();
        itemName = itemName();
        split = split(itemIcon);

        this.addView(buttonContainer);
        buttonContainer.addView(itemName);
        buttonContainer.addView(itemIcon);
        split.setVisibility(INVISIBLE);
        this.addView(split);

    }

    protected RelativeLayout buttonContainer() {
        RelativeLayout.LayoutParams params = new RelativeLayout.LayoutParams(
                ruler.getW(50),
                ruler.getH(8));

        RelativeLayout v = new RelativeLayout(getContext());
        v.setId(GenerateViewId.get());
        v.setLayoutParams(params);
        return v;
    }

    protected TextView title() {
        LayoutParams params = new LayoutParams(
                ruler.getW(30),
                ruler.getH(5));
        params.addRule(CENTER_HORIZONTAL);
        params.topMargin = ruler.getH(13);

        TextView v = new TextView(getContext());
        v.setId(GenerateViewId.get());
        v.setLayoutParams(params);
        v.setTextColor(Color.parseColor("#F2F2F2"));
        ruler.setAudioFit(v, 20, 8, 1);
        v.setGravity(Gravity.CENTER);
        return v;
    }

    protected TextView itemName() {
        LayoutParams params = new LayoutParams(
                ruler.getW(24),
                ruler.getH(5));
        params.addRule(ALIGN_PARENT_RIGHT);
        params.addRule(CENTER_VERTICAL);
        params.rightMargin = ruler.getW(9);

        TextView v = new TextView(getContext());
        v.setId(GenerateViewId.get());
        v.setLayoutParams(params);
        v.setTextColor(Color.parseColor("#666666"));
        ruler.setAudioFit(v, 20, 8, 1);
        v.setGravity(Gravity.CENTER);
        return v;
    }

    protected ImageView itemIcon() {
        LayoutParams params = new LayoutParams(
                ruler.getW(9),
                ruler.getH(6));
        params.addRule(ALIGN_PARENT_LEFT);
        params.addRule(CENTER_VERTICAL);
        params.leftMargin = ruler.getW(5);

        ImageView v = new ImageView(getContext());
        v.setId(GenerateViewId.get());
        v.setLayoutParams(params);
        v.setScaleType(ImageView.ScaleType.FIT_XY);
        return v;
    }

    protected View split(View topView) {
        LayoutParams params = new LayoutParams(
                ruler.getW(31),
                ruler.getH(0.15));
        params.addRule(BELOW, topView.getId());
        params.addRule(CENTER_HORIZONTAL);
        params.topMargin = ruler.getH(1.8);

        View v = new View(getContext());
        v.setId(GenerateViewId.get());
        v.setLayoutParams(params);
        v.setBackgroundColor(Color.GRAY);
        return v;
    }

    public void addSplit() {
        split.setVisibility(INVISIBLE);
    }

    public void setItemBackground(int color) {
        this.buttonContainer.setBackgroundColor(color);
    }

}
