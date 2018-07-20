package com.androidlibrary.ui.basicinformation.api;

import android.content.Context;

import com.androidlibrary.module.ApiParams;
import com.androidlibrary.module.backend.ApiUrls;
import com.androidlibrary.module.backend.data.ProcessingData;
import com.androidlibrary.module.backend.request.AuthTokenGetRequest;

/**
 * Created by ameng on 2016/6/1.
 */
public class ApiV1UserDetailGet<T extends ProcessingData> extends AuthTokenGetRequest<T> {
    public ApiV1UserDetailGet(Context context, ApiParams params) {
        super(context, params);
    }

    @Override
    protected String getUrl() {
        return ApiUrls.apiV1UserDetail(getParams());
    }
}
