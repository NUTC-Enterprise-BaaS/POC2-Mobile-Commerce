package com.androidlibrary.component;

import android.Manifest;
import android.content.Context;
import android.content.pm.PackageManager;
import android.location.Geocoder;
import android.location.Location;
import android.os.Bundle;
import android.support.v4.app.ActivityCompat;
import android.util.Log;

import com.google.android.gms.common.ConnectionResult;
import com.google.android.gms.common.api.GoogleApiClient;
import com.google.android.gms.location.LocationServices;

import java.io.IOException;
import java.text.DecimalFormat;
import java.util.ArrayList;

/**
 * Created by Gary on 2016/6/30.
 */
public class DealMapDistance implements GoogleApiClient.ConnectionCallbacks, GoogleApiClient.OnConnectionFailedListener {
    private Context context;
    public ArrayList<String> addressGroup;
    private ArrayList<String> distanceGroup = new ArrayList<>();
    private double locationLongitude;
    private double locationLatitude;
    private GoogleApiClient mGoogleApiClient;
    private Location mLastLocation;

    public DealMapDistance(Context context, ArrayList<String> addressGroup) {
        this.addressGroup = addressGroup;
        this.context = context;
        initMap();
        saveAllPoint();
    }

    private void initMap() {
        if (mGoogleApiClient == null) {
            mGoogleApiClient = new GoogleApiClient.Builder(context)
                    .addConnectionCallbacks(this)
                    .addOnConnectionFailedListener(this)
                    .addApi(LocationServices.API)
                    .build();
        }
    }


    private void saveAllPoint() {
        if (!addressGroup.isEmpty()) {
            for (int i = 0; i < addressGroup.size(); i++) {
                Geocoder geocoder = new Geocoder(context);
                try {
                    String address = addressGroup.get(i);
                    double longitude = geocoder.getFromLocationName(address, 1).get(0).getLongitude();
                    double latitude = geocoder.getFromLocationName(address, 1).get(0).getLatitude();
                    DecimalFormat df = new DecimalFormat("#.#");
                    String distance = df.format(getDistance(locationLatitude, locationLongitude, latitude, longitude));
                    distanceGroup.add(distance);
                } catch (IOException e) {
                    e.printStackTrace();
                }
            }
        }


    }


    public double getDistance(double startLatitude, double startLongitude, double endLatitude, double endLongitude) {

        double startLat = (Math.PI / 180) * startLatitude;
        double endLat = (Math.PI / 180) * endLatitude;

        double startLon = (Math.PI / 180) * startLongitude;
        double endLon = (Math.PI / 180) * endLongitude;


        //地球半徑
        double R = 6371;

        //兩點距離為km
        double d = Math.acos(Math.sin(startLat) * Math.sin(endLat) + Math.cos(startLat) * Math.cos(endLat) * Math.cos(endLon - startLon)) * R;

        return d;
    }

    public ArrayList<String> getDistanceGroup() {
        Log.e("TAG", distanceGroup.size() + "");
        return distanceGroup;
    }

    public String getLongitude() {
        return String.valueOf(locationLongitude);
    }

    public String getLatitude() {
        return String.valueOf(locationLongitude);
    }

    @Override
    public void onConnected(Bundle bundle) {
        if (ActivityCompat.checkSelfPermission(context, Manifest.permission.ACCESS_FINE_LOCATION) != PackageManager.PERMISSION_GRANTED && ActivityCompat.checkSelfPermission(context, Manifest.permission.ACCESS_COARSE_LOCATION) != PackageManager.PERMISSION_GRANTED) {
            return;
        }
        mLastLocation = LocationServices.FusedLocationApi.getLastLocation(mGoogleApiClient);
        if (mLastLocation != null) {
            locationLongitude = mLastLocation.getLongitude();
            locationLatitude = mLastLocation.getLatitude();
        }
    }

    @Override
    public void onConnectionSuspended(int i) {

    }

    @Override
    public void onConnectionFailed(ConnectionResult connectionResult) {

    }
}
