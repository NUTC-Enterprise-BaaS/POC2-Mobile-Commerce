package com.herbhousesgobuyother.contrube.broadcastreceiver;

import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.Intent;
import android.content.IntentFilter;
import android.content.SharedPreferences;
import android.util.Log;
import android.view.View;

import com.herbhousesgobuyother.R;

public class SettingHideReceiver extends BroadcastReceiver {
    public static final String ACTION = SettingHideReceiver.class.getName();
    public Context context;
    public View layout;
    private SharedPreferences preferences;
    private final String hidePreferences = "hidePreferences";
    private final String hideState = "hideState";


    public SettingHideReceiver(Context context, View layout) {
        this.context = context;
        this.layout = layout;
    }

    @Override
    public void onReceive(Context context, Intent intent) {
        preferences = context.getSharedPreferences(hidePreferences, Context.MODE_PRIVATE);
        boolean state = preferences.getBoolean(hideState, false);
        Log.e("onReceive", state + "");

        layout.findViewById(R.id.fragment_main_special).setVisibility(state ? View.GONE : View.VISIBLE);
        layout.findViewById(R.id.fragment_main_preferential).setVisibility(state ? View.GONE : View.VISIBLE);
        layout.findViewById(R.id.fragment_main_enter_special).setVisibility(state ? View.GONE : View.VISIBLE);
        layout.findViewById(R.id.fragment_main_enter_preferential).setVisibility(state ? View.GONE : View.VISIBLE);
    }

    public void register() {
        context.registerReceiver(this, new IntentFilter(ACTION));
    }

    public static void send(Context context) {
        Intent broadcst = new Intent(ACTION);
        context.sendBroadcast(broadcst);
    }

    public void unregister() {
        context.unregisterReceiver(this);
    }
}