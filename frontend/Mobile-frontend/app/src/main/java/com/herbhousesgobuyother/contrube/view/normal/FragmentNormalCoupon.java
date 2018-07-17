package com.herbhousesgobuyother.contrube.view.normal;

import android.app.AlertDialog;
import android.content.DialogInterface;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Toast;

import com.androidlibrary.module.backend.data.ApiV1NormalUseVoucherData;
import com.androidlibrary.module.backend.data.ApiV1NormalVoucherListGetData;
import com.herbhousesgobuyother.R;
import com.herbhousesgobuyother.contrube.controllor.FragmentNormalCouponControllor;
import com.herbhousesgobuyother.contrube.model.adapter.FragmentNormalCouponAdapt;

import java.util.ArrayList;

/**
 * Created by 依杰 on 2018/7/16.
 */

public class FragmentNormalCoupon extends Fragment implements FragmentNormalCouponAdapt.CallBack {
    private RecyclerView list;
    private FragmentNormalCouponControllor controllor;
    private FragmentNormalCouponAdapt adapt;
    private FragmentNormalCouponAdapt.DataStructure mData;
    private View back;
    private int mPosition = 0;

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        View layout = inflater.inflate(R.layout.fragment_normal_coupon, container, false);
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
    }

    private void init() {
        controllor = new FragmentNormalCouponControllor(getContext());
        adapt = new FragmentNormalCouponAdapt(getContext());
        mData = new FragmentNormalCouponAdapt.DataStructure();
        setRecycleView();
        adapt.setClickEvent(this);
        back.setOnClickListener(backClick);
        controllor.setmCallBackEvent(callBackEvent);
        controllor.getStoreList();
    }

    private FragmentNormalCouponControllor.CallBackEvent callBackEvent = new FragmentNormalCouponControllor.CallBackEvent() {

        @Override
        public void onError() {

        }

        @Override
        public void onSuccess(ApiV1NormalVoucherListGetData information) {
            update(information.storeNameGroup, information.idGroup);
        }

        @Override
        public void onSuccess(ApiV1NormalUseVoucherData information) {
            if (information.message.contains("use")) {
                adapt.clear();
                controllor.getStoreList();
                Toast.makeText(getContext(), "使用優惠券成功", Toast.LENGTH_LONG).show();
            }
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
    public void onClick(final int position) {
        new AlertDialog.Builder(getContext())
                .setTitle("使用優惠券")
                .setMessage("使用後，優惠券將被刪除，是否確認使用優惠券？")
                .setPositiveButton(R.string.finger_print_dialog_yes, new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int which) {
                        controllor.useCoupon(mData.idGroup.get(mPosition));
                        Log.e("onClick", "" + position);
                    }
                })
                .setNeutralButton(R.string.finger_print_dialog_no, new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int which) {
                        dialog.dismiss();
                    }
                })
                .show();
    }
}
