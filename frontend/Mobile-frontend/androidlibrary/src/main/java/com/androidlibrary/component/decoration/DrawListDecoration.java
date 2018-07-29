package com.androidlibrary.component.decoration;

import android.graphics.Color;
import android.graphics.Paint;
import android.graphics.Rect;
import android.support.v7.widget.RecyclerView;
import android.view.View;

/**
 * Created by ameng on 2016/6/30.
 */
public class DrawListDecoration extends RecyclerView.ItemDecoration{
    private Paint paint;
    private int dividerHeight;
    private int itemMarginTop;
    private int itemMarginBottom;
    private int dividerLeftMargin;
    private int dividerRightMargin;

    public DrawListDecoration() {
        super();
        dividerHeight = 2;
        itemMarginTop = 9;
        itemMarginBottom = 9;
        dividerLeftMargin = 0;
        dividerRightMargin = 0;

        paint = new Paint(Paint.ANTI_ALIAS_FLAG);
        paint.setStyle(Paint.Style.FILL);
        paint.setColor(Color.GRAY);
    }

    @Override
    public void getItemOffsets(Rect outRect, View view, RecyclerView parent, RecyclerView.State state) {
        super.getItemOffsets(outRect, view, parent, state);

        outRect.top = itemMarginTop;
        outRect.bottom = itemMarginBottom;
    }
}
