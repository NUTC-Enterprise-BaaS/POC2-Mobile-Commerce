package com.androidlibrary.ui.basicinformation.api;

import android.content.Context;

import com.androidlibrary.module.ApiParams;
import com.androidlibrary.module.backend.ApiUrls;
import com.androidlibrary.module.backend.data.ProcessingData;
import com.androidlibrary.module.backend.params.ParamsConst;
import com.androidlibrary.module.backend.request.AuthTokenPostRequest;

import java.util.HashMap;
import java.util.Map;

/**
 * Created by ameng on 2016/6/13.
 */
public class ApiV1UserDetailPost<T extends ProcessingData> extends AuthTokenPostRequest<T> {
    public ApiV1UserDetailPost(Context context, ApiParams params) {
        super(context, params);
    }

    @Override
    protected String getUrl() {
        return ApiUrls.apiV1UserDetail(getParams());
    }

    @Override
    protected Map<String, String> getPostParams() {
        HashMap<String, String> hashMap = new HashMap<>();
        hashMap.put(ParamsConst.Key.USER_EMAIL, getParams().inputEmail);
        return hashMap;
    }
}
