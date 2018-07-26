package com.poc2.contrube.view.normal;

import android.content.Context;
import android.content.res.Configuration;
import android.content.res.Resources;
import android.os.Bundle;
import android.support.v4.content.ContextCompat;
import android.support.v7.app.AppCompatActivity;
import android.util.Log;
import android.view.View;
import android.widget.FrameLayout;
import android.widget.TextView;

import com.poc2.R;
import com.poc2.contrube.core.FragmentLauncher;

/**
 * Created by Gary on 2016/11/5.
 */

public class ActivityQrcode extends AppCompatActivity {
    private TextView mScannerFragmentButton;
    private TextView mShowQRCodeButton;
    private FrameLayout mFragmentContainer;

    private Context mContext;
    private boolean mFragmentShow;
    private View back;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_qrcode);
        mContext = this;

        findView();
        initSystemFont();

        mFragmentShow = false;
        FragmentLauncher.replace(mContext, R.id.qrcode_scanner_container, null, FragmentQRCodeScan.class);
    }

    private void findView() {
        mScannerFragmentButton = (TextView) findViewById(R.id.qrcode_show_scanner);
        mShowQRCodeButton = (TextView) findViewById(R.id.qrcode_show_recommend);
        mFragmentContainer = (FrameLayout) findViewById(R.id.qrcode_scanner_container);

        mScannerFragmentButton.setOnClickListener(setClickCallback());
        mShowQRCodeButton.setOnClickListener(setClickCallback());
        mScannerFragmentButton.setBackgroundColor(ContextCompat.getColor(mContext, R.color.ActivityQRcodePageItemBackgroundColor2));
        mShowQRCodeButton.setBackgroundColor(ContextCompat.getColor(mContext, R.color.ActivityQRcodePageItemBackgroundColor));
        back = findViewById(R.id.toolbar_back_touch);
        back.setOnClickListener(backClick);

    }

    private View.OnClickListener setClickCallback() {
        return new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                switch (v.getId()) {
                    case R.id.qrcode_show_scanner:
                        if (mFragmentShow) {
                            mScannerFragmentButton.setBackgroundColor(ContextCompat.getColor(mContext, R.color.ActivityQRcodePageItemBackgroundColor2));
                            mShowQRCodeButton.setBackgroundColor(ContextCompat.getColor(mContext, R.color.ActivityQRcodePageItemBackgroundColor));
                            FragmentLauncher.replace(mContext, R.id.qrcode_scanner_container, null, FragmentQRCodeScan.class.getName());
                            mFragmentShow = !mFragmentShow;
                        }
                        break;
                    case R.id.qrcode_show_recommend:
                        if (!mFragmentShow) {
                            mScannerFragmentButton.setBackgroundColor(ContextCompat.getColor(mContext, R.color.ActivityQRcodePageItemBackgroundColor));
                            mShowQRCodeButton.setBackgroundColor(ContextCompat.getColor(mContext, R.color.ActivityQRcodePageItemBackgroundColor2));
                            FragmentLauncher.replace(mContext, R.id.qrcode_scanner_container, null, FragmentQRCodeShow.class.getName());
                            mFragmentShow = !mFragmentShow;
                        }
                        break;
                }
            }
        };
    }
    private void initSystemFont() {
        Resources res = getResources();
        Configuration config = new Configuration();
        config.setToDefaults();
        res.updateConfiguration(config, res.getDisplayMetrics());
        float scale = getResources().getConfiguration().fontScale;
        Log.e("scale", scale + "");
    }
    private View.OnClickListener backClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            finish();
        }
    };
}
