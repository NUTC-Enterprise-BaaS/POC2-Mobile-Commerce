<?php
/*------------------------------------------------------------------------
# configuration.html.php - Ossolution Services Booking
# ------------------------------------------------------------------------
# author    Ossolution team
# copyright Copyright (C) 2015 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/
// no direct access
defined('_JEXEC') or die;


class HTML_OSappscheduleConfiguration{
	/**
	 * Extra field list HTML
	 *
	 * @param unknown_type $option
	 * @param unknown_type $rows
	 * @param unknown_type $pageNav
	 * @param unknown_type $lists
	 */
	function configuration_list($option,$configs,$lists){
		global $mainframe,$_jversion,$configClass;
		
		JHtml::_('behavior.multiselect');
		$editor = JFactory::getEditor();
	    JHTML::_('behavior.modal');
		JHTML::_('behavior.tooltip');
		JToolBarHelper::title(JText::_('OS_CONFIGURATION_CONFIGURATION'),'cog');
		JToolBarHelper::save('configuration_save');
		JToolBarHelper::apply('configuration_apply');
		JToolBarHelper::cancel('configuration_cancel');
		JToolbarHelper::preferences('com_osservicesbooking');
	?>
		<form method="POST" action="index.php?option=<?php echo $option; ?>&task=configuration_list" name="adminForm" id="adminForm">
		<div class="row-fluid">
		<ul class="nav nav-tabs">
			<li class="active"><a href="#general-page" data-toggle="tab"><?php echo JText::_('OS_CONFIGURATION_GENERAL');?></a></li>
			<li><a href="#reminder-page" data-toggle="tab"><?php echo JText::_('OS_CONFIGURATION_REMINDER');?></a></li>			
			<li><a href="#booking-page" data-toggle="tab"><?php echo JText::_('OS_CONFIGURATION_BOOKING');?></a></li>
			<li><a href="#formfields" data-toggle="tab"><?php echo JText::_('OS_FORM_FIELDS');?></a></li>
			<li><a href="#invoice-setting" data-toggle="tab"><?php echo JText::_('OS_CONFIGURATION_INVOICE_SETTINGS');?></a></li>
			<li><a href="#clickatell-setting" data-toggle="tab"><?php echo JText::_('OS_SMS_SETTING');?></a></li>
			<li><a href="#layout-setting" data-toggle="tab"><?php echo JText::_('OS_LAYOUT_SETTING');?></a></li>
			<li><a href="#email-marketing" data-toggle="tab"><?php echo JText::_('OS_EMAIL_MARKETING');?></a></li>
		</ul>
		<div class="tab-content">			
			<div class="tab-pane active" id="general-page">
				<table class="admintable adminform">
					<tr>
						<td class="key">
							<span class="editlinktip hasTip" title="<?php echo JText::_('OS_CONFIGURATION_BUSINESS_NAME')?>::<?php echo JText::_('OS_CONFIGURATION_BUSINESS_NAME_DESC')?>">
								<?php echo JText::_('OS_CONFIGURATION_BUSINESS_NAME')?>
							</span>
						</td>
						<td >
							<input type="text" class="inputbox" size="40" name="business_name" id="business_name" value="<?php echo $configs->business_name;?>">
						</td>
					</tr>
					<tr>
						<td class="key">
							<span class="editlinktip hasTip" title="<?php echo JText::_('OS_CONFIGURATION_EMAIL_NOTIFICATION')?>::<?php echo JText::_('OS_CONFIGURATION_EMAIL_NOTIFICATION_DESC')?>">
								<?php echo JText::_('OS_CONFIGURATION_EMAIL_NOTIFICATION')?>
							</span>
							
						</td>
						<td ><input class="inputbox" type="text" size="40" name="value_string_email_address" value="<?php echo $configs->value_string_email_address?>"></td>
					</tr>
					<tr>
						<td class="key">
							<span class="editlinktip hasTip" title="<?php echo JText::_('OS_CONFIGURATION_MOBILE_NOTIFICATION')?>::<?php echo JText::_('OS_CONFIGURATION_MOBILE_NOTIFICATION_DESC')?>">
								<?php echo JText::_('OS_CONFIGURATION_MOBILE_NOTIFICATION')?>
							</span>
							
						</td>
						<td ><input class="input-small" type="text" size="40" name="mobile_notification" value="<?php echo $configs->mobile_notification?>"></td>
					</tr>
					<tr>
						<td class="key"><?php echo JText::_('OS_CONFIGURATION_DATE_TIME_FORMAT')?></td>
						<td ><?php echo $lists['date_time_format']?></td>
					</tr>
					<tr>
						<td class="key"><?php echo JText::_('OS_CONFIGURATION_DATE_FORMAT')?></td>
						<td ><?php echo $lists['date_format'] ?></td>
						<td><?php echo JText::_('OS_CONFIGURATION_DATE_FORMAT_DESC')?></td>
					</tr>
					<tr>
						<td class="key"><?php echo JText::_('OS_CONFIGURATION_TIME_FORMAT')?></td>
						<td ><?php echo $lists['time_format'] ?></td>
					</tr>
					<?php
					if (version_compare(JVERSION, '3.0', 'ge')){
					?>
					<tr>
						<td class="key" valign="top">
							<span class="editlinktip hasTip" title="<?php echo JText::_('Load Bootstrap Twitter')?>">
								<?php echo JText::_('Load Bootstrap Twitter')?>
							</span>
						</td>
						<td >
							<?php echo $lists['load_bootstrap'];?>
						</td>
					</tr>
					<?php
					}
					?>
					<tr>
						<td class="key">
							<span class="editlinktip hasTip" title="<?php echo JText::_('OS_CSV_SEPARATER')?>::<?php echo JText::_('OS_CSV_SEPARATER_EXPLAIN')?>">
								<?php echo JText::_('OS_CSV_SEPARATER')?>
							</span>
						</td>
						<td >
							<?php echo $lists['csv_separator'] ?>
						</td>
					</tr>
					<tr>
						<td class="key">
						<span class="editlinktip hasTip" title="<?php echo JText::_('OS_CONFIGURATION_CURRENCY')?>::<?php echo JText::_('OS_CONFIGURATION_CURRENCY_DESC')?>">
							<?php echo JText::_('OS_CONFIGURATION_CURRENCY')?>
						</span>
						</td>
						<td ><?php echo $lists['currency_format']?></td>
					</tr>
					<tr>
						<td class="key">							
							<span class="editlinktip hasTip" title="<?php echo JText::_('OS_CURRENCY_SYMBOL_POSITION')?>">
								<?php echo JText::_('OS_CURRENCY_SYMBOL_POSITION')?>
							</span>
						</td>
						<td >
							<?php echo $lists['currency_symbol_position'] ?>
						</td>
					</tr>
					<tr>
						<td class="key">
							<span class="editlinktip hasTip" title="<?php echo JText::_('Show User Timezone')?>::<?php echo JText::_('Do you want to show <B>Time slots</B> in User Timezone.')?>">
								<?php echo JText::_('Show User Timezone')?>
							</span>
						</td>
						<td >
							<?php echo $lists['allow_multiple_timezones'] ?>
						</td>
					</tr>
					<tr>
						<td  class="key" width="20%" valign="top">
							<?php echo JText::_('OS_ACTIVE_OTHER_TIMEZONES');?>
						</td>
						<td width="30%" valign="top">
							<?php
							for($i=1;$i<=5;$i++){
								echo "Timezone #".$i." ";
								OSappscheduleConfiguration::get_tz_options($configClass['timezone'.$i],$i);
								echo "<BR />";
							}
							?>
						</td>
						<td width="50%" valign="top">
							<?php echo JText::_('OS_ACTIVE_OTHER_TIMEZONES_EXPLAIN');?>
							<BR /><BR />
							<img src="<?php echo JURI::base()?>components/com_osservicesbooking/asset/images/tooltip.png"  style="border:2px solid red; "/>
						</td>
					</tr>
					<tr>
						<td class="key" valign="top">
							<span class="editlinktip hasTip" title="<?php echo JText::_('OS_INTEGRATE_GOOGLE_CALENDAR')?>::<?php echo JText::_('OS_INTEGRATE_GOOGLE_CALENDAR_EXPLAIN')?>">
								<?php echo JText::_('OS_INTEGRATE_GOOGLE_CALENDAR')?>
							</span>
						</td>
						<td ><?php echo $lists['integrate_gcalendar'] ?>
						<div class="clearfix"></div>
						<?php
						jimport('joomla.filesystem.folder');
						if(($configs->integrate_gcalendar == 1) and (!JFolder::exists(JPATH_ROOT."/components/com_osservicesbooking/google-api-php-client-master"))){
						?>
						<span class="label label-important">
						In case you integrate OSB with Google Calendar,
						whenever customers make a booking request in your site, the GCalendar of employee will be added new event.
						Administrator have to enter the Google account for each employee.
						<BR />
						Google API must be installed on your server.
						</span>
						<div class="clearfix"></div>
						<?php
						}
						?>
						<span style="font-size:11px;color:red;">
							In case you integrate OSB with Google Calendar,
							whenever customers make a booking request in your site, the GCalendar of employee will be added new event.
							Administrator have to enter the Google account for each employee.
							<BR />
							Google API V3 must be installed on your server.
							<BR />
							You can download Google API V3 from <a href="https://github.com/google/google-api-php-client" target="_blank">here</a>
						</span>
						</td>
					</tr>
					<tr>
						<td class="key">
							<span class="editlinktip hasTip" title="<?php echo JText::_('OS_GCALENDAR_WIDTH')?>::<?php echo JText::_('OS_GCALENDAR_WIDTH_DESC')?>">
								<?php echo JText::_('OS_GCALENDAR_WIDTH')?>
							</span>
						</td>
						<td ><input class="input-mini" type="text" size="4" name="gcalendar_width" value="<?php echo $configs->gcalendar_width?>"></td>
					</tr>
					<tr>
						<td class="key">
							<span class="editlinktip hasTip" title="<?php echo JText::_('OS_GCALENDAR_HEIGHT')?>::<?php echo JText::_('OS_GCALENDAR_HEIGHT_DESC')?>">
								<?php echo JText::_('OS_GCALENDAR_HEIGHT')?>
							</span>
						</td>
						<td ><input class="input-mini" type="text" size="4" name="gcalendar_height" value="<?php echo $configs->gcalendar_height?>"></td>
					</tr>
					<tr>
						<td class="key" valign="top">
							<span class="editlinktip hasTip" title="<?php echo JText::_('OS_CONFIGURATION_FOOTER_CONTENT')?>">
								<?php echo JText::_('OS_CONFIGURATION_FOOTER_CONTENT')?>
							</span>
						</td>
						<td >
							<textarea name="footer_content" id="footer_content" cols="40" rows="5"><?php echo $configs->footer_content?></textarea>
						</td>
					</tr>
					<tr>
						<td class="key" valign="top">
							<span class="editlinktip hasTip" title="<?php echo JText::_('OS_CONFIGURATION_META_KEYWORDS')?>">
								<?php echo JText::_('OS_CONFIGURATION_META_KEYWORDS')?>
							</span>
						</td>
						<td >
							<textarea name="meta_keyword" id="meta_keyword" cols="40" rows="2"><?php echo $configs->meta_keyword?></textarea>
						</td>
					</tr>
					<tr>
						<td class="key" valign="top">
							<span class="editlinktip hasTip" title="<?php echo JText::_('OS_CONFIGURATION_META_DESC')?>">
								<?php echo JText::_('OS_CONFIGURATION_META_DESC')?>
							</span>
						</td>
						<td >
							<textarea name="meta_desc" id="meta_desc" cols="40" rows="4"><?php echo $configs->meta_desc?></textarea>
						</td>
					</tr>
				</table>
			</div>
			<div class="tab-pane" id="reminder-page">
				<table width="100%" class="admintable">
					<tr>
						<td class="key">
							<span class="editlinktip hasTip" title="<?php echo JText::_('OS_CONFIGURATION_ENABLE_NOTIFICATION')?>::<?php echo JText::_('OS_CONFIGURATION_ENABLE_NOTIFICATION_DESC')?>">
								<?php echo JText::_('OS_CONFIGURATION_ENABLE_NOTIFICATION')?>
							</span>
						</td>
						<td ><?php echo $lists['value_sch_reminder_enable'] ?></td>
					</tr>
					<tr>
						<td class="key">
							<span class="editlinktip hasTip" title="<?php echo JText::_('OS_CONFIGURATION_EMAIL_SEND_REMINDER')?>::<?php echo JText::_('OS_CONFIGURATION_EMAIL_SEND_REMINDER_DESC')?>">
								<?php echo JText::_('OS_CONFIGURATION_EMAIL_SEND_REMINDER')?>
							</span>
						</td>
						<td >
							<input class="input-mini" type="text" size="4" name="value_sch_reminder_email_before" value="<?php echo $configs->value_sch_reminder_email_before?>">
							<?php echo JText::_('OS_CONFIGURATION_HOURS_BEFORE')?>
						</td>
					</tr>
					<tr>
						<td class="key" valign="top" width="25%"><?php echo JText::_('Cron task')?></td>
						<td colspan="2" width="75%">
							Live link: <?php echo JURI::root()?>components/com_osservicesbooking/cron.php
							<BR />
							Real path: <?php echo JPATH_ROOT.DS."components".DS."com_osservicesbooking".DS."cron.php";?>
							<BR />
							<font color='Red'>
							<?php echo JText::_('You need to set up a cron job using your hosting account control panel which should execute every hour or every several minutes. Depending on your web server you should use either the live link or real path.
	')?>
							</font>
						</td>
					</tr>
				</table>
			</div>
			<div class="tab-pane" id="booking-page">
				<table  class="admintable adminform">
					<tr>
						<td class="key">
							<span class="editlinktip hasTip" title="<?php echo JText::_('OS_EMPLOYEE_ACL_GROUP')?>::<?php echo JText::_('OS_EMPLOYEE_ACL_GROUP_EXPLAIN')?>">
								<?php echo JText::_('OS_EMPLOYEE_ACL_GROUP')?>
							</span>
						</td>
						<td ><?php echo $lists['employee_acl_group'] ?></td>
					</tr>
					<tr>
						<td class="key">
							<span class="editlinktip hasTip" title="<?php echo JText::_('Employee can change availability status')?>::<?php echo JText::_('Do you allow employee to change their availability status')?>">
								<?php echo JText::_('Employee can change availability status')?>
							</span>
						</td>
						<td ><?php echo $lists['employee_change_availability'] ?></td>
					</tr>
					<tr>
						<td class="key">
							<span class="editlinktip hasTip" title="In case your service has more than one employee, do you want to disable timeslot of service when one of employees is booked ?">
								<?php echo JText::_('Disable timeslot of service when one of employees is booked')?>
							</span>
						</td>
						<td ><?php echo $lists['disable_timeslot'] ?></td>
					</tr>
					<tr>
						<td class="key">
							<span class="editlinktip hasTip" title="<?php echo JText::_('OS_MULTIPLE_WORK')?>::<?php echo JText::_('OS_MULTIPLE_WORK_DESC')?>">
								<?php echo JText::_('OS_MULTIPLE_WORK')?>
							</span>
						</td>
						<td ><?php echo $lists['multiple_work'] ?></td>
					</tr>
					
					<tr>
						<td class="key">
							<span class="editlinktip hasTip" title="<?php echo JText::_('OS_ONLY_ALLOW_REGISTERED')?>::<?php echo JText::_('OS_ONLY_ALLOW_REGISTERED_EXPLAIN')?>">
								<?php echo JText::_('OS_ONLY_ALLOW_REGISTERED')?>
							</span>
						</td>
						<td ><?php echo $lists['allow_registered_only'] ?></td>
					</tr>
					<tr>
						<td class="key">
							<span class="editlinktip hasTip" title="<?php echo JText::_('OS_SHOW_REGISTER_FORM')?>::<?php echo JText::_('OS_SHOW_REGISTER_FORM_EXPLAIN')?>">
								<?php echo JText::_('OS_SHOW_REGISTER_FORM')?>
							</span>
						</td>
						<td ><?php echo $lists['allow_registration'] ?></td>
					</tr>
					<tr>
						<td class="key">
							<span class="editlinktip hasTip" title="<?php echo JText::_('OS_INTEGRATE_USER_PROFILE')?>::<?php echo JText::_('OS_INTEGRATE_USER_PROFILE_DESC')?>">
								<?php echo JText::_('OS_INTEGRATE_USER_PROFILE')?>
							</span>
						</td>
						<td ><?php echo $lists['integrate_user_profile'] ?></td>
					</tr>
					<tr>
						<td class="key">
							<span class="editlinktip hasTip" title="<?php echo JText::_('OS_SELECT_SPECIAL_GROUP')?>::<?php echo JText::_('OS_SELECT_SPECIAL_GROUP_EXPLAIN')?>">
								<?php echo JText::_('OS_SELECT_SPECIAL_GROUP')?>
							</span>
						</td>
						<td ><?php echo $lists['group_payment'] ?></td>
					</tr>
					<tr>
						<td class="key">
							<span class="editlinktip hasTip" title="<?php echo JText::_('OS_CONFIGURATION_STEP')?>::<?php echo JText::_('OS_CONFIGURATION_STEP_DESC')?>">
								<?php echo JText::_('OS_CONFIGURATION_STEP')?>
							</span>
						</td>
						<td ><?php echo $lists['step_format'] ?></td>
					</tr>
					<tr>
						<td class="key">
							<span class="editlinktip hasTip" title="<?php echo JText::_('OS_CONFIGURATION_DISABLE_PAYMENTS')?>::<?php echo JText::_('OS_CONFIGURATION_DISABLE_PAYMENTS_DESC')?>">
								<?php echo JText::_('OS_CONFIGURATION_DISABLE_PAYMENTS')?>
							</span>
						</td>
						<td ><?php echo $lists['disable_payments']?></td>
					</tr>
					<tr>
						<td class="key">
							<span class="editlinktip hasTip" title="<?php echo JText::_('OS_ENABLE_TAX')?>">
								<?php echo JText::_('OS_ENABLE_TAX')?>
							</span>
						</td>
						<td >
							<?php echo $lists['enable_tax'];?>
						</td>
					</tr>
					<tr>
						<td class="key">
							<span class="editlinktip hasTip" title="<?php echo JText::_('OS_CONFIGURATION_TAX_PAYMENT')?>::<?php echo JText::_('OS_CONFIGURATION_TAX_PAYMENT_DESC')?>">
								<?php echo JText::_('OS_CONFIGURATION_TAX_PAYMENT')?>
							</span>
						</td>
						<td ><input class="input-mini" type="text" size="4" name="tax_payment" value="<?php echo $configs->tax_payment?>"></td>
					</tr>
					<tr>
						<td class="key">
							<span class="editlinktip hasTip" title="<?php echo JText::_('OS_CONFIGURATION_DEPOSIT_PAYMENT')?>::<?php echo JText::_('OS_CONFIGURATION_DEPOSIT_PAYMENT_DESC')?>">
								<?php echo JText::_('OS_CONFIGURATION_DEPOSIT_PAYMENT')?>
							</span>
						</td>
						<td >
							<input class="input-mini" type="text" size="4" name="deposit_payment" value="<?php echo $configs->deposit_payment?>">
						</td>
					</tr>
					<tr>
						<td class="key">
							<span class="editlinktip hasTip" title="<?php echo JText::_('OS_ENABLE_EARLY_BIRD')?>::<?php echo JText::_('OS_ENABLE_EARLY_BIRD_DESC')?>">
								<?php echo JText::_('OS_ENABLE_EARLY_BIRD')?>
							</span>
						</td>
						<td >
							<?php echo $lists['early_bird'];?>
						</td>
					</tr>
					<tr>
						<td class="key">
							<span class="editlinktip hasTip" title="<?php echo JText::_('OS_ENABLE_SLOTS_DISCOUNT')?>::<?php echo JText::_('OS_ENABLE_EARLY_BIRD_DESC')?>">
								<?php echo JText::_('OS_ENABLE_SLOTS_DISCOUNT')?>
							</span>
						</td>
						<td >
							<?php echo $lists['enable_slots_discount'];?>
						</td>
					</tr>
					<tr>
						<td class="key">
							<span class="editlinktip hasTip" title="<?php echo JText::_('OS_DEFAULT_ORDER_STATUS')?>::<?php echo JText::_('OS_ENABLE_SLOTS_DISCOUNT_DESC')?>">
								<?php echo JText::_('OS_DEFAULT_ORDER_STATUS')?>
							</span>
						</td>
						<td ><?php echo $lists['disable_payment_order_status']?></td>
					</tr>
					<tr>
						<td class="key" valign="top">
							<?php echo JText::_('OS_ENABLE_CARD_TYPES')?>
						</td>
						<td ><?php echo $lists['cardtypes'];?></td>
					</tr>					
					<tr>
						<td class="key">
							<span class="editlinktip hasTip" title="<?php echo JText::_('OS_CONFIGURATION_EMAIL_CONFIRMATION')?>::<?php echo JText::_('OS_CONFIGURATION_EMAIL_CONFIRMATION_DESC')?>">
								<?php echo JText::_('OS_CONFIGURATION_EMAIL_CONFIRMATION')?>
							</span>
						</td>
						<td ><?php echo $lists['value_enum_email_confirmation'] ?></td>
					</tr>
					<tr>
						<td class="key">
							
							<span class="editlinktip hasTip" title="<?php echo JText::_('OS_CONFIGURATION_EMAIL_SEND_PAYMENTS')?>::<?php echo JText::_('OS_CONFIGURATION_EMAIL_SEND_PAYMENTS_DESC')?>">
								<?php echo JText::_('OS_CONFIGURATION_EMAIL_SEND_PAYMENTS')?>
							</span>
						</td>
						<td >
							<?php echo $lists['value_enum_email_payment'] ?>
						</td>
					</tr>
					<tr>
						<td  class="key">
							<span class="editlinktip hasTip" title="<?php echo JText::_('Allow customers to cancel the booking request')?>::<?php echo JText::_('Do you allow customers to cancel the booking request')?>">
								<?php echo JText::_('Allow customers to cancel the booking request'); ?>
							</span>
						</td>
						<td>
							<?php echo $lists['allow_cancel_request'];?>
						</td>
					</tr>
					<tr>
						<td  class="key">
							<span class="editlinktip hasTip" title="<?php echo JText::_('OS_ALLOW_CANCEL_BOOKING_REQUEST_BEFORE_EXPLAIN')?>::<?php echo JText::_('OS_CANCEL_BOOKING_REQUEST_BEFORE')?>">
								<?php echo JText::_('OS_CANCEL_BOOKING_REQUEST_BEFORE'); ?>
							</span>
						</td>
						<td>
							<input type="text" name="cancel_before" class="input-mini" value="<?php echo $configs->cancel_before ? $configs->cancel_before : 1; ?>" />
							<?php echo JText::_('OS_ALLOW_CANCEL_BOOKING_REQUEST_BEFORE_EXPLAIN1'); ?>
						</td>
					</tr>
                    <tr>
                        <td class="key">
							<span class="editlinktip hasTip" title="<?php echo JText::_('OS_USE_QRCODE_EXPLAIN')?>">
								<?php echo JText::_('OS_USE_QRCODE')?>
							</span>
                        </td>
                        <td >
                            <?php echo $lists['use_qrcode'] ?>
                        </td>
                    </tr>
					<tr>
						<td class="key">
							<span class="editlinktip hasTip" title="<?php echo JText::_('OS_USE_HTTPS')?>::<?php echo JText::_('OS_USE_HTTPS_EXPLAIN')?>">
								<?php echo JText::_('OS_USE_HTTPS')?>
							</span>
						</td>
						<td >
							<?php echo $lists['use_ssl'] ?>
						</td>
					</tr>
				</table>
			</div>
			<div class="tab-pane" id="formfields">
				<table class="admintable adminform" style="width:100%;">
					<tr>
						<td class="key">
							<span class="editlinktip hasTip" title="<?php echo JText::_('OS_CONFIGURATION_PHONE')?>::<?php echo JText::_('OS_CONFIGURATION_PHONE_DESC')?>">
								<?php echo JText::_('OS_CONFIGURATION_PHONE')?>
							</span>
						</td>
						<td ><?php echo $lists['value_sch_include_phone']  ?></td>
					</tr>
					<tr>
						<td class="key">
							<span class="editlinktip hasTip" title="<?php echo JText::_('OS_CONFIGURATION_COUNTRY')?>::<?php echo JText::_('OS_CONFIGURATION_COUNTRY_DESC')?>">
								<?php echo JText::_('OS_CONFIGURATION_COUNTRY')?>
							</span>
						</td>
						<td ><?php echo $lists['value_sch_include_country']  ?></td>
					</tr>
					<tr>
						<td class="key" >
							<span class="editlinktip hasTip" title="<?php echo JText::_('OS_CONFIGURATION_CITY')?>::<?php echo JText::_('OS_CONFIGURATION_CITY_DESC')?>">
								<?php echo JText::_('OS_CONFIGURATION_CITY')?>
							</span>
						</td>
						<td ><?php echo $lists['value_sch_include_city']?></td>
					</tr>
					<tr>
						<td class="key" >
							<span class="editlinktip hasTip" title="<?php echo JText::_('OS_CONFIGURATION_STATE')?>::<?php echo JText::_('OS_CONFIGURATION_STATE_DESC')?>">
								<?php echo JText::_('OS_CONFIGURATION_STATE')?>
							</span>
						</td>
						<td ><?php echo $lists['value_sch_include_state'] ?></td>
					</tr>
					<tr>
						<td class="key">
							<span class="editlinktip hasTip" title="<?php echo JText::_('OS_CONFIGURATION_ZIP')?>::<?php echo JText::_('OS_CONFIGURATION_ZIP_DESC')?>">
								<?php echo JText::_('OS_CONFIGURATION_ZIP')?>
							</span>
						</td>
						<td ><?php echo $lists['value_sch_include_zip'] ?></td>
					</tr>
					<tr>
						<td class="key">
							
							<span class="editlinktip hasTip" title="<?php echo JText::_('OS_CONFIGURATION_ADDRESS')?>::<?php echo JText::_('OS_CONFIGURATION_ADDRESS_DESC')?>">
								<?php echo JText::_('OS_CONFIGURATION_ADDRESS')?>
							</span>
						</td>
						<td ><?php echo $lists['value_sch_include_address'] ?></td>
					</tr>
					<tr>
						<td class="key">
							<span class="editlinktip hasTip" title="<?php echo JText::_('OS_CONFIGURATION_CAPCHA')?>::<?php echo JText::_('OS_CONFIGURATION_CAPCHA_DESC')?>">
								<?php echo JText::_('OS_CONFIGURATION_CAPCHA')?>
							</span>
						</td>
						<td ><?php echo $lists['value_sch_include_captcha'] ?></td>
					</tr>
					<tr>
						<td class="key">
							<span class="editlinktip hasTip" title="<?php echo JText::_('Bypass captcha for registered users')?>::<?php echo JText::_("If set to Yes, registered users won't have to enter captcha code in registration process")?>">
								<?php echo JText::_('Bypass captcha for registered users')?>
							</span>
						</td>
						<td ><?php echo $lists['pass_captcha'] ?></td>
					</tr>
					<tr>
						<td class="key">
							
							<span class="editlinktip hasTip" title="<?php echo JText::_('OS_ENABLE_TERM_AND_CONDITION')?>">
								<?php echo JText::_('OS_ENABLE_TERM_AND_CONDITION')?>
							</span>
						</td>
						<td >
							<?php echo $lists['enable_termandcondition'] ?>
						</td>
					</tr>
					<tr>
						<td class="key">
							
							<span class="editlinktip hasTip" title="<?php echo JText::_('OS_SELECT_ARTICLE')?>">
								<?php echo JText::_('OS_SELECT_ARTICLE')?>
							</span>
						</td>
						<td >
							<?php echo $lists['article_id'] ?>
						</td>
					</tr>
				</table>
			</div>
			<div class="tab-pane" id="invoice-setting">
				<table class="admintable adminform" style="width:100%;">
					<tr>
						<td  class="key" width="10%">
							<span class="editlinktip hasTip" title="<?php echo JText::_('OS_CONFIGURATION_ACTIVATE_INVOICE_FEATURE')?>::<?php echo JText::_('OS_CONFIGURATION_ACTIVATE_INVOICE_FEATURE_DESC')?>">
								<?php echo JText::_('OS_CONFIGURATION_ACTIVATE_INVOICE_FEATURE'); ?>
							</span>
						</td>
						<td width="40%">
							<?php echo $lists['activate_invoice_feature'] ?>
						</td>
						<td>
							<?php echo JText::_('OS_CONFIGURATION_ACTIVATE_INVOICE_FEATURE_EXPLAIN'); ?>
						</td>
					</tr>
					<tr>
						<td  class="key" width="10%">
							<span class="editlinktip hasTip" title="<?php echo JText::_('OS_CONFIGURATION_SEND_INVOICE_TO_ORDER')?>::<?php echo JText::_('OS_CONFIGURATION_SEND_INVOICE_TO_ORDER_DESC')?>">
								<?php echo JText::_('OS_CONFIGURATION_SEND_INVOICE_TO_ORDER'); ?>
							</span>
						</td>
						<td width="40%">
							<?php echo $lists['send_invoice_to_customer']; ?>
						</td>
						<td>
							<?php echo JText::_('OS_CONFIGURATION_SEND_INVOICE_TO_ORDER_EXPLAIN'); ?>
						</td>
					</tr>		
					<tr>
						<td  class="key">
							<span class="editlinktip hasTip" title="<?php echo JText::_('OS_CONFIGURATION_INVOICE_START_NUMBER')?>::<?php echo JText::_('OS_CONFIGURATION_INVOICE_START_NUMBER_DESC')?>">
								<?php echo JText::_('OS_CONFIGURATION_INVOICE_START_NUMBER'); ?>
							</span>
						</td>
						<td>
							<input type="text" name="invoice_start_number" class="input-mini" value="<?php echo $configs->invoice_start_number ? $configs->invoice_start_number : 1; ?>" size="10" />
						</td>
						<td>
							<?php echo JText::_('OS_CONFIGURATION_INVOICE_START_NUMBER_EXPLAIN'); ?>
						</td>
					</tr>
					<tr>
						<td  class="key" style="width:25%">
							<span class="editlinktip hasTip" title="<?php echo JText::_('OS_CONFIGURATION_INVOICE_PREFIX')?>::<?php echo JText::_('OS_CONFIGURATION_INVOICE_PREFIX_DESC')?>">
								<?php echo JText::_('OS_CONFIGURATION_INVOICE_PREFIX'); ?>
							</span>
						</td>
						<td>
							<input type="text" name="invoice_prefix" class="input-mini" value="<?php echo isset($configs->invoice_prefix) ? $configs->invoice_prefix : 'IV'; ?>" size="10" />
						</td>
						<td>
							<?php echo JText::_('OS_CONFIGURATION_INVOICE_PREFIX_DESC'); ?>
						</td>
					</tr>
					<tr>
						<td  class="key" style="width:25%">
							<span class="editlinktip hasTip" title="<?php echo JText::_('OS_CONFIGURATION_INVOICE_NUMBER_LENGTH')?>::<?php echo JText::_('OS_CONFIGURATION_INVOICE_NUMBER_LENGTH_DESC')?>">
								<?php echo JText::_('OS_CONFIGURATION_INVOICE_NUMBER_LENGTH'); ?>
							</span>
						</td>
						<td>
							<input type="text" name="invoice_number_length" class="input-mini" value="<?php echo $configs->invoice_number_length ? $configs->invoice_number_length : 5; ?>" size="10" />
						</td>
						<td >
							<?php echo JText::_('OS_CONFIGURATION_INVOICE_NUMBER_LENGTH_EXPLAIN'); ?>
						</td>
					</tr>																						
					<tr>
						<td class="key" valign="top">
							<span class="editlinktip hasTip" title="<?php echo JText::_('OS_CONFIGURATION_INVOICE_FORMAT')?>::<?php echo JText::_('OS_CONFIGURATION_INVOICE_FORMAT_DESC')?>">
								<?php echo JText::_('OS_CONFIGURATION_INVOICE_FORMAT'); ?>
							</span>
						</td>
						<td colspan="2">
							<?php echo $editor->display( 'invoice_format',  $configs->invoice_format , '100%', '550', '75', '8' ) ;?>
						</td>
						<td>
							&nbsp;
						</td>				
					</tr>
				</table>			
			</div>
			<div class="tab-pane" id="clickatell-setting">
				<table class="admintable adminform" style="width:100%;">
					<tr>
						<td width="40%" valign="top">
							<table class="admintable adminform" style="width:100%;">
								<tr>
									<td  class="key" width="40%" valign="top">
										<span class="editlinktip hasTip" title="<?php echo JText::_('OS_ENABLE_CLICKATELL')?>::<?php echo JText::_('OS_ENABLE_CLICKATELL_EXPLAIN')?>">
											<?php echo JText::_('OS_ENABLE_CLICKATELL'); ?>
										</span>
									</td>
									<td width="60%">
										<?php echo $lists['enable_clickatell'] ?>
										<BR />
										Available for Non-USA. To use <a href="www.clickatell.com" target="_blank">Clickatell.com</a> you need to have an HTTP/S account with them. The values below will be found on your Clickatell.com 'Manage My Products' screen
									</td>
								</tr>
								<tr>
									<td  class="key" width="40%">
										<?php echo JText::_('OS_CLICKATELL_USERNAME'); ?>
									</td>
									<td width="60%">
										<input type="text" class="input-small" name="clickatell_username" id="clickatell_username" value="<?php echo $configs->clickatell_username?>" />
									</td>
								</tr>
								<tr>
									<td  class="key" width="40%">
										<?php echo JText::_('OS_CLICKATELL_PASSWORD'); ?>
									</td>
									<td width="60%">
										<input type="text" class="input-small" name="clickatell_password" id="clickatell_password" value="<?php echo $configs->clickatell_password?>" />
									</td>
								</tr>
								<tr>
									<td  class="key" width="40%">
										<span class="editlinktip hasTip" title="<?php echo JText::_('OS_CLICKATELL_API')?>::<?php echo JText::_('OS_CLICKATELL_API_EXPLAIN')?>">
											<?php echo JText::_('OS_CLICKATELL_API'); ?>
										</span>
									</td>
									<td width="60%">
										<input type="text" class="input-small" name="clickatell_api" id="clickatell_api" value="<?php echo $configs->clickatell_api?>" />
									</td>
								</tr>
								<tr>
									<td  class="key" width="40%">
										<span class="editlinktip hasTip" title="<?php echo JText::_('OS_CLICKATELL_SENDER_ID')?>::<?php echo JText::_('OS_CLICKATELL_SENDER_ID_EXPLAIN')?>">
											<?php echo JText::_('OS_CLICKATELL_SENDER_ID'); ?>
										</span>
									</td>
									<td width="60%">
										<input type="text" class="input-small" name="clickatell_senderid" id="clickatell_senderid" value="<?php echo $configs->clickatell_senderid?>" />
									</td>
								</tr>
							</table>
							<BR />
							<table class="admintable adminform" style="width:100%;">
								<tr>
									<td  class="key" width="40%" valign="top">
										<span class="editlinktip hasTip" title="<?php echo JText::_('OS_ENABLE_EZTEXTING')?>::<?php echo JText::_('OS_ENABLE_EZTEXTING_EXPLAIN')?>">
											<?php echo JText::_('OS_ENABLE_EZTEXTING'); ?>
										</span>
									</td>
									<td width="60%">
										<?php echo $lists['enable_eztexting'] ?>
										<BR />
										Available for USA & Canada ONLY. To use <a href="www.EzTexting.com" target="_blank">EzTexting.com</a> you need to have an account with them. You will need to request 'API access' for OS Services Booking to talk to their service. 
									</td>
								</tr>
								<tr>
									<td class="key" width="40%">
										<?php echo JText::_('OS_EZTEXTING_USERNAME'); ?>
									</td>
									<td width="60%">
										<input type="text" class="input-small" name="eztexting_username" id="eztexting_username" value="<?php echo $configs->eztexting_username?>" />
									</td>
								</tr>
								<tr>
									<td class="key" width="40%">
										<?php echo JText::_('OS_EZTEXTING_PASSWORD'); ?>
									</td>
									<td width="60%">
										<input type="text" class="input-small" name="eztexting_password" id="eztexting_password" value="<?php echo $configs->eztexting_password?>" />
									</td>
								</tr>
							</table>
						</td>
						<td width="60%" valign="top">
							<table class="admintable adminform" style="width:100%;">
								<tr>
									<td  class="key" width="40%">
										<span class="editlinktip hasTip" title="<?php echo JText::_('OS_DEFAULT_DIALING_CODE')?>::<?php echo JText::_('OS_DEFAULT_DIALING_CODE_EXPLAIN')?>">
											<?php echo JText::_('OS_DEFAULT_DIALING_CODE'); ?>
										</span>
									</td>
									<td width="60%">
										<?php echo $lists['dial']; ?>
									</td>
								</tr>
								<tr>
									<td  class="key" width="40%">
										<span class="editlinktip hasTip" title="<?php echo JText::_('OS_SHOW_CODE_LIST')?>::<?php echo JText::_('OS_SHOW_CODE_LIST_EXPLAIN')?>">
											<?php echo JText::_('OS_SHOW_CODE_LIST'); ?>
										</span>
									</td>
									<td width="60%">
										<?php echo $lists['clickatell_showcodelist'] ?>
									</td>
								</tr>
								<tr>
									<td  class="key" width="40%">
										<span class="editlinktip hasTip" title="<?php echo JText::_('OS_ENABLE_UNICODE')?>::<?php echo JText::_('OS_ENABLE_UNICODE_EXPLAIN')?>">
											<?php echo JText::_('OS_ENABLE_UNICODE'); ?>
										</span>
									</td>
									<td width="60%">
										<?php echo $lists['clickatell_enable_unicode'] ?>
									</td>
								</tr>
								<tr>
									<td class="key" width="30%">
										<span class="editlinktip hasTip" title="<?php echo JText::_('OS_NEW_BOOKING')?>::<?php echo JText::_('OS_NEW_BOOKING_EXPLAIN')?>">
											<?php echo JText::_('OS_NEW_BOOKING'); ?> (<?php echo JText::_('OS_FOR_ADMIN'); ?>)
										</span>
									</td>
									<td width="70%">
										<input type="text" class="input-large" name="sms_new_booking_to_admin" id="sms_new_booking_to_admin" value="<?php echo $configs->sms_new_booking_to_admin?>" style="width:400px;" />
									</td>
								</tr>
								<tr>
									<td class="key" width="30%">
										<span class="editlinktip hasTip" title="<?php echo JText::_('OS_NEW_BOOKING')?>::<?php echo JText::_('OS_NEW_BOOKING_EXPLAIN')?>">
											<?php echo JText::_('OS_NEW_BOOKING'); ?> (<?php echo JText::_('OS_FOR_CUSTOMER'); ?>)
										</span>
									</td>
									<td width="70%">
										<input type="text" class="input-large" name="sms_new_booking_to_customer" id="sms_new_booking_to_customer" value="<?php echo $configs->sms_new_booking_to_customer?>" style="width:400px;" />
									</td>
								</tr>
								<tr>
									<td class="key" width="30%">
										<span class="editlinktip hasTip" title="<?php echo JText::_('OS_PAYMENT_COMPLETE')?>::<?php echo JText::_('OS_PAYMENT_COMPLETE_EXPLAIN')?>">
											<?php echo JText::_('OS_PAYMENT_COMPLETE'); ?> (<?php echo JText::_('OS_FOR_ADMIN'); ?>)
										</span>
									</td>
									<td width="70%">
										<input type="text" class="input-large" name="sms_payment_complete_to_admin" id="sms_payment_complete_to_admin" value="<?php echo $configs->sms_payment_complete_to_admin?>" style="width:400px;" />
									</td>
								</tr>
								<tr>
									<td class="key" width="30%">
										<span class="editlinktip hasTip" title="<?php echo JText::_('OS_PAYMENT_COMPLETE')?>::<?php echo JText::_('OS_PAYMENT_COMPLETE_EXPLAIN')?>">
											<?php echo JText::_('OS_PAYMENT_COMPLETE'); ?> (<?php echo JText::_('OS_FOR_CUSTOMER'); ?>)
										</span>
									</td>
									<td width="70%">
										<input type="text" class="input-large" name="sms_payment_complete_to_customer" id="sms_payment_complete_to_customer" value="<?php echo $configs->sms_payment_complete_to_customer;?>" style="width:400px;" />
									</td>
								</tr>
								<tr>
									<td class="key" width="30%">
										<span class="editlinktip hasTip" title="<?php echo JText::_('OS_BOOKING_REMINDER')?>::<?php echo JText::_('OS_BOOKING_REMINDER_EXPLAIN')?>">
											<?php echo JText::_('OS_BOOKING_REMINDER'); ?> (<?php echo JText::_('OS_FOR_CUSTOMER'); ?>)
										</span>
									</td>
									<td width="70%">
										<input type="text" class="input-large" name="sms_reminder_notification" id="sms_reminder_notification" value="<?php echo $configs->sms_reminder_notification?>" style="width:400px;" />
									</td>
								</tr>
								<tr>
									<td class="key" width="30%">
										<span class="editlinktip hasTip" title="<?php echo JText::_('OS_BOOKING_CANCELLED')?>::<?php echo JText::_('OS_BOOKING_CANCELLED_EXPLAIN')?>">
											<?php echo JText::_('OS_BOOKING_CANCELLED'); ?> (<?php echo JText::_('OS_FOR_ADMIN'); ?>)
										</span>
									</td>
									<td width="70%">
										<input type="text" class="input-large" name="sms_order_cancelled_notification" id="sms_order_cancelled_notification" value="<?php echo $configs->sms_order_cancelled_notification?>" style="width:400px;" />
									</td>
								</tr>
								<tr>
									<td class="key" width="30%">
										<span class="editlinktip hasTip" title="<?php echo JText::_('OS_ORDER_STATUS_CHANGED')?>::<?php echo JText::_('OS_ORDER_STATUS_CHANGED_EXPLAIN')?>">
											<?php echo JText::_('OS_ORDER_STATUS_CHANGED'); ?> (<?php echo JText::_('OS_FOR_CUSTOMER'); ?>)
										</span>
									</td>
									<td width="70%">
										<input type="text" class="input-large" name="order_status_changed_to_customer" id="order_status_changed_to_customer" value="<?php echo $configs->order_status_changed_to_customer?>" style="width:400px;" />
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</div>
			<div class="tab-pane" id="layout-setting">
				<table class="admintable adminform" style="width:100%;">
					<tr>
						<td class="key">
							<span class="editlinktip hasTip" title="<?php echo JText::_('Do you want to show Tax amount in cart')?>">
								<?php echo JText::_('Show Tax in Cart')?>
							</span>
						</td>
						<td colspan="2">
							<?php echo $lists['show_tax_in_cart'] ?>
						</td>
					</tr>
					<tr>
						<td class="key">
							<span class="editlinktip hasTip" title="<?php echo JText::_('Disable Calendar on Non Available Employees date')?>::<?php echo JText::_('Disable Calendar on Non Available Employees date')?>">
								<?php echo JText::_('Disable Calendar on Non Available Employees date')?>
							</span>
						</td>
						<td colspan="2">
							<?php echo $lists['disable_calendar_in_off_date'] ?>
						</td>
					</tr>
					<tr>
						<td class="key">
							<span class="editlinktip hasTip" title="<?php echo JText::_('Show Occupied Time Slots')?>::<?php echo JText::_('Do you want to show Occupied Time slots in Booking table')?>">
								<?php echo JText::_('Show Occupied Time Slots')?>
							</span>
						</td>
						<td colspan="2">
							<?php echo $lists['show_occupied'] ?>
						</td>
					</tr>
					<tr>
						<td class="key">
							<span class="editlinktip hasTip" title="<?php echo JText::_('Show JS Popup at Front-end')?>::<?php echo JText::_('Do you want to show JS Popup at front-end of component?')?>">
								<?php echo JText::_('Show JS Popup at Front-end')?>
							</span>
						</td>
						<td colspan="2">
							<?php echo $lists['use_js_popup'] ?>
						</td>
					</tr>
					<tr>
						<td  class="key" width="40%">
							<span class="editlinktip hasTip" title="<?php echo JText::_('Using Cart box')?>::<?php echo JText::_('Do you want to use Cart Box')?>">
								<?php echo JText::_('Using Cart box'); ?>
							</span>
						</td>
						<td colspan="2">
							<?php echo $lists['using_cart']; ?>
						</td>
					</tr>
					<tr>
						<td  class="key" width="40%">
							<span class="editlinktip hasTip" title="<?php echo JText::_('OS_SHOW_CALENDARBOX_IN_CONFIRMPAGE')?>::<?php echo JText::_('OS_SHOW_CALENDARBOX_IN_CONFIRMPAGE_EXPLAIN')?>">
								<?php echo JText::_('OS_SHOW_CALENDARBOX_IN_CONFIRMPAGE'); ?>
							</span>
						</td>
						<td colspan="2">
							<?php echo $lists['show_calendar_box']; ?>
						</td>
					</tr>
					<tr>
						<td class="key">
							<span class="editlinktip hasTip" title="<?php echo JText::_('OS_CALENDAR_START_DATE')?>">
								<?php echo JText::_('OS_CALENDAR_START_DATE')?>
							</span>
						</td>
						<td colspan="2"><?php echo $lists['start_day_in_week'] ?></td>
					</tr>
					<tr>
						<td class="key">
							<span class="editlinktip hasTip" title="<?php echo JText::_('OS_SHOW_SERVICES_AND_EMPLOYEES_IN')?>">
								<?php echo JText::_('OS_SHOW_SERVICES_AND_EMPLOYEES_IN')?>
							</span>
						</td>
						<td colspan="2"><?php echo $lists['usingtab'] ?></td>
					</tr>
                    <tr>
                        <td  class="key" width="20%" valign="top">
                            <?php echo JText::_('OS_SELECT_TIMESLOT_THEME');?>
                        </td>
                        <td width="30%" valign="top">
                            <table width="100%">
                                <?php
                                echo $lists['booking_theme'];
                                ?>
                            </table>
                        </td>
                        <td width="50%" valign="top">
                            Radio timeslots theme
                            <BR />
                            <img src="<?php echo JURI::base()?>components/com_osservicesbooking/asset/images/radio_timeslot.png"  style="border:2px solid red; "/>
                            <BR />
                            Simple timeslots theme
                            <BR />
                            <img src="<?php echo JURI::base()?>components/com_osservicesbooking/asset/images/simple_timeslot.png"  style="border:2px solid red; "/>
                        </td>
                    </tr>
					<tr>
						<td  class="key" width="20%" valign="top">
							<?php echo JText::_('OS_HIDE_TAB_WHEN_HAVING_ONE_ITEM');?>
						</td>
						<td width="30%" valign="top">
							<table width="100%">
								<?php
								echo $lists['hidetabs'];
								?>
							</table>
						</td>
						<td width="50%" valign="top">
							<?php echo JText::_('OS_HIDE_TAB_WHEN_HAVING_ONE_ITEM_EXPLAIN');?>
							<BR />
							<img src="<?php echo JURI::base()?>components/com_osservicesbooking/asset/images/tabs.png"  style="border:2px solid red; "/>
						</td>
					</tr>
					<tr>
						<td  class="key" width="20%" valign="top">
							<?php echo JText::_('OS_SHOW_EMPLOYEE_INFORMATION_BAR');?>
						</td>
						<td width="30%" valign="top">
							<table width="100%">
								<?php
								echo $lists['employee_bar'];
								?>
							</table>
						</td>
						<td width="50%" valign="top">
							<img src="<?php echo JURI::base()?>components/com_osservicesbooking/asset/images/employee_bar.png"  style="border:2px solid red; "/>
						</td>
					</tr>
					<tr>
						<td  class="key" width="20%" valign="top">
							<?php echo JText::_('OS_SHOW_EMPLOYEE_COST');?>
						</td>
						<td width="30%" valign="top">
							<table width="100%">
								<?php
								echo $lists['show_employee_cost'];
								?>
							</table>
						</td>
						<td width="50%" valign="top">
							<img src="<?php echo JURI::base()?>components/com_osservicesbooking/asset/images/show_employee_cost.png"  style="border:2px solid red; "/>
						</td>
					</tr>
					<tr>
						<td  class="key" width="20%" valign="top">
							<?php echo JText::_('OS_SHOW_NUMBERSLOTS_BOOKING_INPUTBOX');?>
						</td>
						<td width="30%" valign="top">
							<table width="100%">
								<?php
								echo $lists['show_number_timeslots_booking'];
								?>
							</table>
						</td>
						<td width="50%" valign="top">
							<?php echo JText::_('OS_SHOW_NUMBERSLOTS_BOOKING_INPUTBOX_EXPLAIN');?>
							<BR />
							<img src="<?php echo JURI::base()?>components/com_osservicesbooking/asset/images/numberslots.png"  style="border:2px solid red; "/>
						</td>
					</tr>
					<tr>
						<td  class="key" width="20%" valign="top">
							<?php echo JText::_('Show dropdown select list Month, Year');?>
						</td>
						<td width="30%" valign="top">
							<table width="100%">
								<?php
								echo $lists['show_dropdown_month_year'];
								?>
							</table>
						</td>
						<td width="50%" valign="top">
							<?php echo JText::_('Please select Calendar Arrow buttons');?>
							<BR />
							<img src="<?php echo JURI::base()?>components/com_osservicesbooking/asset/images/dropdown_month_year.png"  style="border:2px solid red; "/>
						</td>
					</tr>
					<tr>
						<td  class="key" width="20%" valign="top">
							<?php echo JText::_('Calendar Arrow');?>
						</td>
						<td width="30%" valign="top">
							<table width="100%">
								<?php
								$name = "calendar_arrow";
								$arr1 = array('dark','pink','blue','green','transparent');
								$arr2 = array('Dark arrows','Pink arrows','Blue arrows','Green arrows','Transparent arrows');
								for($i=0;$i<count($arr1);$i++){
									
									if($configs->calendar_arrow == $arr1[$i]){
										$checked = "checked";
									}else{
										$checked = "";
									}
									?>
									<tr>
										<td width="20%" style="text-align:center;">
											<input type="radio" name="<?php echo $name;?>" value="<?php echo $arr1[$i]?>" <?php echo $checked?> />
										</td>
										<td width="80%" style="text-align:left;">
											<img src="<?php echo JURI::root()?>components/com_osservicesbooking/asset/images/icons/previous_<?php echo $arr1[$i]?>.png" />
											<img src="<?php echo JURI::root()?>components/com_osservicesbooking/asset/images/icons/next_<?php echo $arr1[$i]?>.png" />
										</td>
									</tr>
									<?php
								}
								?>
							</table>
						</td>
						<td width="50%" valign="top">
							<?php echo JText::_('Please select Calendar Arrow buttons');?>
							<BR />
							<img src="<?php echo JURI::base()?>components/com_osservicesbooking/asset/images/calendar_arrow.png"  style="border:2px solid red; "/>
						</td>
					</tr>
					<tr>
						<td  class="key" width="20%" valign="top">
							<?php echo JText::_('Header Style');?>
						</td>
						<td width="30%" valign="top">
							<table width="100%">
								<?php
								$name = "header_style";
								$arr1 = array('btn','btn btn-primary','btn btn-info','btn btn-success','btn btn-warning','btn btn-danger','btn btn-inverse');
								$arr2 = array('Gray style','Blue style','Light Blue style','Green style','Yellow style','Red style','Black style');
								for($i=0;$i<count($arr1);$i++){
									
									if($configs->header_style == $arr1[$i]){
										$checked = "checked";
									}else{
										$checked = "";
									}
									?>
									<tr>
										<td width="20%" style="text-align:center;">
											<input type="radio" name="<?php echo $name;?>" value="<?php echo $arr1[$i]?>" <?php echo $checked?> />
										</td>
										<td width="80%" style="text-align:left;">
											<input type="button" class="<?php echo $arr1[$i]?>" value="<?php echo $arr2[$i]?>" style="width:150px;" />
										</td>
									</tr>
									<?php
								}
								?>
							</table>
						</td>
						<td width="50%" valign="top">
							<?php echo JText::_('Please select style of Headers');?>
							<BR />
							<img src="<?php echo JURI::base()?>components/com_osservicesbooking/asset/images/header_style.png"  style="border:2px solid red; "/>
						</td>
					</tr>
					<tr>
						<td  class="key" width="20%" valign="top">
							<?php echo JText::_('Calendar Normal Style');?>
						</td>
						<td width="30%" valign="top">
							<table width="100%">
								<?php
								$name = "calendar_normal_style";
								$arr1 = array('btn','btn btn-primary','btn btn-info','btn btn-success','btn btn-warning','btn btn-danger','btn btn-inverse');
								$arr2 = array('Gray style','Blue style','Light Blue style','Green style','Yellow style','Red style','Black style');
								for($i=0;$i<count($arr1);$i++){
									
									if($configs->calendar_normal_style == $arr1[$i]){
										$checked = "checked";
									}else{
										$checked = "";
									}
									?>
									<tr>
										<td width="20%" style="text-align:center;">
											<input type="radio" name="<?php echo $name;?>" value="<?php echo $arr1[$i]?>" <?php echo $checked?> />
										</td>
										<td width="80%" style="text-align:left;">
											<input type="button" class="<?php echo $arr1[$i]?>" value="<?php echo $arr2[$i]?>" style="width:150px;" />
										</td>
									</tr>
									<?php
								}
								?>
							</table>
						</td>
						<td width="50%" valign="top">
							<?php echo JText::_('Please select style of Calendar Normal date');?>
							<BR />
							<img src="<?php echo JURI::base()?>components/com_osservicesbooking/asset/images/normal_date.png"  style="border:2px solid red; "/>
						</td>
					</tr>
					
					<tr>
						<td  class="key" width="20%" valign="top">
							<?php echo JText::_('Calendar Actived Date Style');?>
						</td>
						<td width="30%" valign="top">
							<table width="100%">
								<?php
								$name = "calendar_activate_style";
								$arr1 = array('btn','btn btn-primary','btn btn-info','btn btn-success','btn btn-warning','btn btn-danger','btn btn-inverse');
								$arr2 = array('Gray style','Blue style','Light Blue style','Green style','Yellow style','Red style','Black style');
								for($i=0;$i<count($arr1);$i++){
									
									if($configs->calendar_activate_style == $arr1[$i]){
										$checked = "checked";
									}else{
										$checked = "";
									}
									?>
									<tr>
										<td width="20%" style="text-align:center;">
											<input type="radio" name="<?php echo $name;?>" value="<?php echo $arr1[$i]?>" <?php echo $checked?> />
										</td>
										<td width="80%" style="text-align:left;">
											<input type="button" class="<?php echo $arr1[$i]?>" value="<?php echo $arr2[$i]?>" style="width:150px;" />
										</td>
									</tr>
									<?php
								}
								?>
							</table>
						</td>
						<td width="50%" valign="top">
							<?php echo JText::_('Please select style of Calendar Normal date');?>
							<BR />
							<img src="<?php echo JURI::base()?>components/com_osservicesbooking/asset/images/activate_date.png"  style="border:2px solid red; "/>
						</td>
					</tr>
					
					<tr>
						<td  class="key" width="20%" valign="top">
							<?php echo JText::_('Calendar Current Date Style');?>
						</td>
						<td width="30%" valign="top">
							<table width="100%">
								<?php
								$name = "calendar_currentdate_style";
								$arr1 = array('btn','btn btn-primary','btn btn-info','btn btn-success','btn btn-warning','btn btn-danger','btn btn-inverse');
								$arr2 = array('Gray style','Blue style','Light Blue style','Green style','Yellow style','Red style','Black style');
								for($i=0;$i<count($arr1);$i++){
									
									if($configs->calendar_currentdate_style == $arr1[$i]){
										$checked = "checked";
									}else{
										$checked = "";
									}
									?>
									<tr>
										<td width="20%" style="text-align:center;">
											<input type="radio" name="<?php echo $name;?>" value="<?php echo $arr1[$i]?>" <?php echo $checked?> />
										</td>
										<td width="80%" style="text-align:left;">
											<input type="button" class="<?php echo $arr1[$i]?>" value="<?php echo $arr2[$i]?>" style="width:150px;" />
										</td>
									</tr>
									<?php
								}
								?>
							</table>
						</td>
						<td width="50%" valign="top">
							<?php echo JText::_('Please select style of Calendar Normal date');?>
							<BR />
							<img src="<?php echo JURI::base()?>components/com_osservicesbooking/asset/images/current_date.png"  style="border:2px solid red; "/>
						</td>
					</tr>
					<tr>
						<td  class="key" width="20%" valign="top">
							<?php echo JText::_('OS_SHOW_SERVICE_INFORMATION_BOX');?>
						</td>
						<td width="30%" valign="top">
							<?php 
							echo $lists['show_service_info_box'];
							?>
						</td>
						<td width="50%" valign="top">
							<?php echo JText::_('OS_SHOW_SERVICE_INFORMATION_BOX_EXPLAIN');?>
							<BR /><BR />
							<img src="<?php echo JURI::base()?>components/com_osservicesbooking/asset/images/service_box.png"  style="border:2px solid red; "/>
						</td>
					</tr>
					<tr>
						<td  class="key" width="20%" valign="top">							
							<?php echo JText::_('OS_SHOW_SERVICE_PHOTO');?>
						</td>
						<td width="30%" valign="top">
							<?php 
							echo $lists['show_service_photo'];
							?>
						</td>
						<td width="50%" valign="top">
							<?php echo JText::_('OS_SHOW_SERVICE_PHOTO_EXPLAIN');?>
							<BR /><BR />
							<img src="<?php echo JURI::base()?>components/com_osservicesbooking/asset/images/service_photo.png"  style="border:2px solid red; "/>
						</td>
					</tr>
					<tr>
						<td  class="key" width="20%" valign="top">
							<?php echo JText::_('OS_SHOW_SERVICE_DESCRIPTION');?>
						</td>
						<td width="30%" valign="top">
							<?php 
							echo $lists['show_service_description'];
							?>
						</td>
						<td width="50%" valign="top">
							<?php echo JText::_('OS_SHOW_SERVICE_DESCRIPTION_EXPLAIN');?>
							<BR /><BR />
							<img src="<?php echo JURI::base()?>components/com_osservicesbooking/asset/images/service_description.png"  style="border:2px solid red; "/>
						</td>
					</tr>
					<tr>
						<td  class="key" width="20%" valign="top">
							<?php echo JText::_('OS_SHOW_BOOKED_INFO_BOX');?>
						</td>
						<td width="30%" valign="top">
							<?php 
							echo $lists['show_booked_information'];
							?>
						</td>
						<td width="50%" valign="top">
							<?php echo JText::_('OS_SHOW_BOOKED_INFO_BOX_EXPLAIN');?>
							<BR /><BR />
							<img src="<?php echo JURI::base()?>components/com_osservicesbooking/asset/images/booked_information.png"  style="border:2px solid red; "/>
						</td>
					</tr>
					<tr>
						<td  class="key" width="20%" valign="top">
							<?php echo JText::_('Time Slots background');?>
						</td>
						<td width="30%" valign="top">
							<table width="100%">
								<?php
								$name = "timeslot_background";
								$arr1 = array('#7BA1EB','#1C67A9','#58B158','#F89E1D','#D04640','#2E2E2E','#797979');
								//$arr2 = array('Gray style','Blue style','Light Blue style','Green style','Yellow style','Red style','Black style');
								for($i=0;$i<count($arr1);$i++){
									
									if($configs->timeslot_background == $arr1[$i]){
										$checked = "checked";
									}else{
										$checked = "";
									}
									?>
									<tr>
										<td width="20%" style="text-align:center;">
											<input type="radio" name="<?php echo $name;?>" value="<?php echo $arr1[$i]?>" <?php echo $checked?> />
										</td>
										<td width="80%" style="text-align:center;background-color:<?php echo $arr1[$i];?>;color:white;">
											<?php echo $arr1[$i];?>
										</td>
									</tr>
									<?php
								}
								?>
							</table>
						</td>
						<td width="50%" valign="top">
							<?php echo JText::_('Please select style of Calendar Normal date');?>
							<BR />
							<img src="<?php echo JURI::base()?>components/com_osservicesbooking/asset/images/timeslot_background.png"  style="border:2px solid red; "/>
						</td>
					</tr>
                    <tr>
                        <td  class="key" width="20%" valign="top">
                            <?php echo JText::_('OS_SHOW_ORDER_URL_AND_CANCEL_URL');?>
                        </td>
                        <td width="30%" valign="top">
                            <?php
                            echo $lists['show_details_and_orders'];
                            ?>
                        </td>
                        <td width="50%" valign="top">
                            <?php echo JText::_('OS_SHOW_ORDER_URL_AND_CANCEL_URL_EXPLAIN');?>
                            <BR /><BR />
                            <img src="<?php echo JURI::base()?>components/com_osservicesbooking/asset/images/url.png"  style="border:2px solid red; "/>
                        </td>
                    </tr>
				</table>
			</div>
			<div class="tab-pane" id="email-marketing">
				<table class="admintable adminform" style="width:100%;">
					<tr>
						<td width="100%" colspan="2">
							This feature is used to setup OS Services Booking with access information for adding customers to your AcyMailing lists. When enabled, OS Services Booking will call AcyMailing and insert a new mailing list user as part of the appointment booking process.
							<BR />
							<strong>Note:</strong><BR />
1. Changing the status of a booking has no effect on AcyMailing.<BR />
2. Cancelling a booking does not remove a list entry.<BR />
3. OS Services Booking never removes list entries from AcyMailing. <BR />
4. You must have the AcyMailing component installed to use this option. See <a href="https://www.acyba.com/acymailing.html" target="_blank">https://www.acyba.com/acymailing.html</a>
						</td>
					</tr>
					<tr>
						<td  class="key" width="30%" >
							<?php echo JText::_('OS_ENABLE_ACYMAILING');?>
						</td>
						<td width="70%" valign="top">
							<?php
							echo $lists['enable_acymailing'];
							?>
						</td>
					</tr>
					<tr>
						<td  class="key" width="30%" valign="top">
							<?php echo JText::_('OS_SELECT_DEFAULT_LIST');?>
						</td>
						<td width="70%" valign="top">
							<?php
							$acyLists = null;
							if(file_exists(JPATH_ADMINISTRATOR . '/components/com_acymailing/acymailing.php') && JComponentHelper::isEnabled('com_acymailing', true)){
								if(include_once(rtrim(JPATH_ADMINISTRATOR,DS).DS.'components'.DS.'com_acymailing'.DS.'helpers'.DS.'helper.php')){
									$listClass = acymailing_get('class.list');
									$acyLists = $listClass->getLists();	
								 }
						    }	
							?>
							<select name="acymailing_default_list_id">
			                <?php 
								foreach($acyLists as $List){ ?>			
									<option value="<?php echo $List->listid;?>"<?php if($configs->acymailing_default_list_id == $List->listid){echo " selected='selected' ";} ?>><?php echo $List->name;?></option>
			                <?php } ?>          
			                </select>
							<BR />
							Select a default AcyMailing list to receive new customers.
							<BR />
							You can override this at the OS Services Booking service level in the service modification screen
						</td>
					</tr>
				</table>
			</div>
		</div>
		<input type="hidden" name="option" value="<?php echo $option; ?>" />
		<input type="hidden" name="task" value="" />
		</form>
		<?php
	}
}
?>