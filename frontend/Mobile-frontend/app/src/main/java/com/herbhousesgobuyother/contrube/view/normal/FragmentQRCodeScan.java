package com.herbhousesgobuyother.contrube.view.normal;

import android.app.AlertDialog;
import android.content.ContentResolver;
import android.content.DialogInterface;
import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.graphics.Color;
import android.graphics.drawable.ColorDrawable;
import android.net.Uri;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v4.app.Fragment;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;
import android.widget.Toast;

import com.androidlibrary.module.backend.data.ApiV1BoundsSendData;
import com.androidlibrary.module.backend.data.ApiV1NormalBuyVoucherPostData;
import com.google.zxing.BinaryBitmap;
import com.google.zxing.MultiFormatReader;
import com.google.zxing.NotFoundException;
import com.google.zxing.RGBLuminanceSource;
import com.google.zxing.Result;
import com.google.zxing.ResultPoint;
import com.google.zxing.common.HybridBinarizer;
import com.herbhousesgobuyother.R;
import com.herbhousesgobuyother.contrube.component.dialog.ScanDialog;
import com.herbhousesgobuyother.contrube.controllor.scan.QRCodeScanController;
import com.journeyapps.barcodescanner.BarcodeCallback;
import com.journeyapps.barcodescanner.BarcodeResult;
import com.journeyapps.barcodescanner.CompoundBarcodeView;

import java.io.FileNotFoundException;
import java.util.List;

/**
 * Created by flowmaHuang on 2016/11/2.
 */

public class FragmentQRCodeScan extends Fragment {
    private final int PHOTO = 0;
    private CompoundBarcodeView scan;
    private TextView albums;
    private boolean scanState;
    private QRCodeScanController controller;
    private ScanDialog scanDialog;
    private AlertDialog alertScanDialog;

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        return inflater.inflate(R.layout.fragment_qrcode_scan, container, false);
    }

    @Override
    public void onActivityCreated(@Nullable Bundle savedInstanceState) {
        super.onActivityCreated(savedInstanceState);
        findView();
        init();
    }

    private void findView() {
        scan = (CompoundBarcodeView) getView().findViewById(R.id.scan);
        albums = (TextView) getView().findViewById(R.id.albums);
    }

    private void init() {
        scanState = false;
        controller = new QRCodeScanController(getContext());
        scanDialog = new ScanDialog(getActivity());
        scanDialog.setDialogTitle(R.string.scan_dialog_success_title);
        scanDialog.setDialogContent(R.string.scan_dialog_success_content);
        scanDialog.setButtonColor(R.drawable.activity_register_button_shape);
        alertScanDialog = scanDialog.create();
        alertScanDialog.getWindow().setBackgroundDrawable(new ColorDrawable(Color.TRANSPARENT));
        scan.getStatusView().setVisibility(View.GONE);
        scan.decodeContinuous(callback);
        albums.setOnClickListener(albumsClick);
        controller.setmCallBackEvent(callBackEvent);
        scanDialog.setCallBackEvent(dialogClick);
    }

    private ScanDialog.ScanDialogClick dialogClick = new ScanDialog.ScanDialogClick() {
        @Override
        public void onSubmitClick() {
            alertScanDialog.dismiss();
            getActivity().onBackPressed();
        }
    };

    private QRCodeScanController.CallBackEvent callBackEvent = new QRCodeScanController.CallBackEvent() {
        @Override
        public void onError() {
            scanState = false;
        }

        @Override
        public void onSuccess(ApiV1BoundsSendData information) {
            if (information.result == 0) {
                alertScanDialog.show();
            } else {
                Toast.makeText(getContext(), "QR CODE 錯誤，請換一張", Toast.LENGTH_LONG).show();
                getActivity().onBackPressed();
            }
        }

        @Override
        public void onSuccess(ApiV1NormalBuyVoucherPostData information) {
            if (information.message.contains("success")) {
                scanState = false;
                Toast.makeText(getContext(), "購買優惠券成功", Toast.LENGTH_LONG).show();
            }
        }

    };

    private View.OnClickListener albumsClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            Intent intent = new Intent();
            intent.setType("image/*");
            intent.setAction(Intent.ACTION_GET_CONTENT);
            startActivityForResult(intent, PHOTO);
        }
    };

    private BarcodeCallback callback = new BarcodeCallback() {
        @Override
        public void barcodeResult(BarcodeResult result) {
            final String data = result.getText();
            Log.e(data, result.getText());
            final String[] dataList = data.split(",");
            if (!scanState) {
                scanState = true;
                new AlertDialog.Builder(getContext())
                        .setTitle("購買優惠卷")
                        .setMessage("購買優惠卷將扣除" + dataList[1] + "點，是否確定購買優惠卷？")
                        .setPositiveButton(R.string.finger_print_dialog_yes, new DialogInterface.OnClickListener() {
                            @Override
                            public void onClick(DialogInterface dialog, int which) {
                                controller.checkStorePoint(dataList[0], dataList[1], dataList[2], dataList[3]);
                            }
                        })
                        .setNeutralButton(R.string.finger_print_dialog_no, new DialogInterface.OnClickListener() {
                            @Override
                            public void onClick(DialogInterface dialog, int which) {
                                scanState = false;
                                dialog.dismiss();
                            }
                        })
                        .show();
            }
        }

        @Override
        public void possibleResultPoints(List<ResultPoint> resultPoints) {

        }
    };

    /**
     * 從相簿選取相片，解析QR碼，並intent到網址位置
     */
    @Override
    public void onActivityResult(int requestCode, int resultCode, Intent data) {
        if (requestCode == PHOTO && data != null) {
            Uri uri = data.getData();
            ContentResolver contentResolver = getActivity().getContentResolver();
            try {
                Bitmap bitmap = BitmapFactory.decodeStream(contentResolver.openInputStream(uri));
                int width = bitmap.getWidth();
                int height = bitmap.getHeight();
                int[] pixels = new int[width * bitmap.getHeight()];
                bitmap.getPixels(pixels, 0, width, 0, 0, width, height);
                MultiFormatReader reader = new MultiFormatReader();
                RGBLuminanceSource source = new RGBLuminanceSource(width, height, pixels);
                BinaryBitmap binaryBitmap = new BinaryBitmap(new HybridBinarizer(source));
                Result result = reader.decodeWithState(binaryBitmap);
                Log.e("result", result.getText().trim());
                String shopId = result.getText().trim();
                controller.scanRequest(shopId);
            } catch (FileNotFoundException e) {
                e.printStackTrace();
                Toast.makeText(getActivity(), "QR CODE 錯誤，請換一張", Toast.LENGTH_LONG).show();
                getActivity().onBackPressed();
            } catch (NotFoundException e) {
                e.printStackTrace();
                Toast.makeText(getActivity(), "QR CODE 錯誤，請換一張", Toast.LENGTH_LONG).show();
                getActivity().onBackPressed();
            }

        }

        super.onActivityResult(requestCode, resultCode, data);
    }

    @Override
    public void onResume() {
        scan.resume();
        super.onResume();
    }

    @Override
    public void onPause() {
        scan.pause();
        super.onPause();
    }
}
