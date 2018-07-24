package com.herbhousesgobuyother.contrube.model.adapter;

import android.animation.AnimatorSet;
import android.animation.ObjectAnimator;
import android.content.Context;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.view.animation.OvershootInterpolator;
import android.widget.ImageView;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.herbhousesgobuyother.R;

import java.util.ArrayList;

/**
 * Created by 依杰 on 2016/11/21.
 */

public class ActivityNormalAdvertisementDrawlayoutRecycleViewAdapt extends RecyclerView.Adapter<ActivityNormalAdvertisementDrawlayoutRecycleViewAdapt.ViewHolder> {
    private LayoutInflater layoutInflater;
    private Context context;
    private ActivityNormalAdvertisementDrawlayoutRecycleViewAdapt.DataStructure data;
    private ActivityNormalAdvertisementDrawlayoutRecycleViewAdapt.ViewHolder viewHolder;
    private itemClickListener itemClickListener;

    public interface itemClickListener {
        void onClick(int position, View view, ArrayList<Boolean> stateGroup);
    }

    public ActivityNormalAdvertisementDrawlayoutRecycleViewAdapt(Context context, DataStructure data) {
        layoutInflater = LayoutInflater.from(context);
        this.context = context;
        this.data = data;
    }


    @Override
    public ViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        View view = layoutInflater.inflate(R.layout.item_activity_advertisement_drawlayout, parent, false);
        viewHolder = new ViewHolder(view);

        return viewHolder;
    }

    @Override
    public void onBindViewHolder(final ViewHolder holder, final int position) {
        holder.item.setText(data.itemNameGroup.get(position));
        holder.icon.setBackgroundResource(data.iconGroup.get(position));
        holder.setBackgroundColor(position);
        holder.content.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                holder.content.setTag(data.stateGroup.get(position));
                itemClickListener.onClick(position, holder.content, data.stateGroup);
            }
        });

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
        return data.iconGroup.size();
    }

    public void setItemClickListener(itemClickListener itemClickListener) {
        this.itemClickListener = itemClickListener;
    }

    public static class DataStructure {
        public ArrayList<String> itemNameGroup;
        public ArrayList<Integer> iconGroup;
        public ArrayList<Boolean> stateGroup;

        public DataStructure() {
            iconGroup = new ArrayList<>();
            stateGroup = new ArrayList<>();

            iconGroup.add(R.drawable.icon_01);
            iconGroup.add(R.drawable.icon_02);
            iconGroup.add(R.drawable.icon_03);
            iconGroup.add(R.drawable.icon_04);
            iconGroup.add(R.drawable.icon_05);
            iconGroup.add(R.drawable.icon_06);
            iconGroup.add(R.drawable.icon_07);
            iconGroup.add(R.drawable.icon_09);
            iconGroup.add(R.drawable.icon_10);
            iconGroup.add(R.drawable.drawlayout_special);
            iconGroup.add(R.drawable.drawlayout_premium);

            for (int i = 0; i < iconGroup.size(); i++) {
                stateGroup.add(false);
            }
        }

        public DataStructure(ArrayList<String> list) {
            this();
            this.itemNameGroup = list;
        }

    }

    public class ViewHolder extends RecyclerView.ViewHolder {

        public TextView item;
        public ImageView icon;
        public RelativeLayout content;

        public ViewHolder(View itemView) {
            super(itemView);
            content = (RelativeLayout) itemView.findViewById(R.id.item_activity_advertisement_drawlayout_content);
            item = (TextView) itemView.findViewById(R.id.item_activity_advertisement_drawlayout_item);
            icon = (ImageView) itemView.findViewById(R.id.item_activity_advertisement_drawlayout_icon);
        }

        public void setBackgroundColor(int count) {
            if (count % 2 == 1) {
                content.setBackgroundColor(0x331E7EBD);
            } else {
                content.setBackgroundColor(0xFFF2F2F2);
            }
        }
    }
}
