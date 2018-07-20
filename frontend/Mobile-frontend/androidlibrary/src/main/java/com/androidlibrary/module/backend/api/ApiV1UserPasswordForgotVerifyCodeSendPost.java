package com.androidlibrary.module.backend.api;

import android.content.Context;

import com.androidlibrary.module.ApiParams;
import com.androidlibrary.module.backend.ApiUrls;
import com.androidlibrary.module.backend.data.ProcessingData;
import com.androidlibrary.module.backend.params.ParamsConst;
import com.androidlibrary.module.backend.request.PostRequest;
import com.androidlibrary.module.backend.request.RequestConst;

import java.util.HashMap;
import java.util.Map;

/**
 * Created by ameng on 2016/6/13.
 */
public class ApiV1UserPasswordForgotVerifyCodeSendPost<T extends ProcessingData> extends PostRequest<T> {
    private ApiParams apiParams;

    public ApiV1UserPasswordForgotVerifyCodeSendPost(Context context, ApiParams params) {
        super(context);
        this.apiParams = params;
    }

    @Override
    protected String getUrl() {
        return ApiUrls.apiV1UserPasswordForgotVerifyCodeSendPost(apiParams);
    }

    @Override
    protected Map<String, String> getHeader() {
        HashMap<String, String> header = new HashMap<>();
        header.put(RequestConst.AUTHORIZATION, apiParams.headerAuthorization);
        header.put(RequestConst.ACCEPT, RequestConst.APPLICATION_JSON);
        return header;
    }

    @Override
    protected Map<String, String> getPostParams() {
        HashMap<String, String> hashMap = new HashMap<>();
        if(!apiParams.inputEmail.isEmpty()){
            hashMap.put(ParamsConst.Key.EMAIL, apiParams.inputEmail);
        }else{
            hashMap.put(ParamsConst.Key.SEND_PHONE_NUMBER, apiParams.inputPhoneNumber);
        }
        return hashMap;
    }
}
