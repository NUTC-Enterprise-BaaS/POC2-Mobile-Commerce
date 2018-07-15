package com.herbhousesgobuyother.contrube.component.dialog;

import android.app.AlertDialog;
import android.content.Context;
import android.content.DialogInterface;

/**
 * Created by ameng on 2016/6/10.
 */
public class CallDialog extends AlertDialog.Builder{
    private Context context;
    private DialogInterface.OnClickListener comfirmEvent;
    public CallDialog(Context context) {
        super(context);
        this.context = context;
        setTitle(com.androidlibrary.R.string.call_dialog_tittle);
    }
    public void setPhoneNumer(String message){
        String callingAlert = context.getResources().getString(com.androidlibrary.R.string.call_dialog_now_calling);
        setMessage(callingAlert+message);
    }
    public CallDialog setComfirmEvent(DialogInterface.OnClickListener event){
        this.comfirmEvent = event;
        return this;
    }
    private DialogInterface.OnClickListener unComfirmEvent=new DialogInterface.OnClickListener() {
        @Override
        public void onClick(DialogInterface dialog, int which) {
        }
    };

    @Override
    public AlertDialog show() {
        setPositiveButton(com.androidlibrary.R.string.call_dialog_confirm, comfirmEvent);
        setNegativeButton(com.androidlibrary.R.string.call_dialog_cancel, unComfirmEvent);
        return super.show();
    }
}
