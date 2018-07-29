package com.poc2.contrube.controllor.news;

import android.content.Context;
import android.util.Log;
import android.widget.Toast;

import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.androidlibrary.module.ApiParams;
import com.androidlibrary.module.SequenceLoadLogic;
import com.androidlibrary.module.backend.api.ApiV1PremiumNewsGet;
import com.androidlibrary.module.backend.api.ApiV1PremiumNewsIdGet;
import com.androidlibrary.module.backend.data.ApiV1PremiumNewsGetData;
import com.androidlibrary.module.backend.data.ApiV1PremiumNewsIdGetData;
import com.androidlibrary.module.backend.data.ErrorProcessingData;
import com.androidlibrary.module.backend.params.AccountInjection;
import com.androidlibrary.module.backend.params.ServerInfoInjection;
import com.androidlibrary.module.backend.request.WebRequest;
import com.poc2.R;
import com.poc2.contrube.component.dialog.LoadingDialog;
import com.poc2.contrube.component.dialog.LoginErrorDialog;

public class PremiumNewsController {
    private final String TAG = PremiumNewsController.class.getName();
    private Context context;
    private AccountInjection accountInjection;
    private ServerInfoInjection serverInfoInjection;
    private ApiParams apiParams;
    private LoadingDialog loadingDialog;
    private LoginErrorDialog loginErrorDialog;
    private PremiumNewsController.CallBackEvent mCallBackEvent;
    private SequenceLoadLogic loadLogic;

    public PremiumNewsController(Context context) {
        this.context = context;
        accountInjection = new AccountInjection(context);
        serverInfoInjection = new ServerInfoInjection();
        loadingDialog = new LoadingDialog(context);
        apiParams = new ApiParams(serverInfoInjection, accountInjection);
        loadLogic = new SequenceLoadLogic();
    }

    public WebRequest.Processing<ApiV1PremiumNewsIdGetData> processingData = new WebRequest.Processing<ApiV1PremiumNewsIdGetData>() {
        @Override
        public ApiV1PremiumNewsIdGetData run(String data) {
            return new ApiV1PremiumNewsIdGetData(data);
        }
    };

    public WebRequest.FailProcess<ApiV1PremiumNewsIdGetData> failProcessingData = new WebRequest.FailProcess<ApiV1PremiumNewsIdGetData>() {
        @Override
        public void run(String data, ApiV1PremiumNewsIdGetData information) {
            loadingDialog.dismiss();
            ErrorProcessingData.run(context, data, information);
        }
    };

    private Response.ErrorListener failUnknownReason = new Response.ErrorListener() {
        @Override
        public void onErrorResponse(VolleyError error) {
            loadingDialog.dismiss();
        }
    };

    private WebRequest.SuccessProcess<ApiV1PremiumNewsIdGetData> successResponse = new WebRequest.SuccessProcess<ApiV1PremiumNewsIdGetData>() {
        @Override
        public void run(String data, ApiV1PremiumNewsIdGetData information) {
            loadingDialog.dismiss();
            if (null != mCallBackEvent)
                mCallBackEvent.onSuccess(information);
        }
    };

    public void newsInfoRequest(String newsId) {
        loadingDialog.show();
        ApiParams params = new ApiParams(serverInfoInjection, accountInjection);
        params.inputId = newsId;
        Log.e("params.inputId", params.inputId);
        WebRequest<ApiV1PremiumNewsIdGetData> request = new ApiV1PremiumNewsIdGet<>(context, params);
        request.processing(processingData)
                .failProcess(failProcessingData)
                .unknownFailRequest(failUnknownReason)
                .successProcess(successResponse)
                .start();
    }

    public void newsRequest() {
        loadLogic.next();

        apiParams.inputStart = String.valueOf(0);
        apiParams.inputEnd = String.valueOf(loadLogic.getEnd());

        loadingDialog.show();
        WebRequest<ApiV1PremiumNewsGetData> request = new ApiV1PremiumNewsGet<>(context, apiParams);
        request.processing(new WebRequest.Processing<ApiV1PremiumNewsGetData>() {
            @Override
            public ApiV1PremiumNewsGetData run(String data) {
                return new ApiV1PremiumNewsGetData(data);
            }
        }).failProcess(new WebRequest.FailProcess<ApiV1PremiumNewsGetData>() {
            @Override
            public void run(String data, ApiV1PremiumNewsGetData information) {
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
        }).successProcess(new WebRequest.SuccessProcess<ApiV1PremiumNewsGetData>() {
            @Override
            public void run(String data, ApiV1PremiumNewsGetData information) {
                loadingDialog.dismiss();
                update(information);
            }
        }).start();
    }


    /**
     * 最新消息更新
     */
    private void update(ApiV1PremiumNewsGetData information) {
        if (null != mCallBackEvent)
            mCallBackEvent.onSuccess(information);
    }


    public void setCallBackEvent(CallBackEvent callBackEvent) {
        this.mCallBackEvent = callBackEvent;
    }

    public interface CallBackEvent {
        void onError();

        void onSuccess(ApiV1PremiumNewsGetData information);

        void onSuccess(ApiV1PremiumNewsIdGetData information);

    }
}