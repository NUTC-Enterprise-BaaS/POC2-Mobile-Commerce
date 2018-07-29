package com.androidlibrary.module.backend.request;

import android.content.Context;

import com.android.volley.Request;
import com.androidlibrary.module.backend.data.ProcessingData;

/**
 * Created by chriske on 2016/3/14.
 */
public abstract class PostRequest<T extends ProcessingData> extends WebRequest<T> {
    public PostRequest(Context context) {
        super(context);
    }

    @Override
    protected int getMethod() {
        return Request.Method.POST;
    }
}
