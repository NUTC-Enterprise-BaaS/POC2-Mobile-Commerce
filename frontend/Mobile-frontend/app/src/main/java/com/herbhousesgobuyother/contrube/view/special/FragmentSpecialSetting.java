package com.herbhousesgobuyother.contrube.view.special;

import android.content.Context;
import android.content.Intent;
import android.content.IntentFilter;
import android.content.pm.PackageManager;
import android.media.AudioManager;
import android.net.Uri;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v4.app.Fragment;
import android.support.v7.widget.SwitchCompat;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.RelativeLayout;
import android.widget.SeekBar;
import android.widget.TextView;

import com.androidlibrary.module.backend.data.ApiV1FeedbBackPostData;
import com.androidlibrary.module.backend.data.ApiV1UserCheckVersionPostData;
import com.herbhousesgobuyother.R;
import com.herbhousesgobuyother.contrube.broadcastreceiver.AudioBroadcastReceiver;
import com.herbhousesgobuyother.contrube.controllor.setting.SpecialSettingController;
import com.herbhousesgobuyother.contrube.core.FragmentLauncher;
import com.herbhousesgobuyother.contrube.model.version.VersionHelper;

/**
 * Created by Lucas on 2016/11/1.
 */

public class FragmentSpecialSetting extends Fragment {
    private RelativeLayout shock;
    private SwitchCompat shockSwitch;
    private TextView basic;
    private TextView changePwd;
    private TextView updateCheck;
    private TextView version;
    private TextView feedback;
    private SeekBar seekBar;

    private SpecialSettingController controller;
    private AudioBroadcastReceiver audioBroadcastReceiver;
    private AudioManager audio;
    private IntentFilter filter;
    private VersionHelper versionHelper;
    private View back;

    @Nullable
    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        View layout = inflater.inflate(R.layout.fragment_special_setting, container, false);
        return layout;
    }

    @Override
    public void onViewCreated(View view, Bundle savedInstanceState) {
        super.onViewCreated(view, savedInstanceState);
        findView();
        init();
    }

    private void findView() {
        seekBar = (SeekBar) getView().findViewById(R.id.voice_seekbar);
        shock = (RelativeLayout) getView().findViewById(R.id.setting_shock_contentContainer);
        shockSwitch = (SwitchCompat) getView().findViewById(R.id.setting_switch_shock);
        basic = (TextView) getView().findViewById(R.id.setting_basic);
        changePwd = (TextView) getView().findViewById(R.id.setting_change);
        updateCheck = (TextView) getView().findViewById(R.id.setting_version_update);
        version = (TextView) getView().findViewById(R.id.setting_version);
        feedback = (TextView) getView().findViewById(R.id.setting_feedback);
        back = getView().findViewById(R.id.toolbar_back_touch);
    }

    private void init() {
        try {
            versionHelper = new VersionHelper(getContext());
        } catch (PackageManager.NameNotFoundException e) {
            e.printStackTrace();
        }
        controller = new SpecialSettingController(getContext());
        controller.setNotificationAudio(seekBar, shockSwitch);
        audio = (AudioManager) getContext().getSystemService(Context.AUDIO_SERVICE);
        seekBar.setProgress(audio.getStreamVolume(AudioManager.STREAM_NOTIFICATION));
        audioBroadcastReceiver = new AudioBroadcastReceiver();
        audioBroadcastReceiver.setAudioVolmeChangeListener(volmeChange);
        filter = new IntentFilter();
        filter.addAction("android.media.VOLUME_CHANGED_ACTION");
        syncVersion();
        controller.setmCallBackEvent(callBackEvent);
        feedback.setOnClickListener(feedbackClick);
        basic.setOnClickListener(basicClick);
        changePwd.setOnClickListener(changePwdClick);
        back.setOnClickListener(backClick);
    }

    private View.OnClickListener changePwdClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            ((ActivitySpecialAdvertisement) getActivity()).setAdvertisementEnable(false);
            FragmentLauncher.changeToBack(getContext(), R.id.content_container, null, FragmentSpecialPasswordChoice.class.getName());
        }
    };

    private View.OnClickListener basicClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            ((ActivitySpecialAdvertisement) getActivity()).setAdvertisementEnable(true);
            FragmentLauncher.changeToBack(getContext(), R.id.content_container, null, FragmentSpecialBasicInformation.class.getName());
        }
    };

    private View.OnClickListener feedbackClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            controller.feedbackRequest();
        }
    };

    private SpecialSettingController.CallBackEvent callBackEvent = new SpecialSettingController.CallBackEvent() {
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
}
