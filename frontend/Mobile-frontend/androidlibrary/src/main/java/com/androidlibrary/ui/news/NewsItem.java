package com.androidlibrary.ui.news;

import android.content.Context;
import android.graphics.Color;
import android.text.TextUtils;
import android.view.Gravity;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.androidlibrary.core.GenerateViewId;
import com.androidlibrary.core.Ruler;

import java.util.Calendar;

/**
 * Created by Gary on 2016/5/24.
 */
public class NewsItem extends RelativeLayout {
    private Ruler ruler;
    public TextView title;
    public TextView date;

    public NewsItem(Context context) {
        super(context);
        ruler = new Ruler(getContext());
        title = title();
        date = date();

        this.addView(title);
        this.addView(date);

    }

    private TextView date() {
        LayoutParams params = new LayoutParams(
                  ruler.getW(25),
                  ruler.getH(4));
        params.addRule(ALIGN_PARENT_RIGHT);
        params.addRule(CENTER_VERTICAL);
        params.rightMargin = ruler.getW(1);

        TextView v = new TextView(getContext());
        v.setId(GenerateViewId.get());
        v.setLayoutParams(params);
        v.setTextColor(Color.GRAY);
        ruler.setAudioFit(v,1);

        return v;
    }

    private TextView title() {
        LayoutParams params = new LayoutParams(
                ruler.getW(60),
                ruler.getH(6));
        params.addRule(ALIGN_PARENT_LEFT);
        params.addRule(CENTER_VERTICAL);
        params.leftMargin = ruler.getW(5);

        TextView v = new TextView(getContext());
        v.setId(GenerateViewId.get());
        v.setLayoutParams(params);
        v.setTextColor(Color.GRAY);
        v.setEllipsize(TextUtils.TruncateAt.END);
        v.setSingleLine(true);
        v.setGravity(Gravity.CENTER_VERTICAL);
        ruler.setAudioFit(v,20,16,1);

        return v;
    }

    public void setTitle(String text) {
        this.title.setText(text);
    }

    public void setDate(Long timestampMillis) {
        Calendar time = Calendar.getInstance();
        time.setTimeInMillis(timestampMillis);
        int year = time.get(Calendar.YEAR);
        int month = time.get(Calendar.MONTH) + 1;
        int day = time.get(Calendar.DAY_OF_MONTH);

        this.date.setText(year + "/" + month + "/" + day);
    }

}
