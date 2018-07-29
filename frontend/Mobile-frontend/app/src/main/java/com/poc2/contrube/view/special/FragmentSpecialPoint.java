package com.poc2.contrube.view.special;

import android.app.AlertDialog;
import android.graphics.Color;
import android.graphics.drawable.ColorDrawable;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v4.app.Fragment;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.Spinner;
import android.widget.TextView;
import android.widget.Toast;

import com.androidlibrary.module.RequestStateController;
import com.androidlibrary.module.StateBundle;
import com.androidlibrary.module.backend.data.ApiV1SpecialPointPostData;
import com.androidlibrary.module.backend.data.ApiV1SpecialSendPointPostData;
import com.poc2.R;
import com.poc2.contrube.component.dialog.SpecialPointEditDialog;
import com.poc2.contrube.controllor.point.FragmentSpecialPointControllor;
import com.poc2.contrube.core.FragmentLauncher;
import com.poc2.contrube.model.adapter.PointDetailRecyclerViewAdapter;

import static com.poc2.R.layout.fragment_normal_point_spinner_item;

/**
 * 特約發點頁面
 * Created by flowmaHuang on 2016/11/8.
 */

public class FragmentSpecialPoint extends Fragment {
    private RecyclerView mDetailRecyclerView;
    private Spinner mSearchRangeSpinner;
    private TextView mInputPhoneButton;
    private TextView mExportCsvButton;
    private View mLayoutView;
    private PointDetailRecyclerViewAdapter mRecyclerViewAdapter;
    private PointDetailRecyclerViewAdapter.DataStructure mRecyclerViewData;
    private FragmentSpecialPointControllor controllor;
    private SpecialPointEditDialog editDialog;
    private AlertDialog alertEditDialog;

    public String[] days = {"1-10天", "11-20天", "21-30天"};
    private int position;
    private int dayRangeStart;
    private int dayRangeEnd;
    private ArrayAdapter searchDayAdapter;
    private RequestStateController stateKeeper;
    private View back;

    @Nullable
    @Override
    public View onCreateView(LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        mLayoutView = inflater.inflate(R.layout.fragment_special_point, container, false);
        return mLayoutView;
    }

    @Override
    public void onActivityCreated(@Nullable Bundle savedInstanceState) {
        super.onActivityCreated(savedInstanceState);
        findView();
        init();
    }

    private void init() {
        dayRangeStart = 0;
        dayRangeEnd = 0;
        position = 0;
        mRecyclerViewAdapter = new PointDetailRecyclerViewAdapter(getActivity());
        mRecyclerViewData = new PointDetailRecyclerViewAdapter.DataStructure();
        stateKeeper = new RequestStateController();
        controllor = new FragmentSpecialPointControllor(getContext());
        editDialog = new SpecialPointEditDialog(getContext());
        alertEditDialog = editDialog.create();

        setRecyclerView();
        spinnerAdapter();
        setListener();

        stateKeeper.setOnSameStateListener(stateSameAndForce);
        stateKeeper.setOnForceStateListener(stateSameAndForce);
        stateKeeper.setOnDifferentStateListener(stateDifferent);
        stateKeeper.setOnOnceStateListener(stateOnce);

        controllor.setCallBackEvent(callBackEvent);
        controllor.setSendPointSuccess(pointSuccess);
        controllor.requestPoint(stateKeeper.force(), dayRangeStart, dayRangeEnd);

        alertEditDialog.getWindow().setBackgroundDrawable(new ColorDrawable(Color.TRANSPARENT));
        back.setOnClickListener(backClick);
    }

    private FragmentSpecialPointControllor.SendPointSuccess pointSuccess = new FragmentSpecialPointControllor.SendPointSuccess() {
        @Override
        public void sendPointSuccess(ApiV1SpecialSendPointPostData information) {
            if (information.result == 0) {
                alertEditDialog.dismiss();
                stateKeeper.once();
            }
        }
    };

    private FragmentSpecialPointControllor.CallBackEvent callBackEvent = new FragmentSpecialPointControllor.CallBackEvent() {
        @Override
        public void onError() {

        }

        @Override
        public void onSuccess(ApiV1SpecialPointPostData information, StateBundle bundle) {
            bundle.setData(information);
            stateKeeper.run(bundle);

        }

    };

    // 天數範圍一樣時，使用同一個 bundle 兩秒後繼續請求。
    private RequestStateController.OnStateListener stateSameAndForce = new RequestStateController.OnStateListener() {

        @Override
        public void run(final StateBundle bundle) {
            ApiV1SpecialPointPostData information = (ApiV1SpecialPointPostData) bundle.getData();
            updateApi(information);
            mLayoutView.postDelayed(new Runnable() {
                @Override
                public void run() {
                    controllor.requestPoint(bundle, dayRangeStart, dayRangeEnd);
                }
            }, 2000);
        }
    };

    // 天數範圍不一樣時，不更新介面，更換新的 bundle 兩秒後繼續請求。
    private RequestStateController.OnStateListener stateDifferent = new RequestStateController.OnStateListener() {

        @Override
        public void run(final StateBundle bundle) {
            mLayoutView.postDelayed(new Runnable() {
                @Override
                public void run() {
                    controllor.requestPoint(stateKeeper.get(), dayRangeStart, dayRangeEnd);
                }
            }, 2000);
        }
    };

