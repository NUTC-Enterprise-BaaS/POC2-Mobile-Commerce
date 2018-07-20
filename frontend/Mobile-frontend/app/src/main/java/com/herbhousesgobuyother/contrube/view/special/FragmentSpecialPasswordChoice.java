package com.herbhousesgobuyother.contrube.view.special;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v4.app.Fragment;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import com.herbhousesgobuyother.R;
import com.herbhousesgobuyother.contrube.core.FragmentLauncher;

/**
 * Created by 依杰 on 2016/11/19.
 */

public class FragmentSpecialPasswordChoice extends Fragment {
    private TextView memberPasswordChangeTextView;
    private TextView csvPasswordChangeTextView;
    private View back;

    @Nullable
    @Override
    public View onCreateView(LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        View layout = inflater.inflate(R.layout.fragment_special_password_choice, container, false);
        return layout;
    }

    @Override
    public void onViewCreated(View view, @Nullable Bundle savedInstanceState) {
        super.onViewCreated(view, savedInstanceState);
        findView();
        init();
    }

    private void init() {
        memberPasswordChangeTextView.setOnClickListener(changeClick);
        csvPasswordChangeTextView.setOnClickListener(csvChangeClick);
        back.setOnClickListener(backClick);
    }

    private void findView() {
        memberPasswordChangeTextView = (TextView) getView().findViewById(R.id.fragment_special_password_choice_member_change_text);
        csvPasswordChangeTextView = (TextView) getView().findViewById(R.id.fragment_special_password_choice_member_csv_change_text);
        back = getView().findViewById(R.id.toolbar_back_touch);
    }


    private View.OnClickListener changeClick = new View.OnClickListener() {
        @Override
        public void onClick(View v) {
            ((ActivitySpecialAdvertisement) getActivity()).setAdvertisementEnable(true);
            FragmentLauncher.changeToBack(getContext(), R.id.content_container, null, FragmentSpecialChangePassword.class.getName());
        }
    };

    private View.OnClickListener csvChangeClick = new View.OnClickListener() {
        @Override
        public void onClick(View v) {
            ((ActivitySpecialAdvertisement) getActivity()).setAdvertisementEnable(true);
            FragmentLauncher.changeToBack(getContext(), R.id.content_container, null, FragmentSpecialCsvChangePassword.class.getName());
        }
    };

    private View.OnClickListener backClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            getActivity().onBackPressed();
        }
    };

}