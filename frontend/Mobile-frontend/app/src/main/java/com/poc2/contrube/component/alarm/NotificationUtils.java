package com.poc2.contrube.component.alarm;

import android.app.Notification;
import android.app.NotificationChannel;
import android.app.NotificationManager;
import android.app.PendingIntent;
import android.content.Context;
import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.graphics.Color;
import android.media.RingtoneManager;
import android.os.Build;
import android.support.v4.app.NotificationCompat;

import com.poc2.R;


/**
 * Created by 依杰 on 2017/12/26.
 */

public class NotificationUtils {
    private NotificationUtils() {
    }

    public static void setNotification(Context context, String title, String message, Intent intent, String type) {
        NotificationManager mNotificationManager =
                (NotificationManager) context.getSystemService(Context.NOTIFICATION_SERVICE);

        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.O) {
            NotificationChannel channel = new NotificationChannel("1", title, NotificationManager.IMPORTANCE_HIGH);
            channel.setDescription(message);
            channel.enableLights(true);
            channel.enableVibration(true);
            mNotificationManager.createNotificationChannel(channel);
        }

        long[] vibrate_effect = {1000, 1000, 1000, 1000, 1000};
        Bitmap myBitmap = BitmapFactory.decodeResource(context.getResources(), R.mipmap.ic_launcher);

        NotificationCompat.Builder notificationBuilder = new NotificationCompat.Builder(context, null)
                .setSmallIcon(R.drawable.ic_stat_name)
                .setContentTitle(title)
                .setContentText(message)
                .setLargeIcon(myBitmap)
                .setAutoCancel(true)
                .setVibrate(vibrate_effect)
                .setSound(RingtoneManager.getDefaultUri(RingtoneManager.TYPE_NOTIFICATION))
                .setColor(Color.TRANSPARENT)
                .setPriority(Notification.PRIORITY_HIGH)
                .setChannelId("1")
                .setContentIntent(PendingIntent.getActivity(context, 0, intent,
                        PendingIntent.FLAG_UPDATE_CURRENT));

        mNotificationManager.notify(0, notificationBuilder.build());
    }
}
