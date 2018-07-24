package com.herbhousesgobuyother.contrube.controllor.news;

import android.content.Context;
import android.util.Log;
import android.widget.Toast;

import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.androidlibrary.module.ApiParams;
import com.androidlibrary.module.SequenceLoadLogic;
import com.androidlibrary.module.backend.api.ApiV1SpecialNewsGet;
import com.androidlibrary.module.backend.api.ApiV1SpecialNewsIdGet;
import com.androidlibrary.module.backend.data.ApiV1SpecialNewsGetData;
import com.androidlibrary.module.backend.data.ApiV1SpecialNewsIdGetData;
import com.androidlibrary.module.backend.data.ErrorProcessingData;
import com.androidlibrary.module.backend.params.AccountInjection;
import com.androidlibrary.module.backend.params.ServerInfoInjection;
import com.androidlibrary.module.backend.request.WebRequest;
import com.herbhousesgobuyother.R;
import com.herbhousesgobuyother.contrube.component.dialog.LoadingDialog;
import com.herbhousesgobuyother.contrube.component.dialog.LoginErrorDialog;

public class SpecialNewsController {
    private final String TAG = SpecialNewsController.class.getName();
    private Context context;
    private AccountInjection accountInjection;
    private ServerInfoInjection serverInfoInjection;
    private ApiParams apiParams;
    private LoadingDialog loadingDialog;
    private LoginErrorDialog loginErrorDialog;
    private SpecialNewsController.CallBackEvent mCallBackEvent;
    private SequenceLoadLogic loadLogic;

    public SpecialNewsController(Context context) {
        this.context = context;
        accountInjection = new AccountInjection(context);
        serverInfoInjection = new ServerInfoInjection();
        loadingDialog = new LoadingDialog(context);
        apiParams = new ApiParams(serverInfoInjection, accountInjection);
        loadLogic = new SequenceLoadLogic();
    }

    public WebRequest.Processing<ApiV1SpecialNewsIdGetData> processingData = new WebRequest.Processing<ApiV1SpecialNewsIdGetData>() {
        @Override
        public ApiV1SpecialNewsIdGetData run(String data) {
            return new ApiV1SpecialNewsIdGetData(data);
        }
    };

    public WebRequest.FailProcess<ApiV1SpecialNewsIdGetData> failProcessingData = new WebRequest.FailProcess<ApiV1SpecialNewsIdGetData>() {
        @Override
        public void run(String data, ApiV1SpecialNewsIdGetData information) {
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

    private WebRequest.SuccessProcess<ApiV1SpecialNewsIdGetData> successResponse = new WebRequest.SuccessProcess<ApiV1SpecialNewsIdGetData>() {
        @Override
        public void run(String data, ApiV1SpecialNewsIdGetData information) {
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
        WebRequest<ApiV1SpecialNewsIdGetData> request = new ApiV1SpecialNewsIdGet<>(context, params);
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
        WebRequest<ApiV1SpecialNewsGetData> request = new ApiV1SpecialNewsGet<>(context, apiParams);
        request.processing(new WebRequest.Processing<ApiV1SpecialNewsGetData>() {
            @Override
            public ApiV1SpecialNewsGetData run(String data) {
                return new ApiV1SpecialNewsGetData(data);
            }
        }).failProcess(new WebRequest.FailProcess<ApiV1SpecialNewsGetData>() {
            @Override
            public void run(String data, ApiV1SpecialNewsGetData information) {
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
        }).successProcess(new WebRequest.SuccessProcess<ApiV1SpecialNewsGetData>() {
            @Override
            public void run(String data, ApiV1SpecialNewsGetData information) {
                loadingDialog.dismiss();
                update(information);
            }
        }).start();
    }


    /**
     * 最新消息更新
     */
    private void update(ApiV1SpecialNewsGetData information) {
        if (null != mCallBackEvent)
            mCallBackEvent.onSuccess(information);
    }


    public void setCallBackEvent(CallBackEvent callBackEvent) {
        this.mCallBackEvent = callBackEvent;
    }

    public interface CallBackEvent {
        void onError();

        void onSuccess(ApiV1SpecialNewsGetData information);

        void onSuccess(ApiV1SpecialNewsIdGetData information);

    }
}