package com.poc2.contrube.model.audio;

import android.app.Service;
import android.content.Context;
import android.media.AudioManager;
import android.os.Vibrator;
import android.support.v7.widget.SwitchCompat;
import android.widget.CompoundButton;
import android.widget.SeekBar;

public class AudioHelper {
    private AudioManager audioManager;
    private Vibrator vibrator;
    private Boolean shockState;
    private SeekBar seekBar;
    private SwitchCompat shock;

    public AudioHelper(Context context) {
        audioManager = (AudioManager) context.getSystemService(Context.AUDIO_SERVICE);
        vibrator = (Vibrator) context.getSystemService(Service.VIBRATOR_SERVICE);
        shockState = audioManager.getRingerMode() == 1 ? true : false;
    }

    public AudioHelper(Context context, SeekBar seekBar) {
        this(context);
        this.seekBar = seekBar;
    }

    public AudioHelper(Context context, SeekBar seekBar, SwitchCompat shock) {
        this(context);
        this.seekBar = seekBar;
        this.shock = shock;
    }

    public void setVolume() {
        seekBar.setOnSeekBarChangeListener(new SeekBar.OnSeekBarChangeListener() {
            @Override
            public void onProgressChanged(SeekBar seekBar, int progress, boolean fromUser) {
                if (!shockState) {
                    audioManager.setStreamVolume(AudioManager.STREAM_SYSTEM, progress, AudioManager.FLAG_PLAY_SOUND);
                }
                if (progress > 0) {
                    shock.setChecked(false);
                }
            }

            @Override
            public void onStartTrackingTouch(SeekBar seekBar) {
            }

            @Override
            public void onStopTrackingTouch(SeekBar seekBar) {
            }
        });
    }

    public void setShock() {
        shock.setChecked(shockState);
        shock.setOnCheckedChangeListener(new CompoundButton.OnCheckedChangeListener() {
            @Override
            public void onCheckedChanged(CompoundButton buttonView, boolean isChecked) {
                if (isChecked) {
                    audioManager.setRingerMode(AudioManager.RINGER_MODE_VIBRATE);
                    audioManager.setStreamVolume(AudioManager.STREAM_SYSTEM, 0, AudioManager.FLAG_PLAY_SOUND);
                    vibrator.vibrate(500);
                } else {
                    audioManager.setRingerMode(AudioManager.RINGER_MODE_NORMAL);
                }
                shockState = isChecked;
            }
        });
    }
}