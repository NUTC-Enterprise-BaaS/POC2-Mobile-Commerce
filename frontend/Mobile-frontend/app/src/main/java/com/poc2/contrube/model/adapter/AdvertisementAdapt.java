package com.poc2.contrube.model.adapter;

import android.content.Context;
import android.support.v4.view.PagerAdapter;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;

import com.android.volley.RequestQueue;
import com.android.volley.toolbox.ImageLoader;
import com.android.volley.toolbox.NetworkImageView;
import com.android.volley.toolbox.Volley;
import com.androidlibrary.module.BitmapCache;
import com.poc2.R;

import java.util.ArrayList;

/**
 * Created by Gary on 2016/5/16.
 */
public class AdvertisementAdapt extends PagerAdapter {
    private ImageLoader imageLoader;
    private RequestQueue imageRequestQueue;
    private DataStructure data;
    private LayoutInflater layoutInflater;
    private Context context;

    public AdvertisementAdapt(Context context) {
        this.context = context;
        this.data = new DataStructure(context);
        this.imageRequestQueue = Volley.newRequestQueue(context);
        this.imageLoader = new ImageLoader(imageRequestQueue, new BitmapCache());
        layoutInflater = LayoutInflater.from(context);

    }

    @Override
    public Object instantiateItem(ViewGroup container, int position) {
        View v = layoutInflater.inflate(R.layout.activity_advertisement_item, container, false);
        NetworkImageView view = (NetworkImageView) v.findViewById(R.id.advertisement);
        view.setTag(data.advertisementIdGroup.get(position));
        view.setImageUrl(data.advertisementAddressGroup.get(position), imageLoader);
        view.setScaleType(ImageView.ScaleType.FIT_XY);
        container.addView(v);
        return v;
    }

    @Override
    public void destroyItem(ViewGroup container, int position, Object object) {
        View v = (View) object;
        container.removeView(v);
    }

    @Override
    public int getCount() {
        return data.advertisementIdGroup.size();
    }

    @Override
    public boolean isViewFromObject(View view, Object object) {
        return view == object;
    }

    public void setData(DataStructure data) {
        this.data = data;
        notifyDataSetChanged();
    }

    public static class DataStructure {
        public ArrayList<Integer> advertisementIdGroup;
        public ArrayList<String> advertisementAddressGroup;

        public DataStructure(Context context) {
            advertisementIdGroup = new ArrayList<>();
            advertisementAddressGroup = new ArrayList<>();
        }
    }
}
