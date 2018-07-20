package com.androidlibrary.module.backend.request;

import android.content.Context;
import android.os.Handler;

import com.android.volley.AuthFailureError;
import com.android.volley.DefaultRetryPolicy;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.RetryPolicy;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;
import com.androidlibrary.module.backend.data.ProcessingData;

import java.util.HashMap;
import java.util.Map;

/**
 * Created by chriske on 2016/3/14.
 */
public abstract class WebRequest<T extends ProcessingData> {
    private static RequestQueue queue;
    private Processing processingFlow;
    private SuccessProcess successProcessFlow;
    private SuccessBackgroundProcess successBackgroundFlow;
    private FailProcess failProcessFlow;
    private FailBackgroundProcess failBackgroundFlow;
    private Response.ErrorListener failRequestFlow;
    private Handler handler;
    private static final int socketTimeout = 120000;
    private RetryPolicy policy;
    private HashMap<Integer, FailRequest<? extends ProcessingData>> statueCodeFailQueue;
    private HashMap<Class<? extends VolleyError>, FailRequest<? extends ProcessingData>> exceptionFailQueue;

    public WebRequest(Context context) {
        if (queue == null) {
            queue = Volley.newRequestQueue(context);
        }
        policy = new DefaultRetryPolicy(socketTimeout, 2, DefaultRetryPolicy.DEFAULT_BACKOFF_MULT);

        statueCodeFailQueue = new HashMap<>();
        exceptionFailQueue = new HashMap<>();
    }

    public WebRequest<T> processing(Processing<T> event) {
        processingFlow = event;
        return this;
    }

    public WebRequest<T> successProcess(SuccessProcess<T> event) {
        successProcessFlow = event;
        handler = new Handler();
        return this;
    }

    public WebRequest<T> successProcess(SuccessBackgroundProcess<T> event) {
        successBackgroundFlow = event;
        return this;
    }

    public WebRequest<T> failProcess(FailBackgroundProcess<T> event) {
        failBackgroundFlow = event;
        return this;
    }

    public WebRequest<T> failProcess(FailProcess<T> event) {
        failProcessFlow = event;
        handler = new Handler();
        return this;
    }

    public WebRequest<T> unknownFailRequest(Response.ErrorListener event) {
        this.failRequestFlow = event;
        return this;
    }


    public WebRequest<T> failRequest(int statusCode, FailRequest<T> event) {
        statueCodeFailQueue.put(statusCode, event);
        return this;
    }

    public WebRequest<T> failRequest(Class<? extends VolleyError> exception, FailRequest<T> event) {
        exceptionFailQueue.put(exception, event);
        return this;
    }

    public void start() {
        StringRequest request = new StringRequest(getMethod(), getUrl(), requestSuccess, requestFail) {
            @Override
            public Map<String, String> getHeaders() throws AuthFailureError {
                return getHeader();
            }

            @Override
            protected Map<String, String> getParams() throws AuthFailureError {
                return WebRequest.this.getPostParams();
            }
        };
        request.setRetryPolicy(policy);
        queue.add(request);
    }

    private Response.Listener<String> requestSuccess = new Response.Listener<String>() {
        @Override
        public void onResponse(final String response) {
            new Thread(new Runnable() {
                @Override
                public void run() {
                    runInBackground(response);
                }
            }).start();
        }
    };

    private Response.ErrorListener requestFail = new Response.ErrorListener() {
        @Override
        public void onErrorResponse(VolleyError error) {
            int statusErrorSize = statueCodeFailQueue.size();
            int exceptionErrorSize = exceptionFailQueue.size();
            boolean statusErrorEmpty = statusErrorSize == 0;
            boolean exceptionErrorEmpty = exceptionErrorSize == 0;
            if (statusErrorEmpty && exceptionErrorEmpty) {
                failRequestFlow.onErrorResponse(error);
                return;
            }

            Class<? extends VolleyError> errorClass = error.getClass();
            boolean isExceptionError = error.networkResponse == null;
            boolean isContainException = exceptionFailQueue.containsKey(errorClass);
            if (isExceptionError && isContainException) {
                exceptionFailQueue.get(errorClass).run(0, error);
                return;
            } else if (isExceptionError) {
                failRequestFlow.onErrorResponse(error);
                return;
            }

            int statusCode = error.networkResponse.statusCode;
            boolean isContainerStatusCode = statueCodeFailQueue.containsKey(statusCode);
            if (isContainerStatusCode) {
                statueCodeFailQueue.get(statusCode).run(statusCode, error);
                return;
            } else {
                failRequestFlow.onErrorResponse(error);
                return;
            }
        }
    };

    private void runInBackground(final String response) {
        ProcessingData information = processingFlow.run(response);
        if (information.getProcessResult()) {
            runSuccessProcess(response, information);
        } else {
            runFailProcess(response, information);
        }
    }

    protected void runSuccessProcess(final String data, final ProcessingData information) {
        if (successBackgroundFlow == null) {

            handler.post(new Runnable() {
                @Override
                public void run() {
                    successProcessFlow.run(data, information);
                }
            });
        } else {
            Thread thread = new Thread(new Runnable() {
                @Override
                public void run() {
                    successBackgroundFlow.run(data, information);
                }
            });
            thread.run();
        }
    }

    protected void runFailProcess(final String data, final ProcessingData information) {
        if (successBackgroundFlow == null) {
            handler.post(new Runnable() {
                @Override
                public void run() {
                    failProcessFlow.run(data, information);
                }
            });
        } else {
            Thread thread = new Thread(new Runnable() {
                @Override
                public void run() {
                    successBackgroundFlow.run(data, information);
                }
            });
            thread.run();
        }
    }

    protected abstract int getMethod();

    protected abstract String getUrl();

    protected abstract Map<String, String> getHeader();

    protected abstract Map<String, String> getPostParams();

    public interface Processing<T extends ProcessingData> {
        public T run(String data);
    }

    public interface SuccessProcess<T extends ProcessingData> {
        public void run(String data, T information);
    }

    public interface SuccessBackgroundProcess<T extends ProcessingData> {
        public void run(String data, T information);
    }

    public interface FailProcess<T extends ProcessingData> {
        public void run(String data, T information);
    }

    public interface FailBackgroundProcess<T extends ProcessingData> {
        public void run(String data, T information);
    }

    public interface FailRequest<T extends ProcessingData> {
        public void run(int statusCode, VolleyError error);
    }
}
