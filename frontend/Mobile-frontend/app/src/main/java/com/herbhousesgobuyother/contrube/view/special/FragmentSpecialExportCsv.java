package com.herbhousesgobuyother.contrube.view.special;

import android.app.AlertDialog;
import android.app.DatePickerDialog;
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
import android.widget.DatePicker;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import com.androidlibrary.module.backend.data.ApiV1SpecialCsvDownloadPostData;
import com.androidlibrary.module.backend.data.ApiV1SpecialPointRecordPostData;
import com.herbhousesgobuyother.R;
import com.herbhousesgobuyother.contrube.component.dialog.SpecialExportCsvDialog;
import com.herbhousesgobuyother.contrube.component.dialog.SpecialExportCsvDownloadDialog;
import com.herbhousesgobuyother.contrube.controllor.csv.FragmentSpecialExportCsvControllor;
import com.herbhousesgobuyother.contrube.model.adapter.ExportCsvRecyclerViewAdapter;

import java.util.Calendar;

/**
 * 特約店 匯出CSV頁面
 * Created by flowmaHuang on 2016/11/11.
 */

public class FragmentSpecialExportCsv extends Fragment {
    private RecyclerView mDetailRecyclerView;
    private TextView mStartDateButton;
    private TextView mEndDateButton;
    private TextView mSearchButton;
    private TextView mExportButton;
    private View mLayoutView;
    private ImageView backImag;
    private FragmentSpecialExportCsvControllor controllor;
    private ExportCsvRecyclerViewAdapter mRecyclerViewAdapter;
    private ExportCsvRecyclerViewAdapter.DataStructure mRecyclerViewAdapterData;

    private String timestampStart = "";
    private String timestampEnd = "";

    private SpecialExportCsvDialog exportDialog;
    private AlertDialog alertExportDialog;
    private SpecialExportCsvDownloadDialog downloadDialog;
    private AlertDialog alertDialog;
    private View back;

    @Nullable
    @Override
    public View onCreateView(LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        return mLayoutView = inflater.inflate(R.layout.fragment_special_export_csv, container, false);
    }

    @Override
    public void onActivityCreated(@Nullable Bundle savedInstanceState) {
        super.onActivityCreated(savedInstanceState);
        finView();
        init();
    }

    private void finView() {
        back = getView().findViewById(R.id.toolbar_back_touch);
    }

    private void init() {
        mRecyclerViewAdapter = new ExportCsvRecyclerViewAdapter(getActivity());
        controllor = new FragmentSpecialExportCsvControllor(getActivity());
        mRecyclerViewAdapterData = new ExportCsvRecyclerViewAdapter.DataStructure();
        exportDialog = new SpecialExportCsvDialog(getActivity());
        alertExportDialog = exportDialog.create();
        downloadDialog = new SpecialExportCsvDownloadDialog(getActivity());
        alertDialog = downloadDialog.create();

        findView();
        setListener();
        setRecyclerView();
        defaultShow();

        controllor.setCallBackEvent(callBackEvent);
        controllor.setDownLoadCallBackEvent(downloadEvent);
        downloadDialog.setCsvDownloadEvnet(downloadDialogEvent);
        alertExportDialog.getWindow().setBackgroundDrawable(new ColorDrawable(Color.TRANSPARENT));
        alertDialog.getWindow().setBackgroundDrawable(new ColorDrawable(Color.TRANSPARENT));
        back.setOnClickListener(backClick);
    }

    private FragmentSpecialExportCsvControllor.DownLoadCallBackEvent downloadEvent = new FragmentSpecialExportCsvControllor.DownLoadCallBackEvent() {
        @Override
        public void onDownLoadSuccess(ApiV1SpecialCsvDownloadPostData information) {
            alertExportDialog.dismiss();
            alertDialog.show();
        }

        @Override
        public void onError() {

        }
    };

    private SpecialExportCsvDownloadDialog.csvDownloadEvnet downloadDialogEvent = new SpecialExportCsvDownloadDialog.csvDownloadEvnet() {
        @Override
        public void down() {
            alertDialog.dismiss();
        }
    };

    private FragmentSpecialExportCsvControllor.CallBackEvent callBackEvent = new FragmentSpecialExportCsvControllor.CallBackEvent() {
        @Override
        public void onSuccess(ApiV1SpecialPointRecordPostData information) {
            mRecyclerViewAdapterData = new ExportCsvRecyclerViewAdapter.DataStructure();
            mRecyclerViewAdapterData.idGroup = information.idGroup;
            mRecyclerViewAdapterData.phoneGroup = information.phoneGroup;
            mRecyclerViewAdapterData.moneyGroup = information.moneyGroup;
            mRecyclerViewAdapter.setData(mRecyclerViewAdapterData);
        }

        @Override
        public void onEror() {

        }
    };

    private void defaultShow() {
        Calendar start = Calendar.getInstance(); //當天的最早時間
        start.set(Calendar.HOUR_OF_DAY, 0);
        start.set(Calendar.MINUTE, 0);
        start.set(Calendar.SECOND, 1);
        mStartDateButton.setText(start.get(Calendar.YEAR) + "/" + (start.get(Calendar.MONTH) + 1) + "/" + start.get(Calendar.DAY_OF_MONTH));
        Calendar end = Calendar.getInstance(); //當天的最晚時間
        end.set(Calendar.HOUR_OF_DAY, 23);
        end.set(Calendar.MINUTE, 59);
        end.set(Calendar.SECOND, 59);
        mEndDateButton.setText(end.get(Calendar.YEAR) + "/" + (end.get(Calendar.MONTH) + 1) + "/" + end.get(Calendar.DAY_OF_MONTH));

        timestampStart = String.valueOf(start.getTimeInMillis() / 1000);
        timestampEnd = String.valueOf(end.getTimeInMillis() / 1000);
        controllor.requestPoint(timestampStart, timestampEnd);
    }

