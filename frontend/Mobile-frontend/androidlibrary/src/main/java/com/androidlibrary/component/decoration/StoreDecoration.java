package com.androidlibrary.component.decoration;

import android.graphics.Rect;
import android.support.v7.widget.RecyclerView;
import android.view.View;

/**
 * Created by ameng on 2016/6/4.
 */
public class StoreDecoration extends RecyclerView.ItemDecoration {
    private int dividerHeight;
    private int itemMarginTop;
    private int itemMarginBottom;
    private int itemMarginLeft;
    private int itemMarginRight;
    private int dividerLeftMargin;
    private int dividerRightMargin;

    public StoreDecoration() {
        itemMarginTop = 10;
        itemMarginBottom = 10;
        itemMarginLeft = 15;
        itemMarginRight = 15;
        dividerLeftMargin = 0;
        dividerRightMargin = 0;
    }

    public void setItemMargin(int left, int top, int right, int bottom) {
        this.itemMarginLeft = left;
        this.itemMarginTop = top;
        this.itemMarginRight = right;
        this.itemMarginBottom = bottom;
    }

    @Override
    public void getItemOffsets(Rect outRect, View view, RecyclerView parent, RecyclerView.State state) {
        outRect.left = itemMarginLeft;
        outRect.top = itemMarginTop;
        outRect.right = itemMarginRight;
        outRect.bottom = itemMarginBottom;
    }
}
