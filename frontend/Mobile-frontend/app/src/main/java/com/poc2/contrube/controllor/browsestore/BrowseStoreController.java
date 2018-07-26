package com.poc2.contrube.controllor.browsestore;

import android.content.Context;
import android.util.Log;
import android.widget.EditText;
import android.widget.Spinner;
import android.widget.Toast;

import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.androidlibrary.module.ApiParams;
import com.androidlibrary.module.backend.api.ApiV1GeneralShopLikeCancelPost;
import com.androidlibrary.module.backend.api.ApiV1GeneralShopLikePost;
import com.androidlibrary.module.backend.api.ApiV1StoreGet;
import com.androidlibrary.module.backend.api.ApiV1StoreRegionGet;
import com.androidlibrary.module.backend.data.ApiV1GeneralShopLikeCancelPostData;
import com.androidlibrary.module.backend.data.ApiV1GeneralShopLikePostData;
import com.androidlibrary.module.backend.data.ApiV1StoreGetData;
import com.androidlibrary.module.backend.data.ApiV1StoreRegionGetData;
import com.androidlibrary.module.backend.data.ErrorProcessingData;
import com.androidlibrary.module.backend.params.AccountInjection;
import com.androidlibrary.module.backend.params.ServerInfoInjection;
import com.androidlibrary.module.backend.request.WebRequest;
import com.poc2.R;
import com.poc2.contrube.component.dialog.LoadingDialog;
import com.poc2.contrube.model.SequenceLoadLogic;
import com.poc2.contrube.view.normal.ActivityNormalAdvertisement;

/**
 * Created by Gary on 2016/11/9.
 */

public class BrowseStoreController {
    private final String TAG = BrowseStoreController.class.getName();
    private Context context;
    private AccountInjection accountInjection;
    private ServerInfoInjection serverInfoInjection;
    private ApiParams apiParams;
    private LoadingDialog loadingDialog;
    private BrowseStoreController.CallBackEvent mCallBackEvent;
    private SequenceLoadLogic sequenceLoadLogic;
    private String range;

    public BrowseStoreController(Context context) {
        this.context = context;
        range = "0";
        accountInjection = new AccountInjection(context);
        serverInfoInjection = new ServerInfoInjection();
        loadingDialog = new LoadingDialog(context);
        apiParams = new ApiParams(serverInfoInjection, accountInjection);
        sequenceLoadLogic = new SequenceLoadLogic();
    }

