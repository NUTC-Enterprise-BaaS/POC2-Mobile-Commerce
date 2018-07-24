package com.herbhousesgobuyother.contrube.component.dialog;

import android.app.AlertDialog;
import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;

import com.herbhousesgobuyother.R;

/**
 * Created by 依杰 on 2017/1/19.
 */

public class ScratChcardDialog extends AlertDialog.Builder {
    private View mView;
    private TextView result;
    private Button define;
    private ScratChcardEvent mScratChcardEvent;

    public interface ScratChcardEvent {
        void onClick();
    }

    public ScratChcardDialog(Context context) {
        super(context);
        mView = LayoutInflater.from(context).inflate(R.layout.dialog_scrat_chcard, null);
        setView(mView);
        setCancelable(true);
        findView();
        init();
    }

    private void init() {
        define.setOnClickListener(defineEvent);
    }

    private View.OnClickListener defineEvent = new View.OnClickListener() {
        @Override
        public void onClick(View v) {
            mScratChcardEvent.onClick();
        }
    };

    private void findView() {
        result = (TextView) mView.findViewById(R.id.dialog_scrat_chcard_text);
        define = (Button) mView.findViewById(R.id.dialog_scrat_chcard_button);
    }

    public void setResult(String str) {
        if (str.equals("0")) {
            result.setText("真可惜沒有中獎！");
        } else {
            result.setText(str);
        }
    }

    public void setScratChcardEvent(ScratChcardEvent event) {
        mScratChcardEvent = event;
    }
}
