package com.herbhousesgobuyother.contrube.core;

import android.annotation.TargetApi;
import android.content.Context;
import android.content.res.Configuration;
import android.content.res.Resources;
import android.os.Build;
import android.util.DisplayMetrics;
import android.view.WindowManager;

/**
 * Created by ameng on 9/8/16.
 */
public class Ruler {
    private int width;
    private int height;
    private DisplayMetrics displayMetrics;
    private WindowManager mManager;
    private Resources resources;

    @TargetApi(Build.VERSION_CODES.JELLY_BEAN_MR1)
    public Ruler(Context context) {
        displayMetrics = new DisplayMetrics();
        mManager= (WindowManager) context.getSystemService(Context.WINDOW_SERVICE);
        mManager.getDefaultDisplay().getMetrics(displayMetrics);
        resources = context.getResources();
        width = displayMetrics.widthPixels;
        height = displayMetrics.heightPixels;

//        View decorView = ((Activity) mContext).getWindow().getDecorView();
        int uiOptions = mManager.getDefaultDisplay().getFlags();
        /**
         * 0: NO FULLSCREEN and HAVE NAVIGATION
         * 2: NO FULLSCREEN and NO NAVIGATION
         * 6: HAVE FULLSCREEN and NO NAVIGATION
         * 4: HAVE FULLSCREEN and HAVE NAVIGATION
         * */
        if (isHorizontalOrientation()) {

            if (uiOptions == 0) {
                height = height - getStatusBarHeight();
                width = width + getNavigationBarHeight();
            } else if (uiOptions == 2) {
                height = height - getStatusBarHeight();
            } else if (uiOptions == 6) {
                height = height;
                width = width + getNavigationBarHeight();
            } else if (uiOptions == 4) {
                height = height;
            }
        } else {
            if (uiOptions == 0) {
                height = height - getStatusBarHeight();
            } else if (uiOptions == 2) {
                height = height - getStatusBarHeight() + getNavigationBarHeight();
            } else if (uiOptions == 6) {
                height = height + getNavigationBarHeight();
            } else if (uiOptions == 4) {
                height = height;
            }
        }
    }

    public int setWidth(double Per) {
        if (Per == -1)
            return -1;
        else if (Per == -2)
            return -2;
        return (int) ((Per > 100.0) ? width : ((width * Per) / 100));
    }

    public int setHeight(double Per) {
        if (Per == -1)
            return -1;
        else if (Per == -2)
            return -2;
        return (int) ((Per > 100.0) ? height : ((height * Per) / 100));
    }

    private int getNavigationBarHeight() {
        int resourceId = resources.getIdentifier("navigation_bar_height", "dimen", "android");
        if (resourceId > 0) {
            return resources.getDimensionPixelSize(resourceId);
        }
        return 0;
    }

    public int getHeight() {
        return height;
    }

    public int getWidth() {
        return width;
    }

    private int getStatusBarHeight() {
        int resourceId = resources.getIdentifier("status_bar_height", "dimen", "android");
        if (resourceId > 0) {
            return resources.getDimensionPixelSize(resourceId);
        }
        return 0;
    }

    private boolean isHorizontalOrientation() {
        Configuration config = resources.getConfiguration();
        //Horizontal screen
        if (config.orientation == Configuration.ORIENTATION_LANDSCAPE) {
            return true;
        }
        return false;
    }
}