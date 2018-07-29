package com.poc2.contrube.view.special;

import android.app.AlertDialog;
import android.content.ContentResolver;
import android.content.Context;
import android.content.Intent;
import android.content.res.Configuration;
import android.content.res.Resources;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.graphics.Color;
import android.graphics.drawable.ColorDrawable;
import android.net.Uri;
import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.util.Log;
import android.view.View;
import android.widget.TextView;
import android.widget.Toast;

import com.androidlibrary.module.backend.data.ApiV1IdPhoneData;
import com.google.zxing.BinaryBitmap;
import com.google.zxing.MultiFormatReader;
import com.google.zxing.NotFoundException;
import com.google.zxing.RGBLuminanceSource;
import com.google.zxing.Result;
import com.google.zxing.ResultPoint;
import com.google.zxing.common.HybridBinarizer;
import com.poc2.R;
import com.poc2.contrube.component.dialog.ScanDialog;
import com.poc2.contrube.controllor.recommend.SpecialRegisterRecommendController;
import com.journeyapps.barcodescanner.BarcodeCallback;
import com.journeyapps.barcodescanner.BarcodeResult;
import com.journeyapps.barcodescanner.CompoundBarcodeView;

import java.io.FileNotFoundException;
import java.util.List;

/**
 * Created by cheng on 2016/11/1.
 */
public class ActivitySpecialRegisterRecommend extends AppCompatActivity {
    private Context mContext;
    private final int PHOTO = 0;
    private CompoundBarcodeView scan;
    private TextView albums;
    private boolean scanState;
    private ScanDialog scanDialog;
    private AlertDialog alertScanDialog;
    private SpecialRegisterRecommendController controller;
    private String itemId;
    private String phone;
    private View back;

    @Override

    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_special_register_recommend);
        findView();
        initSystemFont();
        init();
    }

    private void findView() {
        scan = (CompoundBarcodeView) findViewById(R.id.scan);
        albums = (TextView) findViewById(R.id.albums);
        back = findViewById(R.id.toolbar_back_touch);
    }

    private void init() {
        mContext = this;
        scanState = false;
        itemId = "";
        phone = "";
        controller = new SpecialRegisterRecommendController(mContext);
        scanDialog = new ScanDialog(mContext);
        scanDialog.setDialogTitle(R.string.register_recommend_dialog_success_title);
        scanDialog.setDialogContent(R.string.register_recommend_dialog_success_content);
        scanDialog.setButtonColor(R.drawable.layout_fragment_special_point_dialog_button);
        alertScanDialog = scanDialog.create();
        alertScanDialog.getWindow().setBackgroundDrawable(new ColorDrawable(Color.TRANSPARENT));
        scan.getStatusView().setVisibility(View.GONE);
        albums.setOnClickListener(albumsClick);
        controller.setmCallBackEvent(callBackEvent);
        scanDialog.setCallBackEvent(dialogClick);
        scan.decodeContinuous(callback);
        back.setOnClickListener(backClick);

    }

    private BarcodeCallback callback = new BarcodeCallback() {
        @Override
        public void barcodeResult(BarcodeResult result) {
            String data = result.getText();
            if (!scanState) {
                scanState = true;
                if (data.contains("itemId")) {
                    String[] bits = data.split("=");
                    itemId = bits[bits.length - 1];
                   controller.scanRequest(itemId);
                } else {
                    Toast.makeText(mContext, "QR CODE 錯誤，請換一張", Toast.LENGTH_LONG).show();
                    finish();
                }
            }
        }

        @Override
        public void possibleResultPoints(List<ResultPoint> resultPoints) {

        }
    };

    private ScanDialog.ScanDialogClick dialogClick = new ScanDialog.ScanDialogClick() {
        @Override
        public void onSubmitClick() {
            alertScanDialog.dismiss();
            Intent intent = new Intent();
            Bundle bundle = new Bundle();
            bundle.putString("phone", phone);
            bundle.putString("id", itemId);
            intent.putExtras(bundle);
            setResult(RESULT_OK, intent);
            finish();
        }
    };

    private SpecialRegisterRecommendController.CallBackEvent callBackEvent = new SpecialRegisterRecommendController.CallBackEvent() {
        @Override
        public void onError() {

        }

        @Override
        public void onSuccess(ApiV1IdPhoneData information) {
            if (information.result == 0) {
                phone = information.phone;
                alertScanDialog.show();
            } else {
                Toast.makeText(mContext, "QR CODE 錯誤，請換一張", Toast.LENGTH_LONG).show();
                finish();
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

    /**
     * 從相簿選取相片，解析QR碼，並intent到網址位置
     */
    @Override
    public void onActivityResult(int requestCode, int resultCode, Intent data) {
        if (requestCode == PHOTO && data != null) {
            Uri uri = data.getData();
            ContentResolver contentResolver = this.getContentResolver();
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
                String url = result.getText().trim();
                Log.e("codeReaderListener", url);
                if (url.contains("itemId")) {
                    String[] bits = url.split("=");
                    itemId = bits[bits.length - 1];
                    controller.scanRequest(itemId);
                } else {
                    Toast.makeText(mContext, "QR CODE 錯誤，請換一張", Toast.LENGTH_LONG).show();
                    finish();
                }
            } catch (FileNotFoundException e) {
                e.printStackTrace();
                Toast.makeText(mContext, "QR CODE 錯誤，請換一張", Toast.LENGTH_LONG).show();
                finish();
            } catch (NotFoundException e) {
                e.printStackTrace();
                Toast.makeText(mContext, "QR CODE 錯誤，請換一張", Toast.LENGTH_LONG).show();
                finish();
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

    private void initSystemFont() {
        Resources res = getResources();
        Configuration config = new Configuration();
        config.setToDefaults();
        res.updateConfiguration(config, res.getDisplayMetrics());
        float scale = getResources().getConfiguration().fontScale;
        Log.e("scale", scale + "");
    }
    private View.OnClickListener backClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            finish();
        }
    };
}