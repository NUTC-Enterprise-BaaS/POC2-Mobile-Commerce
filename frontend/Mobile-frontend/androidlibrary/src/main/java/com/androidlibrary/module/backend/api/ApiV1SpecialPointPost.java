package com.androidlibrary.module.backend.api;

import android.content.Context;

import com.androidlibrary.module.ApiParams;
import com.androidlibrary.module.backend.ApiUrls;
import com.androidlibrary.module.backend.data.ProcessingData;
import com.androidlibrary.module.backend.params.ParamsConst;
import com.androidlibrary.module.backend.request.PostRequest;
import com.androidlibrary.module.backend.request.RequestConst;

import java.util.HashMap;
import java.util.Map;

/**
 * Created by Gary on 2016/5/28.
 */
public class ApiV1SpecialPointPost<T extends ProcessingData> extends PostRequest<T> {
    private ApiParams apiParams;

    public ApiV1SpecialPointPost(Context context, ApiParams params) {
        super(context);
        this.apiParams = params;
    }

    @Override
    protected String getUrl() {
        return ApiUrls.apiV1SpecialPoint(apiParams);
    }

    @Override
    protected Map<String, String> getHeader() {
        HashMap<String, String> header = new HashMap<>();
        header.put(RequestConst.AUTHORIZATION, apiParams.headerAuthorization);
        header.put(RequestConst.ACCEPT, RequestConst.APPLICATION_JSON);
        return header;
    }

    @Override
    protected Map<String, String> getPostParams() {
        HashMap<String, String> params = new HashMap<>();
        params.put(ParamsConst.Key.TIMESTAMP_START, apiParams.timestampStart);
        params.put(ParamsConst.Key.TIMESTAMP_END, apiParams.timestampEnd);
        return params;
    }

}
