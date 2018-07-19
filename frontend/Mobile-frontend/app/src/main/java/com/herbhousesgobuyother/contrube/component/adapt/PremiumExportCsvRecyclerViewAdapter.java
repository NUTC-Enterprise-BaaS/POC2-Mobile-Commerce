package com.herbhousesgobuyother.contrube.component.adapt;

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

import java.util.ArrayList;

/**
 * Created by 依杰 on 2016/11/30.
 */

public class PremiumExportCsvRecyclerViewAdapter extends RecyclerView.Adapter<PremiumExportCsvRecyclerViewAdapter.ViewHolder> {
    private LayoutInflater mLayoutInflater;
    private Context mContext;
    private ViewHolder viewHolder;
    private DataStructure data;

    public PremiumExportCsvRecyclerViewAdapter(Context context) {
        super();
        this.mContext = context;
        mLayoutInflater = LayoutInflater.from(mContext);
        this.data = new DataStructure();

    }

    @Override
    public ViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        View view = mLayoutInflater.inflate(R.layout.item_export_csv_recycler_view, parent, false);
        viewHolder = new ViewHolder(view);

        return viewHolder;
    }

    @Override
    public void onBindViewHolder(ViewHolder holder, int position) {
        holder.number.setText(data.idGroup.get(position).toString().trim());
        holder.money.setText(data.moneyGroup.get(position).toString().trim());
        holder.phone.setText(data.phoneGroup.get(position).toString().trim());
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
        public ArrayList<String> phoneGroup = new ArrayList<>();
        public ArrayList<Integer> moneyGroup = new ArrayList<>();
    }

    public void setData(DataStructure data) {
        this.data = data;
        notifyDataSetChanged();
    }

    public class ViewHolder extends RecyclerView.ViewHolder {

        public TextView number;
        public TextView phone;
        public TextView money;
        public LinearLayout container;

        public ViewHolder(View itemView) {
            super(itemView);
            number = (TextView) itemView.findViewById(R.id.item_export_csv_number_text);
            phone = (TextView) itemView.findViewById(R.id.item_export_csv_phone_text);
            money = (TextView) itemView.findViewById(R.id.item_export_csv_money_text);
            container = (LinearLayout) itemView.findViewById(R.id.item_export_csv_field_container);
        }

        public void setBackgroundColor(int count) {
            if (count % 2 == 0) {
                container.setBackgroundColor(0xFFE79FA7);
            } else {
                container.setBackgroundColor(Color.WHITE);
            }
        }
    }
}
