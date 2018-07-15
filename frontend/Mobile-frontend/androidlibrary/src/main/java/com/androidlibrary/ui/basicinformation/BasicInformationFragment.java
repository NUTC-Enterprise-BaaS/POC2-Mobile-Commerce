package com.androidlibrary.ui.basicinformation;

import android.app.Activity;
import android.app.Fragment;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;

import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.androidlibrary.component.dialog.FinishModifyDialog;
import com.androidlibrary.component.dialog.LoadingDialog;
import com.androidlibrary.module.ApiParams;
import com.androidlibrary.module.backend.api.ApiV1RecommendShowGet;
import com.androidlibrary.module.backend.data.ApiV1RecommendShowGetData;
import com.androidlibrary.module.backend.data.ErrorProcessingData;
import com.androidlibrary.module.backend.params.AccountInjection;
import com.androidlibrary.module.backend.params.ServerInfoInjection;
import com.androidlibrary.module.backend.request.WebRequest;
import com.androidlibrary.module.consts.CountryConst;
import com.androidlibrary.ui.basicinformation.api.ApiV1UserDetailGet;
import com.androidlibrary.ui.basicinformation.api.ApiV1UserDetailPost;
import com.androidlibrary.ui.basicinformation.data.ApiV1UserDetailGetData;
import com.androidlibrary.ui.basicinformation.data.ApiV1UserDetailPostData;

/**
 * Created by ameng on 2016/6/1.
 */
public class BasicInformationFragment extends Fragment {
    private BasicInformationLayout layout;
    private Activity activity;
    private ApiParams apiParams;
    private AccountInjection accountInjection;
    private ServerInfoInjection serverInfoInjection;
    private LoadingDialog loadingDialog;
    private FinishModifyDialog finishModifyDialog;

    @Nullable
    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        return layout = new BasicInformationLayout(getActivity());
    }

    @Override
    public void onActivityCreated(Bundle savedInstanceState) {
        super.onActivityCreated(savedInstanceState);
        init();
    }

    private void init() {
        activity = getActivity();
        accountInjection = new AccountInjection(activity);
        serverInfoInjection = new ServerInfoInjection();
        loadingDialog = new LoadingDialog(getActivity());
        finishModifyDialog = new FinishModifyDialog(activity);
        layout.toolbarBackImageButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                activity.onBackPressed();
            }
        });
        layout.setSubmitClick(submit);
        layout.scanRecommend.setOnClickListener(scan);
        sync();
        syncRecommend();
    }

    private void syncRecommend() {
        if (layout.recommend.getRightTextView().getText().equals("")) {
            layout.scanRecommend.setVisibility(View.VISIBLE);
        } else {
            layout.scanRecommend.setVisibility(View.GONE);
        }
        WebRequest<ApiV1RecommendShowGetData> request = new ApiV1RecommendShowGet<>(activity, apiParams);
        request.processing(new WebRequest.Processing<ApiV1RecommendShowGetData>() {
            @Override
            public ApiV1RecommendShowGetData run(String data) {
                return new ApiV1RecommendShowGetData(data);
            }
        }).failProcess(new WebRequest.FailProcess<ApiV1RecommendShowGetData>() {
            @Override
            public void run(String data, ApiV1RecommendShowGetData information) {
                loadingDialog.dismiss();
                ErrorProcessingData.run(activity, data, information);
            }
        }).unknownFailRequest(new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                loadingDialog.dismiss();
            }
        }).successProcess(new WebRequest.SuccessProcess<ApiV1RecommendShowGetData>() {
            @Override
            public void run(String data, ApiV1RecommendShowGetData information) {
                if (information.result == 0) {
                    layout.recommend.setRightText(information.general);

                }
            }
        }).start();
    }

    private View.OnClickListener submit = new View.OnClickListener() {
        @Override
        public void onClick(View v) {
            apiParams = new ApiParams(serverInfoInjection, accountInjection);
            apiParams.inputEmail = layout.mail.getRightEditText().getText().toString().trim();
            Log.e(" apiParams.inputEmail", apiParams.inputEmail);
            loadingDialog.show();

            WebRequest<ApiV1UserDetailPostData> request = new ApiV1UserDetailPost<>(activity, apiParams);
            request.processing(new WebRequest.Processing<ApiV1UserDetailPostData>() {
                @Override
                public ApiV1UserDetailPostData run(String data) {
                    return new ApiV1UserDetailPostData(data);
                }
            }).failProcess(new WebRequest.FailProcess<ApiV1UserDetailPostData>() {
                @Override
                public void run(String data, ApiV1UserDetailPostData information) {
                    loadingDialog.dismiss();
                    ErrorProcessingData.run(activity, data, information);
                }
            }).unknownFailRequest(new Response.ErrorListener() {
                @Override
                public void onErrorResponse(VolleyError error) {
                    loadingDialog.dismiss();
                }
            }).successProcess(new WebRequest.SuccessProcess<ApiV1UserDetailPostData>() {
                @Override
                public void run(String data, ApiV1UserDetailPostData information) {
                    if (information.result == 0) {
                        finishModifyDialog.show();
                        sync();
                    }
                }
            }).start();
        }
    };

    private void sync() {
        apiParams = new ApiParams(serverInfoInjection, accountInjection);
        loadingDialog.show();
        WebRequest<ApiV1UserDetailGetData> request = new ApiV1UserDetailGet<>(activity, apiParams);
        request.processing(processingData)
                .failProcess(failProcessingData)
                .unknownFailRequest(failUnknownReason)
                .successProcess(successResponse)
                .start();


    }

    private WebRequest.Processing<ApiV1UserDetailGetData> processingData = new WebRequest.Processing<ApiV1UserDetailGetData>() {
        @Override
        public ApiV1UserDetailGetData run(String data) {
            return new ApiV1UserDetailGetData(data);
        }
    };

    private WebRequest.FailProcess<ApiV1UserDetailGetData> failProcessingData = new WebRequest.FailProcess<ApiV1UserDetailGetData>() {
        @Override
        public void run(String data, ApiV1UserDetailGetData information) {
            loadingDialog.dismiss();
            ErrorProcessingData.run(activity, data, information);
        }
    };

    private Response.ErrorListener failUnknownReason = new Response.ErrorListener() {
        @Override
        public void onErrorResponse(VolleyError error) {
            loadingDialog.dismiss();
        }
    };

    private WebRequest.SuccessProcess<ApiV1UserDetailGetData> successResponse = new WebRequest.SuccessProcess<ApiV1UserDetailGetData>() {
        @Override
        public void run(String data, ApiV1UserDetailGetData information) {
            loadingDialog.dismiss();
            if (information.result == 0) {
                String countryName = getString(CountryConst.get(information.userCountry));

                layout.account.setRightText(information.userAccount);
                layout.birth.setRightText(information.userBirthday);
                layout.country.setRightText(countryName);
                layout.mail.setRightText(information.userEmail);

            }
        }
    };

    private View.OnClickListener scan = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
//            Intent intent = new Intent();
//            intent.setClass(BasicInformationFragment.this,com.herbhousesgobuyother.ui.normal.scanrecommend);
//            startActivity(intent);

        }
    };
}
