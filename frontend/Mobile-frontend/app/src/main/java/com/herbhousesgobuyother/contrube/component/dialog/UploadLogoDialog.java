package com.herbhousesgobuyother.contrube.component.dialog;

import android.app.AlertDialog;
import android.content.Context;
import android.graphics.Bitmap;
import android.net.Uri;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.TextView;

import com.herbhousesgobuyother.R;
import com.herbhousesgobuyother.contrube.model.BitmapCompression;

public class UploadLogoDialog extends AlertDialog.Builder {
    private Context context;
    private View view;
    private UploadLogoDialog.UploadLogoDialogClick uploadLogoDialogClick;
    private TextView title;
    private TextView prompt;
    private Button submit;
    private Button cancel;
    private ImageView logo;


    public UploadLogoDialog(Context context) {
        super(context);
        this.context = context;
        view = LayoutInflater.from(context).inflate(R.layout.upload_logo_dialog, null);
        this.setView(view);
        this.setCancelable(false);
        findView();
        init();
    }

    private void findView() {
        title = (TextView) view.findViewById(R.id.title);
        prompt = (TextView) view.findViewById(R.id.prompt);
        submit = (Button) view.findViewById(R.id.submit);
        cancel = (Button) view.findViewById(R.id.cancel);
        logo = (ImageView) view.findViewById(R.id.logo);
    }

    private void init() {
        submit.setOnClickListener(submitClick);
        cancel.setOnClickListener(cancelClick);
        logo.setOnClickListener(logoClick);
    }

    private View.OnClickListener submitClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            if (null != uploadLogoDialogClick) {
                uploadLogoDialogClick.onSubmitClick();
            }
        }
    };
    private View.OnClickListener cancelClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            if (null != uploadLogoDialogClick) {
                uploadLogoDialogClick.onCancelClick();
            }
        }
    };
    private View.OnClickListener logoClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            if (null != uploadLogoDialogClick) {
                uploadLogoDialogClick.onLogoClick();
            }
        }
    };

    public void setCallBackEvent(UploadLogoDialog.UploadLogoDialogClick uploadLogoDialogClick) {
        this.uploadLogoDialogClick = uploadLogoDialogClick;
    }

    public void setDialogTitle(int title) {
        this.title.setText(title);
    }

    public void setDialogPrompt(int title) {
        this.prompt.setText(title);
    }

    public void setLogoImage(Uri uri) {
        Bitmap bitmap = BitmapCompression.getBitmap(uri, context);
        logo.setImageBitmap(bitmap);
    }


    public void setButtonColor(int drawable) {
        this.submit.setBackgroundResource(drawable);
        this.cancel.setBackgroundResource(drawable);
    }

    public void setDialogTitleColor(int color) {
        this.title.setBackgroundResource(color);
    }

    public interface UploadLogoDialogClick {
        void onSubmitClick();

        void onCancelClick();

        void onLogoClick();
    }


}