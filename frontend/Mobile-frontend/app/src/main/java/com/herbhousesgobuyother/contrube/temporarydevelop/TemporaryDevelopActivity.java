package com.herbhousesgobuyother.contrube.temporarydevelop;

import android.os.Bundle;

import com.herbhousesgobuyother.contrube.core.SeparateDeveloperActivity;
import com.herbhousesgobuyother.contrube.view.guest.ActivityLogin;
import com.herbhousesgobuyother.contrube.view.normal.ActivityNormalRegisterRecommend;
import com.herbhousesgobuyother.contrube.view.normal.FragmentFavorite;
import com.herbhousesgobuyother.contrube.view.normal.FragmentNormalBasicInformation;


/**
 * Created by Ameng on 2015/12/14.
 */

public class TemporaryDevelopActivity extends SeparateDeveloperActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        // Activity
        addActivityButton(ActivityLogin.class, null);
        addActivityButton(ActivityNormalRegisterRecommend.class, null);
//        addActivityButton(ActivityRegister.class, null);
//        addActivityButton(ActivityQrcode.class, null);
//        addActivityButton(ActivityNormalAdvertisement.class, null);
//        addActivityButton(ActivitySpecialAdvertisement.class, null);
//        addActivityButton(ActivityPremiumAdvertisement.class, null);
//        addActivityButton(ActivityForgetPassword.class, null);
//        addActivityButton(ActivitySpecialRegister.class, null);
//        addActivityButton(ActivityPremiumRegister.class, null);

        // Fragment
        addFragmentButton(FragmentNormalBasicInformation.class, null);
        addFragmentButton(FragmentFavorite.class, null);
    }
}