    // 按下搜索或發送點數後，只更新介面且不發出下次請求。
    private RequestStateController.OnStateListener stateOnce = new RequestStateController.OnStateListener() {

        @Override
        public void run(final StateBundle bundle) {
            ApiV1SpecialPointPostData information = (ApiV1SpecialPointPostData) bundle.getData();
            updateApi(information);
        }
    };

    private void updateApi(ApiV1SpecialPointPostData information) {
        mRecyclerViewData = new PointDetailRecyclerViewAdapter.DataStructure();
        mRecyclerViewData.idGroup = information.idGroup;
        mRecyclerViewData.receiveGroup = information.receiveGroup;
        mRecyclerViewData.timestampGroup = information.timestampGroup;
        mRecyclerViewAdapter.setData(mRecyclerViewData);
    }


    private AdapterView.OnItemSelectedListener spinnerClick = new AdapterView.OnItemSelectedListener() {
        @Override
        public void onItemSelected(AdapterView<?> parent, View view, int position, long id) {
            int[] dayRangeGroupStart = {9, 19, 30};
            int[] dayRangeGroupEnd = {0, 10, 20};
            dayRangeStart = dayRangeGroupStart[position];
            dayRangeEnd = dayRangeGroupEnd[position];
            // 更換天數範圍後，更換狀態編號。
            stateKeeper.increaseId();
        }

        @Override
        public void onNothingSelected(AdapterView<?> parent) {

        }
    };

    private void findView() {
        mDetailRecyclerView = (RecyclerView) mLayoutView.findViewById(R.id.fragment_special_point_detail_list);
        mSearchRangeSpinner = (Spinner) mLayoutView.findViewById(R.id.fragment_special_point_search_spinner);
        mInputPhoneButton = (TextView) mLayoutView.findViewById(R.id.fragment_special_point_input_phone_button);
        mExportCsvButton = (TextView) mLayoutView.findViewById(R.id.fragment_special_point_export_csv_button);
        back = getView().findViewById(R.id.toolbar_back_touch);
    }

    private void setRecyclerView() {
        LinearLayoutManager linearManager = new LinearLayoutManager(getContext());
        linearManager.setOrientation(LinearLayoutManager.VERTICAL);
        mDetailRecyclerView.setLayoutManager(linearManager);
        mDetailRecyclerView.setAdapter(mRecyclerViewAdapter);
    }

    private void setListener() {
        editDialog.setEditDialogEvent(editDialogEvent);
        mRecyclerViewAdapter.setEditListener(editOnClick);
        mSearchRangeSpinner.setOnItemSelectedListener(spinnerClick);
        mInputPhoneButton.setOnClickListener(phoneNumberClick);
        mExportCsvButton.setOnClickListener(csvClcik);
    }

    private SpecialPointEditDialog.EditDialogEvent editDialogEvent = new SpecialPointEditDialog.EditDialogEvent() {
        @Override
        public void cancel() {
            alertEditDialog.dismiss();
        }

        @Override
        public void submit() {
            if (editDialog.getEditText().length() > 0) {
                mLayoutView.postDelayed(sendPoint, 300);
                alertEditDialog.dismiss();
            } else {
                Toast.makeText(getContext(), R.string.point_dialog_empty, Toast.LENGTH_LONG).show();
            }
        }
    };

    private Runnable sendPoint = new Runnable() {
        @Override
        public void run() {
            controllor.sendPoint(editDialog.getEditText().getText().toString().trim(),
                      mRecyclerViewData.idGroup.get(position).toString().trim(),
                      mRecyclerViewData.receiveGroup.get(position).toString().trim());
        }
    };

    private View.OnClickListener editOnClick = new View.OnClickListener() {
        @Override
        public void onClick(View v) {
            position = Integer.valueOf(v.getTag().toString());
            String phone = mRecyclerViewData.receiveGroup.get(position).toString();
            editDialog.getTitleText().setText(phone);
            alertEditDialog.show();
        }
    };

    private View.OnClickListener csvClcik = new View.OnClickListener() {
        @Override
        public void onClick(View v) {
            ((ActivitySpecialAdvertisement) getActivity()).goToCsv();
        }
    };

    private View.OnClickListener phoneNumberClick = new View.OnClickListener() {
        @Override
        public void onClick(View v) {
            ((ActivitySpecialAdvertisement) getActivity()).setAdvertisementEnable(false);
            FragmentLauncher.changeToBack(getContext(), R.id.content_container, null, FragmentSpecialPhoneSend.class.getName());

        }
    };

    private void spinnerAdapter() {
        searchDayAdapter = new ArrayAdapter<>(getActivity(), fragment_normal_point_spinner_item, days);
        searchDayAdapter.setDropDownViewResource(R.layout.fragment_normal_point_spinner_down_item);
        mSearchRangeSpinner.setAdapter(searchDayAdapter);
    }

    @Override
    public void onDestroyView() {
        super.onDestroyView();
        stateKeeper.end();
    }

    private View.OnClickListener backClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            getActivity().onBackPressed();
        }
    };
}
