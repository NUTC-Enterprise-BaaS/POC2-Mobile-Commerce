package com.androidlibrary.component.decoration;

import android.graphics.Canvas;
import android.graphics.Color;
import android.graphics.Paint;
import android.graphics.Rect;
import android.support.v7.widget.RecyclerView;
import android.view.View;

public class DividerDecoration extends RecyclerView.ItemDecoration {
    private Paint paint;
    private int dividerHeight;
    private int itemMarginTop;
    private int itemMarginBottom;
    private int dividerLeftMargin;
    private int dividerRightMargin;

    public DividerDecoration() {
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

    public void setDividerMargin(int left, int right) {
        dividerLeftMargin = left;
        dividerRightMargin = right;
    }

    public void setDividerHeight(int height) {
        this.dividerHeight = height;
        paint.setStrokeWidth(dividerHeight);
    }

    public void setItemMargin(int top, int bottom) {
        this.itemMarginTop = top;
        this.itemMarginBottom = bottom;
    }

    public void setDividerColor(int color) {
        paint.setColor(color);
    }

    @Override
    public void getItemOffsets(Rect outRect, View view, RecyclerView parent, RecyclerView.State state) {
        super.getItemOffsets(outRect, view, parent, state);

        outRect.top = itemMarginTop;
        outRect.bottom = itemMarginBottom;
    }

    @Override
    public void onDraw(Canvas c, RecyclerView parent, RecyclerView.State state) {
        super.onDraw(c, parent, state);

        int left = dividerLeftMargin;
        int right = c.getWidth() - dividerRightMargin;

        for (int i = 0; i < parent.getChildCount(); i++) {
            View child = parent.getChildAt(i);
            int childBottom = child.getBottom();

            int bottom = childBottom + itemMarginBottom + (dividerHeight / 2);
            int top = bottom - dividerHeight;
            c.drawRect(left, top, right, bottom, paint);
        }
    }

    @Override
    public void onDrawOver(Canvas c, RecyclerView parent, RecyclerView.State state) {
        super.onDrawOver(c, parent, state);
    }
}