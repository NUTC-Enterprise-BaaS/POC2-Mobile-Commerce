package com.herbhousesgobuyother.contrube.view.normal;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v4.app.Fragment;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.androidlibrary.module.backend.data.ApiV1CheckUserIdentityGetData;
import com.androidlibrary.module.backend.data.ApiV1NormalUserPointGetData;
import com.androidlibrary.module.backend.params.AccountInjection;
import com.herbhousesgobuyother.R;
import com.herbhousesgobuyother.contrube.broadcastreceiver.SettingHideReceiver;
import com.herbhousesgobuyother.contrube.controllor.main.NormalMainController;
import com.herbhousesgobuyother.contrube.core.ActivityLauncher;
import com.herbhousesgobuyother.contrube.core.FragmentLauncher;
import com.herbhousesgobuyother.contrube.model.SettingDataStore;

/**
 * Created by 依杰 on 2016/11/2.
 */

public class FragmentNormalMain extends Fragment {
    private RelativeLayout newsContainer;
    private RelativeLayout scanContainer;
    private RelativeLayout gcmContainer;
    private RelativeLayout browseContainer;
    private RelativeLayout pointContainer;
    private RelativeLayout settingContainer;
    private RelativeLayout applySpecialContainer;
//    private RelativeLayout applyPremiumContainer;
//    private RelativeLayout enterSpecialContainer;
//    private RelativeLayout enterPremiumContainer;
//    private RelativeLayout checkSpecialContainer;
//    private RelativeLayout checkPremiumContainer;
    private TextView ethereumTextView;
    private ImageView openDrawlayoutImag;
    private SettingHideReceiver settingHideReceiver;
    private View layout;
    private String registeredState;
    private AccountInjection accountInjection;
    private NormalMainController controller;

    private TextView mLogoText;

