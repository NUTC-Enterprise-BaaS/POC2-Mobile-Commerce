package com.herbhousesgobuyother.contrube.view.premium;

import android.content.Intent;
import android.graphics.Color;
import android.net.Uri;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v4.app.Fragment;
import android.support.v7.widget.DefaultItemAnimator;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;

import com.androidlibrary.component.decotarion.DividerDecoration;
import com.androidlibrary.module.SequenceLoadLogic;
import com.androidlibrary.module.backend.data.ApiV1PreferentialActivityGetData;
import com.androidlibrary.module.scrollchange.ScrollListener;
import com.herbhousesgobuyother.R;
import com.herbhousesgobuyother.contrube.controllor.activity.PremiumActivityController;
import com.herbhousesgobuyother.contrube.model.adapter.PremiumActivityAdapt;

/**
 * Created by ameng on 11/1/16.
 */

public class FragmentPremiumActivity extends Fragment {
    private PremiumActivityAdapt activityAdapt;
    private PremiumActivityController controller;
    private PremiumActivityAdapt.DataStructure activityData;
    private ScrollListener scrollListener;
    private SequenceLoadLogic loadLogic;
    private View back;

    @Nullable
    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        View layout = inflater.inflate(R.layout.fragment_premium_activity, container, false);
        return layout;
    }

    @Override
    public void onViewCreated(View view, Bundle savedInstanceState) {
        super.onViewCreated(view, savedInstanceState);
        finView();
        init();

    }

    private void finView() {
        back = getView().findViewById(R.id.toolbar_back_touch);
    }

    private void init() {
        controller = new PremiumActivityController(getContext());
        activityData = new PremiumActivityAdapt.DataStructure();
        loadLogic = new SequenceLoadLogic();

        LinearLayoutManager manager = new LinearLayoutManager(getActivity());
        manager.setOrientation(LinearLayoutManager.VERTICAL);
        activityAdapt = new PremiumActivityAdapt(getActivity());
        DefaultItemAnimator animator = new DefaultItemAnimator();
        DividerDecoration decoration = new DividerDecoration();
        decoration.setDividerColor(Color.parseColor("#DDDDDD"));
        decoration.setItemMargin(15, 15);

        RecyclerView recyclerView = (RecyclerView) getView().findViewById(R.id.fragment_news_recyclerview);
        recyclerView.setAdapter(activityAdapt);
        recyclerView.setLayoutManager(manager);
        recyclerView.setItemAnimator(animator);
        recyclerView.addItemDecoration(decoration);
        recyclerView.setHasFixedSize(true);

        scrollListener = new ScrollListener(manager);


        controller.setCallBackEvent(callBackEvent);
        controller.activityRequest();
        scrollListener.setDeclineCallBack(declineCallBack);
        activityAdapt.setItemEvent(itemClick);
        back.setOnClickListener(backClick);

    }

    private View.OnClickListener itemClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            String url = String.valueOf(view.getTag());
            Log.e("url", url);
            goStoreWeb(url);
        }
    };

    private PremiumActivityController.CallBackEvent callBackEvent = new PremiumActivityController.CallBackEvent() {
        @Override
        public void onError() {

        }

        @Override
        public void onSuccess(ApiV1PreferentialActivityGetData information) {
            if (information.result == 0) {
                update(information);
            }
        }
    };

    private void goStoreWeb(String url) {
        if (!url.equals("{}")) {
            Uri uri = Uri.parse(url);
            Intent intent = new Intent(Intent.ACTION_VIEW, uri);
            startActivity(intent);
        }
    }

    private ScrollListener.DeclineCallBack declineCallBack = new ScrollListener.DeclineCallBack() {
        @Override
        public void decline(RecyclerView recyclerView) {
            if (!(activityData.sum >= loadLogic.getEnd())) {
                return;
            }
            controller.activityRequest();
        }
    };

    private void update(ApiV1PreferentialActivityGetData information) {
        activityData.titleGroup = information.titleGroup;
        activityData.timestampGroup = information.timestampGroup;
        activityData.urlGroup = information.urlGroup;
        activityData.sum = information.sum;
        activityAdapt.setData(activityData);
    }

    private View.OnClickListener backClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            getActivity().onBackPressed();
        }
    };
}
