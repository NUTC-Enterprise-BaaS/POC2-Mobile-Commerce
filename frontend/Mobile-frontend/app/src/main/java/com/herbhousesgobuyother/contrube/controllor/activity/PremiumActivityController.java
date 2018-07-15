package com.herbhousesgobuyother.contrube.controllor.activity;

import android.content.Context;
import android.widget.Toast;

import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.androidlibrary.module.ApiParams;
import com.androidlibrary.module.backend.api.ApiV1PreferentialActivityGet;
import com.androidlibrary.module.backend.data.ApiV1PreferentialActivityGetData;
import com.androidlibrary.module.backend.data.ErrorProcessingData;
import com.androidlibrary.module.backend.params.AccountInjection;
import com.androidlibrary.module.backend.params.ServerInfoInjection;
import com.androidlibrary.module.backend.request.WebRequest;
import com.herbhousesgobuyother.R;
import com.herbhousesgobuyother.contrube.component.dialog.LoadingDialog;
import com.herbhousesgobuyother.contrube.model.SequenceLoadLogic;

public class PremiumActivityController {
    private final String TAG = PremiumActivityController.class.getName();
    private Context context;
    private AccountInjection accountInjection;
    private ServerInfoInjection serverInfoInjection;
    private ApiParams apiParams;
    private LoadingDialog loadingDialog;
    private PremiumActivityController.CallBackEvent mCallBackEvent;
    private SequenceLoadLogic loadLogic;

    public PremiumActivityController(Context context) {
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
        WebRequest<ApiV1PreferentialActivityGetData> request = new ApiV1PreferentialActivityGet<>(context, apiParams);
        request.processing(new WebRequest.Processing<ApiV1PreferentialActivityGetData>() {
            @Override
            public ApiV1PreferentialActivityGetData run(String data) {
                return new ApiV1PreferentialActivityGetData(data);
            }
        }).failProcess(new WebRequest.FailProcess<ApiV1PreferentialActivityGetData>() {
            @Override
            public void run(String data, ApiV1PreferentialActivityGetData information) {
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
        }).successProcess(new WebRequest.SuccessProcess<ApiV1PreferentialActivityGetData>() {
            @Override
            public void run(String data, ApiV1PreferentialActivityGetData information) {
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

        void onSuccess(ApiV1PreferentialActivityGetData information);


    }
}