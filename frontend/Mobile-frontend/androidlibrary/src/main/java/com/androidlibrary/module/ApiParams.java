package com.androidlibrary.module;


import com.androidlibrary.module.backend.params.ParamsInjection;

/**
 * Created by chriske on 2016/3/13.
 */
public class ApiParams {
    public ApiParams() {
    }

    public ApiParams(ParamsInjection... messengerGroup) {
        for (ParamsInjection messenger : messengerGroup) {
            messenger.inject(this);
        }
    }

    public String domainHost = "127.0.0.1";
    public int domainPort = 8080;
    public boolean isSSL = false;

    public String headerAuthorization = "";
    public String inputVersion = "";
    public String inputEmail = "";
    public String inputPassword = "";
    public String inputNewPassword = "";
    public String inputPasswordAgain = "";
    public String inputVerify = "";

    public String inputRegisterName = "";
    public String inputRegisterCountry = "";
    public String inputRegisterPhone = "";
    public String inputRegisterEmail = "";
    public String inputRegisterPassword = "";
    public String inputRegisterBirthday = "";
    public String inputRegisterQrCode = "";
    public String timestampStart = "";
    public String timestampEnd = "";
    public String inputStart = "";
    public String inputEnd = "";
    public String inputId = "";
    public String inputPoint = "";
    public String inputPhone = "";
    public String inputStoreId = "";

    public String inputCategory = "";
    public String inputCompany = "";
    public String inputContact = "";
    public String inputTitle = "";
    public String inputContent = "";

    public String inputTransactionId = "";
    public String inputPhoneNumber = "";
    public String inputBonus = "";

    public String inputAesEncode = "";

    public String inputKeyword = "";
    public String inputArea = "";
    public String inputLongitude = "";
    public String inputType = "";
    public String inputLatitude = "";
    public String inputKm = "";
    public String inputDeviceToken = "";

    public String inputStoreName = "";
    public String inputStoreType = "";
    public String inputCategoryEmployment = "";
    public String inputStoreAddress = "";
    public String inputStoreUrl = "";
    public String inputContactPerson = "";
    public String inputContactPersonSex = "";

    public String inputInstanceId = "";
    public String inputdevice = "";
    public String luckyToken = "";
    public String inputFriendPhone = "";
    public String inputFriendEmail = "";
    public String state = "";
    public String check_id = "";
    public String receive_email = "";
    public String send_email = "";
    public String inputRecommendQrCode = "";
    public String inputLogoBase64 = "";
    public String inputContentBase64_1 = "";
    public String inputContentBase64_2 = "";
    public String inputContentBase64_3 = "";
    public String inputIdNumber = "";

    public String inputLdapToken = "";
    public String inputLdapPoint = "";
    public String storeName = "";
}
