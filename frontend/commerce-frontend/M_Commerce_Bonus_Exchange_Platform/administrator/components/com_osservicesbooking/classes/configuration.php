<?php
/*------------------------------------------------------------------------
# configuration.php - Ossolution Services Booking
# ------------------------------------------------------------------------
# author    Ossolution team
# copyright Copyright (C) 2015 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/
// no direct access
defined('_JEXEC') or die;
/**
 * Enter description here...
 *
 */
class OSappscheduleConfiguration{
	/**
	 * Default function
	 *
	 * @param unknown_type $option
	 */
	function display($option,$task){
		global $mainframe;
		$mainframe = JFactory::getApplication();		
		switch ($task){
			default:
			case "configuration_list":
				OSappscheduleConfiguration::configuration_list($option);
			break;
			case "configuration_save":
				OSappscheduleConfiguration::configuration_save($option,1);
			break;
			case "configuration_apply":
				OSappscheduleConfiguration::configuration_save($option,0);
			break;
			case "configuration_cancel":
				$mainframe->redirect("index.php?option=com_osservicesbooking&task=cpanel_list");
			break;
		}
	}
	
	/**
	 * agent list
	 *
	 * @param unknown_type $option
	 */
	function configuration_list($option){
		global $mainframe;
		$mainframe = JFactory::getApplication();
		$db = JFactory::getDBO();
		$db->setQuery("SELECT * FROM #__app_sch_configuation");
		$configs = new stdClass();
		$elements = $db->loadObjectList();
		if (count($elements)){
			foreach ( $elements as $element) {
				$field_name = $element->config_key;
				$field_value= $element->config_value;
				if($field_name != ""){
					$configs->$field_name = $field_value;
				}
			}
		}
		//get date time format
		$options = array();
		$format_date_times = explode('|','d.m.Y, H:i|d.m.Y, H:i:s|m.d.Y, H:i|m.d.Y, H:i:s|Y.m.d, H:i|Y.m.d, H:i:s|j.n.Y, H:i|j.n.Y, H:i:s|n.j.Y, H:i|n.j.Y, H:i:s|Y.n.j, H:i|Y.n.j, H:i:s|d/m/Y, H:i|d/m/Y, H:i:s|m/d/Y, H:i|m/d/Y, H:i:s|Y/m/d, H:i|Y/m/d, H:i:s|j/n/Y, H:i|j/n/Y, H:i:s|n/j/Y, H:i|n/j/Y, H:i:s|Y/n/j, H:i|Y/n/j, H:i:s|d-m-Y, H:i|d-m-Y, H:i:s|m-d-Y, H:i|m-d-Y, H:i:s|Y-m-d, H:i|Y-m-d, H:i:s|j-n-Y, H:i|j-n-Y, H:i:s|n-j-Y, H:i|n-j-Y, H:i:s|Y-n-j, H:i|Y-n-j, H:i:s');
		foreach ($format_date_times as $format_date_time) {
			$options[] = JHTML::_('select.option', $format_date_time, $format_date_time.' ('.JHtml::_('date','now',$format_date_time).') '  );	
		}
		if (!isset($configs->date_time_format)) $configs->date_time_format='Y-m-d H:i:s';
		$lists['date_time_format'] = JHTML::_('select.genericlist', $options, 'date_time_format', ' class="inputbox" ', 'value', 'text', $configs->date_time_format );
	
		//get date format 
		$options = array();
		$format_dates = explode('|','d.m.Y|m.d.Y|Y.m.d|j.n.Y|n.j.Y|Y.n.j|d/m/Y|m/d/Y|Y/m/d|j/n/Y|n/j/Y|Y/n/j|d-m-Y|m-d-Y|Y-m-d|j-n-Y|n-j-Y|Y-n-j');
		foreach ($format_dates as $format_date) {
			$options[] = JHTML::_('select.option', $format_date, $format_date.' ('.JHtml::_('date','now',$format_date).') '  );
		}
		if (!isset($configs->date_format)) $configs->date_format='Y-m-d';
		$lists['date_format'] = JHTML::_('select.genericlist', $options, 'date_format', ' class="inputbox" ', 'value', 'text', $configs->date_format );
	
		//get time format
		$options = array();
		$format_times = explode('|','H:i|G:i|h:i|h:i a|h:i A|g:i|g:i a|g:i A');
		foreach ($format_times as $format_time) {
			$options[] = JHTML::_('select.option', $format_time, $format_time.' ('.JHtml::_('date','now',$format_time).') '  );
		}
		if (!isset($configs->time_format)) $configs->time_format='H:i';
		$lists['time_format'] = JHTML::_('select.genericlist', $options, 'time_format', ' class="inputbox" ', 'value', 'text', $configs->time_format );	

		//get GMT
		$options = array();
		$format_gmts = explode('|','GMT-12:00|GMT-11:00|GMT-10:00|GMT-09:00|GMT-08:00|GMT-07:00|GMT-06:00|GMT-05:00|GMT-04:00|GMT-03:00|GMT-02:00|GMT-01:00|GMT|GMT+01:00|GMT+02:00|GMT+03:00|GMT+04:00|GMT+05:00|GMT+06:00|GMT+07:00|GMT+08:00|GMT+09:00|GMT+10:00|GMT+11:00|GMT+12:00|GMT+13:00');
		
		$gmtArr[0]->value = -12;
		$gmtArr[0]->text  = 'GMT-12:00';
		$gmtArr[1]->value = -11;
		$gmtArr[1]->text  = 'GMT-11:00';
		$gmtArr[2]->value = -10;
		$gmtArr[2]->text  = 'GMT-10:00';
		$gmtArr[3]->value = -9;
		$gmtArr[3]->text  = 'GMT-9:00';
		$gmtArr[4]->value = -8;
		$gmtArr[4]->text  = 'GMT-8:00';
		$gmtArr[5]->value = -7;
		$gmtArr[5]->text  = 'GMT-7:00';
		$gmtArr[6]->value = -6;
		$gmtArr[6]->text  = 'GMT-6:00';
		$gmtArr[7]->value = -5;
		$gmtArr[7]->text  = 'GMT-5:00';
		$gmtArr[8]->value = -4;
		$gmtArr[8]->text  = 'GMT-4:00';
		$gmtArr[9]->value = -3;
		$gmtArr[9]->text  = 'GMT-3:00';
		$gmtArr[10]->value = -2;
		$gmtArr[10]->text  = 'GMT-2:00';
		$gmtArr[11]->value = -1;
		$gmtArr[11]->text  = 'GMT-1:00';
		$gmtArr[12]->value = 0;
		$gmtArr[12]->text  = 'GMT';
		$gmtArr[13]->value = 1;
		$gmtArr[13]->text  = 'GMT+1:00';
		$gmtArr[14]->value = 2;
		$gmtArr[14]->text  = 'GMT+2:00';
		$gmtArr[15]->value = 3;
		$gmtArr[15]->text  = 'GMT+3:00';
		$gmtArr[16]->value = 4;
		$gmtArr[16]->text  = 'GMT+4:00';
		$gmtArr[17]->value = 5;
		$gmtArr[17]->text  = 'GMT+5:00';
		$gmtArr[18]->value = 6;
		$gmtArr[18]->text  = 'GMT+6:00';
		$gmtArr[19]->value = 7;
		$gmtArr[19]->text  = 'GMT+7:00';
		$gmtArr[20]->value = 8;
		$gmtArr[20]->text  = 'GMT+8:00';
		$gmtArr[21]->value = 9;
		$gmtArr[21]->text  = 'GMT+9:00';
		$gmtArr[22]->value = 10;
		$gmtArr[22]->text  = 'GMT+10:00';
		$gmtArr[23]->value = 11;
		$gmtArr[23]->text  = 'GMT+11:00';
		$gmtArr[24]->value = 12;
		$gmtArr[24]->text  = 'GMT+12:00';
		$gmtArr[25]->value = 13;
		$gmtArr[25]->text  = 'GMT+13:00';
		
		foreach ($gmtArr as $format_gmt) {
			$options[] = JHTML::_('select.option', $format_gmt->value, $format_gmt->text);
		}
		if (!isset($configs->value_sch_timezone)) $configs->value_sch_timezone='GMT';
		$lists['value_sch_timezone'] = JHTML::_('select.genericlist', $options, 'value_sch_timezone', ' class="inputbox" ', 'value', 'text', $configs->value_sch_timezone );	
		
		if (!isset($configs->hosting_timezone)) $configs->hosting_timezone='GMT';
		$lists['hosting_timezone'] = JHTML::_('select.genericlist', $options, 'hosting_timezone', ' class="inputbox" ', 'value', 'text', $configs->hosting_timezone );	
		
		//get font famyly
		$options = array();
		$format_font_familys = explode('|','Arial|Arial Black|Book Antiqua|Century|Century Gothic|Comic Sans MS|Courier|Courier New|Impact|Lucida Console|Lucida Sans Unicode|Monotype Corsiva|Modern|Sans Serif|Serif|Small fonts|Symbol|Tahoma|Times New Roman|Verdana');
		foreach ($format_font_familys as $format_font_family) {
			$options[] = JHTML::_('select.option', $format_font_family, $format_font_family);
		}
		if (!isset($configs->font_family))$configs->font_family='Arial';
		$lists['font_family'] = JHTML::_('select.genericlist', $options, 'font_family', ' class="inputbox" ', 'value', 'text', $configs->font_family );	

		//get font Style
		$options = array();
		$format_font_styles = explode('|','Nomal|Bold|Italic|Underline|Bold Italic');
		foreach ($format_font_styles as $format_font_style) {
			$options[] = JHTML::_('select.option', $format_font_style, $format_font_style);
		}
		if (!isset($configs->font_style))$configs->font_style='Nomal';
		$lists['font_style'] = JHTML::_('select.genericlist', $options, 'font_style', ' class="inputbox" ', 'value', 'text', $configs->font_style );	

		//get font size
		$options = array();
		$format_font_sizes = explode('|','10|12|14|16|18|20|22|24|26|28|30');
		foreach ($format_font_sizes as $format_font_size) {
			$options[] = JHTML::_('select.option', $format_font_size, $format_font_size);
		}
		if (!isset($configs->font_size))$configs->font_size='10';
		$lists['font_size'] = JHTML::_('select.genericlist', $options, 'font_size', ' class="inputbox" ', 'value', 'text', $configs->font_size );	
		//get Currency	
	
		$db->setQuery("Select currency_code as value, currency_code as text from #__app_sch_currencies");
		$currencies = $db->loadObjectList();
		if($configs->currency_format == ""){
			$configs->currency_format = "USD";
		}
		
		if (!isset($configs->currency_format))$configs->currency_format='AUD';			
		$lists['currency_format'] = JHTML::_('select.genericlist', $currencies, 'currency_format', ' class="input-small" ', 'value', 'text', $configs->currency_format );	
	
		//get Default booking status
		$options = array() ;
		$options[] =  JHTML::_('select.option', 0, JText::_('OS_CONFIGURATION_CONFIRMED')) ;
		$options[] = JHTML::_('select.option', 1 , JText::_('OS_CONFIGURATION_PENDING')) ;
		$options[] = JHTML::_('select.option', 2 , JText::_('OS_CONFIGURATION_CANCELLED')) ;
		$lists['default_status'] = JHTML::_('select.genericlist', $options, 'default_status', ' class="input-small" ', 'value', 'text', $configs->default_status );
	
		//Default booking status after payment
		$options = array() ;
		$options[] =  JHTML::_('select.option', 0, JText::_('OS_CONFIGURATION_CONFIRMED')) ;
		$options[] = JHTML::_('select.option', 1 , JText::_('OS_CONFIGURATION_PENDING')) ;
		$options[] = JHTML::_('select.option', 2 , JText::_('OS_CONFIGURATION_CANCELLED')) ;
		$lists['payment_status'] = JHTML::_('select.genericlist', $options, 'payment_status', ' class="input-small" ', 'value', 'text', $configs->payment_status );

		//get Step (in minutes)
		$options = array();
		$format_steps = explode('|','5|10|15|20|25|30|35|40|45|50|55|60|65|70|75|80|85|90|95|100|105|110|115|120|125|130|135|140');
		foreach ($format_steps as $format_step) {
			$options[] = JHTML::_('select.option', $format_step, $format_step);
		}
		if (!isset($configs->step_format))$configs->step_format='5';
		$lists['step_format'] = JHTML::_('select.genericlist', $options, 'step_format', ' class="input-small" ', 'value', 'text', $configs->step_format );	
	
		//get Hide prices
		$options = array() ;
		$options[] = JHTML::_('select.option', 0, JText::_('OS_CONFIGURATION_YES'));
		$options[] = JHTML::_('select.option', 1, JText::_('OS_CONFIGURATION_NO'));
		$lists['hide_prices'] = JHTML::_('select.genericlist', $options, 'hide_prices', ' class="input-small" ', 'value', 'text', $configs->hide_prices);

		//get Disable payments
		$options = array() ;
		$options[] = JHTML::_('select.option', 0, JText::_('OS_CONFIGURATION_YES'));
		$options[] = JHTML::_('select.option', 1, JText::_('OS_CONFIGURATION_NO'));
		$lists['disable_payments'] = JHTML::_('select.genericlist', $options, 'disable_payments', ' class="input-small" ', 'value', 'text', $configs->disable_payments);
			
		//show Occupied time slots
		$options = array() ;
		$options[] = JHTML::_('select.option', 1, JText::_('OS_CONFIGURATION_YES'));
		$options[] = JHTML::_('select.option', 0, JText::_('OS_CONFIGURATION_NO'));
		$lists['show_occupied'] = JHTML::_('select.genericlist', $options, 'show_occupied', ' class="input-small" ', 'value', 'text', $configs->show_occupied);
		
		//Order status Disable payments
		$options = array() ;
		$options[] = JHTML::_('select.option', 'P', JText::_('Pending'));
		$options[] = JHTML::_('select.option', 'S', JText::_('Completed'));
		$lists['disable_payment_order_status'] = JHTML::_('select.genericlist', $options, 'disable_payment_order_status', ' class="input-small" ', 'value', 'text', $configs->disable_payment_order_status);

		//get Allow PayPal payments
		$options = array() ;
		$options[] = JHTML::_('select.option', 0, JText::_('OS_CONFIGURATION_YES'));
		$options[] = JHTML::_('select.option', 1, JText::_('OS_CONFIGURATION_NO'));
		$lists['allow_paypal'] = JHTML::_('select.genericlist', $options, 'allow_paypal', ' class="inputbox" ', 'value', 'text', $configs->allow_paypal);

		// get Allow Authorize.net payments	
		$options = array() ;
		$options[] = JHTML::_('select.option', 0, JText::_('OS_CONFIGURATION_YES'));
		$options[] = JHTML::_('select.option', 1, JText::_('OS_CONFIGURATION_NO'));
		$lists['allow_authorize'] = JHTML::_('select.genericlist', $options, 'allow_authorize', ' class="inputbox" ', 'value', 'text', $configs->allow_authorize);

		//get Allow payments with Credit cards
		$options = array() ;
		$options[] = JHTML::_('select.option', 0, JText::_('OS_CONFIGURATION_YES'));
		$options[] = JHTML::_('select.option', 1, JText::_('OS_CONFIGURATION_NO'));
		$lists['allow_payments_with'] = JHTML::_('select.genericlist', $options, 'allow_payments_with', ' class="inputbox" ', 'value', 'text', $configs->allow_payments_with);

		//get Send confirmation email
		$options = array() ;
		$options[] = JHTML::_('select.option', 1, JText::_('OS_CONFIGURATION_NONE'));
		$options[] = JHTML::_('select.option', 2, JText::_('OS_CONFIGURATION_BOOKING_FORM'));
		$options[] = JHTML::_('select.option', 3, JText::_('OS_CONFIGURATION_AFTER_PAYMENTS'));
		$lists['value_enum_email_confirmation'] = JHTML::_('select.genericlist', $options, 'value_enum_email_confirmation', ' class="inputbox" ', 'value', 'text', $configs->value_enum_email_confirmation);

		//get Send payment confirmation email
		$options = array() ;
		$options[] = JHTML::_('select.option', 1, JText::_('OS_CONFIGURATION_NONE'));
		$options[] = JHTML::_('select.option', 2, JText::_('OS_CONFIGURATION_AFTER_PAYMENTS'));
		$lists['value_enum_email_payment'] = JHTML::_('select.genericlist', $options, 'value_enum_email_payment', ' class="inputbox" ', 'value', 'text', $configs->value_enum_email_payment);

		//get Name Required
		$options = array() ;
		$options[] = JHTML::_('select.option', 1, JText::_('OS_CONFIGURATION_YES'));
		$options[] = JHTML::_('select.option', 2, JText::_('OS_CONFIGURATION_NO'));
		$options[] = JHTML::_('select.option', 3, JText::_('OS_CONFIGURATION_YES_REQUIRED'));
		$lists['value_sch_include_name'] = JHTML::_('select.genericlist', $options, 'value_sch_include_name', ' class="input-small" ', 'value', 'text', $configs->value_sch_include_name);

		//get Email Address Required
		$options = array() ;
		$options[] = JHTML::_('select.option', 1, JText::_('OS_CONFIGURATION_YES'));
		$options[] = JHTML::_('select.option', 2, JText::_('OS_CONFIGURATION_NO'));
		$options[] = JHTML::_('select.option', 3, JText::_('OS_CONFIGURATION_YES_REQUIRED'));
		$lists['value_sch_include_email'] = JHTML::_('select.genericlist', $options, 'value_sch_include_email', ' class="input-small" ', 'value', 'text', $configs->value_sch_include_email);

		//get Phone  Required
		$options = array() ;
		$options[] = JHTML::_('select.option', 1, JText::_('OS_CONFIGURATION_YES'));
		$options[] = JHTML::_('select.option', 0, JText::_('OS_CONFIGURATION_NO'));
		$options[] = JHTML::_('select.option', 2, JText::_('OS_CONFIGURATION_YES_REQUIRED'));
		$lists['value_sch_include_phone'] = JHTML::_('select.genericlist', $options, 'value_sch_include_phone', ' class="input-small" ', 'value', 'text', $configs->value_sch_include_phone);

		//get Note  Required
		$options = array() ;
		$options[] = JHTML::_('select.option', 1, JText::_('OS_CONFIGURATION_YES'));
		$options[] = JHTML::_('select.option', 0, JText::_('OS_CONFIGURATION_NO'));
		$lists['value_sch_include_notes'] = JHTML::_('select.genericlist', $options, 'value_sch_include_notes', ' class="input-small" ', 'value', 'text', $configs->value_sch_include_notes);

		//get Country Required
		$options = array() ;
		$options[] = JHTML::_('select.option', 1, JText::_('OS_CONFIGURATION_YES'));
		$options[] = JHTML::_('select.option', 0, JText::_('OS_CONFIGURATION_NO'));
		$lists['value_sch_include_country'] = JHTML::_('select.genericlist', $options, 'value_sch_include_country', ' class="input-small" ', 'value', 'text', $configs->value_sch_include_country);

		//get City Required
		$options = array() ;
		$options[] = JHTML::_('select.option', 1, JText::_('OS_CONFIGURATION_YES'));
		$options[] = JHTML::_('select.option', 0, JText::_('OS_CONFIGURATION_NO'));
		$lists['value_sch_include_city'] = JHTML::_('select.genericlist', $options, 'value_sch_include_city', ' class="input-small" ', 'value', 'text', $configs->value_sch_include_city);

		//get state Required
		$options = array() ;
		$options[] = JHTML::_('select.option', 1, JText::_('OS_CONFIGURATION_YES'));
		$options[] = JHTML::_('select.option', 0, JText::_('OS_CONFIGURATION_NO'));
		$lists['value_sch_include_state'] = JHTML::_('select.genericlist', $options, 'value_sch_include_state', ' class="input-small" ', 'value', 'text', $configs->value_sch_include_state);

		//get Zip Required
		$options = array() ;
		$options[] = JHTML::_('select.option', 1, JText::_('OS_CONFIGURATION_YES'));
		$options[] = JHTML::_('select.option', 0, JText::_('OS_CONFIGURATION_NO'));
		$lists['value_sch_include_zip'] = JHTML::_('select.genericlist', $options, 'value_sch_include_zip', ' class="input-small" ', 'value', 'text', $configs->value_sch_include_zip);

		//get Address Required
		$options = array() ;
		$options[] = JHTML::_('select.option', 1, JText::_('OS_CONFIGURATION_YES'));
		$options[] = JHTML::_('select.option', 0, JText::_('OS_CONFIGURATION_NO'));
		$options[] = JHTML::_('select.option', 2, JText::_('OS_CONFIGURATION_YES_REQUIRED'));
		$lists['value_sch_include_address'] = JHTML::_('select.genericlist', $options, 'value_sch_include_address', ' class="input-small" ', 'value', 'text', $configs->value_sch_include_address);

		//get Capcha Required
		$options = array() ;
		$options[] = JHTML::_('select.option', 1, JText::_('OS_CONFIGURATION_NO'));
		$options[] = JHTML::_('select.option', 2, JText::_('OS_CONFIGURATION_YES_OSB_CAPTCHA'));
		$options[] = JHTML::_('select.option', 3, JText::_('OS_CONFIGURATION_YES_RECAPTCHA'));
		$lists['value_sch_include_captcha'] = JHTML::_('select.genericlist', $options, 'value_sch_include_captcha', ' class="input-medium" ', 'value', 'text', $configs->value_sch_include_captcha);
			
		//Bypass captcha for registered users
		$options = array() ;
		$options[] = JHTML::_('select.option', 0, JText::_('OS_CONFIGURATION_NO'));
		$options[] = JHTML::_('select.option', 1, JText::_('OS_CONFIGURATION_YES'));
		$lists['pass_captcha'] = JHTML::_('select.genericlist', $options, 'pass_captcha', ' class="input-medium" ', 'value', 'text', $configs->pass_captcha);

		//get Enable notifications
		$options = array() ;
		$options[] = JHTML::_('select.option', 0, JText::_('OS_CONFIGURATION_YES'));
		$options[] = JHTML::_('select.option', 1, JText::_('OS_CONFIGURATION_NO'));
		$lists['value_sch_reminder_enable'] = JHTML::_('select.genericlist', $options, 'value_sch_reminder_enable', ' class="inputbox" ', 'value', 'text', $configs->value_sch_reminder_enable);
		
		
		$options = array() ;
		$options[] =  JHTML::_('select.option', 0, JText::_('Live mode')) ;
		$options[] = JHTML::_('select.option', 1 , JText::_('Test mode')) ;
		$lists['paypal_testmode'] = JHTML::_('select.genericlist', $options, 'paypal_testmode', ' class="inputbox" ', 'value', 'text', $configs->paypal_testmode );
		
		
		$options = array() ;
		$options[] = JHTML::_('select.option', 1, JText::_('OS_CONFIGURATION_YES'));
		$options[] = JHTML::_('select.option', 0, JText::_('OS_CONFIGURATION_NO'));
		$lists['show_footer'] = JHTML::_('select.genericlist', $options, 'show_footer', ' class="input-small" ', 'value', 'text', $configs->show_footer);
		
		$options = array() ;
		$options[] = JHTML::_('select.option', 1, JText::_('OS_CONFIGURATION_YES'));
		$options[] = JHTML::_('select.option', 0, JText::_('OS_CONFIGURATION_NO'));
		$lists['multiple_work'] = JHTML::_('select.genericlist', $options, 'multiple_work', ' class="input-small" ', 'value', 'text', $configs->multiple_work);

		$options = array() ;
		$options[] = JHTML::_('select.option', 1, JText::_('OS_CONFIGURATION_YES'));
		$options[] = JHTML::_('select.option', 0, JText::_('OS_CONFIGURATION_NO'));
		$lists['disable_timeslot'] = JHTML::_('select.genericlist', $options, 'disable_timeslot', ' class="input-small" ', 'value', 'text', $configs->disable_timeslot);
		
		$payments = $configs->payments;
		$payments = explode(",",$payments);
		$paymentArr[] = JHTML::_('select.option','os_paypal','Paypal');
		$paymentArr[] = JHTML::_('select.option','os_offline','Offline payment');
		$paymentArr[] = JHTML::_('select.option','os_sagepay','Sagepay payment');
		$lists['payment'] = JHTML::_('select.genericlist',$paymentArr,'payments[]','class="inputbox" multiple','value','text',$payments);
		
		$paymentArr = array();
		$paymentArr[]  =  JHTML::_('select.option','Visa',JText::_('OS_VISA_CARD'));
		$paymentArr[]  =  JHTML::_('select.option','MasterCard',JText::_('OS_MASTER_CARD'));
		$paymentArr[]  =  JHTML::_('select.option','Discover',JText::_('OS_DISCOVER'));
		$paymentArr[]  =  JHTML::_('select.option','Amex',JText::_('OS_AMEX'));
		
		$enable_cardtypes = $configs->enable_cardtypes;
		$enable_cardtypes = explode(",",$enable_cardtypes);
		$lists['cardtypes'] = JHTML::_('select.genericlist',$paymentArr,'enable_cardtypes[]','class="inputbox" multiple','value','text',$enable_cardtypes);
		
		$options = array() ;
		$options[] = JHTML::_('select.option', 1, JText::_('OS_CONFIGURATION_YES'));
		$options[] = JHTML::_('select.option', 0, JText::_('OS_CONFIGURATION_NO'));
		$lists['allow_registered_only'] = JHTML::_('select.genericlist', $options, 'allow_registered_only', ' class="input-small" ', 'value', 'text', $configs->allow_registered_only);
		
		$options = array() ;
		$options[] = JHTML::_('select.option', 'sunday', JText::_('OS_SUNDAY'));
		$options[] = JHTML::_('select.option', 'monday', JText::_('OS_MONDAY'));
		$lists['start_day_in_week'] = JHTML::_('select.genericlist', $options, 'start_day_in_week', ' class="input-small" ', 'value', 'text', $configs->start_day_in_week);
		
		$options = array() ;
		$options[] = JHTML::_('select.option', 1, JText::_('OS_CONFIGURATION_YES'));
		$options[] = JHTML::_('select.option', 0, JText::_('OS_CONFIGURATION_NO'));
		$lists['enable_tax'] = JHTML::_('select.genericlist', $options, 'enable_tax', ' class="input-small" ', 'value', 'text', $configs->enable_tax);
		
		$options = array() ;
		$options[] = JHTML::_('select.option', 1, JText::_('OS_CONFIGURATION_YES'));
		$options[] = JHTML::_('select.option', 0, JText::_('OS_CONFIGURATION_NO'));
		$lists['enable_termandcondition'] = JHTML::_('select.genericlist', $options, 'enable_termandcondition', 'class="input-small" ', 'value', 'text', $configs->enable_termandcondition);
		
		$sql = 'SELECT id, title FROM #__content WHERE `state` = 1 ORDER BY title ';
		$db->setQuery($sql) ;
		$rows = $db->loadObjectList();
		$options = array() ;
		$options[] = JHTML::_('select.option', '' ,'', 'id', 'title') ;
		$options = array_merge($options, $rows) ;		
		$lists['article_id'] = JHTML::_('select.genericlist', $options, 'article_id', ' class="input-large" ', 'id', 'title', $configs->article_id) ;
		
		$options = array() ;
		$options[] = JHTML::_('select.option', 0, JText::_('OS_BEFORE_AMOUNT'));
		$options[] = JHTML::_('select.option', 1, JText::_('OS_AFTER_AMOUNT'));
		$lists['currency_symbol_position'] = JHTML::_('select.genericlist', $options, 'currency_symbol_position', 'STYLE="width:140px;" class="input-small" ', 'value', 'text', (int)$configs->currency_symbol_position);
		
		//get Activate Invoice Feature
		$options = array() ;
		$options[] = JHTML::_('select.option', 1, JText::_('OS_CONFIGURATION_YES'));
		$options[] = JHTML::_('select.option', 0, JText::_('OS_CONFIGURATION_NO'));
		$lists['activate_invoice_feature'] = JHTML::_('select.genericlist', $options, 'activate_invoice_feature', ' class="input-small" ', 'value', 'text',(int) $configs->activate_invoice_feature);

		//get Send invoice to subscribers 
		$options = array() ;
		$options[] = JHTML::_('select.option', 1, JText::_('OS_CONFIGURATION_YES'));
		$options[] = JHTML::_('select.option', 0, JText::_('OS_CONFIGURATION_NO'));
		$lists['send_invoice_to_customer'] = JHTML::_('select.genericlist', $options, 'send_invoice_to_customer', ' class="input-small" ', 'value', 'text', (int)$configs->send_invoice_to_customer);
		
		
		//get Send invoice to subscribers 
		$options = array() ;
		$options[] = JHTML::_('select.option', 1, JText::_('OS_CONFIGURATION_YES'));
		$options[] = JHTML::_('select.option', 0, JText::_('OS_CONFIGURATION_NO'));
		$lists['employee_change_availability'] = JHTML::_('select.genericlist', $options, 'employee_change_availability', ' class="input-small" ', 'value', 'text', (int)$configs->employee_change_availability);
		
		$db->setQuery("Select id as value,title as text from #__usergroups order by id desc");
		$groups = $db->loadObjectList();
		$options = array() ;
		$options[] = JHTML::_('select.option','', JText::_('OS_SELECT_USERGROUP'));
		$options = array_merge($options,$groups);
		$lists['employee_acl_group'] = JHTML::_('select.genericlist', $options, 'employee_acl_group', ' class="input-large" ', 'value', 'text', $configs->employee_acl_group);
		
		$options = array() ;
		$options[] = JHTML::_('select.option','', JText::_('OS_SELECT_USERGROUP'));
		$options = array_merge($options,$groups);
		$lists['group_payment'] = JHTML::_('select.genericlist', $options, 'group_payment', ' class="input-large" ', 'value', 'text', $configs->group_payment);
		
		$optionArr   = array();
		$optionArr[] = JHTML::_('select.option','1',JText::_('OS_YES'));
		$optionArr[] = JHTML::_('select.option','0',JText::_('OS_NO'));
		$lists['enable_clickatell'] = JHTML::_('select.genericlist',$optionArr,'enable_clickatell','class="input-mini"','value','text',(int)$configs->enable_clickatell);
		
		$optionArr   = array();
		$optionArr[] = JHTML::_('select.option','1',JText::_('OS_YES'));
		$optionArr[] = JHTML::_('select.option','0',JText::_('OS_NO'));
		$lists['enable_eztexting'] = JHTML::_('select.genericlist',$optionArr,'enable_eztexting','class="input-mini"','value','text',(int)$configs->enable_eztexting);
		
		$lists['clickatell_showcodelist'] = JHTML::_('select.genericlist',$optionArr,'clickatell_showcodelist','class="input-mini"','value','text',$configs->clickatell_showcodelist);
		
		$lists['clickatell_enable_unicode'] = JHTML::_('select.genericlist',$optionArr,'clickatell_enable_unicode','class="input-mini"','value','text',$configs->clickatell_enable_unicode);
		
		// get dialing codes
		$db->setQuery("SELECT dial_code as value, concat(country,'-',dial_code) as text FROM #__app_sch_dialing_codes ORDER BY country" );
		$dial_rows   = $db->loadObjectList();
		$lists['dial'] = JHTML::_('select.genericlist',$dial_rows,'clickatell_defaultdialingcode','class="input-large"','value','text',$configs->clickatell_defaultdialingcode);
		
		$optionArr   = array();
		$optionArr[] = JHTML::_('select.option','1',JText::_('OS_YES'));
		$optionArr[] = JHTML::_('select.option','0',JText::_('OS_NO'));
		$lists['integrate_gcalendar'] = JHTML::_('select.genericlist',$optionArr,'integrate_gcalendar','class="input-mini"','value','text',$configs->integrate_gcalendar);
		
		
		$options = array() ;
		$options[] = JHTML::_('select.option', 1, JText::_('OS_CONFIGURATION_YES'));
		$options[] = JHTML::_('select.option', 0, JText::_('OS_CONFIGURATION_NO'));
		$lists['show_service_info_box'] = JHTML::_('select.genericlist', $options, 'show_service_info_box', ' class="input-small" ', 'value', 'text', (int)$configs->show_service_info_box);
		
		$options = array() ;
		$options[] = JHTML::_('select.option', 1, JText::_('OS_CONFIGURATION_YES'));
		$options[] = JHTML::_('select.option', 0, JText::_('OS_CONFIGURATION_NO'));
		if($configs->show_booked_information == ""){
			(int)$configs->show_booked_information = 1;
		}
		$lists['show_booked_information'] = JHTML::_('select.genericlist', $options, 'show_booked_information', ' class="input-small" ', 'value', 'text', (int)$configs->show_booked_information);
		
		$options = array() ;
		$options[] = JHTML::_('select.option', 1, JText::_('OS_CONFIGURATION_YES'));
		$options[] = JHTML::_('select.option', 0, JText::_('OS_CONFIGURATION_NO'));
		$lists['show_service_description'] = JHTML::_('select.genericlist', $options, 'show_service_description', ' class="input-small" ', 'value', 'text', (int)$configs->show_service_description);
		
		$options = array() ;
		$options[] = JHTML::_('select.option', 1, JText::_('OS_CONFIGURATION_YES'));
		$options[] = JHTML::_('select.option', 0, JText::_('OS_CONFIGURATION_NO'));
		$lists['show_service_photo'] = JHTML::_('select.genericlist', $options, 'show_service_photo', ' class="input-small" ', 'value', 'text', (int)$configs->show_service_photo);
		
		$options = array() ;
		$options[] = JHTML::_('select.option', 1, JText::_('OS_CONFIGURATION_YES'));
		$options[] = JHTML::_('select.option', 0, JText::_('OS_CONFIGURATION_NO'));
		$lists['load_bootstrap'] = JHTML::_('select.genericlist', $options, 'load_bootstrap', ' class="input-small" ', 'value', 'text', (int)$configs->load_bootstrap);
		
		$options = array() ;
		$options[] = JHTML::_('select.option', 1, JText::_('OS_CONFIGURATION_YES'));
		$options[] = JHTML::_('select.option', 0, JText::_('OS_CONFIGURATION_NO'));
		$lists['use_ssl'] = JHTML::_('select.genericlist', $options, 'use_ssl', ' class="input-small" ', 'value', 'text', $configs->use_ssl);
		
		$options = array() ;
		$options[] = JHTML::_('select.option', ',' , JText::_('OS_COLON')) ;
		$options[] = JHTML::_('select.option', ';', JText::_('OS_SEMICOLON')) ;
		$lists['csv_separator'] = JHTML::_('select.genericlist', $options, 'csv_separator', ' class="input-small" ', 'value', 'text', $configs->csv_separator );
		
		$options = array() ;
		$options[] = JHTML::_('select.option', 1, JText::_('OS_CONFIGURATION_YES'));
		$options[] = JHTML::_('select.option', 0, JText::_('OS_CONFIGURATION_NO'));
		$lists['allow_multiple_timezones'] = JHTML::_('select.genericlist', $options, 'allow_multiple_timezones', ' class="input-mini" ', 'value', 'text', (int)$configs->allow_multiple_timezones);
		
		$options = array() ;
		$options[] = JHTML::_('select.option', 1, JText::_('OS_CONFIGURATION_YES'));
		$options[] = JHTML::_('select.option', 0, JText::_('OS_CONFIGURATION_NO'));
		$lists['disable_calendar_in_off_date'] = JHTML::_('select.genericlist', $options, 'disable_calendar_in_off_date', ' class="input-small" ', 'value', 'text', (int)$configs->disable_calendar_in_off_date);
		
		$options = array() ;
		$options[] = JHTML::_('select.option', 1, JText::_('OS_CONFIGURATION_YES'));
		$options[] = JHTML::_('select.option', 0, JText::_('OS_CONFIGURATION_NO'));
		$lists['use_js_popup'] = JHTML::_('select.genericlist', $options, 'use_js_popup', ' class="input-small" ', 'value', 'text', (int)$configs->use_js_popup);
		
		
		$options = array() ;
		$options[] = JHTML::_('select.option', 1, JText::_('OS_CONFIGURATION_YES'));
		$options[] = JHTML::_('select.option', 0, JText::_('OS_CONFIGURATION_NO'));
		$lists['show_dropdown_month_year'] = JHTML::_('select.genericlist', $options, 'show_dropdown_month_year', ' class="input-small" ', 'value', 'text', (int)$configs->show_dropdown_month_year);
		
		$options = array() ;
		$options[] = JHTML::_('select.option', 1, JText::_('OS_CONFIGURATION_YES'));
		$options[] = JHTML::_('select.option', 0, JText::_('OS_CONFIGURATION_NO'));
		$lists['using_cart'] = JHTML::_('select.genericlist', $options, 'using_cart', ' class="input-small" ', 'value', 'text', (int)$configs->using_cart);
		
		$options = array() ;
		$options[] = JHTML::_('select.option', 1, JText::_('OS_CONFIGURATION_YES'));
		$options[] = JHTML::_('select.option', 0, JText::_('OS_CONFIGURATION_NO'));
		$lists['allow_registration'] = JHTML::_('select.genericlist', $options, 'allow_registration', ' class="input-small" ', 'value', 'text', (int)$configs->allow_registration);
		
		$options = array() ;
		$options[] = JHTML::_('select.option', 1, JText::_('OS_CONFIGURATION_YES'));
		$options[] = JHTML::_('select.option', 0, JText::_('OS_CONFIGURATION_NO'));
		$lists['allow_cancel_request'] = JHTML::_('select.genericlist', $options, 'allow_cancel_request', ' class="input-small" ', 'value', 'text', (int)$configs->allow_cancel_request);
		
		$options = array() ;
		$options[] = JHTML::_('select.option', 0, JText::_('OS_CONFIGURATION_NO'));
		$options[] = JHTML::_('select.option', 1, JText::_('OS_CONFIGURATION_YES'));
		$lists['enable_acymailing'] = JHTML::_('select.genericlist', $options, 'enable_acymailing', ' class="input-small" ', 'value', 'text', (int)$configs->enable_acymailing);
		
		$options = array() ;
		$options[] = JHTML::_('select.option', 0, JText::_('OS_CONFIGURATION_NO'));
		$options[] = JHTML::_('select.option', 1, JText::_('OS_CONFIGURATION_YES'));
		$lists['integrate_user_profile'] = JHTML::_('select.genericlist', $options, 'integrate_user_profile', ' class="input-small" ', 'value', 'text', (int)$configs->integrate_user_profile);
		
		$options = array() ;
		$options[] = JHTML::_('select.option', 0, JText::_('OS_CONFIGURATION_NO'));
		$options[] = JHTML::_('select.option', 1, JText::_('OS_CONFIGURATION_YES'));
		$lists['show_calendar_box'] = JHTML::_('select.genericlist', $options, 'show_calendar_box', ' class="input-small" ', 'value', 'text', (int)$configs->show_calendar_box);
		
		$options = array() ;
		$options[] = JHTML::_('select.option', 0, JText::_('OS_CONFIGURATION_NO'));
		$options[] = JHTML::_('select.option', 1, JText::_('OS_CONFIGURATION_YES'));
		$lists['early_bird'] = JHTML::_('select.genericlist', $options, 'early_bird', ' class="input-small" ', 'value', 'text', (int)$configs->early_bird);
		
		$options = array() ;
		$options[] = JHTML::_('select.option', 0, JText::_('OS_CONFIGURATION_NO'));
		$options[] = JHTML::_('select.option', 1, JText::_('OS_CONFIGURATION_YES'));
		$lists['enable_slots_discount'] = JHTML::_('select.genericlist', $options, 'enable_slots_discount', ' class="input-small" ', 'value', 'text', (int)$configs->enable_slots_discount);
		
		$options = array() ;
		$options[] = JHTML::_('select.option', 0, JText::_('OS_CONFIGURATION_NO'));
		$options[] = JHTML::_('select.option', 1, JText::_('OS_CONFIGURATION_YES'));
		$lists['show_number_timeslots_booking'] = JHTML::_('select.genericlist', $options, 'show_number_timeslots_booking', ' class="input-small" ', 'value', 'text', (int)$configs->show_number_timeslots_booking);
		
		$options = array() ;
		$options[] = JHTML::_('select.option', 0, JText::_('OS_CONFIGURATION_NO'));
		$options[] = JHTML::_('select.option', 1, JText::_('OS_CONFIGURATION_YES'));
		$lists['hidetabs'] = JHTML::_('select.genericlist', $options, 'hidetabs', ' class="input-small" ', 'value', 'text', (int)$configs->hidetabs);
		
		$options = array() ;
		$options[] = JHTML::_('select.option', 0, JText::_('OS_CONFIGURATION_NO'));
		$options[] = JHTML::_('select.option', 1, JText::_('OS_CONFIGURATION_YES'));
		$lists['employee_bar'] = JHTML::_('select.genericlist', $options, 'employee_bar', ' class="input-small" ', 'value', 'text', (int)$configs->employee_bar);
		
		$options = array() ;
		$options[] = JHTML::_('select.option', 0, JText::_('OS_CONFIGURATION_NO'));
		$options[] = JHTML::_('select.option', 1, JText::_('OS_CONFIGURATION_YES'));
		$lists['service_information'] = JHTML::_('select.genericlist', $options, 'service_information', ' class="input-small" ', 'value', 'text', (int)$configs->service_information);
		
		$options = array() ;
		$options[] = JHTML::_('select.option', 0, JText::_('OS_CONFIGURATION_NO'));
		$options[] = JHTML::_('select.option', 1, JText::_('OS_CONFIGURATION_YES'));
		$lists['show_employee_cost'] = JHTML::_('select.genericlist', $options, 'show_employee_cost', ' class="input-small" ', 'value', 'text', (int)$configs->show_employee_cost);
		
		$options = array() ;
		$options[] = JHTML::_('select.option', 0, JText::_('OS_DEFAULT_LAYOUT'));
		$options[] = JHTML::_('select.option', 1, JText::_('OS_SIMPLER_LAYOUT'));
		$lists['using_layout'] = JHTML::_('select.genericlist', $options, 'using_layout', ' class="input-large" ', 'value', 'text', (int)$configs->using_layout);
		
		$options = array() ;
		$options[] = JHTML::_('select.option', 0, JText::_('OS_USINGTAB'));
		$options[] = JHTML::_('select.option', 1, JText::_('OS_USINGSELECTLIST'));
		$lists['usingtab'] = JHTML::_('select.genericlist', $options, 'usingtab', ' class="input-large" ', 'value', 'text', (int)$configs->usingtab);

        $options = array() ;
        $options[] = JHTML::_('select.option', 0, JText::_('OS_RADIO_THEME'));
        $options[] = JHTML::_('select.option', 1, JText::_('OS_SIMPLE_THEME'));
        $lists['booking_theme'] = JHTML::_('select.genericlist', $options, 'booking_theme', ' class="input-medium" ', 'value', 'text', (int)$configs->booking_theme);

        $options = array() ;
        $options[] = JHTML::_('select.option', 0, JText::_('OS_CONFIGURATION_NO'));
        $options[] = JHTML::_('select.option', 1, JText::_('OS_CONFIGURATION_YES'));
        $lists['use_qrcode'] = JHTML::_('select.genericlist', $options, 'use_qrcode', ' class="input-medium" ', 'value', 'text', (int)$configs->use_qrcode);

        $options = array() ;
        $options[] = JHTML::_('select.option', 0, JText::_('OS_CONFIGURATION_NO'));
        $options[] = JHTML::_('select.option', 1, JText::_('OS_CONFIGURATION_YES'));
        $lists['show_details_and_orders'] = JHTML::_('select.genericlist', $options, 'show_details_and_orders', ' class="input-medium" ', 'value', 'text', (int)$configs->show_details_and_orders);
		
		$options = array() ;
		$options[] = JHTML::_('select.option', 1, JText::_('OS_CONFIGURATION_YES'));
		$options[] = JHTML::_('select.option', 0, JText::_('OS_CONFIGURATION_NO'));
		$lists['show_tax_in_cart'] = JHTML::_('select.genericlist', $options, 'show_tax_in_cart', ' class="input-small" ', 'value', 'text', (int)$configs->show_tax_in_cart);

		HTML_OSappscheduleconfiguration::configuration_list($option,$configs,$lists);
	}
	
