package com.herbhousesgobuyother.contrube.controllor.recommend;

import android.content.Context;

import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.androidlibrary.module.ApiParams;
import com.androidlibrary.module.backend.api.ApiV1IdPhonePost;
import com.androidlibrary.module.backend.data.ApiV1IdPhoneData;
import com.androidlibrary.module.backend.data.ErrorProcessingData;
import com.androidlibrary.module.backend.params.AccountInjection;
import com.androidlibrary.module.backend.params.ServerInfoInjection;
import com.androidlibrary.module.backend.request.WebRequest;
import com.herbhousesgobuyother.contrube.component.dialog.LoadingDialog;

/**
 * Created by Gary on 2016/11/9.
 */

public class NormalRegisterRecommendController {
    private final String TAG = NormalRegisterRecommendController.class.getName();
    private Context context;
    private AccountInjection accountInjection;
    private ServerInfoInjection serverInfoInjection;
    private ApiParams apiParams;
    private LoadingDialog loadingDialog;
    private NormalRegisterRecommendController.CallBackEvent mCallBackEvent;

    public NormalRegisterRecommendController(Context context) {
        this.context = context;
        accountInjection = new AccountInjection(context);
        serverInfoInjection = new ServerInfoInjection();
        loadingDialog = new LoadingDialog(context);
        apiParams = new ApiParams(serverInfoInjection, accountInjection);
    }

    public void scanRequest(String itemId) {
        loadingDialog.show();
        apiParams.inputId = itemId;
        WebRequest<ApiV1IdPhoneData> request = new ApiV1IdPhonePost<>(context, apiParams);
        request.processing(processingData)
                .failProcess(failProcessingData)
                .unknownFailRequest(failUnknownReason)
                .successProcess(successResponse)
                .start();
    }

    public WebRequest.Processing<ApiV1IdPhoneData> processingData = new WebRequest.Processing<ApiV1IdPhoneData>() {
        @Override
        public ApiV1IdPhoneData run(String data) {
            return new ApiV1IdPhoneData(data);
        }
    };

    public WebRequest.FailProcess<ApiV1IdPhoneData> failProcessingData = new WebRequest.FailProcess<ApiV1IdPhoneData>() {
        @Override
        public void run(String data, ApiV1IdPhoneData information) {
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

    private WebRequest.SuccessProcess<ApiV1IdPhoneData> successResponse = new WebRequest.SuccessProcess<ApiV1IdPhoneData>() {
        @Override
        public void run(String data, ApiV1IdPhoneData information) {
            loadingDialog.dismiss();
            if (null != mCallBackEvent) {
                mCallBackEvent.onSuccess(information);
            }
        }
    };

    public void setmCallBackEvent(NormalRegisterRecommendController.CallBackEvent callBackEvent) {
        this.mCallBackEvent = callBackEvent;
    }


    public interface CallBackEvent {
        void onError();

        void onSuccess(ApiV1IdPhoneData information);
    }
}
