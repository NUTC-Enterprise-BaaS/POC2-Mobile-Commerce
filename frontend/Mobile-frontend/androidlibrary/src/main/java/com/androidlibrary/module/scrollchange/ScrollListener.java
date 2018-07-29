package com.androidlibrary.module.scrollchange;

import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.util.Log;

/**
 * Created by ameng on 2016/6/10.
 */
public class ScrollListener extends RecyclerView.OnScrollListener {
    private DeclineCallBack declineCallBack;
    private LinearLayoutManager manager;

    public ScrollListener(LinearLayoutManager manager) {
        super();
        this.manager = manager;
    }

    public interface DeclineCallBack {
        void decline(RecyclerView recyclerView);
    }

    @Override
    public void onScrollStateChanged(RecyclerView recyclerView, int newState) {
        super.onScrollStateChanged(recyclerView, newState);
    }

    @Override
    public void onScrolled(RecyclerView recyclerView, int dx, int dy) {
        super.onScrolled(recyclerView, dx, dy);
        int visibleItemCount = recyclerView.getChildCount();
        int totalItemCount = recyclerView.getAdapter().getItemCount();
        int visibleItemIndex = manager.findLastVisibleItemPosition() + 1;
        Log.e("visibleItemIndex", visibleItemIndex + "");
        if (dy < 0) {

        } else if (dy > 0) {
            if ((totalItemCount - visibleItemIndex) == 0) {
                declineCallBack.decline(recyclerView);
            }
        }
    }

    public void setDeclineCallBack(DeclineCallBack declineCallBack) {
        this.declineCallBack = declineCallBack;
    }
}
