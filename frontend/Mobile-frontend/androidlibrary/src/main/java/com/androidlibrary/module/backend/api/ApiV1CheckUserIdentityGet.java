package com.androidlibrary.module.backend.api;

import android.content.Context;

import com.androidlibrary.module.ApiParams;
import com.androidlibrary.module.backend.ApiUrls;
import com.androidlibrary.module.backend.data.ProcessingData;
import com.androidlibrary.module.backend.request.AuthTokenGetRequest;

public class ApiV1CheckUserIdentityGet<T extends ProcessingData> extends AuthTokenGetRequest<T> {
    public ApiV1CheckUserIdentityGet(Context context, ApiParams params) {
        super(context, params);
    }

    @Override
    protected String getUrl() {
        return ApiUrls.apiV1CheckUserIdentity(getParams());
    }
}