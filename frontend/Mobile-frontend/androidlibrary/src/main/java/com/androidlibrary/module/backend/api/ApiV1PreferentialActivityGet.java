package com.androidlibrary.module.backend.api;

import android.content.Context;

import com.androidlibrary.module.ApiParams;
import com.androidlibrary.module.backend.ApiUrls;
import com.androidlibrary.module.backend.data.ProcessingData;
import com.androidlibrary.module.backend.request.AuthTokenGetRequest;

/**
 * Created by Gary on 2016/5/25.
 */
public class ApiV1PreferentialActivityGet<T extends ProcessingData> extends AuthTokenGetRequest<T> {

    public ApiV1PreferentialActivityGet(Context context, ApiParams params) {
        super(context, params);
    }

    @Override
    protected String getUrl() {
        String paramsGet = "?start=" + getParams().inputStart + "&end=" + getParams().inputEnd;
        return ApiUrls.apiV1PreferentialActivity(getParams()) + paramsGet;
    }
}