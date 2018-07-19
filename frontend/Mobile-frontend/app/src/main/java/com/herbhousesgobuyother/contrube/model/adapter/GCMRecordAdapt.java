package com.herbhousesgobuyother.contrube.model.adapter;

import android.content.Context;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import com.herbhousesgobuyother.R;

/**
 * Created by Gary on 2016/11/2.
 */

public class GCMRecordAdapt extends RecyclerView.Adapter<GCMRecordAdapt.MyHolder> {
    private LayoutInflater layoutInflater;
    private Context context;

    public GCMRecordAdapt(Context context) {
        super();
        this.context = context;
        layoutInflater = LayoutInflater.from(context);
    }

    @Override
    public MyHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        View view = layoutInflater.inflate(R.layout.fragment_news_item, parent, false);
        MyHolder myHolder = new MyHolder(view);
        return myHolder;
    }

    @Override
    public void onBindViewHolder(MyHolder holder, int position) {

    }

    @Override
    public int getItemCount() {
        return 10;
    }


    public class MyHolder extends RecyclerView.ViewHolder {
        public TextView name;
        public TextView time;


        public MyHolder(View itemView) {
            super(itemView);
            name = (TextView) itemView.findViewById(R.id.fragment_news_item_name);
            time = (TextView) itemView.findViewById(R.id.fragment_news_item_time);
        }
    }
}

