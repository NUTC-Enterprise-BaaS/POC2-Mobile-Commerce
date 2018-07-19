package com.androidlibrary.component.adapter;

import android.animation.AnimatorSet;
import android.animation.ObjectAnimator;
import android.content.Context;
import android.support.v7.widget.RecyclerView;
import android.view.View;
import android.view.ViewGroup;
import android.view.animation.OvershootInterpolator;

import com.androidlibrary.component.item.GCMItem;
import com.androidlibrary.module.sql.GCMTable;

import java.util.ArrayList;

/**
 * Created by ameng on 7/7/16.
 */
public class GcmListAdapter extends RecyclerView.Adapter {
    private GCMTable gcmTable;
    private Context context;

    public GcmListAdapter(Context context) {
        this.context = context;
        gcmTable = new GCMTable(context);
    }

    @Override
    public RecyclerView.ViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        GCMItem item = new GCMItem(context);
        return new RecyclerView.ViewHolder(item) {
        };
    }

    @Override
    public void onBindViewHolder(RecyclerView.ViewHolder holder, int position) {
        GCMItem item = (GCMItem) holder.itemView;
        ArrayList<String> dataList = gcmTable.getColumesItem(position);
        item.setStorePromotions(dataList.get(0));
        item.setTime(dataList.get(1));
        item.setTag(dataList);
        item.setOnClickListener(clickEvent);

        ObjectAnimator alpha = ObjectAnimator.ofFloat(holder.itemView, "alpha", 0F, 1F);
        ObjectAnimator scaleX = ObjectAnimator.ofFloat(holder.itemView, "scaleX", 0.5F, 1F);
        ObjectAnimator scaleY = ObjectAnimator.ofFloat(holder.itemView, "scaleY", 0.5F, 1F);

        AnimatorSet animatorSet = new AnimatorSet();
        animatorSet.setDuration(500);
        animatorSet.setInterpolator(new OvershootInterpolator());
        animatorSet.play(alpha).with(scaleX).with(scaleY);
        animatorSet.start();
    }

    @Override
    public int getItemCount() {
        return gcmTable.getTableCount();
    }

    private View.OnClickListener clickEvent;

    public void setClickEvent(View.OnClickListener clickEvent) {
        this.clickEvent = clickEvent;
    }
}
