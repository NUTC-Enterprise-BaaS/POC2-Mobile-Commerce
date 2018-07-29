package com.poc2.component.card;

import android.content.Context;
import android.graphics.Bitmap;
import android.graphics.Canvas;
import android.graphics.Paint;
import android.graphics.Path;
import android.graphics.PorterDuff;
import android.graphics.PorterDuffXfermode;
import android.os.Handler;
import android.view.MotionEvent;
import android.widget.TextView;

/**
 * Created by 依杰 on 2016/6/28.
 */
public class ScratchCard extends TextView {
    private Paint paint;
    private Path path;
    private Bitmap bitmap = null;
    private Canvas canvasGray = null;
    int x = 0;
    int y = 0;
    private TouchListener touchListener;
    private boolean istouch;
    private boolean isComplete;
    private ScratchCard.Flag mFlag;


    public interface TouchListener {
        void touching(Boolean isTouch);
    }

    public ScratchCard(Context context) {
        super(context);
        istouch = true;
        isComplete = false;
        path = new Path();
        paint = new Paint();
        paint.setFlags(Paint.ANTI_ALIAS_FLAG); //鋸齒
        paint.setAntiAlias(true);
        paint.setDither(true);
        paint.setStyle(Paint.Style.STROKE);
        paint.setStrokeWidth(60);
        paint.setStrokeCap(Paint.Cap.ROUND); //圓形
        paint.setStrokeJoin(Paint.Join.ROUND);
        paint.setXfermode(new PorterDuffXfermode(PorterDuff.Mode.DST_IN)); //一個轉換方法 適合做橡皮擦
        paint.setAlpha(0); //透明度
        bitmap = Bitmap.createBitmap(1000, 1000, Bitmap.Config.ARGB_8888);
        canvasGray = new Canvas(bitmap);
        canvasGray.drawColor(0xFF808080);
    }

    @Override
    protected void onDraw(Canvas canvas) {
        super.onDraw(canvas);

        canvasGray.drawPath(path, paint);
        canvas.drawBitmap(bitmap, 0, 0, null);
    }

    @Override
    public boolean onTouchEvent(MotionEvent event) {
        int action = event.getAction();
        int currX = (int) event.getX();
        int currY = (int) event.getY();
        switch (action) {
            case MotionEvent.ACTION_DOWN: {
                path.reset();
                x = currX;
                y = currY;
                path.moveTo(x, y);
                istouch = true;
                touchListener.touching(true);
            }
            break;
            case MotionEvent.ACTION_MOVE: {
                path.quadTo(x, y, currX, currY);
                x = currX;
                y = currY;
                postInvalidate();
            }
            break;
            case MotionEvent.ACTION_UP: {
                new Thread(mRunnable).start();
                break;
            }
            case MotionEvent.ACTION_CANCEL: {
                path.reset();
            }
            break;
        }
        return true;
    }

    public void setTouchListener(TouchListener touchListener) {
        this.touchListener = touchListener;
    }

    public boolean getTouchState() {
        return istouch;
    }

    private Runnable mRunnable = new Runnable() {
        private int[] mPixels;
        private Handler handler = new Handler();

        @Override
        public void run() {
            int mWidth = getWidth();
            int mHeight = getHeight();

            float wipeArea = 0;
            float totalArea = mWidth * mHeight;

            Bitmap mBitmap = bitmap;

            mPixels = new int[mWidth * mHeight];

            mBitmap.getPixels(mPixels, 0, mWidth, 0, 0, mWidth, mHeight);

            for (int i = 0; i < mWidth; i++) {
                for (int j = 0; j < mHeight; j++) {
                    int index = i + j * mWidth;
                    if (mPixels[index] == 0) {
                        wipeArea++;
                    }
                }
            }

            if (wipeArea > 0 && totalArea > 0) {
                int percent = (int) (wipeArea * 100 / totalArea);
                if (percent > 30) {
                    if (mFlag != null) {
                        if (isComplete == false) {
                            handler.post(new Runnable() {
                                @Override
                                public void run() {
                                    mFlag.onSuccess();
                                }
                            });
                            isComplete = true;
                        }
                    }
                }
            }
        }

    };

    public interface Flag {
        void onSuccess();
    }

    public void setFlagEvent(Flag event) {
        mFlag = event;
    }
}
