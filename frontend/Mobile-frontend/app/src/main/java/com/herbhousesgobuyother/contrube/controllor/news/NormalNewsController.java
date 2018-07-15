package com.herbhousesgobuyother.contrube.controllor.news;

import android.content.Context;
import android.util.Log;
import android.widget.Toast;

import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.androidlibrary.module.ApiParams;
import com.androidlibrary.module.SequenceLoadLogic;
import com.androidlibrary.module.backend.api.ApiV1GeneralNewsGet;
import com.androidlibrary.module.backend.api.ApiV1GeneralNewsIdGet;
import com.androidlibrary.module.backend.data.ApiV1GeneralNewsGetData;
import com.androidlibrary.module.backend.data.ApiV1GeneralNewsIdGetData;
import com.androidlibrary.module.backend.data.ErrorProcessingData;
import com.androidlibrary.module.backend.params.AccountInjection;
import com.androidlibrary.module.backend.params.ServerInfoInjection;
import com.androidlibrary.module.backend.request.WebRequest;
import com.herbhousesgobuyother.R;
import com.herbhousesgobuyother.contrube.component.dialog.LoadingDialog;
import com.herbhousesgobuyother.contrube.component.dialog.LoginErrorDialog;

public class NormalNewsController {
    private final String TAG = NormalNewsController.class.getName();
    private Context context;
    private AccountInjection accountInjection;
    private ServerInfoInjection serverInfoInjection;
    private ApiParams apiParams;
    private LoadingDialog loadingDialog;
    private LoginErrorDialog loginErrorDialog;
    private NormalNewsController.CallBackEvent mCallBackEvent;
    private SequenceLoadLogic loadLogic;

    public NormalNewsController(Context context) {
        this.context = context;
        accountInjection = new AccountInjection(context);
        serverInfoInjection = new ServerInfoInjection();
        loadingDialog = new LoadingDialog(context);
        apiParams = new ApiParams(serverInfoInjection, accountInjection);
        loadLogic = new SequenceLoadLogic();
    }

    public WebRequest.Processing<ApiV1GeneralNewsIdGetData> processingData = new WebRequest.Processing<ApiV1GeneralNewsIdGetData>() {
        @Override
        public ApiV1GeneralNewsIdGetData run(String data) {
            return new ApiV1GeneralNewsIdGetData(data);
        }
    };

    public WebRequest.FailProcess<ApiV1GeneralNewsIdGetData> failProcessingData = new WebRequest.FailProcess<ApiV1GeneralNewsIdGetData>() {
        @Override
        public void run(String data, ApiV1GeneralNewsIdGetData information) {
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

    private WebRequest.SuccessProcess<ApiV1GeneralNewsIdGetData> successResponse = new WebRequest.SuccessProcess<ApiV1GeneralNewsIdGetData>() {
        @Override
        public void run(String data, ApiV1GeneralNewsIdGetData information) {
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
        WebRequest<ApiV1GeneralNewsIdGetData> request = new ApiV1GeneralNewsIdGet<>(context, params);
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
        WebRequest<ApiV1GeneralNewsGetData> request = new ApiV1GeneralNewsGet<>(context, apiParams);
        request.processing(new WebRequest.Processing<ApiV1GeneralNewsGetData>() {
            @Override
            public ApiV1GeneralNewsGetData run(String data) {
                return new ApiV1GeneralNewsGetData(data);
            }
        }).failProcess(new WebRequest.FailProcess<ApiV1GeneralNewsGetData>() {
            @Override
            public void run(String data, ApiV1GeneralNewsGetData information) {
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
        }).successProcess(new WebRequest.SuccessProcess<ApiV1GeneralNewsGetData>() {
            @Override
            public void run(String data, ApiV1GeneralNewsGetData information) {
                loadingDialog.dismiss();
                update(information);
            }
        }).start();
    }


    /**
     * 最新消息更新
     */
    private void update(ApiV1GeneralNewsGetData information) {
        if (null != mCallBackEvent)
            mCallBackEvent.onSuccess(information);
    }


    public void setCallBackEvent(CallBackEvent callBackEvent) {
        this.mCallBackEvent = callBackEvent;
    }

    public interface CallBackEvent {
        void onError();

        void onSuccess(ApiV1GeneralNewsGetData information);

        void onSuccess(ApiV1GeneralNewsIdGetData information);

    }
}