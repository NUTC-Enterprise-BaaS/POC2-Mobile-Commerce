package com.androidlibrary.module.backend.request;

import android.content.Context;

import com.android.volley.Request;
import com.androidlibrary.module.backend.data.ProcessingData;

import java.util.Map;

/**
 * Created by chriske on 2016/3/14.
 */
public abstract class GetRequest<T extends ProcessingData> extends WebRequest<T> {

    public GetRequest(Context context) {
        super(context);
    }

    @Override
    protected int getMethod() {
        return Request.Method.GET;
    }

    @Override
    protected Map<String, String> getPostParams() {
        return null;
    }
}
