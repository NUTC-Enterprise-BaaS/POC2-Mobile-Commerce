package com.poc2.contrube.component.alarm;

import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.Intent;

import com.poc2.component.pre.PreferencesHelperImp;
import com.poc2.contrube.view.normal.ActivityNormalAdvertisement;

import java.text.SimpleDateFormat;
import java.util.Date;

/**
 * Created by 依杰 on 2017/12/26.
 */

public class AlarmBroadCastReceiver extends BroadcastReceiver {
    public static final String ALARM_KEY_AFTER = "ALARM_KEY_AFTER";
    public static final String ALARM_AFTER = "ALARM_AFTER";
    private PreferencesHelperImp mPreferencesHelperImp;
    @Override
    public void onReceive(Context context, Intent intent) {
        mPreferencesHelperImp = new PreferencesHelperImp(context);
        if (null != intent.getExtras()) {
            if (null != intent.getExtras().getString(ALARM_KEY_AFTER)) {
                mPreferencesHelperImp.setIsTransAction(false);
                NotificationUtils.setNotification(context, "行動電商A紅利數交換平台", "交換點數完成", getIntent(context), "meal");
            }
        }
    }

    private Intent getIntent(Context context) {
        Intent intent = new Intent(context, ActivityNormalAdvertisement.class);
        intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK
                | Intent.FLAG_ACTIVITY_CLEAR_TASK);
        return intent;
    }

}
