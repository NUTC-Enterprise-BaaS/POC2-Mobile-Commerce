package com.poc2.contrube.view.normal;

import android.content.DialogInterface;
import android.content.Intent;
import android.graphics.Color;
import android.net.Uri;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.support.v7.widget.DefaultItemAnimator;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Toast;

import com.androidlibrary.component.decotarion.DividerDecoration;
import com.androidlibrary.module.backend.data.ApiV1GeneralShopLikeCancelPostData;
import com.androidlibrary.module.backend.data.ApiV1GeneralStoreSaveGetData;
import com.poc2.R;
import com.poc2.contrube.component.dialog.CallDialog;
import com.poc2.contrube.controllor.favorite.FavoriteController;
import com.poc2.contrube.model.adapter.FavoriteAdapt;

/**
 * Created by cheng on 2016/11/13.
 */
public class FragmentFavorite extends Fragment {
    private FavoriteAdapt adapt;
    private FavoriteAdapt.DataStructure data;
    private FavoriteController controller;
    private String shopId;
    private String shareUrl;
    private CallDialog callDialog;
    private View back;

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        View layout = inflater.inflate(R.layout.fragment_myfavorite, container, false);
        return layout;
    }

    @Override
    public void onViewCreated(View view, Bundle savedInstanceState) {
        super.onViewCreated(view, savedInstanceState);
        findView();
        init();
    }

    private void findView() {
        back = getView().findViewById(R.id.toolbar_back_touch);
    }

    private void init() {
        shopId = "";
        shareUrl = "";
        controller = new FavoriteController(getContext());
        callDialog = new CallDialog(getActivity());

        LinearLayoutManager manager = new LinearLayoutManager(getActivity());
        manager.setOrientation(LinearLayoutManager.VERTICAL);
        adapt = new FavoriteAdapt(getActivity());
        DefaultItemAnimator animator = new DefaultItemAnimator();
        DividerDecoration decoration = new DividerDecoration();
        decoration.setDividerColor(Color.parseColor("#DDDDDD"));
        decoration.setItemMargin(15, 15);

        RecyclerView recyclerView = (RecyclerView) getView().findViewById(R.id.fragment_favorite_recyclerview);
        recyclerView.setAdapter(adapt);
        recyclerView.setLayoutManager(manager);
        recyclerView.setItemAnimator(animator);
        recyclerView.addItemDecoration(decoration);
        recyclerView.setHasFixedSize(true);

        controller.syncRequest(false);
        controller.setmCallBackEvent(callBackEvent);
        adapt.setDeleteEvent(deleteClick);
        adapt.setOnClickListener(itemClick);
        adapt.setItemCall(callClick);
        adapt.setShareEvent(shareClick);
        adapt.setMapListener(mapClick);
        back.setOnClickListener(backClick);

    }

    private View.OnClickListener mapClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            int tag = Integer.valueOf(view.getTag().toString());
            //輸入目的地，會跳出手機內建地圖
            Uri gmmIntentUri = Uri.parse("geo:0,0?q=" + data.addressGroup.get(tag).toString());
            Intent mapIntent = new Intent(Intent.ACTION_VIEW, gmmIntentUri);
            mapIntent.setPackage("com.google.android.apps.maps");
            if (mapIntent.resolveActivity(getActivity().getPackageManager()) != null) {
                startActivity(mapIntent);
            }
        }
    };

    private View.OnClickListener shareClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            int tag = Integer.valueOf(view.getTag().toString());
            shareUrl = data.urlGroup.get(tag);
            if (shareUrl.equals("")) {
                String content = getString(R.string.browse_store_no_url);
                Toast.makeText(getActivity(), content, Toast.LENGTH_LONG).show();
            } else {
                shareTo("Title", shareUrl, "");
            }
        }
    };

    private void shareTo(String subject, String body, String chooserTitle) {
        Intent sharingIntent = new Intent(Intent.ACTION_SEND);
        sharingIntent.setType("text/plain");
        sharingIntent.putExtra(Intent.EXTRA_SUBJECT, subject);
        sharingIntent.putExtra(Intent.EXTRA_TEXT, body);
        startActivity(Intent.createChooser(sharingIntent, chooserTitle));
    }

    /**
     * 打電話事件
     */
    private View.OnClickListener callClick = new View.OnClickListener() {
        @Override
        public void onClick(final View view) {
            int tag = Integer.valueOf(view.getTag().toString());
            callDialog.setPhoneNumer(data.phoneGroup.get(tag));
            callDialog.setComfirmEvent(new DialogInterface.OnClickListener() {
                @Override
                public void onClick(DialogInterface dialog, int which) {
                    int position = Integer.valueOf(view.getTag().toString());
                    String number = data.phoneGroup.get(position);
                    Uri uri = Uri.parse("tel:" + number);
                    Intent intent = new Intent(Intent.ACTION_DIAL, uri);
                    getActivity().startActivity(intent);
                }
            }).show();
        }
    };

    private View.OnClickListener itemClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            int tag = Integer.valueOf(view.getTag().toString());
            shareUrl = data.urlGroup.get(tag);
            if (shareUrl.equals("")) {
                String content = getString(R.string.browse_store_no_url);
                Toast.makeText(getActivity(), content, Toast.LENGTH_LONG).show();
            } else {
                goStoreWeb(shareUrl);
            }
        }
    };

    private void goStoreWeb(String url) {
        Uri uri = Uri.parse(url);
        Intent intent = new Intent(Intent.ACTION_VIEW, uri);
        startActivity(intent);
    }


    private View.OnClickListener deleteClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            int tag = Integer.valueOf(view.getTag().toString());
            shopId = String.valueOf(data.idGroup.get(tag));
            controller.deleteRequest(shopId);
        }
    };

    private FavoriteController.CallBackEvent callBackEvent = new FavoriteController.CallBackEvent() {
        @Override
        public void onError() {

        }

        @Override
        public void onSuccess(ApiV1GeneralStoreSaveGetData information) {
            if (information.result == 0) {
                update(information);
            }
        }

        @Override
        public void onSuccess(ApiV1GeneralShopLikeCancelPostData information) {
            if (information.result == 0) {
                String content = getString(R.string.brose_store_favorite_cancel);
                Toast.makeText(getActivity(), content, Toast.LENGTH_LONG).show();
                controller.syncRequest(false);
            }
        }
    };


    private void update(ApiV1GeneralStoreSaveGetData information) {
        data = new FavoriteAdapt.DataStructure();
        data.idGroup = information.idGroup;
        data.nameGroup = information.nameGroup;
        data.phoneGroup = information.phoneGroup;
        data.addressGroup = information.addressGroup;
        data.distanceGroup = information.distanceGroup;
        data.photoGroup = information.photoGroup;
        data.urlGroup = information.urlGroup;
        data.kmGroup = information.kmGroup;
        adapt.setData(data);
    }
    private View.OnClickListener backClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            getActivity().onBackPressed();
        }
    };
}
