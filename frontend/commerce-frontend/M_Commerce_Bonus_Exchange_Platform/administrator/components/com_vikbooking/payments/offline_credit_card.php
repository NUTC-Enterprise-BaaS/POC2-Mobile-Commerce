<?php
/**------------------------------------------------------------------------
 * com_vikbooking - VikBooking
 * ------------------------------------------------------------------------
 * author    Alessio Gaggii - e4j - Extensionsforjoomla.com
 * copyright Copyright (C) 2016 e4j - Extensionsforjoomla.com. All Rights Reserved.
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * Websites: http://www.extensionsforjoomla.com
 * Technical Support:  tech@extensionsforjoomla.com
 * ------------------------------------------------------------------------
*/

defined('_JEXEC') OR die('Restricted Area');


class vikBookingPayment {
	
	private $order_info;
	private $params;
	private $validation;
	private $askcvv;
	private $sslvalidation;
	private $sslcapturepage;
	
	/**
	 * Do not edit this function unless you know what you are doing
	 * it is just meant to define the parameters of the payment method
	 */
	static function getAdminParameters () {
		return array(
				'newstatus' => array('type' => 'select', 'label' => 'Set Order Status to://in case you want to manually verify that the credit card information is valid, set this to Pending', 'options' => array('CONFIRMED', 'PENDING')),
				'askcvv' => array('type' => 'select', 'label' => 'Request CVV Code://If enabled the validation page should be in HTTPS for PCI-compliance', 'options' => array('OFF', 'ON')),
				'sslvalidation' => array('type' => 'select', 'label' => 'Force HTTPS Validation://If enabled the validation page will be in HTTPS', 'options' => array('OFF', 'ON')),
				'sslcapturepage' => array('type' => 'select', 'label' => 'Force HTTPS Capture://If enabled the page where the credit card information are captured will be in HTTPS', 'options' => array('OFF', 'ON'))
		);
	}
	
	public function __construct ($order, $params = array()) {
		$this->order_info=$order;
		$this->params = $params;
		$this->validation = 0;
		$this->askcvv = $params['askcvv'] == 'ON' ? 1 : 0;
		$this->sslvalidation = $params['sslvalidation'] == 'ON' ? 1 : 0;
		$this->sslcapturepage = $params['sslcapturepage'] == 'ON' ? 1 : 0;
	}
	
