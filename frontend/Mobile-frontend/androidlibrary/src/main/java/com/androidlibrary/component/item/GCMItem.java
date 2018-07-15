package com.androidlibrary.component.item;

import android.content.Context;
import android.graphics.Color;
import android.text.TextUtils;
import android.view.Gravity;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.androidlibrary.core.GenerateViewId;
import com.androidlibrary.core.Ruler;

/**
 * Created by ameng on 7/7/16.
 */
public class GCMItem extends RelativeLayout {
    private Ruler ruler;
    private ImageView latestImageView;
    private TextView storePromotions;
    public TextView storeTime;

    public GCMItem(Context context) {
        super(context);
        ruler = new Ruler(getContext());
        latestImageView = latestImageView();
        storePromotions = storePromotions(latestImageView);
        storeTime = storeTime();

        this.addView(latestImageView);
        this.addView(storePromotions);
        this.addView(storeTime);
    }

    protected ImageView latestImageView() {
        LayoutParams params = new LayoutParams(
                ruler.getW(8),
                ruler.getW(5));
        params.leftMargin = ruler.getW(1);

        ImageView v = new ImageView(getContext());
        v.setId(GenerateViewId.get());
//        v.setBackground(drawable);
        v.setScaleType(ImageView.ScaleType.FIT_XY);
        return v;
    }

    protected TextView storePromotions(View leftView) {
        LayoutParams params = new LayoutParams(
                ruler.getW(40),
                ruler.getH(6.5));
        params.addRule(RIGHT_OF, leftView.getId());
        params.addRule(CENTER_VERTICAL);
        params.leftMargin = ruler.getW(3);

        TextView v = new TextView(getContext());
        v.setId(GenerateViewId.get());
        v.setLayoutParams(params);
        v.setTextColor(Color.GRAY);
        ruler.setAudioFit(v,20,16,1);
        v.setEllipsize(TextUtils.TruncateAt.END);
        v.setSingleLine(true);
        v.setGravity(Gravity.CENTER_VERTICAL);
        return v;
    }

    protected TextView storeTime() {
        LayoutParams params = new LayoutParams(
                ViewGroup.LayoutParams.WRAP_CONTENT,
                ViewGroup.LayoutParams.WRAP_CONTENT);
        params.addRule(ALIGN_PARENT_RIGHT);
        params.addRule(CENTER_VERTICAL);
        params.rightMargin = ruler.getW(1);

        TextView v = new TextView(getContext());
        v.setId(GenerateViewId.get());
        v.setLayoutParams(params);
        v.setTextColor(Color.GRAY);
        ruler.setAudioFit(v,16,8,1);

        return v;
    }

    public void setStorePromotions(String text) {
        this.storePromotions.setText(text);
    }

    public void setTime(String time) {
        this.storeTime.setText(time);
    }
}
