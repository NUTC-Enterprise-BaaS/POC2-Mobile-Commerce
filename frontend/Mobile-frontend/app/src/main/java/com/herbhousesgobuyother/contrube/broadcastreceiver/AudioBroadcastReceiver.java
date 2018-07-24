package com.herbhousesgobuyother.contrube.broadcastreceiver;

import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.Intent;
import android.media.AudioManager;
import android.util.Log;

public class AudioBroadcastReceiver extends BroadcastReceiver {
    private AudioVolmeChange audioVolmeChangeListener;

    public interface AudioVolmeChange {
        public void volmeValueChangeListener(int value);
    }

    public AudioBroadcastReceiver() {
    }

    @Override
    public void onReceive(Context context, Intent intent) {
        AudioManager audio = (AudioManager) context.getSystemService(Context.AUDIO_SERVICE);
        if (intent.getAction().equals("android.media.VOLUME_CHANGED_ACTION")) {
            int currentVolume = audio.getStreamVolume(AudioManager.STREAM_NOTIFICATION);
            audioVolmeChangeListener.volmeValueChangeListener(currentVolume);
            Log.e("VOLUME_CHANGED_ACTION", "" + currentVolume);
        }
    }

    public void setAudioVolmeChangeListener(AudioVolmeChange audioVolmeChangeListener) {
        this.audioVolmeChangeListener = audioVolmeChangeListener;
    }
}