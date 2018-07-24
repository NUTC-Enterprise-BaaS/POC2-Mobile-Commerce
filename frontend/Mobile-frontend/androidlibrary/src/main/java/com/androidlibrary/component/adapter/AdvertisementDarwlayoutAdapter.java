package com.androidlibrary.component.adapter;

import android.animation.AnimatorSet;
import android.animation.ObjectAnimator;
import android.content.Context;
import android.support.v7.widget.RecyclerView;
import android.view.View;
import android.view.ViewGroup;
import android.view.animation.OvershootInterpolator;

import com.androidlibrary.R;
import com.androidlibrary.component.item.DrawListItem;

import java.util.ArrayList;
import java.util.Arrays;

/**
 * Created by 依杰 2016/7/5.
 */
public class AdvertisementDarwlayoutAdapter extends RecyclerView.Adapter {
    private Context context;
    private DrawListItem item;
    private DataStructure data;
    private itemClickListener itemClickListener;

    public interface itemClickListener {
        public void onClick(int position);
    }

    public AdvertisementDarwlayoutAdapter(Context context) {
        this.context = context;
        this.data = new DataStructure(context);
    }

    @Override
    public RecyclerView.ViewHolder onCreateViewHolder(ViewGroup viewGroup, int i) {
        item = new DrawListItem(context);
        return new RecyclerView.ViewHolder(item) {
        };
    }

    @Override
    public void onBindViewHolder(RecyclerView.ViewHolder holder, final int position) {
        DrawListItem item = (DrawListItem) holder.itemView;
        item.itemName.setText(data.itemNameGroup.get(position));
        item.itemIcon.setBackgroundResource(data.iconGroup.get(position));
        item.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                itemClickListener.onClick(position);
            }
        });
        if (position == data.iconGroup.size() - 1) {
            item.addSplit();
        }

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
        return data.itemNameGroup.size();
    }

    public void setItemClickListener(itemClickListener itemClickListener) {
        this.itemClickListener = itemClickListener;
    }

    public class DataStructure {
        public ArrayList<String> itemNameGroup;
        public ArrayList<Integer> iconGroup;

        public DataStructure(Context context) {
            itemNameGroup = new ArrayList<>(Arrays.asList(context.getResources().getStringArray(R.array.special_list)));
            iconGroup = new ArrayList<>();

            iconGroup.add(R.drawable.logout_icon);
            iconGroup.add(R.drawable.setting_icon);
            iconGroup.add(R.drawable.special_post_icon);
            iconGroup.add(R.drawable.special_activity_icon);
            iconGroup.add(R.drawable.recode_point_icon);
            iconGroup.add(R.drawable.recode_pay_icon);
            iconGroup.add(R.drawable.broadcast_apply_icon);
            iconGroup.add(R.drawable.note_icon);
            iconGroup.add(R.drawable.service_icon);
            iconGroup.add(R.drawable.preferential_icon);
        }
    }
}
