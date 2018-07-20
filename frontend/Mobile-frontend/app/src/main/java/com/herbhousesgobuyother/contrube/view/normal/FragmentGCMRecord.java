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
import android.widget.Button;
import android.widget.TextView;
import android.widget.Toast;

import com.androidlibrary.module.backend.data.ApiV1NormalStoreChangePostData;
import com.androidlibrary.module.backend.data.ApiV1NormalStoreCreateGetData;
import com.androidlibrary.module.backend.data.ApiV1NormalStoreListGetData;
import com.androidlibrary.module.backend.data.ApiV1NormalSyncPointGetData;
import com.androidlibrary.module.backend.data.ApiV1NormalTokenGetData;
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
    private Button mTokenButton;
    private FragmentGCMRecordContorllor controllor;
    private FragmentGCMRecordAdapt adapt;
    private FragmentGCMRecordAdapt.DataStructure mData;
    private View back;
    public ArrayList<String> titleGroup = new ArrayList<>();
    private FragmentGCMRecordDialog mFragmentGCMRecordDialog;
    private AlertDialog mFragmentGCMRecordAlertEditDialog;
    private TextView mTextToken;
    private int mPosition = 0;


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
        mTokenButton = getView().findViewById(R.id.button_token);
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
        mTokenButton.setOnClickListener(tokenClick);

        controllor.setmCallBackEvent(callBackEvent);
        controllor.getStoreList();
        controllor.getVerifyCode();
    }

    private FragmentGCMRecordContorllor.CallBackEvent callBackEvent = new FragmentGCMRecordContorllor.CallBackEvent() {
        @Override
        public void onError(String message) {
            Toast.makeText(getActivity(), message, Toast.LENGTH_SHORT).show();
        }

        @Override
        public void onSuccess(ApiV1NormalStoreListGetData information, ApiV1NormalUserPointGetData pointData) {

            ArrayList<String> list = new ArrayList<>();
            ArrayList<String> id = new ArrayList<>();
            ArrayList<String> userList = new ArrayList<>();

            for (int i = 0; i < information.storeNameGroup.size(); i++) {
                if (!information.storeNameGroup.get(i).equals(pointData.store)) {
                    if (information.storeNameGroup.get(i).equals("HappyBuy")) {
                        list.add("行動電商B紅利數交換平台");
                    }
                    id.add(information.storeNameGroup.get(i));
                    userList.add(information.userNameGroup.get(i));
                }
            }
            update(list, id, userList);
        }

        @Override
        public void onSuccess(ApiV1NormalStoreCreateGetData information) {
            mFragmentGCMRecordDialog.getRateTitleText().setText(String.valueOf(information.rate));
            mFragmentGCMRecordAlertEditDialog.show();
        }

        @Override
        public void onSuccess(ApiV1NormalStoreChangePostData information) {
            controllor.syncPoint(information.url);
        }

        @Override
        public void onSuccess(ApiV1NormalTokenGetData information) {
            mTextToken.setText("您的開通序號：" + information.code);
        }

        @Override
        public void onSuccess(ApiV1NormalSyncPointGetData information) {
            Toast.makeText(getActivity(), "轉換成功", Toast.LENGTH_LONG).show();
            mFragmentGCMRecordAlertEditDialog.dismiss();
        }

    };

    private void update(ArrayList<String> list, ArrayList<String> id, ArrayList<String> user) {
        mData.titleGroup = list;
        mData.idGroup = id;
        mData.userGroup = user;
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

    private View.OnClickListener tokenClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            controllor.getVerifyCode();
        }
    };

    @Override
    public void onClick(int position) {
        mFragmentGCMRecordDialog.getTitleText().setText("轉換" + mData.titleGroup.get(position) + "點數");
        controllor.getRate(mData.idGroup.get(position));
        mPosition = position;
        Log.e("onClick", "" + position);
    }

    @Override
    public void submit(String point) {
        controllor.checkStorePoint(mData.idGroup.get(mPosition), point, mData.userGroup.get(mPosition));
    }

    @Override
    public void cancel() {
        mFragmentGCMRecordAlertEditDialog.dismiss();
    }
}
