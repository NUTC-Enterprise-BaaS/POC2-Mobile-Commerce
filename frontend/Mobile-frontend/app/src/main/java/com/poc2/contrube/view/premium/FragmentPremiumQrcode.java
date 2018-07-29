package com.poc2.contrube.view.premium;

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
import android.widget.Button;
import android.widget.Toast;

import com.android.volley.RequestQueue;
import com.android.volley.toolbox.ImageLoader;
import com.android.volley.toolbox.NetworkImageView;
import com.android.volley.toolbox.Volley;
import com.androidlibrary.module.BitmapCache;
import com.androidlibrary.module.backend.data.ApiV1PremiumQrCodeShowGetData;
import com.poc2.R;
import com.poc2.contrube.controllor.qrshow.PremiumQrcodeControllor;

import java.io.UnsupportedEncodingException;

/**
 * Created by 依杰 on 2016/11/29.
 */

public class FragmentPremiumQrcode extends Fragment {
    public NetworkImageView qrcodeImageView;
    public Button sendQrcodeButton;
    public Button storeAlbumButton;
    private ImageLoader imageLoader;
    private PremiumQrcodeControllor controllor;
    private String shareUrl = "";
    private RequestQueue imageRequestQueue;
    private View back;

    @Nullable
    @Override
    public View onCreateView(LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        return inflater.inflate(R.layout.fragment_premium_qrcode_show, container, false);
    }

    @Override
    public void onViewCreated(View view, @Nullable Bundle savedInstanceState) {
        super.onViewCreated(view, savedInstanceState);
        findView();
        init();
    }

    private void init() {
        controllor = new PremiumQrcodeControllor(getContext());
        sendQrcodeButton.setOnClickListener(send);
        storeAlbumButton.setOnClickListener(save);

        imageRequestQueue = Volley.newRequestQueue(getActivity());
        imageLoader = new ImageLoader(imageRequestQueue, new BitmapCache());
        controllor.showQrCode();
        controllor.setCallBackEvent(callBack);
        back.setOnClickListener(backClick);

    }

    private PremiumQrcodeControllor.CallBackEvent callBack = new PremiumQrcodeControllor.CallBackEvent() {

        @Override
        public void onSuccess(ApiV1PremiumQrCodeShowGetData information) {
            setUrl(information.url, imageLoader);
            shareUrl = information.url;
        }

        @Override
        public void onError() {

        }
    };

    public void setUrl(String uri, ImageLoader imageLoader) {
        String cht = toUtf8("http://chart.apis.google.com/chart?cht=qr&chs=300x300&chl=");
        String choe = toUtf8("&chld=H|0");
        Log.e("cht", cht + "");
        Log.e("choe", choe + "");
        qrcodeImageView.setImageUrl(cht + uri + choe, imageLoader);

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

    private View.OnClickListener send = new View.OnClickListener() {
        @Override
        public void onClick(View v) {
            sharePic("分享QR碼", shareUrl, "分享QR碼");
        }
    };

    private View.OnClickListener save = new View.OnClickListener() {
        @Override
        public void onClick(View v) {
            qrcodeImageView.buildDrawingCache();
            Bitmap bitmap = qrcodeImageView.getDrawingCache();
            MediaStore.Images.Media.insertImage(getActivity().getContentResolver(), bitmap, "My GoBuy QrCode", "");
            Toast.makeText(getActivity(), R.string.scan_qr_code_albums_save, Toast.LENGTH_SHORT).show();
        }
    };

    private void sharePic(String subject, String body, String chooserTitle) {
        try {
            qrcodeImageView.buildDrawingCache();
            Bitmap bitmap = qrcodeImageView.getDrawingCache();
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

    private void findView() {
        qrcodeImageView = (NetworkImageView) getView().findViewById(R.id.premium_qrcode_qr_image);
        sendQrcodeButton = (Button) getView().findViewById(R.id.special_qrcode_send_qr_button);
        storeAlbumButton = (Button) getView().findViewById(R.id.special_qrcode_store_album_button);
        back = getView().findViewById(R.id.toolbar_back_touch);
    }

    private View.OnClickListener backClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            getActivity().onBackPressed();
        }
    };

}
