package com.herbhousesgobuyother.contrube.view.normal;

import android.content.Intent;
import android.graphics.Bitmap;
import android.net.Uri;
import android.os.Bundle;
import android.provider.MediaStore;
import android.support.annotation.Nullable;
import android.support.v4.app.Fragment;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;
import android.widget.Toast;

import com.android.volley.RequestQueue;
import com.android.volley.toolbox.ImageLoader;
import com.android.volley.toolbox.NetworkImageView;
import com.android.volley.toolbox.Volley;
import com.androidlibrary.module.BitmapCache;
import com.androidlibrary.module.backend.data.ApiV1QrCodeShowGetData;
import com.herbhousesgobuyother.R;
import com.herbhousesgobuyother.contrube.controllor.qrshow.QRCodeShowController;

import java.io.UnsupportedEncodingException;

/**
 * Created by flowmaHuang on 2016/11/2.
 */

public class FragmentQRCodeShow extends Fragment {
    private TextView sharePic;
    private TextView shareUrl;
    private TextView save;
    private NetworkImageView qrCode;
    private QRCodeShowController controller;
    private ImageLoader imageLoader;
    private RequestQueue imageRequestQueue;
    private String qrCodeUrl;

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        return inflater.inflate(R.layout.fragment_qrcode_show, container, false);
    }

    @Override
    public void onActivityCreated(@Nullable Bundle savedInstanceState) {
        super.onActivityCreated(savedInstanceState);
        findView();
        init();
    }

    private void findView() {
        qrCode = (NetworkImageView) getView().findViewById(R.id.qrcode_show_qrcode);
        sharePic = (TextView) getView().findViewById(R.id.qrcode_show_share_qr_album);
        shareUrl = (TextView) getView().findViewById(R.id.qrcode_show_share_url);
        save = (TextView) getView().findViewById(R.id.qrcode_show_save);
    }

    private void init() {
        qrCodeUrl = "";
        imageRequestQueue = Volley.newRequestQueue(getActivity());
        imageLoader = new ImageLoader(imageRequestQueue, new BitmapCache());
        controller = new QRCodeShowController(getActivity());
        controller.setmCallBackEvent(callBackEvent);
        sharePic.setOnClickListener(sharePicClick);
        shareUrl.setOnClickListener(shareUrlClick);
        save.setOnClickListener(saveClick);
        controller.showQrCodeRequest();
    }

    /**
     * 轉換networkimageview成bitmap存入
     */
    private View.OnClickListener saveClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            qrCode.buildDrawingCache();
            Bitmap bitmap = qrCode.getDrawingCache();
            MediaStore.Images.Media.insertImage(getActivity().getContentResolver(), bitmap, "My GoBuy QrCode", "");
            Toast.makeText(getActivity(), R.string.scan_qr_code_albums_save, Toast.LENGTH_SHORT).show();
        }
    };

    private View.OnClickListener shareUrlClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            shareUrl("分享連結", qrCodeUrl, "分享連結");
        }
    };

    private View.OnClickListener sharePicClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            sharePic("分享QR碼", qrCodeUrl, "分享QR碼");
        }
    };

    private void shareUrl(String subject, String body, String chooserTitle) {
        Intent sharingIntent = new Intent(Intent.ACTION_SEND);
        sharingIntent.setType("text/plain");
        sharingIntent.putExtra(Intent.EXTRA_SUBJECT, subject);
        sharingIntent.putExtra(Intent.EXTRA_TEXT, body);
        startActivity(Intent.createChooser(sharingIntent, chooserTitle));
    }

    private void sharePic(String subject, String body, String chooserTitle) {
        try {
            qrCode.buildDrawingCache();
            Bitmap bitmap = qrCode.getDrawingCache();
            String url = MediaStore.Images.Media.insertImage(getActivity().getContentResolver(), bitmap, "My GoBuy QrCode", "");
            Intent sharingIntent = new Intent(Intent.ACTION_SEND);
            sharingIntent.setType("image/*");
            sharingIntent.putExtra(Intent.EXTRA_SUBJECT, subject);
            Uri uri = Uri.parse(url);
            sharingIntent.putExtra(Intent.EXTRA_STREAM, uri);
            startActivity(Intent.createChooser(sharingIntent, chooserTitle));
        } catch (Exception e) {
        }
    }

    private QRCodeShowController.CallBackEvent callBackEvent = new QRCodeShowController.CallBackEvent() {
        @Override
        public void onError() {

        }

        @Override
        public void onSuccess(ApiV1QrCodeShowGetData information) {
            if (information.result == 0) {
                qrCode.setImageUrl(dealQrcodeUrl(information.url), imageLoader);
                qrCodeUrl = information.url;
            }
        }
    };

    public String dealQrcodeUrl(String url) {
        String cht = toUtf8("http://chart.apis.google.com/chart?cht=qr&chs=300x300&chl=");
        String choe = toUtf8("&chld=H|0");
        return (cht + url + choe);
    }

    public static String toUtf8(String str) {
        try {
            return new String(str.getBytes("UTF-8"), "UTF-8");
        } catch (UnsupportedEncodingException e) {
            e.printStackTrace();
            Log.e("can't convert to utf8", e.toString());
        }
        return "";
    }
}
