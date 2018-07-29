package com.poc2.contrube.component.alarm;

import android.app.AlarmManager;
import android.app.PendingIntent;
import android.content.Context;
import android.content.Intent;


import java.util.Calendar;


/**
 * Created by 依杰 on 2017/12/26.
 */

public class AlarmManagerUtils {
    private AlarmManagerUtils() {
    }

    public static void setAlaramManager(Context context, int requestCode, String key, String message, Calendar calendar) {
        Intent intentAlarm = new Intent(context, AlarmBroadCastReceiver.class);

        if (key != null && !(key.isEmpty())) {
            intentAlarm.putExtra(key, message);
        }

        PendingIntent pendingIntent = PendingIntent.getBroadcast(context, requestCode, intentAlarm, PendingIntent.FLAG_ONE_SHOT);

        AlarmManager alarmManager = (AlarmManager) context.getSystemService(Context.ALARM_SERVICE);

        alarmManager.set(AlarmManager.RTC_WAKEUP, calendar.getTimeInMillis(), pendingIntent);
    }

    public static Calendar addAlarmCalendar() {
        Calendar calendar = Calendar.getInstance();
        calendar.add(Calendar.SECOND, 0);
        return calendar;
    }

}
