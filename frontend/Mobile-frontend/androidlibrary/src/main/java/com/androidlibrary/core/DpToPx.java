package com.androidlibrary.core;

import android.app.Activity;
import android.content.Context;
import android.util.DisplayMetrics;

/**
 * Created by chriske on 2016/1/18.
 */
public class DpToPx {
    private Context context;

    public DpToPx(Context context) {
        this.context = context;
    }

    public int dp(int dp) {
        DisplayMetrics metrics = new DisplayMetrics();
        ((Activity) context).getWindowManager().getDefaultDisplay().getMetrics(metrics);
        float multiple = metrics.densityDpi / 160;
        return (int) (dp * multiple);
    }
}
