package com.androidlibrary.component.inputbox;

import android.content.Context;
import android.graphics.Canvas;
import android.graphics.Color;
import android.graphics.Paint;
import android.graphics.RectF;

import com.androidlibrary.component.AutoCleanFocusEditText;

/**
 * Created by ameng on 2016/6/6.
 */
public class SearchInputBox extends AutoCleanFocusEditText {
    Paint paint;

    RectF outSide = new RectF(80, 260, 200, 300);
    public SearchInputBox(Context context) {
        super(context);
        paint = new Paint();
        paint.setStyle(Paint.Style.STROKE);
        paint.setAntiAlias(true);
        paint.setStrokeWidth(7);
        paint.setColor(Color.parseColor("#d8d8d8"));
    }

    @Override
    protected void onDraw(Canvas canvas) {
        super.onDraw(canvas);
        float width = ((float)canvas.getWidth())/100f;
        float height = ((float)canvas.getHeight())/100f;
        outSide = new RectF(width*1, height*1, width*98,height*95);
        canvas.drawRoundRect(outSide, 10, 10,paint);
    }
}
