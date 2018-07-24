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

/**
 * Created by 依杰 on 2018/7/16.
 */

public class FragmentNormalCouponAdapt extends RecyclerView.Adapter<FragmentNormalCouponAdapt.ViewHolder> {
    private LayoutInflater layoutInflater;
    private FragmentNormalCouponAdapt.ViewHolder viewHolder;
    private FragmentNormalCouponAdapt.DataStructure data;
    private CallBack mCallBck;


    public interface CallBack{
        void onClick(int position);
    }

    public FragmentNormalCouponAdapt(Context context) {
        super();
        layoutInflater = LayoutInflater.from(context);
        data = new FragmentNormalCouponAdapt.DataStructure();
    }

    @Override
    public FragmentNormalCouponAdapt.ViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        View view = layoutInflater.inflate(R.layout.item_fragment_coupon, parent, false);
        viewHolder = new FragmentNormalCouponAdapt.ViewHolder(view);

        return viewHolder;
    }

    @Override
    public void onBindViewHolder(FragmentNormalCouponAdapt.ViewHolder holder, final int position) {

        viewHolder.name.setText(data.titleGroup.get(position));
        viewHolder.container.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                mCallBck.onClick(position);
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

    public static class DataStructure {
        public ArrayList<String> titleGroup = new ArrayList<>();
        public ArrayList<String> idGroup = new ArrayList<>();
    }

    public void setData(FragmentNormalCouponAdapt.DataStructure data) {
        this.data = data;
        notifyDataSetChanged();
    }

    public void clear(){
        data.idGroup.clear();
        data.titleGroup.clear();
        notifyDataSetChanged();
    }

    @Override
    public int getItemCount() {
        return data.titleGroup.size();
    }

    public static class ViewHolder extends RecyclerView.ViewHolder {

        public TextView name;
        public RelativeLayout container;

        public ViewHolder(View itemView) {
            super(itemView);
            name = (TextView) itemView.findViewById(R.id.item_fragment_gcm_record_name);
            container = (RelativeLayout) itemView.findViewById(R.id.item_fragment_gcm_record_name_container);
        }
    }

    public void setClickEvent(CallBack event) {
        this.mCallBck = event;
    }
}
