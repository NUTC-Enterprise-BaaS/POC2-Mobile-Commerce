package com.herbhousesgobuyother.contrube.controllor.setting;

import android.content.Context;
import android.content.pm.PackageManager;
import android.support.v7.widget.SwitchCompat;
import android.widget.SeekBar;
import android.widget.Toast;

import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.androidlibrary.module.ApiParams;
import com.androidlibrary.module.backend.api.ApiV1FeedbBackPost;
import com.androidlibrary.module.backend.api.ApiV1UserCheckVersionPost;
import com.androidlibrary.module.backend.data.ApiV1FeedbBackPostData;
import com.androidlibrary.module.backend.data.ApiV1UserCheckVersionPostData;
import com.androidlibrary.module.backend.data.ErrorProcessingData;
import com.androidlibrary.module.backend.params.AccountInjection;
import com.androidlibrary.module.backend.params.ServerInfoInjection;
import com.androidlibrary.module.backend.request.WebRequest;
import com.herbhousesgobuyother.R;
import com.herbhousesgobuyother.contrube.broadcastreceiver.SettingHideReceiver;
import com.herbhousesgobuyother.contrube.component.dialog.LoadingDialog;
import com.herbhousesgobuyother.contrube.model.SettingDataStore;
import com.herbhousesgobuyother.contrube.model.audio.AudioHelper;
import com.herbhousesgobuyother.contrube.model.version.VersionHelper;

/**
 * Created by Gary on 2016/11/15.
 */

public class NormalSettingController {
    private final String TAG = NormalSettingController.class.getName();
    private Context context;
    private AccountInjection accountInjection;
    private ServerInfoInjection serverInfoInjection;
    private ApiParams apiParams;
    private LoadingDialog loadingDialog;
    private AudioHelper audioHelper;
    private VersionHelper versionHelper;
    private NormalSettingController.CallBackEvent mCallBackEvent;

    public NormalSettingController(Context context) {
        this.context = context;
        accountInjection = new AccountInjection(context);
        serverInfoInjection = new ServerInfoInjection();
        loadingDialog = new LoadingDialog(context);
        apiParams = new ApiParams(serverInfoInjection, accountInjection);
        try {
            versionHelper = new VersionHelper(context);
        } catch (PackageManager.NameNotFoundException e) {
            e.printStackTrace();
        }
    }

    public void versionRequest() {
        loadingDialog.show();
        apiParams.inputVersion = versionHelper.getVersion();
        WebRequest<ApiV1UserCheckVersionPostData> request = new ApiV1UserCheckVersionPost<>(context, apiParams);
        request.processing(new WebRequest.Processing<ApiV1UserCheckVersionPostData>() {
            @Override
            public ApiV1UserCheckVersionPostData run(String data) {
                return new ApiV1UserCheckVersionPostData(data);
            }
        }).failProcess(new WebRequest.FailProcess<ApiV1UserCheckVersionPostData>() {
            @Override
            public void run(String data, ApiV1UserCheckVersionPostData information) {
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
        }).successProcess(new WebRequest.SuccessProcess<ApiV1UserCheckVersionPostData>() {
            @Override
            public void run(String data, ApiV1UserCheckVersionPostData information) {
                loadingDialog.dismiss();
                mCallBackEvent.onSuccess(information);

            }
        }).start();
    }

    public void feedbackRequest() {
        loadingDialog.show();
        ApiParams params = new ApiParams(serverInfoInjection, accountInjection);
        WebRequest<ApiV1FeedbBackPostData> request = new ApiV1FeedbBackPost<>(context, params);
        request.processing(new WebRequest.Processing<ApiV1FeedbBackPostData>() {
            @Override
            public ApiV1FeedbBackPostData run(String data) {
                return new ApiV1FeedbBackPostData(data);
            }
        }).failProcess(new WebRequest.FailBackgroundProcess<ApiV1FeedbBackPostData>() {
            @Override
            public void run(String data, ApiV1FeedbBackPostData information) {
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
        }).successProcess(new WebRequest.SuccessProcess<ApiV1FeedbBackPostData>() {
            @Override
            public void run(String data, ApiV1FeedbBackPostData information) {
                loadingDialog.dismiss();
                mCallBackEvent.onSuccess(information);
            }
        }).start();
    }

    /**
     * 通知聲音調整的功能
     */
    public void setNotificationAudio(SeekBar seekBar, SwitchCompat shock) {
        audioHelper = new AudioHelper(context, seekBar, shock);
        audioHelper.setVolume();
        audioHelper.setShock();
    }

    /**
     * 切換顯示加入其他會員的功能
     */
    public void setSwitchJoinButtonEvent(boolean state) {
        final SettingDataStore dataStore = new SettingDataStore(context);
        dataStore.save(SettingDataStore.KEY_IS_SHOW_JOIN, !state);
        SettingHideReceiver.send(context);
    }

    public void setmCallBackEvent(NormalSettingController.CallBackEvent callBackEvent) {
        this.mCallBackEvent = callBackEvent;
    }

    public interface CallBackEvent {
        void onError();
        void onSuccess(ApiV1UserCheckVersionPostData information);
        void onSuccess(ApiV1FeedbBackPostData information);
    }
}
