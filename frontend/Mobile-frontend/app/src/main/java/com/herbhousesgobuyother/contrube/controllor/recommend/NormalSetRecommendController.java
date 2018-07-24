package com.herbhousesgobuyother.contrube.controllor.recommend;

import android.content.Context;

import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.androidlibrary.module.ApiParams;
import com.androidlibrary.module.backend.api.ApiV1GeneralRecommendSetPost;
import com.androidlibrary.module.backend.data.ApiV1GeneralRecommendSetData;
import com.androidlibrary.module.backend.params.AccountInjection;
import com.androidlibrary.module.backend.params.ServerInfoInjection;
import com.androidlibrary.module.backend.request.WebRequest;
import com.herbhousesgobuyother.contrube.component.dialog.LoadingDialog;

/**
 * Created by Gary on 2016/11/9.
 */

public class NormalSetRecommendController {
    private final String TAG = NormalSetRecommendController.class.getName();
    private Context context;
    private AccountInjection accountInjection;
    private ServerInfoInjection serverInfoInjection;
    private ApiParams apiParams;
    private LoadingDialog loadingDialog;
    private NormalSetRecommendController.CallBackEvent mCallBackEvent;

    public NormalSetRecommendController(Context context) {
        this.context = context;
        accountInjection = new AccountInjection(context);
        serverInfoInjection = new ServerInfoInjection();
        loadingDialog = new LoadingDialog(context);
        apiParams = new ApiParams(serverInfoInjection, accountInjection);
    }

    public void scanRequest(String itemId) {
        loadingDialog.show();
        apiParams.inputRecommendQrCode = itemId;
        WebRequest<ApiV1GeneralRecommendSetData> request = new ApiV1GeneralRecommendSetPost<>(context, apiParams);
        request.processing(processingData)
                .failProcess(failProcessingData)
                .unknownFailRequest(failUnknownReason)
                .successProcess(successResponse)
                .start();
    }

    public WebRequest.Processing<ApiV1GeneralRecommendSetData> processingData = new WebRequest.Processing<ApiV1GeneralRecommendSetData>() {
        @Override
        public ApiV1GeneralRecommendSetData run(String data) {
            return new ApiV1GeneralRecommendSetData(data);
        }
    };

    public WebRequest.FailProcess<ApiV1GeneralRecommendSetData> failProcessingData = new WebRequest.FailProcess<ApiV1GeneralRecommendSetData>() {
        @Override
        public void run(String data, ApiV1GeneralRecommendSetData information) {
            loadingDialog.dismiss();

        }
    };

    private Response.ErrorListener failUnknownReason = new Response.ErrorListener() {
        @Override
        public void onErrorResponse(VolleyError error) {
            loadingDialog.dismiss();
        }
    };

    private WebRequest.SuccessProcess<ApiV1GeneralRecommendSetData> successResponse = new WebRequest.SuccessProcess<ApiV1GeneralRecommendSetData>() {
        @Override
        public void run(String data, ApiV1GeneralRecommendSetData information) {
            loadingDialog.dismiss();
            if (null != mCallBackEvent) {
                mCallBackEvent.onSuccess(information);
            }
        }
    };

    public void setmCallBackEvent(NormalSetRecommendController.CallBackEvent callBackEvent) {
        this.mCallBackEvent = callBackEvent;
    }


    public interface CallBackEvent {
        void onError();

        void onSuccess(ApiV1GeneralRecommendSetData information);
    }
}
