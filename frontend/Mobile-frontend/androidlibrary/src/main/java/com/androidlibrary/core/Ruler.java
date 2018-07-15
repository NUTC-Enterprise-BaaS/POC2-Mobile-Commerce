package com.androidlibrary.core;

import android.app.Activity;
import android.content.Context;
import android.util.DisplayMetrics;
import android.widget.TextView;

import me.grantland.widget.AutofitHelper;

public class Ruler {
    private int width;
    private int height;
    private DisplayMetrics dm;

    public Ruler(Context Context) {
        dm = new DisplayMetrics();
        ((Activity) Context).getWindowManager().getDefaultDisplay().getMetrics(dm);
        width = dm.widthPixels;
        height = dm.heightPixels;
    }

    public int getW(double Per) {
        if (Per == -1)
            return -1;
        else if (Per == -2)
            return -2;
        return (int) ((Per > 100.0) ? width : ((width * Per) / 100));
    }

    public int getH(double Per) {
        if (Per == -1)
            return -1;
        else if (Per == -2)
            return -2;
        return (int) ((Per > 100.0) ? height : ((height * Per) / 100));
    }

    public static void setAudioFit(TextView beCreate, int max, int min, int lines) {
        AutofitHelper helper = AutofitHelper.create(beCreate);
        helper.setMaxTextSize(max);
        helper.setMinTextSize(min);
        helper.setMaxLines(lines);
    }

    public static void setAudioFit(TextView beCreate, int lines) {
        AutofitHelper helper = AutofitHelper.create(beCreate);
        helper.setMaxTextSize(100);
        helper.setMinTextSize(1);
        helper.setMaxLines(lines);
    }
}