    private void findView() {
        mDetailRecyclerView = (RecyclerView) mLayoutView.findViewById(R.id.fragment_special_export_csv_detail_list);
        mStartDateButton = (TextView) mLayoutView.findViewById(R.id.fragment_special_export_csv_date_from_button);
        mEndDateButton = (TextView) mLayoutView.findViewById(R.id.fragment_special_export_csv_date_to_button);
        mSearchButton = (TextView) mLayoutView.findViewById(R.id.fragment_special_export_csv_search_button);
        mExportButton = (TextView) mLayoutView.findViewById(R.id.fragment_special_export_csv_export_button);
    }

    private void setListener() {
        mStartDateButton.setOnClickListener(clickCallBack);
        mEndDateButton.setOnClickListener(clickCallBack);
        mSearchButton.setOnClickListener(clickCallBack);
        mExportButton.setOnClickListener(clickCallBack);
        exportDialog.setCsvEvent(csvEvent);
    }

    private SpecialExportCsvDialog.CsvEvent csvEvent = new SpecialExportCsvDialog.CsvEvent() {
        @Override
        public void submit() {
            if (exportDialog.getinputEdit().getText().length() > 0) {
                controllor.requestCsv(timestampStart, timestampEnd, exportDialog.getinputEdit().getText().toString().trim());
            } else {
                Toast.makeText(getActivity(), R.string.point_dialog_empty, Toast.LENGTH_LONG).show();
            }
        }

        @Override
        public void forgot() {

        }
    };

    private void setRecyclerView() {
        LinearLayoutManager linearManager = new LinearLayoutManager(getActivity());
        linearManager.setOrientation(LinearLayoutManager.VERTICAL);
        mDetailRecyclerView.setLayoutManager(linearManager);
        mDetailRecyclerView.setAdapter(mRecyclerViewAdapter);
    }

    private DatePickerDialog showStartDatePickerDialog() {
        Calendar calendar = Calendar.getInstance();
        DatePickerDialog mDatePickerDialog = new DatePickerDialog(getActivity(),
                onStartDateSetListener, calendar.get(Calendar.YEAR),
                calendar.get(Calendar.MONTH),
                calendar.get(Calendar.DAY_OF_MONTH));
        mDatePickerDialog.getDatePicker().setMaxDate(calendar.getTimeInMillis());

        return mDatePickerDialog;
    }

    private DatePickerDialog showEndDatePickerDialog() {
        Calendar calendar = Calendar.getInstance();
        DatePickerDialog mDatePickerDialog = new DatePickerDialog(getActivity(),
                onEndDateSetListener, calendar.get(Calendar.YEAR),
                calendar.get(Calendar.MONTH),
                calendar.get(Calendar.DAY_OF_MONTH));
        mDatePickerDialog.getDatePicker().setMaxDate(calendar.getTimeInMillis());

        return mDatePickerDialog;
    }

    /**
     * ** Callback Func
     **/

    private View.OnClickListener clickCallBack = new View.OnClickListener() {
        @Override
        public void onClick(View v) {
            switch (v.getId()) {
                case R.id.fragment_special_export_csv_date_from_button:
                    showStartDatePickerDialog().show();
                    break;
                case R.id.fragment_special_export_csv_date_to_button:
                    showEndDatePickerDialog().show();
                    break;
                case R.id.fragment_special_export_csv_search_button:
                    search();
                    break;
                case R.id.fragment_special_export_csv_export_button:
                    export();
                    break;
            }
        }
    };

    private void export() {
        downloadDialog.getStartText().setText("自 " + mStartDateButton.getText().toString().trim());
        downloadDialog.getEndText().setText("至 " + mEndDateButton.getText().toString().trim());
        alertExportDialog.show();
    }

    private void search() {
        if (mStartDateButton.getText().length() > 0 && mEndDateButton.getText().length() > 0) {
            controllor.requestPoint(timestampStart, timestampEnd);
        } else {
            String content = getString(R.string.date_dialog_empty);
            Toast.makeText(getActivity(), content, Toast.LENGTH_LONG).show();
        }
    }

    private void backImag() {
        getActivity().onBackPressed();
    }

    private DatePickerDialog.OnDateSetListener onStartDateSetListener = new DatePickerDialog.OnDateSetListener() {
        @Override
        public void onDateSet(DatePicker view, int year, int monthOfYear, int dayOfMonth) {
            mStartDateButton.setText(year + "/" + (monthOfYear + 1) + "/" + dayOfMonth);
            Calendar startDate = Calendar.getInstance();
            startDate.set(Calendar.YEAR, year);
            startDate.set(Calendar.MONTH, monthOfYear);
            startDate.set(Calendar.DAY_OF_MONTH, dayOfMonth);
            timestampStart = String.valueOf(startDate.getTimeInMillis() / 1000);
        }
    };

    private DatePickerDialog.OnDateSetListener onEndDateSetListener = new DatePickerDialog.OnDateSetListener() {
        @Override
        public void onDateSet(DatePicker view, int year, int monthOfYear, int dayOfMonth) {
            mEndDateButton.setText(year + "/" + (monthOfYear + 1) + "/" + dayOfMonth);
            Calendar startDate = Calendar.getInstance();
            startDate.set(Calendar.YEAR, year);
            startDate.set(Calendar.MONTH, monthOfYear);
            startDate.set(Calendar.DAY_OF_MONTH, dayOfMonth);
            timestampStart = String.valueOf(startDate.getTimeInMillis() / 1000);
        }
    };
    private View.OnClickListener backClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            getActivity().onBackPressed();
        }
    };
}
