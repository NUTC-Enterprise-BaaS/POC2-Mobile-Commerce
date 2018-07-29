<?php

/*------------------------------------------------------------------------
# osservicesbooking.php - OS Services Booking
# ------------------------------------------------------------------------
# author    Ossolution team
# copyright Copyright (C) 2015 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/
// no direct access
defined('_JEXEC') or die;

error_reporting(E_ERROR | E_PARSE);
//error_reporting(E_ALL);
define("DS",DIRECTORY_SEPARATOR);
JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables');
jimport('joomla.filesystem.folder');
//Include files from classes folder
$dir = JFolder::files(JPATH_COMPONENT_ADMINISTRATOR.DS."classes",'.php');
if(count($dir) > 0){
	for($i=0;$i<count($dir);$i++){
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DS."classes".DS.$dir[$i]);
	}
}
//Include files from classes folder
$dir = JFolder::files(JPATH_COMPONENT_ADMINISTRATOR.DS."helpers",'.php');
if(count($dir) > 0){
	for($i=0;$i<count($dir);$i++){
		require_once(JPATH_COMPONENT_ADMINISTRATOR.DS."helpers".DS.$dir[$i]);
	}
}
require_once JPATH_ROOT.'/components/com_osservicesbooking/plugins/os_payment.php';
require_once JPATH_ROOT.'/components/com_osservicesbooking/plugins/os_payments.php';
require_once JPATH_ROOT.'/components/com_osservicesbooking/helpers/downloadInvoice.php';
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS."elements".DS."osmcurrency.php");
require_once(JPATH_ROOT.DS."components".DS."com_osservicesbooking".DS."helpers".DS."calendar.php");

$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root()."administrator/components/com_osservicesbooking/asset/css/style.css");
$document->addScript(JURI::root()."administrator/components/com_osservicesbooking/asset/javascript/javascript.js");
$document->addScript(JURI::root()."components/com_osservicesbooking/js/javascript.js");
$document->addScript(JURI::root()."components/com_osservicesbooking/js/ajax.js");

global $_jversion,$configs,$configClass,$symbol,$mainframe,$languages;
$languages = OSBHelper::getLanguages();
$mainframe = JFactory::getApplication();
$db = JFactory::getDBO();
$db->setQuery("Select * from #__app_sch_configuation");
$configs = $db->loadObjectList();
$configClass = array();
foreach ($configs as $config) {
	$configClass[$config->config_key] = $config->config_value;
}
if($configClass['currency_format'] == ""){
	$configClass['currency_format'] = "USD";
}
$db->setQuery("Select currency_symbol from #__app_sch_currencies where currency_code like '".$configClass['currency_format']."'");
$currency_symbol = $db->loadResult();

$configClass['currency_symbol'] = $currency_symbol;
global $mainframe;
$mainframe = JFactory::getApplication();

/**
 * Multiple languages processing
 */
if (JLanguageMultilang::isEnabled() && !OSBHelper::isSyncronized()){
	OSBHelper::setupMultilingual();
}

$option = JRequest::getVar('option','com_osservicesbooking');
$task = JRequest::getVar('task','cpanel_list');
if($task != ""){
	$taskArr = explode("_",$task);
	$maintask = $taskArr[0];
}else{
	//cpanel
	$maintask = "";
}

OSappscheduleCpanel::zendChecking();

if($maintask != "ajax"){
	$blacktaskarry = array('fields_addOption','fields_removeFieldOption','fields_editOption','service_addcustomprice','service_removecustomprice','employee_addcustombreaktime','employee_removecustombreaktime','employee_removeRestday','ajax_removetemptimeslot','ajax_removerestdayAjax','ajax_addrestdayAjax','ajax_removeOrderItemAjax','orders_updateNewOrderStatus','');
	$from = JRequest::getVar('from','');
    $tmpl = JRequest::getVar('tmpl','');
	if((!in_array($task,$blacktaskarry)) and (!in_array($from,$fromarray))) {
		OSBHelper::renderSubmenu($task);	
	}
}

