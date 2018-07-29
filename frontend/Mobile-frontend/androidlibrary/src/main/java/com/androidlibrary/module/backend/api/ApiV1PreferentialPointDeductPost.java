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
 * Created by 依杰 on 2016/7/18.
 */
public class ApiV1PreferentialPointDeductPost<T extends ProcessingData> extends AuthTokenPostRequest<T> {

    public ApiV1PreferentialPointDeductPost(Context context, ApiParams params) {
        super(context, params);
    }


    @Override
    protected String getUrl() {
        return ApiUrls.apiV1PreferentialPointDeduct(getParams());
    }

    @Override
    protected Map<String, String> getPostParams() {
        HashMap<String, String> params = new HashMap<>();
        params.put(ParamsConst.Key.TRANSACTION_ID, getParams().inputTransactionId);
        params.put(ParamsConst.Key.PHONE_NUMBER, getParams().inputPhoneNumber);
        params.put(ParamsConst.Key.BONUS, getParams().inputBonus);

        return params;
    }

}
