package com.herbhousesgobuyother.contrube.controllor.point;

import android.content.Context;
import android.widget.Toast;

import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.androidlibrary.module.ApiParams;
import com.androidlibrary.module.backend.api.ApiV1GeneralSendPointPost;
import com.androidlibrary.module.backend.data.ApiV1GeneralSendPointPostData;
import com.androidlibrary.module.backend.data.ErrorProcessingData;
import com.androidlibrary.module.backend.params.AccountInjection;
import com.androidlibrary.module.backend.params.ServerInfoInjection;
import com.androidlibrary.module.backend.request.WebRequest;
import com.herbhousesgobuyother.R;
import com.herbhousesgobuyother.contrube.component.dialog.LoadingDialog;

/**
 * Created by 依杰 on 2016/11/16.
 */

public class NormalPointPasswordDialogController {
    private Context context;
    private AccountInjection accountInjection;
    private ServerInfoInjection serverInfoInjection;
    private ApiParams apiParams;
    private LoadingDialog loadingDialog;
    private NormalPointPasswordDialogController.CallBackEvent callBackEvent;

    public NormalPointPasswordDialogController(Context context) {
        this.context = context;
        accountInjection = new AccountInjection(context);
        serverInfoInjection = new ServerInfoInjection();
        loadingDialog = new LoadingDialog(context);
        apiParams = new ApiParams(serverInfoInjection, accountInjection);
    }

    public void sendPoint(String password, String point, String phone, String email) {
        apiParams.inputPoint = point;
        apiParams.inputPhone = phone;
        apiParams.inputEmail = email;
        apiParams.inputPassword = password;

        WebRequest<ApiV1GeneralSendPointPostData> request = new ApiV1GeneralSendPointPost<>(context, apiParams);
        request.processing(new WebRequest.Processing<ApiV1GeneralSendPointPostData>() {
            @Override
            public ApiV1GeneralSendPointPostData run(String data) {
                return new ApiV1GeneralSendPointPostData(data);
            }
        }).failProcess(new WebRequest.FailProcess<ApiV1GeneralSendPointPostData>() {
            @Override
            public void run(String data, ApiV1GeneralSendPointPostData information) {
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
        }).successProcess(new WebRequest.SuccessProcess<ApiV1GeneralSendPointPostData>() {
            @Override
            public void run(String data, ApiV1GeneralSendPointPostData information) {
                if (information.result == 0) {
                    loadingDialog.dismiss();
                     sendSuccess(information);
                } else if (information.messageGroup.get(0).equals("Recipient's email error")) {
                    String content = context.getString(R.string.request_load_fail_send_point_email_fail);
                    Toast.makeText(context, content, Toast.LENGTH_LONG).show();
                } else if (information.messageGroup.get(0).equals("Recipient's phone error")) {
                    String content = context.getString(R.string.request_load_fail_send_opinion_phone_fail);
                    Toast.makeText(context, content, Toast.LENGTH_LONG).show();
                }else if (information.messageGroup.get(0).equals("Recipient is blocked")) {
                    String content = context.getString(R.string.request_load_fail_send_opinion_block_fail);
                    Toast.makeText(context, content, Toast.LENGTH_LONG).show();
                }else if (information.messageGroup.get(0).equals("The user's device is incorrect")) {
                    String content = context.getString(R.string.request_load_fail_send_opinion_device_fail);
                    Toast.makeText(context, content, Toast.LENGTH_LONG).show();
                }
            }
        }).start();
    }

    private void sendSuccess(ApiV1GeneralSendPointPostData information) {
        if (information.result == 0) {
            callBackEvent.onSuccess(information);
        }
    }

    public void setCallBackEvent(NormalPointPasswordDialogController.CallBackEvent callBackEvent) {
        this.callBackEvent = callBackEvent;
    }

    public interface CallBackEvent {
        void onError();

        void onSuccess(ApiV1GeneralSendPointPostData information);

    }

}
