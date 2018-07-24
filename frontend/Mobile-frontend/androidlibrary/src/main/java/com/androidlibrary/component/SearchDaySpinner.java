package com.androidlibrary.component;

import android.content.Context;
import android.graphics.Color;
import android.view.Gravity;
import android.view.View;
import android.widget.ImageView;
import android.widget.RelativeLayout;
import android.widget.Spinner;
import android.widget.TextView;

import com.androidlibrary.R;
import com.androidlibrary.core.DpToPx;
import com.androidlibrary.core.GenerateViewId;
import com.androidlibrary.core.Ruler;

/**
 * Created by Gary on 2016/5/25.
 */
public class SearchDaySpinner extends RelativeLayout {
    private Ruler ruler;
    private DpToPx equalizer;
    public TextView text;
    public RelativeLayout spinnerContainer;
    public Spinner spinner;
    public ImageView imageView;

    public SearchDaySpinner(Context context) {
        super(context);
        ruler = new Ruler(getContext());
        equalizer = new DpToPx(getContext());
        text = text();
        spinnerContainer = spinnerContainer(text);
        imageView = imageView();
        spinner = spinner(imageView);


        this.addView(text);
        this.addView(spinnerContainer);

        spinnerContainer.addView(imageView);
        spinnerContainer.addView(spinner);
    }

    private RelativeLayout spinnerContainer(View leftView) {
        LayoutParams params = new LayoutParams(
                  ruler.getW(44),
                  ruler.getH(8));
        params.addRule(RIGHT_OF, leftView.getId());
        params.leftMargin = ruler.getW(2);

        RelativeLayout v = new RelativeLayout(getContext());
        v.setId(GenerateViewId.get());
        v.setLayoutParams(params);
        v.setBackgroundResource(R.drawable.spinner_border);
        return v;
    }

    private ImageView imageView() {
        LayoutParams params = new LayoutParams(
                  ruler.getW(8),
                  ruler.getH(13));
        params.addRule(ALIGN_PARENT_RIGHT);
        params.topMargin = -ruler.getW(2);

        ImageView v = new ImageView(getContext());
        v.setId(GenerateViewId.get());
        v.setLayoutParams(params);
        v.setBackgroundResource(R.drawable.drop);
        return v;
    }

    private Spinner spinner(View rightView) {
        LayoutParams params = new LayoutParams(
                  LayoutParams.WRAP_CONTENT,
                  LayoutParams.WRAP_CONTENT);
        params.addRule(CENTER_VERTICAL);
        params.addRule(RelativeLayout.LEFT_OF, rightView.getId());

        Spinner v = new Spinner(getContext());
        v.setId(GenerateViewId.get());
        v.setLayoutParams(params);
        v.setBackgroundColor(Color.parseColor("#00000000"));

        return v;
    }

    private TextView text() {
        LayoutParams params = new LayoutParams(
                  ruler.getW(10),
                  ruler.getH(8));
        params.addRule(CENTER_VERTICAL);

        TextView v = new TextView(getContext());
        v.setId(GenerateViewId.get());
        v.setLayoutParams(params);
        v.setTextColor(Color.parseColor("#595757"));
        v.setText(R.string.search_spinner_title);
        v.setGravity(Gravity.CENTER);
        ruler.setAudioFit(v, 18, 8, 1);

        return v;
    }
}
