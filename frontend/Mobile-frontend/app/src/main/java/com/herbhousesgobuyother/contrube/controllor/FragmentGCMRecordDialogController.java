package com.herbhousesgobuyother.contrube.controllor;

import android.content.Context;
import android.widget.Toast;

import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.androidlibrary.module.ApiParams;
import com.androidlibrary.module.backend.api.ApiV1NormalStoreCreateGet;
import com.androidlibrary.module.backend.api.ApiV1NormalStoreListGet;
import com.androidlibrary.module.backend.data.ApiV1NormalConnectLdapPostData;
import com.androidlibrary.module.backend.data.ApiV1NormalCreateLdapPostData;
import com.androidlibrary.module.backend.data.ApiV1NormalStoreCreateGetData;
import com.androidlibrary.module.backend.data.ApiV1NormalStoreListGetData;
import com.androidlibrary.module.backend.data.ErrorProcessingData;
import com.androidlibrary.module.backend.params.AccountInjection;
import com.androidlibrary.module.backend.params.ServerInfoInjection;
import com.androidlibrary.module.backend.request.WebRequest;
import com.herbhousesgobuyother.R;
import com.herbhousesgobuyother.contrube.component.dialog.LoadingDialog;

/**
 * Created by 依杰 on 2018/7/14.
 */

public class FragmentGCMRecordDialogController {
    private Context context;
    private AccountInjection accountInjection;
    private ServerInfoInjection serverInfoInjection;
    private ApiParams apiParams;
    private LoadingDialog loadingDialog;
    private CallBackEvent mCallBackEvent;

    public FragmentGCMRecordDialogController(Context context) {
        this.context = context;
        accountInjection = new AccountInjection(context);
        serverInfoInjection = new ServerInfoInjection();
        loadingDialog = new LoadingDialog(context);
        apiParams = new ApiParams(serverInfoInjection, accountInjection);
    }



    public void setmCallBackEvent(CallBackEvent callBackEvent) {
        this.mCallBackEvent = callBackEvent;
    }

    public interface CallBackEvent {
        void onError();


    }
}
