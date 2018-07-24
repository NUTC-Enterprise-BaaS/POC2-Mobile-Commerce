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
 * Created by ameng on 2016/5/23.
 */
public class ApiV1RegisterPost<T extends ProcessingData> extends PostRequest<T> {
    private ApiParams apiParams;

    public ApiV1RegisterPost(Context context, ApiParams params) {
        super(context);
        this.apiParams = params;
    }

    @Override
    protected String getUrl() {
        return ApiUrls.apiV1register(apiParams);
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
        params.put(ParamsConst.Key.NAME, apiParams.inputRegisterName);
        params.put(ParamsConst.Key.COUNTRY, apiParams.inputRegisterCountry);
        params.put(ParamsConst.Key.PHONE, apiParams.inputRegisterPhone);
        params.put(ParamsConst.Key.EMAIL, apiParams.inputRegisterEmail);
        params.put(ParamsConst.Key.PASSWORD, apiParams.inputRegisterPassword);
        params.put(ParamsConst.Key.BIRTHDAY, apiParams.inputRegisterBirthday);
        params.put(ParamsConst.Key.QR_CODE, apiParams.inputRegisterQrCode);

        return params;
    }

}
