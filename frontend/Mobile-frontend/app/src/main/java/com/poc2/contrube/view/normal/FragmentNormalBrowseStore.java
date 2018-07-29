package com.poc2.contrube.view.normal;

import android.app.AlertDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v4.app.Fragment;
import android.support.v7.widget.DefaultItemAnimator;
import android.support.v7.widget.GridLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.Spinner;
import android.widget.Toast;

import com.androidlibrary.component.decoration.StoreDecoration;
import com.androidlibrary.module.backend.data.ApiV1StoreGetData;
import com.androidlibrary.module.backend.data.ApiV1StoreRegionGetData;
import com.androidlibrary.module.scrollchange.ScrollListener;
import com.poc2.R;
import com.poc2.contrube.component.dialog.CallDialog;
import com.poc2.contrube.component.dialog.RangeSearchDialog;
import com.poc2.contrube.controllor.browsestore.BrowseStoreController;
import com.poc2.contrube.core.FragmentLauncher;
import com.poc2.contrube.model.SequenceLoadLogic;
import com.poc2.contrube.model.adapter.BrowseStoreAdapt;
import com.poc2.contrube.model.adapter.BrowseStoreListAdapt;

import java.util.ArrayList;

/**
 * Created by Gary on 2016/11/1.
 */

public class FragmentNormalBrowseStore extends Fragment {
    private EditText searchEdit;
    private Button search;
    private Button rangeSearch;
    private Button listChange;
    private Spinner areaSpinner;
    private Spinner memberSpinner;
    private ImageView favorite;
    private View back;

    private ArrayAdapter<String> memberList;
    private GridLayoutManager manager;
    private BrowseStoreAdapt storeAdapt;
    private BrowseStoreListAdapt storeListAdapt;
    private DefaultItemAnimator animator;
    private StoreDecoration decoration;
    private RecyclerView recyclerView;
    private BrowseStoreController controller;
    public ArrayList<String> regionIdGroup;
    public ArrayList<String> regionNameGroup;
    private ScrollListener scrollListener;
    private SequenceLoadLogic loadLogic;
    private BrowseStoreAdapt.DataStructure data;
    private BrowseStoreListAdapt.DataStructure dataList;
    private String shareUrl;
    private CallDialog callDialog;
    private String shopId;
    private boolean listIsShow;
    private RangeSearchDialog rangeSearchDialog;
    private AlertDialog alertRangeSearchDialog;

