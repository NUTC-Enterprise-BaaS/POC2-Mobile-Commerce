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
 * Created by ameng on 2016/5/23.
 */
public class ApiV1GeneralRecommendSetPost<T extends ProcessingData> extends AuthTokenPostRequest<T> {
    private ApiParams apiParams;

    public ApiV1GeneralRecommendSetPost(Context context, ApiParams params) {
        super(context, params);
        this.apiParams = params;
    }

    @Override
    protected String getUrl() {
        return ApiUrls.apiV1GeneralRecommendSet(apiParams);
    }

//    @Override
//    protected Map<String, String> getHeader() {
//        HashMap<String, String> header = new HashMap<>();
//        header.put(RequestConst.ACCEPT, RequestConst.APPLICATION_JSON);
//        return header;
//    }

    @Override
    protected Map<String, String> getPostParams() {
        HashMap<String, String> params = new HashMap<>();
        params.put(ParamsConst.Key.QR_CODE, apiParams.inputRecommendQrCode);

        return params;
    }

}
