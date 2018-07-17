package com.androidlibrary.module.backend.api;

import android.content.Context;

import com.androidlibrary.module.ApiParams;
import com.androidlibrary.module.backend.data.ProcessingData;
import com.androidlibrary.module.backend.request.AuthTokenGetRequest;

/**
 * Created by 依杰 on 2018/7/17.
 */

public class ApiV1NormalSyncPointGet<T extends ProcessingData> extends AuthTokenGetRequest<T> {
    private String mUrl;

    public ApiV1NormalSyncPointGet(Context context, ApiParams params, String url) {
        super(context, params);
        mUrl = url;
    }

    @Override
    protected String getUrl() {
        return mUrl;
    }
}
