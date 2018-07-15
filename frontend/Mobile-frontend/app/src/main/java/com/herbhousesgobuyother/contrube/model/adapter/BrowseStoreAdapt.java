package com.herbhousesgobuyother.contrube.model.adapter;

import android.content.Context;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;

import com.android.volley.RequestQueue;
import com.android.volley.toolbox.ImageLoader;
import com.android.volley.toolbox.NetworkImageView;
import com.android.volley.toolbox.Volley;
import com.androidlibrary.module.BitmapCache;
import com.herbhousesgobuyother.R;

import java.util.ArrayList;

/**
 * Created by Gary on 2016/11/2.
 */

public class BrowseStoreAdapt extends RecyclerView.Adapter<BrowseStoreAdapt.MyHolder> {
    private LayoutInflater layoutInflater;
    private Context context;
    private ImageLoader imageLoader;
    private RequestQueue imageRequestQueue;
    private BrowseStoreAdapt.DataStructure data;
    private View.OnClickListener saveEvent;
    private View.OnClickListener mapGpsEvent;
    private View.OnClickListener callEvent;
    private View.OnLongClickListener onLongClickEvent;
    private View.OnClickListener onClickEvent;

    public BrowseStoreAdapt(Context context) {
        super();
        this.context = context;
        layoutInflater = LayoutInflater.from(context);
        this.imageRequestQueue = Volley.newRequestQueue(context);
        this.imageLoader = new ImageLoader(imageRequestQueue, new BitmapCache());
        this.data = new BrowseStoreAdapt.DataStructure(context);

    }

    @Override
    public MyHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        View view = layoutInflater.inflate(R.layout.fragment_browse_store_item, parent, false);
        MyHolder myHolder = new MyHolder(view);
        return myHolder;
    }

    @Override
    public void onBindViewHolder(MyHolder holder, int position) {
        holder.imageView.setTag(position);
        holder.imageView.setImageUrl(data.shopPhotoGroup.get(position), imageLoader);
        holder.imageView.setOnClickListener(onClickEvent);
        holder.imageView.setLongClickable(true);
        holder.imageView.setOnLongClickListener(onLongClickEvent);
        holder.name.setTag(position);
        holder.name.setText(data.shopNameGroup.get(position));
        holder.call.setTag(position);
        holder.call.setOnClickListener(callEvent);

        holder.map.setTag(position);
        holder.map.setOnClickListener(mapGpsEvent);

        holder.save.setTag(position);
        holder.save.setOnClickListener(saveEvent);
        holder.save.setBackgroundResource(setSaveIcon(data.shopLikeGroup.get(position)));
        holder.distance.setText(data.shopKmGroup.get(position) + "KM");
    }

    @Override
    public int getItemCount() {
        return data.shopIdGroup.size();
    }


    public class MyHolder extends RecyclerView.ViewHolder {
        public NetworkImageView imageView;
        public TextView name;
        public ImageView save;
        public ImageView call;
        public ImageView map;
        public TextView distance;

        public MyHolder(View itemView) {
            super(itemView);
            imageView = (NetworkImageView) itemView.findViewById(R.id.layout_browse_store_item_store_image);
            name = (TextView) itemView.findViewById(R.id.layout_browse_store_item_store_name);
            distance = (TextView) itemView.findViewById(R.id.layout_browse_store_item_distance);
            save = (ImageView) itemView.findViewById(R.id.layout_browse_store_item_save_image);
            call = (ImageView) itemView.findViewById(R.id.layout_browse_store_item_call_image);
            map = (ImageView) itemView.findViewById(R.id.layout_browse_store_item_map_image);
            distance.setSingleLine(true);
        }
    }

    public void setData(BrowseStoreAdapt.DataStructure data) {
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

    public void setOnLongClickListener(View.OnLongClickListener event) {
        onLongClickEvent = event;
    }

    public void setOnClickListener(View.OnClickListener event) {
        onClickEvent = event;
    }

    public void setItemCall(View.OnClickListener event) {
        this.callEvent = event;
    }

    public int setSaveIcon(String isSave) {
        if (isSave.equals("1")) {
            int save = com.androidlibrary.R.drawable.bn_21;
            return save;
        } else {
            int save = com.androidlibrary.R.drawable.bn_28;
            return save;
        }

    }
}

