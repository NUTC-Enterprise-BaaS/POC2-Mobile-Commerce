package com.poc2.contrube.view.special;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;
import android.support.v7.widget.DefaultItemAnimator;
import android.support.v7.widget.GridLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;
import android.widget.Toast;

import com.poc2.R;
import com.poc2.contrube.model.adapter.SelectLogoAdapt;

import java.util.ArrayList;

public class ActivitySpecialSelectLogo extends Activity {
    private Context context;

    private Button submit;
    private TextView title;
    private RecyclerView recyclerView;
    private DefaultItemAnimator animator;
    private GridLayoutManager manager;
    private SelectLogoAdapt selectPicAdapt;
    private ArrayList<Uri> mItemSelect;

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_select_pic);
        findView();
        init();
    }

    private void findView() {
        recyclerView = (RecyclerView) findViewById(R.id.recyclerview);
        submit = (Button) findViewById(R.id.submit);
        title = (TextView) findViewById(R.id.title);
    }

    private void init() {
        context = this;
        mItemSelect = new ArrayList<>();
        manager = new GridLayoutManager(context, 2);
        selectPicAdapt = new SelectLogoAdapt(context);
        animator = new DefaultItemAnimator();
        recyclerView.setAdapter(selectPicAdapt);
        recyclerView.setLayoutManager(manager);
        recyclerView.setItemAnimator(animator);
        recyclerView.setHasFixedSize(true);
        submit.setOnClickListener(okClick);
        title.setText(R.string.activity_select_logo_pic);
    }

    private View.OnClickListener okClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            mItemSelect = selectPicAdapt.getSelectUrl();
            ArrayList<String> select = new ArrayList<>();
            for (int i = 0; i < mItemSelect.size(); i++) {
                select.add(mItemSelect.get(i).toString());
            }
            if (mItemSelect.size() < 1) {
                Toast.makeText(context, "請選滿一張", Toast.LENGTH_LONG).show();
            } else {
                Intent intent = new Intent();
                Bundle bundle = new Bundle();
                bundle.putStringArrayList("uri", select);
                intent.putExtras(bundle);
                setResult(RESULT_OK, intent);
                finish();
            }
        }
    };
}