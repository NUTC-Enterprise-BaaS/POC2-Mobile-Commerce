package com.poc2.ui.normal.scratchcard;

import android.content.Context;
import android.graphics.Color;
import android.graphics.drawable.GradientDrawable;
import android.support.percent.PercentRelativeLayout;
import android.view.Gravity;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.ListView;
import android.widget.ProgressBar;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.poc2.R;
import com.poc2.component.button.CloseButton;
import com.poc2.component.card.ScratchCard;

/**
 * Created byon 2016/7/1.
 */
public class ScratChcardLayout {
    public PercentRelativeLayout layout;
    private GradientDrawable drawable;
    public RelativeLayout textViewRelativeLayout;
    public RelativeLayout closeRelativeLayout;
    public RelativeLayout listViewRelativeLayout;
    public RelativeLayout yellowButtonRelativeLayout;
    public RelativeLayout scratchCardRelativeLayout;
    public PercentRelativeLayout percentRelativeLayout;
    public ImageView scratchCardLogo;
    public TextView remindTextView;
    public CloseButton closeButton;
    public ListView dateListView;
    public ScratchCard scratchCard;
    public Button yellowButton;
    public Button defineButton;
    private Context context;
    public ProgressBar progressBar;

    public ScratChcardLayout(Context context) {
        LayoutInflater inflater1 = LayoutInflater.from(context);
        layout = (PercentRelativeLayout) inflater1.inflate(R.layout.scratch_card_layout, null);
        this.context = context;
        findViewById();

        closeButton = closeButton();
        percentRelativeLayout();
        scratchCard = scratchCard();
        yellowButton = yellowButton();
        defineButton = defineButton();
        remindTextView = remindTextView();
        imageView();
        dateListView = dateListView();

        textViewRelativeLayout.addView(remindTextView);
        closeRelativeLayout.addView(closeButton);
        listViewRelativeLayout.addView(dateListView);
        yellowButtonRelativeLayout.addView(defineButton);
        yellowButtonRelativeLayout.addView(yellowButton);
        scratchCardRelativeLayout.addView(scratchCard);

    }

    private Button defineButton() {
        RelativeLayout.LayoutParams params = new RelativeLayout.LayoutParams(
                  ViewGroup.LayoutParams.MATCH_PARENT,
                  ViewGroup.LayoutParams.WRAP_CONTENT);

        GradientDrawable drawable = new GradientDrawable();
        drawable.setShape(GradientDrawable.RECTANGLE);
        drawable.setCornerRadius(50);
        drawable.setColor(0xFFEDC240);

        Button v = new Button(context);
        v.setLayoutParams(params);
        v.setGravity(Gravity.CENTER);
        v.setText(R.string.phone_send_point_confirm);
        v.setTextSize(18);
        v.setTextColor(0xFFCC2C24);
        v.setVisibility(View.INVISIBLE);
        v.setBackground(drawable);

        return v;
    }

    private void findViewById() {
        textViewRelativeLayout = (RelativeLayout) layout.findViewById(R.id.remindTextView);
        closeRelativeLayout = (RelativeLayout) layout.findViewById(R.id.close);
        listViewRelativeLayout = (RelativeLayout) layout.findViewById(R.id.listview);
        scratchCardLogo = (ImageView) layout.findViewById(R.id.scratchCardLogo);
        yellowButtonRelativeLayout = (RelativeLayout) layout.findViewById(R.id.YellowButton);
        scratchCardRelativeLayout = (RelativeLayout) layout.findViewById(R.id.ScratchCard);
        percentRelativeLayout = (PercentRelativeLayout) layout.findViewById(R.id.garyView);
        progressBar = (ProgressBar) layout.findViewById(R.id.progressBar);
    }

    private TextView remindTextView() {
        RelativeLayout.LayoutParams params = new RelativeLayout.LayoutParams(
                  ViewGroup.LayoutParams.MATCH_PARENT,
                  ViewGroup.LayoutParams.MATCH_PARENT);


        TextView v = new TextView(context);
        v.setLayoutParams(params);
        drawable = new GradientDrawable();
        drawable.setAlpha(0);
        v.setBackground(drawable);
        v.setText(R.string.scratchcard_nonshare);
        v.setTextSize(10);
        v.setGravity(Gravity.CENTER);
        v.setTextColor(0xFFFFFFFF);

        return v;
    }

    private CloseButton closeButton() {
        RelativeLayout.LayoutParams params = new RelativeLayout.LayoutParams(
                  ViewGroup.LayoutParams.MATCH_PARENT,
                  ViewGroup.LayoutParams.MATCH_PARENT);


        CloseButton v = new CloseButton(context);
        v.setLayoutParams(params);
        drawable = new GradientDrawable();
        drawable.setAlpha(0);
        v.setBackground(drawable);

        return v;
    }

    private ListView dateListView() {
        RelativeLayout.LayoutParams params = new RelativeLayout.LayoutParams(
                  ViewGroup.LayoutParams.MATCH_PARENT,
                  ViewGroup.LayoutParams.MATCH_PARENT);

        ListView v = new ListView(context);
        v.setLayoutParams(params);
        drawable = new GradientDrawable();
        drawable.setColor(0XFFD6665C);
        drawable.setAlpha(120);
        v.setBackground(drawable);

        return v;
    }

    private void imageView() {
        scratchCardLogo.setBackgroundResource(R.drawable.scratchcardlogo);
    }

    private Button yellowButton() {
        RelativeLayout.LayoutParams params = new RelativeLayout.LayoutParams(
                  ViewGroup.LayoutParams.MATCH_PARENT,
                  ViewGroup.LayoutParams.WRAP_CONTENT);

        GradientDrawable drawable = new GradientDrawable();
        drawable.setShape(GradientDrawable.RECTANGLE);
        drawable.setCornerRadius(50);
        drawable.setColor(0xFFEDC240);

        Button v = new Button(context);
        v.setLayoutParams(params);
        v.setGravity(Gravity.CENTER);
        v.setText(R.string.scratchcard_share);
        v.setTextSize(18);
        v.setTextColor(0xFFCC2C24);
        v.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                if (scratchCard.getTouchState()) {
                    return;
                } else {
                    shareClick.onClick(view);
                }
            }
        });
        v.setBackground(drawable);
        return v;
    }

    private View.OnClickListener shareClick;

    public void setShareClick(View.OnClickListener click) {
        shareClick = click;
    }

    private ScratchCard scratchCard() {
        RelativeLayout.LayoutParams params = new RelativeLayout.LayoutParams(
                  ViewGroup.LayoutParams.MATCH_PARENT,
                  ViewGroup.LayoutParams.MATCH_PARENT);

        ScratchCard v = new ScratchCard(context);
        v.setLayoutParams(params);
        v.setBackgroundColor(Color.TRANSPARENT);
        v.setGravity(Gravity.CENTER);
        v.setTextSize(25);
        v.setTextColor(0xFF6B6B6B);

        return v;
    }

    private void percentRelativeLayout() {
        drawable = new GradientDrawable();
        drawable.setShape(GradientDrawable.RECTANGLE);
        drawable.setCornerRadius(30);
        drawable.setColor(0xFFACA8B3);
        drawable.setAlpha(120);
        percentRelativeLayout.setBackground(drawable);
    }
}
