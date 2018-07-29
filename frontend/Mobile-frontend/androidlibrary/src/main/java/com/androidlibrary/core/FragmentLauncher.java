package com.androidlibrary.core;

import android.app.Activity;
import android.app.Fragment;
import android.app.FragmentManager;
import android.app.FragmentTransaction;
import android.content.Context;
import android.os.Bundle;

import java.lang.reflect.Constructor;
import java.lang.reflect.InvocationTargetException;

/**
 * Created by chriske on 2015/12/22.
 */
public class FragmentLauncher {

    public static void change(Context context, int containerId, Bundle args, Class<? extends Fragment> fragmentClass) {
        change(context, containerId, args, fragmentClass.getName());
    }

    public static void change(Context context, int containerId, Bundle args, String fragmentClassName) {
        Fragment fragment = dealWithFragment(fragmentClassName);
        if (fragment == null) {
            return;
        }
        fragment.setArguments(args);
        Activity activity = (Activity) context;

        FragmentManager manager = activity.getFragmentManager();
        FragmentTransaction transaction = manager.beginTransaction();
        transaction.setTransition(FragmentTransaction.TRANSIT_FRAGMENT_OPEN);
        transaction.replace(containerId, fragment, fragment.getClass().getName());
        transaction.commitAllowingStateLoss();
    }

    public static void changeToBack(Context context, int containerId, Bundle args, String fragmentClassName) {
        Fragment fragment = dealWithFragment(fragmentClassName);
        if (fragment == null) {
            return;
        }

        fragment.setArguments(args);
        Activity activity = (Activity) context;

        FragmentManager manager = activity.getFragmentManager();
        FragmentTransaction transaction = manager.beginTransaction();
        transaction.setTransition(FragmentTransaction.TRANSIT_FRAGMENT_OPEN);
        transaction.replace(containerId, fragment, fragment.getClass().getName());
        transaction.addToBackStack(null);
        transaction.commitAllowingStateLoss();
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
