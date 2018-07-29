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
 * Created by 依杰 on 2018/7/17.
 */

public class ApiV1NormalBuyVoucherPost<T extends ProcessingData> extends AuthTokenPostRequest<T> {
    public ApiV1NormalBuyVoucherPost(Context context, ApiParams params) {
        super(context, params);
    }

    @Override
    protected String getUrl() {
        return ApiUrls.apiV1BuyVoucher(getParams());
    }

    @Override
    protected Map<String, String> getPostParams() {
        HashMap<String, String> params = new HashMap<>();
        params.put(ParamsConst.Key.VOUCHER_MESSAGE, getParams().message);
        params.put(ParamsConst.Key.STORE_POINT, getParams().inputLdapPoint);
        params.put(ParamsConst.Key.SEND_POINT_STORE_NAME, getParams().storeName);
        params.put(ParamsConst.Key.SEND_POINT_USER_NAME, getParams().userName);
        return params;
    }
}