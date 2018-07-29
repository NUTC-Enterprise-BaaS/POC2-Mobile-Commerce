package com.androidlibrary.module;

/**
 * Created by chriske on 2016/5/31.
 * 透過 FragmentContainer 介面，使 Fragment 再取得 Container ID 時，
 * 不需知道其所在的 Activity 名稱為何，降低依賴性。
 */
public interface FragmentContainer {
    public int getContainerId();
}
