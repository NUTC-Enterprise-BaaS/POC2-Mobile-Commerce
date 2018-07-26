package com.androidlibrary.module.backend.api;

import android.content.Context;

import com.androidlibrary.module.ApiParams;
import com.androidlibrary.module.backend.ApiUrls;
import com.androidlibrary.module.backend.data.ProcessingData;
import com.androidlibrary.module.backend.request.AuthTokenGetRequest;

/**
 * Created by 依杰 on 2018/7/25.
 */

public class ApiV1NormalCostPointGet<T extends ProcessingData> extends AuthTokenGetRequest<T> {

    public ApiV1NormalCostPointGet(Context context, ApiParams params) {
        super(context, params);
    }

    @Override
    protected String getUrl() {
        return ApiUrls.apiCostPoint(getParams());
    }
}
