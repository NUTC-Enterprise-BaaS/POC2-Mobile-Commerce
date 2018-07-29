package com.androidlibrary.module.consts;

import com.androidlibrary.R;

import java.util.HashMap;

/**
 * Created by chriske on 2016/6/10.
 */
public class CountryConst {
    public static final int CHINESE = 0;
    public static final int MALAYSIA = 1;
    public static final int SINGAPURA = 2;
    public static final int KOREA = 3;
    public static final int JAPAN = 4;
    public static final int HONGKONG = 5;
    public static final int MYANMAR = 6;
    public static final int TAIWAN = 7;

    public static int get(int index) {
        return countryMap.get(index);
    }

    private static final HashMap<Integer, Integer> countryMap = new HashMap<>();

    static {
        countryMap.put(CHINESE, R.string.country_chinese);
        countryMap.put(MALAYSIA, R.string.country_malaysia);
        countryMap.put(SINGAPURA, R.string.country_singapura);
        countryMap.put(KOREA, R.string.country_korea);
        countryMap.put(JAPAN, R.string.country_japan);
        countryMap.put(HONGKONG, R.string.country_hongkong);
        countryMap.put(MYANMAR, R.string.country_myanmar);
        countryMap.put(TAIWAN, R.string.country_taiwan);
    }
}
