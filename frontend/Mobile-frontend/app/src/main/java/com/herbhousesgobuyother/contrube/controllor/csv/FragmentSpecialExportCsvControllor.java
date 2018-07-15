package com.herbhousesgobuyother.contrube.controllor.csv;

import android.content.Context;
import android.widget.Toast;

import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.androidlibrary.module.ApiParams;
import com.androidlibrary.module.backend.api.ApiV1SpecialCsvDownloadPost;
import com.androidlibrary.module.backend.api.ApiV1SpecialPointRecordPost;
import com.androidlibrary.module.backend.data.ApiV1SpecialCsvDownloadPostData;
import com.androidlibrary.module.backend.data.ApiV1SpecialPointRecordPostData;
import com.androidlibrary.module.backend.data.ErrorProcessingData;
import com.androidlibrary.module.backend.params.AccountInjection;
import com.androidlibrary.module.backend.params.ServerInfoInjection;
import com.androidlibrary.module.backend.request.WebRequest;
import com.herbhousesgobuyother.R;
import com.herbhousesgobuyother.contrube.component.dialog.LoadingDialog;

/**
 * Created by 依杰 on 2016/11/23.
 */

public class FragmentSpecialExportCsvControllor {
    private Context context;
    private AccountInjection accountInjection;
    private ServerInfoInjection serverInfoInjection;
    private ApiParams apiParams;
    private LoadingDialog loadingDialog;
    private CallBackEvent callBackEvent;
    private DownLoadCallBackEvent downLoadCallBackEvent;

    public FragmentSpecialExportCsvControllor(Context context) {
        this.context = context;
        accountInjection = new AccountInjection(context);
        serverInfoInjection = new ServerInfoInjection();
        loadingDialog = new LoadingDialog(context);
        apiParams = new ApiParams(serverInfoInjection, accountInjection);
    }

    public void requestPoint(String timestampStart, String timestampEnd) {
        apiParams.timestampStart = timestampStart;
        apiParams.timestampEnd = timestampEnd;

        loadingDialog.show();
        WebRequest<ApiV1SpecialPointRecordPostData> request = new ApiV1SpecialPointRecordPost<>(context, apiParams);
        request.processing(new WebRequest.Processing<ApiV1SpecialPointRecordPostData>() {
            @Override
            public ApiV1SpecialPointRecordPostData run(String data) {
                return new ApiV1SpecialPointRecordPostData(data);
            }
        }).failProcess(new WebRequest.FailProcess<ApiV1SpecialPointRecordPostData>() {
            @Override
            public void run(String data, ApiV1SpecialPointRecordPostData information) {
                loadingDialog.dismiss();
                ErrorProcessingData.run(context, data, information);
            }
        }).unknownFailRequest(new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                loadingDialog.dismiss();
                String content = context.getString(R.string.request_load_fail);
                Toast.makeText(context, content, Toast.LENGTH_LONG).show();
            }
        }).successProcess(new WebRequest.SuccessProcess<ApiV1SpecialPointRecordPostData>() {
            @Override
            public void run(String data, ApiV1SpecialPointRecordPostData information) {
                loadingDialog.dismiss();

                if (information.result == 0) {
                    updateApi(information);
                }

            }
        }).start();
    }

    private void updateApi(ApiV1SpecialPointRecordPostData information) {
        callBackEvent.onSuccess(information);
    }

    public void setCallBackEvent(CallBackEvent callBackEvent) {
        this.callBackEvent = callBackEvent;
    }

    public interface CallBackEvent {
        void onSuccess(ApiV1SpecialPointRecordPostData information);

        void onEror();
    }

    public void requestCsv(String timestampStart, String timestampEnd, String password) {
        apiParams.inputPassword = password;
        apiParams.timestampStart = timestampStart;
        apiParams.timestampEnd = timestampEnd;

        WebRequest<ApiV1SpecialCsvDownloadPostData> request = new ApiV1SpecialCsvDownloadPost<>(context, apiParams);
        request.processing(new WebRequest.Processing<ApiV1SpecialCsvDownloadPostData>() {
            @Override
            public ApiV1SpecialCsvDownloadPostData run(String data) {
                return new ApiV1SpecialCsvDownloadPostData(data);
            }
        }).failProcess(new WebRequest.FailProcess<ApiV1SpecialCsvDownloadPostData>() {
            @Override
            public void run(String data, ApiV1SpecialCsvDownloadPostData information) {
                ErrorProcessingData.run(context, data, information);
            }
        }).unknownFailRequest(new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                String content = context.getString(R.string.request_load_fail);
                Toast.makeText(context, content, Toast.LENGTH_LONG).show();
            }
        }).successProcess(new WebRequest.SuccessProcess<ApiV1SpecialCsvDownloadPostData>() {
            @Override
            public void run(String data, ApiV1SpecialCsvDownloadPostData information) {
                if (information.result == 0) {
                    downloadSuccess(information);
                } else if (information.result == 1) {
                    String content = context.getString(R.string.export_csv_dialog_fail2);
                    Toast.makeText(context, content, Toast.LENGTH_LONG).show();
                }

            }
        }).start();
    }

    public void setDownLoadCallBackEvent(DownLoadCallBackEvent event) {
        this.downLoadCallBackEvent = event;
    }

    private void downloadSuccess(ApiV1SpecialCsvDownloadPostData information) {
        downLoadCallBackEvent.onDownLoadSuccess(information);
    }

    public interface DownLoadCallBackEvent {
        void onDownLoadSuccess(ApiV1SpecialCsvDownloadPostData information);

        void onError();
    }
}