    @Nullable
    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        layout = inflater.inflate(R.layout.fragment_normal_main, container, false);
        return layout;
    }

    @Override
    public void onViewCreated(View view, Bundle savedInstanceState) {
        super.onViewCreated(view, savedInstanceState);
        findView();
        init();
        ((ActivityNormalAdvertisement) getActivity()).setAdvertisementEnable(false);
    }

    private void findView() {
        newsContainer = (RelativeLayout) getView().findViewById(R.id.fragment_main_new);
        scanContainer = (RelativeLayout) getView().findViewById(R.id.fragment_main_scan);
        gcmContainer = (RelativeLayout) getView().findViewById(R.id.fragment_main_activity_search);
        browseContainer = (RelativeLayout) getView().findViewById(R.id.fragment_main_store_search);
        pointContainer = (RelativeLayout) getView().findViewById(R.id.fragment_main_bonus_search);
        settingContainer = (RelativeLayout) getView().findViewById(R.id.fragment_main_setting);
        applySpecialContainer = (RelativeLayout) getView().findViewById(R.id.fragment_main_special);
//        applyPremiumContainer = (RelativeLayout) getView().findViewById(R.id.fragment_main_preferential);
//        enterSpecialContainer = (RelativeLayout) getView().findViewById(R.id.fragment_main_enter_special);
//        enterPremiumContainer = (RelativeLayout) getView().findViewById(R.id.fragment_main_enter_preferential);
        openDrawlayoutImag = (ImageView) getView().findViewById(R.id.fragment_main_menu);
//        checkSpecialContainer = (RelativeLayout) getView().findViewById(R.id.fragment_main_check_special);
//        checkPremiumContainer = (RelativeLayout) getView().findViewById(R.id.fragment_main_check_preferential);
        mLogoText = getView().findViewById(R.id.fragment_main_logo);
        ethereumTextView = getView().findViewById(R.id.fragment_main_store_search_textview);
    }

    private void init() {
        controller = new NormalMainController(getContext());
        accountInjection = new AccountInjection(getContext());

        newsContainer.setOnClickListener(newsClick);
        scanContainer.setOnClickListener(scanClick);
        gcmContainer.setOnClickListener(gcmClick);
        browseContainer.setOnClickListener(browseClick);
        pointContainer.setOnClickListener(pointClick);
        settingContainer.setOnClickListener(settingClick);
        applySpecialContainer.setOnClickListener(applySpecialClick);
//        applyPremiumContainer.setOnClickListener(applyPremiumClick);
//        enterSpecialContainer.setOnClickListener(enterSpecialClick);
//        enterPremiumContainer.setOnClickListener(enterPremiumClick);
        openDrawlayoutImag.setOnClickListener(openClick);
        controller.setCallBackEvent(callBackEvent);
//        checkSpecialContainer.setOnClickListener(null);
//        checkPremiumContainer.setOnClickListener(null);
        checkIsShowJoinButton();
        checkState();
        registerDisplayButtonReceiver();

        controller.checkLdapState();
    }

    private NormalMainController.CallBackEvent callBackEvent = new NormalMainController.CallBackEvent() {
        @Override
        public void onError() {

        }

        @Override
        public void onSuccess(ApiV1CheckUserIdentityGetData information) {
            SettingDataStore store = new SettingDataStore(getActivity());
            boolean isShowJoin = store.loadIsShowJoin();
            if (information.result == 0) {
                if (isShowJoin) {
                    if (!information.special.equals("1")) {
//                        checkSpecialContainer.setVisibility(View.INVISIBLE);
                    }
                    if (!information.preferential.equals("1")) {
//                        checkPremiumContainer.setVisibility(View.INVISIBLE);
                    }
                }
            }
        }

        @Override
        public void onSuccess(ApiV1NormalUserPointGetData information) {
            Log.e("onSuccess", "//" + information.token);
            ethereumTextView.setText(information.token == null ? "開通轉換點數" : "轉換平台點數");
        }
    };

    private void checkState() {
        controller.checkStateRequest();
    }

    private View.OnClickListener openClick = new View.OnClickListener() {
        @Override
        public void onClick(View v) {
            ((ActivityNormalAdvertisement) getActivity()).getDrawlayout().openDrawer(((ActivityNormalAdvertisement) getActivity()).getDrawlatoutContent());
        }
    };

    private View.OnClickListener newsClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            ((ActivityNormalAdvertisement) getActivity()).setAdvertisementEnable(true);
            FragmentLauncher.changeToBack(getContext(), R.id.content_container, null, FragmentNews.class.getName());
        }
    };

    private View.OnClickListener scanClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            ActivityLauncher.go(getContext(), ActivityQrcode.class, null);
        }
    };

    private View.OnClickListener gcmClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            ((ActivityNormalAdvertisement) getActivity()).goToSearchStore();
        }
    };

    private View.OnClickListener browseClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            if (ethereumTextView.getText().toString().equals("開通轉換點數")) {
                ((ActivityNormalAdvertisement) getActivity()).goToLdap();
            } else {
                ((ActivityNormalAdvertisement) getActivity()).goToActivity();
            }
        }
    };

    private View.OnClickListener pointClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            ((ActivityNormalAdvertisement) getActivity()).goToPoint();
        }
    };

    private View.OnClickListener settingClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            ((ActivityNormalAdvertisement) getActivity()).goToSetting();

        }
    };

    private View.OnClickListener applySpecialClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            ((ActivityNormalAdvertisement) getActivity()).goToSpecialApply();
        }
    };

    private View.OnClickListener applyPremiumClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            ((ActivityNormalAdvertisement) getActivity()).goToPremiumApply();
        }
    };

    private View.OnClickListener enterSpecialClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            ((ActivityNormalAdvertisement) getActivity()).goToSpecial();
        }
    };

    private View.OnClickListener enterPremiumClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            ((ActivityNormalAdvertisement) getActivity()).goToPremium();

        }
    };

    /**
     * 0：特約商店以及優惠商店尚未註冊
     * 1：特約商店已註冊優惠商店尚未註冊
     * 2：優惠商店已註冊特約商店尚未註冊
     * 3：優惠商店以及特約商店都已註冊
     * 4：超級使用者  特約商店以及優惠商店尚未註冊
     * 5：超級使用者  特約商店已註冊  優惠商店尚未註冊
     * 6：超級使用者  優惠商店已註冊  特約商店尚未註冊
     * 7：超級使用者  優惠商店以及特約商店都已註冊
     */
    private void registeredState() {
        registeredState = accountInjection.loadRegisteredState();
//        if (registeredState.equals("0") || registeredState.equals("4")) {
//            applySpecialContainer.setVisibility(View.VISIBLE);
//            enterSpecialContainer.setVisibility(View.INVISIBLE);
//            applyPremiumContainer.setVisibility(View.VISIBLE);
//            enterPremiumContainer.setVisibility(View.INVISIBLE);
//        } else if (registeredState.equals("1") || registeredState.equals("5")) {
//            applySpecialContainer.setVisibility(View.INVISIBLE);
//            enterSpecialContainer.setVisibility(View.VISIBLE);
//            applyPremiumContainer.setVisibility(View.VISIBLE);
//            enterPremiumContainer.setVisibility(View.INVISIBLE);
//        } else if (registeredState.equals("2") || registeredState.equals("6")) {
//            applySpecialContainer.setVisibility(View.VISIBLE);
//            enterSpecialContainer.setVisibility(View.INVISIBLE);
//            applyPremiumContainer.setVisibility(View.INVISIBLE);
//            enterPremiumContainer.setVisibility(View.VISIBLE);
//        }
    }

    /**
     * 是否顯示加入其他會員按鈕。
     */
    private void checkIsShowJoinButton() {
        SettingDataStore store = new SettingDataStore(getContext());
        boolean isShowJoin = store.loadIsShowJoin();
        int state = (isShowJoin) ? View.VISIBLE : View.GONE;

//        applySpecialContainer.setVisibility(state);
//        applyPremiumContainer.setVisibility(state);
//        enterSpecialContainer.setVisibility(state);
//        enterPremiumContainer.setVisibility(state);
//        checkSpecialContainer.setVisibility(state);
//        checkPremiumContainer.setVisibility(state);
        if (isShowJoin) {
            registeredState();
        }
    }

    /**
     * 掛起 Receiver，如設定頁面 顯示/隱藏 按鈕時發出廣播，收到後立即改變狀態。
     */
    private void registerDisplayButtonReceiver() {
        settingHideReceiver = new SettingHideReceiver(getContext(), layout);
        settingHideReceiver.register();
    }

    /**
     * 如不註銷 Receiver，會產生記憶體洩漏錯誤。
     */
    @Override
    public void onDestroyView() {
        super.onDestroyView();
        if (settingHideReceiver != null) {
            settingHideReceiver.unregister();
        }
    }
}
