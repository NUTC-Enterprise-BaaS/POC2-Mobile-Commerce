package com.herbhousesgobuyother.component.notification;

import android.app.NotificationChannel;
import android.app.NotificationManager;
import android.app.PendingIntent;
import android.content.Context;
import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.graphics.Color;
import android.media.RingtoneManager;
import android.net.Uri;
import android.os.Build;
import android.os.Bundle;
import android.support.v4.app.NotificationCompat;
import android.util.Log;

import com.android.volley.RequestQueue;
import com.android.volley.toolbox.ImageLoader;
import com.android.volley.toolbox.Volley;
import com.androidlibrary.module.BitmapCache;
import com.androidlibrary.module.backend.data.ApiV1BerecommendGetData;
import com.androidlibrary.module.backend.data.ApiV1GcmLuckyGetData;
import com.androidlibrary.module.backend.data.ApiV1GeneralPointReceivePostData;
import com.androidlibrary.module.backend.data.ApiV1ReceivePaymentGetData;
import com.androidlibrary.module.backend.data.ApiV1RecommendGetData;
import com.androidlibrary.module.backend.data.ApiV1StoreGcmSendPostData;
import com.androidlibrary.module.backend.data.ApiV1StorePushStoreSendGetData;
import com.herbhousesgobuyother.R;
import com.herbhousesgobuyother.service.ScratChcardService;
import com.herbhousesgobuyother.ui.send.SendActivity;

/**
 * Created by ameng on 2016/6/30.
 */
public class StorePromotionsNotification {
    private NotificationCompat.Builder builder;
    private Context context;
    private ImageLoader imageLoader;
    private RequestQueue imageRequestQueue;
    public static String LUCKY_TOKEN = "LUCKY_TOKEN";
    public static String PAYMENT_URL = "PAYMENT_URL";
    public static String STATE = "STATE";
    public static String CHECK_ID = "CHECK_ID";
    public static String RECEIVE_EMAIL = "RECEIVE_EMAIL";
    public static String RECEIVE_PHONE = "RECEIVE_PHONE";
    public static String RECEIVE_POINT = "RECEIVE_POINT";
    public static String SEND_EMAIL = "SEND_EMAIL";
    public static String STORE_ID = "STORE_ID";
    public static String STORE_NAME = "STORE_NAME";
    public static String STORE_PHOTO = "STORE_PHOTO";
    public static String STORE_URL = "STORE_URL";
    public static String STORE_POINT = "STORE_POINT";

    public StorePromotionsNotification(Context context) {
        this.context = context;
        this.imageRequestQueue = Volley.newRequestQueue(context);
        this.imageLoader = new ImageLoader(imageRequestQueue, new BitmapCache());
        builder = new NotificationCompat.Builder(context);
        builder.setSmallIcon(R.mipmap.ic_launcher);
        Bitmap myBitmap = BitmapFactory.decodeResource(context.getResources(), R.mipmap.ic_launcher);
        builder.setLargeIcon(myBitmap);
        // 设置通知的優先先级
        builder.setPriority(NotificationCompat.PRIORITY_MAX);
        Uri alarmSound = RingtoneManager.getDefaultUri(RingtoneManager.TYPE_NOTIFICATION);
        // 设置通知的提示音
        builder.setSound(alarmSound);
        builder.setAutoCancel(true);
        builder.setColor(Color.TRANSPARENT);
    }

    public void setBuilder(final ApiV1StoreGcmSendPostData data) {
//        builder.setLargeIcon(response.getBitmap());
        Intent intent = new Intent(Intent.ACTION_VIEW, Uri.parse("http://" + data.shopUrl.trim()));
        PendingIntent pendingIntent = PendingIntent.getActivity(context, 0, intent, PendingIntent.FLAG_UPDATE_CURRENT);
        builder.setContentTitle(data.shopName);
        builder.setContentText(data.storePromotions);
        builder.setContentIntent(pendingIntent);
        start();
//        imageLoader.get(data.shopUrl, new ImageLoader.ImageListener() {
//            @Override
//            public void onResponse(ImageLoader.ImageContainer response, boolean isImmediate) {
//                builder.setLargeIcon(response.getBitmap());
//                builder.setContentTitle(data.shopName);
//                builder.setContentText(data.storePromotions);
//                start();
//            }
//
//            @Override
//            public void onErrorResponse(VolleyError error) {
//            }
//        });
    }

