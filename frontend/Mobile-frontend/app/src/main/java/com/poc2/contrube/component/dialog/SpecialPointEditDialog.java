package com.poc2.contrube.component.dialog;

import android.app.AlertDialog;
import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;

import com.poc2.R;

/**
 * Created by 依杰 on 2016/11/23.
 */

public class SpecialPointEditDialog extends AlertDialog.Builder {
    private Context context;
    private View view;
    private TextView titleText;
    private EditText pointEdit;
    private Button cancelButton;
    private Button submitButton;
    private EditDialogEvent clickEvent;

    public SpecialPointEditDialog(Context context) {
        super(context);
        this.context = context;
        view = LayoutInflater.from(context).inflate(R.layout.dialog_special_point_edit, null);
        this.setView(view);
        this.setCancelable(false);
        init();
    }

    private void findView() {
        cancelButton = (Button) view.findViewById(R.id.dialog_special_point_edit_title_text_cancle_button);
        submitButton = (Button) view.findViewById(R.id.dialog_special_point_edit_title_text_submit_button);
        titleText = (TextView) view.findViewById(R.id.dialog_special_point_edit_title_text);
        pointEdit = (EditText) view.findViewById(R.id.dialog_special_point_edit_input_edit);
    }

    private void init() {
        findView();
        cancelButton.setOnClickListener(cancelClick);
        submitButton.setOnClickListener(submitClick);
    }

    private View.OnClickListener submitClick = new View.OnClickListener() {
        @Override
        public void onClick(View v) {
            if (clickEvent != null) {
                clickEvent.submit();
            }
        }
    };

    private View.OnClickListener cancelClick = new View.OnClickListener() {
        @Override
        public void onClick(View v) {
            if (clickEvent != null) {
                clickEvent.cancel();
            }
        }
    };

    public void setEditDialogEvent(EditDialogEvent clickEvent) {
        this.clickEvent = clickEvent;
    }

    public interface EditDialogEvent {
        void cancel();

        void submit();
    }

    public TextView getTitleText() {
        return titleText;
    }

    public EditText getEditText() {
        return pointEdit;
    }
}
