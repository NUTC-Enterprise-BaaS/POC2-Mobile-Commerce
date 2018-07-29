package com.androidlibrary.ui.news;

import android.content.Context;
import android.graphics.Color;
import android.support.percent.PercentRelativeLayout;
import android.support.v7.widget.DefaultItemAnimator;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.support.v7.widget.Toolbar;
import android.view.Gravity;
import android.view.ViewGroup;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.androidlibrary.component.ReturnButton;
import com.androidlibrary.component.decotarion.DividerDecoration;
import com.androidlibrary.core.DpToPx;
import com.androidlibrary.core.GenerateViewId;
import com.androidlibrary.core.Ruler;

/**
 * Created by Gary on 2016/5/24.
 */
public class NewsLayout extends RelativeLayout {
    private Ruler ruler;
    private DpToPx equalizer;
    public RecyclerView list;
    public TextView toolbarTittleTextView;
    public Toolbar toolbar;
    public RelativeLayout backContainer;
    public ReturnButton toolbarBack;
    public LinearLayoutManager manager;

    public NewsLayout(Context context) {
        super(context);
        this.setBackgroundColor(Color.WHITE);
        ruler = new Ruler(getContext());
        equalizer = new DpToPx(getContext());
        toolbar = toolbar();
        toolbarTittleTextView = toolbarTittleTextView();
        backContainer = backContainer();
        toolbarBack = toolbarBack();
        list = list();

        this.addView(toolbar);
        this.addView(list);
        toolbar.addView(toolbarTittleTextView);
        toolbar.addView(backContainer);
        backContainer.addView(toolbarBack);
    }

    private RelativeLayout backContainer() {
        Toolbar.LayoutParams params = new Toolbar.LayoutParams(
                  ruler.getW(20),
                  Toolbar.LayoutParams.MATCH_PARENT);
        params.gravity = Gravity.LEFT;

        RelativeLayout v = new RelativeLayout(getContext());
        v.setId(GenerateViewId.get());
        v.setLayoutParams(params);

        return v;
    }

    protected ReturnButton toolbarBack() {
        PercentRelativeLayout.LayoutParams params = new PercentRelativeLayout.LayoutParams(
                  equalizer.dp(25),
                  equalizer.dp(25));
        params.addRule(CENTER_IN_PARENT);

        ReturnButton v = new ReturnButton(getContext());
        v.setId(GenerateViewId.get());
        v.setLayoutParams(params);
        v.setBackgroundColor(Color.TRANSPARENT);

        return v;
    }

    protected TextView toolbarTittleTextView() {
        Toolbar.LayoutParams params = new Toolbar.LayoutParams(
                  ruler.getW(24),
                  ruler.getH(5));
        params.gravity = Gravity.CENTER;

        TextView v = new TextView(getContext());
        v.setId(GenerateViewId.get());
        v.setLayoutParams(params);
        v.setTextColor(Color.WHITE);
        v.setGravity(Gravity.CENTER);
        ruler.setAudioFit(v, 20, 8, 1);

        return v;
    }

    protected Toolbar toolbar() {
        LayoutParams params = new LayoutParams(
                  ViewGroup.LayoutParams.MATCH_PARENT,
                  ruler.getH(7));

        Toolbar v = new Toolbar(getContext());
        v.setId(GenerateViewId.get());
        v.setLayoutParams(params);
        v.setBackgroundColor(Color.rgb(3, 110, 184));
        v.setContentInsetsAbsolute(0, 0);

        return v;
    }

    private RecyclerView list() {
        LayoutParams params = new LayoutParams(
                  LayoutParams.MATCH_PARENT,
                  LayoutParams.MATCH_PARENT);
        params.addRule(BELOW, toolbar.getId());

        manager = new LinearLayoutManager(getContext());
        manager.setOrientation(LinearLayoutManager.VERTICAL);
        DefaultItemAnimator animator = new DefaultItemAnimator();
        DividerDecoration decoration = new DividerDecoration();
        decoration.setDividerColor(Color.parseColor("#DDDDDD"));
        decoration.setItemMargin(15, 15);

        RecyclerView v = new RecyclerView(getContext());
        v.setId(GenerateViewId.get());
        v.setLayoutParams(params);
        v.setItemAnimator(animator);
        v.setLayoutManager(manager);
        v.setHasFixedSize(true);
        v.addItemDecoration(decoration);
        v.setPadding(20, 20, 20, 20);

        return v;
    }
}
