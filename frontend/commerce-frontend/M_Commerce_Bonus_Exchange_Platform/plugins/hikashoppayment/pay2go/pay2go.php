<?php

defined('_JEXEC') or die('Restricted access');

class plgHikashoppaymentPay2go extends hikashopPaymentPlugin {

    private $hashKey, $hashIV;
    var $accepted_currencies = array(
        'EUR', 'USD', 'GBP', 'HKD', 'SGD', 'JPY', 'CAD', 'AUD', 'CHF', 'DKK',
        'SEK', 'NOK', 'ILS', 'MYR', 'NZD', 'TRY', 'AED', 'MAD', 'QAR', 'SAR',
        'TWD', 'THB', 'CZK', 'HUF', 'SKK', 'EEK', 'BGN', 'PLN', 'ISK', 'INR',
        'LVL', 'KRW', 'ZAR', 'RON', 'HRK', 'LTL', 'JOD', 'OMR', 'RSD', 'TND', 'CNY',
    );
    var $multiple = true;
    var $name = 'pay2go';
    var $pluginConfig = array(
        'Pay2goMPG_test_mode' => array('測試模式(Test_Mode)', 'boolean', '0'),
        'Pay2go_merchant_id' => array('商店代號(Merchant ID)', 'input'),
        'Pay2goMPG_hash_key' => array('加密傳送KEY (Hash Key)', 'input'),
        'Pay2goMPG_hash_iv' => array('加密傳送IV (Hash IV)', 'input'),
        'Pay2goMPG_finish_status_id' => array('完成付款狀態(Order Finish Status)', 'orderstatus'),
        'Pay2goMPG_fail_status_id' => array('匯款失敗狀態(Credit or WebAtm Order Fail Status)', 'orderstatus'),
        'Pay2goMPG_lang_type' => array('支付頁語系(language)', 'list', array('zh-tw' => 'zh-tw', 'en' => 'en')),
        'Pay2goMPG_clientBack_url' => array('結帳頁返回網址(Go Back Url)', 'input')
    );

    function onAfterOrderConfirm(&$order, &$methods, $method_id) {
        // 組成 html
        $this->payment_html = $this->_getPaymentHtml($order);

        // 是否為測試模式
        $this->htmlAction = ($methods[$order->order_payment_id]->payment_params->Pay2goMPG_test_mode) ? 'https://capi.pay2go.com/MPG/mpg_gateway' : 'https://api.pay2go.com/MPG/mpg_gateway';

        // pay2go 需要傳遞參數
        $this->params = $this->_getPay2goParams($order, $methods[$method_id]);

        return $this->showPage('end');
    }

