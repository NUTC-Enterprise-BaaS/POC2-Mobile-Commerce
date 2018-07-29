package com.androidlibrary.module.backend.api;

import android.content.Context;

import com.androidlibrary.module.ApiParams;
import com.androidlibrary.module.backend.ApiUrls;
import com.androidlibrary.module.backend.data.ProcessingData;
import com.androidlibrary.module.backend.request.AuthTokenGetRequest;
import com.androidlibrary.module.backend.request.RequestConst;

import java.util.HashMap;
import java.util.Map;

/**
 * Created by 依杰 on 2018/7/14.
 */

public class ApiV1NormalStoreCreateGet<T extends ProcessingData> extends AuthTokenGetRequest<T> {

    public ApiV1NormalStoreCreateGet(Context context, ApiParams params) {
        super(context, params);
    }

    @Override
    protected String getUrl() {
        String paramsGet = "?store=" + getParams().inputStart ;
        return ApiUrls.apiV1UserStoreRate(getParams()) + paramsGet;
    }

}