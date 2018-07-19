package com.herbhousesgobuyother.contrube.controllor.qrshow;

import android.content.Context;
import android.widget.Toast;

import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.androidlibrary.module.ApiParams;
import com.androidlibrary.module.backend.api.ApiV1QrCodeShowGet;
import com.androidlibrary.module.backend.data.ApiV1QrCodeShowGetData;
import com.androidlibrary.module.backend.data.ErrorProcessingData;
import com.androidlibrary.module.backend.params.AccountInjection;
import com.androidlibrary.module.backend.params.ServerInfoInjection;
import com.androidlibrary.module.backend.request.WebRequest;
import com.herbhousesgobuyother.R;
import com.herbhousesgobuyother.contrube.component.dialog.LoadingDialog;

/**
 * Created by Gary on 2016/11/9.
 */

public class QRCodeShowController {
    private final String TAG = QRCodeShowController.class.getName();
    private Context context;
    private AccountInjection accountInjection;
    private ServerInfoInjection serverInfoInjection;
    private ApiParams apiParams;
    private LoadingDialog loadingDialog;
    private QRCodeShowController.CallBackEvent mCallBackEvent;

    public QRCodeShowController(Context context) {
        this.context = context;
        accountInjection = new AccountInjection(context);
        serverInfoInjection = new ServerInfoInjection();
        loadingDialog = new LoadingDialog(context);
        apiParams = new ApiParams(serverInfoInjection, accountInjection);
    }

    public void showQrCodeRequest() {
        loadingDialog.show();
        WebRequest<ApiV1QrCodeShowGetData> request = new ApiV1QrCodeShowGet<>(context, apiParams);
        request.processing(processingDataShow)
                .failProcess(failProcessingDataShow)
                .unknownFailRequest(failUnknownReasonShow)
                .successProcess(successResponseShow)
                .start();
    }

    public WebRequest.Processing<ApiV1QrCodeShowGetData> processingDataShow = new WebRequest.Processing<ApiV1QrCodeShowGetData>() {
        @Override
        public ApiV1QrCodeShowGetData run(String data) {
            return new ApiV1QrCodeShowGetData(data);
        }
    };

    public WebRequest.FailProcess<ApiV1QrCodeShowGetData> failProcessingDataShow = new WebRequest.FailProcess<ApiV1QrCodeShowGetData>() {
        @Override
        public void run(String data, ApiV1QrCodeShowGetData information) {
            loadingDialog.dismiss();
            ErrorProcessingData.run(context, data, information);
        }
    };

    private Response.ErrorListener failUnknownReasonShow = new Response.ErrorListener() {
        @Override
        public void onErrorResponse(VolleyError error) {
            loadingDialog.dismiss();
            String content = context.getString(R.string.request_load_fail);
            Toast.makeText(context, content, Toast.LENGTH_LONG).show();
        }
    };

    private WebRequest.SuccessProcess<ApiV1QrCodeShowGetData> successResponseShow = new WebRequest.SuccessProcess<ApiV1QrCodeShowGetData>() {
        @Override
        public void run(String data, ApiV1QrCodeShowGetData information) {
            loadingDialog.dismiss();
            if (null != mCallBackEvent) {
                mCallBackEvent.onSuccess(information);
            }
        }
    };

    public void setmCallBackEvent(QRCodeShowController.CallBackEvent callBackEvent) {
        this.mCallBackEvent = callBackEvent;
    }


    public interface CallBackEvent {
        void onError();

        void onSuccess(ApiV1QrCodeShowGetData information);

    }
}
