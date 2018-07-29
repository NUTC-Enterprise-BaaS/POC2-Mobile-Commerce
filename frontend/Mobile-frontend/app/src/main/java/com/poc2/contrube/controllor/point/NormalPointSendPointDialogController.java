package com.poc2.contrube.controllor.point;

/**
 * Created by 依杰 on 2016/11/16.
 */

public class NormalPointSendPointDialogController {
    private String point;
    private String phone;
    private String email;

    public static NormalPointSendPointDialogController sendPointDialogController = null;

    public static synchronized NormalPointSendPointDialogController getInstance() {
        if (sendPointDialogController == null) {
            sendPointDialogController = new NormalPointSendPointDialogController();
        }
        return sendPointDialogController;
    }

    private NormalPointSendPointDialogController() {
    }

    public void setPoint(String point) {
        this.point = point;
    }

    public void setPhone(String phone) {
        this.phone = phone;
    }

    public void setEmail(String email) {
        this.email = email;
    }

    public String getPoint() {
        return point;
    }

    public String getPhone() {
        return phone;
    }

    public String getEmail() {
        return email;
    }

}
