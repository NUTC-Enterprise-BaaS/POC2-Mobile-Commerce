package com.herbhousesgobuyother.contrube.view.normal;

import android.app.AlertDialog;
import android.graphics.Color;
import android.graphics.drawable.ColorDrawable;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;
import android.widget.Toast;

import com.androidlibrary.module.backend.data.ApiV1NormalStoreChangePostData;
import com.androidlibrary.module.backend.data.ApiV1NormalStoreCreateGetData;
import com.androidlibrary.module.backend.data.ApiV1NormalStoreListGetData;
import com.androidlibrary.module.backend.data.ApiV1NormalUserPointGetData;
import com.herbhousesgobuyother.R;
import com.herbhousesgobuyother.contrube.component.dialog.FragmentGCMRecordDialog;
import com.herbhousesgobuyother.contrube.controllor.gcm.FragmentGCMRecordContorllor;
import com.herbhousesgobuyother.contrube.model.adapter.FragmentGCMRecordAdapt;

import java.util.ArrayList;

/**
 * Created by ameng on 11/1/16.
 */

public class FragmentGCMRecord extends Fragment implements FragmentGCMRecordAdapt.CallBack, FragmentGCMRecordDialog.DialogEvent {
    private RecyclerView list;
    private FragmentGCMRecordContorllor controllor;
    private FragmentGCMRecordAdapt adapt;
    private FragmentGCMRecordAdapt.DataStructure mData;
    private View back;
    public ArrayList<String> titleGroup = new ArrayList<>();
    private FragmentGCMRecordDialog mFragmentGCMRecordDialog;
    private AlertDialog mFragmentGCMRecordAlertEditDialog;
    private TextView mTextToken;
    private int mPosition =0;

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        View layout = inflater.inflate(R.layout.fragment_gcm_record, container, false);
        return layout;
    }

    @Override
    public void onViewCreated(View view, Bundle savedInstanceState) {
        super.onViewCreated(view, savedInstanceState);
        findView();
        init();

    }

    private void findView() {
        back = getView().findViewById(R.id.toolbar_back_touch);
        list = (RecyclerView) getView().findViewById(R.id.fragment_gcm_record_recyclerview);
        mTextToken = getView().findViewById(R.id.text_token);
    }

    private void init() {
        controllor = new FragmentGCMRecordContorllor(getContext());
        adapt = new FragmentGCMRecordAdapt(getContext());
        mData = new FragmentGCMRecordAdapt.DataStructure();
        mFragmentGCMRecordDialog = new FragmentGCMRecordDialog(getContext());
        mFragmentGCMRecordAlertEditDialog = mFragmentGCMRecordDialog.create();
        mFragmentGCMRecordAlertEditDialog.getWindow().setBackgroundDrawable(new ColorDrawable(Color.TRANSPARENT));
        mFragmentGCMRecordDialog.setEditDialogEvent(this);
        setRecycleView();
        adapt.setClickEvent(this);
        back.setOnClickListener(backClick);

        controllor.setmCallBackEvent(callBackEvent);
        controllor.getStoreList();
    }

    private FragmentGCMRecordContorllor.CallBackEvent callBackEvent = new FragmentGCMRecordContorllor.CallBackEvent() {
        @Override
        public void onError(String message) {
            Toast.makeText(getActivity(), message, Toast.LENGTH_SHORT).show();
        }

        @Override
        public void onSuccess(ApiV1NormalStoreListGetData information, ApiV1NormalUserPointGetData pointData) {
            mTextToken.setText("您的開通序號：" + pointData.token);
            ArrayList<String> list = new ArrayList<>();
            ArrayList<String> id = new ArrayList<>();
            for (String name : information.messageGroup) {
                Log.e("onSuccess", "" + name);
                if (!pointData.store.equals(name)) {
                    if (name.equals("HappyBuy")) {
                        list.add("行動電商B紅利數交換平台");
                    }
                    id.add(name);
                }
            }
            update(list, id);
        }

        @Override
        public void onSuccess(ApiV1NormalStoreCreateGetData information) {
            mFragmentGCMRecordDialog.getRateTitleText().setText(String.valueOf(information.rate));
            mFragmentGCMRecordAlertEditDialog.show();
        }

        @Override
        public void onSuccess(ApiV1NormalStoreChangePostData information) {
            Toast.makeText(getActivity(), "轉換成功", Toast.LENGTH_LONG).show();
            mFragmentGCMRecordAlertEditDialog.dismiss();
        }

    };

    private void update(ArrayList<String> list, ArrayList<String> id) {
        mData.titleGroup = list;
        mData.idGroup = id;
        adapt.setData(mData);
    }

    private void setRecycleView() {
        LinearLayoutManager linearManager = new LinearLayoutManager(getContext());
        linearManager.setOrientation(LinearLayoutManager.VERTICAL);
        list.setLayoutManager(linearManager);
        list.setAdapter(adapt);
    }

    private View.OnClickListener backClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            getActivity().onBackPressed();
        }
    };

    @Override
    public void onClick(int position) {
        mFragmentGCMRecordDialog.getTitleText().setText("轉換" + mData.titleGroup.get(position) + "點數");
        controllor.getRate(mData.idGroup.get(position));
        mPosition =position;
        Log.e("onClick", "" + position);
    }

    @Override
    public void submit( String point) {
        controllor.checkStorePoint(mData.idGroup.get(mPosition), point);
    }

    @Override
    public void cancel() {
        mFragmentGCMRecordAlertEditDialog.dismiss();
    }
}
