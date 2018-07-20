package com.androidlibrary.component.item;

import android.content.Context;
import android.graphics.Canvas;
import android.graphics.Color;
import android.graphics.Paint;
import android.graphics.Path;
import android.graphics.PorterDuff;
import android.graphics.PorterDuffXfermode;
import android.graphics.drawable.GradientDrawable;
import android.support.percent.PercentRelativeLayout;
import android.view.Gravity;
import android.view.View;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.android.volley.toolbox.ImageLoader;
import com.android.volley.toolbox.NetworkImageView;
import com.androidlibrary.R;
import com.androidlibrary.core.DpToPx;
import com.androidlibrary.core.GenerateViewId;
import com.androidlibrary.core.Ruler;

/**
 * Created by ameng on 2016/6/4.
 */
public class StoreItem extends PercentRelativeLayout {
    private Ruler ruler;
    private DpToPx equalizer;
    private Paint borderPaint;
    public NetworkImageView storePic;
    private TextView storeName;
    private PercentRelativeLayout baseContainer;
    public Button saveButton;
    public Button callButton;
    public Button driveButton;
    public TextView distanceTextView;

    public StoreItem(Context context) {
        super(context);
        ruler = new Ruler(getContext());
        equalizer = new DpToPx(getContext());

        borderPaint = new Paint();
        borderPaint.setAntiAlias(true);
        borderPaint.setXfermode(new PorterDuffXfermode(PorterDuff.Mode.CLEAR));
        borderPaint.setStyle(Paint.Style.FILL);
        setLayerType(View.LAYER_TYPE_HARDWARE, null);

        storePic = storePicture();
        storeName = storeName(storePic);
        baseContainer = baseContainer(storeName);
        saveButton = saveButton();
        callButton = callButton(saveButton);
        driveButton = driveButton(callButton);
        distanceTextView = distanceTextView(driveButton);

        this.addView(storePic);
        this.addView(storeName);
        this.addView(baseContainer);
        baseContainer.addView(saveButton);
        baseContainer.addView(callButton);
        baseContainer.addView(driveButton);
        baseContainer.addView(distanceTextView);
    }

    public void callClick(View.OnClickListener event, String phone) {
        callButton.setTag(phone);
        callButton.setOnClickListener(event);
    }

    public void setName(String name) {
        storeName.setText(name);
    }

    public void setPic(String uri, ImageLoader imageLoader) {
        storePic.setImageUrl(uri, imageLoader);
    }

    public void setSave(String isSave) {
        if (isSave.equals("1")) {
            saveButton.setBackgroundResource(R.drawable.bn_21);
        } else {
            saveButton.setBackgroundResource(R.drawable.bn_28);
        }
    }

    public void setDistance(String length) {
        distanceTextView.setText(length);
    }

    protected NetworkImageView storePicture() {
        LayoutParams params = new LayoutParams(
                  ruler.getW(48),
                  ruler.getW(25.3));

        NetworkImageView v = new NetworkImageView(getContext());
        v.setId(GenerateViewId.get());
        v.setLayoutParams(params);
        v.setScaleType(ImageView.ScaleType.FIT_XY);

        return v;
    }

    protected TextView storeName(View topView) {
        LayoutParams params = new LayoutParams(
                  ruler.getW(48),
                  ruler.getH(4));
        params.addRule(ALIGN_BOTTOM, topView.getId());

        TextView v = new TextView(getContext());
        v.setId(GenerateViewId.get());
        v.setLayoutParams(params);
        v.setBackgroundColor(Color.parseColor("#CCFFFFFF"));
        ruler.setAudioFit(v, 16, 8, 1);
        v.setGravity(Gravity.LEFT);
        v.setPadding(10, 0, 0, 0);
        return v;
    }

