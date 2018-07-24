package com.herbhousesgobuyother.contrube.controllor.point;

import android.content.Context;
import android.util.Log;
import android.widget.Toast;

import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.androidlibrary.module.ApiParams;
import com.androidlibrary.module.StateBundle;
import com.androidlibrary.module.backend.api.ApiV1PreferentialPointDeductPost;
import com.androidlibrary.module.backend.api.ApiV1PremiumCsvCheckPost;
import com.androidlibrary.module.backend.api.ApiV1PremiumPointPost;
import com.androidlibrary.module.backend.data.ApiV1PreferentialPointDeductPostData;
import com.androidlibrary.module.backend.data.ApiV1PremiumCsvCheckPostData;
import com.androidlibrary.module.backend.data.ApiV1PremiumPointPostData;
import com.androidlibrary.module.backend.data.ErrorProcessingData;
import com.androidlibrary.module.backend.params.AccountInjection;
import com.androidlibrary.module.backend.params.ServerInfoInjection;
import com.androidlibrary.module.backend.request.WebRequest;
import com.herbhousesgobuyother.R;
import com.herbhousesgobuyother.contrube.component.dialog.LoadingDialog;

import java.util.Calendar;

/**
 * Created by 依杰 on 2016/11/30.
 */

public class PremiumPointControllor {
    private Context context;
    private AccountInjection accountInjection;
    private ServerInfoInjection serverInfoInjection;
    private ApiParams apiParams;
    private LoadingDialog loadingDialog;
    private Long timestampStart;
    private Long timestampEnd;
    private CallBackEvent mCallBackEvent;

    public PremiumPointControllor(Context context) {
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

        WebRequest<ApiV1PremiumPointPostData> request = new ApiV1PremiumPointPost<>(context, apiParams);
        request.processing(new WebRequest.Processing<ApiV1PremiumPointPostData>() {
            @Override
            public ApiV1PremiumPointPostData run(String data) {
                return new ApiV1PremiumPointPostData(data);
            }
        }).failProcess(new WebRequest.FailProcess<ApiV1PremiumPointPostData>() {
            @Override
            public void run(String data, ApiV1PremiumPointPostData information) {
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
        }).successProcess(new WebRequest.SuccessProcess<ApiV1PremiumPointPostData>() {
            @Override
            public void run(String data, ApiV1PremiumPointPostData information) {
                if (information.result == 0) {
                    mCallBackEvent.onSuccessRequestPoint(information, bundle);
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

    public void sendPoint(String point, String id, String receive) {
        if (Integer.valueOf(id) <= 0)
            return;
        apiParams.inputTransactionId = id;
        apiParams.inputPhoneNumber = receive;
        apiParams.inputBonus = point;

        WebRequest<ApiV1PreferentialPointDeductPostData> request = new ApiV1PreferentialPointDeductPost<>(context, apiParams);
        request.processing(new WebRequest.Processing<ApiV1PreferentialPointDeductPostData>() {
            @Override
            public ApiV1PreferentialPointDeductPostData run(String data) {
                return new ApiV1PreferentialPointDeductPostData(data);
            }
        }).failProcess(new WebRequest.FailProcess<ApiV1PreferentialPointDeductPostData>() {
            @Override
            public void run(String data, ApiV1PreferentialPointDeductPostData information) {
                ErrorProcessingData.run(context, data, information);
            }
        }).unknownFailRequest(new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                String content = context.getString(R.string.request_load_fail);
                Toast.makeText(context, content, Toast.LENGTH_LONG).show();
            }
        }).successProcess(new WebRequest.SuccessProcess<ApiV1PreferentialPointDeductPostData>() {
            @Override
            public void run(String data, ApiV1PreferentialPointDeductPostData information) {
                mCallBackEvent.onSuccessSendPoint(information);
                if (information.result == 0) {
                    String content = context.getString(R.string.phone_send_point_deduct_point_success);
                    Toast.makeText(context, content, Toast.LENGTH_LONG).show();
                } else if (information.result == 1) {
                    String content = context.getString(R.string.phone_send_point_deduct_point_fail);
                    Toast.makeText(context, content, Toast.LENGTH_LONG).show();
                }
            }
        }).start();
    }

    public void checkPassword(String password) {
        loadingDialog.show();
        apiParams.inputPassword = password;

        WebRequest<ApiV1PremiumCsvCheckPostData> request = new ApiV1PremiumCsvCheckPost<>(context, apiParams);
        request.processing(new WebRequest.Processing<ApiV1PremiumCsvCheckPostData>() {
            @Override
            public ApiV1PremiumCsvCheckPostData run(String data) {
                loadingDialog.dismiss();
                return new ApiV1PremiumCsvCheckPostData(data);
            }
        }).failProcess(new WebRequest.FailProcess<ApiV1PremiumCsvCheckPostData>() {
            @Override
            public void run(String data, ApiV1PremiumCsvCheckPostData information) {
                ErrorProcessingData.run(context, data, information);
                loadingDialog.dismiss();
            }
        }).unknownFailRequest(new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                String content = context.getString(R.string.request_load_fail);
                Toast.makeText(context, content, Toast.LENGTH_LONG).show();
                loadingDialog.dismiss();
            }
        }).successProcess(new WebRequest.SuccessProcess<ApiV1PremiumCsvCheckPostData>() {
            @Override
            public void run(String data, ApiV1PremiumCsvCheckPostData information) {
                loadingDialog.dismiss();
                if (information.result == 0) {
                    mCallBackEvent.onSuccessCheckPassword(information);
                    String content = context.getString(R.string.set_recommend_dialog_success_title);
                    Toast.makeText(context, content, Toast.LENGTH_LONG).show();
                } else if (information.result == 1) {
                    String content = context.getString(R.string.csv_dialog_login_fail);
                    Toast.makeText(context, content, Toast.LENGTH_LONG).show();
                }
            }
        }).start();
    }

    public void setCallBackEvent(CallBackEvent callBackEvent) {
        this.mCallBackEvent = callBackEvent;
    }

    public interface CallBackEvent {
        void onError();

        void onSuccessRequestPoint(ApiV1PremiumPointPostData information, StateBundle bundle);

        void onSuccessSendPoint(ApiV1PreferentialPointDeductPostData information);

        void onSuccessCheckPassword(ApiV1PremiumCsvCheckPostData information);
    }
}