    function onPaymentNotification(&$statuses) {
        $config = JFactory::getConfig();

        // 取時間做檔名 (YYYYMMDD)
        $file_name = date('Ymd', time()) . '.txt';

        // 檔案路徑
        $file = $config['log_path'] . "/" . $file_name;

        $fp = fopen($file, 'a');

        mb_internal_encoding('utf-8');

        $conn = mysql_connect($config['host'], $config['user'], $config['password']);

        mysql_select_db($config['db'], $conn);

        $sql = "SET CHARACTER SET utf8";
        mysql_set_charset('utf8');

        // 讀出後台參數
        $sql = "SELECT payment_params FROM ".$config['dbprefix']."hikashop_payment WHERE payment_type='pay2go'";
        $pay2go_config_params = mysql_fetch_row(mysql_query($sql));

        // 去掉大括號前端字串
        $split_brackets = strstr($pay2go_config_params[0],"{");

        // 根據分號切開參數 (第一維度)
        $split_semicolon = split(";", $split_brackets);

        // 根據冒號切開參數 (第二維度)
        $split_real = array();

        for ($i = 0 ; $i <= count($split_semicolon)-1 ; $i++){
            $split_real[$i] = split(":", $split_semicolon[$i]);
        }

        /**
         * 實際需要參數 : 根據參數的屬性與其屬性值存入一為陣列作對應,且去掉 " 符號
         */
        $pay2go_configs = array();
        for ($i=0 ; $i<count($split_real) ; $i+=2){
            $pay2go_configs[str_replace('"', '', $split_real[$i][2])] = str_replace('"', '', $split_real[$i+1][2]);
        }

        // 交易狀態
        $result = $_POST;
        fwrite($fp, $result['MerchantOrderNo'] . "\n");
        // 取得該筆交易資料
        $sql = "SELECT * FROM ".$config['dbprefix']."hikashop_order WHERE order_number = '" . $result['MerchantOrderNo'] . "'";
        $order_info = mysql_fetch_array(mysql_query($sql), MYSQL_ASSOC);

        // 是否有資料
        if (!empty($order_info)){

            // 1. 檢查交易狀態
            if(in_array($result['Status'], array('SUCCESS', 'CUSTOM'))){

                // 2. 檢查交易總金額
                if($order_info['order_full_price'] == $result['Amt']){

                    /**
                     *  3. 檢查 checkCode
                     */
                    $check = array(
                        "MerchantID" => $result['MerchantID'],
                        "Amt" => $result['Amt'],
                        "MerchantOrderNo" => $result['MerchantOrderNo'],
                        "TradeNo" => $result['TradeNo']
                    );

                    ksort($check);

                    $check_str = http_build_query($check);

                    /**
                     * 是否有設定參數
                     */
                    $checkCode = '';

                    if(!isset($pay2go_configs['Pay2goMPG_hash_key']) || !isset($pay2go_configs['Pay2goMPG_hash_iv'])){
                        $content = $result['MerchantOrderNo'] . ': Hash Setting Errpr';
                        fwrite($fp, $content . "\n");
                        fclose($fp);
                        echo $content;
                        die;
                    } else {
                        $checkCode = 'HashIV=' . $pay2go_configs['Pay2goMPG_hash_iv'] . '&' . $check_str . '&HashKey=' . $pay2go_configs['Pay2goMPG_hash_key'];
                    }

                    $checkCode = strtoupper(hash("sha256", $checkCode));

                    // 如果三次驗證都通過
                    if($checkCode == $result['CheckCode']){

                        if($order_info['order_status'] != $pay2go_configs['Pay2goMPG_finish_status_id']){
                            // 修改訂單狀態
                            $this->modifyOrder($order_info['order_id'], $pay2go_configs['Pay2goMPG_finish_status_id'], true, true);
                        }

                    } else {
                        $content = $result['MerchantOrderNo'] . ': ERROR_3';
                        fwrite($fp, $content . "\n");
                        fclose($fp);
                        echo $content;
                        die;
                    }

                } else {

                    $content = $result['MerchantOrderNo'] . ': ERROR_2';
                    fwrite($fp, $content . "\n");
                    fclose($fp);
                    echo $content;
                    die;

                }

            } else {

                $content = $result['MerchantOrderNo'] . ': ERROR_1';
                echo $content;

                fwrite($fp, $content . "\n");

                // 修改訂單狀態 && 新增歷史紀錄 (Only Credit or WebAtm)
                if(in_array($result['PaymentType'], array('CREDIT', 'WEBATM'))){
                    // 修改訂單狀態
                    $this->modifyOrder($order_info['order_id'], $pay2go_configs['Pay2goMPG_fail_status_id'], true, true);
                }

                fclose($fp);
                die;

            }

        } else {
            $content = $result['MerchantOrderNo'] . ": DataError";
            fwrite($fp, $content . "\n");
            echo $content;
        }

        fclose($fp);
        die;
    }

    /**
     * 組成 html
     *
     * @param obj $order_info 訂購的相關資訊
     */
    protected function _getPaymentHtml($order_info) {

        $result = array(
            'httpDomain' => JURI::root(),
            'order_id' => $order_info->order_id,
            'order_number' => $order_info->order_number,
            'order_payment_method' => $order_info->order_payment_method,
            'customer_email' => $order_info->customer->user_email,
            'shipping_address' => $this->_getAddress(isset($order_info->cart->shipping_address) ? $order_info->cart->shipping_address : $order_info->shipping_address),
            'products' => $this->_getProducts(isset($order_info->cart->products) ? $order_info->cart->products : $order_info->products, $order_info->order_tax_info),
            'shipping_price' => $order_info->order_shipping_price,
            'discount_price' => $order_info->order_discount_price,
            'full_price' => $order_info->order_full_price
        );

        return $result;
    }

