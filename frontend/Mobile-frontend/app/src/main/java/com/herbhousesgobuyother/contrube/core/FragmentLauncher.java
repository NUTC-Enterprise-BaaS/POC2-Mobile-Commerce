package com.herbhousesgobuyother.contrube.core;

import android.content.Context;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentManager;
import android.support.v4.app.FragmentTransaction;
import android.support.v7.app.AppCompatActivity;

import java.lang.reflect.Constructor;
import java.lang.reflect.InvocationTargetException;

/**
 * Created by chriske on 2015/12/22.
 */
public class FragmentLauncher {

    public static void replace(Context context, int containerId, Bundle args, Class<? extends Fragment> fragmentClass) {
        replace(context, containerId, args, fragmentClass.getName());
    }

    public static void replace(Context context, int containerId, Bundle args, Fragment fragment) {
        if (fragment == null)
            return;
        if (args != null)
            fragment.setArguments(args);
        AppCompatActivity activity = (AppCompatActivity) context;

        FragmentManager manager = activity.getSupportFragmentManager();
        FragmentTransaction transaction = manager.beginTransaction();
        transaction.setTransition(FragmentTransaction.TRANSIT_FRAGMENT_OPEN);
        transaction.replace(containerId, fragment, fragment.getClass().getName());
        transaction.commit();
    }

    public static void replace(Context context, int containerId, Bundle args, String fragmentClassName) {
        Fragment fragment = dealWithFragment(fragmentClassName);
        if (fragment == null)
            return;
        if (args != null)
            fragment.setArguments(args);
        fragment.setArguments(args);
        AppCompatActivity activity = (AppCompatActivity) context;

        FragmentManager manager = activity.getSupportFragmentManager();
        FragmentTransaction transaction = manager.beginTransaction();
        transaction.setTransition(FragmentTransaction.TRANSIT_FRAGMENT_OPEN);
        transaction.replace(containerId, fragment, fragment.getClass().getName());
        transaction.commit();
    }

    public static void add(Context context, int containerId, Bundle args, String fragmentClassName) {
        Fragment fragment = dealWithFragment(fragmentClassName);
        if (fragment == null)
            return;
        if (args != null)
            fragment.setArguments(args);
        fragment.setArguments(args);
        AppCompatActivity activity = (AppCompatActivity) context;

        FragmentManager manager = activity.getSupportFragmentManager();

        FragmentTransaction transaction = manager.beginTransaction();
        transaction.setTransition(FragmentTransaction.TRANSIT_FRAGMENT_OPEN);
        transaction.add(containerId, fragment, fragment.getClass().getName());
        transaction.commit();
    }

    public static void add(Context context, int containerId, Bundle args, Fragment fragment) {
        if (fragment == null)
            return;
        if (args != null)
            fragment.setArguments(args);
        AppCompatActivity activity = (AppCompatActivity) context;

        FragmentManager manager = activity.getSupportFragmentManager();

        FragmentTransaction transaction = manager.beginTransaction();
        transaction.setTransition(FragmentTransaction.TRANSIT_FRAGMENT_OPEN);
        transaction.add(containerId, fragment, fragment.getClass().getName());
        transaction.commit();
    }

    public static void changeToBack(Context context, int containerId, Bundle args, Fragment fragment) {
        if (fragment == null)
            return;
        if (args != null)
            fragment.setArguments(args);
        AppCompatActivity activity = (AppCompatActivity) context;

        FragmentManager manager = activity.getSupportFragmentManager();
        FragmentTransaction transaction = manager.beginTransaction();
        transaction.setTransition(FragmentTransaction.TRANSIT_FRAGMENT_OPEN);
        transaction.replace(containerId, fragment, fragment.getClass().getName());
        transaction.addToBackStack(null);
        transaction.commit();
    }

    public static void changeToBack(Context context, int containerId, Bundle args, String fragmentClassName) {
        Fragment fragment = dealWithFragment(fragmentClassName);
        if (fragment == null)
            return;
        if (args != null)
            fragment.setArguments(args);
        fragment.setArguments(args);
        AppCompatActivity activity = (AppCompatActivity) context;

        FragmentManager manager = activity.getSupportFragmentManager();
        FragmentTransaction transaction = manager.beginTransaction();
        transaction.setTransition(FragmentTransaction.TRANSIT_FRAGMENT_OPEN);
        transaction.replace(containerId, fragment, fragment.getClass().getName());
        transaction.addToBackStack(null);
        transaction.commit();
    }

    private static Fragment dealWithFragment(String fragmentClassName) {
        Fragment fragment = null;
        try {
            fragment = generatorFragment(fragmentClassName);
        } catch (ClassNotFoundException e) {
            e.printStackTrace();
        } catch (NoSuchMethodException e) {
            e.printStackTrace();
        } catch (IllegalAccessException e) {
            e.printStackTrace();
        } catch (InvocationTargetException e) {
            e.printStackTrace();
        } catch (InstantiationException e) {
            e.printStackTrace();
        }
        return fragment;
    }

    private static Fragment generatorFragment(String fragmentClassname)
            throws ClassNotFoundException, NoSuchMethodException,
            IllegalAccessException, InvocationTargetException, InstantiationException {

        Class<?> clazz = Class.forName(fragmentClassname);
        Constructor<?> constructor = clazz.getConstructor();
        return (Fragment) constructor.newInstance(new Object[]{});
    }
}
