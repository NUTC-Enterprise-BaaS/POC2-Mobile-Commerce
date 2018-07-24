package com.androidlibrary.module;

/**
 * Created by chriske on 2016/3/18.
 */
public class SequenceLoadLogic {
    public static final int INDEX_START = 0;
    public static final int INDEX_INCREMENT = 15;
    public int current;

    public SequenceLoadLogic() {
        current = INDEX_START - INDEX_INCREMENT;
    }

    public int getStart() {
        return current;
    }

    public int getEnd() {
        return current + INDEX_INCREMENT;
    }

    public void next() {
        current += INDEX_INCREMENT;
    }
}
