package com.androidlibrary.module.backend.params;


import com.androidlibrary.module.ApiParams;

/**
 * Created by chriske on 2016/3/14.
 */
public class ServerInfoInjection extends ParamsInjection {

    @Override
    public void inject(ApiParams params) {
        //本地測試port
//        params.domainHost = "163.17.136.252";

        //廠商測試port
//        params.domainHost = "106.184.6.69";
//        params.domainHost = "10.26.1.228";
        params.domainHost = "211.20.7.116";

//        params.domainPort = 5002;
        params.domainPort = 8080;
        params.isSSL = false;
    }
}
