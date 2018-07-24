<?php
/*------------------------------------------------------------------------
# osappschedule.php - Ossolution Services Booking
# ------------------------------------------------------------------------
# author    Ossolution team
# copyright Copyright (C) 2012 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/
// no direct access
defined('_JEXEC') or die;

error_reporting(E_ERROR | E_PARSE | E_COMPILE_ERROR);
define('DS',DIRECTORY_SEPARATOR);
JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables');
$document = JFactory::getDocument();
$document->addScript(JURI::root()."components/com_osservicesbooking/js/ajax.js");
$document->addScript(JURI::root()."components/com_osservicesbooking/js/paymentmethods.js");
$document->addScript(JURI::root()."components/com_osservicesbooking/js/javascript.js");
$document->addStyleSheet(JURI::root()."components/com_osservicesbooking/style/style.css");
require_once JPATH_ROOT.'/components/com_osservicesbooking/plugins/os_payment.php';
require_once JPATH_ROOT.'/components/com_osservicesbooking/plugins/os_payments.php';
require_once JPATH_COMPONENT.DS."helpers".DS."pane.php";
require_once JPATH_COMPONENT_ADMINISTRATOR.DS."helpers".DS."helper.php";
jimport('joomla.html.parameter');
jimport('joomla.filesystem.folder');
//Include files from classes folder
$dir = JFolder::files(JPATH_COMPONENT.DS."classes");
if(count($dir) > 0){
	for($i=0;$i<count($dir);$i++){
		require_once(JPATH_COMPONENT.DS."classes".DS.$dir[$i]);
	}
}

$dir = JFolder::files(JPATH_COMPONENT.DS."helpers");
if(count($dir) > 0){
	for($i=0;$i<count($dir);$i++){
		if($dir[$i]!= "ipn_log.txt"){
			require_once(JPATH_COMPONENT.DS."helpers".DS.$dir[$i]);
		}
	}
}

global $_jversion,$configs,$configClass,$symbol,$mainframe,$lang_suffix,$languages;
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

$use_ssl = $configClass['use_ssl'];
$domain_post = strpos(JURI::root(),"://");
$domain = substr(JURI::root(),$domain_post);
if($use_ssl == 1){
	$root_url = "https".$domain;
}else{
	$root_url = "http".$domain;
}
$configClass['root_link'] = $root_url;

$user = JFactory::getUser();

if(($user->id > 0) and ($configClass['pass_captcha'] == 1)){
	$configClass['value_sch_include_captcha'] = 1;
}
if(($user->id > 0) and ($configClass['group_payment'] > 0)){
	$db->setQuery("Select count(user_id) from #__user_usergroup_map where user_id = '$user->id' and group_id = '".$configClass['group_payment']."'");
	$count = $db->loadResult();
	if($count > 0){
		$configClass['disable_payments'] = 0;
	}
}

$translatable = JLanguageMultilang::isEnabled() && count($languages);
if($translatable){
	//generate the suffix
	$lang_suffix = OSBHelper::getFieldSuffix();
}

//setup cookie
$session =& JFactory::getSession();

if($unique_cookie == ""){
	$unique_cookie = $session->get( 'unique_cookie', '' );
	if($unique_cookie == ""){
		$unique_cookie = md5(rand(1000,9999));
		@setcookie('unique_cookie',$unique_cookie,time()+3600);
	}else{
		@setcookie('unique_cookie',$unique_cookie,time()+3600);
	}
}
$session->set( 'unique_cookie', $unique_cookie );

$date_from = JRequest::getVar('date_from','');
$date_to = JRequest::getVar('date_to','');
if($date_from != ""){
	$date_from = explode(" ",$date_from);
	JRequest::setVar('date_from',$date_from[0]);
}
if($date_to != ""){
	$date_to = explode(" ",$date_to);
	JRequest::setVar('date_to',$date_to[0]);
}

$select_date = JRequest::getVar('selected_date','');

if($select_date != ""){
	$date_from = $select_date;
	$date_to   = $select_date;
	Jrequest::setVar('date_from',$select_date);
	Jrequest::setVar('date_to',$select_date);
}


//@setcookie('unique_cookie',$unique_cookie,time()+3600);
$config = new JConfig();
$offset = $config->offset;
date_default_timezone_set($offset);
$task = JRequest::getVar('task','');
if($task == ""){
	$view = JRequest::getVar('view');
	switch ($view){
		case "listemployee":
			$task = "default_allemployees";
		break;
		case "venue":
			$task = "venue_listing";
		break;
		case "category":
			$task = "category_listing";
		break;
		case "employee":
			$task = "default_employeeworks";
		break;
		case "customer":
			$task = "default_customer";
		break;
        case "services":
            $task = "service_listing";
        break;
        case "allitems":
            $task = "service_listallitems";
        break;
		case "manageallorders":
			$task = "manage_orders";
		break;
		default:
			$task = "default_layout";
		break;
	}
}

JHTML::_('behavior.tooltip');

if (version_compare(JVERSION, '3.0', 'le')){
	OSBHelper::loadBootstrap();
}else{
	if($configClass['load_bootstrap'] == 1){
		OSBHelper::loadBootstrap();
	}else{
		OSBHelper::loadBootstrapStylesheet();
	}
}
//2.3.6
//$document->addStyleSheet(JURI::root().'components/com_osservicesbooking/style/tabdrop.css');
$document->addStyleSheet("//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css");
?>
<script type="text/javascript" src="<?php echo '//code.jquery.com/ui/1.11.4/jquery-ui.js'; ?>"></script>
<div id="dialogstr4" title="<?php echo JText::_('OS_ITEM_HAS_BEEN_ADD_TO_CART_TITLE');?>">
</div>
<?php
// If no data found from session, using mobile detect class to detect the device type
global $deviceType;
$mobileDetect = new OSB_Mobile_Detect();
$deviceType   = 'desktop';
if ($mobileDetect->isMobile()){
	$deviceType = 'mobile';
}
if ($mobileDetect->isTablet()){
	$deviceType = 'tablet';
}

$header_style = $configClass['header_style'];
$header_style = trim(str_replace("btn","",$header_style));
if(trim($header_style) != ""){
	$header_style = str_replace("btn","",$header_style);
	$header_style = str_replace("-","",$header_style);
	if(trim($header_style) != ""){
		$document->addStyleSheet(JUri::root()."components/com_osservicesbooking/style/tabstyle/".$header_style.".css");
	}
}

if($task != ""){
	$taskArr = explode("_",$task);
	$maintask = $taskArr[0];
}else{
	//cpanel
	$maintask = "";
}
switch ($maintask){
	case "venue":
		OSappscheduleVenueFnt::display($option,$task);
	break;
	case "calendar":
		OsAppscheduleCalendar::display($option,$task);
	break;
	case "ajax":
		OsAppscheduleAjax::display($option,$task);
	break;
	case "form":
		OsAppscheduleForm::display($option,$task);
	break;
	case "category":
		OSappscheduleCategory::display($option,$task);
	break;
    case "service":
        OSappscheduleService::display($option,$task);
    break;
	case "manage":
		$user = JFactory::getUser();
		if (((int)$user->id == 0) || (!JFactory::getUser()->authorise('osservicesbooking.orders', 'com_osservicesbooking'))) {
		    JError::raiseError( 500, JText::_('OS_YOU_DONT_HAVE_PERMISSION_TO_GO_TO_THIS_AREA') );
		}else{
			OSappscheduleManage::display($option,$task);
		}
	break;
	default:
		OsAppscheduleDefault::display($option,$task);
	break;
}
?>