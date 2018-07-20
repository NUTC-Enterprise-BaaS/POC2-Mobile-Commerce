package com.herbhousesgobuyother.contrube.component.dialog;

import android.app.AlertDialog;
import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.SeekBar;

import com.herbhousesgobuyother.R;

public class RangeSearchDialog extends AlertDialog.Builder {
    private Context context;
    private View view;
    private EditText input;
    private SeekBar seekBar;
    private Button cancel;
    private Button submit;
    private RangeSearchDialog.RangeDialogClick rangeDialogClick;

    public RangeSearchDialog(Context context) {
        super(context);
        this.context = context;
        view = LayoutInflater.from(context).inflate(R.layout.fragment_browse_store_range_dialog, null);
        this.setView(view);
        this.setCancelable(false);
        findView();
        init();
    }

    private void findView() {
        input = (EditText) view.findViewById(R.id.range_input);
        seekBar = (SeekBar) view.findViewById(R.id.range_seekbar);
        cancel = (Button) view.findViewById(R.id.range_cancel);
        submit = (Button) view.findViewById(R.id.range_submit);
    }

    private void init() {
        seekBar.setOnSeekBarChangeListener(seekBarListener);
        cancel.setOnClickListener(cancelClick);
        submit.setOnClickListener(submitClick);
    }

    private SeekBar.OnSeekBarChangeListener seekBarListener = new SeekBar.OnSeekBarChangeListener() {
        @Override
        public void onProgressChanged(SeekBar seekBar, int progress, boolean fromUser) {
            input.setText(String.valueOf(progress));
        }

        @Override
        public void onStartTrackingTouch(SeekBar seekBar) {

        }

        @Override
        public void onStopTrackingTouch(SeekBar seekBar) {

        }
    };

    private View.OnClickListener cancelClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            if (null != rangeDialogClick) {
                rangeDialogClick.onCancelClick();
            }
        }
    };

    private View.OnClickListener submitClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            if (null != rangeDialogClick) {
                rangeDialogClick.onSubmitClick(getRange());
            }
        }
    };

    public String getRange() {
        return input.getText().toString().trim();
    }

    public void setCallBackEvent(RangeSearchDialog.RangeDialogClick rangeDialogClick) {
        this.rangeDialogClick = rangeDialogClick;
    }

    public interface RangeDialogClick {
        void onCancelClick();

        void onSubmitClick(String range);
    }


}