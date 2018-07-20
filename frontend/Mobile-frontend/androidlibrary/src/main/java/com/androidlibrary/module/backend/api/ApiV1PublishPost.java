package com.androidlibrary.module.backend.api;

import android.content.Context;

import com.androidlibrary.module.ApiParams;
import com.androidlibrary.module.backend.ApiUrls;
import com.androidlibrary.module.backend.data.ProcessingData;
import com.androidlibrary.module.backend.request.AuthTokenPostRequest;
import com.androidlibrary.module.backend.request.RequestConst;

import java.util.HashMap;
import java.util.Map;

/**
 * Created by Gary on 2016/6/3.
 */
public class ApiV1PublishPost<T extends ProcessingData> extends AuthTokenPostRequest<T> {

    public ApiV1PublishPost(Context context, ApiParams params) {
        super(context, params);
    }


    @Override
    protected String getUrl() {
        return ApiUrls.apiV1SpecialAdvertisePublish(getParams());
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
        return null;
    }
}