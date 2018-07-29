package com.androidlibrary.component.item;

import android.content.Context;
import android.graphics.Color;
import android.graphics.Typeface;
import android.view.Gravity;
import android.view.View;
import android.widget.EditText;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.androidlibrary.R;
import com.androidlibrary.component.dialog.LoginErrorDialog;
import com.androidlibrary.core.GenerateViewId;
import com.androidlibrary.core.Ruler;
import com.androidlibrary.module.checker.InputHelper;

/**
 * Created by ameng on 2016/6/1.
 */
public class BasicInfoItem extends RelativeLayout {
    private Ruler ruler;
    public TextView leftTextView;
    private TextView rightTextView;
    private View splitView;
    private InputHelper inputHelper;
    private EditText rightEditText;
    private LoginErrorDialog loginErrorDialog;

    public BasicInfoItem(Context context) {
        super(context);
        ruler = new Ruler(getContext());
        inputHelper = new InputHelper(getContext());
        loginErrorDialog = new LoginErrorDialog(getContext());
        leftTextView = leftTextView();
        rightTextView = rightTextView(leftTextView);
        rightEditText = rightEditText(leftTextView);
        splitView = splitView(rightTextView);
        this.addView(leftTextView);
        this.addView(rightTextView);
        this.addView(rightEditText);
        this.addView(splitView);

        rightEditText.setVisibility(INVISIBLE);
    }

    protected TextView leftTextView() {
        LayoutParams params = new LayoutParams(
                ruler.getW(20),
                ruler.getH(4));
        params.leftMargin = ruler.getW(5);

        TextView v = new TextView(getContext());
        v.setId(GenerateViewId.get());
        v.setLayoutParams(params);
        v.setTextColor(Color.parseColor("#4D4D4D"));
        v.setGravity(Gravity.CENTER);
        ruler.setAudioFit(v, 18, 10, 1);

        return v;
    }

    protected TextView rightTextView(View leftView) {
        LayoutParams params = new LayoutParams(
                ruler.getW(65),
                ruler.getH(4));
        params.addRule(RIGHT_OF, leftView.getId());
        params.addRule(ALIGN_BASELINE, leftView.getId());
        params.leftMargin = ruler.getW(9);

        TextView v = new TextView(getContext());
        v.setId(GenerateViewId.get());
        v.setLayoutParams(params);
        v.setTextColor(Color.parseColor("#B4B4B5"));
        ruler.setAudioFit(v, 16, 8, 1);

        return v;
    }

    protected EditText rightEditText(View leftView) {
        LayoutParams params = new LayoutParams(
                ruler.getW(65),
                ruler.getH(6));
        params.addRule(RIGHT_OF, leftView.getId());
        params.addRule(ALIGN_BASELINE, rightTextView.getId());
        params.leftMargin = ruler.getW(9);

        EditText v = new EditText(getContext());
        v.setId(GenerateViewId.get());
        v.setLayoutParams(params);
        v.setTypeface(Typeface.DEFAULT_BOLD, 1);
        v.setTextColor(Color.BLACK);
        v.setSingleLine(true);
        inputHelper.addEdit(v, InputHelper.EMAIL);
        inputHelper.setOnEmptyListener(new InputHelper.OnEmptyListener() {
            @Override
            public void dealEmpty(int position) {
                loginErrorDialog.setMessage(getContext().getResources().getStringArray(R.array.basic_information_layout_error_submit)[position]);
                loginErrorDialog.show();
            }
        });
        ruler.setAudioFit(v, 16, 8, 1);

        return v;
    }

    protected View splitView(View topView) {
        LayoutParams params = new LayoutParams(
                ruler.getW(95),
                ruler.getH(0.2));
        params.addRule(BELOW, topView.getId());
        params.addRule(CENTER_HORIZONTAL);
        params.topMargin = ruler.getH(2);

        View v = new View(getContext());
        v.setId(GenerateViewId.get());
        v.setLayoutParams(params);
        v.setBackgroundColor(Color.parseColor("#1D2974"));
        return v;
    }

    public void setLeftText(String message) {
        leftTextView.setText(message);

    }

    public void setRightText(String message) {
        rightTextView.setText(message);
        rightEditText.setHint(rightTextView.getText().toString().trim());
    }

    public EditText getRightEditText() {
        return rightEditText;
    }

    public Boolean click(Boolean click) {
        if (click) {
            leftTextView.setTypeface(Typeface.DEFAULT_BOLD, 1);
            leftTextView.setTextColor(Color.BLACK);
            rightTextView.setVisibility(INVISIBLE);
            rightEditText.setVisibility(VISIBLE);
        } else if (inputHelper.checked()) {
            leftTextView.setTypeface(Typeface.DEFAULT, 0);
            leftTextView.setTextColor(Color.parseColor("#ffa4a3a3"));
            rightTextView.setVisibility(VISIBLE);
            rightEditText.setVisibility(INVISIBLE);
            return true;
        }
        return false;
    }

    public TextView getLeftTextView() {
        return leftTextView;
    }

    public TextView getRightTextView() {
        return rightTextView;
    }


}
