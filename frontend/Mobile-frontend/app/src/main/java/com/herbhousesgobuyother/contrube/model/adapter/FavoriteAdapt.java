package com.herbhousesgobuyother.contrube.model.adapter;

import android.content.Context;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;


import com.herbhousesgobuyother.R;

import java.util.ArrayList;

/**
 * Created by Gary on 2016/11/2.
 */

public class FavoriteAdapt extends RecyclerView.Adapter<FavoriteAdapt.MyHolder> {
    private LayoutInflater layoutInflater;
    private Context context;
    private FavoriteAdapt.DataStructure data;
    private View.OnClickListener shareEvent;
    private View.OnClickListener mapGpsEvent;
    private View.OnClickListener callEvent;
    private View.OnClickListener onClickEvent;
    private View.OnClickListener deleteEvent;

    public FavoriteAdapt(Context context) {
        super();
        this.context = context;
        layoutInflater = LayoutInflater.from(context);
        this.data = new FavoriteAdapt.DataStructure();
    }

    @Override
    public MyHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        View view = layoutInflater.inflate(R.layout.item_myfavorite_button, parent, false);
        MyHolder myHolder = new MyHolder(view);
        return myHolder;
    }

    @Override
    public void onBindViewHolder(MyHolder holder, int position) {
        holder.store.setTag(position);
        holder.store.setOnClickListener(onClickEvent);
        holder.name.setText(data.nameGroup.get(position));
        holder.address.setText(data.addressGroup.get(position));
        holder.phone.setText(data.phoneGroup.get(position));
        holder.distance.setText(data.kmGroup.get(position) + "km");

        holder.call.setTag(position);
        holder.call.setOnClickListener(callEvent);
        holder.map.setTag(position);
        holder.map.setOnClickListener(mapGpsEvent);
        holder.share.setTag(position);
        holder.share.setOnClickListener(shareEvent);
        holder.delete.setTag(position);
        holder.delete.setOnClickListener(deleteEvent);
    }

    @Override
    public int getItemCount() {
        return data.idGroup.size();
    }


    public class MyHolder extends RecyclerView.ViewHolder {
        public LinearLayout store;
        public TextView name;
        public TextView address;
        public TextView phone;
        public TextView distance;
        public ImageView call;
        public ImageView map;
        public ImageView share;
        public TextView delete;


        public MyHolder(View itemView) {
            super(itemView);
            store = (LinearLayout) itemView.findViewById(R.id.fragment_store_container);
            name = (TextView) itemView.findViewById(R.id.fragment_name);
            address = (TextView) itemView.findViewById(R.id.fragment_address);
            phone = (TextView) itemView.findViewById(R.id.fragment_phone);
            distance = (TextView) itemView.findViewById(R.id.fragment_distance);
            call = (ImageView) itemView.findViewById(R.id.fragment_call);
            map = (ImageView) itemView.findViewById(R.id.fragment_bus);
            share = (ImageView) itemView.findViewById(R.id.fragment_share);
            delete = (TextView) itemView.findViewById(R.id.fragment_delete);
            name.setSingleLine(true);
            address.setSingleLine(true);
            phone.setSingleLine(true);
            distance.setSingleLine(true);
        }
    }


    public static class DataStructure {
        public ArrayList<Integer> idGroup = new ArrayList<>();
        public ArrayList<String> nameGroup = new ArrayList<>();
        public ArrayList<String> phoneGroup = new ArrayList<>();
        public ArrayList<String> addressGroup = new ArrayList<>();
        public ArrayList<String> distanceGroup = new ArrayList<>();
        public ArrayList<String> photoGroup = new ArrayList<>();
        public ArrayList<String> urlGroup = new ArrayList<>();
        public ArrayList<String> kmGroup = new ArrayList<>();
    }

    public void setData(DataStructure data) {
        this.data = data;
        notifyDataSetChanged();
    }

    public void setShareEvent(View.OnClickListener event) {
        this.shareEvent = event;
    }

    public void setDeleteEvent(View.OnClickListener event) {
        this.deleteEvent = event;
    }

    public void setMapListener(View.OnClickListener event) {
        mapGpsEvent = event;
    }

    public void setOnClickListener(View.OnClickListener event) {
        onClickEvent = event;
    }

    public void setItemCall(View.OnClickListener event) {
        this.callEvent = event;
    }

}

