package com.androidlibrary.module;


/**
 * Created by ameng on 2016/5/22.
 */
public class ApiUrls {

    public static String getDomain(ApiParams params) {
        String protocol = (params.isSSL) ? "https" : "http";
        String domain = protocol + "://" + params.domainHost + ":" + params.domainPort + "/";
        return domain;
    }
}