	public function showPayment () {
		$pitemid = JRequest::getString('Itemid', '', 'request');
		$depositmess="";
		$actionurl = $this->order_info['notify_url'];
		//enable ssl in the payment validation page
		if ($this->sslvalidation == 1) {
			$actionurl = str_replace('http:', 'https:', $actionurl);
		}
		//enable ssl in the credit card info capture page
		if ($this->sslcapturepage == 1) {
			if ($_SERVER['HTTPS'] != "on") {
				$url = $this->order_info['return_url'];
				$mainframe = JFactory::getApplication();
				$mainframe->redirect(str_replace('http:', 'https:', $url));
				exit;
			}
		}
		//
		$form="<br clear=\"all\"/><p>".JText::_('VBCCOFFLINECCMESSAGE')."</p><form action=\"".$actionurl."\" method=\"post\" name=\"offlineccpaymform\">\n";
		$form.="<table>\n";
		$form.="<tr><td align=\"right\">".JText::_('VBCCCREDITCARDNUMBER')." <sup>*</sup></td><td><input type=\"text\" id=\"credit_card_number\" name=\"credit_card_number\" size=\"20\" value=\"\"/></td></tr>\n";
		$form.='<tr><td align="right">'.JText::_('VBCCVALIDTHROUGH').' <sup>*</sup></td><td><select name="expire_month">
				<option value="01">'.JText::_('VBMONTHONE').'</option>
				<option value="02">'.JText::_('VBMONTHTWO').'</option>
				<option value="03">'.JText::_('VBMONTHTHREE').'</option>
				<option value="04">'.JText::_('VBMONTHFOUR').'</option>
				<option value="05">'.JText::_('VBMONTHFIVE').'</option>
				<option value="06">'.JText::_('VBMONTHSIX').'</option>
				<option value="07">'.JText::_('VBMONTHSEVEN').'</option>
				<option value="08">'.JText::_('VBMONTHEIGHT').'</option>
				<option value="09">'.JText::_('VBMONTHNINE').'</option>
				<option value="10">'.JText::_('VBMONTHTEN').'</option>
				<option value="11">'.JText::_('VBMONTHELEVEN').'</option>
				<option value="12">'.JText::_('VBMONTHTWELVE').'</option>
				</select> ';
		$maxyear = date("Y");
		$form.='<select name="expire_year">';
		for ($i = $maxyear; $i <= ($maxyear + 10); $i++) {
			$form.='<option value="'.substr($i, -2, 2).'">'.$i.'</option>';
		}
		$form.='</select></td></tr>'."\n";
		if($this->askcvv == 1) {
			$form.="<tr><td align=\"right\">".JText::_('VBCCCVV')." <sup>*</sup></td><td><input type=\"text\" id=\"credit_card_cvv\" name=\"credit_card_cvv\" size=\"5\" value=\"\"/></td></tr>\n";
		}
		$form.="<tr><td align=\"right\">".JText::_('VBCCFIRSTNAME')." <sup>*</sup></td><td><input type=\"text\" id=\"business_first_name\" name=\"business_first_name\" size=\"20\" value=\"\"/></td></tr>\n";
		$form.="<tr><td align=\"right\">".JText::_('VBCCLASTNAME')." <sup>*</sup></td><td><input type=\"text\" id=\"business_last_name\" name=\"business_last_name\" size=\"20\" value=\"\"/></td></tr>\n";
		$form.="<tr><td align=\"right\" colspan=\"2\"><input type=\"submit\" id=\"offlineccsubmit\" name=\"offlineccsubmit\" class=\"button\" value=\"".JText::_('VBOFFLINECCSEND')."\" onclick=\"javascript: event.preventDefault(); this.disabled = true; this.value = '".addslashes(JText::_('VBOFFLINECCSENT'))."'; document.offlineccpaymform.submit(); return true;\"/></td></tr>\n";
		$form.="</table>\n";
		$form.="<input type=\"hidden\" name=\"total\" value=\"".number_format($this->order_info['total_to_pay'], 2)."\"/>\n";
		$form.="<input type=\"hidden\" name=\"description\" value=\"".$this->order_info['rooms_name']."\"/>\n";
		if(!empty($pitemid)) {
			$form.="<input type=\"hidden\" name=\"Itemid\" value=\"".$pitemid."\"/>\n";
		}
		$form.="</form>\n";
		
		
		if($this->order_info['leave_deposit']) {
			$depositmess="<p class=\"vbo-leave-deposit\"><span>".JText::_('VBLEAVEDEPOSIT')."</span>".$this->order_info['currency_symb']." ".number_format($this->order_info['total_to_pay'], 2)."</p><br/>";
		}
		//output
		echo $depositmess;
		echo $this->order_info['payment_info']['note'];
		echo $form;
		
		return true;
	}
	
