package com.poc2.contrube.controllor.recommend;

import android.content.Context;

import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.androidlibrary.module.ApiParams;
import com.androidlibrary.module.backend.api.ApiV1SpecialRecommendSetPost;
import com.androidlibrary.module.backend.data.ApiV1SpecialRecommendSetData;
import com.androidlibrary.module.backend.params.AccountInjection;
import com.androidlibrary.module.backend.params.ServerInfoInjection;
import com.androidlibrary.module.backend.request.WebRequest;
import com.poc2.contrube.component.dialog.LoadingDialog;

/**
 * Created by Gary on 2016/11/9.
 */

public class SpecialSetRecommendController {
    private final String TAG = SpecialSetRecommendController.class.getName();
    private Context context;
    private AccountInjection accountInjection;
    private ServerInfoInjection serverInfoInjection;
    private ApiParams apiParams;
    private LoadingDialog loadingDialog;
    private SpecialSetRecommendController.CallBackEvent mCallBackEvent;

    public SpecialSetRecommendController(Context context) {
        this.context = context;
        accountInjection = new AccountInjection(context);
        serverInfoInjection = new ServerInfoInjection();
        loadingDialog = new LoadingDialog(context);
        apiParams = new ApiParams(serverInfoInjection, accountInjection);
    }

    public void scanRequest(String itemId) {
        loadingDialog.show();
        apiParams.inputRecommendQrCode = itemId;
        WebRequest<ApiV1SpecialRecommendSetData> request = new ApiV1SpecialRecommendSetPost<>(context, apiParams);
        request.processing(processingData)
                .failProcess(failProcessingData)
                .unknownFailRequest(failUnknownReason)
                .successProcess(successResponse)
                .start();
    }

    public WebRequest.Processing<ApiV1SpecialRecommendSetData> processingData = new WebRequest.Processing<ApiV1SpecialRecommendSetData>() {
        @Override
        public ApiV1SpecialRecommendSetData run(String data) {
            return new ApiV1SpecialRecommendSetData(data);
        }
    };

    public WebRequest.FailProcess<ApiV1SpecialRecommendSetData> failProcessingData = new WebRequest.FailProcess<ApiV1SpecialRecommendSetData>() {
        @Override
        public void run(String data, ApiV1SpecialRecommendSetData information) {
            loadingDialog.dismiss();

        }
    };

    private Response.ErrorListener failUnknownReason = new Response.ErrorListener() {
        @Override
        public void onErrorResponse(VolleyError error) {
            loadingDialog.dismiss();
        }
    };

    private WebRequest.SuccessProcess<ApiV1SpecialRecommendSetData> successResponse = new WebRequest.SuccessProcess<ApiV1SpecialRecommendSetData>() {
        @Override
        public void run(String data, ApiV1SpecialRecommendSetData information) {
            loadingDialog.dismiss();
            if (null != mCallBackEvent) {
                mCallBackEvent.onSuccess(information);
            }
        }
    };

    public void setmCallBackEvent(SpecialSetRecommendController.CallBackEvent callBackEvent) {
        this.mCallBackEvent = callBackEvent;
    }


    public interface CallBackEvent {
        void onError();

        void onSuccess(ApiV1SpecialRecommendSetData information);
    }
}
