package com.androidlibrary.module;

/**
 * Created by chriske on 2016/6/2.
 */
public class StateBundle {
    private int id;
    private Object data;

    // 可根據不同狀態放入不同資料，功能類似 View.setTag()。
    public void setData(Object data) {
        this.data = data;
    }

    public Object getData() {
        return data;
    }

    public int getId() {
        return id;
    }

    // 運用靜態方法可修改私有變數的特性，使變數只能在建立時修改數值，避免數值再次變動。
    public static StateBundle build(int id) {
        StateBundle bundle = new StateBundle();
        bundle.id = id;
        return bundle;
    }
}
