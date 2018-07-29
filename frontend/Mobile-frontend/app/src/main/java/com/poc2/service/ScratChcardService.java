package com.poc2.service;

import android.app.AlertDialog;
import android.app.Service;
import android.content.Intent;
import android.graphics.PixelFormat;
import android.os.Bundle;
import android.os.IBinder;
import android.view.Gravity;
import android.view.MotionEvent;
import android.view.View;
import android.view.WindowManager;
import android.widget.Toast;

import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.androidlibrary.module.ApiParams;
import com.androidlibrary.module.backend.api.ApiV1LuckyMoneyGet;
import com.androidlibrary.module.backend.api.ApiV1UserLuckySendPost;
import com.androidlibrary.module.backend.data.ApiV1LuckyMoneyGetData;
import com.androidlibrary.module.backend.data.ApiV1UserLuckySendPostData;
import com.androidlibrary.module.backend.data.ErrorProcessingData;
import com.androidlibrary.module.backend.params.AccountInjection;
import com.androidlibrary.module.backend.params.ServerInfoInjection;
import com.androidlibrary.module.backend.request.WebRequest;
import com.poc2.R;
import com.poc2.component.card.ScratchCard;
import com.poc2.component.dialog.ScratChcardErrorDialog;
import com.poc2.component.dialog.ScratChcardShareDialog;
import com.poc2.component.dialog.ScratChcardSuccesDialog;
import com.poc2.component.notification.StorePromotionsNotification;
import com.poc2.contrube.component.dialog.ScratChcardDialog;
import com.poc2.ui.normal.scratchcard.ScratChcardLayout;

/**
 * Created by on 2016/7/1.
 */
public class ScratChcardService extends Service {

    private ScratChcardLayout scratChcardLayout;
    private WindowManager.LayoutParams params;
    private WindowManager windowManager;

    private String luckyToken;
    private ApiParams apiParams;
    private ServerInfoInjection serverInfoInjection;
    private AccountInjection accountInjection;

    private ScratChcardShareDialog shareDialog;
    private ScratChcardSuccesDialog succesDialog;
    private ScratChcardErrorDialog loginScratChcardErrorDialog;
    private ScratChcardDialog mScratChcardDialog;
    private AlertDialog shareAlertDialog;
    private AlertDialog succesAlertDialog;
    private AlertDialog loginScratChcardErrorAlertDialog;
    private AlertDialog mScratChcardAlertDialog;

    private Boolean isCall;

    @Override
    public void onCreate() {
        super.onCreate();
    }

    @Override
    public IBinder onBind(Intent intent) {
        return null;
    }


    @Override
    public int onStartCommand(Intent intent, int flags, int startId) {
        if (intent != null) {
            Bundle extras = intent.getExtras();
            luckyToken = extras.getString(StorePromotionsNotification.LUCKY_TOKEN);
            isCall = false;
            createFloatView();
            setEvent();
        }

        return super.onStartCommand(intent, flags, startId);
    }

    private ScratchCard.Flag mFlagEvent = new ScratchCard.Flag() {
        @Override
        public void onSuccess() {
            mScratChcardAlertDialog.show();
        }
    };

