package com.herbhousesgobuyother.contrube.model.adapter;

import android.animation.AnimatorSet;
import android.animation.ObjectAnimator;
import android.content.Context;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.view.animation.OvershootInterpolator;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.herbhousesgobuyother.R;

import java.util.ArrayList;
import java.util.Calendar;

/**
 * Created by Gary on 2016/11/2.
 */

public class NewsAdapt extends RecyclerView.Adapter<NewsAdapt.MyHolder> {
    private LayoutInflater layoutInflater;
    private Context context;
    private DataStructure data;
    private View.OnClickListener clickEvent;

    public NewsAdapt(Context context) {
        super();
        this.context = context;
        layoutInflater = LayoutInflater.from(context);
        data = new DataStructure();
    }

    @Override
    public MyHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        View view = layoutInflater.inflate(R.layout.fragment_news_item, parent, false);
        MyHolder myHolder = new MyHolder(view);
        return myHolder;
    }

    @Override
    public void onBindViewHolder(MyHolder holder, int position) {
        holder.container.setTag(data.idGroup.get(position));
        holder.container.setOnClickListener(clickEvent);
        holder.name.setText(data.titleGroup.get(position));
        holder.time.setText(setDate(data.timestampGroup.get(position)));

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


    public class MyHolder extends RecyclerView.ViewHolder {
        public TextView name;
        public TextView time;
        public RelativeLayout container;


        public MyHolder(View itemView) {
            super(itemView);
            name = (TextView) itemView.findViewById(R.id.fragment_news_item_name);
            time = (TextView) itemView.findViewById(R.id.fragment_news_item_time);
            container = (RelativeLayout) itemView.findViewById(R.id.fragment_news_item_container);
        }
    }

    public void setItemEvent(View.OnClickListener clickEvent) {
        this.clickEvent = clickEvent;
    }

    public static class DataStructure {
        public ArrayList<Integer> idGroup = new ArrayList<>();
        public ArrayList<String> titleGroup = new ArrayList<>();
        public ArrayList<Long> timestampGroup = new ArrayList<>();
        public int sum;
    }

    public void setData(DataStructure data) {
        this.data = data;
        notifyDataSetChanged();
    }

    public String setDate(Long timestampMillis) {
        Calendar time = Calendar.getInstance();
        time.setTimeInMillis(timestampMillis);
        int year = time.get(Calendar.YEAR);
        int month = time.get(Calendar.MONTH) + 1;
        int day = time.get(Calendar.DAY_OF_MONTH);
        return year + "/" + month + "/" + day;
    }
}

