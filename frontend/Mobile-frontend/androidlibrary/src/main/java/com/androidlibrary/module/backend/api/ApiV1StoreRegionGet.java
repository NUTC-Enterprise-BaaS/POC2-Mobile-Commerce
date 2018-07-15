package com.androidlibrary.module.backend.api;

import android.content.Context;

import com.androidlibrary.module.ApiParams;
import com.androidlibrary.module.backend.ApiUrls;
import com.androidlibrary.module.backend.request.AuthTokenGetRequest;
import com.androidlibrary.module.backend.data.ProcessingData;

/**
 * Created by ameng on 2016/6/6.
 */
public class ApiV1StoreRegionGet<T extends ProcessingData> extends AuthTokenGetRequest<T> {
    public ApiV1StoreRegionGet(Context context, ApiParams params) {
        super(context, params);
    }

    @Override
    protected String getUrl() {
        return ApiUrls.apiV1StoreRegion(getParams());
    }
}
