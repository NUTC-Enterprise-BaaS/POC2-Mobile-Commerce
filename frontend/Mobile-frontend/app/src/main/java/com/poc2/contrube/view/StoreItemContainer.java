package com.poc2.contrube.view;

import android.content.Context;
import android.graphics.Canvas;
import android.graphics.Paint;
import android.graphics.Path;
import android.graphics.PorterDuff;
import android.graphics.PorterDuffXfermode;
import android.util.AttributeSet;
import android.view.View;
import android.widget.RelativeLayout;

/**
 * Created by Gary on 2016/11/3.
 */

public class StoreItemContainer extends RelativeLayout {
    private Paint borderPaint;


    public StoreItemContainer(Context context, AttributeSet attrs) {
        super(context, attrs);
        borderPaint = new Paint();
        borderPaint.setAntiAlias(true);
        borderPaint.setXfermode(new PorterDuffXfermode(PorterDuff.Mode.CLEAR));
        borderPaint.setStyle(Paint.Style.FILL);
        setLayerType(View.LAYER_TYPE_HARDWARE, null);

    }

    public StoreItemContainer(Context context) {
        super(context);
        borderPaint = new Paint();
        borderPaint.setAntiAlias(true);
        borderPaint.setXfermode(new PorterDuffXfermode(PorterDuff.Mode.CLEAR));
        borderPaint.setStyle(Paint.Style.FILL);
        setLayerType(View.LAYER_TYPE_HARDWARE, null);

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
}
