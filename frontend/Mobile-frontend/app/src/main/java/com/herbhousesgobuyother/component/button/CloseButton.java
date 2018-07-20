package com.herbhousesgobuyother.component.button;

import android.content.Context;
import android.graphics.Canvas;
import android.graphics.Paint;
import android.widget.RelativeLayout;

/**
 * Created by 依杰 on 2016/6/29.
 */
public class CloseButton extends RelativeLayout {
    private Paint paint;
    private float width;
    private float height;

    public CloseButton(Context context) {
        super(context);
        paint = new Paint();
        paint.setAntiAlias(true);
        paint.setColor(0xFF000000);
        paint.setStrokeWidth(6);
    }

    @Override
    protected void onDraw(Canvas canvas) {
        height = canvas.getHeight();
        width = canvas.getWidth();

        canvas.drawLine(width, 0, 0, height, paint);
        canvas.drawLine(width, height, 0, 0, paint);

        super.onDraw(canvas);
    }
}

