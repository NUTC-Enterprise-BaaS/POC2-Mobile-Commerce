package com.androidlibrary.module.backend.params;

/**
 * Created by chriske on 2016/3/14.
 */
public class ParamsConst {
    public static class Type {
        private Type() {
        }

        public static final Type STRING = new Type();
        public static final Type FLOAT = new Type();
        public static final Type DOUBLE = new Type();
        public static final Type INT = new Type();
        public static final Type LONG = new Type();
    }

    public static class Key {
        public static final String VERSION = "version";
        public static final String NAME = "name";
        public static final String COUNTRY = "country";
        public static final String PHONE = "phone";
        public static final String BIRTHDAY = "birthday";
        public static final String ACCOUNT = "account";
        public static final String PASSWORD = "password";
        public static final String EMAIL = "email";
        public static final String VERIFY_CODE = "verify_code";
        public static final String PASSWORD_CONFIRMATION = "password_confirmation";
        public static final String TIMESTAMP_START = "timestamp_start";
        public static final String TIMESTAMP_END = "timestamp_end";
        public static final String POINT = "point";
        public static final String SEND_PHONE_NUMBER = "phone_number";
        public static final String STORE_ID = "store_id";
        public static final String CATEGORY = "category";
        public static final String COMPANY = "company_name";
        public static final String CONTACT = "contact_member";
        public static final String TITLE = "title";
        public static final String CONTENT = "content";
        public static final String TRANSACTION_ID = "id";
        public static final String PHONE_NUMBER = "phone_number";
        public static final String BONUS = "bonus";
        public static final String SHOP_ID = "shop_id";
        public static final String USER_EMAIL = "user_email";
        public static final String OLD_PASSWORD = "old_password";
        public static final String NEW_PASSWORD = "new_password";
        public static final String DEVICE_TOKEN = "device_token";
        public static final String Store_Name = "store_name";
        public static final String Store_Type = "store_type";
        public static final String Category_Employment = "category_employment";
        public static final String Store_Address = "store_address";
        public static final String Store_Url = "store_url";
        public static final String Contact_Person = "contact_person";
        public static final String Contact_Person_Sex = "contact_person_sex";
        public static final String Id = "id";
        public static final String INSTANCE_ID = "instance_id";
        public static final String DEVICE = "device";
        public static final String LUCKY_TOKEN = "lucky_token";
        public static final String Friend_Email = "friend_email";
        public static final String Friend_Phone = "friend_phone";
        public static final String STATE = "state";
        public static final String CHECK_ID = "check_id";
        public static final String RECEIVE_EMAIL = "receive_email";
        public static final String SEND_EMAIL = "send_email";
        public static final String QR_CODE = "qrcode";
        public static final String LOGO_PIC = "store_logo";
        public static final String LOGO_CONTENT_1 = "store_image[0]";
        public static final String LOGO_CONTENT_2 = "store_image[1]";
        public static final String LOGO_CONTENT_3 = "store_image[2]";
        public static final String CSV_PASSWORD = "csv_password";
        public static final String ID_NUMBER = "id_number";

        public static final String LDAP_TOKEN = "verify_code";
        public static final String STORE_POINT = "point";
        public static final String SEND_POINT_STORE_NAME = "toStor";
        public static final String SEND_POINT_USER_NAME = "toUser";
        public static final String VOUCHER_MESSAGE = "message";
        public static final String VOUCHER_ID = "voucherid";
    }
}
