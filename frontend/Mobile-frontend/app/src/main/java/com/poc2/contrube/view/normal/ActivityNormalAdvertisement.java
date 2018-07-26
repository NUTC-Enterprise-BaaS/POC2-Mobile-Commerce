package com.poc2.contrube.view.normal;

import android.content.ComponentName;
import android.content.Context;
import android.content.Intent;
import android.content.ServiceConnection;
import android.content.res.Configuration;
import android.content.res.Resources;
import android.net.Uri;
import android.os.Bundle;
import android.os.IBinder;
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
import com.androidlibrary.module.backend.data.ApiV1NormalLDAPLoginPostData;
import com.androidlibrary.module.backend.data.ApiV1NormalStoreListGetData;
import com.androidlibrary.module.backend.data.ApiV1PushUnregisterData;
import com.androidlibrary.module.backend.data.ApiV1UserCustomerServiceGetData;
import com.androidlibrary.module.backend.data.ApiV1UserInstructionGetData;
import com.androidlibrary.module.backend.params.AccountInjection;
import com.androidlibrary.module.consts.AccountConst;
import com.google.android.gms.common.ConnectionResult;
import com.google.android.gms.common.GoogleApiAvailability;
import com.poc2.R;
import com.poc2.component.notification.StorePromotionsNotification;
import com.poc2.component.pre.PreferencesHelperImp;
import com.poc2.contrube.controllor.advertisement.NormalAdvertisementController;
import com.poc2.contrube.core.ActivityLauncher;
import com.poc2.contrube.core.FragmentLauncher;
import com.poc2.contrube.model.adapter.ActivityNormalAdvertisementDrawlayoutRecycleViewAdapt;
import com.poc2.contrube.model.adapter.AdvertisementAdapt;
import com.poc2.contrube.service.RegistrationIntentService;
import com.poc2.contrube.service.TokenGetService;
import com.poc2.contrube.view.guest.ActivityLogin;
import com.poc2.contrube.view.premium.ActivityPremiumAdvertisement;
import com.poc2.contrube.view.premium.ActivityPremiumRegister;
import com.poc2.contrube.view.special.ActivitySpecialAdvertisement;
import com.viewpagerindicator.CirclePageIndicator;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.Collections;


/**
 * Created by Gary on 2016/11/5.
 */

public class ActivityNormalAdvertisement extends AppCompatActivity {
    /**
     * Google Service Resolution result
     */
    private static final int PLAY_SERVICES_RESOLUTION_REQUEST = 9000;

    private AdvertisementAdapt mAdvertisementAdapt;
    private Context mContext;
    private ViewPager mViewPager;
    private CirclePageIndicator mCirclePageIndicator;
    private RelativeLayout advertisementContainer;
    private ImageView close;
    private NormalAdvertisementController controller;
    private AdvertisementAdapt.DataStructure data;
    public static String lat = "";
    public static String lng = "";
//    private GoogleMapModel googleMapModel;

    private RelativeLayout drawlayoutTitleContent;
    private TextView drawlayoutTitle;
    private RelativeLayout drawlatoutContent;
    private DrawerLayout drawlayout;
    private RecyclerView drawRecycleView;
    private ActivityNormalAdvertisementDrawlayoutRecycleViewAdapt drawlayoutAdapt;
    private ActivityNormalAdvertisementDrawlayoutRecycleViewAdapt.DataStructure drawlayoutData;
    private AccountInjection accountInjection;
    private String registeredState;
    private Intent tokenService;
    private Intent gcmService;
    private RegistrationIntentService.MyBinder myBinder;
    public static boolean backToMainState = false;
    private String specialCheck;
    private String premiumCheck;
    private StorePromotionsNotification storePromotions;
    private PreferencesHelperImp mPreferencesHelperImp;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_advertisement);
        mContext = this;
