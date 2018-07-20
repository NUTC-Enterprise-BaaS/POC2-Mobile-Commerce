package com.herbhousesgobuyother.contrube.controllor.point;

import android.content.Context;
import android.util.Log;
import android.widget.Toast;

import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.androidlibrary.module.ApiParams;
import com.androidlibrary.module.backend.api.ApiV1GeneralPointPost;
import com.androidlibrary.module.backend.api.ApiV1NormalUserPointGet;
import com.androidlibrary.module.backend.data.ApiV1GeneralPointPostData;
import com.androidlibrary.module.backend.data.ApiV1NormalUserPointGetData;
import com.androidlibrary.module.backend.data.ErrorProcessingData;
import com.androidlibrary.module.backend.params.AccountInjection;
import com.androidlibrary.module.backend.params.ServerInfoInjection;
import com.androidlibrary.module.backend.request.WebRequest;
import com.herbhousesgobuyother.R;
import com.herbhousesgobuyother.contrube.component.dialog.LoadingDialog;

import java.util.Calendar;

/**
 * Created by 依杰 on 2016/11/14.
 */

public class NormalPointController {
    private Context context;
    private AccountInjection accountInjection;
    private ServerInfoInjection serverInfoInjection;
    private ApiParams apiParams;
    private LoadingDialog loadingDialog;
    private Long timestampStart;
    private Long timestampEnd;
    private NormalPointController.CallBackEvent callBackEvent;

    public NormalPointController(Context context) {
        this.context = context;
        accountInjection = new AccountInjection(context);
        serverInfoInjection = new ServerInfoInjection();
        loadingDialog = new LoadingDialog(context);
        apiParams = new ApiParams(serverInfoInjection, accountInjection);
    }

    public void requestPoint(int dayRangeStart, int dayRangeEnd) {
        dealTimestamp(dayRangeStart, dayRangeEnd);

        apiParams.timestampStart = String.valueOf(timestampStart);
        apiParams.timestampEnd = String.valueOf(timestampEnd);
        Log.e("timestampStart", timestampStart + "");
        Log.e("timestampEnd", timestampEnd + "");

        loadingDialog.show();
        WebRequest<ApiV1GeneralPointPostData> request = new ApiV1GeneralPointPost<>(context, apiParams);
        request.processing(new WebRequest.Processing<ApiV1GeneralPointPostData>() {
            @Override
            public ApiV1GeneralPointPostData run(String data) {
                return new ApiV1GeneralPointPostData(data);
            }
        }).failProcess(new WebRequest.FailProcess<ApiV1GeneralPointPostData>() {
            @Override
            public void run(String data, ApiV1GeneralPointPostData information) {
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
        }).successProcess(new WebRequest.SuccessProcess<ApiV1GeneralPointPostData>() {
            @Override
            public void run(String data, ApiV1GeneralPointPostData information) {
                loadingDialog.dismiss();
                update(information);
            }
        }).start();
    }

    private void dealTimestamp(int dayRangeStart, int dayRangeEnd) {
        Calendar timeEnd = Calendar.getInstance();
        timeEnd.add(Calendar.DATE, -dayRangeEnd);
        timeEnd.set(Calendar.HOUR, 11);
        timeEnd.set(Calendar.MINUTE, 59);
        timeEnd.set(Calendar.SECOND, 59);
        timestampEnd = timeEnd.getTimeInMillis() / 1000;

        Calendar time = Calendar.getInstance();
        time.add(Calendar.DATE, -dayRangeStart);
        timestampStart = time.getTimeInMillis() / 1000;
    }

    private void update(ApiV1GeneralPointPostData information) {
        if (information.result == 0) {
            callBackEvent.onSuccess(information);
        }
    }

    public void checkLdapState() {
        loadingDialog.show();
        WebRequest<ApiV1NormalUserPointGetData> request = new ApiV1NormalUserPointGet<>(context, apiParams);
        request.processing(new WebRequest.Processing<ApiV1NormalUserPointGetData>() {
            @Override
            public ApiV1NormalUserPointGetData run(String data) {
                return new ApiV1NormalUserPointGetData(data);
            }
        }).failProcess(new WebRequest.FailProcess<ApiV1NormalUserPointGetData>() {
            @Override
            public void run(String data, ApiV1NormalUserPointGetData information) {
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
        }).successProcess(new WebRequest.SuccessProcess<ApiV1NormalUserPointGetData>() {
            @Override
            public void run(String data, ApiV1NormalUserPointGetData information) {
                loadingDialog.dismiss();
                if (null != callBackEvent) {
                    callBackEvent.onSuccess(information);
                }
            }
        }).start();
    }

    public void setCallBackEvent(CallBackEvent callBackEvent) {
        this.callBackEvent = callBackEvent;
    }

    public interface CallBackEvent {
        void onError();

        void onSuccess(ApiV1GeneralPointPostData information);

        void onSuccess(ApiV1NormalUserPointGetData information);
    }

}
