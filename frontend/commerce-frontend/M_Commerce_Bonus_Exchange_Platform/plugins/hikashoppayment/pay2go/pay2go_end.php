<?php
/**
 * @author	hughes
 */
    defined('_JEXEC') or die('Restricted access');

?>

<style type="text/css">
    h2{color:#89a9c1;font-size:14px;font-weight:bold;margin-top:20px;margin-bottom:5px;border-bottom:1px solid #d6d6d6;padding-bottom:4px;}
    a:visited{cursor:pointer;color:#2d9cbb;text-decoration:none;border:none;}
</style>

<div id="hikashop_mail" style="font-family:Arial, Helvetica,sans-serif;font-size:12px;line-height:18px;width:100%;background-color:#ffffff;padding-bottom:20px;color:#5b5b5b;">

    <div class="hikashop_online" style="font-family:Arial, Helvetica,sans-serif;font-size:11px;line-height:18px;color:#6a5c6b;text-decoration:none;margin:10px;text-align:center;">
        <a target="_blank" style="cursor:pointer;color:#2d9cbb;text-decoration:none;border:none;" href="<?php echo $this->payment_html['httpDomain']?>index.php?option=com_hikashop&ctrl=order&task=show&cid[]=<?php echo $this->payment_html['order_id']?>">
            <span class="hikashop_online" style="color:#6a5c6b;text-decoration:none;font-size:11px;margin-top:10px;margin-bottom:10px;text-align:center;">按這裡轉至網站瀏覽訂單細節</span>
        </a>
    </div>

    <table class="w600" style="font-family:Arial, Helvetica, sans-serif;font-size:12px;line-height:18px;margin:auto;background-color:#ebebeb;" border="0" cellspacing="0" cellpadding="0" width="600" align="center">

        <tr style="line-height: 0px;">
            <td class="w600" style="line-height:0px" width="600" valign="bottom">
                <img class="w600" src="<?php echo $this->payment_html['httpDomain']?>media/com_hikashop/images/mail/header_black.png" border="0" />
            </td>
        </tr>

        <tr>
            <td class="w600" style="" width="600" align="center">
                <table class="w600" border="0" cellspacing="0" cellpadding="0" width="600" style="margin:0px;font-family: Arial, Helvetica, sans-serif;font-size:12px;line-height:18px;">
                    <tr>
                        <td class="w20" width="20"></td>

                        <td class="w560 pict" style="text-align:left; color:#575757" width="560">
                            <div id="title" style="font-family: Arial, Helvetica, sans-serif;font-size:12px;line-height:18px;">
                                <img src="data:image/png;base64,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" border="0" alt="" style="float:left;margin-right:4px;"/>
				<h1 style="color:#1c8faf !important;font-size:16px;font-weight:bold; border-bottom:1px solid #ddd; padding-bottom:10px">你的訂單</h1>
                                <h2 style="color:#1c8faf !important;font-size:12px;font-weight:bold; padding-bottom:10px">已建立.</h2>
                            </div>
                        </td>

                        <td class="w20" width="20"></td>
                    </tr>

                    <tr>
                        <td class="w20" width="20"></td>

                        <td style="border:1px solid #adadad;background-color:#ffffff;">

                            <div class="w550" width="550" id="content" style="font-family: Arial, Helvetica, sans-serif;font-size:12px;line-height:18px;margin-left:5px;margin-right:5px;">
                                <h1 style="color:#1c8faf !important;font-size:16px;font-weight:bold;border-bottom:1px solid #ddd;padding-top:10px;padding-bottom:10px;">訂單</h1>
                                <p><span style="color:#1c8faf !important;font-weight:bold;">客戶信箱 :</span> <?php echo $this->payment_html['customer_email']?></p>
                                <p><span style="color:#1c8faf !important;font-weight:bold;">訂單編號 :</span> <?php echo $this->payment_html['order_number']?></p>
                                <p><span style="color:#1c8faf !important;font-weight:bold;">付款方式 :</span> <?php echo $this->payment_html['order_payment_method']?></p>

                                <table class="w550" border="0" cellspacing="0" cellpadding="0" width="550" style="margin-top:10px;font-family: Arial, Helvetica, sans-serif;font-size:12px;line-height:18px;">
                                    <tr><td style="color:#1c8faf !important;font-size:12px;font-weight:bold;">帳單地址</td></tr>
                                    <tr>
                                        <td>
                                            <br/><?php echo $this->payment_html['shipping_address']['title']?> <?php echo $this->payment_html['shipping_address']['name']?>
                                            <br/><?php echo $this->payment_html['shipping_address']['street']?>
                                            <br/><?php echo $this->payment_html['shipping_address']['post_code']?> <?php echo $this->payment_html['shipping_address']['city']?> <?php echo $this->payment_html['shipping_address']['state']?>
                                            <br/><?php echo $this->payment_html['shipping_address']['country']?>
                                            <br/>電話: <?php echo $this->payment_html['shipping_address']['telephone']?>
                                        </td>
                                    </tr>
                                </table>

                                <table class="w550" border="0" cellspacing="0" cellpadding="0" width="550" style="margin-top:10px;margin-bottom:10px;font-family: Arial, Helvetica, sans-serif;font-size:12px;line-height:18px;">
                                    <tr>
                                        <td style="border-bottom:1px solid #ddd;padding-bottom:3px;text-align:left;color:#1c8faf !important;font-size:12px;font-weight:bold;">商品名稱</td>
                                        <td style="border-bottom:1px solid #ddd;padding-bottom:3px;text-align:right;color:#1c8faf !important;font-size:12px;font-weight:bold;">單價</td>
                                        <td style="border-bottom:1px solid #ddd;padding-bottom:3px;text-align:right;color:#1c8faf !important;font-size:12px;font-weight:bold;">數量</td>
                                        <td style="border-bottom:1px solid #ddd;padding-bottom:3px;text-align:right;color:#1c8faf !important;font-size:12px;font-weight:bold;">合計</td>
                                    </tr>

                                    <?php foreach($this->payment_html['products']['orders'] as $order) { ?>
                                    <tr>
                                        <td style="border-bottom:1px solid #ddd;padding-bottom:3px;">
                                            <p><?php echo $order['product_name']?></p>
                                        </td>

                                        <td style="border-bottom:1px solid #ddd;padding-bottom:3px;text-align:right"><?php echo $order['product_price'] + $order['product_tax']?></td>
                                        <td style="border-bottom:1px solid #ddd;padding-bottom:3px;text-align:right"><?php echo $order['product_quantity']?></td>
                                        <td style="border-bottom:1px solid #ddd;padding-bottom:3px;text-align:right"><?php echo ($order['product_price'] + $order['product_tax']) * $order['product_quantity']?></td>
                                    </tr>
                                    <?php } ?>

                                    <tr>
                                        <td colspan="3" style="text-align:right;color:#1c8faf !important;font-size:12px;font-weight:bold;">小計</td>
                                        <td style="text-align:right"><?php echo $this->payment_html['products']['total_price']?></td>
                                    </tr>

                                    <tr>
                                        <td colspan="3" style="text-align:right;color:#1c8faf !important;font-size:12px;font-weight:bold;">運費</td>
                                        <td style="text-align:right"><?php echo $this->payment_html['shipping_price']?></td>
                                    </tr>

                                    <tr>
                                        <td colspan="3" style="text-align:right;color:#1c8faf !important;font-size:12px;font-weight:bold;">優惠折抵</td>
                                        <td style="text-align:right"><?php echo $this->payment_html['discount_price']?></td>
                                    </tr>

                                    <tr>
                                    
                                    </tr>

                                    <tr>
                                        <td colspan="3" style="text-align:right;color:#1c8faf !important;font-size:12px;font-weight:bold;">總金額</td>
                                        <td style="text-align:right"><?php echo $this->payment_html['full_price']?></td>
                                    </tr>

                                </table>

                            </div>
                        </td>

                        <td class="w20" width="20"></td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr style="line-height: 0px;">
            <td class="w600" style="line-height:0px" width="600" valign="top">
                    <img class="w600" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAlgAAAAoCAQAAACI0ky/AAAAAXNSR0IArs4c6QAAAAJiS0dEAP+Hj8y/AAAACXBIWXMAAAsTAAALEwEAmpwYAAAAB3RJTUUH3QgfFwgEjKNSvQAAANxJREFUeNrt3cEJwkAURdE34t4OTCO6sgz7sgMrMXUoWoHuVBDtIETJZpxz1h8Cf3GZCYGUdwDqMLMCQLAABAsQLADBAhAsQLAABAtAsADBAhAsAMECBAtAsAAEC/hHcysAvnVKzlkODHS5HJNVDgMzfdab7JNFroMPK1snLMCVEECwAAQLECwAwQIEC0CwAAQLaI0v3YFfdCNm+pQRU7dRU0mS4lf1gCshwNRXwp0jFlBLsF52ANQSrKcdAIIFMHGwHnYACBaAYAGtButuB0AtwfLSHRAsAMECWvUBR3IXlCl8r6oAAAAASUVORK5CYII=" border="0" alt="--" />
            </td>
        </tr>

    </table>
</div>

<form method="post" action="<?php echo $this->htmlAction ?>" style="text-align:center;">
    <?php
    foreach ($this->params as $name => $value) { ?>
    <input type="hidden" name="<?php echo $name ?>" value="<?php echo $value ?>">
    <?php } ?>
    <br style="clear:both">
    <input type="submit" class="btn button hikashop_cart_input_button" name="sub" value="<?php echo JText::_('PAY_NOW');?>" style="width: 117px;height: 42px;font-size: 16px" />
</form>
