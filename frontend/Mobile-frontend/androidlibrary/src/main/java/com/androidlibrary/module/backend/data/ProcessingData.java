package com.androidlibrary.module.backend.data;

import java.util.ArrayList;

/**
 * Created by chriske on 2016/3/14.
 */
public class ProcessingData {
    private ArrayList<Exception> errorStack;
    private ArrayList<String> reasonStack;

    public ProcessingData() {
        errorStack = new ArrayList<>();
        reasonStack = new ArrayList<>();
    }

    public void addProcessFail(Exception e, String reason) {
        if (e == null & reason == null) {
            throw new RuntimeException("Exception and reason which be added are both null.");
        }

        e = (e == null) ? new Exception(reason) : e;
        errorStack.add(e);

        reason = (reason == null) ? e.toString() : reason;
        reasonStack.add(reason);
    }

    public void addProcessFail(String reason) {
        addProcessFail(null, reason);
    }

    public boolean getProcessResult() {
        return errorStack.size() == 0;
    }

    public ArrayList<String> getReasonStack() {
        return reasonStack;
    }

    public ArrayList<Exception> getErrorStack() {
        return errorStack;
    }
}
