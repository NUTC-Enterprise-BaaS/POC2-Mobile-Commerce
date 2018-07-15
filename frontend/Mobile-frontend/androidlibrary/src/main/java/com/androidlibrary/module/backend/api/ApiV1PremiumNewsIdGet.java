package com.androidlibrary.module.backend.api;

import android.content.Context;

import com.androidlibrary.module.ApiParams;
import com.androidlibrary.module.backend.ApiUrls;
import com.androidlibrary.module.backend.data.ProcessingData;
import com.androidlibrary.module.backend.request.AuthTokenGetRequest;

/**
 * Created by ameng on 2016/6/1.
 */
public class ApiV1PremiumNewsIdGet<T extends ProcessingData> extends AuthTokenGetRequest<T> {
    public ApiV1PremiumNewsIdGet(Context context, ApiParams params) {
        super(context, params);
    }

    @Override
    protected String getUrl() {
        return ApiUrls.apiV1PremiumNewsId(getParams());
    }
}
