package com.herbhousesgobuyother.contrube.controllor.point;

import android.content.Context;
import android.util.Log;
import android.widget.Toast;

import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.androidlibrary.module.ApiParams;
import com.androidlibrary.module.StateBundle;
import com.androidlibrary.module.backend.api.ApiV1SpecialPointPost;
import com.androidlibrary.module.backend.api.ApiV1SpecialSendPointPost;
import com.androidlibrary.module.backend.data.ApiV1SpecialPointPostData;
import com.androidlibrary.module.backend.data.ApiV1SpecialSendPointPostData;
import com.androidlibrary.module.backend.data.ErrorProcessingData;
import com.androidlibrary.module.backend.params.AccountInjection;
import com.androidlibrary.module.backend.params.ServerInfoInjection;
import com.androidlibrary.module.backend.request.WebRequest;
import com.herbhousesgobuyother.R;
import com.herbhousesgobuyother.contrube.component.dialog.LoadingDialog;

import java.util.Calendar;

/**
 * Created by 依杰 on 2016/11/22.
 */

public class FragmentSpecialPointControllor {
    private Context context;
    private AccountInjection accountInjection;
    private ServerInfoInjection serverInfoInjection;
    private ApiParams apiParams;
    private LoadingDialog loadingDialog;
    private Long timestampStart;
    private Long timestampEnd;
    private CallBackEvent callBackEvent;
    private SendPointSuccess sendPointSuccess;

    public FragmentSpecialPointControllor(Context context) {
        this.context = context;
        accountInjection = new AccountInjection(context);
        serverInfoInjection = new ServerInfoInjection();
        loadingDialog = new LoadingDialog(context);
        apiParams = new ApiParams(serverInfoInjection, accountInjection);
    }

    public void requestPoint(final StateBundle bundle, int dayRangeStart, int dayRangeEnd) {
        dealTimestamp(dayRangeStart, dayRangeEnd);

        apiParams.timestampStart = String.valueOf(timestampStart);
        apiParams.timestampEnd = String.valueOf(timestampEnd);
        Log.e("timestampStart", apiParams.timestampStart + "");
        Log.e("timestampEnd", apiParams.timestampEnd + "");

        WebRequest<ApiV1SpecialPointPostData> request = new ApiV1SpecialPointPost<>(context, apiParams);
        request.processing(new WebRequest.Processing<ApiV1SpecialPointPostData>() {
            @Override
            public ApiV1SpecialPointPostData run(String data) {
                return new ApiV1SpecialPointPostData(data);
            }
        }).failProcess(new WebRequest.FailProcess<ApiV1SpecialPointPostData>() {
            @Override
            public void run(String data, ApiV1SpecialPointPostData information) {
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
        }).successProcess(new WebRequest.SuccessProcess<ApiV1SpecialPointPostData>() {
            @Override
            public void run(String data, ApiV1SpecialPointPostData information) {
                if (information.result == 0) {
                    onSuccess(information, bundle);
                    loadingDialog.dismiss();
                }
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

    private void onSuccess(ApiV1SpecialPointPostData information, StateBundle bundle) {
        callBackEvent.onSuccess(information, bundle);
    }

    public void setCallBackEvent(CallBackEvent callBackEvent) {
        this.callBackEvent = callBackEvent;
    }

    public interface CallBackEvent {
        void onError();

        void onSuccess(ApiV1SpecialPointPostData information, StateBundle bundle);
    }

    public void sendPoint(String point, String id, String receive) {
        if (Integer.valueOf(id) <= 0)
            return;
        apiParams.inputTransactionId = id;
        apiParams.inputPhoneNumber = receive;
        apiParams.inputBonus = point;

        WebRequest<ApiV1SpecialSendPointPostData> request = new ApiV1SpecialSendPointPost<>(context, apiParams);
        request.processing(new WebRequest.Processing<ApiV1SpecialSendPointPostData>() {
            @Override
            public ApiV1SpecialSendPointPostData run(String data) {
                return new ApiV1SpecialSendPointPostData(data);
            }
        }).failProcess(new WebRequest.FailProcess<ApiV1SpecialSendPointPostData>() {
            @Override
            public void run(String data, ApiV1SpecialSendPointPostData information) {
                ErrorProcessingData.run(context, data, information);
            }
        }).unknownFailRequest(new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                String content = context.getString(R.string.request_load_fail);
                Toast.makeText(context, content, Toast.LENGTH_LONG).show();
            }
        }).successProcess(new WebRequest.SuccessProcess<ApiV1SpecialSendPointPostData>() {
            @Override
            public void run(String data, ApiV1SpecialSendPointPostData information) {
                sendPointSuccess.sendPointSuccess(information);
                if (information.result == 0) {
                    String content = context.getString(R.string.phone_send_point_send_point_success);
                    Toast.makeText(context, content, Toast.LENGTH_LONG).show();
                } else if (information.result == 1) {
                    String content = context.getString(R.string.phone_send_point_send_point_fail);
                    Toast.makeText(context, content, Toast.LENGTH_LONG).show();
                }
            }
        }).start();
    }

    public void setSendPointSuccess(SendPointSuccess success) {
        this.sendPointSuccess = success;
    }

    public interface SendPointSuccess {
        void sendPointSuccess(ApiV1SpecialSendPointPostData information);
    }
}
