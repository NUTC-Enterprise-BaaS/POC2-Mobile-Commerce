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
 * Created by 依杰 on 2016/7/7.
 */
public class ApiV1UserLuckySendPost<T extends ProcessingData> extends AuthTokenPostRequest<T> {
    public ApiV1UserLuckySendPost(Context context, ApiParams params) {
        super(context, params);
    }

    @Override
    protected String getUrl() {
        return ApiUrls.apiV1UserLuckySend(getParams());
    }

    @Override
    protected Map<String, String> getPostParams() {
        HashMap<String, String> params = new HashMap<>();
        params.put(ParamsConst.Key.Friend_Phone, getParams().inputFriendPhone);
        params.put(ParamsConst.Key.Friend_Email, getParams().inputFriendEmail);
        params.put(ParamsConst.Key.LUCKY_TOKEN, getParams().luckyToken);

        return params;
    }
}