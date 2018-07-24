package com.herbhousesgobuyother.contrube.component.dialog;

import android.app.AlertDialog;
import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;

import com.herbhousesgobuyother.R;

/**
 * Created by 依杰 on 2016/11/30.
 */

public class PremiumExportCsvDownloadDialog extends AlertDialog.Builder {
    private Context context;
    private View view;
    private TextView startText;
    private TextView endText;
    private Button downButton;
    private csvDownloadEvnet csvDownloadEvnet;

    public PremiumExportCsvDownloadDialog(Context context) {
        super(context);
        this.context = context;
        view = LayoutInflater.from(context).inflate(R.layout.dialog_premium_export_csv_download, null);
        this.setView(view);
        this.setCancelable(false);
        findView();
        init();
    }

    private void init() {
        downButton.setOnClickListener(downClick);
    }

    private void findView() {
        startText = (TextView) view.findViewById(R.id.dialog_special_export_csv_download_start_text);
        endText = (TextView) view.findViewById(R.id.dialog_special_export_csv_download_end_text);
        downButton = (Button) view.findViewById(R.id.dialog_special_export_csv_download_button);
    }

    private View.OnClickListener downClick = new View.OnClickListener() {
        @Override
        public void onClick(View v) {
            csvDownloadEvnet.down();
        }
    };

    public interface csvDownloadEvnet {
        void down();
    }

    public TextView getStartText() {
        return startText;
    }

    public TextView getEndText() {
        return endText;
    }

    public void setCsvDownloadEvnet(csvDownloadEvnet evnet) {
        this.csvDownloadEvnet = evnet;
    }
}
