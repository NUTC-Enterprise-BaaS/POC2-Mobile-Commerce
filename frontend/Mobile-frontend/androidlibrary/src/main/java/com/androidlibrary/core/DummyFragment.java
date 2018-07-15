package com.androidlibrary.core;

import android.app.Fragment;
import android.graphics.Color;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.view.Gravity;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

/**
 * Created by chriske on 2016/1/28.
 */
public class DummyFragment extends Fragment {
    @Nullable
    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        TextView v = new TextView(getActivity());
        v.setText("Dummy fragment just for team development.");
        v.setGravity(Gravity.CENTER);
        v.setTextColor(Color.WHITE);
        v.setBackgroundColor(Color.parseColor("#FFBF00"));
        return v;
    }
}
