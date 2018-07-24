package com.herbhousesgobuyother.contrube.core;

import android.content.Intent;
import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.view.Gravity;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.FrameLayout;
import android.widget.LinearLayout;
import android.widget.ScrollView;
import android.widget.TextView;

/**
 * Created by chriske on 2016/1/20.
 */
public class SeparateDeveloperActivity extends AppCompatActivity {
    private ScrollView scrollView;
    private LinearLayout container;
    private TextView title;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        scrollView = scrollView();
        container = container();
        title = title();

        scrollView.addView(container);
        container.addView(title);

        setContentView(scrollView);
    }

    private ScrollView scrollView() {
        FrameLayout.LayoutParams params = new FrameLayout.LayoutParams(
                ViewGroup.LayoutParams.MATCH_PARENT,
                ViewGroup.LayoutParams.MATCH_PARENT);

        ScrollView v = new ScrollView(this);
        v.setLayoutParams(params);

        return v;
    }

    private LinearLayout container() {
        FrameLayout.LayoutParams params = new FrameLayout.LayoutParams(
                ViewGroup.LayoutParams.MATCH_PARENT,
                ViewGroup.LayoutParams.WRAP_CONTENT);

        LinearLayout v = new LinearLayout(this);
        v.setLayoutParams(params);
        v.setOrientation(LinearLayout.VERTICAL);

        return v;
    }

    private TextView title() {
        FrameLayout.LayoutParams params = new FrameLayout.LayoutParams(
                ViewGroup.LayoutParams.MATCH_PARENT,
                ViewGroup.LayoutParams.WRAP_CONTENT);

        TextView v = new TextView(this);
        v.setLayoutParams(params);
        v.setGravity(Gravity.CENTER_HORIZONTAL);
        v.setText("Temporary activity just for team development.");

        return v;
    }

    protected void addActivityButton(final Class activityClass, Bundle args) {
        if (args == null) {
            args = new Bundle();
        }

        final Bundle sendArgs = args;
        Button v = new Button(this);
        v.setText(activityClass.getName());
        v.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(SeparateDeveloperActivity.this, activityClass);
                intent.putExtras(sendArgs);
                startActivity(intent);
            }
        });
        container.addView(v);
    }

    protected void addFragmentButton(final Class fragmentClass, Bundle args) {
        if (args == null) {
            args = new Bundle();
        }
        args.putString(StubFragmentActivity.FRAGMENT_KEY, fragmentClass.getName());

        final Bundle sendArgs = args;
        Button v = new Button(this);
        v.setText(fragmentClass.getName());
        v.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(SeparateDeveloperActivity.this, StubFragmentActivity.class);
                intent.putExtras(sendArgs);
                startActivity(intent);
            }
        });
        container.addView(v);
    }
}
