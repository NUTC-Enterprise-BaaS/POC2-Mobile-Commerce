package com.poc2.contrube.model.adapter;

import android.content.Context;
import android.graphics.Color;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.poc2.R;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Calendar;

/**
 * Created by 依杰 on 2016/11/30.
 */

public class PremiumPointDetailRecyclerViewAdapter extends RecyclerView.Adapter<PremiumPointDetailRecyclerViewAdapter.ViewHolder> {
    private LayoutInflater layoutInflater;
    private DataStructure data;
    private ViewHolder viewHolder;
    private View.OnClickListener editEvent;

    public PremiumPointDetailRecyclerViewAdapter(Context context) {
        super();
        layoutInflater = LayoutInflater.from(context);
        this.data = new PremiumPointDetailRecyclerViewAdapter.DataStructure();
    }

    @Override
    public PremiumPointDetailRecyclerViewAdapter.ViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        View view = layoutInflater.inflate(R.layout.item_premium_point_detail, parent, false);
        viewHolder = new ViewHolder(view);

        return viewHolder;
    }

    @Override
    public void onBindViewHolder(ViewHolder holder, int position) {
        holder.receiveGroup.setText(data.receiveGroup.get(position));
        holder.setDate(data.timestampGroup.get(position));
        holder.setBackgroundColor(position);
        holder.container.setTag(position);
        holder.container.setOnClickListener(editEvent);
    }

    @Override
    public int getItemCount() {
        return data.idGroup.size();
    }


    public static class DataStructure {
        public ArrayList<Integer> idGroup = new ArrayList<>();
        public ArrayList<String> receiveGroup = new ArrayList<>();
        public ArrayList<Long> timestampGroup = new ArrayList<>();
    }

    public void setData(DataStructure data) {
        this.data = data;
        this.notifyDataSetChanged();
    }

    public class ViewHolder extends RecyclerView.ViewHolder {

        public TextView receiveGroup;
        public TextView timestamp;
        public LinearLayout container;

        public ViewHolder(View itemView) {
            super(itemView);
            receiveGroup = (TextView) itemView.findViewById(R.id.item_point_detail_receiver);
            timestamp = (TextView) itemView.findViewById(R.id.item_point_detail_trade_time);
            container = (LinearLayout) itemView.findViewById(R.id.item_point_detail_receiver_container);
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
                container.setBackgroundColor(0xFFE79FA7);
            } else {
                container.setBackgroundColor(Color.WHITE);
            }
        }
    }

    public void setEditListener(View.OnClickListener event) {
        editEvent = event;
    }
}
