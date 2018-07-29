package com.poc2.component.button;

import android.content.Context;
import android.graphics.Canvas;
import android.graphics.Paint;
import android.widget.RelativeLayout;

/**
 * Created by 依杰 on 2016/5/13.
 */
public class ReturnButton extends RelativeLayout {
    private Paint paint;
    private float width;
    private float height;

    public ReturnButton(Context context) {
        super(context);
        paint = new Paint();
        paint.setAntiAlias(true);
        paint.setColor(0xFFFFFFFF);
        paint.setStrokeWidth(4);
    }

    @Override
    protected void onDraw(Canvas canvas) {
        height = canvas.getHeight();
        width = canvas.getWidth();

        canvas.drawLine(width / 2, 0, 0, height / 2, paint);
        canvas.drawLine(0, height / 2, width / 2, height, paint);

        super.onDraw(canvas);
    }
}
