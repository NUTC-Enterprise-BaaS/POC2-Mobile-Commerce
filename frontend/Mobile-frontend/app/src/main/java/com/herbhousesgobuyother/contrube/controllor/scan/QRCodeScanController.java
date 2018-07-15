package com.herbhousesgobuyother.contrube.controllor.scan;

import android.content.Context;

import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.androidlibrary.module.ApiParams;
import com.androidlibrary.module.backend.api.ApiV1BoundsSendPost;
import com.androidlibrary.module.backend.data.ApiV1BoundsSendData;
import com.androidlibrary.module.backend.data.ErrorProcessingData;
import com.androidlibrary.module.backend.params.AccountInjection;
import com.androidlibrary.module.backend.params.ServerInfoInjection;
import com.androidlibrary.module.backend.request.WebRequest;
import com.herbhousesgobuyother.contrube.component.dialog.LoadingDialog;

/**
 * Created by Gary on 2016/11/9.
 */

public class QRCodeScanController {
    private final String TAG = QRCodeScanController.class.getName();
    private Context context;
    private AccountInjection accountInjection;
    private ServerInfoInjection serverInfoInjection;
    private ApiParams apiParams;
    private LoadingDialog loadingDialog;
    private QRCodeScanController.CallBackEvent mCallBackEvent;

    public QRCodeScanController(Context context) {
        this.context = context;
        accountInjection = new AccountInjection(context);
        serverInfoInjection = new ServerInfoInjection();
        loadingDialog = new LoadingDialog(context);
        apiParams = new ApiParams(serverInfoInjection, accountInjection);
    }

    public void scanRequest(String shopId) {
        loadingDialog.show();
        apiParams.inputStoreId = shopId;
        WebRequest<ApiV1BoundsSendData> request = new ApiV1BoundsSendPost<>(context, apiParams);
        request.processing(processingData)
                .failProcess(failProcessingData)
                .unknownFailRequest(failUnknownReason)
                .successProcess(successResponse)
                .start();
    }

    public WebRequest.Processing<ApiV1BoundsSendData> processingData = new WebRequest.Processing<ApiV1BoundsSendData>() {
        @Override
        public ApiV1BoundsSendData run(String data) {
            return new ApiV1BoundsSendData(data);
        }
    };

    public WebRequest.FailProcess<ApiV1BoundsSendData> failProcessingData = new WebRequest.FailProcess<ApiV1BoundsSendData>() {
        @Override
        public void run(String data, ApiV1BoundsSendData information) {
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

    private WebRequest.SuccessProcess<ApiV1BoundsSendData> successResponse = new WebRequest.SuccessProcess<ApiV1BoundsSendData>() {
        @Override
        public void run(String data, ApiV1BoundsSendData information) {
            loadingDialog.dismiss();
            if (null != mCallBackEvent)
                mCallBackEvent.onSuccess(information);
        }
    };

    public void setmCallBackEvent(QRCodeScanController.CallBackEvent callBackEvent) {
        this.mCallBackEvent = callBackEvent;
    }


    public interface CallBackEvent {
        void onError();

        void onSuccess(ApiV1BoundsSendData information);

    }
}