//        googleMapModel = new GoogleMapModel(mContext);
        FragmentLauncher.replace(mContext, R.id.content_container, null, FragmentNormalMain.class);
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
        accountInjection = new AccountInjection(mContext);
        controller = new NormalAdvertisementController(mContext);
        data = new AdvertisementAdapt.DataStructure(mContext);
        mAdvertisementAdapt = new AdvertisementAdapt(this);
        mViewPager.setAdapter(mAdvertisementAdapt);
        mCirclePageIndicator.setViewPager(mViewPager);
        close.setOnClickListener(closeClick);
        controller.setCallBackEvent(callBackEvent);
        controller.advertisementRequest();
        drawlayoutTitleContent.setOnClickListener(drawlayoutTitleClick);
        TokenGetServiceOn();
        checkState();
        setDrawlayout();
        setRecyclerAdapter();
        storePromotions = new StorePromotionsNotification(this);
        mPreferencesHelperImp = new PreferencesHelperImp(ActivityNormalAdvertisement.this);
        if (checkPlayServices()) {
            /** Start IntentService to register this application with GCM.*/
            gcmService = new Intent(this, RegistrationIntentService.class);
            startService(gcmService);
            bindService(gcmService, connection, BIND_AUTO_CREATE);
        }
    }

    public void onPurchased() {
        controller.loginLDAP(mPreferencesHelperImp.getAccount(), mPreferencesHelperImp.getPassword());
    }

    private void checkState() {
        controller.checkStateRequest();
    }

    private ServiceConnection connection = new ServiceConnection() {

        @Override
        public void onServiceDisconnected(ComponentName name) {
        }

        @Override
        public void onServiceConnected(ComponentName name, IBinder service) {
            myBinder = (RegistrationIntentService.MyBinder) service;
            RegistrationIntentService intentService = (RegistrationIntentService) myBinder.getIntentService();
            intentService.setDeviceTokenListener(new RegistrationIntentService.DeviceTokenListener() {
                @Override
                public void deveiceTokenResult(boolean result) {
                    if (result) {
                        Bundle args = getIntent().getExtras();
                        setRecommend(args);
                    }
                }
            });
        }
    };

    /**
     * Check the device to make sure it has the Google Play Services APK. If
     * it doesn't, display a dialog that allows users to download the APK from
     * the Google Play Store or enable it in the device's system settings.
     */

    private boolean checkPlayServices() {
        GoogleApiAvailability apiAvailability = GoogleApiAvailability.getInstance();
        int resultCode = apiAvailability.isGooglePlayServicesAvailable(this);
        if (resultCode != ConnectionResult.SUCCESS) {
            if (apiAvailability.isUserResolvableError(resultCode)) {
                apiAvailability.getErrorDialog(this, resultCode, PLAY_SERVICES_RESOLUTION_REQUEST)
                        .show();
            } else {
                Log.i("checkPlayServices", "This device is not supported.");
            }
            return false;
        }
        return true;
    }

    private void setRecommend(Bundle args) {
        if (args.getString("recommendId") != null && args.getString("from") != null) {
            String recommendId = args.getString("recommendId");
            String from = args.getString("from");
            switch (from) {
                case "general":
                    controller.generalRecommendRequest(recommendId);
                    break;

                case "special":
//                    controller.specialRecommendRequest(recommendId);
                    break;

                case "premium":
//                    controller.premiumRecommendRequest(recommendId);
                    break;
            }
        }
    }

    private void TokenGetServiceOn() {
        tokenService = new Intent(mContext, TokenGetService.class);
        startService(tokenService);
    }

    private void setDrawlayout() {
        drawlayoutTitleContent.setBackgroundColor(0xFF036EB8);
        drawlayoutTitle.setText(R.string.general);
        recyclerAdapterState();
        drawRecycleView.setAdapter(drawlayoutAdapt);
        drawlayoutAdapt.setItemClickListener(new ActivityNormalAdvertisementDrawlayoutRecycleViewAdapt.itemClickListener() {
            @Override
            public void onClick(int position, View view, ArrayList<Boolean> stateGroup) {
                if (!(boolean) view.getTag()) {
                    drawlayout.closeDrawers();
                    changePage(position);
                    Collections.fill(stateGroup, false);
                    if (!(position == 0 || position == 7 || position == 8 || position == 9 || position == 10)) {
                        stateGroup.set(position, !stateGroup.get(position));
                    }
                    backToMainState = true;
                }
                drawlayout.closeDrawers();
            }
        });
    }


    private void setRecyclerAdapter() {
        LinearLayoutManager linearManager = new LinearLayoutManager(mContext);
        linearManager.setOrientation(LinearLayoutManager.VERTICAL);
        drawRecycleView.setLayoutManager(linearManager);
    }

    private void recyclerAdapterState() {
        registeredState = accountInjection.loadRegisteredState();
        if (registeredState.equals("0") || registeredState.equals("4")) {
            ArrayList<String> itemNameGroup = new ArrayList<>(Arrays.asList(mContext.getResources().getStringArray(R.array.list)));
            drawlayoutData = new ActivityNormalAdvertisementDrawlayoutRecycleViewAdapt.DataStructure(itemNameGroup);
            drawlayoutAdapt = new ActivityNormalAdvertisementDrawlayoutRecycleViewAdapt(mContext, drawlayoutData);
            drawlayoutAdapt.notifyDataSetChanged();
            drawRecycleView.setAdapter(drawlayoutAdapt);
        } else if (registeredState.equals("1") || registeredState.equals("5")) {
            ArrayList<String> itemNameGroup = new ArrayList<>(Arrays.asList(mContext.getResources().getStringArray(R.array.list1)));
            drawlayoutData = new ActivityNormalAdvertisementDrawlayoutRecycleViewAdapt.DataStructure(itemNameGroup);
            drawlayoutAdapt = new ActivityNormalAdvertisementDrawlayoutRecycleViewAdapt(mContext, drawlayoutData);
            drawlayoutAdapt.notifyDataSetChanged();
            drawRecycleView.setAdapter(drawlayoutAdapt);
        } else if (registeredState.equals("2") || registeredState.equals("6")) {
            ArrayList<String> itemNameGroup = new ArrayList<>(Arrays.asList(mContext.getResources().getStringArray(R.array.list2)));
            drawlayoutData = new ActivityNormalAdvertisementDrawlayoutRecycleViewAdapt.DataStructure(itemNameGroup);
            drawlayoutAdapt = new ActivityNormalAdvertisementDrawlayoutRecycleViewAdapt(mContext, drawlayoutData);
            drawlayoutAdapt.notifyDataSetChanged();
            drawRecycleView.setAdapter(drawlayoutAdapt);
        } else {
            ArrayList<String> itemNameGroup = new ArrayList<>(Arrays.asList(mContext.getResources().getStringArray(R.array.list3)));
            drawlayoutData = new ActivityNormalAdvertisementDrawlayoutRecycleViewAdapt.DataStructure(itemNameGroup);
            drawlayoutAdapt = new ActivityNormalAdvertisementDrawlayoutRecycleViewAdapt(mContext, drawlayoutData);
            drawlayoutAdapt.notifyDataSetChanged();
            drawRecycleView.setAdapter(drawlayoutAdapt);
        }
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
                goToSearchStore();
                break;
            case 3:
                goToScan();
                break;
            case 4:
                goToNews();
                break;
            case 5:
                goToActivity();
                break;
            case 6:
                goToPoint();
                break;
            case 7:
                goToIntroduction();
                break;
            case 8:
                goToCustomerService();
                break;
            case 9:
                goToSpecialPage();
                break;
            case 10:
                goToPremiumPage();
                break;
        }
    }

    public void goToSpecialPage() {
        if (!specialCheck.equals("1")) {
            if (registeredState.equals("0") || registeredState.equals("2") || registeredState.equals("4") || registeredState.equals("6")) {
                goToSpecialApply();
            } else {
                goToSpecial();
            }
        }
    }

    public void goToPremiumPage() {
        if (!premiumCheck.equals("1")) {
            if (registeredState.equals("0") || registeredState.equals("1") || registeredState.equals("4") || registeredState.equals("5")) {
                goToPremiumApply();
            } else {
                goToPremium();
            }
        }
    }

    public void goToSetting() {
        setAdvertisementEnable(true);
        FragmentLauncher.changeToBack(mContext, R.id.content_container, null, FragmentNormalSetting.class.getName());
    }

    public void goToSearchStore() {
        setAdvertisementEnable(true);
        FragmentLauncher.changeToBack(mContext, R.id.content_container, null, FragmentNormalBrowseStore.class.getName());
    }

    public void goToScan() {
        ActivityLauncher.go(mContext, ActivityQrcode.class, null);
    }

    public void goToNews() {
        setAdvertisementEnable(true);
        FragmentLauncher.changeToBack(mContext, R.id.content_container, null, FragmentNews.class.getName());
    }

    public void goToLoginBlockChain() {
        setAdvertisementEnable(true);
        FragmentLauncher.changeToBack(mContext, R.id.content_container, null, FragmentGCMRecord.class.getName());
    }

    public void goToActivity() {
        setAdvertisementEnable(true);
        FragmentLauncher.changeToBack(mContext, R.id.content_container, null, FragmentNormalLoginBlockChain.class.getName());
    }

    public void goToLdap() {
        setAdvertisementEnable(true);
        FragmentLauncher.changeToBack(mContext, R.id.content_container, null, FragmentNormalLdap.class.getName());
    }

    public void goToPoint() {
        setAdvertisementEnable(true);
        FragmentLauncher.changeToBack(mContext, R.id.content_container, null, FragmentNormalPoint.class.getName());
    }

    public void goToIntroduction() {
        controller.introductionRequest();
    }

    public void goToCustomerService() {
        controller.customerServiceRequest();
    }

    public void goToSpecial() {
        ActivityLauncher.go(mContext, ActivitySpecialAdvertisement.class, null);
        finish();
    }

    public void goToPremium() {
        ActivityLauncher.go(mContext, ActivityPremiumAdvertisement.class, null);
        finish();
    }

    public void goToSpecialApply() {
        setAdvertisementEnable(true);
        FragmentLauncher.changeToBack(mContext, R.id.content_container, null, FragmentNormalCoupon.class.getName());
//        Intent intent = new Intent(mContext, ActivitySpecialRegister.class);
//        startActivity(intent);
    }

    public void goToPremiumApply() {
        Intent intent = new Intent(mContext, ActivityPremiumRegister.class);
        startActivity(intent);
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

    private NormalAdvertisementController.CallBackEvent callBackEvent = new NormalAdvertisementController.CallBackEvent() {
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
        public void onSuccessCheckState(ApiV1CheckUserIdentityGetData information) {
            if (information.result == 0) {
                specialCheck = information.special;
                premiumCheck = information.preferential;
            }
        }

        @Override
        public void onSuccess(ApiV1NormalStoreListGetData information) {
            onBackPressed();
            if (information.storeNameGroup.size() == 1) {
                goToLdap();
            } else {
                goToLoginBlockChain();
            }
        }

        @Override
        public void onSuccess(ApiV1NormalLDAPLoginPostData information) {
            controller.checkLdapState();
            mPreferencesHelperImp.setLDAPToken(information.token);
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
            CheckExitDialog dialog = new CheckExitDialog(ActivityNormalAdvertisement.this);
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

    /**
     * 如不註銷 Receiver，會產生記憶體洩漏錯誤。
     */
    @Override
    public void onDestroy() {
        super.onDestroy();
        if (tokenService != null) {
            stopService(tokenService);
        }

        if (gcmService != null) {
            stopService(gcmService);
        }

        if (connection != null) {
            unbindService(connection);
        }

    }

    public void onStop() {
//        if (googleMapModel != null) {
//            googleMapModel.stopConnect();
//        }
        super.onStop();
    }

    public AccountInjection getAccountInjection() {
        return this.accountInjection;
    }

    private void initSystemFont() {
        Resources res = getResources();
        Configuration config = new Configuration();
        config.setToDefaults();
        res.updateConfiguration(config, res.getDisplayMetrics());
        float scale = getResources().getConfiguration().fontScale;
        Log.e("scale", scale + "");
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
