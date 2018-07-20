package com.androidlibrary.component.dialog;

import android.app.DatePickerDialog;
import android.content.Context;

import java.util.Calendar;

/**
 * Created by ameng on 2016/5/23.
 */
public class ChooseDateDialog {
    private DatePickerDialog datePickerDialog;
    private Context context;
    private Calendar calendar;
    private int year;
    private int month;
    private int day;

    private DatePickerDialog.OnDateSetListener dateSetListener;

    public ChooseDateDialog(Context context) {
        this.context = context;
        calendar = Calendar.getInstance();
        year = calendar.get(Calendar.YEAR);
        month = calendar.get(Calendar.MONTH);
        day = calendar.get(Calendar.DAY_OF_MONTH);
    }


    public void show() {
        datePickerDialog.show();
    }

    public void setdateSetListener(DatePickerDialog.OnDateSetListener event) {
        dateSetListener = event;
        datePickerDialog = new DatePickerDialog(context, dateSetListener, year, month, day);
    }
}
