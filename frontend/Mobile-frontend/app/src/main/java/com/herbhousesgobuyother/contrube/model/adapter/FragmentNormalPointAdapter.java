package com.herbhousesgobuyother.contrube.model.adapter;

import android.animation.AnimatorSet;
import android.animation.ObjectAnimator;
import android.content.Context;
import android.graphics.Color;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.view.animation.OvershootInterpolator;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.herbhousesgobuyother.R;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Calendar;

/**
 * Created by 依杰 on 2016/11/7.
 */

public class FragmentNormalPointAdapter extends RecyclerView.Adapter<FragmentNormalPointAdapter.ViewHolder> {
    private LayoutInflater layoutInflater;
    private Context context;
    private DataStructure data;
    private ViewHolder viewHolder;

    public FragmentNormalPointAdapter(Context context) {
        super();
        this.context = context;
        layoutInflater = LayoutInflater.from(context);
        this.data = new DataStructure();

    }

    @Override
    public ViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        View view = layoutInflater.inflate(R.layout.fragment_normal_point_item, parent, false);
        viewHolder = new ViewHolder(view);

        return viewHolder;
    }

    @Override
    public void onBindViewHolder(ViewHolder holder, int position) {
        holder.storeName.setText(data.storeNameGroup.get(position));
        holder.setDate(data.timestampGroup.get(position));
        holder.setPoint(data.bonusGroup.get(position));
        holder.setBackgroundColor(position);

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
        public ArrayList<String> storeNameGroup = new ArrayList<>();
        public ArrayList<Long> timestampGroup = new ArrayList<>();
        public ArrayList<Integer> bonusGroup = new ArrayList<>();
    }

    public void setData(DataStructure data) {
        this.data = data;
        notifyDataSetChanged();
    }

    public static class ViewHolder extends RecyclerView.ViewHolder {

        public TextView storeName;
        public TextView timestamp;
        public TextView bonus;
        public LinearLayout container;

        public ViewHolder(View itemView) {
            super(itemView);
            storeName = (TextView) itemView.findViewById(R.id.fragment_normal_point_item_store_name_text);
            timestamp = (TextView) itemView.findViewById(R.id.fragment_normal_point_item_trade_time_text);
            bonus = (TextView) itemView.findViewById(R.id.fragment_normal_point_item_bonus_text);
            container = (LinearLayout) itemView.findViewById(R.id.fragment_normal_point_container);
        }

        public void setPoint(int count) {
            bonus.setText(String.valueOf(count));
            if (count >= 0) {
                bonus.setTextColor(0xff7ABC29);
                bonus.setText("+" + String.valueOf(count));
            } else {
                bonus.setTextColor(Color.RED);
                bonus.setText(String.valueOf(count));
            }
        }

        public void setDate(Long timestampMillis) {
            Calendar time = Calendar.getInstance();
            time.setTimeInMillis(timestampMillis * 1000);
            SimpleDateFormat format = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
            String tradeTime = format.format(time.getTime());
            timestamp.setText(tradeTime);
        }

        public void setBackgroundColor(int count) {
            if (count % 2 == 0) {
                container.setBackgroundColor(0xffD2CCDF);
            } else {
                container.setBackgroundColor(Color.WHITE);
            }
        }
    }

}