    /**
     * 取得地址資訊
     *
     * @param obj $address_info 地址資訊
     */
    protected function _getAddress($address_info) {

        $result = array(
            'title' => $address_info->address_title,
            'name' => $address_info->address_lastname . ' ' . $address_info->address_firstname,
            'street' => $address_info->address_street,
            'street' => $address_info->address_street,
            'post_code' => $address_info->address_post_code,
            'city' => $address_info->address_city,
            'state' => $address_info->address_state,
            'country' => $address_info->address_country,
            'telephone' => $address_info->address_telephone
        );

        return $result;
    }

    /**
     * 取得訂單貨物資訊
     *
     * @param obj   $product_infos 訂單貨物資訊
     * @param array $tax_info      稅率資訊
     */
    protected function _getProducts($product_infos, $tax_info) {

        $result = array(
            'total_price' => 0,
            // 'total_tax' => $tax_info[key($tax_info)]->tax_amount,
            // 'tax_name' => key($tax_info)
        );

        foreach ($product_infos as $key => $obj) {

            $result['orders'][$key] = array();
            $result['orders'][$key]['product_name'] = $obj->order_product_name;
            $result['orders'][$key]['product_quantity'] = $obj->order_product_quantity;
            $result['orders'][$key]['product_price'] = $obj->order_product_price;
            $result['orders'][$key]['product_tax'] = $obj->order_product_tax;

            // 計算總額
            $result['total_price'] += $obj->order_product_quantity * $obj->order_product_price + $obj->order_product_quantity * $obj->order_product_tax;
        }

        return $result;
    }

    /**
     * pay2go 需要傳遞參數
     *
     * @param obj $order_info   訂購的相關資訊
     * @param obj $pay2goConfig pay2go的相關資訊
     *
     * @return array
     */
    protected function _getPay2goParams($order_info, $pay2goConfig) {

        $this->hashKey = trim($pay2goConfig->payment_params->Pay2goMPG_hash_key);
        $this->hashIV = trim($pay2goConfig->payment_params->Pay2goMPG_hash_iv);

        $result = array(
            'MerchantID' => trim($pay2goConfig->payment_params->Pay2go_merchant_id),
            'RespondType' => 'String',
            'TimeStamp' => time(),
            'Version' => '1.1',
            'MerchantOrderNo' => $order_info->order_number,
            'Amt' => intval($order_info->order_full_price),
            'ItemDesc' => 'Pay2go Order',
            'Email' => $order_info->cart->customer->user_email,
            'LoginType' => '0',
            'ReturnURL' => JURI::root() . "index.php?option=com_hikashop&ctrl=checkout&task=after_end&order_id=$order_info->order_id",
            'NotifyURL' => JURI::root() . "index.php?option=com_hikashop&ctrl=checkout&task=notify&notif_payment=pay2go&tmpl=component&lang=CURRENT_LANG_CODE",
            // ClientBackURL 是選擇非即時付款(超商代碼、條碼、ATM)返回hikashop網站的連結
            'ClientBackURL' => trim($pay2goConfig->payment_params->Pay2goMPG_clientBack_url),
            'LangType' => $pay2goConfig->payment_params->Pay2goMPG_lang_type
        );

        // 取得檢查碼
        $result['CheckValue'] = $this->_getCheckValue($result);

        return $result;
    }

    /**
     * 取得檢查碼
     *
     * @param array  $params    訂單參數
     *
     * @return string checkValue
     */
    protected function _getCheckValue($params) {
        // 要重新排序的參數
        $sortArray = array(
            'MerchantID' => $params['MerchantID'],
            'TimeStamp' => $params['TimeStamp'],
            'MerchantOrderNo' => $params['MerchantOrderNo'],
            'Version' => $params['Version'],
            'Amt' => $params['Amt'],
        );

        ksort($sortArray);

        $check_merstr = http_build_query($sortArray);

        $checkValue_str = 'HashKey=' . $this->hashKey . '&' . $check_merstr . '&HashIV=' . $this->hashIV;

        return strtoupper(hash("sha256", $checkValue_str));
    }

    function onPaymentConfigurationSave(&$element) {
        return true;
    }

    function getPaymentDefaultValues(&$element) {
        $element->payment_name = 'pay2go';
        $element->payment_description = 'Pay2go Description';
        $element->payment_images = 'None';
    }

}
