package com.poc2.contrube.model;

import android.content.Context;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.net.Uri;
import android.util.Log;

import java.io.InputStream;

/**
 * Created by Gary on 2016/12/13.
 */

public class BitmapCompression {

    public static Bitmap getBitmap(Uri uri, Context context) {

        try {
            //第一次只載入長寬
//            使用絕對路徑
//            InputStream iii = new FileInputStream(absolutePathOfImage);

//            使用URI
            InputStream in = context.getContentResolver().openInputStream(uri);
            BitmapFactory.Options opt = new BitmapFactory.Options();
            opt.inJustDecodeBounds = true;

            if (in != null) {
                BitmapFactory.decodeStream(in, null, opt);
                in.close();
            }

            int inSampleSize = 4;

            //第二次正式壓縮
            in = context.getContentResolver().openInputStream(uri);
            opt = new BitmapFactory.Options();
            opt.inSampleSize = inSampleSize;
            Bitmap bmp = BitmapFactory.decodeStream(in, null, opt);
            in.close();
            Log.e("bmp","Load Bitmap Success");
            return bmp;
        } catch (Exception e) {
            Log.e("bmp",e.toString());
            return null;
        }
    }
}