	/**
	 * save service
	 *
	 * @param unknown_type $option
	 */
	function configuration_save($option,$save){
		global $mainframe;
		$mainframe = JFactory::getApplication();
		$db = JFactory::getDbo();
		
		$post = JRequest::get('post',JREQUEST_ALLOWRAW);
		unset($post['option']);
		unset($post['task']);		
		
		$sql = 'TRUNCATE TABLE #__app_sch_configuation';
		$db->setQuery($sql);
		$db->query();
		
		foreach ($post as $key=>$value) {
			if (is_array($value)) $value = implode(',',$value);
			$configKey = $db->Quote($key);
			$configValue = $db->Quote($value);
			$sql = "INSERT INTO #__app_sch_configuation (config_key, config_value) VALUES($configKey,$configValue)";			
			$db->setQuery($sql);
			$db->query();
		}
	 	
		$enable_cardtypes = JRequest::getVar('enable_cardtypes');
		if(count($payments) > 0){
			$payments  = implode(",",$payments);
			$db->setQuery("Update #__app_sch_configuation set config_value = '$enable_cardtypes' where config_key like 'enable_cardtypes'");
			$db->query();
		}
		
		$enable_tax = Jrequest::getVar('enable_tax',0);
		if($enable_tax == 0){
			$db->setQuery("UPDATE #__app_sch_configuation SET config_value = '' WHERE config_key LIKE 'tax_payment'");
			$db->query();
		}
		
		$db->setQuery("Delete from #__app_sch_urls");
		$db->query();
		
		if($save == 1){
			$mainframe->redirect("index.php?option=com_osservicesbooking&task=cpanel_list",JText::_('OS_CONFIGURATION_SAVE'));
		}else{
			$mainframe->enqueueMessage(JText::_('OS_CONFIGURATION_SAVE'),'message');
			OSappscheduleconfiguration::configuration_list($option);
		}
	}
	
