package com.androidlibrary.core;

import java.util.concurrent.atomic.AtomicInteger;


/**
 * Created by ChrisKe on 6/15/2015.
 */
public class GenerateViewId {
    private static final AtomicInteger sNextGeneratedId = new AtomicInteger(1);

    /*
        deprecated:
            view.setId( ( int )( Math.random() * 1000000 );  )
        recommend  to support api 16:
            view.setId( GenerateViewId.get(); )
     */

    public static int get() {
        for (; ; ) {
            final int result = sNextGeneratedId.get();
            // aapt-generated IDs have the high byte nonzero; clamp to the range under that.
            int newValue = result + 1;
            if (newValue > 0x00FFFFFF) newValue = 1; // Roll over to 1, not 0.
            if (sNextGeneratedId.compareAndSet(result, newValue)) {
                return result;
            }
        }
    }

}