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

public class UploadContentDialog extends AlertDialog.Builder {
    private Context context;
    private View view;
    private UploadContentDialog.UploadContentDialogClick uploadContentDialogClick;
    private TextView title;
    private TextView prompt;
    private Button submit;
    private Button cancel;
    private ImageView view1;
    private ImageView view2;
    private ImageView view3;


    public UploadContentDialog(Context context) {
        super(context);
        this.context = context;
        view = LayoutInflater.from(context).inflate(R.layout.upload_content_dialog, null);
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
        view1 = (ImageView) view.findViewById(R.id.view1);
        view2 = (ImageView) view.findViewById(R.id.view2);
        view3 = (ImageView) view.findViewById(R.id.view3);
    }

    private void init() {
        submit.setOnClickListener(submitClick);
        cancel.setOnClickListener(cancelClick);
        view1.setOnClickListener(view1Click);
        view2.setOnClickListener(view2Click);
        view3.setOnClickListener(view3Click);
    }

    private View.OnClickListener submitClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            if (null != uploadContentDialogClick) {
                uploadContentDialogClick.onSubmitClick();
            }
        }
    };
    private View.OnClickListener cancelClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            if (null != uploadContentDialogClick) {
                uploadContentDialogClick.onCancelClick();
            }
        }
    };
    private View.OnClickListener view1Click = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            if (null != uploadContentDialogClick) {
                uploadContentDialogClick.onView1Click();
            }
        }
    };
    private View.OnClickListener view2Click = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            if (null != uploadContentDialogClick) {
                uploadContentDialogClick.onView2Click();
            }
        }
    };
    private View.OnClickListener view3Click = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            if (null != uploadContentDialogClick) {
                uploadContentDialogClick.onView3Click();
            }
        }
    };

    public void setCallBackEvent(UploadContentDialog.UploadContentDialogClick uploadContentDialogClick) {
        this.uploadContentDialogClick = uploadContentDialogClick;
    }

    public void setDialogTitle(int title) {
        this.title.setText(title);
    }

    public void setDialogPrompt(int title) {
        this.prompt.setText(title);
    }

    public void setContentView1Image(Uri uri) {
        Bitmap bitmap = BitmapCompression.getBitmap(uri, context);
        view1.setImageBitmap(bitmap);
    }
    public void setContentView2Image(Uri uri) {
        Bitmap bitmap = BitmapCompression.getBitmap(uri, context);
        view2.setImageBitmap(bitmap);
    }
    public void setContentView3Image(Uri uri) {
        Bitmap bitmap = BitmapCompression.getBitmap(uri, context);
        view3.setImageBitmap(bitmap);
    }


    public void setButtonColor(int drawable) {
        this.submit.setBackgroundResource(drawable);
        this.cancel.setBackgroundResource(drawable);
    }

    public void setDialogTitleColor(int color) {
        this.title.setBackgroundResource(color);
    }

    public interface UploadContentDialogClick {
        void onSubmitClick();

        void onCancelClick();

        void onView1Click();

        void onView2Click();

        void onView3Click();
    }


}