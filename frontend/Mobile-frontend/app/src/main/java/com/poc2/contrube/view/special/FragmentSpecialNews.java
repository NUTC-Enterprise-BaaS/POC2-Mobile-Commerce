package com.poc2.contrube.view.special;

import android.content.Intent;
import android.graphics.Color;
import android.net.Uri;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v4.app.Fragment;
import android.support.v7.widget.DefaultItemAnimator;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Toast;

import com.androidlibrary.component.decotarion.DividerDecoration;
import com.androidlibrary.module.SequenceLoadLogic;
import com.androidlibrary.module.backend.data.ApiV1SpecialNewsGetData;
import com.androidlibrary.module.backend.data.ApiV1SpecialNewsIdGetData;
import com.androidlibrary.module.scrollchange.ScrollListener;
import com.poc2.R;
import com.poc2.contrube.controllor.news.SpecialNewsController;
import com.poc2.contrube.model.adapter.SpecialNewsAdapt;

/**
 * Created by ameng on 11/1/16.
 */

public class FragmentSpecialNews extends Fragment {
    private SpecialNewsAdapt newsAdapt;
    private SpecialNewsController controller;
    private SpecialNewsAdapt.DataStructure newsData;
    private ScrollListener scrollListener;
    private SequenceLoadLogic loadLogic;
    private View back;

    @Nullable
    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        View layout = inflater.inflate(R.layout.fragment_special_news, container, false);
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
        controller = new SpecialNewsController(getContext());
        newsData = new SpecialNewsAdapt.DataStructure();
        loadLogic = new SequenceLoadLogic();

        LinearLayoutManager manager = new LinearLayoutManager(getActivity());
        manager.setOrientation(LinearLayoutManager.VERTICAL);
        newsAdapt = new SpecialNewsAdapt(getActivity());
        DefaultItemAnimator animator = new DefaultItemAnimator();
        DividerDecoration decoration = new DividerDecoration();
        decoration.setDividerColor(Color.parseColor("#DDDDDD"));
        decoration.setItemMargin(15, 15);

        RecyclerView recyclerView = (RecyclerView) getView().findViewById(R.id.fragment_news_recyclerview);
        recyclerView.setAdapter(newsAdapt);
        recyclerView.setLayoutManager(manager);
        recyclerView.setItemAnimator(animator);
        recyclerView.addItemDecoration(decoration);
        recyclerView.setHasFixedSize(true);

        scrollListener = new ScrollListener(manager);


        controller.setCallBackEvent(callBackEvent);
        controller.newsRequest();
        scrollListener.setDeclineCallBack(declineCallBack);
        newsAdapt.setItemEvent(itemClick);
        back.setOnClickListener(backClick);
    }

    private View.OnClickListener itemClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            String newsId = String.valueOf(view.getTag().toString());
            controller.newsInfoRequest(newsId);
        }
    };

    private SpecialNewsController.CallBackEvent callBackEvent = new SpecialNewsController.CallBackEvent() {
        @Override
        public void onError() {

        }

        @Override
        public void onSuccess(ApiV1SpecialNewsGetData information) {
            if (information.result == 0) {
                update(information);
            } else if (information.messageGroup.get(0).toString().equals("No such data input error")) {
                String content = getString(R.string.request_data_empty);
                Toast.makeText(getActivity(), content, Toast.LENGTH_LONG).show();
            }

        }

        @Override
        public void onSuccess(ApiV1SpecialNewsIdGetData information) {
            if (information.result == 0) {
                goStoreWeb(information.url);
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
            if (!(newsData.sum >= loadLogic.getEnd())) {
                return;
            }
            controller.newsRequest();
        }
    };

    private void update(ApiV1SpecialNewsGetData information) {
        newsData.idGroup = information.idGroup;
        newsData.titleGroup = information.titleGroup;
        newsData.timestampGroup = information.timestampGroup;
        newsData.sum = information.sum;
        newsAdapt.setData(newsData);
    }

    private View.OnClickListener backClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            getActivity().onBackPressed();
        }
    };
}