    public void deleteRequest(String shopId) {
        ApiParams params = new ApiParams(serverInfoInjection, accountInjection);
        params.inputStoreId = shopId;

        loadingDialog.show();
        WebRequest<ApiV1GeneralShopLikeCancelPostData> request = new ApiV1GeneralShopLikeCancelPost<>(context, params);
        request.processing(new WebRequest.Processing<ApiV1GeneralShopLikeCancelPostData>() {
            @Override
            public ApiV1GeneralShopLikeCancelPostData run(String data) {
                return new ApiV1GeneralShopLikeCancelPostData(data);
            }
        }).failProcess(new WebRequest.FailProcess<ApiV1GeneralShopLikeCancelPostData>() {
            @Override
            public void run(String data, ApiV1GeneralShopLikeCancelPostData information) {
                loadingDialog.dismiss();
                ErrorProcessingData.run(context, data, information);
            }
        }).unknownFailRequest(new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                loadingDialog.dismiss();
                String content = context.getString(R.string.request_load_fail);
                Toast.makeText(context, content, Toast.LENGTH_LONG).show();
            }
        }).successProcess(new WebRequest.SuccessProcess<ApiV1GeneralShopLikeCancelPostData>() {
            @Override
            public void run(String data, ApiV1GeneralShopLikeCancelPostData information) {
                loadingDialog.dismiss();
                if (information.result == 0) {
                    String content = context.getString(R.string.brose_store_favorite_cancel);
                    Toast.makeText(context, content, Toast.LENGTH_LONG).show();
                    if (null != mCallBackEvent) {
                        mCallBackEvent.onSaveSuccess();
                    }
                }

            }
        }).start();
    }

    public void saveRequest(String shopId) {
        ApiParams params = new ApiParams(serverInfoInjection, accountInjection);
        params.inputStoreId = shopId;

        loadingDialog.show();
        WebRequest<ApiV1GeneralShopLikePostData> request = new ApiV1GeneralShopLikePost<>(context, params);
        request.processing(new WebRequest.Processing<ApiV1GeneralShopLikePostData>() {
            @Override
            public ApiV1GeneralShopLikePostData run(String data) {
                return new ApiV1GeneralShopLikePostData(data);
            }
        }).failProcess(new WebRequest.FailProcess<ApiV1GeneralShopLikePostData>() {
            @Override
            public void run(String data, ApiV1GeneralShopLikePostData information) {
                loadingDialog.dismiss();
                ErrorProcessingData.run(context, data, information);
            }
        }).unknownFailRequest(new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                loadingDialog.dismiss();
                String content = context.getString(R.string.request_load_fail);
                Toast.makeText(context, content, Toast.LENGTH_LONG).show();
            }
        }).successProcess(new WebRequest.SuccessProcess<ApiV1GeneralShopLikePostData>() {
            @Override
            public void run(String data, ApiV1GeneralShopLikePostData information) {
                loadingDialog.dismiss();
                if (information.result == 0) {
                    String content = context.getString(R.string.brose_store_favorite);
                    Toast.makeText(context, content, Toast.LENGTH_LONG).show();
                    if (null != mCallBackEvent) {
                        mCallBackEvent.onSaveSuccess();
                    }
                }

            }
        }).start();
    }

    public void syncSlipRequest(EditText search, Spinner areaSpinner, Spinner memberSpinner) {
        sequenceLoadLogic.next();
        apiParams.inputStart = String.valueOf(0);
        apiParams.inputEnd = String.valueOf(sequenceLoadLogic.getEnd());
        apiParams.inputKm = range;
        apiParams.inputKeyword = search.getText().toString().trim();
        apiParams.inputLatitude = ActivityNormalAdvertisement.lat;
        apiParams.inputLongitude = ActivityNormalAdvertisement.lng;
        apiParams.inputType = String.valueOf(memberSpinner.getSelectedItemPosition());
        apiParams.inputArea = String.valueOf(areaSpinner.getSelectedItemPosition());

        loadingDialog.show();
        WebRequest<ApiV1StoreGetData> request = new ApiV1StoreGet<>(context, apiParams);
        request.processing(processingData)
                .failProcess(failProcessingData)
                .unknownFailRequest(failUnknownReason)
                .successProcess(successResponse)
                .start();
    }

    public void syncRequest(EditText search, Spinner areaSpinner, Spinner memberSpinner) {
        apiParams.inputStart = String.valueOf(0);
        apiParams.inputEnd = String.valueOf(15);
        apiParams.inputKm = range;
        apiParams.inputKeyword = search.getText().toString().trim();
        apiParams.inputLatitude = ActivityNormalAdvertisement.lat;
        apiParams.inputLongitude = ActivityNormalAdvertisement.lng;
        apiParams.inputType = String.valueOf(memberSpinner.getSelectedItemPosition());
        apiParams.inputArea = String.valueOf(areaSpinner.getSelectedItemPosition());

        loadingDialog.show();
        WebRequest<ApiV1StoreGetData> request = new ApiV1StoreGet<>(context, apiParams);
        request.processing(processingData)
                .failProcess(failProcessingData)
                .unknownFailRequest(failUnknownReason)
                .successProcess(successResponse)
                .start();
    }

    public WebRequest.Processing<ApiV1StoreGetData> processingData = new WebRequest.Processing<ApiV1StoreGetData>() {
        @Override
        public ApiV1StoreGetData run(String data) {
            return new ApiV1StoreGetData(data);
        }
    };

    public WebRequest.FailProcess<ApiV1StoreGetData> failProcessingData = new WebRequest.FailProcess<ApiV1StoreGetData>() {
        @Override
        public void run(String data, ApiV1StoreGetData information) {
            loadingDialog.dismiss();
            ErrorProcessingData.run(context, data, information);
        }
    };

    private Response.ErrorListener failUnknownReason = new Response.ErrorListener() {
        @Override
        public void onErrorResponse(VolleyError error) {
            loadingDialog.dismiss();
            String content = context.getString(R.string.request_load_fail);
            Toast.makeText(context, content, Toast.LENGTH_LONG).show();
        }
    };

    private WebRequest.SuccessProcess<ApiV1StoreGetData> successResponse = new WebRequest.SuccessProcess<ApiV1StoreGetData>() {
        @Override
        public void run(String data, ApiV1StoreGetData information) {
            loadingDialog.dismiss();
            if (null != mCallBackEvent) {
                mCallBackEvent.onSuccess(information);
            }
        }
    };

    public void loadAreaRequest() {
        WebRequest<ApiV1StoreRegionGetData> request = new ApiV1StoreRegionGet<>(context, apiParams);
        request.processing(new WebRequest.Processing<ApiV1StoreRegionGetData>() {
            @Override
            public ApiV1StoreRegionGetData run(String data) {
                return new ApiV1StoreRegionGetData(data);
            }
        }).failProcess(new WebRequest.FailProcess<ApiV1StoreRegionGetData>() {
            @Override
            public void run(String data, ApiV1StoreRegionGetData information) {
                loadingDialog.dismiss();
                ErrorProcessingData.run(context, data, information);
            }
        }).unknownFailRequest(new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                loadingDialog.dismiss();
                String content = context.getString(R.string.request_load_fail);
                Toast.makeText(context, content, Toast.LENGTH_LONG).show();
            }
        }).successProcess(new WebRequest.SuccessProcess<ApiV1StoreRegionGetData>() {
            @Override
            public void run(String data, ApiV1StoreRegionGetData information) {
                loadingDialog.dismiss();
                if (null != mCallBackEvent) {
                    mCallBackEvent.onSuccess(information);
                }
            }
        }).start();
    }

    public void setRange(String range) {
        this.range = range;
        Log.e("this.range", this.range);
    }


    public void setmCallBackEvent(CallBackEvent callBackEvent) {
        this.mCallBackEvent = callBackEvent;
    }


    public interface CallBackEvent {
        void onError();

        void onSuccess(ApiV1StoreRegionGetData information);

        void onSuccess(ApiV1StoreGetData information);

        void onSaveSuccess();
    }
}