    public void setBuilder(final ApiV1GcmLuckyGetData data) {
        Intent intent = new Intent();
        intent.setClass(context, ScratChcardService.class);
        intent.putExtra(LUCKY_TOKEN, data.luckyToken);
        PendingIntent pendingIntent = PendingIntent.getService(context, 0, intent, PendingIntent.FLAG_UPDATE_CURRENT);
        builder.setContentTitle("限時活動");
        builder.setContentText("即括即送紅利點數");
        builder.setContentIntent(pendingIntent);

        start();
    }

    public void setBuilder(final ApiV1GeneralPointReceivePostData data) {
        Intent intent = new Intent(context, SendActivity.class);
        Bundle bundle = new Bundle();
        bundle.putString(STATE, data.state);
        bundle.putString(CHECK_ID, data.checkId);
        bundle.putString(RECEIVE_EMAIL, data.receiveEmail);
        bundle.putString(SEND_EMAIL, data.sendEmail);
        bundle.putString(RECEIVE_PHONE, data.receivePhone);
        bundle.putString(RECEIVE_POINT, data.point);
        intent.putExtras(bundle);
        PendingIntent pendingIntent = PendingIntent.getActivity(context, 0, intent, PendingIntent.FLAG_UPDATE_CURRENT);
        builder.setContentTitle("有人送點過來了！！");
        builder.setContentText("立刻打開看看吧！");
        builder.setContentIntent(pendingIntent);

        start();
    }

    public void setBuilder(final ApiV1BerecommendGetData data) {
        builder.setContentTitle(data.data);
        start();
    }

    public void setBuilder(final ApiV1RecommendGetData data) {
        builder.setContentTitle(data.data);
        start();
    }

    public void setBuilder(final ApiV1ReceivePaymentGetData data) {
        Intent intent = new Intent(Intent.ACTION_VIEW, Uri.parse("http://" + data.url.trim()));
        PendingIntent pendingIntent = PendingIntent.getActivity(context, 0, intent, PendingIntent.FLAG_UPDATE_CURRENT);
        builder.setContentText(data.name);
        builder.setContentTitle("繳費通知");
        builder.setContentIntent(pendingIntent);

        start();
    }

    public void setBuilder(final ApiV1StorePushStoreSendGetData data) {
//        Intent intent = new Intent(context, SendActivity.class);
//        Bundle bundle = new Bundle();
//        bundle.putString(STORE_ID, data.id);
//        bundle.putString(STORE_NAME, data.name);
//        bundle.putString(STORE_PHOTO, data.photo);
//        bundle.putString(STORE_URL, data.url);
//        bundle.putString(STORE_POINT, data.point);
//        intent.putExtras(bundle);
//        PendingIntent pendingIntent = PendingIntent.getActivity(context, 0, intent, PendingIntent.FLAG_UPDATE_CURRENT);
        if (Integer.valueOf(data.point) > 0) {
            builder.setContentTitle(data.name + "送" + data.point + "點過來了！！");
            Log.e("data.point", data.point);
        } else if (Integer.valueOf(data.point) < 0) {
            builder.setContentTitle(data.name + "扣了你" + Math.abs(Integer.valueOf(data.point)) + "點！！");
            Log.e("data.point", data.point);

        }
//        builder.setContentText("立刻打開看看吧！");
//        builder.setContentIntent(pendingIntent);

        start();
    }


    public void start() {
        NotificationManager notificationManager = (NotificationManager) context.getSystemService(Context.NOTIFICATION_SERVICE);
        if (Build.VERSION.SDK_INT >= Build.VERSION_CODES.O) {
            NotificationChannel channel = new NotificationChannel("1", "行動電商A紅利數交換平台", NotificationManager.IMPORTANCE_HIGH);
            channel.setDescription("一則新訊息");
            channel.enableLights(true);
            channel.enableVibration(true);
            notificationManager.createNotificationChannel(channel);
        }
        notificationManager.notify(0, builder.build());
    }
}