	function get_tz_options($selectedzone,$item){
	    echo '<select name="timezone'.$item.'" class="input-large">';
	    echo '<option value=""></option>';
	    echo self::timezonechoice($selectedzone);
	    echo '</select>';
	}
	
	public static function timezonechoice($selectedzone) {
	    $all = timezone_identifiers_list();
	    $i = 0;
	    foreach($all AS $zone) {
	        $zone = explode('/',$zone);
	        $zonen[$i]['continent'] = isset($zone[0]) ? $zone[0] : '';
	        $zonen[$i]['city'] = isset($zone[1]) ? $zone[1] : '';
	        $zonen[$i]['subcity'] = isset($zone[2]) ? $zone[2] : '';
	        $i++;
	    }
	
	    asort($zonen);
	    $structure = '';
	    foreach($zonen AS $zone) {
	        extract($zone);
	        if($continent == 'Africa' || $continent == 'America' || $continent == 'Antarctica' || $continent == 'Arctic' || $continent == 'Asia' || $continent == 'Atlantic' || $continent == 'Australia' || $continent == 'Europe' || $continent == 'Indian' || $continent == 'Pacific') {
	        	if(!isset($selectcontinent)) {
	          		$structure .= '<optgroup label="'.$continent.'">'; // continent
	        	} elseif($selectcontinent != $continent) {
	          		$structure .= '</optgroup><optgroup label="'.$continent.'">'; // continent
	       		}
	
		        if(isset($city) != ''){
		          if (!empty($subcity) != ''){
		            $city = $city . '/'. $subcity;
		          }
		          $structure .= "<option ".((($continent.'/'.$city)==$selectedzone)?'selected="selected "':'')." value=\"".($continent.'/'.$city)."\">".str_replace('_',' ',$city)."</option>"; //Timezone
		        } else {
		          if (!empty($subcity) != ''){
		            $city = $city . '/'. $subcity;
		          }
		          $structure .= "<option ".(($continent==$selectedzone)?'selected="selected "':'')." value=\"".$continent."\">".$continent."</option>"; //Timezone
		        }
		        $selectcontinent = $continent;
	      	}
	    }
	    $structure .= '</optgroup>';
	    return $structure;
	}
}
?>