switch ($maintask){
	default:
	case "cpanel":
		if (JFactory::getUser()->authorise('core.manage', 'com_osservicesbooking')) {
			OSappscheduleCpanel::display($option,$task);
		}else{
			return JError::raise(E_WARNING, 404, JText::_('JERROR_ALERTNOAUTHOR'));
		}
	break;
    case "balance":
		if (JFactory::getUser()->authorise('user_balance', 'com_osservicesbooking')) {
			OSappscheduleBalance::display($option,$task);
		}else{
			return JError::raise(E_WARNING, 404, JText::_('JERROR_ALERTNOAUTHOR'));
		}
    break;
	case "employee":
		if (JFactory::getUser()->authorise('employees', 'com_osservicesbooking')) {
			OSappscheduleEmployee::display($option,$task);
		}else{
			return JError::raise(E_WARNING, 404, JText::_('JERROR_ALERTNOAUTHOR'));
		}
	break;
	case "worktime":
		if (JFactory::getUser()->authorise('workingtime', 'com_osservicesbooking')) {
			OSappscheduleWorktime::display($option,$task);
		}else{
			return JError::raise(E_WARNING, 404, JText::_('JERROR_ALERTNOAUTHOR'));
		}
	break;
	case "worktimecustom":
		if (JFactory::getUser()->authorise('custom_workingtime', 'com_osservicesbooking')) {
			OSappscheduleWorktimecustom::display($option,$task);
		}else{
			return JError::raise(E_WARNING, 404, JText::_('JERROR_ALERTNOAUTHOR'));
		}
	break;
	case 'configuration':
		if (!JFactory::getUser()->authorise('configuration', 'com_osservicesbooking')) {
			return JError::raise(E_WARNING, 404, JText::_('JERROR_ALERTNOAUTHOR'));
		}
		OSappscheduleConfiguration::display($option,$task);
	break;	
	case 'orders':
		if (JFactory::getUser()->authorise('orders', 'com_osservicesbooking')) {
			OSappscheduleOrders::display($option,$task);
		}else{
			return JError::raise(E_WARNING, 404, JText::_('JERROR_ALERTNOAUTHOR'));
		}
	break;
	case 'fields':
		if (JFactory::getUser()->authorise('custom_fields', 'com_osservicesbooking')) {
			OsAppscheduleFields::display($option,$task);
		}else{
			return JError::raise(E_WARNING, 404, JText::_('JERROR_ALERTNOAUTHOR'));
		}
	break;
	case 'emails':
		if (JFactory::getUser()->authorise('emails', 'com_osservicesbooking')) {
			OsAppscheduleEmails::display($option,$task);
		}else{
			return JError::raise(E_WARNING, 404, JText::_('JERROR_ALERTNOAUTHOR'));
		}
	break;
	case 'translation':
		if (JFactory::getUser()->authorise('translation', 'com_osservicesbooking')) {
			OsAppscheduleTranslation::display($option,$task);
		}else{
			return JError::raise(E_WARNING, 404, JText::_('JERROR_ALERTNOAUTHOR'));
		}
	break;
	case 'category':
		if (JFactory::getUser()->authorise('categories', 'com_osservicesbooking')) {
			OSappscheduleCategory::display($option,$task);
		}else{
			return JError::raise(E_WARNING, 404, JText::_('JERROR_ALERTNOAUTHOR'));
		}
	break;
	case 'plugin':
		if (JFactory::getUser()->authorise('payment', 'com_osservicesbooking')) {
			OSappschedulePlugin::display($option,$task);
		}else{
			return JError::raise(E_WARNING, 404, JText::_('JERROR_ALERTNOAUTHOR'));
		}
	break;
	case 'venue':
		if (JFactory::getUser()->authorise('venues', 'com_osservicesbooking')) {
			OsAppscheduleVenue::display($option,$task);
		}else{
			return JError::raise(E_WARNING, 404, JText::_('JERROR_ALERTNOAUTHOR'));
		}
	break;
	case 'coupon':
		if (JFactory::getUser()->authorise('coupons', 'com_osservicesbooking')) {
			OSappscheduleCoupon::display($option,$task);
		}else{
			return JError::raise(E_WARNING, 404, JText::_('JERROR_ALERTNOAUTHOR'));
		}
	break;
	case 'install':
	case 'service':
		OSappscheduleService::display($option,$task);
	break;
}
/*
$subMenus = array(
			JText::_('OS_DASHBOARD')=> 'cpanel',
			JText::_('OS_CONFIGURATION_CONFIGURATION')=>'configuration',
			JText::_('OS_MANAGE_VENUES')=>'venue',
			JText::_('OS_CATEGORIES')=>'category',
			JText::_('OS_SERVICES')=> 'service',
			JText::_('OS_CUSTOM_FIELD') => 'fields',
			JText::_('OS_EMPLOYEE') => 'employee',
			JText::_('OS_WORKING_TIME') => 'worktime',
			JText::_('OS_MANAGE_WORKTIMECUSTOM')=> 'worktimecustom',
			JText::_('OS_MANAGE_PAYMENT_PLUGINS')=> 'plugin',
			JText::_('OS_MANAGE_EMAIL_TEMPLATES')=> 'emails',
			JText::_('OS_MANAGE_COUPONS')=> 'coupon',
			JText::_('OS_MANAGE_ORDERS')=> 'orders',
			JText::_('OS_MANAGE_TRANSLATION_LIST')=> 'translation'
			);
if (version_compare(JVERSION, '3.0', 'ge')) {
	Helpermenu::creatmenu($option,$maintask,$subMenus);
}
*/
if (version_compare(JVERSION, '3.0', 'le')){
	OSBHelper::loadBootstrap();
}else{
	OSBHelper::loadBootstrapStylesheet();
}

OSBHelper::displayCopyright();
?>