package com.poc2.contrube.model.adapter;

import android.content.Context;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;

import com.poc2.R;

import java.util.ArrayList;

/**
 * Created by Gary on 2016/11/2.
 */

public class BrowseStoreListAdapt extends RecyclerView.Adapter<BrowseStoreListAdapt.MyHolder> {
    private LayoutInflater layoutInflater;
    private Context context;
    private BrowseStoreListAdapt.DataStructure data;
    private View.OnClickListener saveEvent;
    private View.OnClickListener mapGpsEvent;
    private View.OnClickListener onClickEvent;

    public BrowseStoreListAdapt(Context context) {
        super();
        this.context = context;
        layoutInflater = LayoutInflater.from(context);
        this.data = new BrowseStoreListAdapt.DataStructure(context);

    }

    @Override
    public MyHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        View view = layoutInflater.inflate(R.layout.fragment_browse_store_list_item, parent, false);
        MyHolder myHolder = new MyHolder(view);
        return myHolder;
    }

    @Override
    public void onBindViewHolder(MyHolder holder, int position) {
        holder.name.setTag(position);
        holder.name.setText(data.shopNameGroup.get(position));
        holder.name.setOnClickListener(onClickEvent);
        holder.distance.setTag(position);
        holder.distance.setText(data.shopKmGroup.get(position) + "KM");
        holder.distance.setOnClickListener(mapGpsEvent);
        holder.save.setTag(position);
        holder.save.setOnClickListener(saveEvent);
        holder.save.setBackgroundResource(setSaveIcon(data.shopLikeGroup.get(position)));
    }

    @Override
    public int getItemCount() {
        return data.shopIdGroup.size();
    }


    public class MyHolder extends RecyclerView.ViewHolder {
        public TextView name;
        public ImageView save;
        public TextView distance;
        public View line;

        public MyHolder(View itemView) {
            super(itemView);
            name = (TextView) itemView.findViewById(R.id.layout_browse_store_item_store_name);
            distance = (TextView) itemView.findViewById(R.id.layout_browse_store_item_distance);
            save = (ImageView) itemView.findViewById(R.id.layout_browse_store_item_save_image);
            line = itemView.findViewById(R.id.layout_browse_store_item_line);
            name.setSingleLine(true);
            distance.setSingleLine(true);
        }
    }

    public void setData(BrowseStoreListAdapt.DataStructure data) {
        this.data = data;
        notifyDataSetChanged();
    }

    public static class DataStructure {
        public ArrayList<String> shopIdGroup;
        public ArrayList<String> shopNameGroup;
        public ArrayList<String> shopPhotoGroup;
        public ArrayList<String> shopPhoneGroup;
        public ArrayList<String> shopAddressGroup;
        public ArrayList<String> shopUrlGroup;
        public ArrayList<String> shopLikeGroup;
        public ArrayList<String> shopKmGroup;
        public int sum;

        public DataStructure(Context context) {
            shopIdGroup = new ArrayList<>();
            shopNameGroup = new ArrayList<>();
            shopPhotoGroup = new ArrayList<>();
            shopPhoneGroup = new ArrayList<>();
            shopAddressGroup = new ArrayList<>();
            shopUrlGroup = new ArrayList<>();
            shopLikeGroup = new ArrayList<>();
            shopKmGroup = new ArrayList<>();
        }
    }

    public void setSaveEvent(View.OnClickListener event) {
        this.saveEvent = event;
    }

    public void setMapListener(View.OnClickListener event) {
        mapGpsEvent = event;
    }


    public void setOnClickListener(View.OnClickListener event) {
        onClickEvent = event;
    }


    public int setSaveIcon(String isSave) {
        if (isSave.equals("1")) {
            int save = com.androidlibrary.R.drawable.bn_21;
            return save;
        } else {
            int save = R.drawable.heart_icon_01;
            return save;
        }

    }
}

