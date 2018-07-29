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
 * Created by Gary on 2016/5/25.
 */
public class ApiV1GeneralNewsGet<T extends ProcessingData> extends AuthTokenGetRequest<T> {

    public ApiV1GeneralNewsGet(Context context, ApiParams params) {
        super(context, params);
    }

    @Override
    protected String getUrl() {
        String paramsGet = "?start=" + getParams().inputStart + "&end=" + getParams().inputEnd;
        return ApiUrls.apiV1GeneralNews(getParams()) + paramsGet;
    }

    @Override
    protected Map<String, String> getHeader() {
        HashMap<String, String> header = new HashMap<>();
        header.put(RequestConst.AUTHORIZATION, getParams().headerAuthorization);
        header.put(RequestConst.ACCEPT, RequestConst.APPLICATION_JSON);
        return header;
    }
}