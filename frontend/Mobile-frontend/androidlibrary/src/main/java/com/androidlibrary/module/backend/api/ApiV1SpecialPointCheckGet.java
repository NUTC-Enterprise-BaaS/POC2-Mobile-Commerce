package com.androidlibrary.module.backend.api;

import android.content.Context;

import com.androidlibrary.module.ApiParams;
import com.androidlibrary.module.backend.ApiUrls;
import com.androidlibrary.module.backend.data.ProcessingData;
import com.androidlibrary.module.backend.request.AuthTokenGetRequest;

/**
 * Created by ameng on 2016/6/6.
 */
public class ApiV1SpecialPointCheckGet<T extends ProcessingData> extends AuthTokenGetRequest<T> {
    public ApiV1SpecialPointCheckGet(Context context, ApiParams params) {
        super(context, params);
    }

    @Override
    protected String getUrl() {
        String aesEncode = "?aes_encode=" + getParams().inputAesEncode ;
        return ApiUrls.apiV1SpecialPhonePointCheck(getParams())+aesEncode;
    }
}
