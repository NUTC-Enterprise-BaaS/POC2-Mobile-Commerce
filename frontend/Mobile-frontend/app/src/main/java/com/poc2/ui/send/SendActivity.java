package com.poc2.ui.send;

import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.app.AppCompatActivity;
import android.util.Log;
import android.view.View;
import android.widget.Toast;

import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.androidlibrary.component.dialog.LoadingDialog;
import com.androidlibrary.module.ApiParams;
import com.androidlibrary.module.backend.api.ApiV1NormalPointReceivePost;
import com.androidlibrary.module.backend.data.ApiV1NormalPointReceivePostData;
import com.androidlibrary.module.backend.data.ErrorProcessingData;
import com.androidlibrary.module.backend.params.AccountInjection;
import com.androidlibrary.module.backend.params.ServerInfoInjection;
import com.androidlibrary.module.backend.request.WebRequest;
import com.poc2.R;
import com.poc2.component.notification.StorePromotionsNotification;

public class SendActivity extends AppCompatActivity {
    private SendLayout layout;
    private String receiveEmail;
    private String sendEmail;
    private String point;
    private String checkId;
    private ServerInfoInjection serverInfoInjection;
    private AccountInjection accountInjection;
    private ApiParams apiParams;
    private LoadingDialog loadingDialog;

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(layout = new SendLayout(this));


        Bundle bundle = getIntent().getExtras();
        sendEmail = bundle.getString(StorePromotionsNotification.RECEIVE_EMAIL);
        point = bundle.getString(StorePromotionsNotification.RECEIVE_POINT);
        receiveEmail = bundle.getString(StorePromotionsNotification.SEND_EMAIL);
        checkId = bundle.getString(StorePromotionsNotification.CHECK_ID);
        init();
    }

    private void init() {
        accountInjection = new AccountInjection(this);
        serverInfoInjection = new ServerInfoInjection();
        apiParams = new ApiParams(serverInfoInjection, accountInjection);
        loadingDialog = new LoadingDialog(this);
        setText();
        layout.cancleButton.setOnClickListener(cancle);
        layout.accussButton.setOnClickListener(access);
        Log.e("ASD", accountInjection.loadToken());
    }

    private View.OnClickListener access = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            loadingDialog.show();

            apiParams.state = "1";
            apiParams.check_id = checkId;
            apiParams.receive_email = sendEmail;
            apiParams.send_email = receiveEmail;

            WebRequest<ApiV1NormalPointReceivePostData> request = new ApiV1NormalPointReceivePost<>(SendActivity.this, apiParams);
            request.processing(new WebRequest.Processing<ApiV1NormalPointReceivePostData>() {
                @Override
                public ApiV1NormalPointReceivePostData run(String data) {
                    return new ApiV1NormalPointReceivePostData(data);
                }
            }).failProcess(new WebRequest.FailProcess<ApiV1NormalPointReceivePostData>() {
                @Override
                public void run(String data, ApiV1NormalPointReceivePostData information) {
                    loadingDialog.dismiss();
                    ErrorProcessingData.run(SendActivity.this, data, information);
                }
            }).unknownFailRequest(new Response.ErrorListener() {
                @Override
                public void onErrorResponse(VolleyError error) {
                    loadingDialog.dismiss();
                    String content = getString(R.string.request_load_fail);
                    Toast.makeText(SendActivity.this, content, Toast.LENGTH_LONG).show();
                }
            }).successProcess(new WebRequest.SuccessProcess<ApiV1NormalPointReceivePostData>() {
                @Override
                public void run(String data, ApiV1NormalPointReceivePostData information) {
                    loadingDialog.dismiss();
                    if (information.result == 0) {
                        String content = getString(R.string.phone_receive_point_send_point_success);
                        Toast.makeText(SendActivity.this, content, Toast.LENGTH_LONG).show();
                        SendActivity.this.finish();
                    }
                }
            }).start();
        }
    };

    private View.OnClickListener cancle = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            loadingDialog.show();

            apiParams.state = "0";
            apiParams.check_id = checkId;
            apiParams.receive_email = sendEmail;
            apiParams.send_email = receiveEmail;

            WebRequest<ApiV1NormalPointReceivePostData> request = new ApiV1NormalPointReceivePost<>(SendActivity.this, apiParams);
            request.processing(new WebRequest.Processing<ApiV1NormalPointReceivePostData>() {
                @Override
                public ApiV1NormalPointReceivePostData run(String data) {
                    return new ApiV1NormalPointReceivePostData(data);
                }
            }).failProcess(new WebRequest.FailProcess<ApiV1NormalPointReceivePostData>() {
                @Override
                public void run(String data, ApiV1NormalPointReceivePostData information) {
                    loadingDialog.dismiss();
                    ErrorProcessingData.run(SendActivity.this, data, information);
                }
            }).unknownFailRequest(new Response.ErrorListener() {
                @Override
                public void onErrorResponse(VolleyError error) {
                    loadingDialog.dismiss();
                    String content = getString(R.string.request_load_fail);
                    Toast.makeText(SendActivity.this, content, Toast.LENGTH_LONG).show();
                }
            }).successProcess(new WebRequest.SuccessProcess<ApiV1NormalPointReceivePostData>() {
                @Override
                public void run(String data, ApiV1NormalPointReceivePostData information) {
                    loadingDialog.dismiss();
                    if (information.result == 0) {
                        String content = getString(R.string.phone_receive_point_send_point_fail);
                        Toast.makeText(SendActivity.this, content, Toast.LENGTH_LONG).show();
                        SendActivity.this.finish();
                    }
                }
            }).start();
        }
    };

    private void setText() {
        layout.sendEmailTextView.setText("從" + receiveEmail);
        layout.sendPointTextView.setText("收到" + point + "點");
    }
}
