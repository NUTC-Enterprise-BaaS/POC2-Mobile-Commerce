package com.androidlibrary.ui.news;

import android.animation.AnimatorSet;
import android.animation.ObjectAnimator;
import android.content.Context;
import android.support.v7.widget.RecyclerView;
import android.view.ViewGroup;
import android.view.animation.OvershootInterpolator;

import com.androidlibrary.ui.news.NewsItem;

import java.util.ArrayList;

/**
 * Created by Gary on 2016/5/25.
 */
public class GeneralNewsAdapter extends RecyclerView.Adapter {
    private Context context;
    private DataStructure data;


    public GeneralNewsAdapter(Context context) {
        this.context = context;
        this.data = new DataStructure();

    }

    @Override
    public RecyclerView.ViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        NewsItem item = new NewsItem(context);
        return new RecyclerView.ViewHolder(item) {
        };
    }


    @Override
    public void onBindViewHolder(RecyclerView.ViewHolder holder, int position) {
        NewsItem item = (NewsItem) holder.itemView;
        item.setTitle(data.titleGroup.get(position));
        item.setDate(data.dateGroup.get(position));
        item.setTag(data.idGroup.get(position));

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
        return data.idGroup.size();
    }

    public static class DataStructure {
        public ArrayList<Integer> idGroup = new ArrayList<>();
        public ArrayList<String> titleGroup = new ArrayList<>();
        public ArrayList<Long> dateGroup = new ArrayList<>();

    }

    public void setData(DataStructure data) {
        this.data = data;
        notifyDataSetChanged();
    }

}