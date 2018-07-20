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
 * Created by on 2016/6/28.
 */
public class ApiV1PreferentialRegisterPost<T extends ProcessingData> extends AuthTokenPostRequest<T> {
    public ApiV1PreferentialRegisterPost(Context context, ApiParams params) {
        super(context, params);
    }

    @Override
    protected String getUrl() {
        return ApiUrls.apiV1PreferentialRegister(getParams());
    }

    @Override
    protected Map<String, String> getPostParams() {
        HashMap<String, String> params = new HashMap<>();
        params.put(ParamsConst.Key.Store_Name, getParams().inputStoreName);
        params.put(ParamsConst.Key.Store_Type, getParams().inputStoreType);
        params.put(ParamsConst.Key.Category_Employment, getParams().inputCategoryEmployment);
        params.put(ParamsConst.Key.Store_Address, getParams().inputStoreAddress);
        params.put(ParamsConst.Key.Store_Url, getParams().inputStoreUrl);
        params.put(ParamsConst.Key.Contact_Person, getParams().inputContactPerson);
        params.put(ParamsConst.Key.Contact_Person_Sex, getParams().inputContactPersonSex);
        params.put(ParamsConst.Key.Id, getParams().inputId);
        params.put(ParamsConst.Key.VERIFY_CODE, getParams().inputVerify);
        params.put(ParamsConst.Key.QR_CODE, getParams().inputRegisterQrCode);
        params.put(ParamsConst.Key.LOGO_PIC, getParams().inputLogoBase64);
        if (!getParams().inputContentBase64_1.equals("")) {
            params.put(ParamsConst.Key.LOGO_CONTENT_1, getParams().inputContentBase64_1);
            params.put(ParamsConst.Key.LOGO_CONTENT_2, getParams().inputContentBase64_2);
            params.put(ParamsConst.Key.LOGO_CONTENT_3, getParams().inputContentBase64_3);
        }


        return params;
    }
}
