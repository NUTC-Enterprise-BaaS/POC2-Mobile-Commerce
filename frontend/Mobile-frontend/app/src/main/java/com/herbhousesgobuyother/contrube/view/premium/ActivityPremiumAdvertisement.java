package com.herbhousesgobuyother.contrube.view.premium;

import android.content.Context;
import android.content.Intent;
import android.content.res.Configuration;
import android.content.res.Resources;
import android.net.Uri;
import android.os.Bundle;
import android.support.v4.app.FragmentManager;
import android.support.v4.view.ViewPager;
import android.support.v4.widget.DrawerLayout;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.util.Log;
import android.view.View;
import android.widget.ImageView;
import android.widget.RelativeLayout;
import android.widget.TextView;
import android.widget.Toast;

import com.androidlibrary.component.dialog.CheckExitDialog;
import com.androidlibrary.module.backend.data.ApiV1AdveriseShowGetData;
import com.androidlibrary.module.backend.data.ApiV1CheckUserIdentityGetData;
import com.androidlibrary.module.backend.data.ApiV1PublishPostData;
import com.androidlibrary.module.backend.data.ApiV1PushUnregisterData;
import com.androidlibrary.module.backend.data.ApiV1UserCustomerServiceGetData;
import com.androidlibrary.module.backend.data.ApiV1UserInstructionGetData;
import com.androidlibrary.module.backend.params.AccountInjection;
import com.androidlibrary.module.consts.AccountConst;
import com.herbhousesgobuyother.R;
import com.herbhousesgobuyother.contrube.controllor.advertisement.PremiumAdvertisementController;
import com.herbhousesgobuyother.contrube.core.ActivityLauncher;
import com.herbhousesgobuyother.contrube.core.FragmentLauncher;
import com.herbhousesgobuyother.contrube.model.adapter.ActivityPremiumAdvertisementDrawlayoutRecycleViewAdapt;
import com.herbhousesgobuyother.contrube.model.adapter.AdvertisementAdapt;
import com.herbhousesgobuyother.contrube.view.guest.ActivityLogin;
import com.herbhousesgobuyother.contrube.view.normal.ActivityNormalAdvertisement;
import com.herbhousesgobuyother.contrube.view.special.ActivitySpecialAdvertisement;
import com.herbhousesgobuyother.contrube.view.special.ActivitySpecialRegister;
import com.viewpagerindicator.CirclePageIndicator;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.Collections;

import static com.herbhousesgobuyother.contrube.view.normal.ActivityNormalAdvertisement.backToMainState;


/**
 * Created by Gary on 2016/11/5.
 */

public class ActivityPremiumAdvertisement extends AppCompatActivity {
    private AdvertisementAdapt mAdvertisementAdapt;
    private Context mContext;
    private ViewPager mViewPager;
    private CirclePageIndicator mCirclePageIndicator;
    private RelativeLayout advertisementContainer;
    private ImageView close;

