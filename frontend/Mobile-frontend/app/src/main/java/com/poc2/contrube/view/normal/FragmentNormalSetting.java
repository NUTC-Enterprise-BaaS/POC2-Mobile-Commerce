package com.poc2.contrube.view.normal;

import android.app.AlertDialog;
import android.app.KeyguardManager;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.IntentFilter;
import android.content.pm.PackageManager;
import android.hardware.fingerprint.FingerprintManager;
import android.media.AudioManager;
import android.net.Uri;
import android.os.Build;
import android.os.Bundle;
import android.provider.Settings;
import android.support.annotation.Nullable;
import android.support.annotation.RequiresApi;
import android.support.v4.app.Fragment;
import android.support.v7.widget.SwitchCompat;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.CompoundButton;
import android.widget.RelativeLayout;
import android.widget.SeekBar;
import android.widget.TextView;
import android.widget.Toast;

import com.androidlibrary.module.backend.data.ApiV1FeedbBackPostData;
import com.androidlibrary.module.backend.data.ApiV1NormalBindingClearGetData;
import com.androidlibrary.module.backend.data.ApiV1NormalStoreListGetData;
import com.androidlibrary.module.backend.data.ApiV1UserCheckVersionPostData;
import com.poc2.R;
import com.poc2.component.pre.PreferencesHelperImp;
import com.poc2.contrube.broadcastreceiver.AudioBroadcastReceiver;
import com.poc2.contrube.controllor.setting.NormalSettingController;
import com.poc2.contrube.core.FragmentLauncher;
import com.poc2.contrube.model.SettingDataStore;
import com.poc2.contrube.model.version.VersionHelper;

/**
 * Created by Lucas on 2016/11/1.
 */

public class FragmentNormalSetting extends Fragment {
    private RelativeLayout shock;
    private SwitchCompat shockSwitch;
    private TextView basic;
    private TextView changePwd;
    private RelativeLayout hide;
    private SwitchCompat hideSwitch;
    private RelativeLayout update;
    private RelativeLayout mFingerPrintLayout;
    private TextView updateCheck;
    private TextView version;
    private TextView feedback;
    private SeekBar seekBar;
    private View back;
    private SwitchCompat fingerPrintSwitch;
    private KeyguardManager mKeyguardManager;
    private FingerprintManager mFingerprintManager;
    private RelativeLayout clearLayout;
    private NormalSettingController controller;
    private AudioBroadcastReceiver audioBroadcastReceiver;
    private AudioManager audio;
    private IntentFilter filter;
    private VersionHelper versionHelper;

    private PreferencesHelperImp mPreferencesHelperImp;

