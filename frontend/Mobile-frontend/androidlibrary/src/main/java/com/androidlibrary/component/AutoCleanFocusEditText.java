package com.androidlibrary.component;

import android.content.Context;
import android.view.KeyEvent;
import android.widget.EditText;

/**
 * Created by chriske on 2016/5/26.
 */
public class AutoCleanFocusEditText extends EditText {
    public AutoCleanFocusEditText(Context context) {
        super(context);
        this.setSingleLine();
    }

    @Override
    public boolean onKeyPreIme(int keyCode, KeyEvent event) {
        if(keyCode == KeyEvent.KEYCODE_BACK){
            clearFocus();
        }
        return super.onKeyPreIme(keyCode, event);
    }
}
