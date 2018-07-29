package com.androidlibrary.component;

import android.content.Context;
import android.graphics.Color;
import android.view.View;
import android.view.ViewGroup;
import android.widget.LinearLayout;

import com.androidlibrary.core.DpToPx;
import com.androidlibrary.core.GenerateViewId;
import com.androidlibrary.core.Ruler;

/**
 * Created by Gary on 2016/5/26.
 */
public class PointListBackgroundView extends LinearLayout {
    private Ruler ruler;
    private DpToPx equalizer;
    public View oddView;
    public View evenView;
    public View oddView2;
    public View evenView2;
    public View oddView3;
    public View evenView3;
    public View oddView4;
    public View evenView4;
    public View oddView5;
    public View evenView5;
    public View oddView6;
    public View evenView6;

    public PointListBackgroundView(Context context) {
        super(context);
        ruler = new Ruler(getContext());
        equalizer = new DpToPx(getContext());
        self();
        oddView = oddView();
        evenView = evenView();
        oddView2 = oddView();
        evenView2 = evenView();
        oddView3 = oddView();
        evenView3 = evenView();
        oddView4 = oddView();
        evenView4 = evenView();
        oddView5 = oddView();
        evenView5 = evenView();
        oddView6 = oddView();
        evenView6 = evenView();

        this.addView(oddView);
        this.addView(evenView);
        this.addView(oddView2);
        this.addView(evenView2);
        this.addView(oddView3);
        this.addView(evenView3);
        this.addView(oddView4);
        this.addView(evenView4);
        this.addView(oddView5);
        this.addView(evenView5);
        this.addView(oddView6);
        this.addView(evenView6);
    }

    private View evenView() {
        LayoutParams params = new LayoutParams(
                LayoutParams.MATCH_PARENT,
                ruler.getH(6));

        View v = new View(getContext());
        v.setId(GenerateViewId.get());
        v.setLayoutParams(params);
        v.setBackgroundColor(Color.WHITE);
        return v;
    }

    private View oddView() {
        LayoutParams params = new LayoutParams(
                LayoutParams.MATCH_PARENT,
                ruler.getH(6));

        View v = new View(getContext());
        v.setId(GenerateViewId.get());
        v.setLayoutParams(params);
        v.setBackgroundColor(Color.parseColor("#D2CCDF"));
        return v;
    }

    private void self() {
        LayoutParams params = new LayoutParams(
                ViewGroup.LayoutParams.MATCH_PARENT,
                ViewGroup.LayoutParams.MATCH_PARENT);
        this.setOrientation(VERTICAL);
        this.setLayoutParams(params);
    }
}
