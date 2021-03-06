package com.androidlibrary.module.backend.request;

import android.content.Context;

import com.androidlibrary.module.ApiParams;
import com.androidlibrary.module.backend.data.ProcessingData;

import java.util.HashMap;
import java.util.Map;

/**
 * Created by chriske on 2016/3/14.
 */
public abstract class AuthTokenPostRequest<T extends ProcessingData> extends PostRequest<T> {
    private ApiParams params;

    public AuthTokenPostRequest(Context context, ApiParams params) {
        super(context);
        this.params = params;
    }

    protected ApiParams getParams() {
        return params;
    }

    @Override
    protected Map<String, String> getHeader() {
        HashMap<String, String> header = new HashMap<>();
        header.put(RequestConst.AUTHORIZATION, getParams().headerAuthorization);
        header.put(RequestConst.ACCEPT, RequestConst.APPLICATION_JSON);
        return header;
    }
}
