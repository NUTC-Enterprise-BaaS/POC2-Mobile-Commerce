package com.androidlibrary.module.backend.api;

import android.content.Context;

import com.androidlibrary.module.ApiParams;
import com.androidlibrary.module.backend.ApiUrls;
import com.androidlibrary.module.backend.data.ProcessingData;
import com.androidlibrary.module.backend.request.AuthTokenPostRequest;

import java.util.Map;

/**
 * Created by Gary on 2016/6/3.
 */
public class ApiV1FeedbBackPost<T extends ProcessingData> extends AuthTokenPostRequest<T> {

    public ApiV1FeedbBackPost(Context context, ApiParams params) {
        super(context, params);
    }


    @Override
    protected String getUrl() {
        return ApiUrls.apiV1FeedbBack(getParams());
    }


    @Override
    protected Map<String, String> getPostParams() {
        return null;
    }

}