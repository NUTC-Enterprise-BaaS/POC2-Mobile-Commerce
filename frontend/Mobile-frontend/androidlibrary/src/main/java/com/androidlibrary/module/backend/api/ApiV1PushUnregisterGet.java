package com.androidlibrary.module.backend.api;

import android.content.Context;

import com.androidlibrary.module.ApiParams;
import com.androidlibrary.module.backend.ApiUrls;
import com.androidlibrary.module.backend.data.ProcessingData;
import com.androidlibrary.module.backend.request.AuthTokenGetRequest;

/**
 * Created by ameng on 7/13/16.
 */
public class ApiV1PushUnregisterGet<T extends ProcessingData> extends AuthTokenGetRequest<T> {

    public ApiV1PushUnregisterGet(Context context, ApiParams params) {
        super(context,params);
    }

    @Override
    protected String getUrl() {
        return ApiUrls.apiV1PushUnregister(getParams());
    }
}
