package com.androidlibrary.module.backend.api;

import android.content.Context;

import com.androidlibrary.module.ApiParams;
import com.androidlibrary.module.backend.ApiUrls;
import com.androidlibrary.module.backend.data.ProcessingData;
import com.androidlibrary.module.backend.request.AuthTokenGetRequest;

/**
 * Created by Gary on 2016/8/2.
 */
public class ApiV1SpecialActivityGet<T extends ProcessingData> extends AuthTokenGetRequest<T> {

    public ApiV1SpecialActivityGet(Context context, ApiParams params) {
        super(context, params);
    }

    @Override
    protected String getUrl() {
        String paramsGet = "?start=" + getParams().inputStart + "&end=" + getParams().inputEnd;
        return ApiUrls.apiV1SpecialActivity(getParams()) + paramsGet;
    }
}