    private RelativeLayout drawlayoutTitleContent;
    private TextView drawlayoutTitle;
    private RelativeLayout drawlatoutContent;
    private DrawerLayout drawlayout;
    private RecyclerView drawRecycleView;
    private ActivityPremiumAdvertisementDrawlayoutRecycleViewAdapt drawlayoutAdapt;
    private ActivityPremiumAdvertisementDrawlayoutRecycleViewAdapt.DataStructure drawlayoutData;
    private AccountInjection accountInjection;
    private String registeredState;
    private PremiumAdvertisementController controller;
    private AdvertisementAdapt.DataStructure data;
    private String specialCheck;
    private String premiumCheck;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_advertisement);
        mContext = this;

        FragmentLauncher.replace(mContext, R.id.content_container, null, FragmentPremiumMain.class);
        finView();
        initSystemFont();
        init();
    }

    private void finView() {
        mViewPager = (ViewPager) findViewById(R.id.advertisement_viewpager);
        mCirclePageIndicator = (CirclePageIndicator) findViewById(R.id.advertisement_indicator);
        advertisementContainer = (RelativeLayout) findViewById(R.id.advertisement_container);
        close = (ImageView) findViewById(R.id.advertisement_close);

        drawlatoutContent = (RelativeLayout) findViewById(R.id.advertisement_drawlayout_content);
        drawlayout = (DrawerLayout) findViewById(R.id.advertisement_drawlayout);
        drawRecycleView = (RecyclerView) findViewById(R.id.advertisement_recycle);
        drawlayoutTitleContent = (RelativeLayout) findViewById(R.id.advertisement_drawlayout_title_content);
        drawlayoutTitle = (TextView) findViewById(R.id.advertisement_title);
    }

    private void init() {
        specialCheck = "";
        premiumCheck = "";
        controller = new PremiumAdvertisementController(mContext);
        accountInjection = new AccountInjection(mContext);
        mAdvertisementAdapt = new AdvertisementAdapt(this);
        data = new AdvertisementAdapt.DataStructure(mContext);
        mViewPager.setAdapter(mAdvertisementAdapt);
        mCirclePageIndicator.setViewPager(mViewPager);

        close.setOnClickListener(closeClick);
        controller.setCallBackEvent(callBackEvent);
        controller.advertisementRequest();
        drawlayoutTitleContent.setOnClickListener(drawlayoutTitleClick);

        checkState();
        setDrawlayout();
        setrecyclerAdapter();
    }

    private void checkState() {
        controller.checkStateRequest();
    }


    private void setDrawlayout() {
        drawlayoutTitleContent.setBackgroundColor(0xFFCE121B);
        drawlayoutTitle.setText(R.string.preferential);
        recyclerAdapterState();
        drawlayoutAdapt.setItemClickListener(new ActivityPremiumAdvertisementDrawlayoutRecycleViewAdapt.itemClickListener() {
            @Override
            public void onClick(int position, View view, ArrayList<Boolean> stateGroup) {
                if (!(boolean) view.getTag()) {
                    drawlayout.closeDrawers();
                    changePage(position);
                    Collections.fill(stateGroup, false);
                    if (!(position == 0 || position == 5 || position == 6 || position == 7 || position == 9)) {
                        stateGroup.set(position, !stateGroup.get(position));
                    }
                    backToMainState = true;
                }
                drawlayout.closeDrawers();
            }
        });
    }

    private void setrecyclerAdapter() {
        LinearLayoutManager linearManager = new LinearLayoutManager(mContext);
        linearManager.setOrientation(LinearLayoutManager.VERTICAL);
        drawRecycleView.setLayoutManager(linearManager);

    }

    private void changePage(int position) {
        switch (position) {
            case 0:
                controller.logoutRequest();
                break;
            case 1:
                goToSetting();
                break;
            case 2:
                goToNews();
                break;
            case 3:
                goToActivity();
                break;
            case 4:
                goToPoint();
                break;
            case 5:
                goToAdvertiseApply();
                break;
            case 6:
                goToIntroduction();
                break;
            case 7:
                goToCustomerService();
                break;
            case 8:
                goToNormal();
                break;
            case 9:
                goToSpecialPage();
                break;

        }
    }

    private void goToSpecialPage() {
        if (!specialCheck.equals("1")) {
            if (registeredState.equals("0") || registeredState.equals("2") || registeredState.equals("4") || registeredState.equals("6")) {
                goToSpecialApply();
            } else {
                goToSpecial();
            }
        }
    }

    private void recyclerAdapterState() {
        registeredState = accountInjection.loadRegisteredState();
        if (registeredState.equals("2") || registeredState.equals("0") || registeredState.equals("4") || registeredState.equals("6")) {
            ArrayList<String> itemNameGroup = new ArrayList<>(Arrays.asList(mContext.getResources().getStringArray(R.array.list_premium_nonspecial)));
            drawlayoutData = new ActivityPremiumAdvertisementDrawlayoutRecycleViewAdapt.DataStructure(itemNameGroup);
            drawlayoutAdapt = new ActivityPremiumAdvertisementDrawlayoutRecycleViewAdapt(mContext, drawlayoutData);
            drawlayoutAdapt.notifyDataSetChanged();
            drawRecycleView.setAdapter(drawlayoutAdapt);
        } else {
            ArrayList<String> itemNameGroup = new ArrayList<>(Arrays.asList(mContext.getResources().getStringArray(R.array.list_premium)));
            drawlayoutData = new ActivityPremiumAdvertisementDrawlayoutRecycleViewAdapt.DataStructure(itemNameGroup);
            drawlayoutAdapt = new ActivityPremiumAdvertisementDrawlayoutRecycleViewAdapt(mContext, drawlayoutData);
            drawlayoutAdapt.notifyDataSetChanged();
            drawRecycleView.setAdapter(drawlayoutAdapt);
        }
    }

    public void setAdvertisementEnable(Boolean state) {
        if (state) {
            advertisementContainer.setVisibility(View.VISIBLE);
        } else {
            advertisementContainer.setVisibility(View.GONE);
        }
    }

    private View.OnClickListener closeClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            advertisementContainer.setVisibility(View.GONE);
        }
    };

    /**
     * 如果還有 Fragment 可以返回則不退出，
     * 如要退出時會檢查是否顯示確認視窗。
     */
    @Override
    public void onBackPressed() {
        FragmentManager manager = getSupportFragmentManager();
        if (manager.getBackStackEntryCount() == 0) {
            CheckExitDialog dialog = new CheckExitDialog(ActivityPremiumAdvertisement.this);
            dialog.show();
            drawlayout.closeDrawers();

        } else {
            setDrawlayout();
            manager.popBackStack();
            drawlayout.closeDrawers();
        }
    }

    public DrawerLayout getDrawlayout() {
        return drawlayout;
    }

    public RelativeLayout getDrawlatoutContent() {
        return drawlatoutContent;
    }

    private void initSystemFont() {
        Resources res = getResources();
        Configuration config = new Configuration();
        config.setToDefaults();
        res.updateConfiguration(config, res.getDisplayMetrics());
        float scale = getResources().getConfiguration().fontScale;
        Log.e("scale", scale + "");
    }


    public void goToSetting() {
        setAdvertisementEnable(true);
        FragmentLauncher.changeToBack(mContext, R.id.content_container, null, FragmentPremiumSetting.class.getName());
    }


    public void goToNews() {
        setAdvertisementEnable(true);
        FragmentLauncher.changeToBack(mContext, R.id.content_container, null, FragmentPremiumNews.class.getName());
    }

    public void goToActivity() {
        setAdvertisementEnable(true);
        FragmentLauncher.changeToBack(mContext, R.id.content_container, null, FragmentPremiumActivity.class.getName());
    }

    public void goToPoint() {
        setAdvertisementEnable(true);
        FragmentLauncher.changeToBack(mContext, R.id.content_container, null, FragmentPremiumPoint.class.getName());
    }

    public void goToScan() {
        setAdvertisementEnable(false);
        FragmentLauncher.changeToBack(mContext, R.id.content_container, null, FragmentPremiumQrcode.class.getName());

    }

    public void goToIntroduction() {
        controller.introductionRequest();
    }

    public void goToCustomerService() {
        controller.customerServiceRequest();
    }

    public void goToAdvertiseApply() {
        controller.advertiseApplyRequest();
    }

    public void goToSpecialApply() {
        Intent intent = new Intent(mContext, ActivitySpecialRegister.class);
        startActivity(intent);
    }

    public void goToSpecial() {
        ActivityLauncher.go(mContext, ActivitySpecialAdvertisement.class, null);
        finish();
    }

    public void goToNormal() {
        Bundle bundle = new Bundle();
        ActivityLauncher.go(mContext, ActivityNormalAdvertisement.class, bundle);
        finish();
    }

    private PremiumAdvertisementController.CallBackEvent callBackEvent = new PremiumAdvertisementController.CallBackEvent() {
        @Override
        public void onError() {
            setAdvertisementEnable(false);
        }

        @Override
        public void onSuccess(ApiV1AdveriseShowGetData information) {
            data = new AdvertisementAdapt.DataStructure(mContext);
            data.advertisementIdGroup = information.idGroup;
            data.advertisementAddressGroup = information.urlGroup;
            mAdvertisementAdapt.setData(data);
        }

        @Override
        public void onSuccessLogout(ApiV1PushUnregisterData information) {
            if (information.result == 0) {
                ActivityLauncher.go(mContext, ActivityLogin.class, null);
                accountInjection.clear();
                accountInjection.save(AccountConst.KEY_IS_KEEP_LOGIN, false);
                finish();
            } else {
                String content = getString(R.string.request_load_fail);
                Toast.makeText(mContext, content, Toast.LENGTH_LONG).show();
            }
        }

        @Override
        public void onSuccessIntroduction(ApiV1UserInstructionGetData information) {
            if (information.result == 0) {
                Intent intent = new Intent(Intent.ACTION_VIEW, Uri.parse(information.url.trim()));
                startActivity(intent);
            }
        }

        @Override
        public void onSuccessCustomerService(ApiV1UserCustomerServiceGetData information) {
            if (information.result == 0) {
                Intent intent = new Intent(Intent.ACTION_VIEW, Uri.parse(information.url.trim()));
                startActivity(intent);
            }
        }

        @Override
        public void onSuccessAdvertiseApply(ApiV1PublishPostData information) {
            if (information.result == 0) {
                Intent intent = new Intent(Intent.ACTION_VIEW, Uri.parse(information.url.trim()));
                startActivity(intent);
            }
        }

        @Override
        public void onSuccessCheckState(ApiV1CheckUserIdentityGetData information) {
            if (information.result == 0) {
                specialCheck = information.special;
                premiumCheck = information.preferential;
            }
        }
    };

    public AccountInjection getAccountInjection() {
        return this.accountInjection;
    }

    private View.OnClickListener drawlayoutTitleClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            FragmentManager manager = getSupportFragmentManager();
            int backStackCount = manager.getBackStackEntryCount();
            for (int i = 0; i < backStackCount; i++) {
                int backStackId = getSupportFragmentManager().getBackStackEntryAt(i).getId();
                manager.popBackStack(backStackId, FragmentManager.POP_BACK_STACK_INCLUSIVE);
            }
            drawlayout.closeDrawers();
        }
    };
}
