package com.herbhousesgobuyother.contrube.controllor.qrshow;

import android.content.Context;
import android.widget.Toast;

import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.androidlibrary.module.ApiParams;
import com.androidlibrary.module.backend.api.ApiV1SpecialQrCodeShowGet;
import com.androidlibrary.module.backend.data.ApiV1SpecialQrCodeShowGetData;
import com.androidlibrary.module.backend.data.ErrorProcessingData;
import com.androidlibrary.module.backend.params.AccountInjection;
import com.androidlibrary.module.backend.params.ServerInfoInjection;
import com.androidlibrary.module.backend.request.WebRequest;
import com.herbhousesgobuyother.R;
import com.herbhousesgobuyother.contrube.component.dialog.LoadingDialog;

/**
 * Created by 依杰 on 2016/11/28.
 */

public class FragmentSpecialQrcodeControllor {
    private Context context;
    private AccountInjection accountInjection;
    private ServerInfoInjection serverInfoInjection;
    private ApiParams apiParams;
    private LoadingDialog loadingDialog;
    private CallBackEvent event;

    public FragmentSpecialQrcodeControllor(Context context) {
        this.context = context;
        accountInjection = new AccountInjection(context);
        serverInfoInjection = new ServerInfoInjection();
        loadingDialog = new LoadingDialog(context);
        apiParams = new ApiParams(serverInfoInjection, accountInjection);
    }

    public void showQrCode() {
        loadingDialog.show();
        WebRequest<ApiV1SpecialQrCodeShowGetData> request = new ApiV1SpecialQrCodeShowGet<>(context, apiParams);
        request.processing(processingDataShow)
                  .failProcess(failProcessingDataShow)
                  .unknownFailRequest(failUnknownReasonShow)
                  .successProcess(successResponseShow)
                  .start();
    }

    public WebRequest.Processing<ApiV1SpecialQrCodeShowGetData> processingDataShow = new WebRequest.Processing<ApiV1SpecialQrCodeShowGetData>() {
        @Override
        public ApiV1SpecialQrCodeShowGetData run(String data) {
            return new ApiV1SpecialQrCodeShowGetData(data);
        }
    };

    public WebRequest.FailProcess<ApiV1SpecialQrCodeShowGetData> failProcessingDataShow = new WebRequest.FailProcess<ApiV1SpecialQrCodeShowGetData>() {
        @Override
        public void run(String data, ApiV1SpecialQrCodeShowGetData information) {
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

    private WebRequest.SuccessProcess<ApiV1SpecialQrCodeShowGetData> successResponseShow = new WebRequest.SuccessProcess<ApiV1SpecialQrCodeShowGetData>() {
        @Override
        public void run(String data, ApiV1SpecialQrCodeShowGetData information) {
            loadingDialog.dismiss();
            if (information.result == 0) {
                onSuccess(information);
            }
        }
    };

    public void setCallBackEvent(CallBackEvent event) {
        this.event = event;
    }

    private void onSuccess(ApiV1SpecialQrCodeShowGetData information) {
        event.onSuccess(information);
    }

    public interface CallBackEvent {
        void onSuccess(ApiV1SpecialQrCodeShowGetData information);

        void onError();
    }
}
