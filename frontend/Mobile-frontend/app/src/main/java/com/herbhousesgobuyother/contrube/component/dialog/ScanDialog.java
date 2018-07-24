package com.herbhousesgobuyother.contrube.component.dialog;

import android.app.AlertDialog;
import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;

import com.herbhousesgobuyother.R;

public class ScanDialog extends AlertDialog.Builder {
    private Context context;
    private View view;
    private Button submit;
    private ScanDialog.ScanDialogClick rangeDialogClick;
    private TextView title;
    private TextView content;


    public ScanDialog(Context context) {
        super(context);
        this.context = context;
        view = LayoutInflater.from(context).inflate(R.layout.fragment_qrcode_scan_dialog, null);
        this.setView(view);
        this.setCancelable(false);
        findView();
        init();
    }

    private void findView() {
        title = (TextView) view.findViewById(R.id.scan_title);
        content = (TextView) view.findViewById(R.id.scan_content);
        submit = (Button) view.findViewById(R.id.scan_submit);
    }

    private void init() {
        submit.setOnClickListener(submitClick);
    }

    private View.OnClickListener submitClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            if (null != rangeDialogClick) {
                rangeDialogClick.onSubmitClick();
            }
        }
    };

    public void setCallBackEvent(ScanDialog.ScanDialogClick rangeDialogClick) {
        this.rangeDialogClick = rangeDialogClick;
    }

    public void setDialogTitle(int title) {
        this.title.setText(title);
    }

    public void setDialogContent(int content) {
        this.content.setText(content);

    }

    public void setButtonColor(int drawable) {
        this.submit.setBackgroundResource(drawable);
    }

    public interface ScanDialogClick {
        void onSubmitClick();
    }


}