    private void setEvent() {
        succesDialog.setConfirm(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                succesAlertDialog.dismiss();
                shareAlertDialog.dismiss();
                if (scratChcardLayout.layout != null) {
                    windowManager.removeView(scratChcardLayout.layout);
                }
            }
        });
        loginScratChcardErrorDialog.setconfirmEvent(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                loginScratChcardErrorAlertDialog.dismiss();
            }
        });
        scratChcardLayout.scratchCard.setTouchListener(new ScratchCard.TouchListener() {
            @Override
            public void touching(Boolean isTouch) {
                if (isCall == false) {
                    if (isTouch == true) {
                        syncMoney();
                        scratChcardLayout.defineButton.setVisibility(View.VISIBLE);
                        scratChcardLayout.yellowButton.setVisibility(View.INVISIBLE);
                    }
                }
            }
        });

        scratChcardLayout.defineButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if (scratChcardLayout.layout != null) {
                    windowManager.removeView(scratChcardLayout.layout);
                }
            }
        });

        scratChcardLayout.yellowButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if (isCall == false) {
                    shareAlertDialog.show();
                }
            }
        });

    }

    private void createFloatView() {
        serverInfoInjection = new ServerInfoInjection();
        accountInjection = new AccountInjection(this);
        apiParams = new ApiParams(serverInfoInjection, accountInjection);
        createDialog();

        params = new WindowManager.LayoutParams(
                  WindowManager.LayoutParams.WRAP_CONTENT,
                  WindowManager.LayoutParams.WRAP_CONTENT,
                  WindowManager.LayoutParams.TYPE_TOAST,
                  WindowManager.LayoutParams.FLAG_NOT_FOCUSABLE |
                            WindowManager.LayoutParams.FLAG_NOT_TOUCH_MODAL,
                  PixelFormat.RGBA_8888
        );
        params.gravity = Gravity.CENTER;
        windowManager = (WindowManager) getSystemService(WINDOW_SERVICE);
        scratChcardLayout = new ScratChcardLayout(this);
        windowManager.addView(scratChcardLayout.layout, params);
        scratChcardLayout.closeButton.setOnTouchListener(close);
        scratChcardLayout.progressBar.setVisibility(View.VISIBLE);
    }

    private void createDialog() {
        shareDialog = new ScratChcardShareDialog(this);
        shareAlertDialog = shareDialog.create();
        shareDialog.setScratChcardShareDialogEvent(mShareDialogEvent);
        shareAlertDialog.getWindow().setType(WindowManager.LayoutParams.TYPE_TOAST);//設定提示框為系統提示框
        succesDialog = new ScratChcardSuccesDialog(this);
        succesAlertDialog = succesDialog.create();
        succesAlertDialog.getWindow().setType(WindowManager.LayoutParams.TYPE_TOAST);//設定提示框為系統提示框
        loginScratChcardErrorDialog = new ScratChcardErrorDialog(this);
        loginScratChcardErrorAlertDialog = loginScratChcardErrorDialog.create();
        loginScratChcardErrorAlertDialog.getWindow().setType(WindowManager.LayoutParams.TYPE_TOAST);//設定提示框為系統提示框
        mScratChcardDialog = new ScratChcardDialog(this);
        mScratChcardAlertDialog = mScratChcardDialog.create();
        mScratChcardDialog.setScratChcardEvent(mScratChcardEvent);
        mScratChcardAlertDialog.getWindow().setType(WindowManager.LayoutParams.TYPE_TOAST);
    }

    private ScratChcardShareDialog.ScratChcardShareDialogEvent mShareDialogEvent = new ScratChcardShareDialog.ScratChcardShareDialogEvent() {
        @Override
        public void onClickCancel() {
            shareAlertDialog.dismiss();
        }

        @Override
        public void onClickSubmit() {
            syncShare();
        }
    };

    private ScratChcardDialog.ScratChcardEvent mScratChcardEvent = new ScratChcardDialog.ScratChcardEvent() {
        @Override
        public void onClick() {
            mScratChcardAlertDialog.dismiss();
        }
    };

    private void syncMoney() {
        apiParams.luckyToken = luckyToken;
        WebRequest<ApiV1LuckyMoneyGetData> request = new ApiV1LuckyMoneyGet<>(ScratChcardService.this, apiParams);
        request.processing(new WebRequest.Processing<ApiV1LuckyMoneyGetData>() {
            @Override
            public ApiV1LuckyMoneyGetData run(String data) {
                return new ApiV1LuckyMoneyGetData(data);
            }
        }).failProcess(new WebRequest.FailProcess<ApiV1LuckyMoneyGetData>() {
            @Override
            public void run(String data, ApiV1LuckyMoneyGetData information) {
                scratChcardLayout.scratchCard.setText("真可惜沒有中獎！");
                scratChcardLayout.progressBar.setVisibility(View.INVISIBLE);
                mScratChcardDialog.setResult("真可惜沒有中獎！");
                scratChcardLayout.scratchCard.setFlagEvent(mFlagEvent);
                isCall = true;
            }
        }).unknownFailRequest(new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                String content = getString(R.string.request_load_fail);
                Toast.makeText(ScratChcardService.this, content, Toast.LENGTH_LONG).show();

            }
        }).successProcess(new WebRequest.SuccessProcess<ApiV1LuckyMoneyGetData>() {
            @Override
            public void run(String data, ApiV1LuckyMoneyGetData information) {
                scratChcardLayout.scratchCard.setText(information.luckyMoney);
                scratChcardLayout.progressBar.setVisibility(View.INVISIBLE);
                isCall = true;

                mScratChcardDialog.setResult(information.luckyMoney);
                scratChcardLayout.scratchCard.setFlagEvent(mFlagEvent);
            }
        }).start();
    }

    private void syncShare() {
        apiParams.inputFriendPhone = shareDialog.getPhoneEdit().getText().toString().trim();
        apiParams.inputFriendEmail = shareDialog.getEmailEdit().getText().toString().trim();
        apiParams.luckyToken = luckyToken;
        WebRequest<ApiV1UserLuckySendPostData> request = new ApiV1UserLuckySendPost<>(ScratChcardService.this, apiParams);

        request.processing(new WebRequest.Processing<ApiV1UserLuckySendPostData>() {
            @Override
            public ApiV1UserLuckySendPostData run(String data) {
                return new ApiV1UserLuckySendPostData(data);
            }
        }).failProcess(new WebRequest.FailProcess<ApiV1UserLuckySendPostData>() {
            @Override
            public void run(String data, ApiV1UserLuckySendPostData information) {
                ErrorProcessingData.run(ScratChcardService.this, data, information);
            }
        }).unknownFailRequest(new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                String content = getString(R.string.request_load_fail);
                Toast.makeText(ScratChcardService.this, content, Toast.LENGTH_LONG).show();
            }
        }).successProcess(new WebRequest.SuccessProcess<ApiV1UserLuckySendPostData>() {
            @Override
            public void run(String data, ApiV1UserLuckySendPostData information) {
                if (information.result == 0) {
                    succesAlertDialog.show();
                    succesDialog.layout.phoneTextView.setText(apiParams.inputFriendPhone);
                    succesDialog.layout.mailTextView.setText(apiParams.inputFriendEmail);
                } else {
                    loginScratChcardErrorAlertDialog.show();
                    loginScratChcardErrorDialog.layout.message.setText(R.string.showCorrectFriendError);
                }
            }
        }).start();
    }

    private View.OnTouchListener close = new View.OnTouchListener() {
        @Override
        public boolean onTouch(View view, MotionEvent motionEvent) {
            if (scratChcardLayout.layout != null) {
                windowManager.removeView(scratChcardLayout.layout);
            }
            return false;
        }
    };
}
