package com.herbhousesgobuyother.contrube.view.special;

import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.RelativeLayout;

import com.androidlibrary.module.backend.params.AccountInjection;
import com.herbhousesgobuyother.R;

/**
 * Created by Xung on 2016/11/1.
 */

public class FragmentSpecialMain extends Fragment {
    private ImageView menuImag;
    private RelativeLayout pointContainer;
    private RelativeLayout newsContainer;
    private RelativeLayout settingContainer;
    private RelativeLayout showQrContainer;
    private RelativeLayout advApplyContainer;
    private RelativeLayout joinPremium;
    private AccountInjection accountInjection;


    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        View layout = inflater.inflate(R.layout.fragment_special_main, container, false);
        return layout;
    }

    @Override
    public void onViewCreated(View view, Bundle savedInstanceState) {
        super.onViewCreated(view, savedInstanceState);
        findView();
        init();
        ((ActivitySpecialAdvertisement) getActivity()).setAdvertisementEnable(false);
    }

    private void findView() {
        menuImag = (ImageView) getView().findViewById(R.id.special_activity_menu);
        pointContainer = (RelativeLayout) getView().findViewById(R.id.activity_enter_special_bonus);
        newsContainer = (RelativeLayout) getView().findViewById(R.id.activity_enter_special_news);
        settingContainer = (RelativeLayout) getView().findViewById(R.id.activity_enter_special_setting);
        showQrContainer = (RelativeLayout) getView().findViewById(R.id.activity_enter_special_scanqrcodesearch);
        advApplyContainer = (RelativeLayout) getView().findViewById(R.id.activity_enter_special_advertisement);
        joinPremium = (RelativeLayout) getView().findViewById(R.id.activity_enter_special_join);
    }

    private void init() {
        accountInjection = new AccountInjection(getActivity());
        if (accountInjection.loadRegisteredState().equals("2") || accountInjection.loadRegisteredState().equals("3") || accountInjection.loadRegisteredState().equals("6") || accountInjection.loadRegisteredState().equals("7")) {
            joinPremium.setVisibility(View.INVISIBLE);
        }
        menuImag.setOnClickListener(openDrawlayoutEvent);
        pointContainer.setOnClickListener(pointClick);
        newsContainer.setOnClickListener(newsClick);
        settingContainer.setOnClickListener(settingClick);
        showQrContainer.setOnClickListener(showQr);
        advApplyContainer.setOnClickListener(advClick);
        joinPremium.setOnClickListener(joinClick);
    }

    private View.OnClickListener joinClick = new View.OnClickListener() {
        @Override
        public void onClick(View v) {
            ((ActivitySpecialAdvertisement) getActivity()).goToPremiumApply();
        }
    };

    private View.OnClickListener showQr = new View.OnClickListener() {
        @Override
        public void onClick(View v) {
            ((ActivitySpecialAdvertisement) getActivity()).goToScan();
        }
    };

    private View.OnClickListener newsClick = new View.OnClickListener() {
        @Override
        public void onClick(View v) {
            ((ActivitySpecialAdvertisement) getActivity()).goToNews();
        }
    };
    private View.OnClickListener openDrawlayoutEvent = new View.OnClickListener() {
        @Override
        public void onClick(View v) {
            ((ActivitySpecialAdvertisement) getActivity()).getDrawlayout().openDrawer(((ActivitySpecialAdvertisement) getActivity()).getDrawlatoutContent());
        }
    };
    private View.OnClickListener pointClick = new View.OnClickListener() {
        @Override
        public void onClick(View v) {
            ((ActivitySpecialAdvertisement) getActivity()).goToPoint();
        }
    };
    private View.OnClickListener settingClick = new View.OnClickListener() {
        @Override
        public void onClick(View v) {
            ((ActivitySpecialAdvertisement) getActivity()).goToSetting();
        }
    };
    private View.OnClickListener advClick = new View.OnClickListener() {
        @Override
        public void onClick(View v) {
            ((ActivitySpecialAdvertisement) getActivity()).goToAdvertiseApply();
        }
    };

}
