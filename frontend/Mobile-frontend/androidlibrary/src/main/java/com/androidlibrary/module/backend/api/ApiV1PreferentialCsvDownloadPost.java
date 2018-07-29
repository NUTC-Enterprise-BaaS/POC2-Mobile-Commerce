package com.androidlibrary.module.backend.api;

import android.content.Context;

import com.androidlibrary.module.ApiParams;
import com.androidlibrary.module.backend.ApiUrls;
import com.androidlibrary.module.backend.data.ProcessingData;
import com.androidlibrary.module.backend.params.ParamsConst;
import com.androidlibrary.module.backend.request.AuthTokenPostRequest;
import com.androidlibrary.module.backend.request.RequestConst;

import java.util.HashMap;
import java.util.Map;

/**
 * Created by 依杰 on 2016/7/14.
 */
public class ApiV1PreferentialCsvDownloadPost<T extends ProcessingData> extends AuthTokenPostRequest<T> {

    public ApiV1PreferentialCsvDownloadPost(Context context, ApiParams params) {
        super(context, params);
    }


    @Override
    protected String getUrl() {
        return ApiUrls.apiV1PreferentialCsvDownloadPost(getParams());
    }

    @Override
    protected Map<String, String> getHeader() {
        HashMap<String, String> header = new HashMap<>();
        header.put(RequestConst.ACCEPT, RequestConst.APPLICATION_JSON);
        header.put(RequestConst.AUTHORIZATION, getParams().headerAuthorization);

        return header;
    }

    @Override
    protected Map<String, String> getPostParams() {
        HashMap<String, String> params = new HashMap<>();
        params.put(ParamsConst.Key.PASSWORD, getParams().inputPassword);
        params.put(ParamsConst.Key.TIMESTAMP_START, getParams().timestampStart);
        params.put(ParamsConst.Key.TIMESTAMP_END, getParams().timestampEnd);

        return params;
    }

}