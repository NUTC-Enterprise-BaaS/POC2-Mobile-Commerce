package com.androidlibrary.module.backend.data;

import android.content.Context;
import android.util.Log;
import android.widget.Toast;

import java.util.ArrayList;

/**
 * Created by chriske on 2016/3/18.
 */
public class ErrorProcessingData {
    public static final String TAG = "[ErrorProcessingData]";

    public static void run(Context activity, String data, ProcessingData information) {
        ArrayList<Exception> errorStack = information.getErrorStack();
        String content = "Get " + errorStack.size() + " errors in data processing.";
        Toast.makeText(activity, content, Toast.LENGTH_SHORT).show();
        Log.e(TAG, "Raw Data: " + data);
        for (Exception e : errorStack) {
            Log.e(TAG, "Error List: ", e);
        }
    }
}
