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
 * Created by ameng on 2016/6/21.
 */
public class ApiV1SecialCeckVersionPost<T extends ProcessingData> extends PostRequest<T> {
    private ApiParams params;
    public ApiV1SecialCeckVersionPost(Context context, ApiParams params) {
        super(context);
        this.params = params;
    }

    @Override
    protected String getUrl() {
        return ApiUrls.apiV1SpecialCheckVersion(params);
    }

    @Override
    protected Map<String, String> getHeader() {
        HashMap<String, String> header = new HashMap<>();
        header.put(RequestConst.ACCEPT, RequestConst.APPLICATION_JSON);
        return header;
    }

    @Override
    protected Map<String, String> getPostParams() {
        HashMap<String, String> params = new HashMap<>();
        params.put(ParamsConst.Key.VERSION, ApiV1SecialCeckVersionPost.this.params.inputVersion);
        return params;
    }
}
