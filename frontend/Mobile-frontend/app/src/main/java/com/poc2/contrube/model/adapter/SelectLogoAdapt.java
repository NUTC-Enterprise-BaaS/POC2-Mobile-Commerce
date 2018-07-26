package com.poc2.contrube.model.adapter;

import android.app.Activity;
import android.content.Context;
import android.database.Cursor;
import android.net.Uri;
import android.provider.MediaStore;
import android.support.v7.widget.RecyclerView;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.Toast;

import com.bumptech.glide.Glide;
import com.poc2.R;

import java.util.ArrayList;
import java.util.Collections;

/**
 * Created by Gary on 2016/11/2.
 */

public class SelectLogoAdapt extends RecyclerView.Adapter<SelectLogoAdapt.MyHolder> {
    private LayoutInflater layoutInflater;
    private Context context;
    private ArrayList<String> imagesPath;
    private ArrayList<Uri> mItemSelect;
    private ArrayList<Uri> imagesUri;

    public SelectLogoAdapt(Context context) {
        super();
        this.context = context;
        layoutInflater = LayoutInflater.from(context);
        imagesPath = getAllShownImagesPath((Activity) context);
        imagesUri = getAllShownImagesUri((Activity) context);
        mItemSelect = new ArrayList<>();
        for (int i = 0; i < imagesPath.size(); i++) {
            Log.e("imagesPath", imagesPath.get(i).toString());
        }
        for (int i = 0; i < imagesUri.size(); i++) {
            Log.e("imagesUri", imagesUri.get(i).toString());
        }
    }

    @Override
    public MyHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        View view = layoutInflater.inflate(R.layout.activity_select_pic_item, parent, false);
        MyHolder myHolder = new MyHolder(view);
        return myHolder;
    }

    @Override
    public void onBindViewHolder(MyHolder holder, int position) {
        Glide.with(context).load(imagesPath.get(position))
                .into(holder.pic);

        //用圖片的Uri當Key
        Uri imageKey = imagesUri.get(position);
        holder.pic.setOnClickListener(itemListener(imageKey, position));
        if (mItemSelect.contains(imageKey)) {
            holder.check.setVisibility(View.VISIBLE);
        } else {
            holder.check.setVisibility(View.INVISIBLE);
        }
    }

    private View.OnClickListener itemListener(final Uri imageKey, final int position) {
        return new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                if (mItemSelect.contains(imageKey)) {
                    mItemSelect.remove(imageKey);
                    Log.e("mItemSelect", mItemSelect.toString() + "");
                } else {
                    if (mItemSelect.size() < 1) {
                        mItemSelect.add(imageKey);
                        Log.e("size", mItemSelect.toString() + "");
                    } else {
                        Toast.makeText(context, "最多選一張", Toast.LENGTH_LONG).show();
                    }
                }
                notifyItemChanged(position);
            }
        };
    }

    @Override
    public int getItemCount() {
        return imagesPath.size();
    }

    public class MyHolder extends RecyclerView.ViewHolder {
        public ImageView pic;
        public ImageView check;

        public MyHolder(View itemView) {
            super(itemView);
            pic = (ImageView) itemView.findViewById(R.id.pic);
            check = (ImageView) itemView.findViewById(R.id.check);
        }
    }

    private ArrayList<String> getAllShownImagesPath(Activity activity) {
        Uri imageExternalContentUri = MediaStore.Images.Media.EXTERNAL_CONTENT_URI;
        ArrayList<String> listOfAllImages = new ArrayList<String>();
        Uri mImageUri;
        String absolutePathOfImage;
        String imageUriText;

//        MediaStore.Images.Media.DATA 為真實路徑
        String[] projection = {MediaStore.Images.Media.DATA,
                MediaStore.Images.ImageColumns._ID,
                MediaStore.Images.Media.BUCKET_DISPLAY_NAME};

        Cursor cursor = activity.getContentResolver().query(
                imageExternalContentUri,
                projection,
                null,
                null,
                null);

        int column_index_data = cursor.getColumnIndex(MediaStore.MediaColumns.DATA);
        int column_index_folder_name = cursor
                .getColumnIndexOrThrow(MediaStore.Images.Media.BUCKET_DISPLAY_NAME);
        int column_index_id = cursor.getColumnIndexOrThrow(MediaStore.MediaColumns._ID);
        while (cursor.moveToNext()) {
            absolutePathOfImage = cursor.getString(column_index_data);
            imageUriText = cursor.getString(column_index_id);
            mImageUri = Uri.withAppendedPath(imageExternalContentUri, imageUriText);
            listOfAllImages.add(absolutePathOfImage);
        }
        Collections.reverse(listOfAllImages);
        return listOfAllImages;
    }

    private ArrayList<Uri> getAllShownImagesUri(Activity activity) {
        Uri imageExternalContentUri = MediaStore.Images.Media.EXTERNAL_CONTENT_URI;
        ArrayList<String> listOfAllImages = new ArrayList<String>();
        ArrayList<Uri> listOfAllImagesUri = new ArrayList<Uri>();
        Uri mImageUri;
        String absolutePathOfImage;
        String imageUriText;

//        MediaStore.Images.Media.DATA 為真實路徑
        String[] projection = {MediaStore.Images.Media.DATA,
                MediaStore.Images.ImageColumns._ID,
                MediaStore.Images.Media.BUCKET_DISPLAY_NAME};

        Cursor cursor = activity.getContentResolver().query(
                imageExternalContentUri,
                projection,
                null,
                null,
                null);

        int column_index_data = cursor.getColumnIndex(MediaStore.MediaColumns.DATA);
        int column_index_folder_name = cursor
                .getColumnIndexOrThrow(MediaStore.Images.Media.BUCKET_DISPLAY_NAME);
        int column_index_id = cursor.getColumnIndexOrThrow(MediaStore.MediaColumns._ID);

        while (cursor.moveToNext()) {
            //檔案絕對路徑
//            absolutePathOfImage = cursor.getString(column_index_data);
//            listOfAllImages.add(absolutePathOfImage);

            //檔案Uri
            imageUriText = cursor.getString(column_index_id);
            mImageUri = Uri.withAppendedPath(imageExternalContentUri, imageUriText);
            listOfAllImagesUri.add(mImageUri);
        }
        Collections.reverse(listOfAllImagesUri);
        return listOfAllImagesUri;
    }

    public ArrayList<Uri> getSelectUrl() {
        return mItemSelect;
    }
}




