package com.androidlibrary.module.backend.api;

import android.content.Context;
import android.util.Log;

import com.androidlibrary.module.ApiParams;
import com.androidlibrary.module.backend.ApiUrls;
import com.androidlibrary.module.backend.data.ProcessingData;
import com.androidlibrary.module.backend.request.AuthTokenGetRequest;
import com.androidlibrary.module.backend.request.RequestConst;

import java.util.HashMap;
import java.util.Map;

/**
 * Created by Gary on 2016/5/25.
 */
public class ApiV1GeneralStoreSaveGet<T extends ProcessingData> extends AuthTokenGetRequest<T> {

    public ApiV1GeneralStoreSaveGet(Context context, ApiParams params) {
        super(context, params);
    }

    @Override
    protected String getUrl() {
        String longitude = "?longitude=" + getParams().inputLongitude;
        String latitude = "&latitude=" + getParams().inputLatitude;
        Log.e("url", ApiUrls.apiV1GeneralStoreSave(getParams()) + longitude + latitude);

        return ApiUrls.apiV1GeneralStoreSave(getParams()) + longitude + latitude;
    }

    @Override
    protected Map<String, String> getHeader() {
        HashMap<String, String> header = new HashMap<>();
        header.put(RequestConst.AUTHORIZATION, getParams().headerAuthorization);
        header.put(RequestConst.ACCEPT, RequestConst.APPLICATION_JSON);
        return header;
    }

}