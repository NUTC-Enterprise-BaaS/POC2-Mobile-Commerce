package com.androidlibrary.module.watcher;

import android.text.Editable;
import android.text.TextWatcher;

/**
 * Created by chriske on 2016/5/26.
 */
public class EnglishNumberTextWatcher implements TextWatcher {
    @Override
    public void beforeTextChanged(CharSequence s, int start, int count, int after) {
    }

    @Override
    public void onTextChanged(CharSequence s, int start, int before, int count) {
    }

    @Override
    public void afterTextChanged(Editable s) {
        if (s.length() == 0) {
            return;
        }
        int unicode = s.toString().codePointAt(s.length() - 1);
        if (unicode >= 46 && unicode <= 57) {   // 数字
            return;
        }
        if (unicode >= 64 && unicode <= 90) {   // 大寫字母
            return;
        }
        if (unicode >= 97 && unicode <= 122) {  // 小寫字母
            return;
        }
        s.delete(s.length() - 1, s.length());
    }
}
