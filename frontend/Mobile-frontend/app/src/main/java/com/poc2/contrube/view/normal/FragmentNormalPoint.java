package com.poc2.contrube.view.normal;

import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.Spinner;
import android.widget.TextView;

import com.androidlibrary.module.backend.data.ApiV1GeneralPointPostData;
import com.androidlibrary.module.backend.data.ApiV1NormalCostPointGetData;
import com.androidlibrary.module.backend.data.ApiV1NormalHistoryPointPostData;
import com.androidlibrary.module.backend.data.ApiV1NormalPointGetData;
import com.androidlibrary.module.backend.data.ApiV1NormalSyncPointGetData;
import com.androidlibrary.module.backend.data.ApiV1NormalUserPointGetData;
import com.poc2.R;
import com.poc2.contrube.component.dialog.NormalPointSendPointDialog;
import com.poc2.contrube.controllor.point.NormalPointController;
import com.poc2.contrube.model.adapter.FragmentNormalPointAdapter;

import static com.poc2.R.layout.fragment_normal_point_spinner_item;

/**
 * Created by 依杰 on 2016/11/7.
 */

public class FragmentNormalPoint extends Fragment {

    private TextView pointTextView;
    private TextView costTextView;
    private RecyclerView recyclerView;
    private Spinner searchSpinner;
    private Button sendButton;
    private FragmentNormalPointAdapter pointAdapter;
    private FragmentNormalPointAdapter.DataStructure pointData;
    private ArrayAdapter searchDayAdapter;
    private String[] days = {"1-10天", "11-20天", "21-30天"};
    private int dayRangeStart;
    private int dayRangeEnd;
    private NormalPointSendPointDialog sendPointDialog;
    private NormalPointController controller;
    private View back;

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        View layout = inflater.inflate(R.layout.fragment_normal_point, container, false);
        return layout;

    }

    @Override
    public void onViewCreated(View view, Bundle savedInstanceState) {
        super.onViewCreated(view, savedInstanceState);
        finView();
        init();
    }

    private void finView() {
        back = getView().findViewById(R.id.toolbar_back_touch);
        sendButton = (Button) getView().findViewById(R.id.fragment_normal_point_transfer_button);
        pointTextView = (TextView) getView().findViewById(R.id.fragment_normal_point_point_text);
        costTextView = (TextView) getView().findViewById(R.id.fragment_normal_point_cost_text);
        recyclerView = (RecyclerView) getView().findViewById(R.id.fragment_normal_point_recyclerview);
        searchSpinner = (Spinner) getView().findViewById(R.id.fragment_normal_point_day_spinner);
    }

    private void init() {
        controller = new NormalPointController(getContext());
        pointAdapter = new FragmentNormalPointAdapter(getActivity());
        pointData = new FragmentNormalPointAdapter.DataStructure();
        sendPointDialog = new NormalPointSendPointDialog(getContext());
        recyclerAdapter();
        spinnerAdapter();
        searchSpinner.setOnItemSelectedListener(spinnerClick);
        controller.setCallBackEvent(callBackEvent);
        sendButton.setOnClickListener(sendClick);
        controller.syncData();
        back.setOnClickListener(backClick);

    }

    private View.OnClickListener sendClick = new View.OnClickListener() {
        @Override
        public void onClick(View v) {
            sendPointDialog.show();
        }
    };

    private NormalPointController.CallBackEvent callBackEvent = new NormalPointController.CallBackEvent() {
        @Override
        public void onError() {

        }

        @Override
        public void onSuccess(ApiV1GeneralPointPostData information) {
//            setCostCount(information.cost);

            pointData.idGroup = information.idGroup;
            pointData.storeNameGroup = information.storeNameGroup;
            pointData.timestampGroup = information.timestampGroup;
            pointData.bonusGroup = information.bonusGroup;
            pointAdapter.setData(pointData);

        }

        @Override
        public void onSuccess(ApiV1NormalUserPointGetData information) {
//            setPointCount(information.point);
        }

        @Override
        public void onSuccess(ApiV1NormalPointGetData information) {
            setPointCount(information.point);
        }

        @Override
        public void onSuccess(ApiV1NormalCostPointGetData information) {
            setCostCount(information.costs);
        }

        @Override
        public void onSuccess(ApiV1NormalHistoryPointPostData information) {
            pointData.idGroup = information.idGroup;
            pointData.storeNameGroup = information.storeNameGroup;
            pointData.timestampGroup = information.timestampGroup;
            pointData.bonusGroup = information.bonusGroup;
            pointAdapter.setData(pointData);
        }

        @Override
        public void onSuccess(ApiV1NormalSyncPointGetData information) {
            controller.getCostPoint();
            controller.getPoint();
            controller.requestHistory(9, 0);
        }
    };

    private void setPointCount(int count) {
        pointTextView.setText(getContext().getString(R.string.point_layout_point, count));
    }

    private void setCostCount(int count) {
        costTextView.setText(getContext().getString(R.string.point_layout_cost, count));
    }

    private AdapterView.OnItemSelectedListener spinnerClick = new AdapterView.OnItemSelectedListener() {
        @Override
        public void onItemSelected(AdapterView<?> parent, View view, int position, long id) {
            int[] dayRangeGroupStart = {9, 19, 30};
            int[] dayRangeGroupEnd = {0, 10, 20};
            dayRangeStart = dayRangeGroupStart[position];
            dayRangeEnd = dayRangeGroupEnd[position];
            controller.requestHistory(dayRangeStart, dayRangeEnd);
            controller.getCostPoint();
            controller.getPoint();
        }

        @Override
        public void onNothingSelected(AdapterView<?> parent) {

        }
    };

    private void recyclerAdapter() {
        LinearLayoutManager linearManager = new LinearLayoutManager(getContext());
        linearManager.setOrientation(LinearLayoutManager.VERTICAL);
        recyclerView.setLayoutManager(linearManager);
        recyclerView.setAdapter(pointAdapter);
    }

    private void spinnerAdapter() {
        searchDayAdapter = new ArrayAdapter<>(getActivity(), fragment_normal_point_spinner_item, days);
        searchDayAdapter.setDropDownViewResource(R.layout.fragment_normal_point_spinner_down_item);
        searchSpinner.setAdapter(searchDayAdapter);
    }

    private View.OnClickListener backClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            getActivity().onBackPressed();
        }
    };
}
