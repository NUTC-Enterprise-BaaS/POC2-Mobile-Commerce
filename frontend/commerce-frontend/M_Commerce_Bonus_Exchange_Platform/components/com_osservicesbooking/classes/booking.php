<?php
/*------------------------------------------------------------------------
# booking.php - Ossolution Services Booking
# ------------------------------------------------------------------------
# author    Ossolution team
# copyright Copyright (C) 2012 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/

// No direct access.
defined('_JEXEC') or die;

class OsAppscheduleForm{
	/**
	 * Default function
	 *
	 * @param unknown_type $option
	 */
	function display($option,$task){
		global $mainframe;
		$mainframe = JFactory::getApplication();
		$cid = JRequest::getVar( 'cid', array(0));
		JArrayHelper::toInteger($cid, array(0));	
		switch ($task){
			case "form_step1":
				OsAppscheduleForm::checkout();
			break;
			case "form_step2":
				OsAppscheduleForm::confirm();
			break;
			case "form_register":
				OsAppscheduleForm::registerUser();
			break;
		}
	}
	
	/**
	 * Checkout 
	 * Step1 
	 *
	 */
	function checkout(){
		global $mainframe,$configClass;
		$employee_id 			= JRequest::getInt('employee_id',0);
		$category_id 			= JRequest::getInt('category_id',0);
		$vid		 			= JRequest::getInt('vid',0);
		$sid					= JRequest::getInt('sid',0);
		$date_from				= JRequest::getVar('date_from','');
		if($date_from != ""){
			$current_time = strtotime($date_from);
		}else{
			$current_time = HelperOSappscheduleCommon::getRealTime();
		}
		$lists['current_time']  = $current_time;
		$date_to				= JRequest::getVar('date_to','');
		$lists['employee_id'] 	= $employee_id;
		$lists['category'] 		= $category_id;
		$lists['vid'] 			= $vid;
		$lists['date_from'] 	= $date_from;
		$lists['date_to'] 		= $date_to;
		$lists['sid']			= $sid;
		require_once(JPATH_ROOT.DS."components".DS."com_content".DS."helpers".DS."route.php");
		$document  = JFactory::getDocument();
		$document->setTitle($configClass['business_name']." - ".JText::_('OS_CHECKOUT'));
		//$unique_cookie = $_COOKIE['unique_cookie'];
		$unique_cookie = JRequest::getVar('unique_cookie','');
		if($unique_cookie == ""){
			$unique_cookie = $_COOKIE['unique_cookie'];
		}
		setcookie('unique_cookie',$unique_cookie,time() + 3600);
		//get information from profile table
		$db = JFactory::getDbo();
		$user = JFactory::getUser();
		//get profile
		if($user->id > 0){
			
			$db->setQuery("Select * from #__app_sch_userprofiles where user_id = '$user->id'");
			$profile = $db->loadObject();
			if($profile->id == 0){
				if($configClass['integrate_user_profile'] == 1){
					$profile = new stdClass();
					$profileArr = array('address1','city','country','postal_code','phone');
					$profileArr1 = array('order_address','order_city','order_country','order_zip','order_phone');
					for($i=0;$i<count($profileArr);$i++){
						$userprofile = $profileArr[$i];
						$db->setQuery("Select profile_value from #__user_profiles where profile_key like 'profile.".$userprofile."' and user_id = '$user->id'");
						$profile->{$profileArr1[$i]} = $db->loadResult();
						$profile->{$profileArr1[$i]} = substr($profile->{$profileArr1[$i]},1);
						$profile->{$profileArr1[$i]} = substr($profile->{$profileArr1[$i]},0,strlen($profile->{$profileArr1[$i]})-1);
						$profile->{$profileArr1[$i]} = stripslashes($profile->{$profileArr1[$i]});
					}
				}
			}
		}
		
		$countryArr[] = JHTML::_('select.option','','');
		$db->setQuery("Select country_name as value, country_name as text from #__app_sch_countries order by country_name");
		$countries = $db->loadObjectList();
		$countryArr = array_merge($countryArr,$countries);
		$lists['country'] = JHTML::_('select.genericlist',$countryArr,'order_country','style="width:180px;" class="inputbox"','value','text',$profile->order_country);
		$db->setQuery("Select * from #__app_sch_fields where field_area = '1' and published = '1'  order by ordering");
		$fields = $db->loadObjectList();
		if($configClass['disable_payments']  == 1){
			$paymentMethod = JRequest::getVar('payment_method', os_payments::getDefautPaymentMethod(), 'post');	
			if (!$paymentMethod)
			    $paymentMethod = os_payments::getDefautPaymentMethod();
			
			###############Payment Methods parameters###############################
		
			//Creditcard payment parameters		
			$x_card_num = JRequest::getVar('x_card_num', '', 'post');
			$expMonth =  JRequest::getVar('exp_month', date('m'), 'post') ;				
			$expYear = JRequest::getVar('exp_year', date('Y'), 'post') ;		
			$x_card_code = JRequest::getVar('x_card_code', '', 'post');
			$cardHolderName = JRequest::getVar('card_holder_name', '', 'post') ;
			$lists['exp_month'] = JHTML::_('select.integerlist', 1, 12, 1, 'exp_month', ' id="exp_month" class="input-mini"  ', $expMonth, '%02d') ;
			$currentYear = date('Y') ;
			$lists['exp_year'] = JHTML::_('select.integerlist', $currentYear, $currentYear + 10 , 1, 'exp_year', ' id="exp_year" class="input-mini" ', $expYear) ;
			$options =  array() ;
			$cardTypes = explode(',', $configClass['enable_cardtypes']);
			if (in_array('Visa', $cardTypes)) {
				$options[] = JHTML::_('select.option', 'Visa', JText::_('OS_VISA_CARD')) ;			
			}
			if (in_array('MasterCard', $cardTypes)) {
				$options[] = JHTML::_('select.option', 'MasterCard', JText::_('OS_MASTER_CARD')) ;
			}
			
			if (in_array('Discover', $cardTypes)) {
				$options[] = JHTML::_('select.option', 'Discover', JText::_('OS_DISCOVER')) ;
			}		
			if (in_array('Amex', $cardTypes)) {
				$options[] = JHTML::_('select.option', 'Amex', JText::_('OS_AMEX')) ;
			}		
			$lists['card_type'] = JHTML::_('select.genericlist', $options, 'card_type', ' class="inputbox" ', 'value', 'text') ;
			//Echeck
					
			$x_bank_aba_code = JRequest::getVar('x_bank_aba_code', '', 'post') ;
			$x_bank_acct_num = JRequest::getVar('x_bank_acct_num', '', 'post') ;
			$x_bank_name = JRequest::getVar('x_bank_name', '', 'post') ;
			$x_bank_acct_name = JRequest::getVar('x_bank_acct_name', '', 'post') ;				
			$options = array() ;
			$options[] = JHTML::_('select.option', 'CHECKING', JText::_('OS_BANK_TYPE_CHECKING')) ;
			$options[] = JHTML::_('select.option', 'BUSINESSCHECKING', JText::_('OS_BANK_TYPE_BUSINESSCHECKING')) ;
			$options[] = JHTML::_('select.option', 'SAVINGS', JText::_('OS_BANK_TYPE_SAVING')) ;
			$lists['x_bank_acct_type'] = JHTML::_('select.genericlist', $options, 'x_bank_acct_type', ' class="inputbox" ', 'value', 'text', JRequest::getVar('x_bank_acct_type')) ;
			
			$methods = os_payments::getPaymentMethods(true, false) ;
			
			$lists['x_card_num'] = $x_card_num;
			$lists['x_card_code'] = $x_card_code;
			$lists['cardHolderName'] = $cardHolderName;
			$lists['x_bank_acct_num'] = $x_bank_acct_num;
			$lists['x_bank_acct_name'] = $x_bank_acct_name;
			$lists['methods'] = $methods;
			$lists['idealEnabled'] = 0;

			$lists['paymentMethod'] = $paymentMethod;
			
			$idealEnabled = HelperOSappscheduleCommon::idealEnabled();
			if ($idealEnabled) {			
				$bankLists = HelperOSappscheduleCommon::getBankLists() ;			
				$options = array() ;
				foreach ($bankLists as $bankId => $bankName) {
					$options[] = JHTML::_('select.option', $bankId, $bankName) ; 
				}	
				$lists['bank_id'] = JHTML::_('select.genericlist', $options, 'bank_id', ' class="inputbox" ', 'value', 'text', JRequest::getInt('bank_id'));				
			}
		}
		
		$dialArr[] 	 = JHTML::_('select.option','',Jtext::_('OS_SELECT_DIAL_CODE'));
		$db->setQuery("SELECT dial_code as value, concat(country,'-',dial_code) as text FROM #__app_sch_dialing_codes ORDER BY country" );
		$dial_rows   = $db->loadObjectList();
		$dialArr	 = array_merge($dialArr,$dial_rows);
		$lists['dial'] = JHTML::_('select.genericlist',$dialArr,'dial_code','class="input-small"','value','text',$configClass['clickatell_defaultdialingcode']);
		$total = OsAppscheduleAjax::getOrderCost();
		$lists['total'] = $total;
		HTML_OsAppscheduleForm::checkoutLayout($lists,$fields,$profile);
	}
	
	/**
	 * Checkout Step2 
	 * Confirmation user information
	 *
	 */
	function confirm(){
		global $mainframe,$configClass;
		$db = JFactory::getDbo();
		
		$employee_id = JRequest::getInt('employee_id',0);
		$category_id = JRequest::getInt('category_id',0);
		$vid		 = JRequest::getInt('vid',0);
		$service_id	 = JRequest::getVar('service_id',0);
		$lists['employee_id'] 	= $employee_id;
		$lists['category'] 		= $category_id;
		$lists['vid'] 			= $vid;
		$date_from				= JRequest::getVar('date_from','');
		$date_to				= JRequest::getVar('date_to','');
		$lists['date_from'] 	= $date_from;
		if($date_from != ""){
			$current_time = strtotime($date_from);
		}else{
			$current_time = HelperOSappscheduleCommon::getRealTime();
		}
		$lists['current_time']  = $current_time;
		$lists['date_to'] 		= $date_to;
		if($configClass['value_sch_include_captcha'] == 3){
			$post = JRequest::get('post');      
			JPluginHelper::importPlugin('captcha');
			$dispatcher = JDispatcher::getInstance();
			$res = $dispatcher->trigger('onCheckAnswer',$post['recaptcha_response_field']);
			if(!$res[0]){
			    $mainframe->redirect(JRoute::_('index.php?option=com_osservicesbooking&task=form_step1&employee_id='.$employee_id.'&vid='.$vid.'&service_id='.$service_id.'&category_id='.$category_id),JText::_('OS_CAPTCHA_IS_INVALID'));
			}
		}
		
		$document  = JFactory::getDocument();
		$document->setTitle($configClass['business_name']." - ".JText::_('OS_CONFIRM_INFORMATION'));
		$coupon_id = JRequest::getInt('coupon_id',0);
		$user = JFactory::getUser();
		if($coupon_id > 0){
			$db->setQuery("Select * from #__app_sch_coupons where id = '$coupon_id'");
			$coupon = $db->loadObject();
			$max_user_use = $coupon->max_user_use;
			$max_total_use = $coupon->max_total_use;
			if($max_total_use > 0){
				$db->setQuery("Select count(id) from #__app_sch_coupon_used where coupon_id = '$coupon_id'");
				$nused = $db->loadResult();
				if($nused >= $max_total_use){
					$coupon_id = 0;
				}
			}
			if(($max_user_use > 0) and ($coupon_id > 0)){
				if($user->id > 0){
					$db->setQuery("Select count(id) from #__app_sch_coupon_used where user_id = '$user->id' and coupon_id = '$coupon_id'");
					$nused = $db->loadResult();
					if($nused >= $max_user_use){
						$coupon_id = 0;
					}
				}
			}
		}
		$coupon = array();
		if($coupon_id > 0) {
			$db->setQuery("Select * from #__app_sch_coupons where id = '$coupon_id'");
			$coupon = $db->loadObject();
		}
		
		$tax = $configClass['tax_payment'];
		$total = 0;
		$total = OsAppscheduleAjax::getOrderCost();
		$fieldObj = array();
		$fields = JRequest::getVar('fields','');
		$fieldArr = explode("||",$fields);
		if(count($fieldArr) > 0){
			$field_amount = 0;
			for($i=0;$i<count($fieldArr);$i++){
				$field_data = "";
				$field  = $fieldArr[$i];
				$fArr   = explode("|",$field);
				$fid    = $fArr[0];
				$fvalue = $fArr[1];
				$fvalue = str_replace("(@)","&",$fvalue);
				$db->setQuery("Select * from #__app_sch_fields where id = '$fid'");
				$field 	= $db->loadObject();
				$field_type = $field->field_type;
				if($field_type == 0){
					$field_data = $fvalue;
				}elseif($field_type == 1){
					$db->setQuery("Select * from #__app_sch_field_options where id = '$fvalue'");
					$fieldOption = $db->loadObject();
					if($fieldOption->additional_price > 0){
						$field_amount += $fieldOption->additional_price;
					}
					$field_data .= OSBHelper::getLanguageFieldValue($fieldOption,'field_option');
					if($fieldOption->additional_price > 0){
						$field_data.= " - ".$fieldOption->additional_price." ".$configClass['currency_format'];
					}
				}elseif($field_type == 2){
					$fieldValueArr = explode(",",$fvalue);
					if(count($fieldValueArr) > 0){
						for($j=0;$j<count($fieldValueArr);$j++){
							$temp = $fieldValueArr[$j];
							$db->setQuery("Select * from #__app_sch_field_options where id = '$temp'");
							$fieldOption = $db->loadObject();
							if($fieldOption->additional_price > 0){
								$field_amount += $fieldOption->additional_price;
							}
							$field_data .= OSBHelper::getLanguageFieldValue($fieldOption,'field_option');
							if($fieldOption->additional_price > 0){
								$field_data.= " - ".$fieldOption->additional_price." ".$configClass['currency_format'];
							}
							$field_data .= ",";
						}
						$field_data = substr($field_data,0,strlen($field_data)-1);
					}
				}
				
				$count	= count($fieldObj);
				$fieldObj[$count]->field = $field;
				$fieldObj[$count]->fvalue = $field_data;
				$fieldObj[$count]->fieldoptions = $fvalue;
			}
		}
		$total += $field_amount;
		
		if($configClass['disable_payments'] == 1){
			$select_payment 	= JRequest::getVar('payment_method','');
			if($select_payment !=  ""){
				$method = os_payments::getPaymentMethod($select_payment) ;
				$x_card_num			= JRequest::getVar('x_card_num','');
				$x_card_code		= JRequest::getVar('x_card_code','');
				$card_holder_name	= JRequest::getVar('card_holder_name','');
				$exp_year			= JRequest::getVar('exp_year','');
				$exp_month			= JRequest::getVar('exp_month','');
				$card_type			= JRequest::getVar('card_type','');
				$lists['method'] 			= $method;
				$lists['x_card_num'] 		= $x_card_num;
				$lists['x_card_code'] 		= $x_card_code;
				$lists['card_holder_name'] 	= $card_holder_name;
				$lists['exp_year'] 			= $exp_year;
				$lists['exp_month'] 		= $exp_month;
				$lists['card_type'] 		= $card_type;
				$lists['select_payment']	= $select_payment;
				$lists['card_holder_name']  = $card_holder_name;
			}
		}
		
		//Saving profile
		$profile = JTable::getInstance('Profile','OsAppTable');
		$user = JFactory::getUser();
		if($user->id > 0){
			$db->setQuery("Select count(id) from #__app_sch_userprofiles where user_id = '$user->id'");
			$count = $db->loadResult();
			if($count > 0){
				$db->setQuery("Select id from #__app_sch_userprofiles where user_id = '$user->id'");
				$id = $db->loadResult();
				$profile->id = $id;
			}else{
				$profile->id = 0;
			}
			$profile->user_id 		= $user->id;
			$profile->order_name 	= Jrequest::getVar('order_name','');
			$profile->order_email 	= Jrequest::getVar('order_email','');
			$profile->order_phone 	= Jrequest::getVar('order_phone','');
			$profile->order_country = Jrequest::getVar('order_country','');
			$profile->order_address = Jrequest::getVar('order_address','');
			$profile->order_state 	= Jrequest::getVar('order_state','');
			$profile->order_city 	= Jrequest::getVar('order_city','');
			$profile->order_zip 	= Jrequest::getVar('order_zip','');
			$profile->store();
			
			//check and update into User profile table
			if($configClass['integrate_user_profile'] == 1){
				$newprofile = new stdClass();
				$profileArr = array('address1','city','country','postal_code','phone');
				$profileArr1 = array('order_address','order_city','order_country','order_zip','order_phone');
				for($i=0;$i<count($profileArr);$i++){
					$userprofile = $profileArr[$i];
					$db->setQuery("Select count(user_id) from #__user_profiles where user_id = '$user->id' and profile_key like 'profile.".$userprofile."'");
					$count = $db->loadResult();
					if($count > 0){
						$db->setQuery("Update #__user_profiles set profile_value = '".$profile->{$profileArr1[$i]}."' where user_id = '$user->id' and profile_key like 'profile.".$userprofile."'");
						$db->query();
					}else{
						$db->setQuery("Insert into #__user_profiles (user_id,profile_key,profile_value) values ('$user->id','profile.".$userprofile."','".$profile->{$profileArr1[$i]}."')");
						$db->query();
					}
				}
			}
		}
		HTML_OsAppscheduleForm::confirmInforFormHTML($total,$fieldObj,$lists,$coupon);
	}
	
	/**
	 * Register User
	 *
	 */
	function registerUser(){
		global $mainframe,$configClass;
		$lang = & JFactory::getLanguage() ;
		$tag = $lang->getTag();
		if (!$tag)
			$tag = 'en-GB' ;
			
		$lang->load('com_users', JPATH_ROOT, $tag);
			
		$order_name 		= Jrequest::getVar("order_name","");
		$order_email 		= Jrequest::getVar("order_email","");
		$order_username 	= Jrequest::getVar("username","");
		$order_password 	= Jrequest::getVar("password1","");
		
		$data['name'] 		= $order_name;
		$data['password'] 	= $order_password;
		$data['email'] 		= $order_email ;
		$data['email1'] 	= $order_email ;
		$data['username']   = $order_username;
		$data['password2']  = $order_password;
		
		$user = new JUser  ;
		$params	= JComponentHelper::getParams('com_users');
		$data['groups'] = array() ;
		$data['groups'][]= $params->get('new_usertype', 2) ;
		$useractivation = $params->get('useractivation');
		$sendActivationEmail = $configClass['sendActivationEmail'];
		
		
		/*
			$data['block'] = 1 ;
			$data['activation'] = JApplication::getHash(JUserHelper::genRandomPassword());
			if (!$user->bind($data)) {
				JError::raiseError(JText::sprintf('COM_USERS_REGISTRATION_BIND_FAILED', $user->getError()));
				return false;
			}
			// Store the data.
			if (!$user->save()) {
				JError::raiseError(JText::sprintf('COM_USERS_REGISTRATION_SAVE_FAILED', $user->getError()));
				return false;
			}
			$config = JFactory::getConfig() ;
			//Sending activation email
			$data = $user->getProperties();
			$data['fromname']	= $config->get('fromname');
			$data['mailfrom']	= $config->get('mailfrom');
			$data['sitename']	= $config->get('sitename');
			$data['siteurl']	= JUri::root();
			$uri = JURI::getInstance();
			$base = $uri->toString(array('scheme', 'user', 'pass', 'host', 'port'));
			$data['activate'] = $base.JRoute::_('index.php?option=com_users&task=registration.activate&token='.$data['activation'], false);
			
			$emailSubject	= JText::sprintf(
					'COM_USERS_EMAIL_ACCOUNT_DETAILS',
					$data['name'],
					$data['sitename']
			);
			
			$emailBody = JText::sprintf(
					'COM_USERS_EMAIL_REGISTERED_WITH_ACTIVATION_BODY',
					$data['name'],
					$data['sitename'],
					$data['siteurl'].'index.php?option=com_users&task=registration.activate&token='.$data['activation'],
					$data['siteurl'],
					$data['username'],
					$data['password2']
			);
			$mailer = JFactory::getMailer();				
			$mailer->sendMail($data['mailfrom'], $data['fromname'], $data['email'], $emailSubject, $emailBody);
		} else {
		*/
		$data['block'] = 0;
		if (!$user->bind($data)) {
			//JError::raiseError(JText::sprintf('COM_USERS_REGISTRATION_BIND_FAILED', $user->getError()));
			//return false;
			$msg = JText::sprintf('OS_COM_USERS_REGISTRATION_BIND_FAILED', $user->getError());
			$mainframe->redirect(JRoute::_('index.php?option=com_osservicesbooking&task=form_step1&category_id='.JRequest::getVar('category_id',0)."&vid=".JRequest::getVar('vid',0)."&employee_id=".JRequest::getVar('employee_id',0)),$msg);
		}
		// Store the data.
		if (!$user->save()) {
			$msg = JText::sprintf('OS_COM_USERS_REGISTRATION_SAVE_FAILED', $user->getError());
			$mainframe->redirect(JRoute::_('index.php?option=com_osservicesbooking&task=form_step1&category_id='.JRequest::getVar('category_id',0)."&vid=".JRequest::getVar('vid',0)."&employee_id=".JRequest::getVar('employee_id',0)),$msg);
		}
		//}								
		//process login
		if($configClass['use_ssl'] == 1){
			$returnUrl = JRoute::_($configClass['root_link'].'index.php?option=com_osservicesbooking&task=form_step1&category_id='.JRequest::getInt('category_id',0).'&employee_id='.JRequest::getInt('employee_id',0).'&vid='.JRequest::getInt('vid',0).'&Itemid='.Jrequest::getVar('Itemid'));
		}else{
			$returnUrl = JRoute::_(JURI::root().'index.php?option=com_osservicesbooking&task=form_step1&category_id='.JRequest::getInt('category_id',0).'&employee_id='.JRequest::getInt('employee_id',0).'&vid='.JRequest::getInt('vid',0).'&Itemid='.Jrequest::getVar('Itemid'));	
		}
		
		$options = array();
		$options['remember'] = 1;
		$options['return'] = $returnUrl;

		$credentials = array();
		$credentials['username'] = $order_username;
		$credentials['password'] = $order_password;
		
		//preform the login action
		//$error = $mainframe->login($credentials, $options);
		//end login
		if (true === $mainframe->login($credentials, $options)) {
			// Success
			//$app->setUserState('users.login.form.data', array());
			//$app->redirect(JRoute::_($app->getUserState('users.login.form.return'), false));
			$mainframe->redirect($returnUrl);
		} else {
			// Login failed !
			$data['remember'] = (int) $options['remember'];
			$mainframe->setUserState('users.login.form.data', $data);
			$mainframe->redirect(JRoute::_('index.php?option=com_users&view=login', false));
		}
	}
}
?>