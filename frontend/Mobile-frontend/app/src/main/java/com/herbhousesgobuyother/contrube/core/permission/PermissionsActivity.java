package com.herbhousesgobuyother.contrube.core.permission;

import android.annotation.TargetApi;
import android.app.Activity;
import android.app.AlertDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.net.Uri;
import android.os.Build;
import android.os.Bundle;
import android.provider.Settings;
import android.support.annotation.NonNull;
import android.support.annotation.Nullable;
import android.support.v4.app.ActivityCompat;
import android.support.v7.app.AppCompatActivity;


/**
 * Created by ameng on 9/30/16.
 */
@TargetApi(Build.VERSION_CODES.M)
public class PermissionsActivity extends AppCompatActivity {
    public static final int PERMISSIONS_ACCEPT = 0;
    public static final int PERMISSIONS_REFUSE = 1;

    private static final int PERMISSION_REQUEST_CODE = 2;
    private static final String EXTRA_PERMISSIONS = "permission";
    private PermissionsChecker permissionsChecker;
    private boolean isRequireCheck;

    public static void startPermissionsForResult(Activity activity, int requestCode, String... permissions) {
        Intent intent = new Intent(activity, PermissionsActivity.class);
        intent.putExtra(EXTRA_PERMISSIONS, permissions);
        ActivityCompat.startActivityForResult(activity, intent, requestCode, null);
    }

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getIntent() == null || !getIntent().hasExtra(EXTRA_PERMISSIONS)) {
            throw new RuntimeException("PermissionsActivity need to use a static method startGalleryPickingForResult to start!");
        }
        permissionsChecker = new PermissionsChecker(this);
        isRequireCheck = true;
    }

    @Override
    protected void onResume() {
        super.onResume();
        if (isRequireCheck) {
            String[] permissions = getExtraPermissions();
            if (permissionsChecker.missingPermissions(permissions)) {
                requestPermissions(permissions);
            } else {
                allPermissionsAccept();
            }
        } else {
            isRequireCheck = true;
        }
    }

    private String[] getExtraPermissions() {
        return getIntent().getStringArrayExtra(EXTRA_PERMISSIONS);
    }


    private void requestPermissions(String... permissions) {
        this.requestPermissions(permissions, PERMISSION_REQUEST_CODE);
    }

    /**
     * if the privilege is not missing then finish this page.
     * if the privilege is missing,prompted Dialog.
     *
     * @param requestCode  requestCode
     * @param permissions  permissions
     * @param grantResults grantResults
     */
    @Override
    public void onRequestPermissionsResult(int requestCode, @NonNull String[] permissions, @NonNull int[] grantResults) {
        if (requestCode == PERMISSION_REQUEST_CODE && isAllPermissionsAccept(grantResults)) {
            isRequireCheck = true;
            allPermissionsAccept();
        } else {
            isRequireCheck = false;
            showMissingPermissionDialog();
        }
    }

    private boolean isAllPermissionsAccept(@NonNull int[] grantResults) {
        for (int grantResult : grantResults) {
            if (grantResult == PackageManager.PERMISSION_DENIED) {
                return true;
            }
        }
        return false;
    }

    private void showMissingPermissionDialog() {
        AlertDialog.Builder builder = new AlertDialog.Builder(PermissionsActivity.this);
        builder.setTitle("Help");
        builder.setMessage("Missed needing permissions ");

        builder.setNegativeButton("Quit", new DialogInterface.OnClickListener() {
            @Override
            public void onClick(DialogInterface dialog, int which) {
                setResult(PERMISSIONS_REFUSE);
                finish();
            }
        });

        builder.setPositiveButton("Settings", new DialogInterface.OnClickListener() {
            @Override
            public void onClick(DialogInterface dialog, int which) {
                startSettings();
            }
        });

        builder.show();
    }

    private void startSettings() {
        Intent intent = new Intent(Settings.ACTION_APPLICATION_DETAILS_SETTINGS);
        intent.setData(Uri.parse("package:" + getPackageName()));
        startActivity(intent);
    }

    private void allPermissionsAccept() {
        setResult(PERMISSIONS_ACCEPT);
        finish();
    }
}
