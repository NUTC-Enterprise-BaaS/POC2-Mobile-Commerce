package com.herbhousesgobuyother.contrube.model.version;

import android.content.Context;
import android.content.pm.PackageInfo;
import android.content.pm.PackageManager;

import com.herbhousesgobuyother.contrube.core.PreferencesHelper;

public class VersionHelper extends PreferencesHelper {
    private static final String FILE_NAME = VersionHelper.class.getName();
    private static final String VERSION = "0";
    private PackageInfo info;
    public boolean isUpdate;

    public VersionHelper(Context context) throws PackageManager.NameNotFoundException {
        super(context);
        info = context.getPackageManager().getPackageInfo(getContext().getPackageName(), 0);
        isUpdate = false;
    }

    public String getVersion() {
        return info.versionName;
    }

    @Override
    public String getClassName() {
        return FILE_NAME;
    }
}