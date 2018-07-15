package com.herbhousesgobuyother.contrube.model;

import android.Manifest;
import android.content.Context;
import android.content.pm.PackageManager;
import android.location.Location;
import android.os.Bundle;
import android.support.annotation.NonNull;
import android.support.annotation.Nullable;
import android.support.v4.app.ActivityCompat;

import com.google.android.gms.common.ConnectionResult;
import com.google.android.gms.common.api.GoogleApiClient;
import com.google.android.gms.location.LocationServices;
import com.herbhousesgobuyother.contrube.view.normal.ActivityNormalAdvertisement;

/**
 * Created by Gary on 2016/11/16.
 */

public class GoogleMapModel implements GoogleApiClient.ConnectionCallbacks, GoogleApiClient.OnConnectionFailedListener {
    private Context mContext;
    private GoogleApiClient mGoogleApiClient;
    private Location mLastLocation;
    public String lat = "";
    public String lng = "";

    public GoogleMapModel(Context context) {
        this.mContext = context;
        lat = "";
        lng = "";
        initMap();
    }

    /**
     * 初始化GoogleMap參數
     */
    private void initMap() {
        if (mGoogleApiClient == null) {
            mGoogleApiClient = new GoogleApiClient.Builder(mContext)
                    .addConnectionCallbacks(this)
                    .addOnConnectionFailedListener(this)
                    .addApi(LocationServices.API)
                    .build();

            mGoogleApiClient.connect();
        }
    }

    @Override
    public void onConnected(@Nullable Bundle bundle) {
        if (ActivityCompat.checkSelfPermission(mContext, Manifest.permission.ACCESS_FINE_LOCATION) != PackageManager.PERMISSION_GRANTED && ActivityCompat.checkSelfPermission(mContext, Manifest.permission.ACCESS_COARSE_LOCATION) != PackageManager.PERMISSION_GRANTED) {
            return;
        }
        mLastLocation = LocationServices.FusedLocationApi.getLastLocation(mGoogleApiClient);
        if (mLastLocation != null) {
            lng = String.valueOf(mLastLocation.getLongitude());
            lat = String.valueOf(mLastLocation.getLatitude());
            ActivityNormalAdvertisement.lng = String.valueOf(mLastLocation.getLongitude());
            ActivityNormalAdvertisement.lat = String.valueOf(mLastLocation.getLatitude());
        }
    }

    @Override
    public void onConnectionSuspended(int i) {

    }

    @Override
    public void onConnectionFailed(@NonNull ConnectionResult connectionResult) {

    }

    public String getLongitude() {
        return lng;
    }

    public String getLatitude() {
        return lat;
    }

    public void stopConnect() {
        if (mGoogleApiClient != null) {
            mGoogleApiClient.disconnect();
        }
    }
}
