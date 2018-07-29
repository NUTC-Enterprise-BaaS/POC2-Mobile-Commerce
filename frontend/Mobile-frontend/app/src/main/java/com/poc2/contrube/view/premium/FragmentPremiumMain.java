package com.poc2.contrube.view.premium;

import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.RelativeLayout;

import com.androidlibrary.module.backend.params.AccountInjection;
import com.poc2.R;

/**
 * Created by isa on 2016/11/2.
 */
public class FragmentPremiumMain extends Fragment {
    private ImageView menuImag;
    private RelativeLayout pointContainer;
    private RelativeLayout newsContainer;
    private RelativeLayout settingContainer;
    private RelativeLayout showQrContainer;
    private RelativeLayout advApplyContainer;
    private RelativeLayout joinSpecial;
    private AccountInjection accountInjection;

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        View layout = inflater.inflate(R.layout.fragment_premium_main, container, false);
        return layout;
    }

    @Override
    public void onViewCreated(View view, Bundle savedInstanceState) {
        super.onViewCreated(view, savedInstanceState);
        findView();
        init();
        ((ActivityPremiumAdvertisement) getActivity()).setAdvertisementEnable(false);
    }

    private void findView() {
        menuImag = (ImageView) getView().findViewById(R.id.premium_activity_menu);
        pointContainer = (RelativeLayout) getView().findViewById(R.id.premium_activity_bonus);
        newsContainer = (RelativeLayout) getView().findViewById(R.id.premium_activity_news);
        settingContainer = (RelativeLayout) getView().findViewById(R.id.premium_activity_setting);
        showQrContainer = (RelativeLayout) getView().findViewById(R.id.premium_activity_scan_qr_code_search);
        advApplyContainer = (RelativeLayout) getView().findViewById(R.id.premium_activity_advertisement);
        joinSpecial = (RelativeLayout) getView().findViewById(R.id.join);

    }

    private void init() {
        accountInjection = new AccountInjection(getActivity());
        if (accountInjection.loadRegisteredState().equals("1") || accountInjection.loadRegisteredState().equals("3") || accountInjection.loadRegisteredState().equals("5") || accountInjection.loadRegisteredState().equals("7")) {
            joinSpecial.setVisibility(View.INVISIBLE);
        }
        menuImag.setOnClickListener(openDrawlayoutEvent);
        pointContainer.setOnClickListener(pointClick);
        newsContainer.setOnClickListener(newsClick);
        settingContainer.setOnClickListener(settingClick);
        showQrContainer.setOnClickListener(showQr);
        advApplyContainer.setOnClickListener(advClick);
        joinSpecial.setOnClickListener(joinClick);
    }

    private View.OnClickListener joinClick = new View.OnClickListener() {
        @Override
        public void onClick(View v) {
            ((ActivityPremiumAdvertisement) getActivity()).goToSpecialApply();
        }
    };

    private View.OnClickListener showQr = new View.OnClickListener() {
        @Override
        public void onClick(View v) {
            ((ActivityPremiumAdvertisement) getActivity()).goToScan();
        }
    };

    private View.OnClickListener openDrawlayoutEvent = new View.OnClickListener() {
        @Override
        public void onClick(View v) {
            ((ActivityPremiumAdvertisement) getActivity()).getDrawlayout().openDrawer(((ActivityPremiumAdvertisement) getActivity()).getDrawlatoutContent());
        }
    };

    private View.OnClickListener newsClick = new View.OnClickListener() {
        @Override
        public void onClick(View v) {
            ((ActivityPremiumAdvertisement) getActivity()).goToNews();
        }
    };
    private View.OnClickListener pointClick = new View.OnClickListener() {
        @Override
        public void onClick(View v) {
            ((ActivityPremiumAdvertisement) getActivity()).goToPoint();
        }
    };
    private View.OnClickListener settingClick = new View.OnClickListener() {
        @Override
        public void onClick(View v) {
            ((ActivityPremiumAdvertisement) getActivity()).goToSetting();
        }
    };
    private View.OnClickListener advClick = new View.OnClickListener() {
        @Override
        public void onClick(View v) {
            ((ActivityPremiumAdvertisement) getActivity()).goToAdvertiseApply();
        }
    };
}