    protected PercentRelativeLayout baseContainer(View topView) {
        PercentRelativeLayout.LayoutParams params = new PercentRelativeLayout.LayoutParams(
                  ruler.getW(48),
                  ruler.getW(10));
        params.addRule(BELOW, topView.getId());

        PercentRelativeLayout v = new PercentRelativeLayout(getContext());
        v.setId(GenerateViewId.get());
        v.setLayoutParams(params);
        v.setHorizontalGravity(LinearLayout.HORIZONTAL);
        GradientDrawable drawable = new GradientDrawable(
                  GradientDrawable.Orientation.LEFT_RIGHT, new int[]{0xFF036EB7, 0xFF036EB7});
        drawable.setGradientType(GradientDrawable.LINEAR_GRADIENT);
        drawable.setCornerRadii(new float[]{0, 0, 0, 0, 25, 25, 25, 25});
        v.setBackground(drawable);

        return v;
    }

    protected Button saveButton() {
        PercentRelativeLayout.LayoutParams params = new PercentRelativeLayout.LayoutParams(
                  ruler.getW(6),
                  ruler.getW(6));
        params.addRule(CENTER_VERTICAL);
        params.leftMargin = ruler.getW(2);

        Button v = new Button(getContext());
        v.setId(GenerateViewId.get());
        v.setLayoutParams(params);
        v.setBackgroundResource(R.drawable.bn_28);

        return v;
    }

    protected Button callButton(View leftVIew) {
        PercentRelativeLayout.LayoutParams params = new PercentRelativeLayout.LayoutParams(
                  ruler.getW(6),
                  ruler.getW(6.5));
        params.addRule(RIGHT_OF, leftVIew.getId());
        params.addRule(CENTER_VERTICAL);
        params.leftMargin = ruler.getW(3.5);

        Button v = new Button(getContext());
        v.setId(GenerateViewId.get());
        v.setLayoutParams(params);
        v.setBackgroundResource(R.drawable.bn_22);

        return v;
    }

    protected Button driveButton(View leftVIew) {
        PercentRelativeLayout.LayoutParams params = new PercentRelativeLayout.LayoutParams(
                  ruler.getW(6),
                  ruler.getW(6));
        params.addRule(RIGHT_OF, leftVIew.getId());
        params.addRule(CENTER_VERTICAL);
        params.leftMargin = ruler.getW(3.5);

        Button v = new Button(getContext());
        v.setId(GenerateViewId.get());
        v.setLayoutParams(params);
        v.setBackgroundResource(R.drawable.new_b_x8);

        return v;
    }

    protected TextView distanceTextView(View leftVIew) {
        PercentRelativeLayout.LayoutParams params = new PercentRelativeLayout.LayoutParams(
                  ruler.getW(17),
                  ruler.getH(8));
        params.topMargin = 0;
        params.addRule(RIGHT_OF, leftVIew.getId());
        params.addRule(CENTER_VERTICAL);
        params.leftMargin = ruler.getW(1);

        TextView v = new TextView(getContext());
        v.setId(GenerateViewId.get());
        v.setLayoutParams(params);
        v.setTextColor(Color.WHITE);
        v.setGravity(Gravity.CENTER);
        ruler.setAudioFit(v, 16, 8, 1);

        return v;
    }

    @Override
    protected void dispatchDraw(Canvas canvas) {
        super.dispatchDraw(canvas);
        float width = ((float) canvas.getWidth());
        float height = ((float) canvas.getHeight());
        float radious = width * 0.1f;

        Path outPath = new Path();
        outPath.moveTo(radious, 0);
        outPath.lineTo(0, 0);
        outPath.lineTo(0, radious);
        outPath.quadTo(0, 0, radious, 0);
        outPath.close();

        outPath.moveTo(width - radious, 0);
        outPath.lineTo(width, 0);
        outPath.lineTo(width, radious);
        outPath.quadTo(width, 0, width - radious, 0);
        outPath.close();
        canvas.drawPath(outPath, borderPaint);
    }

    public void setSaveClick(OnClickListener click) {
        saveButton.setOnClickListener(click);
    }

    public void setMapGpsListener(OnClickListener event) {
        driveButton.setOnClickListener(event);
    }
}
