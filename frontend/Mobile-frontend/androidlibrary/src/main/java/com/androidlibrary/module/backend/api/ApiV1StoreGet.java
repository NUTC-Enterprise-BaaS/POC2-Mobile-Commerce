package com.androidlibrary.module.backend.api;

import android.content.Context;
import android.util.Log;

import com.androidlibrary.module.ApiParams;
import com.androidlibrary.module.backend.ApiUrls;
import com.androidlibrary.module.backend.request.AuthTokenGetRequest;
import com.androidlibrary.module.backend.data.ProcessingData;

/**
 * Created by ameng on 2016/6/6.
 */
public class ApiV1StoreGet<T extends ProcessingData> extends AuthTokenGetRequest<T> {
    public ApiV1StoreGet(Context context, ApiParams params) {
        super(context, params);
    }

    @Override
    protected String getUrl() {
        String urlEnd = "?start=" + getParams().inputStart + "&end=" + getParams().inputEnd;
        String keyword = "&keyword=" + getParams().inputKeyword;
        String area = "&area=" + getParams().inputArea;
        String longitude = "&longitude=" + getParams().inputLongitude;
        String latitude = "&latitude=" + getParams().inputLatitude;
        String km = "&km=" + getParams().inputKm;
        String type = "&type=" + getParams().inputType;
        Log.e("TAG", ApiUrls.apiV1Store(getParams()) + urlEnd + keyword + area + longitude + latitude + km + type);
        return ApiUrls.apiV1Store(getParams()) + urlEnd + keyword + area + longitude + latitude + km + type;
    }
}
