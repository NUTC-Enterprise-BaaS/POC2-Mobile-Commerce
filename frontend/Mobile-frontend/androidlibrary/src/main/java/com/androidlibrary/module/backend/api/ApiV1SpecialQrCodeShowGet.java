package com.androidlibrary.module.backend.api;

import android.content.Context;

import com.androidlibrary.module.ApiParams;
import com.androidlibrary.module.backend.ApiUrls;
import com.androidlibrary.module.backend.request.AuthTokenGetRequest;import com.androidlibrary.module.backend.data.ProcessingData;


/**
 * Created by 依杰 on 2016/10/17.
 */

public class ApiV1SpecialQrCodeShowGet<T extends ProcessingData> extends AuthTokenGetRequest<T> {
    public ApiV1SpecialQrCodeShowGet(Context context, ApiParams params) {
        super(context, params);
    }

    @Override
    protected String getUrl() {
        return ApiUrls.apiV1SpecialQrCodeShowGet(getParams());
    }
}
