package com.androidlibrary.module.backend.api;

import android.content.Context;

import com.androidlibrary.module.ApiParams;
import com.androidlibrary.module.backend.ApiUrls;
import com.androidlibrary.module.backend.data.ProcessingData;
import com.androidlibrary.module.backend.params.ParamsConst;
import com.androidlibrary.module.backend.request.AuthTokenPostRequest;

import java.util.HashMap;
import java.util.Map;

/**
 * Created by 依杰 on 2016/8/16.
 */
public class ApiV1NormalPointReceivePost<T extends ProcessingData> extends AuthTokenPostRequest<T> {
    public ApiV1NormalPointReceivePost(Context context, ApiParams params) {
        super(context, params);
    }

    @Override
    protected String getUrl() {
        return ApiUrls.apiV1NormalPointReceive(getParams());
    }

    @Override
    protected Map<String, String> getPostParams() {
        HashMap<String, String> params = new HashMap<>();
        params.put(ParamsConst.Key.STATE, getParams().state);
        params.put(ParamsConst.Key.CHECK_ID, getParams().check_id);
        params.put(ParamsConst.Key.RECEIVE_EMAIL, getParams().receive_email);
        params.put(ParamsConst.Key.SEND_EMAIL, getParams().send_email);

        return params;
    }
}
