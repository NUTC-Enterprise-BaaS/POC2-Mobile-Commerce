package com.herbhousesgobuyother.contrube.controllor.activity;

import android.content.Context;
import android.widget.Toast;

import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.androidlibrary.module.ApiParams;
import com.androidlibrary.module.backend.api.ApiV1SpecialActivityGet;
import com.androidlibrary.module.backend.data.ApiV1SpecialActivityGetData;
import com.androidlibrary.module.backend.data.ErrorProcessingData;
import com.androidlibrary.module.backend.params.AccountInjection;
import com.androidlibrary.module.backend.params.ServerInfoInjection;
import com.androidlibrary.module.backend.request.WebRequest;
import com.herbhousesgobuyother.R;
import com.herbhousesgobuyother.contrube.component.dialog.LoadingDialog;
import com.herbhousesgobuyother.contrube.model.SequenceLoadLogic;

public class SpecialActivityController {
    private final String TAG = SpecialActivityController.class.getName();
    private Context context;
    private AccountInjection accountInjection;
    private ServerInfoInjection serverInfoInjection;
    private ApiParams apiParams;
    private LoadingDialog loadingDialog;
    private SpecialActivityController.CallBackEvent mCallBackEvent;
    private SequenceLoadLogic loadLogic;

    public SpecialActivityController(Context context) {
        this.context = context;
        accountInjection = new AccountInjection(context);
        serverInfoInjection = new ServerInfoInjection();
        loadingDialog = new LoadingDialog(context);
        apiParams = new ApiParams(serverInfoInjection, accountInjection);
        loadLogic = new SequenceLoadLogic();
    }

    public void activityRequest() {
        loadLogic.next();
        apiParams.inputStart = String.valueOf(0);
        apiParams.inputEnd = String.valueOf(loadLogic.getEnd());

        loadingDialog.show();
        WebRequest<ApiV1SpecialActivityGetData> request = new ApiV1SpecialActivityGet<>(context, apiParams);
        request.processing(new WebRequest.Processing<ApiV1SpecialActivityGetData>() {
            @Override
            public ApiV1SpecialActivityGetData run(String data) {
                return new ApiV1SpecialActivityGetData(data);
            }
        }).failProcess(new WebRequest.FailProcess<ApiV1SpecialActivityGetData>() {
            @Override
            public void run(String data, ApiV1SpecialActivityGetData information) {
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
        }).successProcess(new WebRequest.SuccessProcess<ApiV1SpecialActivityGetData>() {
            @Override
            public void run(String data, ApiV1SpecialActivityGetData information) {
                loadingDialog.dismiss();
                if (null!=mCallBackEvent) {
                    mCallBackEvent.onSuccess(information);
                }
            }
        }).start();
    }

    public void setCallBackEvent(CallBackEvent callBackEvent) {
        this.mCallBackEvent = callBackEvent;
    }

    public interface CallBackEvent {
        void onError();

        void onSuccess(ApiV1SpecialActivityGetData information);


    }
}