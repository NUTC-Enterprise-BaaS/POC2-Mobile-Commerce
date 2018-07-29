package com.poc2.contrube.controllor.phone;

import android.content.Context;
import android.util.Log;
import android.widget.Toast;

import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.androidlibrary.module.ApiParams;
import com.androidlibrary.module.backend.api.ApiV1PremiumPhoneSendPointPost;
import com.androidlibrary.module.backend.api.ApiV1SpecialPointCheckGet;
import com.androidlibrary.module.backend.data.ApiV1PremiumPhoneSendPointPostData;
import com.androidlibrary.module.backend.data.ApiV1SpecialPointCheckGetData;
import com.androidlibrary.module.backend.data.ErrorProcessingData;
import com.androidlibrary.module.backend.params.AccountInjection;
import com.androidlibrary.module.backend.params.ServerInfoInjection;
import com.androidlibrary.module.backend.request.WebRequest;
import com.poc2.R;

/**
 * Created by 依杰 on 2016/11/30.
 */

public class FragmentPremiumPhoneSendControllor {
    private Context context;
    private AccountInjection accountInjection;
    private ServerInfoInjection serverInfoInjection;
    private ApiParams apiParams;
    private CallBackEvent callBackEvent;
    private SendPointSuccess sendPointSuccess;

    public FragmentPremiumPhoneSendControllor(Context context) {
        this.context = context;
        accountInjection = new AccountInjection(context);
        serverInfoInjection = new ServerInfoInjection();
        apiParams = new ApiParams(serverInfoInjection, accountInjection);
    }

    public void checkApi(String encryptionResult) {
        apiParams.inputAesEncode = encryptionResult;

        WebRequest<ApiV1SpecialPointCheckGetData> request = new ApiV1SpecialPointCheckGet<>(context, apiParams);
        request.processing(new WebRequest.Processing<ApiV1SpecialPointCheckGetData>() {
            @Override
            public ApiV1SpecialPointCheckGetData run(String data) {
                return new ApiV1SpecialPointCheckGetData(data);
            }
        }).failProcess(new WebRequest.FailProcess<ApiV1SpecialPointCheckGetData>() {
            @Override
            public void run(String data, ApiV1SpecialPointCheckGetData information) {
                ErrorProcessingData.run(context, data, information);
            }
        }).unknownFailRequest(new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                String content = context.getString(R.string.request_load_fail);
                Toast.makeText(context, content, Toast.LENGTH_LONG).show();
            }
        }).successProcess(new WebRequest.SuccessProcess<ApiV1SpecialPointCheckGetData>() {
            @Override
            public void run(String data, ApiV1SpecialPointCheckGetData information) {
                if (information.result == 0) {
                    onSuccess();
                } else {
                    String content = context.getString(R.string.phone_send_point_check_fail);
                    Toast.makeText(context, content, Toast.LENGTH_LONG).show();
                }
            }
        }).start();
    }

    private void onSuccess() {
        callBackEvent.onSuccess();
    }

    public void setCallBackEvent(CallBackEvent event) {
        this.callBackEvent = event;
    }

    public interface CallBackEvent {
        void onSuccess();

        void onError();
    }

    public void sendPoint(String point, String allNumber) {
        apiParams.inputPhoneNumber = allNumber;
        apiParams.inputBonus = point;
        Log.e("point", apiParams.inputBonus);

        WebRequest<ApiV1PremiumPhoneSendPointPostData> request = new ApiV1PremiumPhoneSendPointPost<>(context, apiParams);
        request.processing(new WebRequest.Processing<ApiV1PremiumPhoneSendPointPostData>() {
            @Override
            public ApiV1PremiumPhoneSendPointPostData run(String data) {
                return new ApiV1PremiumPhoneSendPointPostData(data);
            }
        }).failProcess(new WebRequest.FailProcess<ApiV1PremiumPhoneSendPointPostData>() {
            @Override
            public void run(String data, ApiV1PremiumPhoneSendPointPostData information) {
                ErrorProcessingData.run(context, data, information);
            }
        }).unknownFailRequest(new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                String content = context.getString(R.string.request_load_fail);
                Toast.makeText(context, content, Toast.LENGTH_LONG).show();
            }
        }).successProcess(new WebRequest.SuccessProcess<ApiV1PremiumPhoneSendPointPostData>() {
            @Override
            public void run(String data, ApiV1PremiumPhoneSendPointPostData information) {
                if (information.result == 0) {
                    String content = context.getString(R.string.phone_send_point_deduct_point_success);
                    Toast.makeText(context, content, Toast.LENGTH_LONG).show();
                    sendSuccess();
                } else {
                    String content = context.getString(R.string.phone_send_point_deduct_point_fail);
                    Toast.makeText(context, content, Toast.LENGTH_LONG).show();
                }
            }
        }).start();
    }

    public void setSendPointSuccess(SendPointSuccess success) {
        this.sendPointSuccess = success;
    }

    private void sendSuccess() {
        sendPointSuccess.onSuccess();
    }

    public interface SendPointSuccess {
        void onSuccess();

        void onError();
    }
}
