package com.herbhousesgobuyother.contrube.core;

import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.view.ViewGroup;
import android.widget.FrameLayout;

/**
 * Created by chriske on 2016/1/20.
 */
public class StubFragmentActivity extends AppCompatActivity {
    public static final String FRAGMENT_KEY = StubFragmentActivity.class.getName();
    private FrameLayout layout;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        layout = new FrameLayout(this);
        layout.setId(GenerateViewId.get());
        layout.setLayoutParams(new FrameLayout.LayoutParams(
                ViewGroup.LayoutParams.MATCH_PARENT,
                ViewGroup.LayoutParams.MATCH_PARENT));

        setContentView(layout);

        Bundle args = getIntent().getExtras();
        String fragmentClassName = args.getString(FRAGMENT_KEY);
        FragmentLauncher.replace(this, layout.getId(), args, fragmentClassName);
    }
}