	public function validatePayment () {
		$array_result=array();
		$array_result['verified']=0;
		
		//post data
		$creditcard = JRequest::getString('credit_card_number', '', 'request');
		$expire_month = JRequest::getString('expire_month', '', 'request');
		$expire_year = JRequest::getString('expire_year', '', 'request');
		$cvv = JRequest::getString('credit_card_cvv', '', 'request');
		$total = JRequest::getString('total', '', 'request');
		$business_first_name = JRequest::getString('business_first_name', '', 'request');
		$business_last_name = JRequest::getString('business_last_name', '', 'request');
		//end post data
		
		//post data validation
		$error_redirect_url = 'index.php?option=com_vikbooking&task=vieworder&sid='.$this->order_info['sid'].'&ts='.$this->order_info['ts'];
		$valid_data = true;
		$current_month = date("m");
		$current_year = date("y");
		if ((int)$expire_year < (int)$current_year) {
			$valid_data = false;
		} else { 
			if ((int)$expire_year == (int)$current_year) {
				if ((int)$expire_month < (int)$current_month) {
					$valid_data = false;
				}
			}
		}
		if(empty($creditcard) || (empty($cvv) && $this->askcvv == 1) || empty($business_first_name) || empty($business_last_name)) {
			$valid_data = false;
		}
		if(!$valid_data) {
			JError::raiseWarning('', JText::_('VBCCCREDITCARDNUMBERINVALID'));
			$mainframe = JFactory::getApplication();
			$mainframe->redirect($error_redirect_url);
			exit;
		}
		//end post data validation
		
		//Credit Card Information Received
		
		$this->validation = 1;
		$array_result['skip_email'] = 1;
		if (empty($this->params['newstatus']) || $this->params['newstatus'] == 'CONFIRMED') {
			$array_result['verified'] = 1;
			$array_result['skip_email'] = 0;
		}
		
		//Send Credit Card Info via eMail to the Administrator
		$admail = vikbooking::getAdminMail();
		$currencyname = vikbooking::getCurrencyName();
		
		$replacement = '*';
		for ($i = 1; $i <= strlen($creditcard); $i++) {
			$replacement .= '*';
		}
		
		$log = JText::_('VBCCCREDITCARDNUMBER').": ".$creditcard."\n";
		$log .= JText::_('VBCCVALIDTHROUGH')." (mm/yy): ".$expire_month."/".$expire_year."\n";
		if($this->askcvv == 1) {
			$log .= JText::_('VBCCCVV').": *** (".JText::_('VBSENTVIAMAIL').")"."\n";
		}
		$log .= JText::_('VBCCFIRSTNAME').": ".$business_first_name."\n";
		$log .= JText::_('VBCCLASTNAME').": ".$business_last_name."\n";
		$array_result['log'] = $log;
		
		$mess = "Order ID: ".$this->order_info['id']."\n\n";
		$mess .= JText::_('VBCCCREDITCARDNUMBER').": ".substr_replace($creditcard, $replacement, 1, -1)."\n";
		$mess .= JText::_('VBCCVALIDTHROUGH')." (mm/yy): ".$expire_month."/".$expire_year."\n";
		if($this->askcvv == 1) {
			$mess .= JText::_('VBCCCVV').": ".$cvv."\n";
		}
		$mess .= JText::_('VBCCFIRSTNAME').": ".$business_first_name."\n";
		$mess .= JText::_('VBCCLASTNAME').": ".$business_last_name."\n\n";
		$mess .= JText::_('VBOFFCCTOTALTOPAY').": ".$currencyname." ".number_format($total, 2)."\n\n\n";
		$mess .= JURI::root().'index.php?option=com_vikbooking&task=vieworder&sid='.$this->order_info['sid'].'&ts='.$this->order_info['ts'];
		
		$mailer = JFactory::getMailer();
		$adsendermail = vikbooking::getSenderMail();
		$sender = array($adsendermail, $adsendermail);
		$mailer->setSender($sender);
		$mailer->addRecipient($admail);
		$mailer->addReplyTo($adsendermail);
		$mailer->setSubject(JText::_('VBOFFCCMAILSUBJECT'));
		$mailer->setBody($mess);
		$mailer->isHTML(false);
		$mailer->Encoding = 'base64';
		$mailer->Send();
		
		return $array_result;
	}
	
	//this function is called after the payment has been validated for redirect actions
	//When this method is called, the class is invoked at the same time as validatePayment()
	public function afterValidation ($esit = 0) {
		$pitemid = JRequest::getString('Itemid', '', 'request');
		$redirect_url = 'index.php?option=com_vikbooking&task=vieworder&sid='.$this->order_info['sid'].'&ts='.$this->order_info['ts'].(!empty($pitemid) ? '&Itemid='.$pitemid : '');
		$esit = $this->validation;
		if($esit < 1) {
			JError::raiseWarning('', JText::_('VBCCPAYMENTNOTVERIFIED'));
		}else {
			$app = JFactory::getApplication();
			$app->enqueueMessage(JText::_('VBCCINFOSENTOK'));
		}
		
		$mainframe = JFactory::getApplication();
		$mainframe->redirect($redirect_url);
		exit;
		//
	}
	
}


?>