    @Nullable
    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        View layout = inflater.inflate(R.layout.fragment_browse_store, container, false);
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
        areaSpinner = (Spinner) getView().findViewById(R.id.activity_browse_store_area_spinner);
        memberSpinner = (Spinner) getView().findViewById(R.id.activity_browse_store_member_spinner);
        recyclerView = (RecyclerView) getView().findViewById(R.id.activity_browse_store_recyclerview);
        searchEdit = (EditText) getView().findViewById(R.id.activity_browse_store_editText);
        search = (Button) getView().findViewById(R.id.activity_browse_store_search_button);
        rangeSearch = (Button) getView().findViewById(R.id.activity_browse_store_range_search_button);
        listChange = (Button) getView().findViewById(R.id.activity_browse_store_list_change_button);
        favorite = (ImageView) getView().findViewById(R.id.activity_browse_store_like_image);
        searchEdit.setSingleLine(true);
    }

    private void init() {
        shareUrl = "";
        shopId = "";
        listIsShow = false;
        regionIdGroup = new ArrayList<>();
        regionNameGroup = new ArrayList<>();
        loadLogic = new SequenceLoadLogic();
        rangeSearchDialog = new RangeSearchDialog(getActivity());
        alertRangeSearchDialog = rangeSearchDialog.create();
        data = new BrowseStoreAdapt.DataStructure(getActivity());
        dataList = new BrowseStoreListAdapt.DataStructure(getActivity());
        controller = new BrowseStoreController(getActivity());
        callDialog = new CallDialog(getActivity());
        memberList = new ArrayAdapter<String>(getActivity(), R.layout.spinner_item, getActivity().getResources().getStringArray(R.array.general_store_list));
        memberList.setDropDownViewResource(R.layout.spinner_item);
        memberSpinner.setAdapter(memberList);

        manager = new GridLayoutManager(getActivity(), 2);
        storeAdapt = new BrowseStoreAdapt(getActivity());
        storeListAdapt = new BrowseStoreListAdapt(getActivity());
        animator = new DefaultItemAnimator();
        decoration = new StoreDecoration();

        recyclerView.setAdapter(storeAdapt);
        recyclerView.setLayoutManager(manager);
        recyclerView.setItemAnimator(animator);
        recyclerView.addItemDecoration(decoration);
        recyclerView.setHasFixedSize(true);
        controller.setmCallBackEvent(callBackEvent);
        scrollListener = new ScrollListener(manager);
        scrollListener.setDeclineCallBack(declineCallBack);
        search.setOnClickListener(searchClick);
        listChange.setOnClickListener(changeListClick);
        areaSpinner.setOnItemSelectedListener(spinnerClick);
        memberSpinner.setOnItemSelectedListener(spinnerClick);
        storeAdapt.setOnClickListener(clickListener);
        storeAdapt.setOnLongClickListener(longClickListener);
        storeAdapt.setItemCall(callEvent);
        storeAdapt.setMapListener(mapGps);
        storeAdapt.setSaveEvent(saveClick);
        storeListAdapt.setOnClickListener(clickListener);
        storeListAdapt.setMapListener(mapGps);
        storeListAdapt.setSaveEvent(saveClick);
        rangeSearch.setOnClickListener(rangeSearchClick);
        rangeSearchDialog.setCallBackEvent(rangeDialogClick);
        favorite.setOnClickListener(favoriteClick);
        back.setOnClickListener(backClick);
        loadArea();
        sync();
    }

    private View.OnClickListener favoriteClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            ((ActivityNormalAdvertisement) getActivity()).setAdvertisementEnable(true);
            FragmentLauncher.changeToBack(getContext(), R.id.content_container, null, FragmentFavorite.class.getName());
        }
    };

    private RangeSearchDialog.RangeDialogClick rangeDialogClick = new RangeSearchDialog.RangeDialogClick() {
        @Override
        public void onCancelClick() {
            alertRangeSearchDialog.dismiss();
        }

        @Override
        public void onSubmitClick(String range) {
            alertRangeSearchDialog.dismiss();
            controller.setRange(range);
            sync();
        }
    };


    private View.OnClickListener rangeSearchClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            alertRangeSearchDialog.show();
        }
    };

    private View.OnClickListener changeListClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            if (listIsShow) {
                manager.setSpanCount(2);
                recyclerView.setAdapter(storeAdapt);
            } else {
                manager.setSpanCount(1);
                recyclerView.setAdapter(storeListAdapt);
            }
            listIsShow = !listIsShow;
        }
    };

    private AdapterView.OnItemSelectedListener spinnerClick = new AdapterView.OnItemSelectedListener() {
        @Override
        public void onItemSelected(AdapterView<?> parent, View view, int position, long id) {
            sync();
        }

        @Override
        public void onNothingSelected(AdapterView<?> parent) {

        }
    };

    private View.OnClickListener searchClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            sync();
        }
    };

    private void syncSlip() {
        controller.syncSlipRequest(searchEdit, areaSpinner, memberSpinner);
    }

    private void sync() {
        controller.syncRequest(searchEdit, areaSpinner, memberSpinner);
    }

    private BrowseStoreController.CallBackEvent callBackEvent = new BrowseStoreController.CallBackEvent() {
        @Override
        public void onError() {

        }

        @Override
        public void onSuccess(ApiV1StoreRegionGetData information) {
            if (information.result == 0) {
                regionIdGroup = information.regionIdGroup;
                regionNameGroup = information.regionNameGroup;
                setAreaData();
            }
        }

        @Override
        public void onSuccess(ApiV1StoreGetData information) {
            if (information.result == 0) {
                ArrayList<String> shopIdGroup = new ArrayList<>();
                ArrayList<String> shopNameGroup = new ArrayList<>();
                ArrayList<String> shopPhoneGroup = new ArrayList<>();
                ArrayList<String> shopAddressGroup = new ArrayList<>();
                ArrayList<String> shopLikeGroup = new ArrayList<>();
                ArrayList<String> shopKmGroup = new ArrayList<>();
                ArrayList<String> urlList = new ArrayList<>();
                ArrayList<String> shopPhotoList = new ArrayList<>();
                for (int i = 0; i < information.shopNameGroup.size(); i++) {
                    if (information.shopNameGroup.get(i).equals("美的世界美容國際機構") || information.shopNameGroup.get(i).equals("樂活鮮瓶（中工店）")
                            || information.shopNameGroup.get(i).equals("紫湄美顏坊") || information.shopNameGroup.get(i).equals("國寶奇木藝品")
                            || information.shopNameGroup.get(i).equals("回歸自然生機飲食館")) {
                        Log.e("onSuccess", "" +information.shopNameGroup.get(i) );
                    }else {
                        shopIdGroup.add(information.shopIdGroup.get(i));
                        shopNameGroup.add(information.shopNameGroup.get(i));
                        shopPhotoList.add("http://211.20.7.116:8000/" + information.shopPhotoGroup.get(i).split("http://ginkerapp.com/")[1]);
                        shopPhoneGroup.add(information.shopPhoneGroup.get(i));
                        shopAddressGroup.add(information.shopAddressGroup.get(i));
                        urlList.add("http://211.20.7.116:8000/" + information.shopUrlGroup.get(i).split("http://ginkerapp.com/")[1]);
                        shopLikeGroup.add(information.shopLikeGroup.get(i));
                        shopKmGroup.add(information.shopKmGroup.get(i));
                    }

                }

                update(shopIdGroup, urlList, shopPhotoList, shopNameGroup, shopPhoneGroup, shopAddressGroup, shopLikeGroup, shopKmGroup, information);
                if (information.meaageGroup.get(0).toString().equals("No such data input error")) {
                    String content = getString(R.string.browse_store_range_fail);
                    Toast.makeText(getActivity(), content, Toast.LENGTH_LONG).show();
                    update(shopIdGroup, urlList, shopPhotoList, shopNameGroup, shopPhoneGroup, shopAddressGroup, shopLikeGroup, shopKmGroup, information);
                }
            }
        }

        @Override
        public void onSaveSuccess() {
            sync();
        }
    };

    private void setAreaData() {
        if (regionNameGroup != null) {
            String[] locationRawData = new String[regionIdGroup.size()];
            locationRawData[0] = "全地區";
            for (int i = 1; i < regionNameGroup.size(); i++) {
                locationRawData[i] = regionNameGroup.get(i).toString();
            }
            if (getActivity() != null) {
                ArrayAdapter LoRawAdapter = new ArrayAdapter<>(getActivity(), R.layout.spinner_item, locationRawData);
                LoRawAdapter.setDropDownViewResource(R.layout.spinner_item);
                areaSpinner.setAdapter(LoRawAdapter);
            }
        }
    }

    private void loadArea() {
        controller.loadAreaRequest();
    }

    private ScrollListener.DeclineCallBack declineCallBack = new ScrollListener.DeclineCallBack() {
        @Override
        public void decline(RecyclerView recyclerView) {
            if (!(data.sum >= loadLogic.getEnd())) {
                return;
            }
            syncSlip();
        }
    };

    public void update(ArrayList<String> shopIdGroup, ArrayList<String> urlList, ArrayList<String> shopPhotoList, ArrayList<String> shopNameGroup
            , ArrayList<String> shopPhoneGroup, ArrayList<String> shopAddressGroup, ArrayList<String> shopLikeGroup, ArrayList<String> shopKmGroup,
                       ApiV1StoreGetData information) {
        data.shopIdGroup = shopIdGroup;
        data.shopNameGroup = shopNameGroup;
        data.shopPhotoGroup = shopPhotoList;
        data.shopPhoneGroup = shopPhoneGroup;
        data.shopAddressGroup = shopAddressGroup;
        data.shopUrlGroup = urlList;
        data.shopLikeGroup = shopLikeGroup;
        data.shopKmGroup = shopKmGroup;
        data.sum = information.sum;
        storeAdapt.setData(data);

        dataList.shopIdGroup = shopIdGroup;
        dataList.shopNameGroup = shopNameGroup;
        dataList.shopPhotoGroup = shopPhotoList;
        dataList.shopPhoneGroup = shopPhoneGroup;
        dataList.shopAddressGroup = shopAddressGroup;
        dataList.shopUrlGroup = urlList;
        dataList.shopLikeGroup = shopLikeGroup;
        dataList.shopKmGroup = shopKmGroup;
        storeListAdapt.setData(dataList);
    }

    /**
     * 短按事件
     */
    private View.OnClickListener clickListener = new View.OnClickListener() {
        @Override
        public void onClick(View v) {
            int tag = Integer.valueOf(v.getTag().toString());
            shareUrl = data.shopUrlGroup.get(tag);
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

    /**
     * 長按事件
     */
    private View.OnLongClickListener longClickListener = new View.OnLongClickListener() {
        @Override
        public boolean onLongClick(View v) {
            int position = Integer.valueOf(v.getTag().toString());
            shareUrl = data.shopUrlGroup.get(position);
            shareTo("Title", shareUrl, "");
            return false;
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
    private View.OnClickListener callEvent = new View.OnClickListener() {
        @Override
        public void onClick(final View v) {
            int tag = Integer.valueOf(v.getTag().toString());
            callDialog.setPhoneNumer(data.shopPhoneGroup.get(tag));
            callDialog.setComfirmEvent(new DialogInterface.OnClickListener() {
                @Override
                public void onClick(DialogInterface dialog, int which) {
                    int position = Integer.valueOf(v.getTag().toString());
                    String number = data.shopPhoneGroup.get(position);
                    Uri uri = Uri.parse("tel:" + number);
                    Intent intent = new Intent(Intent.ACTION_DIAL, uri);
                    getActivity().startActivity(intent);
                }
            }).show();
        }
    };

    /**
     * 導航事件
     */
    private View.OnClickListener mapGps = new View.OnClickListener() {
        @Override
        public void onClick(View v) {
            int position = Integer.valueOf(v.getTag().toString());
            //輸入目的地，會跳出手機內建地圖
            Uri gmmIntentUri = Uri.parse("geo:0,0?q=" + data.shopAddressGroup.get(position).toString());
            Intent mapIntent = new Intent(Intent.ACTION_VIEW, gmmIntentUri);
            mapIntent.setPackage("com.google.android.apps.maps");
            if (mapIntent.resolveActivity(getActivity().getPackageManager()) != null) {
                startActivity(mapIntent);
            }
        }
    };

    /**
     * 蒐藏事件
     */
    private View.OnClickListener saveClick = new View.OnClickListener() {
        @Override
        public void onClick(View v) {
            int tag = Integer.valueOf(v.getTag().toString());
            shopId = data.shopIdGroup.get(tag);
            boolean isSave = false;
            if (data.shopLikeGroup.get(tag).equals("1")) {
                isSave = true;
            }
            if (!isSave) {
                controller.saveRequest(shopId);
            } else {
                controller.deleteRequest(shopId);
            }
        }
    };
    private View.OnClickListener backClick = new View.OnClickListener() {
        @Override
        public void onClick(View view) {
            getActivity().onBackPressed();
        }
    };
}