    @Nullable
    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        View layout = inflater.inflate(R.layout.fragment_setting, container, false);
        return layout;
    }

    @RequiresApi(api = Build.VERSION_CODES.M)
    @Override
    public void onViewCreated(View view, Bundle savedInstanceState) {
        super.onViewCreated(view, savedInstanceState);
        findView();
        init();
    }

    private void findView() {
        back = getView().findViewById(R.id.toolbar_back_touch);
        seekBar = (SeekBar) getView().findViewById(R.id.voice_seekbar);
        shock = (RelativeLayout) getView().findViewById(R.id.setting_shock_contentContainer);
        shockSwitch = (SwitchCompat) getView().findViewById(R.id.setting_switch_shock);
        basic = (TextView) getView().findViewById(R.id.setting_basic);
        changePwd = (TextView) getView().findViewById(R.id.setting_change);
        hide = (RelativeLayout) getView().findViewById(R.id.setting_hide_button_contentContainer);
        hideSwitch = (SwitchCompat) getView().findViewById(R.id.setting_switch_hide_button);
        fingerPrintSwitch = (SwitchCompat) getView().findViewById(R.id.setting_switch_button);

        update = (RelativeLayout) getView().findViewById(R.id.setting_version_contentContainer);
        updateCheck = (TextView) getView().findViewById(R.id.setting_version_update);
        version = (TextView) getView().findViewById(R.id.setting_version);
        feedback = (TextView) getView().findViewById(R.id.setting_feedback);
        mFingerPrintLayout = getView().findViewById(R.id.layout_finger_print);

        clearLayout = getView().findViewById(R.id.layout_clear);
    }

    @RequiresApi(api = Build.VERSION_CODES.M)
    private void init() {
        mKeyguardManager = getContext().getSystemService(KeyguardManager.class);
        mFingerprintManager = getContext().getSystemService(FingerprintManager.class);

        try {
            versionHelper = new VersionHelper(getContext());
        } catch (PackageManager.NameNotFoundException e) {
            e.printStackTrace();
        }
        controller = new NormalSettingController(getContext());
        controller.setNotificationAudio(seekBar, shockSwitch);
        audio = (AudioManager) getContext().getSystemService(Context.AUDIO_SERVICE);
        seekBar.setProgress(audio.getStreamVolume(AudioManager.STREAM_NOTIFICATION));
        audioBroadcastReceiver = new AudioBroadcastReceiver();
        audioBroadcastReceiver.setAudioVolmeChangeListener(volmeChange);
        filter = new IntentFilter();
        filter.addAction("android.media.VOLUME_CHANGED_ACTION");
        setSwitchJoinButtonEvent();
        syncVersion();
        controller.setmCallBackEvent(callBackEvent);
        feedback.setOnClickListener(feedbackClick);
        basic.setOnClickListener(basicClick);
        changePwd.setOnClickListener(changePwdClick);
        back.setOnClickListener(backClick);
        clearLayout.setOnClickListener(clearClick);

        mPreferencesHelperImp = new PreferencesHelperImp(getContext());
        fingerPrintSwitch.setChecked(mPreferencesHelperImp.getIsUseFingerPrint());
        fingerPrintSwitch.setOnCheckedChangeListener(fingerPrintClick);

        controller.checkLdapState();

        mFingerPrintLayout.setVisibility((mPreferencesHelperImp.getAccount().equals("")
                || mPreferencesHelperImp.getPassword().equals("")) ? View.GONE : View.VISIBLE);
    }

    private CompoundButton.OnCheckedChangeListener fingerPrintClick = new CompoundButton.OnCheckedChangeListener() {
        @RequiresApi(api = Build.VERSION_CODES.M)
        @Override
        public void onCheckedChanged(CompoundButton compoundButton, boolean isChecked) {
            if (!mKeyguardManager.isKeyguardSecure() || !mFingerprintManager.hasEnrolledFingerprints()) {
                fingerPrintSwitch.setChecked(false);
                new AlertDialog.Builder(getContext())
                        .setTitle(R.string.finger_print_dialog_title)
                        .setMessage(R.string.finger_print_dialog_message)
                        .setPositiveButton(R.string.finger_print_dialog_yes, new DialogInterface.OnClickListener() {
                            @Override
                            public void onClick(DialogInterface dialog, int which) {
                                Intent intent = new Intent();
                                intent.setAction(Settings.ACTION_SECURITY_SETTINGS);
                                startActivity(intent);
                            }
                        })
                        .setNeutralButton(R.string.finger_print_dialog_no, new DialogInterface.OnClickListener() {
                            @Override
                            public void onClick(DialogInterface dialog, int which) {
                                dialog.dismiss();
                            }
                        })
                        .show();
            }

            mPreferencesHelperImp.setIsUseFingerPrint(isChecked);
        }
    };

    private View.OnClickListener changePwdClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            ((ActivityNormalAdvertisement) getActivity()).setAdvertisementEnable(false);
            FragmentLauncher.changeToBack(getContext(), R.id.content_container, null, FragmentChangePassword.class.getName());
        }
    };

    private View.OnClickListener basicClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            ((ActivityNormalAdvertisement) getActivity()).setAdvertisementEnable(true);
            FragmentLauncher.changeToBack(getContext(), R.id.content_container, null, FragmentNormalBasicInformation.class.getName());
        }
    };

    private View.OnClickListener feedbackClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            controller.feedbackRequest();
        }
    };

    private NormalSettingController.CallBackEvent callBackEvent = new NormalSettingController.CallBackEvent() {
        @Override
        public void onError() {

        }

        @Override
        public void onSuccess(ApiV1UserCheckVersionPostData information) {
            if (information.messageGroup.get(0).equals("Please update APP version")) {
                updateCheck.setVisibility(View.VISIBLE);
            } else if (information.messageGroup.get(0).equals("This APP is the latest version")) {
                updateCheck.setVisibility(View.INVISIBLE);
            }
        }

        @Override
        public void onSuccess(ApiV1FeedbBackPostData information) {
            if (information.result == 0) {
                Intent intent = new Intent(Intent.ACTION_VIEW, Uri.parse(information.url.trim()));
                startActivity(intent);
            }
        }

        @Override
        public void onSuccess(ApiV1NormalBindingClearGetData information) {
            if (information.message.contains("clean"))
                Toast.makeText(getContext(), "取消轉換平台點數功能成功", Toast.LENGTH_LONG).show();
            else
                Toast.makeText(getContext(), "取消轉換平台點數功能失敗", Toast.LENGTH_LONG).show();
        }

        @Override
        public void onSuccess(ApiV1NormalStoreListGetData information) {
            if (information.storeNameGroup.size() > 1) {
                clearLayout.setVisibility(View.VISIBLE);
            } else {
                clearLayout.setVisibility(View.GONE);
            }
        }
    };

    private void syncVersion() {
        version.setText(versionHelper.getVersion());
        controller.versionRequest();
    }

    private AudioBroadcastReceiver.AudioVolmeChange volmeChange = new AudioBroadcastReceiver.AudioVolmeChange() {
        @Override
        public void volmeValueChangeListener(int value) {
            seekBar.setProgress(value);
            if (value == 0) {
                shockSwitch.setChecked(true);
            } else {
                shockSwitch.setChecked(false);
            }
        }
    };

    /**
     * 切換顯示加入其他會員的功能
     */
    public void setSwitchJoinButtonEvent() {
        final SettingDataStore dataStore = new SettingDataStore(getContext());
        boolean isShowJoinButton = dataStore.loadIsShowJoin();
        hideSwitch.setChecked(!isShowJoinButton);
        hideSwitch.setOnCheckedChangeListener(switchClick);
    }

    private CompoundButton.OnCheckedChangeListener switchClick = new CompoundButton.OnCheckedChangeListener() {
        @Override
        public void onCheckedChanged(CompoundButton compoundButton, boolean isChecked) {
            controller.setSwitchJoinButtonEvent(isChecked);
        }
    };

    @Override
    public void onResume() {
        super.onResume();
        getContext().registerReceiver(audioBroadcastReceiver, filter);
    }

    @Override
    public void onPause() {
        super.onPause();
        getContext().unregisterReceiver(audioBroadcastReceiver);
    }

    private View.OnClickListener backClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            getActivity().onBackPressed();
        }
    };

    private View.OnClickListener clearClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            new AlertDialog.Builder(getContext())
                    .setTitle("取消區塊鏈點數交換功能")
                    .setMessage("取消後，將無法繼續使用區塊鏈點數交換，是否確認取消？")
                    .setPositiveButton(R.string.finger_print_dialog_yes, new DialogInterface.OnClickListener() {
                        @Override
                        public void onClick(DialogInterface dialog, int which) {
                            controller.bindingClearRequest();
                        }
                    })
                    .setNeutralButton(R.string.finger_print_dialog_no, new DialogInterface.OnClickListener() {
                        @Override
                        public void onClick(DialogInterface dialog, int which) {
                            dialog.dismiss();
                        }
                    })
                    .show();
        }
    };
}
