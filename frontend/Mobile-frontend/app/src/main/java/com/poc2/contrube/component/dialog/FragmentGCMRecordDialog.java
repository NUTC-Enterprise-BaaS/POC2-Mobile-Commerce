package com.poc2.contrube.component.dialog;

import android.app.AlertDialog;
import android.content.Context;
import android.text.Editable;
import android.text.TextWatcher;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;

import com.poc2.R;

/**
 * Created by 依杰 on 2018/5/10.
 */

public class FragmentGCMRecordDialog extends AlertDialog.Builder {
    private Button mSubmitButton;
    private Button mCancelButton;
    private EditText mPointEdit;
    private TextView mResultText;
    private TextView mTitleText;
    private TextView mRateText;
    private TextView mOwnRateText;
    private TextView mPointText;
    private View view;
    private FragmentGCMRecordDialog.DialogEvent mEvent;
    private LoginErrorDialog mLoginErrorDialog;
    private Context mContext;

    public FragmentGCMRecordDialog(Context context) {
        super(context);
        view = LayoutInflater.from(context).inflate(R.layout.dialog_gcm_record, null);
        this.setView(view);
        this.setCancelable(false);
        mContext = context;
        init();
    }

    public interface DialogEvent {
        void submit(String point);

        void cancel();
    }

    private void finView() {
        mTitleText = view.findViewById(R.id.text_title);
        mSubmitButton = view.findViewById(R.id.button_submit);
        mCancelButton = view.findViewById(R.id.button_cancel);
        mPointEdit = view.findViewById(R.id.edit_point);
        mResultText = view.findViewById(R.id.text_result_point);
        mRateText = view.findViewById(R.id.text_convert_point);
        mPointText = view.findViewById(R.id.text_point);
        mOwnRateText = view.findViewById(R.id.text_rate);
    }

    private void init() {
        finView();
        mSubmitButton.setOnClickListener(submitClick);
        mCancelButton.setOnClickListener(cancleClick);
        mLoginErrorDialog = new LoginErrorDialog(mContext);
        mPointEdit.addTextChangedListener(new TextWatcher() {
            @Override
            public void beforeTextChanged(CharSequence charSequence, int i, int i1, int i2) {

            }

            @Override
            public void onTextChanged(CharSequence charSequence, int i, int i1, int i2) {
                if (charSequence.length() > 0) {
                    if (mRateText.getText() != null) {
                        mResultText.setText(String.valueOf(Integer.valueOf(String.valueOf(charSequence))
                                * Integer.valueOf(mRateText.getText().toString())
                                / Integer.valueOf(mOwnRateText.getText().toString())));
                    }
                } else mResultText.setText("0");
            }

            @Override
            public void afterTextChanged(Editable editable) {

            }
        });
    }

    public void setEditDialogEvent(FragmentGCMRecordDialog.DialogEvent clickEvent) {
        this.mEvent = clickEvent;
    }

    private View.OnClickListener submitClick = new View.OnClickListener() {
        @Override
        public void onClick(View v) {
            if (mPointEdit.getText().toString().isEmpty() || mPointEdit.getText() == null) {
                mLoginErrorDialog.setTitle("錯誤").setMessage("請填寫想轉換的點數數值").show();
                return;
            }
            if (mEvent != null) {
                mEvent.submit(mPointEdit.getText().toString());
            }
        }
    };

    private View.OnClickListener cancleClick = new View.OnClickListener() {
        @Override
        public void onClick(View v) {
            if (mEvent != null) {
                mEvent.cancel();
            }
        }
    };

    public TextView getRateTitleText() {
        return mRateText;
    }

    public TextView getTitleText() {
        return mTitleText;
    }

    public TextView getPointTextText() {
        return mPointText;
    }

    public TextView getOwnRateTitleText() {
        return mOwnRateText;
    }
}
