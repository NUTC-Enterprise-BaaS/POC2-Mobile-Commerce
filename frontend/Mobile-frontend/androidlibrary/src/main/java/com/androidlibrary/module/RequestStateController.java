package com.androidlibrary.module;

import android.support.annotation.NonNull;

/**
 * Created by chriske on 2016/6/2.
 */
public class RequestStateController {
    private static final int STATE_END = -1;
    private static final int STATE_ONCE = -2;
    private static final int STATE_FORCE = -3;
    private int id;

    // 增加狀態編號，如遇上結束旗標則不繼續增加。
    public void increaseId() {
        if (id == STATE_END) {
            return;
        }

        // 規定旗標在正數範圍增長，負數範圍用來定義特殊狀態。
        id += 1;
        if (id < 0) {
            id = 0;
        }
    }

    public StateBundle get() {
        return StateBundle.build(id);
    }

    public StateBundle once() {
        return StateBundle.build(STATE_ONCE);
    }
    public StateBundle force() {
        return StateBundle.build(STATE_FORCE);
    }

    // 設定結束旗標，
    public void end() {
        id = STATE_END;
    }

    // 根據狀態執行不同動作。
    public void run(StateBundle bundle) {
        if (id == STATE_END) {
            endEvent.run(bundle);
        } else if (bundle.getId() == STATE_ONCE) {
            onceEvent.run(bundle);
        } else if (bundle.getId() == STATE_FORCE) {
            forceEvent.run(bundle);
        } else if (bundle.getId() == id) {
            sameEvent.run(bundle);
        } else {
            differentEvent.run(bundle);
        }
    }

    // 設定狀態相同時的 Callback 。
    public void setOnSameStateListener(@NonNull OnStateListener event) {
        sameEvent = event;
    }

    // 設定狀態不同時的 Callback 。
    public void setOnDifferentStateListener(@NonNull OnStateListener event) {
        differentEvent = event;
    }

    // 設定狀態被設定為結束時的 Callback 。
    public void setOnEndStateListener(@NonNull OnStateListener event) {
        endEvent = event;
    }

    // 設定只跑一次的狀態時的 Callback 。
    public void setOnOnceStateListener(@NonNull OnStateListener event) {
        onceEvent = event;
    }

    // 設定強制狀態時的 Callback 。
    public void setOnForceStateListener(@NonNull OnStateListener event) {
        forceEvent = event;
    }

    // 預設無動作的 Callback，避免 null point exception。
    private OnStateListener sameEvent = new OnStateListener() {
        @Override
        public void run(StateBundle bundle) {
        }
    };

    private OnStateListener differentEvent = new OnStateListener() {
        @Override
        public void run(StateBundle bundle) {
        }
    };

    private OnStateListener endEvent = new OnStateListener() {
        @Override
        public void run(StateBundle bundle) {
        }
    };

    private OnStateListener onceEvent = new OnStateListener() {
        @Override
        public void run(StateBundle bundle) {
        }
    };

    private OnStateListener forceEvent = new OnStateListener() {
        @Override
        public void run(StateBundle bundle) {
        }
    };

    public static interface OnStateListener {
        void run(StateBundle bundle);
    }
}
