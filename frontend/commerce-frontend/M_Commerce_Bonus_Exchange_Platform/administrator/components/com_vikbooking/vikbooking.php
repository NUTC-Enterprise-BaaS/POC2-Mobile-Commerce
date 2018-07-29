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

defined('_JEXEC') or die('Restricted access');

$er_l = isset($_REQUEST['error_reporting']) && intval($_REQUEST['error_reporting'] == '-1') ? -1 : 0;
defined('VIKBOOKING_ERROR_REPORTING') OR define('VIKBOOKING_ERROR_REPORTING', $er_l);
error_reporting(VIKBOOKING_ERROR_REPORTING);

//Joomla 3.0
if(!defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}
//

require_once(JPATH_SITE . DS ."components". DS ."com_vikbooking". DS . "helpers" . DS ."lib.vikbooking.php");
require_once(JPATH_ADMINISTRATOR . DS ."components". DS ."com_vikbooking". DS . "helpers" . DS ."jv_helper.php");

require_once(JPATH_ADMINISTRATOR . DS . "components". DS ."com_vikbooking" . DS . 'admin.vikbooking.html.php');

$document = JFactory::getDocument();
$document->addStyleSheet('components/com_vikbooking/vikbooking.css');
$document->addStyleSheet('components/com_vikbooking/resources/fonts/vboicomoon.css');
JHtml::_('jquery.framework', true, true);
JHtml::_('script', JURI::root().'components/com_vikbooking/resources/jquery-1.11.3.min.js', false, true, false, false);

$cid = JRequest::getVar('cid', array(0));
$task = JRequest::getVar('task');

//Joomla 2.5
$option=empty($option) ? "com_vikbooking" : $option;
//

//ACL
if (!JFactory::getUser()->authorise('core.manage', 'com_vikbooking')) {
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}
//

require_once(JPATH_ADMINISTRATOR . DS . "components". DS ."com_vikbooking" . DS . 'toolbar.vikbooking.php');

switch ($task) {
	case 'viewiva' :
		HTML_vikbooking::printHeader("2");
		viewIva($option);
		break;
	case 'newiva' :
		HTML_vikbooking::printHeader("2");
		newIva($option);
		break;
	case 'editiva' :
		HTML_vikbooking::printHeader("2");
		editIva($cid[0], $option);
		break;	
	case 'createiva' :
		HTML_vikbooking::printHeader();
		saveIva($option);
		break;
	case 'updateiva' :
		HTML_vikbooking::printHeader();
		updateIva($option);
		break;	
	case 'removeiva' :
		HTML_vikbooking::printHeader();
		removeIva($cid, $option);
		break;	
	case 'canceliva' :
		HTML_vikbooking::printHeader();
		cancelEditingIva($option);
		break;	
	case 'viewprices' :
		HTML_vikbooking::printHeader("1");
		viewPrices($option);
		break;
	case 'newprice' :
		HTML_vikbooking::printHeader("1");
		newPrice($option);
		break;
	case 'editprice' :
		HTML_vikbooking::printHeader("1");
		editPrice($cid[0], $option);
		break;	
	case 'createprice' :
		HTML_vikbooking::printHeader();
		savePrice($option);
		break;
	case 'updateprice' :
		HTML_vikbooking::printHeader();
		updatePrice($option);
		break;	
	case 'removeprice' :
		HTML_vikbooking::printHeader();
		removePrice($cid, $option);
		break;	
	case 'cancelprice' :
		HTML_vikbooking::printHeader();
		cancelEditingPrice($option);
		break;
	case 'viewcategories' :
		HTML_vikbooking::printHeader("4");
		viewCategories($option);
		break;
	case 'newcat' :
		HTML_vikbooking::printHeader("4");
		newCat($option);
		break;
	case 'editcat' :
		HTML_vikbooking::printHeader("4");
		editCat($cid[0], $option);
		break;	
	case 'createcat' :
		HTML_vikbooking::printHeader();
		saveCat($option);
		break;
	case 'updatecat' :
		HTML_vikbooking::printHeader();
		updateCat($option);
		break;	
	case 'removecat' :
		HTML_vikbooking::printHeader();
		removeCat($cid, $option);
		break;	
	case 'cancelcat' :
		HTML_vikbooking::printHeader();
		cancelEditingCat($option);
		break;
	case 'viewcarat' :
		HTML_vikbooking::printHeader("5");
		viewCarat($option);
		break;
	case 'newcarat' :
		HTML_vikbooking::printHeader("5");
		newCarat($option);
		break;
	case 'editcarat' :
		HTML_vikbooking::printHeader("5");
		editCarat($cid[0], $option);
		break;	
	case 'createcarat' :
		HTML_vikbooking::printHeader();
		saveCarat($option);
		break;
	case 'updatecarat' :
		HTML_vikbooking::printHeader();
		updateCarat($option);
		break;	
	case 'removecarat' :
		HTML_vikbooking::printHeader();
		removeCarat($cid, $option);
		break;	
	case 'cancelcarat' :
		HTML_vikbooking::printHeader();
		cancelEditingCarat($option);
		break;
	case 'viewoptionals' :
		HTML_vikbooking::printHeader("6");
		viewOptionals($option);
		break;
	case 'newoptionals' :
		HTML_vikbooking::printHeader("6");
		newOptionals($option);
		break;
	case 'editoptional' :
		HTML_vikbooking::printHeader("6");
		editOptional($cid[0], $option);
		break;	
	case 'createoptionals' :
		HTML_vikbooking::printHeader();
		saveOptionals($option);
		break;
	case 'updateoptional' :
		HTML_vikbooking::printHeader();
		updateOptional($option);
		break;	
	case 'removeoptionals' :
		HTML_vikbooking::printHeader();
		removeOptionals($cid, $option);
		break;	
	case 'canceloptionals' :
		HTML_vikbooking::printHeader();
		cancelEditingOptionals($option);
		break;
	case 'viewroom' :
		HTML_vikbooking::printHeader("7");
		viewRoom($option);
		break;
	case 'newroom' :
		HTML_vikbooking::printHeader("7");
		newRoom($option);
		break;
	case 'editroom' :
		HTML_vikbooking::printHeader("7");
		editRoom($cid[0], $option);
		break;	
	case 'createroom' :
		saveRoom($option);
		break;
	case 'createroomstay' :
		saveRoom($option, true);
		break;
	case 'updateroom' :
		HTML_vikbooking::printHeader();
		updateRoom($option);
		break;
	case 'updateroomstay' :
		HTML_vikbooking::printHeader();
		updateRoom($option, true);
		break;
	case 'removeroom' :
		HTML_vikbooking::printHeader();
		removeRoom($cid, $option);
		break;	
	case 'modavail' :
		HTML_vikbooking::printHeader();
		modAvail($cid[0], $option);
		break;	
	case 'viewtariffe' :
		viewTariffe($cid[0], $option);
		break;
	case 'removetariffe' :
		removeTariffe($cid, $option);
		break;
	case 'calendar' :
		viewCalendar($cid[0], $option);
		break;
	case 'editbusy' :
		editBusy($cid[0], $option);
		break;
	case 'updatebusy' :
		updateBusy($option);
		break;
	case 'removebusy' :
		HTML_vikbooking::printHeader();
		removeBusy($option);
		break;								
	case 'cancel' :
		HTML_vikbooking::printHeader();
		cancelEditing($option);
		break;
	case 'cancelcalendar' :
		HTML_vikbooking::printHeader();
		cancelCalendar($option);
		break;
	case 'canceloverview' :
		cancelOverview($option);
		break;
	case 'cancelbusy' :
		HTML_vikbooking::printHeader();
		cancelBusy($option);
		break;
	case 'vieworders' :
		HTML_vikbooking::printHeader("8");
		viewOrders($option);
		break;
	case 'removeorders' :
		HTML_vikbooking::printHeader();
		removeOrders($cid, $option);
		break;
	case 'editorder' :
		HTML_vikbooking::printHeader("8");
		editOrder($cid[0], $option);
		break;	
	case 'canceledorder' :
		HTML_vikbooking::printHeader();
		cancelEditingOrders($option);
		break;
	case 'config' :
		HTML_vikbooking::printHeader("11");
		viewConfig($option);
		break;	
	case 'saveconfig' :
		HTML_vikbooking::printHeader();
		saveConfig($option);
		break;
	case 'goconfig' :
		goConfig($option);
		break;
	case 'choosebusy' :
		chooseBusy($option);
		break;
	case 'seasons' :
		HTML_vikbooking::printHeader("13");
		showSeasons($option);
		break;
	case 'newseason' :
		HTML_vikbooking::printHeader("13");
		newSeason($option);
		break;
	case 'editseason' :
		HTML_vikbooking::printHeader("13");
		editSeason($cid[0], $option);
		break;	
	case 'createseason' :
		HTML_vikbooking::printHeader();
		saveSeason($option);
		break;
	case 'createseason_new' :
		saveSeason($option, true);
		break;
	case 'updateseasonstay' :
		updateSeason($option, true);
		break;
	case 'updateseason' :
		HTML_vikbooking::printHeader();
		updateSeason($option);
		break;
	case 'removeseasons' :
		HTML_vikbooking::printHeader();
		removeSeasons($cid, $option);
		break;	
	case 'cancelseason' :
		HTML_vikbooking::printHeader();
		cancelEditingSeason($option);
		break;
	case 'payments' :
		HTML_vikbooking::printHeader("14");
		showPayments($option);
		break;
	case 'newpayment' :
		HTML_vikbooking::printHeader("14");
		newPayment($option);
		break;
	case 'editpayment' :
		HTML_vikbooking::printHeader("14");
		editPayment($cid[0], $option);
		break;	
	case 'createpayment' :
		HTML_vikbooking::printHeader();
		savePayment($option);
		break;
	case 'updatepayment' :
		HTML_vikbooking::printHeader();
		updatePayment($option);
		break;	
	case 'removepayments' :
		HTML_vikbooking::printHeader();
		removePayments($cid, $option);
		break;	
	case 'cancelpayment' :
		HTML_vikbooking::printHeader();
		cancelEditingPayment($option);
		break;
	case 'modavailpayment' :
		HTML_vikbooking::printHeader();
		modAvailPayment($cid[0], $option);
		break;
	case 'setordconfirmed' :
		setOrderConfirmed($cid[0], $option);
		break;
	case 'overview' :
		HTML_vikbooking::printHeader("15");
		showOverview($option);
		break;
	case 'viewcustomf' :
		HTML_vikbooking::printHeader("16");
		viewCustomf($option);
		break;
	case 'newcustomf' :
		HTML_vikbooking::printHeader("16");
		newCustomf($option);
		break;
	case 'editcustomf' :
		HTML_vikbooking::printHeader("16");
		editCustomf($cid[0], $option);
		break;	
	case 'createcustomf' :
		HTML_vikbooking::printHeader();
		saveCustomf($option);
		break;
	case 'updatecustomf' :
		HTML_vikbooking::printHeader();
		updateCustomf($option);
		break;	
	case 'removecustomf' :
		HTML_vikbooking::printHeader();
		removeCustomf($cid, $option);
		break;	
	case 'cancelcustomf' :
		HTML_vikbooking::printHeader();
		cancelEditingCustomf($option);
		break;
	case 'sortfield' :
		sortField($option);
		break;
	case 'removemoreimgs' :
		removeMoreImgs($option);
		break;
	case 'viewcoupons' :
		HTML_vikbooking::printHeader("17");
		viewCoupons($option);
		break;
	case 'newcoupon' :
		HTML_vikbooking::printHeader("17");
		newCoupon($option);
		break;
	case 'editcoupon' :
		HTML_vikbooking::printHeader("17");
		editCoupon($cid[0], $option);
		break;	
	case 'createcoupon' :
		HTML_vikbooking::printHeader();
		saveCoupon($option);
		break;
	case 'updatecoupon' :
		HTML_vikbooking::printHeader();
		updateCoupon($option);
		break;	
	case 'removecoupons' :
		HTML_vikbooking::printHeader();
		removeCoupons($cid, $option);
		break;	
	case 'cancelcoupon' :
		HTML_vikbooking::printHeader();
		cancelEditingCoupon($option);
		break;
	case 'rooms' :
		HTML_vikbooking::printHeader("7");
		viewRoom($option);
		break;
	case 'restrictions' :
		HTML_vikbooking::printHeader("restrictions");
		viewRestrictions($option);
		break;
	case 'newrestriction' :
		HTML_vikbooking::printHeader("restrictions");
		newRestriction($option);
		break;
	case 'editrestriction' :
		HTML_vikbooking::printHeader("restrictions");
		editRestriction($cid[0], $option);
		break;	
	case 'createrestriction' :
		HTML_vikbooking::printHeader();
		saveRestriction($option);
		break;
	case 'updaterestriction' :
		HTML_vikbooking::printHeader();
		updateRestriction($option);
		break;	
	case 'removerestrictions' :
		HTML_vikbooking::printHeader();
		removeRestrictions($cid, $option);
		break;	
	case 'cancelrestriction' :
		HTML_vikbooking::printHeader();
		cancelEditingRestriction($option);
		break;
	case 'sortoption' :
		sortOption($option);
		break;
	case 'resendordemail' :
		resendOrderEmail($cid[0], $option);
		break;
	case 'csvexportprepare' :
		csvExportPrepare($option);
		break;
	case 'csvexportlaunch' :
		csvExportLaunch($option);
		break;
	case 'icsexportprepare' :
		icsExportPrepare($option);
		break;
	case 'icsexportlaunch' :
		icsExportLaunch($option);
		break;
	case 'renewsession' :
		renewSession($option);
		break;
	case 'loadpaymentparams' :
		loadPaymentParams();
		break;
	case 'cancelbusyvcm' :
		HTML_vikbooking::printHeader();
		cancelBusyVcm($option);
		break;
	case 'ratesoverview' :
		HTML_vikbooking::printHeader("20");
		ratesOverview($cid[0], $option);
		break;
	case 'calc_rates' :
		calculateRates($option);
		break;
	case 'translations' :
		HTML_vikbooking::printHeader("21");
		viewTranslations($option);
		break;
	case 'savetranslationstay' :
		saveTranslations($option, true);
		break;
	case 'savetranslation' :
		saveTranslations($option);
		break;
	case 'sortpayment' :
		sortPayment($cid[0], $option);
		break;
	case 'sendcancordemail' :
		resendOrderEmail($cid[0], $option, true);
		break;
	case 'unlockrecords' :
		unlockRecords($cid, $option);
		break;
	case 'customers' :
		HTML_vikbooking::printHeader("22");
		viewCustomers($option);
		break;
	case 'newcustomer' :
		HTML_vikbooking::printHeader("22");
		newCustomer($option);
		break;
	case 'editcustomer' :
		HTML_vikbooking::printHeader("22");
		editCustomer($cid[0], $option);
		break;
	case 'savecustomer' :
		saveCustomer($option);
		break;
	case 'updatecustomer' :
		updateCustomer($option);
		break;
	case 'updatecustomerstay' :
		updateCustomer($option, true);
		break;
	case 'removecustomers' :
		removeCustomers($cid, $option);
		break;
	case 'cancelcustomer' :
		cancelEditingCustomer($option);
		break;
	case 'invoke_vcm' :
		invokeChannelManager($cid, $option);
		break;
	case 'multiphotosupload' :
		multiPhotosUpload($option);
		break;
	case 'edittmplfile' :
		editTmplFile($option);
		break;
	case 'savetmplfile' :
		saveTmplFile($option);
		break;
	case 'packages' :
		HTML_vikbooking::printHeader("packages");
		viewPackages($option);
		break;
	case 'newpackage' :
		HTML_vikbooking::printHeader("packages");
		newPackage($option);
		break;
	case 'editpackage' :
		HTML_vikbooking::printHeader("packages");
		editPackage($cid[0], $option);
		break;
	case 'createpackage' :
		savePackage($option);
		break;
	case 'createpackagestay' :
		savePackage($option, true);
		break;
	case 'updatepackage' :
		updatePackage($option);
		break;
	case 'updatepackagestay' :
		updatePackage($option, true);
		break;
	case 'removepackages' :
		removePackages($cid, $option);
		break;
	case 'cancelpackages' :
		cancelEditingPackages($option);
		break;
	case 'dayselectioncount' :
		daySelectionCount($option);
		break;
	case 'searchcustomer' :
		searchCustomer($option);
		break;
	case 'stats' :
		//HTML_vikbooking::printHeader("stats");
		viewStats($option);
		break;
	case 'loadsmsparams' :
		loadSMSParams();
		break;
	case 'loadsmsbalance' :
		loadSMSBalance();
		break;
	case 'sendcustomsms' :
		sendCustomSMS($option);
		break;
	case 'loadcronparams' :
		loadCronParams();
		break;
	case 'crons' :
		HTML_vikbooking::printHeader("crons");
		viewCrons($option);
		break;
	case 'newcron' :
		HTML_vikbooking::printHeader("crons");
		newCron($option);
		break;
	case 'editcron' :
		HTML_vikbooking::printHeader("crons");
		editCron($cid[0], $option);
		break;
	case 'createcron' :
		saveCron($option);
		break;
	case 'createcronstay' :
		saveCron($option, true);
		break;
	case 'updatecron' :
		updateCron($option);
		break;
	case 'updatecronstay' :
		updateCron($option, true);
		break;
	case 'removecrons' :
		removeCrons($cid, $option);
		break;
	case 'cancelcrons' :
		cancelEditingCrons($option);
		break;
	case 'cronlogs' :
		showCronLogs($option);
		break;
	case 'cron_exec' :
		executeCron($option);
		break;
	case 'downloadcron' :
		downloadCron($option);
		break;
	case 'geninvoices' :
		generateInvoices($cid, $option);
		break;
	case 'invoices' :
		HTML_vikbooking::printHeader("invoices");
		viewInvoices($option);
		break;
	case 'resendinvoices' :
		resendInvoices($cid, $option);
		break;
	case 'downloadinvoices' :
		downloadInvoices($cid, $option);
		break;
	case 'removeinvoices' :
		removeInvoices($cid, $option);
		break;
	default :
		HTML_vikbooking::printHeader("18");
		viewDashboard($option);
		break;
}

if(vikbooking::showFooter()) HTML_vikbooking::printFooter();

function loadSMSBalance () {
	$html = 'Error1 [N/A]';
	$sms_api = vikbooking::getSMSAPIClass();
	if(file_exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_vikbooking'.DS.'smsapi'.DS.$sms_api)) {
		require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_vikbooking'.DS.'smsapi'.DS.$sms_api);
		$sms_obj = new VikSmsApi(array(), vikbooking::getSMSParams());
		if(method_exists('VikSmsApi', 'estimate')) {
			$array_result = $sms_obj->estimate("+393711271611", "estimate credit");
			if( $array_result->errorCode != 0 ) {
				$html = 'Error3 ['.$array_result->errorMsg.']';
			}else {
				$html = vikbooking::getCurrencySymb().' '.$array_result->userCredit;
			}
		}else {
			$html = 'Error2 [N/A]';
		}
	}
	echo $html;
	exit;
}

function loadSMSParams () {
	$html = '---------';
	$phpfile = JRequest::getString('phpfile', '', 'request');
	if (!empty($phpfile)) {
		$sms_api = vikbooking::getSMSAPIClass();
		$sms_params = $sms_api == $phpfile ? vikbooking::getSMSParams(false) : '';
		$html = vikbooking::displaySMSParameters($phpfile, $sms_params);
	}
	echo $html;
	exit;
}

function loadCronParams () {
	$html = '---------';
	$phpfile = JRequest::getString('phpfile', '', 'request');
	if (!empty($phpfile)) {
		$html = vikbooking::displayCronParameters($phpfile);
	}
	echo $html;
	exit;
}

function loadPaymentParams () {
	$html = '---------';
	$phpfile = JRequest::getString('phpfile', '', 'request');
	if (!empty($phpfile)) {
		$html = vikbooking::displayPaymentParameters($phpfile);
	}
	echo $html;
	exit;
}

function viewInvoices($option) {
	$dbo = JFactory::getDBO();
	$mainframe = JFactory::getApplication();
	$session = JFactory::getSession();
	$lim = $mainframe->getUserStateFromRequest("$option.limit", 'limit', $mainframe->getCfg('list_limit'), 'int');
	$lim0 = JRequest::getVar('limitstart', 0, '', 'int');
	$search_clauses = array();
	$pfilterinvoices = JRequest::getString('filterinvoices', '', 'request');
	$pmonyear = JRequest::getString('monyear', '', 'request');
	$ts_filter_start = 0;
	$ts_filter_end = 0;
	if(!empty($pmonyear)) {
		$monyear_parts = explode('_', trim($pmonyear));
		$ts_filter_start = mktime(0, 0, 0, (int)$monyear_parts[1], 1, (int)$monyear_parts[0]);
		if(!empty($ts_filter_start)) {
			$ts_filter_end = mktime(23, 59, 59, (int)$monyear_parts[1], date('t', $ts_filter_start), (int)$monyear_parts[0]);
			if(!empty($ts_filter_end)) {
				$search_clauses[] = "`i`.`for_date`>=".$ts_filter_start;
				$search_clauses[] = "`i`.`for_date`<=".$ts_filter_end;
			}
		}
	}
	if(!empty($pfilterinvoices)) {
		$filter_clause = "(`i`.`number` LIKE ".$dbo->quote('%'.$pfilterinvoices.'%')." OR CONCAT_WS(' ',`c`.`first_name`,`c`.`last_name`) LIKE ".$dbo->quote('%'.$pfilterinvoices.'%')." OR `o`.`custmail` LIKE ".$dbo->quote('%'.$pfilterinvoices.'%').")";
		$search_clauses[] = $filter_clause;
	}
	$rows = "";
	$navbut = "";
	//get months archive
	$archive = array();
	$q = "SELECT `i`.`id`,`i`.`idorder`,`i`.`for_date`,`o`.`ts` FROM `#__vikbooking_invoices` AS `i` LEFT JOIN `#__vikbooking_orders` `o` ON `o`.`id`=`i`.`idorder` ORDER BY `i`.`for_date` DESC;";
	$dbo->setQuery($q);
	$dbo->Query($q);
	if($dbo->getNumRows() > 0) {
		$invoices = $dbo->loadAssocList();
		foreach ($invoices as $k => $invoice) {
			$info_date = getdate($invoice['for_date']);
			$key_str = $info_date['year'].'_'.$info_date['mon'];
			if(array_key_exists($key_str, $archive)) {
				$archive[$key_str] += 1;
			}else {
				$archive[$key_str] = 1;
			}
		}
	}
	//
	$q = "SELECT SQL_CALC_FOUND_ROWS `i`.*,`o`.`custdata`,`o`.`ts`,`o`.`status`,`o`.`days`,`o`.`checkin`,`o`.`checkout`,`o`.`custmail`,`o`.`total`,`o`.`adminnotes`,`o`.`country`,`o`.`phone`, CONCAT_WS(' ',`c`.`first_name`,`c`.`last_name`) AS `customer_name`,`nat`.`country_name` " .
		"FROM `#__vikbooking_invoices` AS `i` " .
		"LEFT JOIN `#__vikbooking_orders` `o` ON `o`.`id`=`i`.`idorder` " .
		"LEFT JOIN `#__vikbooking_customers` `c` ON `c`.`id`=`i`.`idcustomer` " .
		"LEFT JOIN `#__vikbooking_countries` `nat` ON `nat`.`country_3_code`=`o`.`country` " .
		(count($search_clauses) > 0 ? "WHERE ".implode(' AND ', $search_clauses)." " : "").
		"ORDER BY `i`.`for_date` DESC, `i`.`idorder` DESC";
	if(!empty($ts_filter_start) && !empty($ts_filter_end)) {
		//month-year filter
		$q .= ";";
		$lim0 = 0;
		$dbo->setQuery($q);
		$dbo->Query($q);
		if ($dbo->getNumRows() > 0) {
			$rows = $dbo->loadAssocList();
		}
	}else {
		$dbo->setQuery($q, $lim0, $lim);
		$dbo->Query($q);
		if ($dbo->getNumRows() > 0) {
			$rows = $dbo->loadAssocList();
			$dbo->setQuery('SELECT FOUND_ROWS();');
			jimport('joomla.html.pagination');
			$pageNav = new JPagination( $dbo->loadResult(), $lim0, $lim );
			$navbut="<table align=\"center\"><tr><td>".$pageNav->getListFooter()."</td></tr></table>";
		}
	}
	HTML_vikbooking::pViewInvoices($rows, $archive, $option, $lim0, $navbut);
}

function downloadInvoices ($ids, $option) {
	if (@count($ids) > 0) {
		$dbo = JFactory::getDBO();
		$q = "SELECT * FROM `#__vikbooking_invoices` WHERE `id` IN (".implode(', ', $ids).");";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if($dbo->getNumRows() > 0) {
			$invoices = $dbo->loadAssocList();
			if(!(count($invoices) > 1)) {
				//Single Invoice Download
				if(file_exists(JPATH_SITE.DS.'components'.DS.'com_vikbooking'.DS.'helpers'.DS.'invoices'.DS.'generated'.DS.$invoices[0]['file_name'])) {
					header("Content-type:application/pdf");
					header("Content-Disposition:attachment;filename=".$invoices[0]['file_name']);
					readfile(JPATH_SITE.DS.'components'.DS.'com_vikbooking'.DS.'helpers'.DS.'invoices'.DS.'generated'.DS.$invoices[0]['file_name']);
					exit;
				}
			}else {
				//Multiple Invoices Download
				$to_zip = array();
				foreach ($invoices as $k => $invoice) {
					$to_zip[$k]['name'] = $invoice['file_name'];
					$to_zip[$k]['path'] = JPATH_SITE.DS.'components'.DS.'com_vikbooking'.DS.'helpers'.DS.'invoices'.DS.'generated'.DS.$invoice['file_name'];
				}
				if(class_exists('ZipArchive')) {
					$zip_path = JPATH_SITE.DS.'components'.DS.'com_vikbooking'.DS.'helpers'.DS.'invoices'.DS.'generated'.DS.date('Y-m-d').'-invoices.zip';
					$zip = new ZipArchive;
					$zip->open($zip_path, ZipArchive::CREATE);
					foreach ($to_zip as $k => $zipv) {
						$zip->addFile($zipv['path'], $zipv['name']);
					}
					$zip->close();
					header("Content-type:application/zip");
					header("Content-Disposition:attachment;filename=".date('Y-m-d').'-invoices.zip');
					header("Content-Length:".filesize($zip_path));
					readfile($zip_path);
					unlink($zip_path);
					exit;
				}else {
					//Class ZipArchive does not exist
					JError::raiseWarning('', 'Class ZipArchive does not exists on your server. Download the files one by one.');
				}
			}
		}
	}
	$mainframe = JFactory::getApplication();
	$mainframe->redirect("index.php?option=".$option."&task=invoices");
}

function resendInvoices($ids, $option) {
	$mainframe = JFactory::getApplication();
	if(!(count($ids) > 0)) {
		$mainframe->redirect("index.php?option=".$option."&task=invoices");
		exit;
	}
	$dbo = JFactory::getDBO();
	$bookings = array();
	$q = "SELECT `i`.`id` AS `id_invoice`,`o`.*,`co`.`idcustomer`,CONCAT_WS(' ',`c`.`first_name`,`c`.`last_name`) AS `customer_name`,`c`.`pin` AS `customer_pin`,`nat`.`country_name` FROM `#__vikbooking_invoices` AS `i` LEFT JOIN `#__vikbooking_orders` `o` ON `o`.`id`=`i`.`idorder` LEFT JOIN `#__vikbooking_customers_orders` `co` ON `co`.`idorder`=`o`.`id` LEFT JOIN `#__vikbooking_customers` `c` ON `c`.`id`=`co`.`idcustomer` LEFT JOIN `#__vikbooking_countries` `nat` ON `nat`.`country_3_code`=`o`.`country` WHERE `i`.`id` IN (".implode(', ', $ids).") AND `o`.`status`='confirmed' AND `o`.`total` > 0 ORDER BY `o`.`id` ASC;";
	$dbo->setQuery($q);
	$dbo->Query($q);
	if($dbo->getNumRows() > 0) {
		$bookings = $dbo->loadAssocList();
	}
	if(!(count($bookings) > 0)) {
		JError::raiseWarning('', JText::_('VBOGENINVERRNOBOOKINGS'));
		$mainframe->redirect("index.php?option=".$option."&task=invoices");
		exit;
	}
	$tot_generated = 0;
	$tot_sent = 0;
	foreach ($bookings as $bkey => $booking) {
		$send_res = vikbooking::sendBookingInvoice($booking['id_invoice'], $booking);
		if($send_res !== false) {
			$tot_sent++;
		}
	}
	$mainframe->enqueueMessage(JText::sprintf('VBOTOTINVOICESGEND', $tot_generated, $tot_sent));
	$mainframe->redirect("index.php?option=".$option."&task=invoices");
}

function removeInvoices ($ids, $option) {
	$tot_removed = 0;
	if (@count($ids)) {
		$dbo = JFactory::getDBO();
		foreach($ids as $d){
			$q = "SELECT * FROM `#__vikbooking_invoices` WHERE `id`=".(int)$d.";";
			$dbo->setQuery($q);
			$dbo->Query($q);
			if($dbo->getNumRows() == 1) {
				$cur_invoice = $dbo->loadAssoc();
				if(file_exists(JPATH_SITE.DS.'components'.DS.'com_vikbooking'.DS.'helpers'.DS.'invoices'.DS.'generated'.DS.$cur_invoice['file_name'])) {
					unlink(JPATH_SITE.DS.'components'.DS.'com_vikbooking'.DS.'helpers'.DS.'invoices'.DS.'generated'.DS.$cur_invoice['file_name']);
				}
				$q="DELETE FROM `#__vikbooking_invoices` WHERE `id`=".(int)$d.";";
				$dbo->setQuery($q);
				$dbo->Query($q);
				$tot_removed++;
			}
		}
	}
	$mainframe = JFactory::getApplication();
	$mainframe->enqueueMessage(JText::sprintf('VBOTOTINVOICESRMVD', $tot_removed));
	$mainframe->redirect("index.php?option=".$option."&task=invoices");
}

function generateInvoices($ids, $option) {
	$mainframe = JFactory::getApplication();
	if(!(count($ids) > 0)) {
		$mainframe->redirect("index.php?option=".$option."&task=vieworders");
		exit;
	}
	$dbo = JFactory::getDBO();
	$pinvoice_num = JRequest::getInt('invoice_num', '', 'request');
	$pinvoice_num = $pinvoice_num <= 0 ? 1 : $pinvoice_num;
	$pinvoice_suff = JRequest::getString('invoice_suff', '', 'request');
	$pinvoice_date = JRequest::getString('invoice_date', '', 'request');
	$pcompany_info = JRequest::getString('company_info', '', 'request', JREQUEST_ALLOWHTML);
	$pcompany_info = strpos($pcompany_info, '<') !== false ? $pcompany_info : nl2br($pcompany_info);
	$pinvoice_send = JRequest::getInt('invoice_send', '', 'request');
	$pinvoice_send = $pinvoice_send > 0 ? true : false;
	$bookings = array();
	$q = "SELECT `o`.*,`co`.`idcustomer`,CONCAT_WS(' ',`c`.`first_name`,`c`.`last_name`) AS `customer_name`,`c`.`pin` AS `customer_pin`,`nat`.`country_name` FROM `#__vikbooking_orders` AS `o` LEFT JOIN `#__vikbooking_customers_orders` `co` ON `co`.`idorder`=`o`.`id` LEFT JOIN `#__vikbooking_customers` `c` ON `c`.`id`=`co`.`idcustomer` LEFT JOIN `#__vikbooking_countries` `nat` ON `nat`.`country_3_code`=`o`.`country` WHERE `o`.`id` IN (".implode(', ', $ids).") AND `o`.`status`='confirmed' AND `o`.`total` > 0 ORDER BY `o`.`id` ASC;";
	$dbo->setQuery($q);
	$dbo->Query($q);
	if($dbo->getNumRows() > 0) {
		$bookings = $dbo->loadAssocList();
	}
	if(!(count($bookings) > 0)) {
		JError::raiseWarning('', JText::_('VBOGENINVERRNOBOOKINGS'));
		$mainframe->redirect("index.php?option=".$option."&task=vieworders");
		exit;
	}
	$tot_generated = 0;
	$tot_sent = 0;
	foreach ($bookings as $bkey => $booking) {
		$gen_res = vikbooking::generateBookingInvoice($booking, $pinvoice_num, $pinvoice_suff, $pinvoice_date, $pcompany_info);
		if($gen_res !== false && $gen_res > 0) {
			$tot_generated++;
			$pinvoice_num++;
			if($pinvoice_send) {
				$send_res = vikbooking::sendBookingInvoice($gen_res, $booking);
				if($send_res !== false) {
					$tot_sent++;
				}
			}
		}else {
			JError::raiseWarning('', JText::sprintf('VBOGENINVERRBOOKING', $booking['id']));
		}
	}
	if($tot_generated > 0) {
		$q = "UPDATE `#__vikbooking_config` SET `setting`='".($pinvoice_num - 1)."' WHERE `param`='invoiceinum';";
		$dbo->setQuery($q);
		$dbo->Query($q);
	}
	$q = "UPDATE `#__vikbooking_config` SET `setting`=".$dbo->quote($pinvoice_suff)." WHERE `param`='invoicesuffix';";
	$dbo->setQuery($q);
	$dbo->Query($q);
	$q = "UPDATE `#__vikbooking_config` SET `setting`=".$dbo->quote($pcompany_info)." WHERE `param`='invcompanyinfo';";
	$dbo->setQuery($q);
	$dbo->Query($q);
	$mainframe->enqueueMessage(JText::sprintf('VBOTOTINVOICESGEND', $tot_generated, $tot_sent));
	$mainframe->redirect("index.php?option=".$option."&task=vieworders");
}

function downloadCron($option) {
	$pcron_id = JRequest::getInt('cron_id', '', 'request');
	$pcron_name = JRequest::getString('cron_name', '', 'request');
	
	$file_cont = '<?php
/**------------------------------------------------------------------------
 * com_vikbooking - VikBooking
 * ------------------------------------------------------------------------
 * author    Alessio Gaggii - e4j - Extensionsforjoomla.com
 * copyright Copyright (C) 2016 e4j - Extensionsforjoomla.com. All Rights Reserved.
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * Websites: https://e4j.com
 * Technical Support:  tech@e4j.com
 * ------------------------------------------------------------------------
*/
$cron_id = "'.$pcron_id.'";
$cron_key = "'.vikbooking::getCronKey().'";
$url = "'.JURI::root().'index.php?option=com_vikbooking&task=cron_exec&tmpl=component";

$fields = array(
	"cron_id" => $cron_id,
	"cronkey" => md5($cron_key),
);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
curl_setopt($ch, CURLOPT_TIMEOUT, 20);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, count($fields));
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));
$res = curl_exec($ch);
curl_close($ch);

echo $res;';
	
	header("Cache-Control: public");
	header("Content-Description: File Transfer");
	header("Content-Length: ".filesize($file_cont).";");
	header("Content-Disposition: attachment; filename=".$pcron_name.".php");
	header("Content-Type: application/php; "); 
	header("Content-Transfer-Encoding: binary");

	echo $file_cont;
	die;
}

function executeCron($option) {
	$dbo = JFactory::getDBO();
	$pcron_id = JRequest::getInt('cron_id', '', 'request');
	$pcronkey = JRequest::getString('cronkey', '', 'request');
	if($pcronkey != vikbooking::getCronKey()) {
		echo 'Error1';
		die;
	}
	$q = "SELECT * FROM `#__vikbooking_cronjobs` WHERE `id`=".(int)$pcron_id.";";
	$dbo->setQuery($q);
	$dbo->Query($q);
	if($dbo->getNumRows() == 1) {
		$cron_data = $dbo->loadAssoc();
		if(file_exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_vikbooking'.DS.'cronjobs'.DS.$cron_data['class_file'])) {
			//
			ob_start();
			require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_vikbooking'.DS.'cronjobs'.DS.$cron_data['class_file']);
			$cron_obj = new VikCronJob($cron_data['id'], json_decode($cron_data['params'], true));
			$cron_obj->debug = true;
			$run_res = $cron_obj->run();
			$cron_output = ob_get_contents();
			ob_end_clean();
			$cron_obj->afterRun();
			//
			HTML_vikbooking::pExecuteCron($cron_data, $run_res, $cron_output, $cron_obj, $option);
		}else {
			echo 'Error2';
			die;
		}
	}
}

function showCronLogs($option) {
	$dbo = JFactory::getDBO();
	$pcron_id = JRequest::getInt('cron_id', '', 'request');
	$q = "SELECT * FROM `#__vikbooking_cronjobs` WHERE `id`=".(int)$pcron_id.";";
	$dbo->setQuery($q);
	$dbo->Query($q);
	if($dbo->getNumRows() == 1) {
		$cron_data = $dbo->loadAssoc();
		$cron_data['logs'] = empty($cron_data['logs']) ? '--------' : $cron_data['logs'];
		echo '<pre>'.print_r($cron_data['logs'], true).'</pre>';
	}
}

function removeCrons ($ids, $option) {
	if (@count($ids)) {
		$dbo = JFactory::getDBO();
		foreach($ids as $d){
			$q = "SELECT * FROM `#__vikbooking_cronjobs` WHERE `id`=".(int)$d.";";
			$dbo->setQuery($q);
			$dbo->Query($q);
			if($dbo->getNumRows() == 1) {
				$cur_cron = $dbo->loadAssoc();
				//launch uninstall() method if available
				if(file_exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_vikbooking'.DS.'cronjobs'.DS.$cur_cron['class_file'])) {
					require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_vikbooking'.DS.'cronjobs'.DS.$cur_cron['class_file']);
					if (method_exists('VikCronJob', 'uninstall')) {
						$cron_obj = new VikCronJob($cur_cron['id'], json_decode($cur_cron['params'], true));
						$cron_obj->uninstall();
					}
				}
				//
				$q="DELETE FROM `#__vikbooking_cronjobs` WHERE `id`=".(int)$d.";";
				$dbo->setQuery($q);
				$dbo->Query($q);
			}
		}
	}
	$mainframe = JFactory::getApplication();
	$mainframe->redirect("index.php?option=".$option."&task=crons");
}

function updateCron($option, $stay = false) {
	$dbo = JFactory::getDBO();
	$mainframe = JFactory::getApplication();
	$pwhereup = JRequest::getInt('where', '', 'request');
	$pcron_name = JRequest::getString('cron_name', '', 'request');
	$pclass_file = JRequest::getString('class_file', '', 'request');
	$ppublished = JRequest::getString('published', '', 'request');
	$ppublished = intval($ppublished) == 1 ? 1 : 0;
	$vikcronparams = JRequest::getVar('vikcronparams', array(), 'request', 'none', JREQUEST_ALLOWHTML);
	$cronparamarr = array();
	$cronparamstr = '';
	if(count($vikcronparams) > 0) {
		foreach($vikcronparams as $setting => $cont) {
			if (strlen($setting) > 0) {
				$cronparamarr[$setting] = $cont;
			}
		}
		if (count($cronparamarr) > 0) {
			$cronparamstr = json_encode($cronparamarr);
		}
	}
	$goto = "index.php?option=".$option."&task=crons";
	if(empty($pcron_name) || empty($pclass_file) || empty($pwhereup)) {
		$mainframe->redirect($goto);
		exit;
	}
	//launch update() method if available
	if(file_exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_vikbooking'.DS.'cronjobs'.DS.$pclass_file)) {
		require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_vikbooking'.DS.'cronjobs'.DS.$pclass_file);
		if (method_exists('VikCronJob', 'update')) {
			$cron_obj = new VikCronJob($pwhereup, $cronparamarr);
			$cron_obj->update();
		}
	}
	//
	$q = "UPDATE `#__vikbooking_cronjobs` SET `cron_name`=".$dbo->quote($pcron_name).",`class_file`=".$dbo->quote($pclass_file).",`params`=".$dbo->quote($cronparamstr).",`published`=".(int)$ppublished." WHERE `id`=".(int)$pwhereup.";";
	$dbo->setQuery($q);
	$dbo->Query($q);
	$mainframe->enqueueMessage(JText::_('VBOCRONUPDATED'));
	if($stay) {
		$goto = "index.php?option=".$option."&task=editcron&cid[]=".$pwhereup;
	}
	$mainframe->redirect($goto);
}

function saveCron($option, $stay = false) {
	$dbo = JFactory::getDBO();
	$mainframe = JFactory::getApplication();
	$pcron_name = JRequest::getString('cron_name', '', 'request');
	$pclass_file = JRequest::getString('class_file', '', 'request');
	$ppublished = JRequest::getString('published', '', 'request');
	$ppublished = intval($ppublished) == 1 ? 1 : 0;
	$vikcronparams = JRequest::getVar('vikcronparams', array(), 'request', 'none', JREQUEST_ALLOWHTML);
	$cronparamarr = array();
	$cronparamstr = '';
	if(count($vikcronparams) > 0) {
		foreach($vikcronparams as $setting => $cont) {
			if (strlen($setting) > 0) {
				$cronparamarr[$setting] = $cont;
			}
		}
		if (count($cronparamarr) > 0) {
			$cronparamstr = json_encode($cronparamarr);
		}
	}
	$goto = "index.php?option=".$option."&task=crons";
	if(empty($pcron_name) || empty($pclass_file)) {
		$goto = "index.php?option=".$option."&task=newcron";
		$mainframe->redirect($goto);
		exit;
	}
	$q = "INSERT INTO `#__vikbooking_cronjobs` (`cron_name`,`class_file`,`params`,`published`) VALUES (".$dbo->quote($pcron_name).", ".$dbo->quote($pclass_file).", ".$dbo->quote($cronparamstr).", ".(int)$ppublished.");";
	$dbo->setQuery($q);
	$dbo->Query($q);
	$lid = $dbo->insertid();
	if(!empty($lid)) {
		//launch install() method if available
		if(file_exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_vikbooking'.DS.'cronjobs'.DS.$pclass_file)) {
			require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_vikbooking'.DS.'cronjobs'.DS.$pclass_file);
			if (method_exists('VikCronJob', 'install')) {
				$cron_obj = new VikCronJob($lid, $cronparamarr);
				$cron_obj->install();
			}
		}
		//
		$mainframe->enqueueMessage(JText::_('VBOCRONSAVED'));
		if($stay) {
			$goto = "index.php?option=".$option."&task=editcron&cid[]=".$lid;
		}
	}
	$mainframe->redirect($goto);
}

function editCron($cron_id, $option) {
	$dbo = JFactory::getDBO();
	$row = array();
	$q = "SELECT * FROM `#__vikbooking_cronjobs` WHERE `id`=".(int)$cron_id.";";
	$dbo->setQuery($q);
	$dbo->Query($q);
	if($dbo->getNumRows() == 1) {
		$row = $dbo->loadAssoc();
	}
	$allf = glob(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_vikbooking'.DS.'cronjobs'.DS.'*.php');
	if (!(count($allf) > 0) || !(count($row) > 0)) {
		$mainframe = JFactory::getApplication();
		JError::raiseWarning('', 'No class files for creating a cron.');
		$mainframe->redirect("index.php?option=".$option."&task=crons");
		exit;
	}
	HTML_vikbooking::pEditCron($row, $allf, $option);
}

function newCron($option) {
	$allf = glob(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_vikbooking'.DS.'cronjobs'.DS.'*.php');
	if (!(count($allf) > 0)) {
		$mainframe = JFactory::getApplication();
		JError::raiseWarning('', 'No class files for creating a cron.');
		$mainframe->redirect("index.php?option=".$option."&task=crons");
		exit;
	}
	HTML_vikbooking::pNewCron($allf, $option);
}

function viewCrons($option) {
	$dbo = JFactory::getDBO();
	$mainframe = JFactory::getApplication();
	$lim = $mainframe->getUserStateFromRequest("$option.limit", 'limit', $mainframe->getCfg('list_limit'), 'int');
	$lim0 = JRequest::getVar('limitstart', 0, '', 'int');
	$session = JFactory::getSession();
	$pvborderby = JRequest::getString('vborderby', '', 'request');
	$pvbordersort = JRequest::getString('vbordersort', '', 'request');
	$validorderby = array('id', 'cron_name', 'last_exec');
	$orderby = $session->get('vbViewCronsOrderby', 'id');
	$ordersort = $session->get('vbViewCronsOrdersort', 'DESC');
	if (!empty($pvborderby) && in_array($pvborderby, $validorderby)) {
		$orderby = $pvborderby;
		$session->set('vbViewCronsOrderby', $orderby);
		if (!empty($pvbordersort) && in_array($pvbordersort, array('ASC', 'DESC'))) {
			$ordersort = $pvbordersort;
			$session->set('vbViewCronsOrdersort', $ordersort);
		}
	}
	$rows = "";
	$navbut = "";
	$q = "SELECT SQL_CALC_FOUND_ROWS `c`.* FROM `#__vikbooking_cronjobs` AS `c` ORDER BY `c`.`".$orderby."` ".$ordersort;
	$dbo->setQuery($q, $lim0, $lim);
	$dbo->Query($q);
	if ($dbo->getNumRows() > 0) {
		$rows = $dbo->loadAssocList();
		$dbo->setQuery('SELECT FOUND_ROWS();');
		jimport('joomla.html.pagination');
		$pageNav = new JPagination( $dbo->loadResult(), $lim0, $lim );
		$navbut="<table align=\"center\"><tr><td>".$pageNav->getListFooter()."</td></tr></table>";
	}
	HTML_vikbooking::pViewCrons($rows, $option, $lim0, $navbut, $orderby, $ordersort);
}

function sendCustomSMS($option) {
	$mainframe = JFactory::getApplication();
	$pphone = JRequest::getString('phone', '', 'request');
	$psmscont = JRequest::getString('smscont', '', 'request');
	$pgoto = JRequest::getString('goto', '', 'request');
	$pgoto = !empty($pgoto) ? urldecode($pgoto) : 'index.php?option=com_vikbooking';
	if(!empty($pphone) && !empty($psmscont)) {
		$sms_api = vikbooking::getSMSAPIClass();
		$sms_api_params = vikbooking::getSMSParams();
		if(!empty($sms_api) && file_exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_vikbooking'.DS.'smsapi'.DS.$sms_api) && !empty($sms_api_params)) {
			require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_vikbooking'.DS.'smsapi'.DS.$sms_api);
			$sms_obj = new VikSmsApi(array(), $sms_api_params);
			$response_obj = $sms_obj->sendMessage($pphone, $psmscont);
			if( !$sms_obj->validateResponse($response_obj) ) {
				JError::raiseWarning('', $sms_obj->getLog());
			}else {
				$mainframe->enqueueMessage(JText::_('VBSENDSMSOK'));
			}
		}else {
			JError::raiseWarning('', JText::_('VBSENDSMSERRMISSAPI'));
		}
	}else {
		JError::raiseWarning('', JText::_('VBSENDSMSERRMISSDATA'));
	}
	$mainframe->redirect($pgoto);
}

function viewStats($option) {
	$dbo = JFactory::getDBO();
	$session = JFactory::getSession();
	JHtml::_('script', JURI::root().'administrator/components/com_vikbooking/resources/Chart.min.js', false, true, false, false);
	$pdfrom = JRequest::getString('dfrom', '', 'request');
	$sess_from = $session->get('vbViewStatsFrom', '');
	$pdfrom = empty($pdfrom) && !empty($sess_from) ? $sess_from : $pdfrom;
	$fromts = !empty($pdfrom) ? vikbooking::getDateTimestamp($pdfrom, '0', '0') : 0;
	$pdto = JRequest::getString('dto', '', 'request');
	$sess_to = $session->get('vbViewStatsTo', '');
	$pdto = empty($pdto) && !empty($sess_to) ? $sess_to : $pdto;
	$tots = !empty($pdto) ? vikbooking::getDateTimestamp($pdto, '23', '59') : 0;
	$tots = $tots < $fromts ? 0 : $tots;
	//store last dates in session
	if(!empty($pdfrom)) {
		$session->set('vbViewStatsFrom', $pdfrom);
		$session->set('vbViewStatsTo', $pdto);
	}
	//
	$bookings = array();
	$arr_months = array();
	$arr_channels = array();
	$arr_countries = array();
	$arr_totals = array('total_income' => 0, 'total_income_netcmms' => 0, 'total_income_nettax' => 0);
	$q = "SELECT `id`,`ts`,`days`,`checkin`,`checkout`,`totpaid`,`total`,`idorderota`,`channel`,`country`,`tot_taxes`,`tot_city_taxes`,`tot_fees`,`cmms` FROM `#__vikbooking_orders` WHERE `status`='confirmed'".(!empty($fromts) ? " AND `ts`>=".$fromts : "").(!empty($tots) ? " AND `ts`<=".$tots : "")." ORDER BY `ts` ASC;";
	$dbo->setQuery($q);
	$dbo->Query($q);
	if($dbo->getNumRows() > 0) {
		$bookings = $dbo->loadAssocList();
		$first_ts = $bookings[0]['ts'];
		end($bookings);
		$last_ts = $bookings[(key($bookings))]['ts'];
		reset($bookings);
		$fromts = empty($fromts) ? $first_ts : $fromts;
		$tots = empty($tots) ? $last_ts : $tots;
		foreach ($bookings as $o) {
			$info_ts = getdate($o['ts']);
			$monyear = $info_ts['mon'].'-'.$info_ts['year'];
			$arr_totals['total_income'] += $o['total'];
			$arr_totals['total_income_netcmms'] += (float)$o['cmms'];
			$arr_totals['total_income_nettax'] += ($o['total'] - $o['tot_taxes'] - $o['tot_city_taxes'] - $o['tot_fees']);
			if(!empty($o['country'])) {
				if(!array_key_exists($o['country'], $arr_countries)) {
					$arr_countries[$o['country']] = 1;
				}else {
					$arr_countries[$o['country']]++;
				}
			}
			if(empty($o['channel']) || stripos($o['channel'], 'channel manager') !== false) {
				$channel = JText::_('VBOIBECHANNEL');
			}else {
				$ch_parts = explode('_', $o['channel']);
				$channel = array_key_exists(1, $ch_parts) && !empty($ch_parts[1]) ? $ch_parts[1] : $ch_parts[0];
			}
			if(!in_array($channel, $arr_channels)) {
				$arr_channels[] = $channel;
			}
			if(!array_key_exists($monyear, $arr_months)) {
				$arr_months[$monyear] = array();
			}
			if(!array_key_exists($channel, $arr_months[$monyear])) {
				$arr_months[$monyear][$channel] = array($o);
			}else {
				$arr_months[$monyear][$channel][] = $o;
			}
		}
		$arr_totals['total_income_netcmms'] = $arr_totals['total_income'] - $arr_totals['total_income_netcmms'];
		if(count($arr_countries)) {
			asort($arr_countries);
			$arr_countries = array_reverse($arr_countries, true);
			$all_countries = array_keys($arr_countries);
			foreach ($all_countries as $kc => $country) {
				$all_countries[$kc] = $dbo->quote($country);
			}
			$q = "SELECT `country_name`,`country_3_code` FROM `#__vikbooking_countries` WHERE `country_3_code` IN (".implode(',', $all_countries).");";
			$dbo->setQuery($q);
			$dbo->Query($q);
			if($dbo->getNumRows() > 0) {
				$countries_names = $dbo->loadAssocList();
				foreach ($countries_names as $kc => $vc) {
					$country_flag = '';
					if(file_exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_vikbooking'.DS.'resources'.DS.'countries'.DS.$vc['country_3_code'].'.png')) {
						$country_flag = '<img src="'.JURI::root().'administrator/components/com_vikbooking/resources/countries/'.$vc['country_3_code'].'.png'.'" title="'.$vc['country_name'].'" />';
					}
					$arr_countries[$vc['country_3_code']] = array('country_name' => $vc['country_name'], 'tot_bookings' => $arr_countries[$vc['country_3_code']], 'img' => $country_flag);
				}
			}else {
				$arr_countries = array();
			}
		}
	}
	HTML_vikbooking::pViewStats($bookings, $fromts, $tots, $arr_months, $arr_channels, $arr_countries, $arr_totals, $option);
}

function searchCustomer($option) {
	//this function is called via ajax
	$kw = JRequest::getString('kw', '', 'request');
	$cstring = '';
	if(strlen($kw) > 0) {
		$dbo = JFactory::getDBO();
		$q = "SELECT * FROM `#__vikbooking_customers` WHERE CONCAT_WS(' ', `first_name`, `last_name`) LIKE ".$dbo->quote("%".$kw."%")." OR `email` LIKE ".$dbo->quote("%".$kw."%")." OR `pin` LIKE ".$dbo->quote("%".$kw."%")." ORDER BY `first_name` ASC;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if($dbo->getNumRows() > 0) {
			$customers = $dbo->loadAssocList();
			$cust_old_fields = array();
			$cstring_search = '';
			foreach ($customers as $k => $v) {
				$cstring_search .= '<div class="vbo-custsearchres-entry" data-custid="'.$v['id'].'" data-email="'.$v['email'].'" data-phone="'.addslashes($v['phone']).'" data-country="'.$v['country'].'" data-pin="'.$v['pin'].'" data-firstname="'.addslashes($v['first_name']).'" data-lastname="'.addslashes($v['last_name']).'">'."\n";
				$cstring_search .= '<span class="vbo-custsearchres-name hasTooltip" title="'.$v['email'].'">'.$v['first_name'].' '.$v['last_name'].'</span>'."\n";
				$cstring_search .= '<span class="vbo-custsearchres-pin">'.$v['pin'].'</span>'."\n";
				if(!empty($v['country'])) {
					if(file_exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_vikbooking'.DS.'resources'.DS.'countries'.DS.$v['country'].'.png')) {
						$cstring_search .= '<span class="vbo-custsearchres-cflag"><img src="'.JURI::root().'administrator/components/com_vikbooking/resources/countries/'.$v['country'].'.png'.'" title="'.$v['country'].'" class="vbo-country-flag"/></span>'."\n";
					}
				}
				$cstring_search .= '</div>'."\n";
				if(!empty($v['cfields'])) {
					$oldfields = json_decode($v['cfields'], true);
					if(is_array($oldfields) && count($oldfields)) {
						$cust_old_fields[$v['id']] = $oldfields;
					}
				}
			}
			$cstring = json_encode(array($cust_old_fields, $cstring_search));
		}
	}
	echo $cstring;
	exit;
}

function updatePackage($option, $stay = false) {
	$dbo = JFactory::getDBO();
	$mainframe = JFactory::getApplication();
	$pwhereup = JRequest::getInt('whereup', '', 'request');
	$q = "SELECT * FROM `#__vikbooking_packages` WHERE `id`=".(int)$pwhereup.";";
	$dbo->setQuery($q);
	$dbo->Query($q);
	if($dbo->getNumRows() == 1) {
		$pkg_data = $dbo->loadAssoc();
	}else {
		JError::raiseWarning('', 'Not Found.');
		$mainframe->redirect("index.php?option=".$option."&task=packages");
		exit;
	}
	$pname = JRequest::getString('name', '', 'request');
	$palias = JRequest::getString('alias', '', 'request');
	$palias = empty($palias) ? $pname : $palias;
	$palias = JFilterOutput::stringURLSafe($palias);
	$pimg = JRequest::getVar('img', null, 'files', 'array');
	$pfrom = JRequest::getString('from', '', 'request');
	$pto = JRequest::getString('to', '', 'request');
	$pexcludeday = JRequest::getVar('excludeday', array());
	$strexcldates = array();
	foreach ($pexcludeday as $exclday) {
		if(!empty($exclday)) {
			$strexcldates[] = $exclday;
		}
	}
	$strexcldates = implode(';', $strexcldates);
	$prooms = JRequest::getVar('rooms', array());
	$pminlos = JRequest::getInt('minlos', '', 'request');
	$pminlos = $pminlos < 1 ? 1 : $pminlos;
	$pmaxlos = JRequest::getInt('maxlos', '', 'request');
	$pmaxlos = $pmaxlos < 0 ? 0 : $pmaxlos;
	$pmaxlos = $pmaxlos < $pminlos ? 0 : $pmaxlos;
	$pcost = JRequest::getString('cost', '', 'request');
	$paliq = JRequest::getInt('aliq', '', 'request');
	$ppernight_total = JRequest::getInt('pernight_total', '', 'request');
	$ppernight_total = $ppernight_total == 1 ? 1 : 2;
	$pperperson = JRequest::getInt('perperson', '', 'request');
	$pperperson = $pperperson > 0 ? 1 : 0;
	$pshowoptions = JRequest::getInt('showoptions', '', 'request');
	$pshowoptions = $pshowoptions >= 1 && $pshowoptions <= 3 ? $pshowoptions : 1;
	$pdescr = JRequest::getString('descr', '', 'request', JREQUEST_ALLOWRAW);
	$pshortdescr = JRequest::getString('shortdescr', '', 'request', JREQUEST_ALLOWHTML);
	$pconditions = JRequest::getString('conditions', '', 'request', JREQUEST_ALLOWRAW);
	$pbenefits = JRequest::getString('benefits', '', 'request', JREQUEST_ALLOWHTML);
	$ptsinit = vikbooking::getDateTimestamp($pfrom, '0', '0');
	$ptsend = vikbooking::getDateTimestamp($pto, '23', '59');
	$ptsinit = empty($ptsinit) ? time() : $ptsinit;
	$ptsend = empty($ptsend) || $ptsend < $ptsinit ? $ptsinit : $ptsend;
	//file upload
	jimport('joomla.filesystem.file');
	$gimg="";
	if(isset($pimg) && strlen(trim($pimg['name']))) {
		$pautoresize = JRequest::getString('autoresize', '', 'request');
		$presizeto = JRequest::getInt('resizeto', '', 'request');
		$creativik = new vikResizer();
		$filename = JFile::makeSafe(str_replace(" ", "_", strtolower($pimg['name'])));
		$src = $pimg['tmp_name'];
		$dest = JPATH_SITE.DS.'components'.DS.'com_vikbooking'.DS.'resources'.DS.'uploads'.DS;
		$j="";
		if (file_exists($dest.$filename)) {
			$j=rand(171, 1717);
			while (file_exists($dest.$j.$filename)) {
				$j++;
			}
		}
		$finaldest=$dest.$j.$filename;
		$check=getimagesize($pimg['tmp_name']);
		if($check[2] & imagetypes()) {
			if (JFile::upload($src, $finaldest)) {
				$gimg=$j.$filename;
				//orig img
				$origmod = true;
				if($pautoresize == "1" && !empty($presizeto)) {
					$origmod = $creativik->proportionalImage($finaldest, $dest.'big_'.$j.$filename, $presizeto, $presizeto);
				}else {
					copy($finaldest, $dest.'big_'.$j.$filename);
				}
				//thumb
				$thumb = $creativik->proportionalImage($finaldest, $dest.'thumb_'.$j.$filename, 250, 250);
				if (!$thumb || !$origmod) {
					if(file_exists($dest.'big_'.$j.$filename)) @unlink($dest.'big_'.$j.$filename);
					if(file_exists($dest.'thumb_'.$j.$filename)) @unlink($dest.'thumb_'.$j.$filename);
					JError::raiseWarning('', 'Error Uploading the File: '.$pimg['name']);
				}
				@unlink($finaldest);
			}else {
				JError::raiseWarning('', 'Error while uploading image');
			}
		}else {
			JError::raiseWarning('', 'Uploaded file is not an Image');
		}
	}
	//
	$goto = "index.php?option=".$option."&task=packages";
	$q = "UPDATE `#__vikbooking_packages` SET `name`=".$dbo->quote($pname).",`alias`=".$dbo->quote($palias)."".(!empty($gimg) ? "`img`=".$dbo->quote($gimg) : "").",`dfrom`=".(int)$ptsinit.",`dto`=".(int)$ptsend.",`excldates`=".$dbo->quote($strexcldates).",`minlos`=".(int)$pminlos.",`maxlos`=".(int)$pmaxlos.",`cost`=".$dbo->quote($pcost).",`idiva`='".$paliq."',`pernight_total`=".(int)$ppernight_total.",`perperson`=".(int)$pperperson.",`descr`=".$dbo->quote($pdescr).",`shortdescr`=".$dbo->quote($pshortdescr).",`benefits`=".$dbo->quote($pbenefits).",`conditions`=".$dbo->quote($pconditions).",`showoptions`=".(int)$pshowoptions." WHERE `id`=".(int)$pwhereup.";";
	$dbo->setQuery($q);
	$dbo->Query($q);
	$q = "DELETE FROM `#__vikbooking_packages_rooms` WHERE `idpackage`=".(int)$pwhereup.";";
	$dbo->setQuery($q);
	$dbo->Query($q);
	foreach ($prooms as $roomid) {
		if(!empty($roomid)) {
			$q = "INSERT INTO `#__vikbooking_packages_rooms` (`idpackage`,`idroom`) VALUES (".(int)$pwhereup.", ".(int)$roomid.");";
			$dbo->setQuery($q);
			$dbo->Query($q);
		}
	}
	$mainframe->enqueueMessage(JText::_('VBOPKGUPDATED'));
	if($stay) {
		$goto = "index.php?option=".$option."&task=editpackage&cid[]=".$pwhereup;
	}
	$mainframe->redirect($goto);
}

function savePackage($option, $stay = false) {
	$dbo = JFactory::getDBO();
	$mainframe = JFactory::getApplication();
	$pname = JRequest::getString('name', '', 'request');
	$palias = JRequest::getString('alias', '', 'request');
	$palias = empty($palias) ? $pname : $palias;
	$palias = JFilterOutput::stringURLSafe($palias);
	$pimg = JRequest::getVar('img', null, 'files', 'array');
	$pfrom = JRequest::getString('from', '', 'request');
	$pto = JRequest::getString('to', '', 'request');
	$pexcludeday = JRequest::getVar('excludeday', array());
	$strexcldates = array();
	foreach ($pexcludeday as $exclday) {
		if(!empty($exclday)) {
			$strexcldates[] = $exclday;
		}
	}
	$strexcldates = implode(';', $strexcldates);
	$prooms = JRequest::getVar('rooms', array());
	$pminlos = JRequest::getInt('minlos', '', 'request');
	$pminlos = $pminlos < 1 ? 1 : $pminlos;
	$pmaxlos = JRequest::getInt('maxlos', '', 'request');
	$pmaxlos = $pmaxlos < 0 ? 0 : $pmaxlos;
	$pmaxlos = $pmaxlos < $pminlos ? 0 : $pmaxlos;
	$pcost = JRequest::getString('cost', '', 'request');
	$paliq = JRequest::getInt('aliq', '', 'request');
	$ppernight_total = JRequest::getInt('pernight_total', '', 'request');
	$ppernight_total = $ppernight_total == 1 ? 1 : 2;
	$pperperson = JRequest::getInt('perperson', '', 'request');
	$pperperson = $pperperson > 0 ? 1 : 0;
	$pshowoptions = JRequest::getInt('showoptions', '', 'request');
	$pshowoptions = $pshowoptions >= 1 && $pshowoptions <= 3 ? $pshowoptions : 1;
	$pdescr = JRequest::getString('descr', '', 'request', JREQUEST_ALLOWRAW);
	$pshortdescr = JRequest::getString('shortdescr', '', 'request', JREQUEST_ALLOWHTML);
	$pconditions = JRequest::getString('conditions', '', 'request', JREQUEST_ALLOWRAW);
	$pbenefits = JRequest::getString('benefits', '', 'request', JREQUEST_ALLOWHTML);
	$ptsinit = vikbooking::getDateTimestamp($pfrom, '0', '0');
	$ptsend = vikbooking::getDateTimestamp($pto, '23', '59');
	$ptsinit = empty($ptsinit) ? time() : $ptsinit;
	$ptsend = empty($ptsend) || $ptsend < $ptsinit ? $ptsinit : $ptsend;
	//file upload
	jimport('joomla.filesystem.file');
	$gimg="";
	if(isset($pimg) && strlen(trim($pimg['name']))) {
		$pautoresize = JRequest::getString('autoresize', '', 'request');
		$presizeto = JRequest::getInt('resizeto', '', 'request');
		$creativik = new vikResizer();
		$filename = JFile::makeSafe(str_replace(" ", "_", strtolower($pimg['name'])));
		$src = $pimg['tmp_name'];
		$dest = JPATH_SITE.DS.'components'.DS.'com_vikbooking'.DS.'resources'.DS.'uploads'.DS;
		$j="";
		if (file_exists($dest.$filename)) {
			$j=rand(171, 1717);
			while (file_exists($dest.$j.$filename)) {
				$j++;
			}
		}
		$finaldest=$dest.$j.$filename;
		$check=getimagesize($pimg['tmp_name']);
		if($check[2] & imagetypes()) {
			if (JFile::upload($src, $finaldest)) {
				$gimg=$j.$filename;
				//orig img
				$origmod = true;
				if($pautoresize == "1" && !empty($presizeto)) {
					$origmod = $creativik->proportionalImage($finaldest, $dest.'big_'.$j.$filename, $presizeto, $presizeto);
				}else {
					copy($finaldest, $dest.'big_'.$j.$filename);
				}
				//thumb
				$thumb = $creativik->proportionalImage($finaldest, $dest.'thumb_'.$j.$filename, 250, 250);
				if (!$thumb || !$origmod) {
					if(file_exists($dest.'big_'.$j.$filename)) @unlink($dest.'big_'.$j.$filename);
					if(file_exists($dest.'thumb_'.$j.$filename)) @unlink($dest.'thumb_'.$j.$filename);
					JError::raiseWarning('', 'Error Uploading the File: '.$pimg['name']);
				}
				@unlink($finaldest);
			}else {
				JError::raiseWarning('', 'Error while uploading image');
			}
		}else {
			JError::raiseWarning('', 'Uploaded file is not an Image');
		}
	}
	//
	$goto = "index.php?option=".$option."&task=packages";
	$q = "INSERT INTO `#__vikbooking_packages` (`name`,`alias`,`img`,`dfrom`,`dto`,`excldates`,`minlos`,`maxlos`,`cost`,`idiva`,`pernight_total`,`perperson`,`descr`,`shortdescr`,`benefits`,`conditions`,`showoptions`) VALUES (".$dbo->quote($pname).", ".$dbo->quote($palias).", ".$dbo->quote($gimg).", ".(int)$ptsinit.", ".(int)$ptsend.", ".$dbo->quote($strexcldates).", ".(int)$pminlos.", ".(int)$pmaxlos.", ".$dbo->quote($pcost).",'".$paliq."', ".(int)$ppernight_total.", ".(int)$pperperson.", ".$dbo->quote($pdescr).", ".$dbo->quote($pshortdescr).", ".$dbo->quote($pbenefits).", ".$dbo->quote($pconditions).", ".(int)$pshowoptions.");";
	$dbo->setQuery($q);
	$dbo->Query($q);
	$lid = $dbo->insertid();
	if(!empty($lid)) {
		$mainframe->enqueueMessage(JText::_('VBOPKGSAVED'));
		if($stay) {
			$goto = "index.php?option=".$option."&task=editpackage&cid[]=".$lid;
		}
		foreach ($prooms as $roomid) {
			if(!empty($roomid)) {
				$q = "INSERT INTO `#__vikbooking_packages_rooms` (`idpackage`,`idroom`) VALUES (".(int)$lid.", ".(int)$roomid.");";
				$dbo->setQuery($q);
				$dbo->Query($q);
			}
		}
	}
	$mainframe->redirect($goto);
}

function editPackage($pid, $option) {
	$dbo = JFactory::getDBO();
	$mainframe = JFactory::getApplication();
	$rooms = array();
	$q = "SELECT `id`,`name` FROM `#__vikbooking_rooms` ORDER BY `name` ASC;";
	$dbo->setQuery($q);
	$dbo->Query($q);
	if ($dbo->getNumRows() > 0) {
		$rooms = $dbo->loadAssocList();
	}else {
		JError::raiseWarning('', JText::_('VBNOROOMSFOUND'));
		$mainframe->redirect("index.php?option=".$option."&task=rooms");
		exit;
	}
	$q = "SELECT * FROM `#__vikbooking_packages` WHERE `id`=".(int)$pid.";";
	$dbo->setQuery($q);
	$dbo->Query($q);
	if ($dbo->getNumRows() == 1) {
		$package = $dbo->loadAssoc();
	}else {
		JError::raiseWarning('', 'Not Found');
		$mainframe->redirect("index.php?option=".$option."&task=packages");
		exit;
	}
	$q = "SELECT * FROM `#__vikbooking_packages_rooms` WHERE `idpackage`=".(int)$pid.";";
	$dbo->setQuery($q);
	$dbo->Query($q);
	if ($dbo->getNumRows() > 0) {
		$rooms_xref = $dbo->loadAssocList();
		foreach ($rooms_xref as $room_xref) {
			foreach ($rooms as $rk => $rv) {
				if($rv['id'] != $room_xref['idroom']) {
					continue;
				}
				$rooms[$rk]['selected'] = 1;
				break;
			}
		}
	}
	HTML_vikbooking::pEditPackage($package, $rooms, $option);
}

function daySelectionCount($option) {
	//this function is called via ajax
	$tsinit = JRequest::getString('dinit', '', 'request');
	$tsend = JRequest::getString('dend', '', 'request');
	if(strlen($tsinit) > 0 && strlen($tsend) > 0) {
		$ptsinit=vikbooking::getDateTimestamp($tsinit, '0', '0');
		$ptsend=vikbooking::getDateTimestamp($tsend, '23', '59');
		$diff = $ptsend - $ptsinit;
		if($diff >= 172800) {
			$datef = vikbooking::getDateFormat(true);
			if ($datef=="%d/%m/%Y") {
				$df = 'd-m-Y';
			}else {
				$df = 'Y-m-d';
			}
			//minimum 2 days for excluding some days
			$daysdiff = floor($diff / 86400);
			$infoinit = getdate($ptsinit);
			$select = '';
			$select .= '<div style="display: inline-block;"><select name="excludeday[]" multiple="multiple" size="'.($daysdiff > 8 ? 8 : $daysdiff).'" id="vboexclusion">';
			for($i = 0; $i <= $daysdiff; $i++) {
				$ts = $i > 0 ? mktime(0, 0, 0, $infoinit['mon'], ((int)$infoinit['mday'] + $i), $infoinit['year']) : $ptsinit;
				$infots = getdate($ts);
				$optval = $infots['mon'].'-'.$infots['mday'].'-'.$infots['year'];
				$select .= '<option value="'.$optval.'">'.date($df, $ts).'</option>';
			}
			$select .= '</select></div>';
			//excluded days of the week
			if($daysdiff >= 14) {
				$select .= '<div style="display: inline-block; margin-left: 40px;"><select name="excludewdays[]" multiple="multiple" size="8" id="excludewdays" onchange="vboExcludeWDays();">';
				$select .= '<optgroup label="'.JText::_('VBOEXCLWEEKD').'">';
				$select .= '<option value="0">'.JText::_('VBSUNDAY').'</option><option value="1">'.JText::_('VBMONDAY').'</option><option value="2">'.JText::_('VBTUESDAY').'</option><option value="3">'.JText::_('VBWEDNESDAY').'</option><option value="4">'.JText::_('VBTHURSDAY').'</option><option value="5">'.JText::_('VBFRIDAY').'</option><option value="6">'.JText::_('VBSATURDAY').'</option>';
				$select .= '</optgroup>';
				$select .= '</select></div>';
			}
			//
			echo $select;
		}else {
			echo '';
		}
	}else {
		echo '';
	}
	exit;
}

function newPackage($option) {
	$dbo = JFactory::getDBO();
	$rooms = array();
	$q = "SELECT `id`,`name` FROM `#__vikbooking_rooms` ORDER BY `name` ASC;";
	$dbo->setQuery($q);
	$dbo->Query($q);
	if ($dbo->getNumRows() > 0) {
		$rooms = $dbo->loadAssocList();
	}else {
		$mainframe = JFactory::getApplication();
		JError::raiseWarning('', JText::_('VBNOROOMSFOUND'));
		$mainframe->redirect("index.php?option=".$option."&task=rooms");
		exit;
	}
	HTML_vikbooking::pNewPackage($rooms, $option);
}

function removePackages ($ids, $option) {
	if (@count($ids)) {
		$dbo = JFactory::getDBO();
		foreach($ids as $d){
			$q="DELETE FROM `#__vikbooking_packages` WHERE `id`=".(int)$d.";";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$q="DELETE FROM `#__vikbooking_packages_rooms` WHERE `idpackage`=".(int)$d.";";
			$dbo->setQuery($q);
			$dbo->Query($q);
		}
	}
	$mainframe = JFactory::getApplication();
	$mainframe->redirect("index.php?option=".$option."&task=packages");
}

function viewPackages($option) {
	$dbo = JFactory::getDBO();
	$mainframe = JFactory::getApplication();
	$lim = $mainframe->getUserStateFromRequest("$option.limit", 'limit', $mainframe->getCfg('list_limit'), 'int');
	$lim0 = JRequest::getVar('limitstart', 0, '', 'int');
	$session = JFactory::getSession();
	$pvborderby = JRequest::getString('vborderby', '', 'request');
	$pvbordersort = JRequest::getString('vbordersort', '', 'request');
	$validorderby = array('id', 'name', 'dfrom', 'dto', 'cost');
	$orderby = $session->get('vbViewPacksOrderby', 'id');
	$ordersort = $session->get('vbViewPacksOrdersort', 'DESC');
	if (!empty($pvborderby) && in_array($pvborderby, $validorderby)) {
		$orderby = $pvborderby;
		$session->set('vbViewPacksOrderby', $orderby);
		if (!empty($pvbordersort) && in_array($pvbordersort, array('ASC', 'DESC'))) {
			$ordersort = $pvbordersort;
			$session->set('vbViewPacksOrdersort', $ordersort);
		}
	}
	$rows = "";
	$navbut = "";
	$q = "SELECT SQL_CALC_FOUND_ROWS `p`.*,(SELECT COUNT(*) FROM `#__vikbooking_packages_rooms` AS `pr` WHERE `pr`.`idpackage`=`p`.`id`) AS `tot_rooms` FROM `#__vikbooking_packages` AS `p` ORDER BY `p`.`".$orderby."` ".$ordersort;
	$dbo->setQuery($q, $lim0, $lim);
	$dbo->Query($q);
	if ($dbo->getNumRows() > 0) {
		$rows = $dbo->loadAssocList();
		$dbo->setQuery('SELECT FOUND_ROWS();');
		jimport('joomla.html.pagination');
		$pageNav = new JPagination( $dbo->loadResult(), $lim0, $lim );
		$navbut="<table align=\"center\"><tr><td>".$pageNav->getListFooter()."</td></tr></table>";
	}
	HTML_vikbooking::pViewPackages($rows, $option, $lim0, $navbut, $orderby, $ordersort);
}

function saveTmplFile($option) {
	$fpath = JRequest::getString('path', '', 'request', JREQUEST_ALLOWRAW);
	$pcont = JRequest::getString('cont', '', 'request', JREQUEST_ALLOWRAW);
	$mainframe = JFactory::getApplication();
	$exists = file_exists($fpath) ? true : false;
	if(!$exists) {
		$fpath = urldecode($fpath);
	}
	$fpath = file_exists($fpath) ? $fpath : '';
	if(!empty($fpath)) {
		$fp = fopen($fpath, 'wb');
		$byt = (int)fwrite($fp, $pcont);
		fclose($fp);
		if ($byt > 0) {
			$mainframe->enqueueMessage(JText::_('VBOUPDTMPLFILEOK'));
		}else {
			JError::raiseWarning('', JText::_('VBOUPDTMPLFILENOBYTES'));
		}
	}else {
		JError::raiseWarning('', JText::_('VBOUPDTMPLFILEERR'));
	}
	$mainframe->redirect("index.php?option=".$option."&task=edittmplfile&path=".$fpath."&tmpl=component");

	exit;
}

function editTmplFile($option) {
	$fpath = JRequest::getString('path', '', 'request', JREQUEST_ALLOWRAW);
	$exists = file_exists($fpath) ? true : false;
	if(!$exists) {
		$fpath = urldecode($fpath);
	}
	$fpath = file_exists($fpath) ? $fpath : '';
	HTML_vikbooking::pEditTmplFile($fpath, $option);
}

function multiPhotosUpload($option) {
	$dbo = JFactory::getDBO();
	$proomid = JRequest::getInt('roomid', '', 'request');
	
	$resp = array('files' => array());
	$error_messages = array(
		1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
		2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
		3 => 'The uploaded file was only partially uploaded',
		4 => 'No file was uploaded',
		6 => 'Missing a temporary folder',
		7 => 'Failed to write file to disk',
		8 => 'A PHP extension stopped the file upload',
		'post_max_size' => 'The uploaded file exceeds the post_max_size directive in php.ini',
		'max_file_size' => 'File is too big',
		'min_file_size' => 'File is too small',
		'accept_file_types' => 'Filetype not allowed',
		'max_number_of_files' => 'Maximum number of files exceeded',
		'max_width' => 'Image exceeds maximum width',
		'min_width' => 'Image requires a minimum width',
		'max_height' => 'Image exceeds maximum height',
		'min_height' => 'Image requires a minimum height',
		'abort' => 'File upload aborted',
		'image_resize' => 'Failed to resize image',
		'vbo_type' => 'The file type cannot be accepted',
		'vbo_jupload' => 'The upload has failed. Check the Joomla Configuration',
		'vbo_perm' => 'Error moving the uploaded files. Check your permissions'
	);

	$creativik = new vikResizer();
	$updpath = JPATH_SITE.DS.'components'.DS.'com_vikbooking'.DS.'resources'.DS.'uploads'.DS;
	$bigsdest = $updpath;
	$thumbsdest = $updpath;
	$dest = $updpath;
	$moreimagestr = '';
	$cur_captions = json_encode(array());

	$q = "SELECT `moreimgs`,`imgcaptions` FROM `#__vikbooking_rooms` WHERE `id`=".$proomid.";";
	$dbo->setQuery($q);
	$dbo->Query($q);
	if($dbo->getNumRows() == 1) {
		$photo_data = $dbo->loadAssocList();
		$cur_captions = $photo_data[0]['imgcaptions'];
		$cur_photos = $photo_data[0]['moreimgs'];
		if(!empty($cur_photos)) {
			$moreimagestr .= $cur_photos;
		} 
	}

	
	$bulkphotos = JRequest::getVar('bulkphotos', null, 'files', 'array');

	if(is_array($bulkphotos) && count($bulkphotos) > 0 && array_key_exists('name', $bulkphotos) && count($bulkphotos['name']) > 0) {
		foreach ($bulkphotos['name'] as $updk => $photoname) {
			$uploaded_image = array();
			$filename = JFile::makeSafe(str_replace(" ", "_", strtolower($photoname)));
			$src = $bulkphotos['tmp_name'][$updk];
			$j="";
			if (file_exists($dest.$filename)) {
				$j=rand(171, 1717);
				while (file_exists($dest.$j.$filename)) {
					$j++;
				}
			}
			$finaldest=$dest.$j.$filename;
			$is_error = false;
			$err_key = '';
			if(array_key_exists('error', $bulkphotos) && array_key_exists($updk, $bulkphotos['error']) && !empty($bulkphotos['error'][$updk])) {
				if(array_key_exists($bulkphotos['error'][$updk], $error_messages)) {
					$is_error = true;
					$err_key = $bulkphotos['error'][$updk];
				}
			}
			if(!$is_error) {
				$check=getimagesize($bulkphotos['tmp_name'][$updk]);
				if($check[2] & imagetypes()) {
					if (JFile::upload($src, $finaldest)) {
						$gimg=$j.$filename;
						//orig img
						$origmod = true;
						copy($finaldest, $bigsdest.'big_'.$j.$filename);
						//thumb
						$thumb = $creativik->proportionalImage($finaldest, $thumbsdest.'thumb_'.$j.$filename, 70, 70);
						if (!$thumb || !$origmod) {
							if(file_exists($bigsdest.'big_'.$j.$filename)) @unlink($bigsdest.'big_'.$j.$filename);
							if(file_exists($thumbsdest.'thumb_'.$j.$filename)) @unlink($thumbsdest.'thumb_'.$j.$filename);
							$is_error = true;
							$err_key = 'vbo_perm';
						}else {
							$moreimagestr.=$j.$filename.";;";
						}
						@unlink($finaldest);
					}else {
						$is_error = true;
						$err_key = 'vbo_jupload';
					}
				}else {
					$is_error = true;
					$err_key = 'vbo_type';
				}
			}
			$img = new stdClass();
			if($is_error) {
				$img->name = '';
				$img->size = '';
				$img->type = '';
				$img->url = '';
				$img->error = array_key_exists($err_key, $error_messages) ? $error_messages[$err_key] : 'Generic Error for Upload';
			}else {
				$img->name = $photoname;
				$img->size = $bulkphotos['size'][$updk];
				$img->type = $bulkphotos['type'][$updk];
				$img->url = JURI::root().'components/com_vikbooking/resources/uploads/big_'.$j.$filename;
			}
			$resp['files'][] = $img;
		}
	}else {
		$res = new stdClass();
		$res->name = '';
		$res->size = '';
		$res->type = '';
		$res->url = '';
		$res->error = 'No images received for upload';
		$resp['files'][] = $res;
	}
	//Update current extra images string
	$q = "UPDATE `#__vikbooking_rooms` SET `moreimgs`=".$dbo->quote($moreimagestr)." WHERE `id`=".$proomid.";";
	$dbo->setQuery($q);
	$dbo->Query($q);
	$resp['actmoreimgs'] = $moreimagestr;
	//Update current extra images uploaded
	$cur_thumbs = '';
	$morei=explode(';;', $moreimagestr);
	if(@count($morei) > 0) {
		$imgcaptions = json_decode($cur_captions, true);
		$usecaptions = empty($imgcaptions) || is_null($imgcaptions) || !is_array($imgcaptions) || !(count($imgcaptions) > 0) ? false : true;
		foreach($morei as $ki => $mi) {
			if(!empty($mi)) {
				$cur_thumbs.='<div class="vbo-editroom-currentphoto">';
				$cur_thumbs.='<a href="'.JURI::root().'components/com_vikbooking/resources/uploads/big_'.$mi.'" target="_blank" class="modal"><img src="'.JURI::root().'components/com_vikbooking/resources/uploads/thumb_'.$mi.'" class="maxfifty"/></a>';
				$cur_thumbs.='<a class="vbo-toggle-imgcaption" href="javascript: void(0);" onclick="vbOpenImgDetails(\''.$ki.'\')"><img src="./components/com_vikbooking/resources/settings.png" style="border: 0; width: 25px;"/></a>';
				$cur_thumbs.='<div id="vbimgdetbox'.$ki.'" class="vbimagedetbox" style="display: none;"><div class="captionremoveimg"><span>'.JText::_('VBREMOVEIMG').'</span><a class="vbimgrm" href="index.php?option=com_vikbooking&task=removemoreimgs&roomid='.$proomid.'&imgind='.$ki.'" title="'.JText::_('VBREMOVEIMG').'"><img src="./components/com_vikbooking/resources/remove.png" style="border: 0;"/></a></div><div class="captionlabel"><span>'.JText::_('VBIMGCAPTION').'</span><input type="text" name="caption'.$ki.'" value="'.($usecaptions === true ? $imgcaptions[$ki] : "").'" size="40"/></div><input class="captionsubmit" type="button" name="updcatpion" value="'.JText::_('VBIMGUPDATE').'" onclick="javascript: updateCaptions();"/></div>';
				$cur_thumbs.='</div>';
			}
		}
		$cur_thumbs.='<br clear="all"/>';
	}
	$resp['currentthumbs'] = $cur_thumbs;

	echo json_encode($resp);
	exit;
}

function invokeChannelManager ($oids, $option) {
	$mainframe = JFactory::getApplication();
	$sync_type = JRequest::getString('stype', 'new', 'request');
	$sync_type = !in_array($sync_type, array('new', 'modify', 'cancel')) ? 'new' : $sync_type;
	$original_booking_js = JRequest::getString('origb', '', 'request', JREQUEST_ALLOWRAW);
	$return_url = JRequest::getString('returl', '', 'request');
	$return_url = !empty($return_url) ? urldecode($return_url) : $return_url;
	if(!(count($oids) > 0) || !file_exists(JPATH_SITE . DS ."components". DS ."com_vikchannelmanager". DS . "helpers" . DS ."synch.vikbooking.php")) {
		$mainframe->redirect("index.php?option=".$option."&task=vieworders");
		exit;
	}
	require_once(JPATH_SITE . DS ."components". DS ."com_vikchannelmanager". DS . "helpers" . DS ."synch.vikbooking.php");
	$result = false;
	if($sync_type == 'new') {
		foreach ($oids as $oid) {
			if(!empty($oid)) {
				$vcm = new synchVikBooking($oid);
				$vcm->setSkipCheckAutoSync();
				$rq_rs = $vcm->sendRequest();
				$result = $result || $rq_rs ? true : $result;
			}
		}
	}elseif($sync_type == 'modify') {
		//only one Booking ID per request as the original booking is transmitted in JSON format.
		$original_booking = json_decode(urldecode($original_booking_js), true);
		if(!empty($original_booking_js) && is_array($original_booking) && @count($original_booking) > 0) {
			foreach ($oids as $oid) {
				if(!empty($oid)) {
					$vcm = new synchVikBooking($oid);
					$vcm->setSkipCheckAutoSync();
					$vcm->setFromModification($original_booking);
					$result = $vcm->sendRequest();
					break;
				}
			}
		}
	}elseif($sync_type == 'cancel') {
		foreach ($oids as $oid) {
			if(!empty($oid)) {
				$vcm = new synchVikBooking($oid);
				$vcm->setSkipCheckAutoSync();
				$vcm->setFromCancellation(array('id' => $oid));
				$rq_rs = $vcm->sendRequest();
				$result = $result || $rq_rs ? true : $result;
			}
		}
	}

	if($result === true) {
		$mainframe->enqueueMessage(JText::_('VBCHANNELMANAGERRESULTOK'));
	}else {
		JError::raiseWarning('', JText::_('VBCHANNELMANAGERRESULTKO').' <a href="index.php?option=com_vikchannelmanager" target="_blank">'.JText::_('VBCHANNELMANAGEROPEN').'</a>');
	}

	if(!empty($return_url)) {
		$mainframe->redirect($return_url);
	}else {
		$mainframe->redirect("index.php?option=".$option."&task=vieworders");
	}
}

function updateCustomer ($option, $stay = false) {
	$dbo = JFactory::getDBO();
	$mainframe = JFactory::getApplication();
	$pfirst_name = JRequest::getString('first_name', '', 'request');
	$plast_name = JRequest::getString('last_name', '', 'request');
	$pemail = JRequest::getString('email', '', 'request');
	$pphone = JRequest::getString('phone', '', 'request');
	$pcountry = JRequest::getString('country', '', 'request');
	$ppin = JRequest::getString('pin', '', 'request');
	$pujid = JRequest::getInt('ujid', '', 'request');
	$pwhere = JRequest::getInt('where', '', 'request');
	if(!empty($pwhere) && !empty($pfirst_name) && !empty($plast_name)) {
		$q = "SELECT * FROM `#__vikbooking_customers` WHERE `id`=".(int)$pwhere." LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if($dbo->getNumRows() == 1) {
			$customer = $dbo->loadAssoc();
		}else {
			$mainframe->redirect("index.php?option=".$option."&task=customers");
			exit;
		}
		$q = "SELECT * FROM `#__vikbooking_customers` WHERE `email`=".$dbo->quote($pemail)." AND `id`!=".(int)$pwhere." LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if($dbo->getNumRows() == 0) {
			$cpin = vikbooking::getCPinIstance();
			if(empty($ppin)) {
				$ppin = $customer['pin'];
			}elseif($cpin->pinExists($ppin, $customer['pin'])) {
				$ppin = $cpin->generateUniquePin();
			}
			$q = "UPDATE `#__vikbooking_customers` SET `first_name`=".$dbo->quote($pfirst_name).",`last_name`=".$dbo->quote($plast_name).",`email`=".$dbo->quote($pemail).",`phone`=".$dbo->quote($pphone).",`country`=".$dbo->quote($pcountry).",`pin`=".$dbo->quote($ppin).",`ujid`=".$dbo->quote($pujid)." WHERE `id`=".(int)$pwhere.";";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$mainframe->enqueueMessage(JText::_('VBCUSTOMERSAVED'));
		}else {
			//email already exists
			$ex_customer = $dbo->loadAssoc();
			JError::raiseWarning('', JText::_('VBERRCUSTOMEREMAILEXISTS').'<br/><a href="index.php?option=com_vikbooking&task=editcustomer&cid[]='.$ex_customer['id'].'" target="_blank">'.$ex_customer['first_name'].' '.$ex_customer['last_name'].'</a>');
			$mainframe->redirect("index.php?option=".$option."&task=editcustomer&cid[]=".$pwhere);
			exit;
		}
	}
	if($stay) {
		$mainframe->redirect("index.php?option=".$option."&task=editcustomer&cid[]=".$pwhere);
	}else {
		$mainframe->redirect("index.php?option=".$option."&task=customers");
	}
}

function saveCustomer ($option) {
	$dbo = JFactory::getDBO();
	$mainframe = JFactory::getApplication();
	$pfirst_name = JRequest::getString('first_name', '', 'request');
	$plast_name = JRequest::getString('last_name', '', 'request');
	$pemail = JRequest::getString('email', '', 'request');
	$pphone = JRequest::getString('phone', '', 'request');
	$pcountry = JRequest::getString('country', '', 'request');
	$ppin = JRequest::getString('pin', '', 'request');
	$pujid = JRequest::getInt('ujid', '', 'request');
	if(!empty($pfirst_name) && !empty($plast_name)) {
		$q = "SELECT * FROM `#__vikbooking_customers` WHERE `email`=".$dbo->quote($pemail)." LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if($dbo->getNumRows() == 0) {
			$cpin = vikbooking::getCPinIstance();
			if(empty($ppin)) {
				$ppin = $cpin->generateUniquePin();
			}elseif($cpin->pinExists($ppin)) {
				$ppin = $cpin->generateUniquePin();
			}
			$q = "INSERT INTO `#__vikbooking_customers` (`first_name`,`last_name`,`email`,`phone`,`country`,`pin`,`ujid`) VALUES(".$dbo->quote($pfirst_name).", ".$dbo->quote($plast_name).", ".$dbo->quote($pemail).", ".$dbo->quote($pphone).", ".$dbo->quote($pcountry).", ".$dbo->quote($ppin).", ".$dbo->quote($pujid).");";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$lid = $dbo->insertid();
			if(!empty($lid)) {
				$mainframe->enqueueMessage(JText::_('VBCUSTOMERSAVED'));
			}
		}else {
			//email already exists
			$ex_customer = $dbo->loadAssoc();
			JError::raiseWarning('', JText::_('VBERRCUSTOMEREMAILEXISTS').'<br/><a href="index.php?option=com_vikbooking&task=editcustomer&cid[]='.$ex_customer['id'].'" target="_blank">'.$ex_customer['first_name'].' '.$ex_customer['last_name'].'</a>');
		}
	}
	$mainframe->redirect("index.php?option=".$option."&task=customers");
}

function removeCustomers ($ids, $option) {
	if (@count($ids)) {
		$dbo = JFactory::getDBO();
		foreach($ids as $d){
			$q="DELETE FROM `#__vikbooking_customers` WHERE `id`=".(int)$d.";";
			$dbo->setQuery($q);
			$dbo->Query($q);
		}
	}
	$mainframe = JFactory::getApplication();
	$mainframe->redirect("index.php?option=".$option."&task=customers");
}

function editCustomer ($idcust, $option) {
	$dbo = JFactory::getDBO();
	$q = "SELECT * FROM `#__vikbooking_customers` WHERE `id`=".(int)$idcust.";";
	$dbo->setQuery($q);
	$dbo->Query($q);
	if($dbo->getNumRows() == 1) {
		$customer = $dbo->loadAssoc();
	}else {
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=".$option."&task=customers");
		exit;
	}
	$q = "SELECT * FROM `#__vikbooking_countries` ORDER BY `#__vikbooking_countries`.`country_name` ASC;";
	$dbo->setQuery($q);
	$dbo->Query($q);
	$countries = $dbo->loadAssocList();
	$wselcountries = '<select name="country"><option value="">----</option>'."\n";
	foreach ($countries as $key => $val) {
		$wselcountries .= '<option value="'.$val['country_3_code'].'"'.($val['country_3_code'] == $customer['country'] ? ' selected="selected"' : '').'>'.$val['country_name'].'</option>'."\n";
	}
	$wselcountries .= '</select>';
	HTML_vikbooking::pEditCustomer($customer, $wselcountries, $option);
}

function newCustomer ($option) {
	$dbo = JFactory::getDBO();
	$q = "SELECT * FROM `#__vikbooking_countries` ORDER BY `#__vikbooking_countries`.`country_name` ASC;";
	$dbo->setQuery($q);
	$dbo->Query($q);
	$countries = $dbo->loadAssocList();
	$wselcountries = '<select name="country"><option value="">----</option>'."\n";
	foreach ($countries as $key => $val) {
		$wselcountries .= '<option value="'.$val['country_3_code'].'">'.$val['country_name'].'</option>'."\n";
	}
	$wselcountries .= '</select>';
	HTML_vikbooking::pNewCustomer($wselcountries, $option);
}

function viewCustomers ($option) {
	$dbo = JFactory::getDBO();
	$mainframe = JFactory::getApplication();
	$lim = $mainframe->getUserStateFromRequest("$option.limit", 'limit', $mainframe->getCfg('list_limit'), 'int');
	$lim0 = JRequest::getVar('limitstart', 0, '', 'int');
	$session = JFactory::getSession();
	$pvborderby = JRequest::getString('vborderby', '', 'request');
	$pvbordersort = JRequest::getString('vbordersort', '', 'request');
	$validorderby = array('first_name', 'last_name', 'email', 'phone', 'country', 'pin', 'tot_bookings');
	$orderby = $session->get('vbViewCustomersOrderby', 'last_name');
	$ordersort = $session->get('vbViewCustomersOrdersort', 'ASC');
	if (!empty($pvborderby) && in_array($pvborderby, $validorderby)) {
		$orderby = $pvborderby;
		$session->set('vbViewCustomersOrderby', $orderby);
		if (!empty($pvbordersort) && in_array($pvbordersort, array('ASC', 'DESC'))) {
			$ordersort = $pvbordersort;
			$session->set('vbViewCustomersOrdersort', $ordersort);
		}
	}
	$pfiltercustomer = JRequest::getString('filtercustomer', '', 'request');
	$whereclause = '';
	if(!empty($pfiltercustomer)) {
		$whereclause = " WHERE CONCAT_WS(' ', `first_name`, `last_name`) LIKE ".$dbo->quote("%".$pfiltercustomer."%")." OR `email` LIKE ".$dbo->quote("%".$pfiltercustomer."%")." OR `pin` LIKE ".$dbo->quote("%".$pfiltercustomer."%")."";
	}
	$q = "SELECT SQL_CALC_FOUND_ROWS *,(SELECT COUNT(*) FROM `#__vikbooking_customers_orders` WHERE `#__vikbooking_customers_orders`.`idcustomer`=`#__vikbooking_customers`.`id`) AS `tot_bookings`,(SELECT `country_name` FROM `#__vikbooking_countries` WHERE `#__vikbooking_countries`.`country_3_code`=`#__vikbooking_customers`.`country`) AS `country_full_name` FROM `#__vikbooking_customers`".$whereclause." ORDER BY `".$orderby."` ".$ordersort;
	$dbo->setQuery($q, $lim0, $lim);
	$dbo->Query($q);
	if ($dbo->getNumRows() > 0) {
		$rows = $dbo->loadAssocList();
		$dbo->setQuery('SELECT FOUND_ROWS();');
		jimport('joomla.html.pagination');
		$pageNav = new JPagination( $dbo->loadResult(), $lim0, $lim );
		$navbut="<table align=\"center\"><tr><td>".$pageNav->getListFooter()."</td></tr></table>";
		HTML_vikbooking::pViewCustomers($rows, $option, $lim0, $navbut, $orderby, $ordersort);
	}else {
		$rows="";
		HTML_vikbooking::pViewCustomers($rows, $option);
	}
}

function sortPayment ($sortid, $option) {
	$pmode = JRequest::getString('mode', '', 'request');
	$dbo = JFactory::getDBO();
	if (!empty($pmode) && !empty($sortid)) {
		$q="SELECT `id`,`ordering` FROM `#__vikbooking_gpayments` ORDER BY `#__vikbooking_gpayments`.`ordering` ASC;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$totr=$dbo->getNumRows();
		if ($totr > 1) {
			$data = $dbo->loadAssocList();
			if ($pmode=="up") {
				foreach($data as $v){
					if ($v['id']==$sortid) {
						$y=$v['ordering'];
					}
				}
				if ($y && $y > 1) {
					$vik=$y - 1;
					$found=false;
					foreach($data as $v){
						if (intval($v['ordering'])==intval($vik)) {
							$found=true;
							$q="UPDATE `#__vikbooking_gpayments` SET `ordering`='".$y."' WHERE `id`='".$v['id']."' LIMIT 1;";
							$dbo->setQuery($q);
							$dbo->Query($q);
							$q="UPDATE `#__vikbooking_gpayments` SET `ordering`='".$vik."' WHERE `id`='".$sortid."' LIMIT 1;";
							$dbo->setQuery($q);
							$dbo->Query($q);
							break;
						}
					}
					if(!$found) {
						$q="UPDATE `#__vikbooking_gpayments` SET `ordering`='".$vik."' WHERE `id`='".$sortid."' LIMIT 1;";
						$dbo->setQuery($q);
						$dbo->Query($q);
					}
				}
			}elseif ($pmode=="down") {
				foreach($data as $v){
					if ($v['id']==$sortid) {
						$y=$v['ordering'];
					}
				}
				if ($y) {
					$vik=$y + 1;
					$found=false;
					foreach($data as $v){
						if (intval($v['ordering'])==intval($vik)) {
							$found=true;
							$q="UPDATE `#__vikbooking_gpayments` SET `ordering`='".$y."' WHERE `id`='".$v['id']."' LIMIT 1;";
							$dbo->setQuery($q);
							$dbo->Query($q);
							$q="UPDATE `#__vikbooking_gpayments` SET `ordering`='".$vik."' WHERE `id`='".$sortid."' LIMIT 1;";
							$dbo->setQuery($q);
							$dbo->Query($q);
							break;
						}
					}
					if(!$found) {
						$q="UPDATE `#__vikbooking_gpayments` SET `ordering`='".$vik."' WHERE `id`='".$sortid."' LIMIT 1;";
						$dbo->setQuery($q);
						$dbo->Query($q);
					}
				}
			}
		}
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=".$option."&task=payments");
	}else {
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=".$option);
	}
}

function saveTranslations($option, $stay = false) {
	$dbo = JFactory::getDBO();
	$mainframe = JFactory::getApplication();
	$vbo_tn = vikbooking::getTranslator();
	$table = JRequest::getString('vbo_table', '', 'request');
	$cur_langtab = JRequest::getString('vbo_lang', '', 'request');
	$langs = $vbo_tn->getLanguagesList();
	$xml_tables = $vbo_tn->getTranslationTables();
	if(!empty($table) && array_key_exists($table, $xml_tables)) {
		$tn = JRequest::getVar('tn', array(), 'request', JREQUEST_ALLOWRAW);
		$tn_saved = 0;
		$table_cols = $vbo_tn->getTableColumns($table);
		$table = $vbo_tn->replacePrefix($table);
		foreach ($langs as $ltag => $lang) {
			if($ltag == $vbo_tn->default_lang) {
				continue;
			}
			if(array_key_exists($ltag, $tn) && count($tn[$ltag]) > 0) {
				foreach ($tn[$ltag] as $reference_id => $translation) {
					$lang_translation = array();
					foreach ($table_cols as $field => $fdetails) {
						if(!array_key_exists($field, $translation)) {
							continue;
						}
						$ftype = $fdetails['type'];
						if($ftype == 'skip') {
							continue;
						}
						if($ftype == 'json') {
							$translation[$field] = json_encode($translation[$field]);
						}
						$lang_translation[$field] = $translation[$field];
					}
					if(count($lang_translation) > 0) {
						$q = "SELECT `id` FROM `#__vikbooking_translations` WHERE `table`=".$dbo->quote($table)." AND `lang`=".$dbo->quote($ltag)." AND `reference_id`=".$dbo->quote((int)$reference_id).";";
						$dbo->setQuery($q);
						$dbo->Query($q);
						if($dbo->getNumRows() > 0) {
							$last_id = $dbo->loadResult();
							$q = "UPDATE `#__vikbooking_translations` SET `content`=".$dbo->quote(json_encode($lang_translation))." WHERE `id`=".(int)$last_id.";";
						}else {
							$q = "INSERT INTO `#__vikbooking_translations` (`table`,`lang`,`reference_id`,`content`) VALUES (".$dbo->quote($table).", ".$dbo->quote($ltag).", ".$dbo->quote((int)$reference_id).", ".$dbo->quote(json_encode($lang_translation)).");";
						}
						$dbo->setQuery($q);
						$dbo->Query($q);
						$tn_saved++;
					}
				}
			}
		}
		if($tn_saved > 0) {
			$mainframe->enqueueMessage(JText::_('VBOTRANSLSAVEDOK'));
		}
	}else {
		JError::raiseWarning('', JText::_('VBTRANSLATIONERRINVTABLE'));
	}
	$mainframe->redirect("index.php?option=".$option.($stay ? '&task=translations&vbo_table='.$vbo_tn->replacePrefix($table).'&vbo_lang='.$cur_langtab : ''));
}

function viewTranslations($option) {
	$vbo_tn = vikbooking::getTranslator();
	HTML_vikbooking::pViewTranslations($vbo_tn, $option);
}

function calculateRates($option) {
	$response = 'e4j.error.ErrorCode(1) Server is blocking the self-request';
	$currencysymb = vikbooking::getCurrencySymb();
	$vbo_df = vikbooking::getDateFormat();
	$df = $vbo_df == "%d/%m/%Y" ? 'd/m/Y' : ($vbo_df == "%m/%d/%Y" ? 'm/d/Y' : 'Y/m/d');
	$id_room = JRequest::getInt('id_room', '', 'request');
	$checkin = JRequest::getString('checkin', '', 'request');
	$nights = JRequest::getInt('num_nights', 1, 'request');
	$adults = JRequest::getInt('num_adults', 0, 'request');
	$children = JRequest::getInt('num_children', 0, 'request');
	$checkin_ts = strtotime($checkin);
	if(empty($checkin_ts)) {
		$checkin = date('Y-m-d');
		$checkin_ts = strtotime($checkin);
	}
	$is_dst = date('I', $checkin_ts);
	$checkout_ts = $checkin_ts;
	for ($i = 1; $i <= $nights; $i++) { 
		$checkout_ts += 86400;
		$is_now_dst = date('I', $checkout_ts);
		if ($is_dst != $is_now_dst) {
			if ((int)$is_dst == 1) {
				$checkout_ts += 3600;
			}else {
				$checkout_ts -= 3600;
			}
			$is_dst = $is_now_dst;
		}
	}
	$checkout = date('Y-m-d', $checkout_ts);
	if(function_exists('curl_init')) {
		$endpoint = JURI::root().'index.php?option=com_vikbooking&task=tac_av_l';
		$rates_data = 'e4jauth=%s&req_type=hotel_availability&start_date='.$checkin.'&end_date='.$checkout.'&nights='.$nights.'&num_rooms=1&adults[]='.$adults.'&children[]='.$children;
		$ch = curl_init($endpoint);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
		curl_setopt($ch, CURLOPT_TIMEOUT, 20);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, sprintf($rates_data, md5('vbo.e4j.vbo')));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded"));
		$res = curl_exec($ch);
		if($curl_errno = curl_errno($ch)) {
			$response = "e4j.error.curl Error (".curl_errno($ch)."): ".curl_error($ch);
		}else {
			$arr_res = json_decode($res, true);
			if(is_array($arr_res)) {
				if(!array_key_exists('e4j.error', $arr_res)) {
					if(array_key_exists($id_room, $arr_res)) {
						$response = '';
						foreach ($arr_res[$id_room] as $rate) {
							$response .= '<div class="vbo-calcrates-rateblock">';
							$response .= '<span class="vbo-calcrates-ratename">'.$rate['pricename'].'</span>';
							$response .= '<span class="vbo-calcrates-ratenet"><span>'.JText::_('VBCALCRATESNET').'</span>'.$currencysymb.' '.vikbooking::numberFormat($rate['cost']).'</span>';
							$response .= '<span class="vbo-calcrates-ratetax"><span>'.JText::_('VBCALCRATESTAX').'</span>'.$currencysymb.' '.vikbooking::numberFormat($rate['taxes']).'</span>';
							if(!empty($rate['city_taxes'])) {
								$response .= '<span class="vbo-calcrates-ratecitytax"><span>'.JText::_('VBCALCRATESCITYTAX').'</span>'.$currencysymb.' '.vikbooking::numberFormat($rate['city_taxes']).'</span>';
							}
							if(!empty($rate['fees'])) {
								$response .= '<span class="vbo-calcrates-ratefees"><span>'.JText::_('VBCALCRATESFEES').'</span>'.$currencysymb.' '.vikbooking::numberFormat($rate['fees']).'</span>';
							}
							$tot = $rate['cost'] + $rate['taxes'] + $rate['city_taxes'] + $rate['fees'];
							$tot = round($tot, 2);
							$response .= '<span class="vbo-calcrates-ratetotal"><span>'.JText::_('VBCALCRATESTOT').'</span>'.$currencysymb.' '.vikbooking::numberFormat($tot).'</span>';
							if(array_key_exists('affdays', $rate) && $rate['affdays'] > 0) {
								$response .= '<span class="vbo-calcrates-ratespaffdays"><span>'.JText::_('VBCALCRATESSPAFFDAYS').'</span>'.$rate['affdays'].'</span>';
							}
							if(array_key_exists('diffusagediscount', $rate) && count($rate['diffusagediscount']) > 0) {
								foreach ($rate['diffusagediscount'] as $occupancy => $disc) {
									$response .= '<span class="vbo-calcrates-rateoccupancydisc"><span>'.JText::sprintf('VBCALCRATESADUOCCUPANCY', $occupancy).'</span>- '.$currencysymb.' '.vikbooking::numberFormat($disc).'</span>';
									break;
								}
							}elseif(array_key_exists('diffusagecost', $rate) && count($rate['diffusagecost']) > 0) {
								foreach ($rate['diffusagecost'] as $occupancy => $charge) {
									$response .= '<span class="vbo-calcrates-rateoccupancycharge"><span>'.JText::sprintf('VBCALCRATESADUOCCUPANCY', $occupancy).'</span>+ '.$currencysymb.' '.vikbooking::numberFormat($charge).'</span>';
									break;
								}
							}
							$response .= '</div>';
						}
						//Debug
						//$response .= '<br/><pre>'.print_r($arr_res, true).'</pre><br/>';
					}else {
						$response = 'e4j.error.'.JText::sprintf('VBCALCRATESROOMNOTAVAILCOMBO', date($df, $checkin_ts), date($df, $checkout_ts));
					}
				}else {
					$response = 'e4j.error.'.$arr_res['e4j.error'];
				}
			}else {
				$response = (strpos($res, 'e4j.error') === false ? 'e4j.error' : '').$res;
			}
		}
		curl_close($ch);
	}

	echo trim($response);
	exit;
}

function ratesOverview($roomid, $option) {
	$dbo = JFactory::getDBO();
	if(empty($roomid)) {
		$q="SELECT `id` FROM `#__vikbooking_rooms` ORDER BY `#__vikbooking_rooms`.`name` ASC LIMIT 1";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if ($dbo->getNumRows() == 1) {
			$roomid = $dbo->loadResult();
		}
	}
	if(!empty($roomid)) {
		$q="SELECT `id`,`name` FROM `#__vikbooking_rooms` ORDER BY `#__vikbooking_rooms`.`name` ASC;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$all_rooms = $dbo->loadAssocList();
		$q = "SELECT * FROM `#__vikbooking_rooms` WHERE `id`=".intval($roomid).";";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if ($dbo->getNumRows() == 1) {
			$roomrows = $dbo->loadAssoc();
			$pnights_cal = JRequest::getVar('nights_cal', array());
			$pnights_cal = vikbooking::filterNightsSeasonsCal($pnights_cal);
			$room_nights_cal = explode(',', vikbooking::getRoomParam('seasoncal_nights', $room[0]['params']));
			$room_nights_cal = vikbooking::filterNightsSeasonsCal($room_nights_cal);
			$seasons_cal = array();
			$seasons_cal_nights = array();
			if(count($pnights_cal) > 0) {
				$seasons_cal_nights = $pnights_cal;
			}elseif(count($room_nights_cal) > 0) {
				$seasons_cal_nights = $room_nights_cal;
			}else{
				$q = "SELECT `days` FROM `#__vikbooking_dispcost` WHERE `idroom`=".intval($roomid)." ORDER BY `#__vikbooking_dispcost`.`days` ASC LIMIT 7;";
				$dbo->setQuery($q);
				$dbo->Query($q);
				if ($dbo->getNumRows() > 0) {
					$nights_vals = $dbo->loadAssocList();
					$nights_got = array();
					foreach ($nights_vals as $night) {
						$nights_got[] = $night['days'];
					}
					$seasons_cal_nights = vikbooking::filterNightsSeasonsCal($nights_got);
				}
			}
			if (count($seasons_cal_nights) > 0) {
				$q = "SELECT `p`.*,`tp`.`name`,`tp`.`attr`,`tp`.`idiva`,`tp`.`breakfast_included`,`tp`.`free_cancellation`,`tp`.`canc_deadline` FROM `#__vikbooking_dispcost` AS `p` LEFT JOIN `#__vikbooking_prices` `tp` ON `p`.`idprice`=`tp`.`id` WHERE `p`.`days` IN (".implode(',', $seasons_cal_nights).") AND `p`.`idroom`=".$roomid." ORDER BY `p`.`days` ASC, `p`.`cost` ASC;";
				$dbo->setQuery($q);
				$dbo->Query($q);
				if($dbo->getNumRows() > 0) {
					$tars = $dbo->loadAssocList();
					$arrtar = array();
					foreach ($tars as $tar) {
						$arrtar[$tar['days']][] = $tar;
					}
					$seasons_cal['nights'] = $seasons_cal_nights;
					$seasons_cal['offseason'] = $arrtar;
					$q = "SELECT * FROM `#__vikbooking_seasons` WHERE `idrooms` LIKE '%-".$roomid."-%';";
					$dbo->setQuery($q);
					$dbo->Query($q);
					if($dbo->getNumRows() > 0) {
						$seasons = $dbo->loadAssocList();
						//Restrictions
						$all_restrictions = vikbooking::loadRestrictions(true, array($roomid));
						$all_seasons = array();
						$curtime = time();
						foreach ($seasons as $sk => $s) {
							$now_year = !empty($s['year']) ? $s['year'] : date('Y');
							list($sfrom, $sto) = vikbooking::getSeasonRangeTs($s['from'], $s['to'], $now_year);
							if($sto < $curtime && empty($s['year'])) {
								$now_year += 1;
								list($sfrom, $sto) = vikbooking::getSeasonRangeTs($s['from'], $s['to'], $now_year);
							}
							if($sto >= $curtime) {
								$s['from_ts'] = $sfrom;
								$s['to_ts'] = $sto;
								$all_seasons[] = $s;
							}
						}
						if(count($all_seasons) > 0) {
							$vbo_df = vikbooking::getDateFormat();
							$vbo_df = $vbo_df == "%d/%m/%Y" ? 'd/m/Y' : ($vbo_df == "%m/%d/%Y" ? 'm/d/Y' : 'Y/m/d');
							$hcheckin = 0;
							$mcheckin = 0;
							$hcheckout = 0;
							$mcheckout = 0;
							$timeopst = vikbooking::getTimeOpenStore();
							if (is_array($timeopst)) {
								$opent = vikbooking::getHoursMinutes($timeopst[0]);
								$closet = vikbooking::getHoursMinutes($timeopst[1]);
								$hcheckin = $opent[0];
								$mcheckin = $opent[1];
								$hcheckout = $closet[0];
								$mcheckout = $closet[1];
							}
							$all_seasons = vikbooking::sortSeasonsRangeTs($all_seasons);
							$seasons_cal['seasons'] = $all_seasons;
							$seasons_cal['season_prices'] = array();
							$seasons_cal['restrictions'] = array();
							//calc price changes for each season and for each num-night
							foreach ($all_seasons as $sk => $s) {
								$checkin_base_ts = $s['from_ts'];
								$is_dst = date('I', $checkin_base_ts);
								foreach ($arrtar as $numnights => $tar) {
									$checkout_base_ts = $s['to_ts'];
									for($i = 1; $i <= $numnights; $i++) {
										$checkout_base_ts += 86400;
										$is_now_dst = date('I', $checkout_base_ts);
										if ($is_dst != $is_now_dst) {
											if ((int)$is_dst == 1) {
												$checkout_base_ts += 3600;
											}else {
												$checkout_base_ts -= 3600;
											}
											$is_dst = $is_now_dst;
										}
									}
									//calc check-in and check-out ts for the two dates
									$first = vikbooking::getDateTimestamp(date($vbo_df, $checkin_base_ts), $hcheckin, $mcheckin);
									$second = vikbooking::getDateTimestamp(date($vbo_df, $checkout_base_ts), $hcheckout, $mcheckout);
									$tar = vikbooking::applySeasonsRoom($tar, $first, $second, $s);
									$seasons_cal['season_prices'][$sk][$numnights] = $tar;
									//Restrictions
									if(count($all_restrictions) > 0) {
										$season_restr = vikbooking::parseSeasonRestrictions($first, $second, $numnights, $all_restrictions);
										if(count($season_restr) > 0) {
											$seasons_cal['restrictions'][$sk][$numnights] = $season_restr;
										}
									}
								}
							}
						}
					}
				}
			}
			//send output with function
			HTML_vikbooking::pRatesOverview($all_rooms, $roomrows, $seasons_cal_nights, $seasons_cal, $option);
		}else {
			cancelEditing($option);
		}
	}else {
		cancelEditing($option);
	}
}

function renewSession($option) {
	$dbo = JFactory::getDBO();
	$q="TRUNCATE TABLE `#__session`;";
	$dbo->setQuery($q);
	$dbo->Query($q);
	$mainframe = JFactory::getApplication();
	$mainframe->redirect("index.php?option=".$option."&task=config");
}

function icsExportLaunch($option) {
	$dbo = JFactory::getDBO();
	$pcheckindate = JRequest::getString('checkindate', '', 'request');
	$pcheckoutdate = JRequest::getString('checkoutdate', '', 'request');
	$pstatus = JRequest::getString('status', '', 'request');
	$validstatus = array('confirmed', 'standby', 'cancelled');
	$filterstatus = '';
	$filterfirst = 0;
	$filtersecond = 0;
	$nowdf = vikbooking::getDateFormat(true);
	if ($nowdf=="%d/%m/%Y") {
		$df='d/m/Y';
	}elseif ($nowdf=="%m/%d/%Y") {
		$df='m/d/Y';
	}else {
		$df='Y/m/d';
	}
	$currencyname = vikbooking::getCurrencyName();
	if (!empty($pstatus) && in_array($pstatus, $validstatus)) {
		$filterstatus = $pstatus;
	}
	if (!empty($pcheckindate)) {
		if (vikbooking::dateIsValid($pcheckindate)) {
			$first=vikbooking::getDateTimestamp($pcheckindate, '0', '0');
			$filterfirst = $first;
		}
	}
	if (!empty($pcheckoutdate)) {
		if (vikbooking::dateIsValid($pcheckoutdate)) {
			$second=vikbooking::getDateTimestamp($pcheckoutdate, '23', '59');
			if ($second > $first) {
				$filtersecond = $second;
			}
		}
	}
	$clause = array();
	if ($filterfirst > 0) {
		$clause[] = "`o`.`checkin` >= ".$filterfirst;
	}
	if ($filtersecond > 0) {
		$clause[] = "`o`.`checkout` <= ".$filtersecond;
	}
	if (!empty($filterstatus)) {
		$clause[] = "`o`.`status` = '".$filterstatus."'";
	}
	$q="SELECT `o`.*,`or`.`idroom`,`or`.`adults`,`or`.`children`,`r`.`name` FROM `#__vikbooking_orders` AS `o` LEFT JOIN `#__vikbooking_ordersrooms` `or` ON `or`.`idorder`=`o`.`id` LEFT JOIN `#__vikbooking_rooms` `r` ON `or`.`idroom`=`r`.`id` ".(count($clause) > 0 ? "WHERE ".implode(" AND ", $clause)." " : "")."ORDER BY `o`.`checkin` ASC;";
	$dbo->setQuery($q);
	$dbo->Query($q);
	if ($dbo->getNumRows() > 0) {
		$orders = $dbo->loadAssocList();
		$icscontent = "BEGIN:VCALENDAR\n";
		$icscontent .= "VERSION:2.0\n";
		$icscontent .= "PRODID:-//e4j//VikBooking//EN\n";
		$icscontent .= "CALSCALE:GREGORIAN\n";
		$str = "";
		foreach($orders as $kord => $ord) {
			if ($orders[($kord + 1)]['id'] == $ord['id']) {
				continue;
			}
			$statusstr = '';
			if ($ord['status'] == 'confirmed') {
				$statusstr = JText::_('VBCSVSTATUSCONFIRMED');
			}elseif ($ord['status'] == 'standby') {
				$statusstr = JText::_('VBCSVSTATUSSTANDBY');
			}elseif ($ord['status'] == 'cancelled') {
				$statusstr = JText::_('VBCSVSTATUSCANCELLED');
			}
			$uri = JURI::root().'index.php?option=com_vikbooking&task=vieworder&sid='.$ord['sid'].'&ts='.$ord['ts'];
			$ordnumbstr = $ord['id'].(!empty($ord['confirmnumber']) ? ' - '.$ord['confirmnumber'] : '').(!empty($ord['idorderota']) ? ' ('.ucwords($ord['channel']).')' : '').' - '.$statusstr;
			$peoplestr = ($ord['adults'] + $ord['children']).($ord['children'] > 0 ? ' ('.JText::_('VBCSVCHILDREN').': '.$ord['children'].')' : '');
			$totalstring = ($ord['total'] > 0 ? (vikbooking::numberFormat($ord['total']).' '.$usecurrencyname) : '');
			$totalpaidstring = ($ord['totpaid'] > 0 ? (' ('.vikbooking::numberFormat($ord['totpaid']).')') : '');
			$description = JText::sprintf('VBICSEXPDESCRIPTION', $ordnumbstr."\\n", $peoplestr."\\n", $ord['days']."\\n", $totalstring.$totalpaidstring."\\n", "\\n".str_replace("\n", "\\n", trim($ord['custdata'])));
			$str .= "BEGIN:VEVENT\n";
			$str .= "DTEND:".date('Ymd\THis\Z', $ord['checkout'])."\n";
			$str .= "UID:".uniqid()."\n";
			$str .= "DTSTAMP:".date('Ymd\THis\Z', time())."\n";
			$str .= ((strlen($description) > 0 ) ? "DESCRIPTION:".preg_replace('/([\,;])/','\\\$1', $description)."\n" : "");
			$str .= "URL;VALUE=URI:".preg_replace('/([\,;])/','\\\$1', $uri)."\n";
			$str .= "SUMMARY:".JText::sprintf('VBICSEXPSUMMARY', date($df, $ord['checkin']))."\n";
			$str .= "DTSTART:".date('Ymd\THis\Z', $ord['checkin'])."\n";
			$str .= "END:VEVENT\n";
		}
		$icscontent .= $str;
		$icscontent .= "END:VCALENDAR\n";
		//download file from buffer
		header("Content-Type: application/octet-stream; ");
		header("Cache-Control: no-store, no-cache");
		header('Content-Disposition: attachment; filename="bookings_export.ics"');
		$f = fopen('php://output', "w");
		fwrite($f, $icscontent);
		fclose($f);
		exit;
	}else {
		JError::raiseWarning('', JText::_('VBICSEXPNORECORDS'));
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=".$option."&task=icsexportprepare&checkindate=".$pcheckindate."&checkoutdate=".$pcheckoutdate."&status=".$pstatus."&tmpl=component");
	}
}

function icsExportPrepare($option) {
	HTML_vikbooking::pIcsExportPrepare($option);
}

function csvExportLaunch($option) {
	$dbo = JFactory::getDBO();
	$pcheckindate = JRequest::getString('checkindate', '', 'request');
	$pcheckoutdate = JRequest::getString('checkoutdate', '', 'request');
	$pstatus = JRequest::getString('status', '', 'request');
	$validstatus = array('confirmed', 'standby', 'cancelled');
	$filterstatus = '';
	$filterfirst = 0;
	$filtersecond = 0;
	$nowdf = vikbooking::getDateFormat(true);
	if ($nowdf=="%d/%m/%Y") {
		$df='d/m/Y';
	}elseif ($nowdf=="%m/%d/%Y") {
		$df='m/d/Y';
	}else {
		$df='Y/m/d';
	}
	$currencyname = vikbooking::getCurrencyName();
	if (!empty($pstatus) && in_array($pstatus, $validstatus)) {
		$filterstatus = $pstatus;
	}
	if (!empty($pcheckindate)) {
		if (vikbooking::dateIsValid($pcheckindate)) {
			$first=vikbooking::getDateTimestamp($pcheckindate, '0', '0');
			$filterfirst = $first;
		}
	}
	if (!empty($pcheckoutdate)) {
		if (vikbooking::dateIsValid($pcheckoutdate)) {
			$second=vikbooking::getDateTimestamp($pcheckoutdate, '23', '59');
			if ($second > $first) {
				$filtersecond = $second;
			}
		}
	}
	$clause = array();
	if ($filterfirst > 0) {
		$clause[] = "`o`.`checkin` >= ".$filterfirst;
	}
	if ($filtersecond > 0) {
		$clause[] = "`o`.`checkout` <= ".$filtersecond;
	}
	if (!empty($filterstatus)) {
		$clause[] = "`o`.`status` = '".$filterstatus."'";
	}
	$q="SELECT `o`.*,`or`.`idroom`,`or`.`adults`,`or`.`children`,`or`.`idtar`,`or`.`optionals`,`r`.`name`,`d`.`idprice`,`p`.`idiva`,`t`.`aliq`,`t`.`breakdown` FROM `#__vikbooking_orders` AS `o` LEFT JOIN `#__vikbooking_ordersrooms` `or` ON `or`.`idorder`=`o`.`id` LEFT JOIN `#__vikbooking_rooms` `r` ON `or`.`idroom`=`r`.`id` LEFT JOIN `#__vikbooking_dispcost` `d` ON `or`.`idtar`=`d`.`id` LEFT JOIN `#__vikbooking_prices` `p` ON `d`.`idprice`=`p`.`id` LEFT JOIN `#__vikbooking_iva` `t` ON `p`.`idiva`=`t`.`id` ".(count($clause) > 0 ? "WHERE ".implode(" AND ", $clause)." " : "")."ORDER BY `o`.`checkin` ASC;";
	$dbo->setQuery($q);
	$dbo->Query($q);
	if ($dbo->getNumRows() > 0) {
		$orders = $dbo->loadAssocList();
		//options
		$all_options = array();
		$q = "SELECT * FROM `#__vikbooking_optionals` ORDER BY `#__vikbooking_optionals`.`ordering` ASC;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if ($dbo->getNumRows() > 0) {
			$options = $dbo->loadAssocList();
			foreach ($options as $ok => $ov) {
				$all_options[$ov['id']] = $ov;
			}
		}
		//
		$orderscsv = array();
		$orderscsv[] = array(JText::_('VBCSVCHECKIN'), JText::_('VBCSVCHECKOUT'), JText::_('VBCSVNIGHTS'), JText::_('VBCSVROOM'), JText::_('VBCSVPEOPLE'), JText::_('VBCSVCUSTINFO'), JText::_('VBCSVCUSTMAIL'), JText::_('VBCSVOPTIONS'), JText::_('VBCSVPAYMENTMETHOD'), JText::_('VBCSVORDIDCONFNUMB'), JText::_('VBCSVEXPFILTBSTATUS'), JText::_('VBCSVTOTAL'), JText::_('VBCSVTOTPAID'), JText::_('VBCSVTOTTAXES'));
		foreach($orders as $kord => $ord) {
			$usecurrencyname = $currencyname;
			$usecurrencyname = !empty($ord['idorderota']) && !empty($ord['chcurrency']) ? $ord['chcurrency'] : $usecurrencyname;
			$peoplestr = ($ord['adults'] + $ord['children']).($ord['children'] > 0 ? ' ('.JText::_('VBCSVCHILDREN').': '.$ord['children'].')' : '');
			$custinfostr = str_replace(",", " ", $ord['custdata']);
			$paystr = '';
			if (!empty($ord['idpayment'])) {
				$payparts = explode('=', $ord['idpayment']);
				$paystr = $payparts[1];
			}
			$ordnumbstr = $ord['id'].' - '.$ord['confirmnumber'].(!empty($ord['idorderota']) ? ' ('.ucwords($ord['channel']).')' : ''); 
			$statusstr = '';
			if ($ord['status'] == 'confirmed') {
				$statusstr = JText::_('VBCSVSTATUSCONFIRMED');
			}elseif ($ord['status'] == 'standby') {
				$statusstr = JText::_('VBCSVSTATUSSTANDBY');
			}elseif ($ord['status'] == 'cancelled') {
				$statusstr = JText::_('VBCSVSTATUSCANCELLED');
			}
			$totalstring = ($ord['total'] > 0 ? $ord['total'].' '.$usecurrencyname : '0.00'.' '.$usecurrencyname);
			$totalpaidstring = ($ord['totpaid'] > 0 ? $ord['totpaid'].' '.$usecurrencyname : '0.00'.' '.$usecurrencyname);
			if ($orders[($kord + 1)]['id'] == $ord['id']) {
				$totalstring = '';
				$totalpaidstring = '';
			}
			$options_str = '';
			if(!empty($ord['optionals'])) {
				$stepo = explode(";", $ord['optionals']);
				foreach($stepo as $oo){
					if (!empty($oo)) {
						$stept = explode(":", $oo);
						if(array_key_exists($stept[0], $all_options)) {
							$actopt = $all_options[$stept[0]];

							if (!empty($actopt['ageintervals']) && $ord['children'] > 0 && strstr($stept[1], '-') != false) {
								$optagecosts = vikbooking::getOptionIntervalsCosts($actopt['ageintervals']);
								$optagenames = vikbooking::getOptionIntervalsAges($actopt['ageintervals']);
								$agestept = explode('-', $stept[1]);
								$stept[1] = $agestept[0];
								$chvar = $agestept[1];
								$actopt['chageintv'] = $chvar;
								$actopt['name'] .= ' ('.$optagenames[($chvar - 1)].')';
								$realcost = (intval($actopt['perday']) == 1 ? (floatval($optagecosts[($chvar - 1)]) * $ord['days'] * $stept[1]) : (floatval($optagecosts[($chvar - 1)]) * $stept[1]));
							}else {
								$realcost = (intval($actopt['perday']) == 1 ? ($actopt['cost'] * $ord['days'] * $stept[1]) : ($actopt['cost'] * $stept[1]));
							}
							if($actopt['maxprice'] > 0 && $realcost > $actopt['maxprice']) {
								$realcost=$actopt['maxprice'];
								if(intval($actopt['hmany']) == 1 && intval($stept[1]) > 1) {
									$realcost = $actopt['maxprice'] * $stept[1];
								}
							}
							$realcost = $actopt['perperson'] == 1 ? ($realcost * $ord['adults']) : $realcost;
							$tmpopr=vikbooking::sayOptionalsPlusIva($realcost, $actopt['idiva']);
							$options_str .= ($stept[1] > 1 ? $stept[1]." " : "").$actopt['name'].": ".$currencyname." ".vikbooking::numberFormat($tmpopr)." \r\n";
						}
					}
				}
			}
			//taxes
			$taxes_str = '';
			if($ord['tot_taxes'] > 0.00) {
				$taxes_str .= $usecurrencyname.' '.vikbooking::numberFormat($ord['tot_taxes']);
				if(!empty($ord['aliq']) && !empty($ord['breakdown'])) {
					$tax_breakdown = json_decode($ord['breakdown'], true);
					$tax_breakdown = is_array($tax_breakdown) && count($tax_breakdown) > 0 ? $tax_breakdown : array();
					if(count($tax_breakdown)) {
						foreach ($tax_breakdown as $tbkk => $tbkv) {
							$tax_break_cost = $ord['tot_taxes'] * floatval($tbkv['aliq']) / $ord['aliq'];
							$taxes_str .= "\r\n".$tbkv['name'].": ".$usecurrencyname.' '.vikbooking::numberFormat($tax_break_cost);
						}
					}
				}
			}
			//
			$orderscsv[] = array(date($df, $ord['checkin']), date($df, $ord['checkout']), $ord['days'], $ord['name'], $peoplestr, $custinfostr, $ord['custmail'], $options_str, $paystr, $ordnumbstr, $statusstr, $totalstring, $totalpaidstring, $taxes_str);
		}
		header("Content-type: text/csv");
		header("Cache-Control: no-store, no-cache");
		header('Content-Disposition: attachment; filename="bookings_export_'.date('Y-m-d').'.csv"');
		$outstream = fopen("php://output", 'w');
		foreach($orderscsv as $csvline) {
			fputcsv($outstream, $csvline);
		}
		fclose($outstream);
		exit;
	}else {
		JError::raiseWarning('', JText::_('VBCSVEXPNORECORDS'));
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=".$option."&task=csvexportprepare&checkindate=".$pcheckindate."&checkoutdate=".$pcheckoutdate."&status=".$pstatus."&tmpl=component");
	}
}

function csvExportPrepare($option) {
	HTML_vikbooking::pCsvExportPrepare($option);
}

function resendOrderEmail ($oid, $option, $cancellation = false) {
	$dbo = JFactory::getDBO();
	$q="SELECT * FROM `#__vikbooking_orders` WHERE `id`='".$oid."';";
	$dbo->setQuery($q);
	$dbo->Query($q);
	if($dbo->getNumRows() == 1) {
		$order=$dbo->loadAssocList();
		//check if the language in use is the same as the one used during the checkout
		if (!empty($order[0]['lang'])) {
			$lang = JFactory::getLanguage();
			if($lang->getTag() != $order[0]['lang']) {
				$lang->load('com_vikbooking', JPATH_ADMINISTRATOR, $order[0]['lang'], true);
			}
		}
		//
		$vbo_tn = vikbooking::getTranslator();
		$q="SELECT `or`.*,`r`.`id` AS `r_reference_id`,`r`.`name`,`r`.`units`,`r`.`fromadult`,`r`.`toadult`,`r`.`params` FROM `#__vikbooking_ordersrooms` AS `or`,`#__vikbooking_rooms` AS `r` WHERE `or`.`idorder`='".$order[0]['id']."' AND `or`.`idroom`=`r`.`id` ORDER BY `or`.`id` ASC;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$ordersrooms=$dbo->loadAssocList();
		$vbo_tn->translateContents($ordersrooms, '#__vikbooking_rooms', array('id' => 'r_reference_id'));
		$currencyname = vikbooking::getCurrencyName();
		$realback=vikbooking::getHoursRoomAvail() * 3600;
		$realback+=$order[0]['checkout'];
		$rooms = array();
		$tars = array();
		$arrpeople = array();
		$is_package = !empty($order[0]['pkg']) ? true : false;
		//send mail
		$ftitle=vikbooking::getFrontTitle ();
		$nowts=time();
		$viklink=JURI::root()."index.php?option=com_vikbooking&task=vieworder&sid=".$order[0]['sid']."&ts=".$order[0]['ts'];
		foreach($ordersrooms as $kor => $or) {
			$num = $kor + 1;
			$rooms[$num] = $or;
			$arrpeople[$num]['adults'] = $or['adults'];
			$arrpeople[$num]['children'] = $or['children'];
			if($is_package === true || (!empty($or['cust_cost']) && $or['cust_cost'] > 0.00)) {
				//package or custom cost set from the back-end
				continue;
			}
			$q="SELECT * FROM `#__vikbooking_dispcost` WHERE `id`='".$or['idtar']."';";
			$dbo->setQuery($q);
			$dbo->Query($q);
			if($dbo->getNumRows() > 0) {
				$tar = $dbo->loadAssocList();
				$tar = vikbooking::applySeasonsRoom($tar, $order[0]['checkin'], $order[0]['checkout']);
				//different usage
				if ($or['fromadult'] <= $or['adults'] && $or['toadult'] >= $or['adults']) {
					$diffusageprice = vikbooking::loadAdultsDiff($or['idroom'], $or['adults']);
					//Occupancy Override
					$occ_ovr = vikbooking::occupancyOverrideExists($tar, $or['adults']);
					$diffusageprice = $occ_ovr !== false ? $occ_ovr : $diffusageprice;
					//
					if (is_array($diffusageprice)) {
						//set a charge or discount to the price(s) for the different usage of the room
						foreach($tar as $kpr => $vpr) {
							$tar[$kpr]['diffusage'] = $or['adults'];
							if ($diffusageprice['chdisc'] == 1) {
								//charge
								if ($diffusageprice['valpcent'] == 1) {
									//fixed value
									$tar[$kpr]['diffusagecostpernight'] = $diffusageprice['pernight'] == 1 ? 1 : 0;
									$aduseval = $diffusageprice['pernight'] == 1 ? $diffusageprice['value'] * $tar[$kpr]['days'] : $diffusageprice['value'];
									$tar[$kpr]['diffusagecost'] = "+".$aduseval;
									$tar[$kpr]['cost'] = $vpr['cost'] + $aduseval;
								}else {
									//percentage value
									$tar[$kpr]['diffusagecostpernight'] = $diffusageprice['pernight'] == 1 ? $vpr['cost'] : 0;
									$aduseval = $diffusageprice['pernight'] == 1 ? round(($vpr['cost'] * $diffusageprice['value'] / 100) * $tar[$kpr]['days'] + $vpr['cost'], 2) : round(($vpr['cost'] * (100 + $diffusageprice['value']) / 100), 2);
									$tar[$kpr]['diffusagecost'] = "+".$diffusageprice['value']."%";
									$tar[$kpr]['cost'] = $aduseval;
								}
							}else {
								//discount
								if ($diffusageprice['valpcent'] == 1) {
									//fixed value
									$tar[$kpr]['diffusagecostpernight'] = $diffusageprice['pernight'] == 1 ? 1 : 0;
									$aduseval = $diffusageprice['pernight'] == 1 ? $diffusageprice['value'] * $tar[$kpr]['days'] : $diffusageprice['value'];
									$tar[$kpr]['diffusagecost'] = "-".$aduseval;
									$tar[$kpr]['cost'] = $vpr['cost'] - $aduseval;
								}else {
									//percentage value
									$tar[$kpr]['diffusagecostpernight'] = $diffusageprice['pernight'] == 1 ? $vpr['cost'] : 0;
									$aduseval = $diffusageprice['pernight'] == 1 ? round($vpr['cost'] - ((($vpr['cost'] / $tar[$kpr]['days']) * $diffusageprice['value'] / 100) * $tar[$kpr]['days']), 2) : round(($vpr['cost'] * (100 - $diffusageprice['value']) / 100), 2);
									$tar[$kpr]['diffusagecost'] = "-".$diffusageprice['value']."%";
									$tar[$kpr]['cost'] = $aduseval;
								}
							}
						}
					}
				}
				//
				$tars[$num] = $tar[0];
			}else {
				JError::raiseWarning('', JText::_('VBERRNOFAREFOUND'));
			}
		}
		$pcheckin = $order[0]['checkin'];
		$pcheckout = $order[0]['checkout'];
		$secdiff = $pcheckout - $pcheckin;
		$daysdiff = $secdiff / 86400;
		if (is_int($daysdiff)) {
			if ($daysdiff < 1) {
				$daysdiff = 1;
			}
		}else {
			if ($daysdiff < 1) {
				$daysdiff = 1;
			}else {
				$sum = floor($daysdiff) * 86400;
				$newdiff = $secdiff - $sum;
				$maxhmore = vikbooking::getHoursMoreRb() * 3600;
				if ($maxhmore >= $newdiff) {
					$daysdiff = floor($daysdiff);
				} else {
					$daysdiff = ceil($daysdiff);
				}
			}
		}
		$isdue = 0;
		$pricestr = array();
		$optstr = array();
		foreach($ordersrooms as $kor => $or) {
			$num = $kor + 1;
			if($is_package === true || (!empty($or['cust_cost']) && $or['cust_cost'] > 0.00)) {
				//package cost or cust_cost should always be inclusive of taxes
				$calctar = $or['cust_cost'];
				$isdue += $calctar;
				$pricestr[$num] = (!empty($or['pkg_name']) ? $or['pkg_name'] : JText::_('VBOROOMCUSTRATEPLAN')).": ".$calctar." ".$currencyname;
			}elseif (array_key_exists($num, $tars) && is_array($tars[$num])) {
				$calctar = vikbooking::sayCostPlusIva($tars[$num]['cost'], $tars[$num]['idprice']);
				$tars[$num]['calctar'] = $calctar;
				$isdue += $calctar;
				$pricestr[$num] = vikbooking::getPriceName($tars[$num]['idprice'], $vbo_tn) . ": " . $calctar . " " . $currencyname . (!empty ($tars[$num]['attrdata']) ? "\n" . vikbooking::getPriceAttr($tars[$num]['idprice'], $vbo_tn) . ": " . $tars[$num]['attrdata'] : "");
			}
			if (!empty ($or['optionals'])) {
				$stepo = explode(";", $or['optionals']);
				foreach ($stepo as $oo) {
					if (!empty ($oo)) {
						$stept = explode(":", $oo);
						$q = "SELECT * FROM `#__vikbooking_optionals` WHERE `id`=" . $dbo->quote($stept[0]) . ";";
						$dbo->setQuery($q);
						$dbo->Query($q);
						if ($dbo->getNumRows() == 1) {
							$actopt = $dbo->loadAssocList();
							$vbo_tn->translateContents($actopt, '#__vikbooking_optionals');
							$chvar = '';
							if (!empty($actopt[0]['ageintervals']) && $or['children'] > 0 && strstr($stept[1], '-') != false) {
								$optagecosts = vikbooking::getOptionIntervalsCosts($actopt[0]['ageintervals']);
								$optagenames = vikbooking::getOptionIntervalsAges($actopt[0]['ageintervals']);
								$agestept = explode('-', $stept[1]);
								$stept[1] = $agestept[0];
								$chvar = $agestept[1];
								$actopt[0]['chageintv'] = $chvar;
								$actopt[0]['name'] .= ' ('.$optagenames[($chvar - 1)].')';
								$actopt[0]['quan'] = $stept[1];
								$realcost = (intval($actopt[0]['perday']) == 1 ? (floatval($optagecosts[($chvar - 1)]) * $order[0]['days'] * $stept[1]) : (floatval($optagecosts[($chvar - 1)]) * $stept[1]));
							}else {
								$actopt[0]['quan'] = $stept[1];
								$realcost = (intval($actopt[0]['perday']) == 1 ? ($actopt[0]['cost'] * $order[0]['days'] * $stept[1]) : ($actopt[0]['cost'] * $stept[1]));
							}
							if (!empty ($actopt[0]['maxprice']) && $actopt[0]['maxprice'] > 0 && $realcost > $actopt[0]['maxprice']) {
								$realcost = $actopt[0]['maxprice'];
								if(intval($actopt[0]['hmany']) == 1 && intval($stept[1]) > 1) {
									$realcost = $actopt[0]['maxprice'] * $stept[1];
								}
							}
							if ($actopt[0]['perperson'] == 1) {
								$realcost = $realcost * $or['adults'];
							}
							$tmpopr = vikbooking::sayOptionalsPlusIva($realcost, $actopt[0]['idiva']);
							$isdue += $tmpopr;
							$optstr[$num][] = ($stept[1] > 1 ? $stept[1] . " " : "") . $actopt[0]['name'] . ": " . $tmpopr . " " . $currencyname . "\n";
						}
					}
				}
			}
		}
		//vikbooking 1.1 coupon
		$usedcoupon = false;
		$origisdue = $isdue;
		if(strlen($order[0]['coupon']) > 0) {
			$usedcoupon = true;
			$expcoupon = explode(";", $order[0]['coupon']);
			$isdue = $isdue - $expcoupon[1];
		}
		//
		//ConfirmationNumber
		$confirmnumber = $order[0]['confirmnumber'];
		//end ConfirmationNumber
		$esit_mess = JText::sprintf('VBORDEREMAILRESENT', $order[0]['custmail']);
		$status_str = JText::_('VBCOMPLETED');
		if($cancellation) {
			$confirmnumber = '';
			$esit_mess = JText::sprintf('VBCANCORDEREMAILSENT', $order[0]['custmail']);
			$status_str = JText::_('VBCANCELLED');
		}
		$app = JFactory::getApplication();
		$app->enqueueMessage($esit_mess);
		vikbooking::sendCustMailFromBack($order[0]['custmail'], strip_tags($ftitle)." ".JText::_('VBRENTALORD'), $ftitle, $nowts, $order[0]['custdata'], $rooms, $order[0]['checkin'], $order[0]['checkout'], $pricestr, $optstr, $isdue, $viklink, $status_str, $order[0]['id'], $order[0]['coupon'], $arrpeople, $confirmnumber);
	}
	$mainframe = JFactory::getApplication();
	$mainframe->redirect("index.php?option=".$option."&task=editorder&cid[]=".$oid);
}

function sortOption ($option) {
	$sortid = JRequest::getVar('cid', array(0));
	$pmode = JRequest::getString('mode', '', 'request');
	$dbo = JFactory::getDBO();
	if (!empty($pmode)) {
		$q="SELECT `id`,`ordering` FROM `#__vikbooking_optionals` ORDER BY `#__vikbooking_optionals`.`ordering` ASC;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$totr=$dbo->getNumRows();
		if ($totr > 1) {
			$data = $dbo->loadAssocList();
			if ($pmode=="up") {
				foreach($data as $v){
					if ($v['id']==$sortid[0]) {
						$y=$v['ordering'];
					}
				}
				if ($y && $y > 1) {
					$vik=$y - 1;
					$found=false;
					foreach($data as $v){
						if (intval($v['ordering'])==intval($vik)) {
							$found=true;
							$q="UPDATE `#__vikbooking_optionals` SET `ordering`='".$y."' WHERE `id`='".$v['id']."' LIMIT 1;";
							$dbo->setQuery($q);
							$dbo->Query($q);
							$q="UPDATE `#__vikbooking_optionals` SET `ordering`='".$vik."' WHERE `id`='".$sortid[0]."' LIMIT 1;";
							$dbo->setQuery($q);
							$dbo->Query($q);
							break;
						}
					}
					if(!$found) {
						$q="UPDATE `#__vikbooking_optionals` SET `ordering`='".$vik."' WHERE `id`='".$sortid[0]."' LIMIT 1;";
						$dbo->setQuery($q);
						$dbo->Query($q);
					}
				}
			}elseif ($pmode=="down") {
				foreach($data as $v){
					if ($v['id']==$sortid[0]) {
						$y=$v['ordering'];
					}
				}
				if ($y) {
					$vik=$y + 1;
					$found=false;
					foreach($data as $v){
						if (intval($v['ordering'])==intval($vik)) {
							$found=true;
							$q="UPDATE `#__vikbooking_optionals` SET `ordering`='".$y."' WHERE `id`='".$v['id']."' LIMIT 1;";
							$dbo->setQuery($q);
							$dbo->Query($q);
							$q="UPDATE `#__vikbooking_optionals` SET `ordering`='".$vik."' WHERE `id`='".$sortid[0]."' LIMIT 1;";
							$dbo->setQuery($q);
							$dbo->Query($q);
							break;
						}
					}
					if(!$found) {
						$q="UPDATE `#__vikbooking_optionals` SET `ordering`='".$vik."' WHERE `id`='".$sortid[0]."' LIMIT 1;";
						$dbo->setQuery($q);
						$dbo->Query($q);
					}
				}
			}
		}
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=".$option."&task=viewoptionals");
	}else {
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=".$option);
	}
}

function updateRestriction ($option) {
	$dbo = JFactory::getDBO();
	$pwhere = JRequest::getInt('where', '', 'request');
	$pname = JRequest::getString('name', '', 'request');
	$pmonth = JRequest::getInt('month', '', 'request');
	$pmonth = empty($pmonth) ? 0 : $pmonth;
	$pname = empty($pname) ? 'Restriction '.$pmonth : $pname;
	$pdfrom = JRequest::getString('dfrom', '', 'request');
	$pdto = JRequest::getString('dto', '', 'request');
	$pwday = JRequest::getString('wday', '', 'request');
	$pwdaytwo = JRequest::getString('wdaytwo', '', 'request');
	$pwdaytwo = strlen($pwday) > 0 && strlen($pwdaytwo) > 0 && $pwday == $pwdaytwo ? '' : $pwdaytwo;
	$pcomboa = JRequest::getString('comboa', '', 'request');
	$pcomboa = strlen($pwday) > 0 && strlen($pwdaytwo) > 0 && $pwday != $pwdaytwo ? $pcomboa : '';
	$pcombob = JRequest::getString('combob', '', 'request');
	$pcombob = strlen($pwday) > 0 && strlen($pwdaytwo) > 0 && $pwday != $pwdaytwo ? $pcombob : '';
	$pcomboc = JRequest::getString('comboc', '', 'request');
	$pcomboc = strlen($pwday) > 0 && strlen($pwdaytwo) > 0 && $pwday != $pwdaytwo ? $pcomboc : '';
	$pcombod = JRequest::getString('combod', '', 'request');
	$pcombod = strlen($pwday) > 0 && strlen($pwdaytwo) > 0 && $pwday != $pwdaytwo ? $pcombod : '';
	$combostr = '';
	$combostr .= strlen($pwday) > 0 && strlen($pwdaytwo) > 0 && $pwday != $pwdaytwo && !empty($pcomboa) ? $pcomboa.':' : ':';
	$combostr .= strlen($pwday) > 0 && strlen($pwdaytwo) > 0 && $pwday != $pwdaytwo && !empty($pcombob) ? $pcombob.':' : ':';
	$combostr .= strlen($pwday) > 0 && strlen($pwdaytwo) > 0 && $pwday != $pwdaytwo && !empty($pcomboc) ? $pcomboc.':' : ':';
	$combostr .= strlen($pwday) > 0 && strlen($pwdaytwo) > 0 && $pwday != $pwdaytwo && !empty($pcombod) ? $pcombod : '';
	$pminlos = JRequest::getInt('minlos', '', 'request');
	$pminlos = $pminlos < 1 ? 1 : $pminlos;
	$pmaxlos = JRequest::getInt('maxlos', '', 'request');
	$pmaxlos = empty($pmaxlos) ? 0 : $pmaxlos;
	$pmultiplyminlos = JRequest::getString('multiplyminlos', '', 'request');
	$pmultiplyminlos = empty($pmultiplyminlos) ? 0 : 1;
	$pallrooms = JRequest::getString('allrooms', '', 'request');
	$pallrooms = $pallrooms == "1" ? 1 : 0;
	$pidrooms = JRequest::getVar('idrooms', array(0));
	$ridr = '';
	if (!empty($pidrooms) && @count($pidrooms) && $pallrooms == 0) {
		foreach ($pidrooms as $idr) {
			$ridr .= '-'.$idr.'-;';
		}
	}
	if ($pminlos == 1 && strlen($pwday) == 0) {
		JError::raiseWarning('', JText::_('VBUSELESSRESTRICTION'));
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=".$option."&task=editrestriction&cid[]=".$pwhere);
	}else {
		//check if there are restrictions for this month
		if($pmonth > 0) {
			$q="SELECT `id` FROM `#__vikbooking_restrictions` WHERE `month`='".$pmonth."' AND `id`!='".$pwhere."';";
			$dbo->setQuery($q);
			$dbo->Query($q);
			if ($dbo->getNumRows() > 0) {
				JError::raiseWarning('', JText::_('VBRESTRICTIONMONTHEXISTS'));
				$mainframe = JFactory::getApplication();
				$mainframe->redirect("index.php?option=".$option."&task=editrestriction&cid[]=".$pwhere);
			}
			$pdfrom = 0;
			$pdto = 0;
		}else {
			//dates range
			if (empty($pdfrom) || empty($pdto)) {
				JError::raiseWarning('', JText::_('VBRESTRICTIONERRDRANGE'));
				$mainframe = JFactory::getApplication();
				$mainframe->redirect("index.php?option=".$option."&task=editrestriction&cid[]=".$pwhere);
			}else {
				$pdfrom = vikbooking::getDateTimestamp($pdfrom, 0, 0);
				$pdto = vikbooking::getDateTimestamp($pdto, 0, 0);
			}
		}
		//
		$q="UPDATE `#__vikbooking_restrictions` SET `name`=".$dbo->quote($pname).",`month`='".$pmonth."',`wday`=".(strlen($pwday) > 0 ? "'".$pwday."'" : "null").",`minlos`='".$pminlos."',`multiplyminlos`='".$pmultiplyminlos."',`maxlos`='".$pmaxlos."',`dfrom`=".$pdfrom.",`dto`=".$pdto.",`wdaytwo`=".(strlen($pwday) > 0 && strlen($pwdaytwo) > 0 ? intval($pwdaytwo) : "null").",`wdaycombo`=".(strlen($combostr) > 0 ? $dbo->quote($combostr) : "null").",`allrooms`=".$pallrooms.",`idrooms`=".(strlen($ridr) > 0 ? $dbo->quote($ridr) : "null")." WHERE `id`='".$pwhere."';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$app = JFactory::getApplication();
		$app->enqueueMessage(JText::_('VBRESTRICTIONSAVED'));
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=".$option."&task=restrictions");
	}
}

function saveRestriction ($option) {
	$dbo = JFactory::getDBO();
	$pname = JRequest::getString('name', '', 'request');
	$pmonth = JRequest::getInt('month', '', 'request');
	$pmonth = empty($pmonth) ? 0 : $pmonth;
	$pname = empty($pname) ? 'Restriction '.$pmonth : $pname;
	$pdfrom = JRequest::getString('dfrom', '', 'request');
	$pdto = JRequest::getString('dto', '', 'request');
	$pwday = JRequest::getString('wday', '', 'request');
	$pwdaytwo = JRequest::getString('wdaytwo', '', 'request');
	$pwdaytwo = strlen($pwday) > 0 && strlen($pwdaytwo) > 0 && $pwday == $pwdaytwo ? '' : $pwdaytwo;
	$pcomboa = JRequest::getString('comboa', '', 'request');
	$pcomboa = strlen($pwday) > 0 && strlen($pwdaytwo) > 0 && $pwday != $pwdaytwo ? $pcomboa : '';
	$pcombob = JRequest::getString('combob', '', 'request');
	$pcombob = strlen($pwday) > 0 && strlen($pwdaytwo) > 0 && $pwday != $pwdaytwo ? $pcombob : '';
	$pcomboc = JRequest::getString('comboc', '', 'request');
	$pcomboc = strlen($pwday) > 0 && strlen($pwdaytwo) > 0 && $pwday != $pwdaytwo ? $pcomboc : '';
	$pcombod = JRequest::getString('combod', '', 'request');
	$pcombod = strlen($pwday) > 0 && strlen($pwdaytwo) > 0 && $pwday != $pwdaytwo ? $pcombod : '';
	$combostr = '';
	$combostr .= strlen($pwday) > 0 && strlen($pwdaytwo) > 0 && $pwday != $pwdaytwo && !empty($pcomboa) ? $pcomboa.':' : ':';
	$combostr .= strlen($pwday) > 0 && strlen($pwdaytwo) > 0 && $pwday != $pwdaytwo && !empty($pcombob) ? $pcombob.':' : ':';
	$combostr .= strlen($pwday) > 0 && strlen($pwdaytwo) > 0 && $pwday != $pwdaytwo && !empty($pcomboc) ? $pcomboc.':' : ':';
	$combostr .= strlen($pwday) > 0 && strlen($pwdaytwo) > 0 && $pwday != $pwdaytwo && !empty($pcombod) ? $pcombod : '';
	$pminlos = JRequest::getInt('minlos', '', 'request');
	$pminlos = $pminlos < 1 ? 1 : $pminlos;
	$pmaxlos = JRequest::getInt('maxlos', '', 'request');
	$pmaxlos = empty($pmaxlos) ? 0 : $pmaxlos;
	$pmultiplyminlos = JRequest::getString('multiplyminlos', '', 'request');
	$pmultiplyminlos = empty($pmultiplyminlos) ? 0 : 1;
	$pallrooms = JRequest::getString('allrooms', '', 'request');
	$pallrooms = $pallrooms == "1" ? 1 : 0;
	$pidrooms = JRequest::getVar('idrooms', array(0));
	$ridr = '';
	if (!empty($pidrooms) && @count($pidrooms) && $pallrooms == 0) {
		foreach ($pidrooms as $idr) {
			$ridr .= '-'.$idr.'-;';
		}
	}
	if ($pminlos == 1 && strlen($pwday) == 0) {
		JError::raiseWarning('', JText::_('VBUSELESSRESTRICTION'));
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=".$option."&task=newrestriction");
	}else {
		//check if there are restrictions for this month
		if($pmonth > 0) {
			$q="SELECT `id` FROM `#__vikbooking_restrictions` WHERE `month`='".$pmonth."';";
			$dbo->setQuery($q);
			$dbo->Query($q);
			if ($dbo->getNumRows() > 0) {
				JError::raiseWarning('', JText::_('VBRESTRICTIONMONTHEXISTS'));
				$mainframe = JFactory::getApplication();
				$mainframe->redirect("index.php?option=".$option."&task=newrestriction");
			}
			$pdfrom = 0;
			$pdto = 0;
		}else {
			//dates range
			if (empty($pdfrom) || empty($pdto)) {
				JError::raiseWarning('', JText::_('VBRESTRICTIONERRDRANGE'));
				$mainframe = JFactory::getApplication();
				$mainframe->redirect("index.php?option=".$option."&task=newrestriction");
			}else {
				$pdfrom = vikbooking::getDateTimestamp($pdfrom, 0, 0);
				$pdto = vikbooking::getDateTimestamp($pdto, 0, 0);
			}
		}
		//
		$q="INSERT INTO `#__vikbooking_restrictions` (`name`,`month`,`wday`,`minlos`,`multiplyminlos`,`maxlos`,`dfrom`,`dto`,`wdaytwo`,`wdaycombo`,`allrooms`,`idrooms`) VALUES(".$dbo->quote($pname).", '".$pmonth."', ".(strlen($pwday) > 0 ? "'".$pwday."'" : "null").", '".$pminlos."', '".$pmultiplyminlos."', '".$pmaxlos."', ".$pdfrom.", ".$pdto.", ".(strlen($pwday) > 0 && strlen($pwdaytwo) > 0 ? intval($pwdaytwo) : "null").", ".(strlen($combostr) > 0 ? $dbo->quote($combostr) : "null").", ".$pallrooms.", ".(strlen($ridr) > 0 ? $dbo->quote($ridr) : "null").");";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$lid = $dbo->insertid();
		if (!empty($lid)) {
			$app = JFactory::getApplication();
			$app->enqueueMessage(JText::_('VBRESTRICTIONSAVED'));
			$mainframe = JFactory::getApplication();
			$mainframe->redirect("index.php?option=".$option."&task=restrictions");
		}else {
			JError::raiseWarning('', 'Error while saving');
			$mainframe = JFactory::getApplication();
			$mainframe->redirect("index.php?option=".$option."&task=newrestriction");
		}
	}
}

function editRestriction ($rid, $option) {
	$dbo = JFactory::getDBO();
	$q="SELECT * FROM `#__vikbooking_restrictions` WHERE `id`='".$rid."';";
	$dbo->setQuery($q);
	$dbo->Query($q);
	if ($dbo->getNumRows() > 0) {
		$data = $dbo->loadAssocList();
		$q = "SELECT `id`,`name` FROM `#__vikbooking_rooms` ORDER BY `#__vikbooking_rooms`.`name` ASC;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$rooms = $dbo->getNumRows() > 0 ? $dbo->loadAssocList() : '';
		HTML_vikbooking::pEditRestriction($data[0], $rooms, $option);
	}else {
		JError::raiseWarning('', 'Error, not found');
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=".$option."&task=restrictions");
	}
}

function newRestriction ($option) {
	$dbo = JFactory::getDBO();
	$q = "SELECT `id`,`name` FROM `#__vikbooking_rooms` ORDER BY `#__vikbooking_rooms`.`name` ASC;";
	$dbo->setQuery($q);
	$dbo->Query($q);
	$rooms = $dbo->getNumRows() > 0 ? $dbo->loadAssocList() : '';
	HTML_vikbooking::pNewRestriction($rooms, $option);
}

function viewRestrictions ($option) {
	$dbo = JFactory::getDBO();
	$mainframe = JFactory::getApplication();
	$lim = $mainframe->getUserStateFromRequest("$option.limit", 'limit', $mainframe->getCfg('list_limit'), 'int');
	$lim0 = JRequest::getVar('limitstart', 0, '', 'int');
	$q="SELECT SQL_CALC_FOUND_ROWS * FROM `#__vikbooking_restrictions` ORDER BY `#__vikbooking_restrictions`.`name` ASC";
	$dbo->setQuery($q, $lim0, $lim);
	$dbo->Query($q);
	if ($dbo->getNumRows() > 0) {
		$rows = $dbo->loadAssocList();
		$dbo->setQuery('SELECT FOUND_ROWS();');
		jimport('joomla.html.pagination');
		$pageNav = new JPagination( $dbo->loadResult(), $lim0, $lim );
		$navbut="<table align=\"center\"><tr><td>".$pageNav->getListFooter()."</td></tr></table>";
		HTML_vikbooking::pViewRestrictions($rows, $option, $lim0, $navbut);
	}else {
		$rows = "";
		HTML_vikbooking::pViewRestrictions($rows, $option);
	}
}

function removeRestrictions ($ids, $option) {
	if (@count($ids)) {
		$dbo = JFactory::getDBO();
		foreach($ids as $d){
			$q="DELETE FROM `#__vikbooking_restrictions` WHERE `id`='".$d."';";
			$dbo->setQuery($q);
			$dbo->Query($q);
		}
	}
	$mainframe = JFactory::getApplication();
	$mainframe->redirect("index.php?option=".$option."&task=restrictions");
}

function viewDashboard ($option) {
	$dbo = JFactory::getDBO();
	$q="SELECT COUNT(*) FROM `#__vikbooking_prices`;";
	$dbo->setQuery($q);
	$dbo->Query($q);
	$totprices = $dbo->loadResult();
	$q="SELECT COUNT(*) FROM `#__vikbooking_rooms`;";
	$dbo->setQuery($q);
	$dbo->Query($q);
	$totrooms = $dbo->loadResult();
	$q="SELECT COUNT(*) FROM `#__vikbooking_dispcost`;";
	$dbo->setQuery($q);
	$dbo->Query($q);
	$totdailyfares = $dbo->loadResult();
	$arrayfirst = array('totprices' => $totprices, 'totrooms' => $totrooms, 'totdailyfares' => $totdailyfares);
	$nextreservations = "";
	$totnextresconf = 0;
	$totnextrespend = 0;
	$tot_rooms_units = 0;
	$all_rooms_ids = array();
	$all_rooms_units = array();
	$all_rooms_features = array();
	$today_start_ts = mktime(0, 0, 0, date("n"), date("j"), date("Y"));
	$today_end_ts = mktime(23, 59, 59, date("n"), date("j"), date("Y"));
	$checkin_today = array();
	$checkout_today = array();
	$rooms_locked = array();
	if($totprices > 0 && $totrooms > 0) {
		$q="SELECT `o`.`id`,`o`.`custdata`,`o`.`status`,`o`.`checkin`,`o`.`checkout`,`o`.`roomsnum`,`o`.`country`,(SELECT CONCAT_WS(' ',`or`.`t_first_name`,`or`.`t_last_name`) FROM `#__vikbooking_ordersrooms` AS `or` WHERE `or`.`idorder`=`o`.`id` LIMIT 1) AS `nominative`,(SELECT SUM(`or`.`adults`) FROM `#__vikbooking_ordersrooms` AS `or` WHERE `or`.`idorder`=`o`.`id`) AS `tot_adults`,(SELECT SUM(`or`.`children`) FROM `#__vikbooking_ordersrooms` AS `or` WHERE `or`.`idorder`=`o`.`id`) AS `tot_children` FROM `#__vikbooking_orders` AS `o` WHERE `o`.`status`!='cancelled' AND `o`.`checkin`>".$today_end_ts." ORDER BY `o`.`checkin` ASC LIMIT 10;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if($dbo->getNumRows() > 0) {
			$nextreservations = $dbo->loadAssocList();
		}
		$q="SELECT COUNT(*) FROM `#__vikbooking_orders` WHERE `checkin`>".time()." AND `status`='confirmed';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$totnextresconf = $dbo->loadResult();
		$q="SELECT COUNT(*) FROM `#__vikbooking_orders` WHERE `checkin`>".time()." AND `status`='standby';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$totnextrespend = $dbo->loadResult();
		$q="SELECT SUM(`units`) FROM `#__vikbooking_rooms`;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$tot_rooms_units = $dbo->loadResult();
		$q="SELECT `id`,`name`,`units`,`params` FROM `#__vikbooking_rooms`;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if($dbo->getNumRows() > 0) {
			$all_rooms = $dbo->loadAssocList();
			foreach ($all_rooms as $k => $r) {
				$all_rooms_ids[$r['id']] = $r['name'];
				$all_rooms_units[$r['id']] = $r['units'];
				$rparams = json_decode($r['params'], true);
				$all_rooms_features[$r['id']] = array_key_exists('features', $rparams) && is_array($rparams['features']) ? $rparams['features'] : array();
			}
		}
	}
	$arrayfirst['totnextresconf'] = $totnextresconf;
	$arrayfirst['totnextrespend'] = $totnextrespend;
	$arrayfirst['tot_rooms_units'] = (int)$tot_rooms_units;
	$arrayfirst['all_rooms_ids'] = $all_rooms_ids;
	$arrayfirst['all_rooms_units'] = $all_rooms_units;
	$arrayfirst['all_rooms_features'] = $all_rooms_features;
	$arrayfirst['today_start_ts'] = $today_start_ts;
	$arrayfirst['today_end_ts'] = $today_end_ts;
	$q="SELECT `o`.`id`,`o`.`custdata`,`o`.`status`,`o`.`checkin`,`o`.`checkout`,`o`.`roomsnum`,`o`.`country`,(SELECT CONCAT_WS(' ',`or`.`t_first_name`,`or`.`t_last_name`) FROM `#__vikbooking_ordersrooms` AS `or` WHERE `or`.`idorder`=`o`.`id` LIMIT 1) AS `nominative`,(SELECT SUM(`or`.`adults`) FROM `#__vikbooking_ordersrooms` AS `or` WHERE `or`.`idorder`=`o`.`id`) AS `tot_adults`,(SELECT SUM(`or`.`children`) FROM `#__vikbooking_ordersrooms` AS `or` WHERE `or`.`idorder`=`o`.`id`) AS `tot_children` FROM `#__vikbooking_orders` AS `o` WHERE `o`.`checkin`>=".$today_start_ts." AND `o`.`checkin`<=".$today_end_ts." ORDER BY `o`.`checkin` ASC;";
	$dbo->setQuery($q);
	$dbo->Query($q);
	if($dbo->getNumRows() > 0) {
		$checkin_today = $dbo->loadAssocList();
	}
	$q="SELECT `o`.`id`,`o`.`custdata`,`o`.`status`,`o`.`checkin`,`o`.`checkout`,`o`.`roomsnum`,`o`.`country`,(SELECT CONCAT_WS(' ',`or`.`t_first_name`,`or`.`t_last_name`) FROM `#__vikbooking_ordersrooms` AS `or` WHERE `or`.`idorder`=`o`.`id` LIMIT 1) AS `nominative`,(SELECT SUM(`or`.`adults`) FROM `#__vikbooking_ordersrooms` AS `or` WHERE `or`.`idorder`=`o`.`id`) AS `tot_adults`,(SELECT SUM(`or`.`children`) FROM `#__vikbooking_ordersrooms` AS `or` WHERE `or`.`idorder`=`o`.`id`) AS `tot_children` FROM `#__vikbooking_orders` AS `o` WHERE `o`.`status`!='cancelled' AND `o`.`checkout`>=".$today_start_ts." AND `o`.`checkout`<=".$today_end_ts." ORDER BY `o`.`checkout` ASC;";
	$dbo->setQuery($q);
	$dbo->Query($q);
	if($dbo->getNumRows() > 0) {
		$checkout_today = $dbo->loadAssocList();
	}
	$q = "DELETE FROM `#__vikbooking_tmplock` WHERE `until`<" . time() . ";";
	$dbo->setQuery($q);
	$dbo->Query($q);
	$q = "SELECT `lock`.*,`r`.`name`,(SELECT CONCAT_WS(' ',`or`.`t_first_name`,`or`.`t_last_name`) FROM `#__vikbooking_ordersrooms` AS `or` WHERE `lock`.`idorder`=`or`.`idorder` LIMIT 1) AS `nominative` FROM `#__vikbooking_tmplock` AS `lock` LEFT JOIN `#__vikbooking_rooms` `r` ON `lock`.`idroom`=`r`.`id` WHERE `lock`.`until`>".time()." ORDER BY `lock`.`id` DESC;";
	$dbo->setQuery($q);
	$dbo->Query($q);
	if($dbo->getNumRows() > 0) {
		$rooms_locked = $dbo->loadAssocList();
	}
	HTML_vikbooking::pViewDashboard($arrayfirst, $nextreservations, $checkin_today, $checkout_today, $rooms_locked, $option);
}

function updateCoupon ($option) {
	$pcode = JRequest::getString('code', '', 'request');
	$pvalue = JRequest::getString('value', '', 'request');
	$pfrom = JRequest::getString('from', '', 'request');
	$pto = JRequest::getString('to', '', 'request');
	$pidrooms = JRequest::getVar('idrooms', array(0));
	$pwhere = JRequest::getString('where', '', 'request');
	$ptype = JRequest::getString('type', '', 'request');
	$ptype = $ptype == "1" ? 1 : 2;
	$ppercentot = JRequest::getString('percentot', '', 'request');
	$ppercentot = $ppercentot == "1" ? 1 : 2;
	$pallvehicles = JRequest::getString('allvehicles', '', 'request');
	$pallvehicles = $pallvehicles == "1" ? 1 : 0;
	$pmintotord = JRequest::getString('mintotord', '', 'request');
	$stridrooms="";
	if(@count($pidrooms) > 0 && $pallvehicles != 1) {
		foreach($pidrooms as $ch) {
			if(!empty($ch)) {
				$stridrooms.=";".$ch.";";
			}
		}
	}
	$strdatevalid = "";
	if(strlen($pfrom) > 0 && strlen($pto) > 0) {
		$first=vikbooking::getDateTimestamp($pfrom, 0, 0);
		$second=vikbooking::getDateTimestamp($pto, 0, 0);
		if($first < $second) {
			$strdatevalid .= $first."-".$second;
		}
	}
	$dbo = JFactory::getDBO();
	$q="SELECT * FROM `#__vikbooking_coupons` WHERE `code`=".$dbo->quote($pcode)." AND `id`!='".$pwhere."';";
	$dbo->setQuery($q);
	$dbo->Query($q);
	if($dbo->getNumRows() > 0) {
		JError::raiseWarning('', JText::_('VBCOUPONEXISTS'));
	}else {
		$app = JFactory::getApplication();
		$app->enqueueMessage(JText::_('VBCOUPONSAVEOK'));
		$q="UPDATE `#__vikbooking_coupons` SET `code`=".$dbo->quote($pcode).",`type`='".$ptype."',`percentot`='".$ppercentot."',`value`=".$dbo->quote($pvalue).",`datevalid`='".$strdatevalid."',`allvehicles`='".$pallvehicles."',`idrooms`='".$stridrooms."',`mintotord`=".$dbo->quote($pmintotord)." WHERE `id`='".$pwhere."';";
		$dbo->setQuery($q);
		$dbo->Query($q);
	}
	$mainframe = JFactory::getApplication();
	$mainframe->redirect("index.php?option=".$option."&task=viewcoupons");
}

function saveCoupon ($option) {
	$pcode = JRequest::getString('code', '', 'request');
	$pvalue = JRequest::getString('value', '', 'request');
	$pfrom = JRequest::getString('from', '', 'request');
	$pto = JRequest::getString('to', '', 'request');
	$pidrooms = JRequest::getVar('idrooms', array(0));
	$ptype = JRequest::getString('type', '', 'request');
	$ptype = $ptype == "1" ? 1 : 2;
	$ppercentot = JRequest::getString('percentot', '', 'request');
	$ppercentot = $ppercentot == "1" ? 1 : 2;
	$pallvehicles = JRequest::getString('allvehicles', '', 'request');
	$pallvehicles = $pallvehicles == "1" ? 1 : 0;
	$pmintotord = JRequest::getString('mintotord', '', 'request');
	$stridrooms="";
	if(@count($pidrooms) > 0 && $pallvehicles != 1) {
		foreach($pidrooms as $ch) {
			if(!empty($ch)) {
				$stridrooms.=";".$ch.";";
			}
		}
	}
	$strdatevalid = "";
	if(strlen($pfrom) > 0 && strlen($pto) > 0) {
		$first=vikbooking::getDateTimestamp($pfrom, 0, 0);
		$second=vikbooking::getDateTimestamp($pto, 0, 0);
		if($first < $second) {
			$strdatevalid .= $first."-".$second;
		}
	}
	$dbo = JFactory::getDBO();
	$q="SELECT * FROM `#__vikbooking_coupons` WHERE `code`=".$dbo->quote($pcode).";";
	$dbo->setQuery($q);
	$dbo->Query($q);
	if($dbo->getNumRows() > 0) {
		JError::raiseWarning('', JText::_('VBCOUPONEXISTS'));
	}else {
		$app = JFactory::getApplication();
		$app->enqueueMessage(JText::_('VBCOUPONSAVEOK'));
		$q="INSERT INTO `#__vikbooking_coupons` (`code`,`type`,`percentot`,`value`,`datevalid`,`allvehicles`,`idrooms`,`mintotord`) VALUES(".$dbo->quote($pcode).",'".$ptype."','".$ppercentot."',".$dbo->quote($pvalue).",'".$strdatevalid."','".$pallvehicles."','".$stridrooms."', ".$dbo->quote($pmintotord).");";
		$dbo->setQuery($q);
		$dbo->Query($q);
	}
	$mainframe = JFactory::getApplication();
	$mainframe->redirect("index.php?option=".$option."&task=viewcoupons");
}

function editCoupon ($coupid, $option) {
	$dbo = JFactory::getDBO();
	$q="SELECT * FROM `#__vikbooking_coupons` WHERE `id`='".$coupid."';";
	$dbo->setQuery($q);
	$dbo->Query($q);
	$coupon = $dbo->loadAssocList();
	$coupon = $coupon[0];
	$wselrooms = "";
	$q="SELECT `id`,`name` FROM `#__vikbooking_rooms` ORDER BY `#__vikbooking_rooms`.`name` ASC;";
	$dbo->setQuery($q);
	$dbo->Query($q);
	if ($dbo->getNumRows() > 0) {
		$rooms = $dbo->loadAssocList();
		$filterroomr = array();
		if(strlen($coupon['idrooms']) > 0) {
			$cparts = explode(";", $coupon['idrooms']);
			foreach($cparts as $fc) {
				if(!empty($fc)) {
					$filterroomr[]=$fc;
				}
			}
		}
		$wselrooms = "<select name=\"idrooms[]\" multiple=\"multiple\" size=\"5\">\n";
		foreach($rooms as $c) {
			$wselrooms .= "<option value=\"".$c['id']."\"".(in_array($c['id'], $filterroomr) ? " selected=\"selected\"" : "").">".$c['name']."</option>\n";
		}
		$wselrooms .= "</select>\n";
	}
	HTML_vikbooking::pEditCoupon($coupon, $wselrooms, $option);
}

function newCoupon ($option) {
	$dbo = JFactory::getDBO();
	$wselrooms = "";
	$q="SELECT `id`,`name` FROM `#__vikbooking_rooms` ORDER BY `#__vikbooking_rooms`.`name` ASC;";
	$dbo->setQuery($q);
	$dbo->Query($q);
	if ($dbo->getNumRows() > 0) {
		$rooms = $dbo->loadAssocList();
		$wselrooms = "<select name=\"idrooms[]\" multiple=\"multiple\" size=\"5\">\n";
		foreach($rooms as $c) {
			$wselrooms .= "<option value=\"".$c['id']."\">".$c['name']."</option>\n";
		}
		$wselrooms .= "</select>\n";
	}
	HTML_vikbooking::pNewCoupon($wselrooms, $option);
}

function viewCoupons ($option) {
	$dbo = JFactory::getDBO();
	$mainframe = JFactory::getApplication();
	$lim = $mainframe->getUserStateFromRequest("$option.limit", 'limit', $mainframe->getCfg('list_limit'), 'int');
	$lim0 = JRequest::getVar('limitstart', 0, '', 'int');
	$q="SELECT SQL_CALC_FOUND_ROWS * FROM `#__vikbooking_coupons` ORDER BY `#__vikbooking_coupons`.`code` ASC";
	$dbo->setQuery($q, $lim0, $lim);
	$dbo->Query($q);
	if ($dbo->getNumRows() > 0) {
		$rows = $dbo->loadAssocList();
		$dbo->setQuery('SELECT FOUND_ROWS();');
		jimport('joomla.html.pagination');
		$pageNav = new JPagination( $dbo->loadResult(), $lim0, $lim );
		$navbut="<table align=\"center\"><tr><td>".$pageNav->getListFooter()."</td></tr></table>";
		HTML_vikbooking::pViewCoupons($rows, $option, $lim0, $navbut);
	}else {
		$rows = "";
		HTML_vikbooking::pViewCoupons($rows, $option);
	}
}

function removeMoreImgs($option) {
	$proomid = JRequest::getInt('roomid', '', 'request');
	$pimgind = JRequest::getInt('imgind', '', 'request');
	if(strlen($pimgind) > 0) {
		$dbo = JFactory::getDBO();
		$q="SELECT `moreimgs` FROM `#__vikbooking_rooms` WHERE `id`='".$proomid."';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$actmore=$dbo->loadResult();
		if(strlen($actmore) > 0) {
			$actsplit = explode(';;', $actmore);
			if($pimgind < 0) {
				foreach ($actsplit as $img) {
					@unlink(JPATH_SITE.DS.'components'.DS.'com_vikbooking'.DS.'resources'.DS.'uploads'.DS.'big_'.$img);
					@unlink(JPATH_SITE.DS.'components'.DS.'com_vikbooking'.DS.'resources'.DS.'uploads'.DS.'thumb_'.$img);
				}
				$actsplit = array(0);
			}else {
				if(array_key_exists($pimgind, $actsplit)) {
					@unlink(JPATH_SITE.DS.'components'.DS.'com_vikbooking'.DS.'resources'.DS.'uploads'.DS.'big_'.$actsplit[$pimgind]);
					@unlink(JPATH_SITE.DS.'components'.DS.'com_vikbooking'.DS.'resources'.DS.'uploads'.DS.'thumb_'.$actsplit[$pimgind]);
					unset($actsplit[$pimgind]);
				}
			}
			$newstr="";
			foreach($actsplit as $oi) {
				if(!empty($oi)) {
					$newstr.=$oi.';;';
				}
			}
			$q="UPDATE `#__vikbooking_rooms` SET `moreimgs`=".$dbo->quote($newstr)." WHERE `id`='".$proomid."';";
			$dbo->setQuery($q);
			$dbo->Query($q);
		}
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=".$option."&task=editroom&cid[]=".$proomid);
	}else {
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=".$option);
	}
}

function updateCustomf ($option) {
	$pname = JRequest::getString('name', '', 'request', JREQUEST_ALLOWHTML);
	$ptype = JRequest::getString('type', '', 'request');
	$pchoose = JRequest::getVar('choose', array(0));
	$prequired = JRequest::getString('required', '', 'request');
	$prequired = $prequired == "1" ? 1 : 0;
	$pisemail = JRequest::getString('isemail', '', 'request');
	$pisemail = $pisemail == "1" ? 1 : 0;
	$pisnominative = JRequest::getString('isnominative', '', 'request');
	$pisnominative = $pisnominative == "1" && $ptype == 'text' ? 1 : 0;
	$pisphone = JRequest::getString('isphone', '', 'request');
	$pisphone = $pisphone == "1" && $ptype == 'text' ? 1 : 0;
	$ppoplink = JRequest::getString('poplink', '', 'request');
	$pwhere = JRequest::getInt('where', '', 'request');
	$choosestr="";
	if(@count($pchoose) > 0) {
		foreach($pchoose as $ch) {
			if(!empty($ch)) {
				$choosestr.=$ch.";;__;;";
			}
		}
	}
	$dbo = JFactory::getDBO();
	$q="UPDATE `#__vikbooking_custfields` SET `name`=".$dbo->quote($pname).",`type`=".$dbo->quote($ptype).",`choose`=".$dbo->quote($choosestr).",`required`=".$dbo->quote($prequired).",`isemail`=".$dbo->quote($pisemail).",`poplink`=".$dbo->quote($ppoplink).",`isnominative`=".$pisnominative.",`isphone`=".$pisphone." WHERE `id`=".$dbo->quote($pwhere).";";
	$dbo->setQuery($q);
	$dbo->Query($q);
	$mainframe = JFactory::getApplication();
	$mainframe->redirect("index.php?option=".$option."&task=viewcustomf");
}

function editCustomf ($fid, $option) {
	$dbo = JFactory::getDBO();
	$q="SELECT * FROM `#__vikbooking_custfields` WHERE `id`=".$dbo->quote($fid).";";
	$dbo->setQuery($q);
	$dbo->Query($q);
	$field=$dbo->loadAssocList();
	HTML_vikbooking::pEditCustomf($field[0], $option);
}

function saveCustomf ($option) {
	$pname = JRequest::getString('name', '', 'request', JREQUEST_ALLOWHTML);
	$ptype = JRequest::getString('type', '', 'request');
	$pchoose = JRequest::getVar('choose', array(0));
	$prequired = JRequest::getString('required', '', 'request');
	$prequired = $prequired == "1" ? 1 : 0;
	$pisemail = JRequest::getString('isemail', '', 'request');
	$pisemail = $pisemail == "1" ? 1 : 0;
	$pisnominative = JRequest::getString('isnominative', '', 'request');
	$pisnominative = $pisnominative == "1" && $ptype == 'text' ? 1 : 0;
	$pisphone = JRequest::getString('isphone', '', 'request');
	$pisphone = $pisphone == "1" && $ptype == 'text' ? 1 : 0;
	$ppoplink = JRequest::getString('poplink', '', 'request');
	$choosestr="";
	if(@count($pchoose) > 0) {
		foreach($pchoose as $ch) {
			if(!empty($ch)) {
				$choosestr.=$ch.";;__;;";
			}
		}
	}
	$dbo = JFactory::getDBO();
	$q="SELECT `ordering` FROM `#__vikbooking_custfields` ORDER BY `#__vikbooking_custfields`.`ordering` DESC LIMIT 1;";
	$dbo->setQuery($q);
	$dbo->Query($q);
	if($dbo->getNumRows() == 1) {
		$getlast=$dbo->loadResult();
		$newsortnum=$getlast + 1;
	}else {
		$newsortnum=1;
	}
	$q="INSERT INTO `#__vikbooking_custfields` (`name`,`type`,`choose`,`required`,`ordering`,`isemail`,`poplink`,`isnominative`,`isphone`) VALUES(".$dbo->quote($pname).", ".$dbo->quote($ptype).", ".$dbo->quote($choosestr).", ".$dbo->quote($prequired).", ".$dbo->quote($newsortnum).", ".$dbo->quote($pisemail).", ".$dbo->quote($ppoplink).", ".$pisnominative.", ".$pisphone.");";
	$dbo->setQuery($q);
	$dbo->Query($q);
	$mainframe = JFactory::getApplication();
	$mainframe->redirect("index.php?option=".$option."&task=viewcustomf");
}

function newCustomf ($option) {
	HTML_vikbooking::pNewCustomf($option);
}

function removeCustomf ($ids, $option) {
	if (@count($ids)) {
		$dbo = JFactory::getDBO();
		foreach($ids as $d){
			$q="DELETE FROM `#__vikbooking_custfields` WHERE `id`=".$dbo->quote($d).";";
			$dbo->setQuery($q);
			$dbo->Query($q);
		}
	}
	$mainframe = JFactory::getApplication();
	$mainframe->redirect("index.php?option=".$option."&task=viewcustomf");
}

function removeCoupons ($ids, $option) {
	if (@count($ids)) {
		$dbo = JFactory::getDBO();
		foreach($ids as $d){
			$q="DELETE FROM `#__vikbooking_coupons` WHERE `id`=".$dbo->quote($d).";";
			$dbo->setQuery($q);
			$dbo->Query($q);
		}
	}
	$mainframe = JFactory::getApplication();
	$mainframe->redirect("index.php?option=".$option."&task=viewcoupons");
}

function sortField ($option) {
	$sortid = JRequest::getVar('cid', array(0));
	$pmode = JRequest::getString('mode', '', 'request');
	$dbo = JFactory::getDBO();
	if (!empty($pmode)) {
		$q="SELECT `id`,`ordering` FROM `#__vikbooking_custfields` ORDER BY `#__vikbooking_custfields`.`ordering` ASC;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$totr=$dbo->getNumRows();
		if ($totr > 1) {
			$data = $dbo->loadAssocList();
			if ($pmode=="up") {
				foreach($data as $v){
					if ($v['id']==$sortid[0]) {
						$y=$v['ordering'];
					}
				}
				if ($y && $y > 1) {
					$vik=$y - 1;
					$found=false;
					foreach($data as $v){
						if (intval($v['ordering'])==intval($vik)) {
							$found=true;
							$q="UPDATE `#__vikbooking_custfields` SET `ordering`='".$y."' WHERE `id`='".$v['id']."' LIMIT 1;";
							$dbo->setQuery($q);
							$dbo->Query($q);
							$q="UPDATE `#__vikbooking_custfields` SET `ordering`='".$vik."' WHERE `id`='".$sortid[0]."' LIMIT 1;";
							$dbo->setQuery($q);
							$dbo->Query($q);
							break;
						}
					}
					if(!$found) {
						$q="UPDATE `#__vikbooking_custfields` SET `ordering`='".$vik."' WHERE `id`='".$sortid[0]."' LIMIT 1;";
						$dbo->setQuery($q);
						$dbo->Query($q);
					}
				}
			}elseif ($pmode=="down") {
				foreach($data as $v){
					if ($v['id']==$sortid[0]) {
						$y=$v['ordering'];
					}
				}
				if ($y) {
					$vik=$y + 1;
					$found=false;
					foreach($data as $v){
						if (intval($v['ordering'])==intval($vik)) {
							$found=true;
							$q="UPDATE `#__vikbooking_custfields` SET `ordering`='".$y."' WHERE `id`='".$v['id']."' LIMIT 1;";
							$dbo->setQuery($q);
							$dbo->Query($q);
							$q="UPDATE `#__vikbooking_custfields` SET `ordering`='".$vik."' WHERE `id`='".$sortid[0]."' LIMIT 1;";
							$dbo->setQuery($q);
							$dbo->Query($q);
							break;
						}
					}
					if(!$found) {
						$q="UPDATE `#__vikbooking_custfields` SET `ordering`='".$vik."' WHERE `id`='".$sortid[0]."' LIMIT 1;";
						$dbo->setQuery($q);
						$dbo->Query($q);
					}
				}
			}
		}
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=".$option."&task=viewcustomf");
	}else {
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=".$option);
	}
}

function viewCustomf ($option) {
	$dbo = JFactory::getDBO();
	$mainframe = JFactory::getApplication();
	$lim = $mainframe->getUserStateFromRequest("$option.limit", 'limit', $mainframe->getCfg('list_limit'), 'int');
	$lim0 = JRequest::getVar('limitstart', 0, '', 'int');
	$q="SELECT SQL_CALC_FOUND_ROWS * FROM `#__vikbooking_custfields` ORDER BY `#__vikbooking_custfields`.`ordering` ASC";
	$dbo->setQuery($q, $lim0, $lim);
	$dbo->Query($q);
	if ($dbo->getNumRows() > 0) {
		$rows = $dbo->loadAssocList();
		$dbo->setQuery('SELECT FOUND_ROWS();');
		jimport('joomla.html.pagination');
		$pageNav = new JPagination( $dbo->loadResult(), $lim0, $lim );
		$navbut="<table align=\"center\"><tr><td>".$pageNav->getListFooter()."</td></tr></table>";
		HTML_vikbooking::pViewCustomf($rows, $option, $lim0, $navbut);
	}else {
		$rows = "";
		HTML_vikbooking::pViewCustomf($rows, $option);
	}
}

function showOverview ($option) {
	$dbo = JFactory::getDBO();
	$pmonth = JRequest::getString('month', '', 'request');
	$punits_show_type = JRequest::getString('units_show_type', '', 'request');
	$session = JFactory::getSession();
	if(!empty($punits_show_type)) {
		$session->set('vbUnitsShowType', $punits_show_type);
	}
	if(empty($pmonth)) {
		$sess_month = $session->get('vbOverviewMonth', '');
		if(!empty($sess_month)) {
			$pmonth = $sess_month;
		}
	}
	$oldest_checkin = 0;
	$furthest_checkout = 0;
	$q="SELECT `checkin` FROM `#__vikbooking_busy` ORDER BY `checkin` ASC LIMIT 1;";
	$dbo->setQuery($q);
	$dbo->Query($q);
	if($dbo->getNumRows() > 0) {
		$oldest_arr = $dbo->loadAssocList();
		$oldest_checkin = $oldest_arr[0]['checkin'];
	}
	$q="SELECT `checkout` FROM `#__vikbooking_busy` ORDER BY `checkout` DESC LIMIT 1;";
	$dbo->setQuery($q);
	$dbo->Query($q);
	if($dbo->getNumRows() > 0) {
		$furthest_arr = $dbo->loadAssocList();
		$furthest_checkout = $furthest_arr[0]['checkout'];
	}
	if(!empty($pmonth)) {
		$session->set('vbOverviewMonth', $pmonth);
		$tsstart=$pmonth;
	}else {
		$oggid=getdate();
		$tsstart=mktime(0, 0, 0, $oggid['mon'], 1, $oggid['year']);
	}
	$oggid=getdate($tsstart);
	if($oggid['mon']==12) {
		$nextmon=1;
		$year=$oggid['year'] + 1;
	}else {
		$nextmon=$oggid['mon'] + 1;
		$year=$oggid['year'];
	}
	$tsend=mktime(0, 0, 0, $nextmon, 1, $year);
	$today=getdate();
	$firstmonth=mktime(0, 0, 0, $today['mon'], 1, $today['year']);
	$wmonthsel="<select name=\"month\" onchange=\"document.vboverview.submit();\">\n";
	if (!empty($oldest_checkin)) {
		$oldest_date = getdate($oldest_checkin);
		$oldest_month = mktime(0, 0, 0, $oldest_date['mon'], 1, $oldest_date['year']);
		if ($oldest_month < $firstmonth) {
			while ($oldest_month < $firstmonth) {
				$wmonthsel.="<option value=\"".$oldest_month."\"".($oldest_month==$tsstart ? " selected=\"selected\"" : "").">".vikbooking::sayMonth($oldest_date['mon'])." ".$oldest_date['year']."</option>\n";
				if($oldest_date['mon']==12) {
					$nextmon=1;
					$year=$oldest_date['year'] + 1;
				}else {
					$nextmon=$oldest_date['mon'] + 1;
					$year=$oldest_date['year'];
				}
				$oldest_month = mktime(0, 0, 0, $nextmon, 1, $year);
				$oldest_date = getdate($oldest_month);
			}
		}
	}
	$wmonthsel.="<option value=\"".$firstmonth."\"".($firstmonth==$tsstart ? " selected=\"selected\"" : "").">".vikbooking::sayMonth($today['mon'])." ".$today['year']."</option>\n";
	$futuremonths = 12;
	if (!empty($furthest_checkout)) {
		$furthest_date = getdate($furthest_checkout);
		$furthest_month = mktime(0, 0, 0, $furthest_date['mon'], 1, $furthest_date['year']);
		if ($furthest_month > $firstmonth) {
			$monthsdiff = floor(($furthest_month - $firstmonth) / (86400 * 30));
			$futuremonths = $monthsdiff > $futuremonths ? $monthsdiff : $futuremonths;
		}
	}
	for($i=1; $i<=$futuremonths; $i++) {
		$newts=getdate($firstmonth);
		if($newts['mon']==12) {
			$nextmon=1;
			$year=$newts['year'] + 1;
		}else {
			$nextmon=$newts['mon'] + 1;
			$year=$newts['year'];
		}
		$firstmonth=mktime(0, 0, 0, $nextmon, 1, $year);
		$newts=getdate($firstmonth);
		$wmonthsel.="<option value=\"".$firstmonth."\"".($firstmonth==$tsstart ? " selected=\"selected\"" : "").">".vikbooking::sayMonth($newts['mon'])." ".$newts['year']."</option>\n";
	}
	$wmonthsel.="</select>\n";
	$mainframe = JFactory::getApplication();
	$lim = $mainframe->getUserStateFromRequest("$option.limit", 'limit', $mainframe->getCfg('list_limit'), 'int');
	$lim0 = JRequest::getVar('limitstart', 0, '', 'int');
	$q="SELECT SQL_CALC_FOUND_ROWS * FROM `#__vikbooking_rooms` ORDER BY `#__vikbooking_rooms`.`name` ASC";
	$dbo->setQuery($q, $lim0, $lim);
	$dbo->Query($q);
	if ($dbo->getNumRows() > 0) {
		$rows = $dbo->loadAssocList();
		$dbo->setQuery('SELECT FOUND_ROWS();');
		jimport('joomla.html.pagination');
		$pageNav = new JPagination( $dbo->loadResult(), $lim0, $lim );
		$navbut="<table align=\"center\"><tr><td>".$pageNav->getListFooter()."</td></tr></table>";
		$arrbusy=array();
		$actnow=time();
		foreach($rows as $r) {
			$q="SELECT `b`.*,`ob`.`idorder` FROM `#__vikbooking_busy` AS `b`,`#__vikbooking_ordersbusy` AS `ob` WHERE `b`.`idroom`='".$r['id']."' AND `b`.`id`=`ob`.`idbusy` AND (`b`.`checkin`>=".$tsstart." OR `b`.`checkout`>=".$tsstart.") AND (`b`.`checkin`<=".$tsend." OR `b`.`checkout`<=".$tsstart.");";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$cbusy=$dbo->getNumRows() > 0 ? $dbo->loadAssocList() : "";
			$arrbusy[$r['id']]=$cbusy;
		}
		HTML_vikbooking::pShowOverview($rows, $arrbusy, $wmonthsel, $tsstart, $option, $lim0, $navbut);
	}else {
		JError::raiseWarning('', JText::_('VBOVERVIEWNOROOMS'));
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=".$option);
	}
}

function setOrderConfirmed ($oid, $option) {
	$dbo = JFactory::getDBO();
	$q="SELECT * FROM `#__vikbooking_orders` WHERE `id`='".$oid."';";
	$dbo->setQuery($q);
	$dbo->Query($q);
	if($dbo->getNumRows() == 1) {
		$order=$dbo->loadAssocList();
		//check if the language in use is the same as the one used during the checkout
		if (!empty($order[0]['lang'])) {
			$lang = JFactory::getLanguage();
			if($lang->getTag() != $order[0]['lang']) {
				$lang->load('com_vikbooking', JPATH_ADMINISTRATOR, $order[0]['lang'], true);
			}
		}
		//
		$vbo_tn = vikbooking::getTranslator();
		$q="SELECT `or`.*,`r`.`id` AS `r_reference_id`,`r`.`name`,`r`.`units`,`r`.`fromadult`,`r`.`toadult`,`r`.`params` FROM `#__vikbooking_ordersrooms` AS `or`,`#__vikbooking_rooms` AS `r` WHERE `or`.`idorder`='".$order[0]['id']."' AND `or`.`idroom`=`r`.`id` ORDER BY `or`.`id` ASC;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$ordersrooms=$dbo->loadAssocList();
		$vbo_tn->translateContents($ordersrooms, '#__vikbooking_rooms', array('id' => 'r_reference_id'));
		$currencyname = vikbooking::getCurrencyName();
		$realback=vikbooking::getHoursRoomAvail() * 3600;
		$realback+=$order[0]['checkout'];
		$allbook = true;
		$notavail = array();
		foreach($ordersrooms as $ind => $or) {
			if (!vikbooking::roomBookable($or['idroom'], $or['units'], $order[0]['checkin'], $order[0]['checkout'])) {
				$allbook = false;
				$notavail[] = $or['name']." (".JText::_('VBMAILADULTS').": ".$or['adults'].($or['children'] > 0 ? " - ".JText::_('VBMAILCHILDREN').": ".$or['children'] : "").")";
			}
		}
		if (!$allbook) {
			JError::raiseWarning('', JText::_('VBERRCONFORDERNOTAVROOM').' '.implode(", ", $notavail).'<br/>'.JText::_('VBUNABLESETRESCONF'));
		}else {
			$rooms = array();
			$tars = array();
			$arrpeople = array();
			$is_package = !empty($order[0]['pkg']) ? true : false;
			foreach($ordersrooms as $ind => $or) {
				$q="INSERT INTO `#__vikbooking_busy` (`idroom`,`checkin`,`checkout`,`realback`) VALUES('".$or['idroom']."','".$order[0]['checkin']."','".$order[0]['checkout']."','".$realback."');";
				$dbo->setQuery($q);
				$dbo->Query($q);
				$lid = $dbo->insertid();
				$q = "INSERT INTO `#__vikbooking_ordersbusy` (`idorder`,`idbusy`) VALUES('".$oid."', '".$lid."');";
				$dbo->setQuery($q);
				$dbo->Query($q);
			}
			$q="UPDATE `#__vikbooking_orders` SET `status`='confirmed' WHERE `id`='".$order[0]['id']."';";
			$dbo->setQuery($q);
			$dbo->Query($q);
			//send mail
			$ftitle=vikbooking::getFrontTitle ();
			$nowts=time();
			$viklink=JURI::root()."index.php?option=com_vikbooking&task=vieworder&sid=".$order[0]['sid']."&ts=".$order[0]['ts'];
			//Assign room specific unit
			$set_room_indexes = vikbooking::autoRoomUnit();
			$room_indexes_usemap = array();
			//
			foreach($ordersrooms as $kor => $or) {
				$num = $kor + 1;
				$rooms[$num] = $or;
				$arrpeople[$num]['adults'] = $or['adults'];
				$arrpeople[$num]['children'] = $or['children'];
				//Assign room specific unit
				if($set_room_indexes === true) {
					$room_indexes = vikbooking::getRoomUnitNumsAvailable($order[0], $or['r_reference_id']);
					$use_ind_key = 0;
					if(count($room_indexes)) {
						if(!array_key_exists($or['r_reference_id'], $room_indexes_usemap)) {
							$room_indexes_usemap[$or['r_reference_id']] = $use_ind_key;
						}else {
							$use_ind_key = $room_indexes_usemap[$or['r_reference_id']];
						}
						$q = "UPDATE `#__vikbooking_ordersrooms` SET `roomindex`=".(int)$room_indexes[$use_ind_key]." WHERE `id`=".(int)$or['id'].";";
						$dbo->setQuery($q);
						$dbo->Query($q);
						//update rooms references for the customer email sending function
						$rooms[$num]['roomindex'] = (int)$room_indexes[$use_ind_key];
						//
						$room_indexes_usemap[$or['r_reference_id']]++;
					}
				}
				//
				if($is_package === true || (!empty($or['cust_cost']) && $or['cust_cost'] > 0.00)) {
					//package or custom cost set from the back-end
					continue;
				}
				$q="SELECT * FROM `#__vikbooking_dispcost` WHERE `id`='".$or['idtar']."';";
				$dbo->setQuery($q);
				$dbo->Query($q);
				if($dbo->getNumRows() > 0) {
					$tar = $dbo->loadAssocList();
					$tar = vikbooking::applySeasonsRoom($tar, $order[0]['checkin'], $order[0]['checkout']);
					//different usage
					if ($or['fromadult'] <= $or['adults'] && $or['toadult'] >= $or['adults']) {
						$diffusageprice = vikbooking::loadAdultsDiff($or['idroom'], $or['adults']);
						//Occupancy Override
						$occ_ovr = vikbooking::occupancyOverrideExists($tar, $or['adults']);
						$diffusageprice = $occ_ovr !== false ? $occ_ovr : $diffusageprice;
						//
						if (is_array($diffusageprice)) {
							//set a charge or discount to the price(s) for the different usage of the room
							foreach($tar as $kpr => $vpr) {
								$tar[$kpr]['diffusage'] = $or['adults'];
								if ($diffusageprice['chdisc'] == 1) {
									//charge
									if ($diffusageprice['valpcent'] == 1) {
										//fixed value
										$tar[$kpr]['diffusagecostpernight'] = $diffusageprice['pernight'] == 1 ? 1 : 0;
										$aduseval = $diffusageprice['pernight'] == 1 ? $diffusageprice['value'] * $tar[$kpr]['days'] : $diffusageprice['value'];
										$tar[$kpr]['diffusagecost'] = "+".$aduseval;
										$tar[$kpr]['cost'] = $vpr['cost'] + $aduseval;
									}else {
										//percentage value
										$tar[$kpr]['diffusagecostpernight'] = $diffusageprice['pernight'] == 1 ? $vpr['cost'] : 0;
										$aduseval = $diffusageprice['pernight'] == 1 ? round(($vpr['cost'] * $diffusageprice['value'] / 100) * $tar[$kpr]['days'] + $vpr['cost'], 2) : round(($vpr['cost'] * (100 + $diffusageprice['value']) / 100), 2);
										$tar[$kpr]['diffusagecost'] = "+".$diffusageprice['value']."%";
										$tar[$kpr]['cost'] = $aduseval;
									}
								}else {
									//discount
									if ($diffusageprice['valpcent'] == 1) {
										//fixed value
										$tar[$kpr]['diffusagecostpernight'] = $diffusageprice['pernight'] == 1 ? 1 : 0;
										$aduseval = $diffusageprice['pernight'] == 1 ? $diffusageprice['value'] * $tar[$kpr]['days'] : $diffusageprice['value'];
										$tar[$kpr]['diffusagecost'] = "-".$aduseval;
										$tar[$kpr]['cost'] = $vpr['cost'] - $aduseval;
									}else {
										//percentage value
										$tar[$kpr]['diffusagecostpernight'] = $diffusageprice['pernight'] == 1 ? $vpr['cost'] : 0;
										$aduseval = $diffusageprice['pernight'] == 1 ? round($vpr['cost'] - ((($vpr['cost'] / $tar[$kpr]['days']) * $diffusageprice['value'] / 100) * $tar[$kpr]['days']), 2) : round(($vpr['cost'] * (100 - $diffusageprice['value']) / 100), 2);
										$tar[$kpr]['diffusagecost'] = "-".$diffusageprice['value']."%";
										$tar[$kpr]['cost'] = $aduseval;
									}
								}
							}
						}
					}
					//
					$tars[$num] = $tar[0];
				}else {
					JError::raiseWarning('', JText::_('VBERRNOFAREFOUND'));
				}
			}
			$pcheckin = $order[0]['checkin'];
			$pcheckout = $order[0]['checkout'];
			$secdiff = $pcheckout - $pcheckin;
			$daysdiff = $secdiff / 86400;
			if (is_int($daysdiff)) {
				if ($daysdiff < 1) {
					$daysdiff = 1;
				}
			}else {
				if ($daysdiff < 1) {
					$daysdiff = 1;
				}else {
					$sum = floor($daysdiff) * 86400;
					$newdiff = $secdiff - $sum;
					$maxhmore = vikbooking::getHoursMoreRb() * 3600;
					if ($maxhmore >= $newdiff) {
						$daysdiff = floor($daysdiff);
					} else {
						$daysdiff = ceil($daysdiff);
					}
				}
			}
			$isdue = 0;
			$pricestr = array();
			$optstr = array();
			foreach($ordersrooms as $kor => $or) {
				$num = $kor + 1;
				if($is_package === true || (!empty($or['cust_cost']) && $or['cust_cost'] > 0.00)) {
					//package cost or cust_cost should always be inclusive of taxes
					$calctar = $or['cust_cost'];
					$isdue += $calctar;
					$pricestr[$num] = (!empty($or['pkg_name']) ? $or['pkg_name'] : JText::_('VBOROOMCUSTRATEPLAN')).": ".$calctar." ".$currencyname;
				}elseif (array_key_exists($num, $tars) && is_array($tars[$num])) {
					$calctar = vikbooking::sayCostPlusIva($tars[$num]['cost'], $tars[$num]['idprice']);
					$tars[$num]['calctar'] = $calctar;
					$isdue += $calctar;
					$pricestr[$num] = vikbooking::getPriceName($tars[$num]['idprice'], $vbo_tn) . ": " . $calctar . " " . $currencyname . (!empty ($tars[$num]['attrdata']) ? "\n" . vikbooking::getPriceAttr($tars[$num]['idprice'], $vbo_tn) . ": " . $tars[$num]['attrdata'] : "");
				}
				if (!empty ($or['optionals'])) {
					$stepo = explode(";", $or['optionals']);
					foreach ($stepo as $oo) {
						if (!empty ($oo)) {
							$stept = explode(":", $oo);
							$q = "SELECT * FROM `#__vikbooking_optionals` WHERE `id`=" . $dbo->quote($stept[0]) . ";";
							$dbo->setQuery($q);
							$dbo->Query($q);
							if ($dbo->getNumRows() == 1) {
								$actopt = $dbo->loadAssocList();
								$vbo_tn->translateContents($actopt, '#__vikbooking_optionals');
								$chvar = '';
								if (!empty($actopt[0]['ageintervals']) && $or['children'] > 0 && strstr($stept[1], '-') != false) {
									$optagecosts = vikbooking::getOptionIntervalsCosts($actopt[0]['ageintervals']);
									$optagenames = vikbooking::getOptionIntervalsAges($actopt[0]['ageintervals']);
									$agestept = explode('-', $stept[1]);
									$stept[1] = $agestept[0];
									$chvar = $agestept[1];
									$actopt[0]['chageintv'] = $chvar;
									$actopt[0]['name'] .= ' ('.$optagenames[($chvar - 1)].')';
									$actopt[0]['quan'] = $stept[1];
									$realcost = (intval($actopt[0]['perday']) == 1 ? (floatval($optagecosts[($chvar - 1)]) * $order[0]['days'] * $stept[1]) : (floatval($optagecosts[($chvar - 1)]) * $stept[1]));
								}else {
									$actopt[0]['quan'] = $stept[1];
									$realcost = (intval($actopt[0]['perday']) == 1 ? ($actopt[0]['cost'] * $order[0]['days'] * $stept[1]) : ($actopt[0]['cost'] * $stept[1]));
								}
								if (!empty ($actopt[0]['maxprice']) && $actopt[0]['maxprice'] > 0 && $realcost > $actopt[0]['maxprice']) {
									$realcost = $actopt[0]['maxprice'];
									if(intval($actopt[0]['hmany']) == 1 && intval($stept[1]) > 1) {
										$realcost = $actopt[0]['maxprice'] * $stept[1];
									}
								}
								if ($actopt[0]['perperson'] == 1) {
									$realcost = $realcost * $or['adults'];
								}
								$tmpopr = vikbooking::sayOptionalsPlusIva($realcost, $actopt[0]['idiva']);
								$isdue += $tmpopr;
								$optstr[$num][] = ($stept[1] > 1 ? $stept[1] . " " : "") . $actopt[0]['name'] . ": " . $tmpopr . " " . $currencyname . "\n";
							}
						}
					}
				}
			}
			//vikbooking 1.1 coupon
			$usedcoupon = false;
			$origisdue = $isdue;
			if(strlen($order[0]['coupon']) > 0) {
				$usedcoupon = true;
				$expcoupon = explode(";", $order[0]['coupon']);
				$isdue = $isdue - $expcoupon[1];
			}
			//
			//ConfirmationNumber
			$confirmnumber = vikbooking::generateConfirmNumber($order[0]['id'], true);
			//end ConfirmationNumber
			$app = JFactory::getApplication();
			$app->enqueueMessage(JText::_('VBORDERSETASCONF'));
			vikbooking::sendCustMailFromBack($order[0]['custmail'], strip_tags($ftitle)." ".JText::_('VBRENTALORD'), $ftitle, $nowts, $order[0]['custdata'], $rooms, $order[0]['checkin'], $order[0]['checkout'], $pricestr, $optstr, $isdue, $viklink, JText::_('VBCOMPLETED'), $order[0]['id'], $order[0]['coupon'], $arrpeople, $confirmnumber);
			//SMS skipping the administrator
			vikbooking::sendBookingSMS($order[0]['id'], array('admin'));
			//
			//Invoke Channel Manager
			if(file_exists(JPATH_SITE . DS ."components". DS ."com_vikchannelmanager". DS . "helpers" . DS ."synch.vikbooking.php")) {
				$vcm_sync_url = 'index.php?option=com_vikbooking&task=invoke_vcm&stype=new&cid[]='.$order[0]['id'].'&returl='.urlencode('index.php?option=com_vikbooking&task=editorder&cid[]='.$order[0]['id']);
				JError::raiseNotice('', JText::_('VBCHANNELMANAGERINVOKEASK').' <button type="button" class="btn btn-primary" onclick="document.location.href=\''.$vcm_sync_url.'\';">'.JText::_('VBCHANNELMANAGERSENDRQ').'</button>');
			}
			//
		}
	}
	$mainframe = JFactory::getApplication();
	$mainframe->redirect("index.php?option=".$option."&task=editorder&cid[]=".$oid);
}

function removePayments ($ids, $option) {
	if (@count($ids)) {
		$dbo = JFactory::getDBO();
		foreach($ids as $d){
			$q="DELETE FROM `#__vikbooking_gpayments` WHERE `id`=".$dbo->quote($d).";";
			$dbo->setQuery($q);
			$dbo->Query($q);
		}
	}
	$mainframe = JFactory::getApplication();
	$mainframe->redirect("index.php?option=".$option."&task=payments");
}

function updatePayment ($option) {
	$pwhere = JRequest::getString('where', '', 'request');
	$pname = JRequest::getString('name', '', 'request');
	$ppayment = JRequest::getString('payment', '', 'request');
	$ppublished = JRequest::getString('published', '', 'request');
	$pcharge = JRequest::getString('charge', '', 'request');
	$psetconfirmed = JRequest::getString('setconfirmed', '', 'request');
	$pshownotealw = JRequest::getString('shownotealw', '', 'request');
	$pnote = JRequest::getString('note', '', 'request', JREQUEST_ALLOWRAW);
	$pval_pcent = JRequest::getString('val_pcent', '', 'request');
	$pval_pcent = !in_array($pval_pcent, array('1', '2')) ? 1 : $pval_pcent;
	$pch_disc = JRequest::getString('ch_disc', '', 'request');
	$pch_disc = !in_array($pch_disc, array('1', '2')) ? 1 : $pch_disc;
	$vikpaymentparams = JRequest::getVar('vikpaymentparams', array(0));
	$payparamarr = array();
	$payparamstr = '';
	if(count($vikpaymentparams) > 0) {
		foreach($vikpaymentparams as $setting => $cont) {
			if (strlen($setting) > 0) {
				$payparamarr[$setting] = $cont;
			}
		}
		if (count($payparamarr) > 0) {
			$payparamstr = json_encode($payparamarr);
		}
	}
	$dbo = JFactory::getDBO();
	if(!empty($pname) && !empty($ppayment) && !empty($pwhere)) {
		$setpub=$ppublished=="1" ? 1 : 0;
		$psetconfirmed=$psetconfirmed=="1" ? 1 : 0;
		$pshownotealw=$pshownotealw=="1" ? 1 : 0;
		$q="SELECT `id` FROM `#__vikbooking_gpayments` WHERE `file`=".$dbo->quote($ppayment)." AND `id`!='".$pwhere."';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if($dbo->getNumRows() >= 0) {
			$q="UPDATE `#__vikbooking_gpayments` SET `name`=".$dbo->quote($pname).",`file`=".$dbo->quote($ppayment).",`published`='".$setpub."',`note`=".$dbo->quote($pnote).",`charge`=".$dbo->quote($pcharge).",`setconfirmed`='".$psetconfirmed."',`shownotealw`='".$pshownotealw."',`val_pcent`='".$pval_pcent."',`ch_disc`='".$pch_disc."',`params`=".$dbo->quote($payparamstr)." WHERE `id`=".$dbo->quote($pwhere).";";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$app = JFactory::getApplication();
			$app->enqueueMessage(JText::_('VBPAYMENTUPDATED'));
			$mainframe = JFactory::getApplication();
			$mainframe->redirect("index.php?option=".$option."&task=payments");
		}else {
			JError::raiseWarning('', JText::_('ERRINVFILEPAYMENT'));
			$mainframe = JFactory::getApplication();
			$mainframe->redirect("index.php?option=".$option."&task=editpayment&cid[]=".$pwhere);
		}
	}else {
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=".$option."&task=editpayment&cid[]=".$pwhere);
	}
}

function savePayment ($option) {
	$pname = JRequest::getString('name', '', 'request');
	$ppayment = JRequest::getString('payment', '', 'request');
	$ppublished = JRequest::getString('published', '', 'request');
	$pcharge = JRequest::getString('charge', '', 'request');
	$psetconfirmed = JRequest::getString('setconfirmed', '', 'request');
	$pshownotealw = JRequest::getString('shownotealw', '', 'request');
	$pnote = JRequest::getString('note', '', 'request', JREQUEST_ALLOWHTML);
	$pval_pcent = JRequest::getString('val_pcent', '', 'request');
	$pval_pcent = !in_array($pval_pcent, array('1', '2')) ? 1 : $pval_pcent;
	$pch_disc = JRequest::getString('ch_disc', '', 'request');
	$pch_disc = !in_array($pch_disc, array('1', '2')) ? 1 : $pch_disc;
	$vikpaymentparams = JRequest::getVar('vikpaymentparams', array(0));
	$payparamarr = array();
	$payparamstr = '';
	if(count($vikpaymentparams) > 0) {
		foreach($vikpaymentparams as $setting => $cont) {
			if (strlen($setting) > 0) {
				$payparamarr[$setting] = $cont;
			}
		}
		if (count($payparamarr) > 0) {
			$payparamstr = json_encode($payparamarr);
		}
	}
	$dbo = JFactory::getDBO();
	if(!empty($pname) && !empty($ppayment)) {
		$setpub=$ppublished=="1" ? 1 : 0;
		$psetconfirmed=$psetconfirmed=="1" ? 1 : 0;
		$pshownotealw=$pshownotealw=="1" ? 1 : 0;
		$q="SELECT `id` FROM `#__vikbooking_gpayments` WHERE `file`=".$dbo->quote($ppayment).";";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if($dbo->getNumRows() >= 0) {
			$q="INSERT INTO `#__vikbooking_gpayments` (`name`,`file`,`published`,`note`,`charge`,`setconfirmed`,`shownotealw`,`val_pcent`,`ch_disc`,`params`) VALUES(".$dbo->quote($pname).",".$dbo->quote($ppayment).",'".$setpub."',".$dbo->quote($pnote).",".$dbo->quote($pcharge).",'".$psetconfirmed."','".$pshownotealw."','".$pval_pcent."','".$pch_disc."',".$dbo->quote($payparamstr).");";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$app = JFactory::getApplication();
			$app->enqueueMessage(JText::_('VBPAYMENTSAVED'));
			$mainframe = JFactory::getApplication();
			$mainframe->redirect("index.php?option=".$option."&task=payments");
		}else {
			JError::raiseWarning('', JText::_('ERRINVFILEPAYMENT'));
			$mainframe = JFactory::getApplication();
			$mainframe->redirect("index.php?option=".$option."&task=newpayment");
		}
	}else {
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=".$option."&task=newpayment");
	}
}

function editPayment ($pid, $option) {
	$dbo = JFactory::getDBO();
	$q="SELECT * FROM `#__vikbooking_gpayments` WHERE `id`=".$dbo->quote($pid).";";
	$dbo->setQuery($q);
	$dbo->Query($q);
	$pdata=$dbo->loadAssocList();
	HTML_vikbooking::pEditPayment($pdata[0], $option);
}

function newPayment ($option) {
	HTML_vikbooking::pNewPayment($option);
}

function showPayments ($option) {
	$dbo = JFactory::getDBO();
	$mainframe = JFactory::getApplication();
	$lim = $mainframe->getUserStateFromRequest("$option.limit", 'limit', $mainframe->getCfg('list_limit'), 'int');
	$lim0 = JRequest::getVar('limitstart', 0, '', 'int');
	$q="SELECT SQL_CALC_FOUND_ROWS * FROM `#__vikbooking_gpayments` ORDER BY `#__vikbooking_gpayments`.`ordering` ASC";
	$dbo->setQuery($q, $lim0, $lim);
	$dbo->Query($q);
	if ($dbo->getNumRows() > 0) {
		$rows = $dbo->loadAssocList();
		$dbo->setQuery('SELECT FOUND_ROWS();');
		jimport('joomla.html.pagination');
		$pageNav = new JPagination( $dbo->loadResult(), $lim0, $lim );
		$navbut="<table align=\"center\"><tr><td>".$pageNav->getListFooter()."</td></tr></table>";
		HTML_vikbooking::pShowPayments($rows, $option, $lim0, $navbut);
	}else {
		$rows = "";
		HTML_vikbooking::pShowPayments($rows, $option);
	}	
}

function modAvailPayment ($idp, $option) {
	if (!empty($idp)) {
		$dbo = JFactory::getDBO();
		$q="SELECT `published` FROM `#__vikbooking_gpayments` WHERE `id`=".intval($idp).";";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$get = $dbo->loadAssocList();
		$q="UPDATE `#__vikbooking_gpayments` SET `published`='".(intval($get[0]['published'])==1 ? 0 : 1)."' WHERE `id`=".intval($idp).";";
		$dbo->setQuery($q);
		$dbo->Query($q);
	}
	cancelEditingPayment($option);
}

function removeSeasons ($ids, $option) {
	if (@count($ids)) {
		$dbo = JFactory::getDBO();
		foreach($ids as $d){
			$q="DELETE FROM `#__vikbooking_seasons` WHERE `id`=".$dbo->quote($d).";";
			$dbo->setQuery($q);
			$dbo->Query($q);
		}
	}
	$mainframe = JFactory::getApplication();
	$mainframe->redirect("index.php?option=".$option."&task=seasons");
}

function updateSeason ($option, $stay = false) {
	$pwhere = JRequest::getString('where', '', 'request');
	$pfrom = JRequest::getString('from', '', 'request');
	$pto = JRequest::getString('to', '', 'request');
	$ptype = JRequest::getString('type', '', 'request');
	$pdiffcost = JRequest::getString('diffcost', '', 'request');
	$pidrooms = JRequest::getVar('idrooms', array(0));
	$pidprices = JRequest::getVar('idprices', array(0));
	$pwdays = JRequest::getVar('wdays', array());
	$pspname = JRequest::getString('spname', '', 'request');
	$pcheckinincl = JRequest::getString('checkinincl', '', 'request');
	$pcheckinincl = $pcheckinincl == 1 ? 1 : 0;
	$pyeartied = JRequest::getString('yeartied', '', 'request');
	$pyeartied = $pyeartied == "1" ? 1 : 0;
	$tieyear = 0;
	$ppromo = JRequest::getInt('promo', '', 'request');
	$ppromo = $ppromo == 1 ? 1 : 0;
	$ppromodaysadv = JRequest::getInt('promodaysadv', '', 'request');
	$ppromominlos = JRequest::getInt('promominlos', '', 'request');
	$ppromotxt = JRequest::getString('promotxt', '', 'request', JREQUEST_ALLOWHTML);
	$pval_pcent = JRequest::getString('val_pcent', '', 'request');
	$pval_pcent = $pval_pcent == "1" ? 1 : 2;
	$proundmode = JRequest::getString('roundmode', '', 'request');
	$proundmode = (!empty($proundmode) && in_array($proundmode, array('PHP_ROUND_HALF_UP', 'PHP_ROUND_HALF_DOWN')) ? $proundmode : '');
	$pnightsoverrides = JRequest::getVar('nightsoverrides', array());
	$pvaluesoverrides = JRequest::getVar('valuesoverrides', array());
	$pandmoreoverride = JRequest::getVar('andmoreoverride', array());
	$padultsdiffchdisc = JRequest::getVar('adultsdiffchdisc', array());
	$padultsdiffval = JRequest::getVar('adultsdiffval', array());
	$padultsdiffvalpcent = JRequest::getVar('adultsdiffvalpcent', array());
	$padultsdiffpernight = JRequest::getVar('adultsdiffpernight', array());
	$occupancy_ovr = array();
	$dbo = JFactory::getDBO();
	if((!empty($pfrom) && !empty($pto)) || count($pwdays) > 0) {
		$skipseason = false;
		if(empty($pfrom) || empty($pto)) {
			$skipseason = true;
		}
		$skipdays = false;
		$wdaystr = null;
		if(count($pwdays) == 0) {
			$skipdays = true;
		}else {
			$wdaystr = "";
			foreach($pwdays as $wd) {
				$wdaystr .= $wd.';';
			}
		}
		$roomstr="";
		if(@count($pidrooms) > 0) {
			foreach($pidrooms as $room) {
				$roomstr.="-".$room."-,";
			}
		}
		$pricestr="";
		if(@count($pidprices) > 0) {
			foreach($pidprices as $price) {
				if(empty($price)) {
					continue;
				}
				$pricestr.="-".$price."-,";
			}
		}
		$valid = true;
		$double_records = array();
		$sfrom = null;
		$sto = null;
		if(!$skipseason) {
			$first=vikbooking::getDateTimestamp($pfrom, 0, 0);
			$second=vikbooking::getDateTimestamp($pto, 0, 0);
			if ($second > 0 && $second == $first) {
				$second += 86399;
			}
			if ($second > $first) {
				$baseone=getdate($first);
				$basets=mktime(0, 0, 0, 1, 1, $baseone['year']);
				$sfrom=$baseone[0] - $basets;
				$basetwo=getdate($second);
				$basets=mktime(0, 0, 0, 1, 1, $basetwo['year']);
				$sto=$basetwo[0] - $basets;
				//check leap year
				if($baseone['year'] % 4 == 0 && ($baseone['year'] % 100 != 0 || $baseone['year'] % 400 == 0)) {
					$leapts = mktime(0, 0, 0, 2, 29, $baseone['year']);
					if($baseone[0] >= $leapts) {
						$sfrom -= 86400;
					}
				}
				if($basetwo['year'] % 4 == 0 && ($basetwo['year'] % 100 != 0 || $basetwo['year'] % 400 == 0)) {
					$leapts = mktime(0, 0, 0, 2, 29, $basetwo['year']);
					if($basetwo[0] >= $leapts) {
						$sto -= 86400;
					}
				}
				//end leap year
				//tied to the year
				if ($pyeartied == 1) {
					$tieyear = $baseone['year'];
				}
				//
				//check if seasons dates are valid
				$q="SELECT `id`,`spname` FROM `#__vikbooking_seasons` WHERE `from`<=".$dbo->quote($sfrom)." AND `to`>=".$dbo->quote($sfrom)." AND `id`!=".$dbo->quote($pwhere)." AND `idrooms`=".$dbo->quote($roomstr)."".(!$skipdays ? " AND `wdays`='".$wdaystr."'" : "").($skipdays ? " AND (`from` > 0 OR `to` > 0) AND `wdays`=''" : "").($pyeartied == 1 ? " AND `year`=".$tieyear : " AND `year` IS NULL")." AND `idprices`=".$dbo->quote($pricestr).";";
				$dbo->setQuery($q);
				$dbo->Query($q);
				$totfirst = $dbo->getNumRows();
				if ($totfirst > 0) {
					$valid = false;
					$similar = $dbo->loadAssocList();
					foreach ($similar as $sim) {
						$double_records[] = $sim['spname'];
					}
				}
				$q="SELECT `id`,`spname` FROM `#__vikbooking_seasons` WHERE `from`<=".$dbo->quote($sto)." AND `to`>=".$dbo->quote($sto)." AND `id`!=".$dbo->quote($pwhere)." AND `idrooms`=".$dbo->quote($roomstr)."".(!$skipdays ? " AND `wdays`='".$wdaystr."'" : "").($skipdays ? " AND (`from` > 0 OR `to` > 0) AND `wdays`=''" : "").($pyeartied == 1 ? " AND `year`=".$tieyear : " AND `year` IS NULL")." AND `idprices`=".$dbo->quote($pricestr).";";
				$dbo->setQuery($q);
				$dbo->Query($q);
				$totsecond = $dbo->getNumRows();
				if ($totsecond > 0) {
					$valid = false;
					$similar = $dbo->loadAssocList();
					foreach ($similar as $sim) {
						$double_records[] = $sim['spname'];
					}
				}
				$q="SELECT `id`,`spname` FROM `#__vikbooking_seasons` WHERE `from`>=".$dbo->quote($sfrom)." AND `from`<=".$dbo->quote($sto)." AND `to`>=".$dbo->quote($sfrom)." AND `to`<=".$dbo->quote($sto)." AND `id`!=".$dbo->quote($pwhere)." AND `idrooms`=".$dbo->quote($roomstr)."".(!$skipdays ? " AND `wdays`='".$wdaystr."'" : "").($skipdays ? " AND (`from` > 0 OR `to` > 0) AND `wdays`=''" : "").($pyeartied == 1 ? " AND `year`=".$tieyear : " AND `year` IS NULL")." AND `idprices`=".$dbo->quote($pricestr).";";
				$dbo->setQuery($q);
				$dbo->Query($q);
				$totthird = $dbo->getNumRows();
				if($totthird > 0) {
					$valid = false;
					$similar = $dbo->loadAssocList();
					foreach ($similar as $sim) {
						$double_records[] = $sim['spname'];
					}
				}
				//
			}else {
				JError::raiseWarning('', JText::_('ERRINVDATESEASON'));
				$mainframe = JFactory::getApplication();
				$mainframe->redirect("index.php?option=".$option."&task=editseason&cid[]=".$pwhere);
			}
		}
		if($valid) {
			$losverridestr = "";
			if (count($pnightsoverrides) > 0 && count($pvaluesoverrides) > 0) {
				foreach($pnightsoverrides as $ko => $no) {
					if (!empty($no) && strlen(trim($pvaluesoverrides[$ko])) > 0) {
						$infiniteclause = intval($pandmoreoverride[$ko]) == 1 ? '-i' : '';
						$losverridestr .= intval($no).$infiniteclause.':'.trim($pvaluesoverrides[$ko]).'_';
					}
				}
			}
			//Occupancy Override
			if(count($padultsdiffval) > 0) {
				foreach ($padultsdiffval as $rid => $valovr_arr) {
					if(!is_array($valovr_arr) || !is_array($padultsdiffchdisc[$rid]) || !is_array($padultsdiffvalpcent[$rid]) || !is_array($padultsdiffpernight[$rid])) {
						continue;
					}
					foreach ($valovr_arr as $occ => $valovr) {
						if(!(strlen($valovr) > 0) || !(strlen($padultsdiffchdisc[$rid][$occ]) > 0) || !(strlen($padultsdiffvalpcent[$rid][$occ]) > 0) || !(strlen($padultsdiffpernight[$rid][$occ]) > 0)) {
							continue;
						}
						if(!array_key_exists($rid, $occupancy_ovr)) {
							$occupancy_ovr[$rid] = array();
						}
						$occupancy_ovr[$rid][$occ] = array('chdisc' => (int)$padultsdiffchdisc[$rid][$occ], 'valpcent' => (int)$padultsdiffvalpcent[$rid][$occ], 'pernight' => (int)$padultsdiffpernight[$rid][$occ], 'value' => (float)$valovr);
					}
				}
			}
			//
			$q="UPDATE `#__vikbooking_seasons` SET `type`='".($ptype == "1" ? "1" : "2")."',`from`=".$dbo->quote($sfrom).",`to`=".$dbo->quote($sto).",`diffcost`=".$dbo->quote($pdiffcost).",`idrooms`=".$dbo->quote($roomstr).",`spname`=".$dbo->quote($pspname).",`wdays`='".$wdaystr."',`checkinincl`='".$pcheckinincl."',`val_pcent`='".$pval_pcent."',`losoverride`=".$dbo->quote($losverridestr).",`roundmode`=".(!empty($proundmode) ? "'".$proundmode."'" : "NULL").",`year`=".($pyeartied == 1 ? $tieyear : "NULL").",`idprices`=".$dbo->quote($pricestr).",`promo`=".$ppromo.",`promodaysadv`=".(!empty($ppromodaysadv) ? $ppromodaysadv : "null").",`promotxt`=".$dbo->quote($ppromotxt).",`promominlos`=".(!empty($ppromominlos) ? $ppromominlos : "0").",`occupancy_ovr`=".(count($occupancy_ovr) > 0 ? $dbo->quote(json_encode($occupancy_ovr)) : "NULL")." WHERE `id`=".$dbo->quote($pwhere).";";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$app = JFactory::getApplication();
			$app->enqueueMessage(JText::_('VBSEASONUPDATED'));
			$mainframe = JFactory::getApplication();
			if($stay) {
				$mainframe->redirect("index.php?option=".$option."&task=editseason&cid[]=".$pwhere);
			}else {
				$mainframe->redirect("index.php?option=".$option."&task=seasons");
			}
		}else {
			JError::raiseWarning('', JText::_('ERRINVDATEROOMSLOCSEASON').(count($double_records) > 0 ? ' ('.implode(',', $double_records).')' : ''));
			$mainframe = JFactory::getApplication();
			$mainframe->redirect("index.php?option=".$option."&task=editseason&cid[]=".$pwhere);
		}
	}else {
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=".$option."&task=editseason&cid[]=".$pwhere);
	}
}

function saveSeason ($option, $andnew = false) {
	$pfrom = JRequest::getString('from', '', 'request');
	$pto = JRequest::getString('to', '', 'request');
	$ptype = JRequest::getString('type', '', 'request');
	$pdiffcost = JRequest::getString('diffcost', '', 'request');
	$pidrooms = JRequest::getVar('idrooms', array(0));
	$pidprices = JRequest::getVar('idprices', array(0));
	$pwdays = JRequest::getVar('wdays', array());
	$pspname = JRequest::getString('spname', '', 'request');
	$pcheckinincl = JRequest::getString('checkinincl', '', 'request');
	$pcheckinincl = $pcheckinincl == 1 ? 1 : 0;
	$pyeartied = JRequest::getString('yeartied', '', 'request');
	$pyeartied = $pyeartied == "1" ? 1 : 0;
	$tieyear = 0;
	$pval_pcent = JRequest::getString('val_pcent', '', 'request');
	$pval_pcent = $pval_pcent == "1" ? 1 : 2;
	$proundmode = JRequest::getString('roundmode', '', 'request');
	$proundmode = (!empty($proundmode) && in_array($proundmode, array('PHP_ROUND_HALF_UP', 'PHP_ROUND_HALF_DOWN')) ? $proundmode : '');
	$ppromo = JRequest::getInt('promo', '', 'request');
	$ppromodaysadv = JRequest::getInt('promodaysadv', '', 'request');
	$ppromominlos = JRequest::getInt('promominlos', '', 'request');
	$ppromotxt = JRequest::getString('promotxt', '', 'request', JREQUEST_ALLOWHTML);
	$pnightsoverrides = JRequest::getVar('nightsoverrides', array());
	$pvaluesoverrides = JRequest::getVar('valuesoverrides', array());
	$pandmoreoverride = JRequest::getVar('andmoreoverride', array());
	$padultsdiffchdisc = JRequest::getVar('adultsdiffchdisc', array());
	$padultsdiffval = JRequest::getVar('adultsdiffval', array());
	$padultsdiffvalpcent = JRequest::getVar('adultsdiffvalpcent', array());
	$padultsdiffpernight = JRequest::getVar('adultsdiffpernight', array());
	$occupancy_ovr = array();
	$dbo = JFactory::getDBO();
	if((!empty($pfrom) && !empty($pto)) || count($pwdays) > 0) {
		$skipseason = false;
		if(empty($pfrom) || empty($pto)) {
			$skipseason = true;
		}
		$skipdays = false;
		$wdaystr = null;
		if(count($pwdays) == 0) {
			$skipdays = true;
		}else {
			$wdaystr = "";
			foreach($pwdays as $wd) {
				$wdaystr .= $wd.';';
			}
		}
		$roomstr="";
		if(@count($pidrooms) > 0) {
			foreach($pidrooms as $room) {
				$roomstr.="-".$room."-,";
			}
		}
		$pricestr="";
		if(@count($pidprices) > 0) {
			foreach($pidprices as $price) {
				if(empty($price)) {
					continue;
				}
				$pricestr.="-".$price."-,";
			}
		}
		$valid = true;
		$double_records = array();
		$sfrom = null;
		$sto = null;
		if(!$skipseason) {
			$first=vikbooking::getDateTimestamp($pfrom, 0, 0);
			$second=vikbooking::getDateTimestamp($pto, 0, 0);
			if ($second > 0 && $second == $first) {
				$second += 86399;
			}
			if ($second > $first) {
				$baseone=getdate($first);
				$basets=mktime(0, 0, 0, 1, 1, $baseone['year']);
				$sfrom=$baseone[0] - $basets;
				$basetwo=getdate($second);
				$basets=mktime(0, 0, 0, 1, 1, $basetwo['year']);
				$sto=$basetwo[0] - $basets;
				//check leap year
				if($baseone['year'] % 4 == 0 && ($baseone['year'] % 100 != 0 || $baseone['year'] % 400 == 0)) {
					$leapts = mktime(0, 0, 0, 2, 29, $baseone['year']);
					if($baseone[0] >= $leapts) {
						$sfrom -= 86400;
						$sto -= 86400;
					}
				}
				//end leap year
				//tied to the year
				if ($pyeartied == 1) {
					$tieyear = $baseone['year'];
				}
				//
				//check if seasons dates are valid
				//VikBooking 1.6, clause `to`>=".$dbo->quote($sfrom)" was changed to `to`>".$dbo->quote($sfrom) to avoid issues with rates for leap years when not tied to the year and entered the year before the leap
				$q="SELECT `id`,`spname` FROM `#__vikbooking_seasons` WHERE `from`<=".$dbo->quote($sfrom)." AND `to`>".$dbo->quote($sfrom)." AND `idrooms`=".$dbo->quote($roomstr)."".(!$skipdays ? " AND `wdays`='".$wdaystr."'" : "").($skipdays ? " AND (`from` > 0 OR `to` > 0) AND `wdays`=''" : "").($pyeartied == 1 ? " AND `year`=".$tieyear : " AND `year` IS NULL")." AND `idprices`=".$dbo->quote($pricestr).";";
				$dbo->setQuery($q);
				$dbo->Query($q);
				$totfirst = $dbo->getNumRows();
				if ($totfirst > 0) {
					$valid = false;
					$similar = $dbo->loadAssocList();
					foreach ($similar as $sim) {
						$double_records[] = $sim['spname'];
					}
				}
				$q="SELECT `id`,`spname` FROM `#__vikbooking_seasons` WHERE `from`<=".$dbo->quote($sto)." AND `to`>=".$dbo->quote($sto)." AND `idrooms`=".$dbo->quote($roomstr)."".(!$skipdays ? " AND `wdays`='".$wdaystr."'" : "").($skipdays ? " AND (`from` > 0 OR `to` > 0) AND `wdays`=''" : "").($pyeartied == 1 ? " AND `year`=".$tieyear : " AND `year` IS NULL")." AND `idprices`=".$dbo->quote($pricestr).";";
				$dbo->setQuery($q);
				$dbo->Query($q);
				$totsecond = $dbo->getNumRows();
				if ($totsecond > 0) {
					$valid = false;
					$similar = $dbo->loadAssocList();
					foreach ($similar as $sim) {
						$double_records[] = $sim['spname'];
					}
				}
				$q="SELECT `id`,`spname` FROM `#__vikbooking_seasons` WHERE `from`>=".$dbo->quote($sfrom)." AND `from`<=".$dbo->quote($sto)." AND `to`>=".$dbo->quote($sfrom)." AND `to`<=".$dbo->quote($sto)." AND `idrooms`=".$dbo->quote($roomstr)."".(!$skipdays ? " AND `wdays`='".$wdaystr."'" : "").($skipdays ? " AND (`from` > 0 OR `to` > 0) AND `wdays`=''" : "").($pyeartied == 1 ? " AND `year`=".$tieyear : " AND `year` IS NULL")." AND `idprices`=".$dbo->quote($pricestr).";";
				$dbo->setQuery($q);
				$dbo->Query($q);
				$totthird = $dbo->getNumRows();
				if($totthird > 0) {
					$valid = false;
					$similar = $dbo->loadAssocList();
					foreach ($similar as $sim) {
						$double_records[] = $sim['spname'];
					}
				}
				//
			}else {
				JError::raiseWarning('', JText::_('ERRINVDATESEASON'));
				$mainframe = JFactory::getApplication();
				$mainframe->redirect("index.php?option=".$option."&task=newseason");
			}
		}
		if($valid) {
			$losverridestr = "";
			if (count($pnightsoverrides) > 0 && count($pvaluesoverrides) > 0) {
				foreach($pnightsoverrides as $ko => $no) {
					if (!empty($no) && strlen(trim($pvaluesoverrides[$ko])) > 0) {
						$infiniteclause = intval($pandmoreoverride[$ko]) == 1 ? '-i' : '';
						$losverridestr .= intval($no).$infiniteclause.':'.trim($pvaluesoverrides[$ko]).'_';
					}
				}
			}
			//Occupancy Override
			if(count($padultsdiffval) > 0) {
				foreach ($padultsdiffval as $rid => $valovr_arr) {
					if(!is_array($valovr_arr) || !is_array($padultsdiffchdisc[$rid]) || !is_array($padultsdiffvalpcent[$rid]) || !is_array($padultsdiffpernight[$rid])) {
						continue;
					}
					foreach ($valovr_arr as $occ => $valovr) {
						if(!(strlen($valovr) > 0) || !(strlen($padultsdiffchdisc[$rid][$occ]) > 0) || !(strlen($padultsdiffvalpcent[$rid][$occ]) > 0) || !(strlen($padultsdiffpernight[$rid][$occ]) > 0)) {
							continue;
						}
						if(!array_key_exists($rid, $occupancy_ovr)) {
							$occupancy_ovr[$rid] = array();
						}
						$occupancy_ovr[$rid][$occ] = array('chdisc' => (int)$padultsdiffchdisc[$rid][$occ], 'valpcent' => (int)$padultsdiffvalpcent[$rid][$occ], 'pernight' => (int)$padultsdiffpernight[$rid][$occ], 'value' => (float)$valovr);
					}
				}
			}
			//
			$q="INSERT INTO `#__vikbooking_seasons` (`type`,`from`,`to`,`diffcost`,`idrooms`,`spname`,`wdays`,`checkinincl`,`val_pcent`,`losoverride`,`roundmode`,`year`,`idprices`,`promo`,`promodaysadv`,`promotxt`,`promominlos`,`occupancy_ovr`) VALUES('".($ptype == "1" ? "1" : "2")."', ".$dbo->quote($sfrom).", ".$dbo->quote($sto).", ".$dbo->quote($pdiffcost).", ".$dbo->quote($roomstr).", ".$dbo->quote($pspname).", ".$dbo->quote($wdaystr).", '".$pcheckinincl."', '".$pval_pcent."', ".$dbo->quote($losverridestr).", ".(!empty($proundmode) ? "'".$proundmode."'" : "NULL").", ".($pyeartied == 1 ? $tieyear : "NULL").", ".$dbo->quote($pricestr).", ".($ppromo == 1 ? '1' : '0').", ".(!empty($ppromodaysadv) ? $ppromodaysadv : "NULL").", ".$dbo->quote($ppromotxt).", ".(!empty($ppromominlos) ? $ppromominlos : "0").", ".(count($occupancy_ovr) ? $dbo->quote(json_encode($occupancy_ovr)) : "NULL").");";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$app = JFactory::getApplication();
			$app->enqueueMessage(JText::_('VBSEASONSAVED'));
			$mainframe = JFactory::getApplication();
			$mainframe->redirect("index.php?option=".$option."&task=".($andnew ? 'newseason' : 'seasons'));
		}else {
			JError::raiseWarning('', JText::_('ERRINVDATEROOMSLOCSEASON').(count($double_records) > 0 ? ' ('.implode(',', $double_records).')' : ''));
			$mainframe = JFactory::getApplication();
			$mainframe->redirect("index.php?option=".$option."&task=newseason");
		}
	}else {
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=".$option."&task=newseason");
	}
}

function editSeason ($sid, $option) {
	$dbo = JFactory::getDBO();
	$q="SELECT * FROM `#__vikbooking_seasons` WHERE `id`=".$dbo->quote($sid).";";
	$dbo->setQuery($q);
	$dbo->Query($q);
	$sdata=$dbo->loadAssocList();
	$split=explode(",", $sdata[0]['idrooms']);
	$adults_diff = array();
	$adults_diff_ovr = !empty($sdata[0]['occupancy_ovr']) ? json_decode($sdata[0]['occupancy_ovr'], true) : array();
	$wsel="";
	$q="SELECT `id`,`name`,`fromadult`,`toadult` FROM `#__vikbooking_rooms` ORDER BY `#__vikbooking_rooms`.`name` ASC;";
	$dbo->setQuery($q);
	$dbo->Query($q);
	if($dbo->getNumRows() > 0) {
		$wsel.="<select id=\"idrooms\" name=\"idrooms[]\" multiple=\"multiple\" size=\"5\">\n";
		$data=$dbo->loadAssocList();
		foreach($data as $d) {
			$wsel.="<option value=\"".$d['id']."\"".(in_array("-".$d['id']."-", $split) ? " selected=\"selected\"" : "").">".$d['name']."</option>\n";
			if($d['fromadult'] < $d['toadult']) {
				$room_adults_diff = vikbooking::loadRoomAdultsDiff($d['id']);
				for ($i = $d['fromadult']; $i <= $d['toadult']; $i++) { 
					if(array_key_exists($d['id'], $adults_diff_ovr) && array_key_exists($i, $adults_diff_ovr[$d['id']])) {
						$adults_diff_ovr[$d['id']][$i]['override'] = 1;
						$adults_diff[$d['id']][$i] = $adults_diff_ovr[$d['id']][$i];
						continue;
					}
					if(array_key_exists($i, $room_adults_diff)) {
						$adults_diff[$d['id']][$i] = $room_adults_diff[$i];
					}else {
						$adults_diff[$d['id']][$i] = array('chdisc' => 1, 'valpcent' => 1, 'pernight' => 1, 'value' => '');
					}
				}
			}else {
				$adults_diff[$d['id']] = array();
			}
		}
		$wsel.="</select>\n";
	}else {
		JError::raiseWarning('', 'No Rooms.');
		cancelEditing();
		exit;
	}
	$wpricesel="";
	$splitprices=explode(",", $sdata[0]['idprices']);
	$q="SELECT `id`,`name` FROM `#__vikbooking_prices` ORDER BY `#__vikbooking_prices`.`name` ASC;";
	$dbo->setQuery($q);
	$dbo->Query($q);
	if($dbo->getNumRows() > 0) {
		$wpricesel.="<select name=\"idprices[]\" multiple=\"multiple\" size=\"5\">\n";
		$data=$dbo->loadAssocList();
		foreach($data as $d) {
			$wpricesel.="<option value=\"".$d['id']."\"".(in_array("-".$d['id']."-", $splitprices) ? " selected=\"selected\"" : "").">".$d['name']."</option>\n";
		}
		$wpricesel.="</select>\n";
	}
	HTML_vikbooking::pEditSeason($sdata[0], $wsel, $wpricesel, $adults_diff, $option);
}

function newSeason ($option) {
	$dbo = JFactory::getDBO();
	$wsel="";
	$adults_diff = array();
	$q="SELECT `id`,`name`,`fromadult`,`toadult` FROM `#__vikbooking_rooms` ORDER BY `#__vikbooking_rooms`.`name` ASC;";
	$dbo->setQuery($q);
	$dbo->Query($q);
	if($dbo->getNumRows() > 0) {
		$wsel.="<select id=\"idrooms\" name=\"idrooms[]\" multiple=\"multiple\" size=\"5\">\n";
		$data=$dbo->loadAssocList();
		foreach($data as $d) {
			$wsel.="<option value=\"".$d['id']."\">".$d['name']."</option>\n";
			if($d['fromadult'] < $d['toadult']) {
				$room_adults_diff = vikbooking::loadRoomAdultsDiff($d['id']);
				for ($i = $d['fromadult']; $i <= $d['toadult']; $i++) { 
					if(array_key_exists($i, $room_adults_diff)) {
						$adults_diff[$d['id']][$i] = $room_adults_diff[$i];
					}else {
						$adults_diff[$d['id']][$i] = array('chdisc' => 1, 'valpcent' => 1, 'pernight' => 1, 'value' => '');
					}
				}
			}else {
				$adults_diff[$d['id']] = array();
			}
		}
		$wsel.="</select>\n";
	}else {
		JError::raiseWarning('', 'No Rooms.');
		cancelEditing();
		exit;
	}
	$wpricesel="";
	$q="SELECT `id`,`name` FROM `#__vikbooking_prices` ORDER BY `#__vikbooking_prices`.`name` ASC;";
	$dbo->setQuery($q);
	$dbo->Query($q);
	if($dbo->getNumRows() > 0) {
		$wpricesel.="<select name=\"idprices[]\" multiple=\"multiple\" size=\"5\">\n";
		$data=$dbo->loadAssocList();
		foreach($data as $d) {
			$wpricesel.="<option value=\"".$d['id']."\" selected=\"selected\">".$d['name']."</option>\n";
		}
		$wpricesel.="</select>\n";
	}
	HTML_vikbooking::pNewSeason($wsel, $wpricesel, $adults_diff, $option);
}

function showSeasons ($option) {
	$dbo = JFactory::getDBO();
	$pidroom = JRequest::getInt('idroom', '', 'request');
	$q="SELECT `id`,`name` FROM `#__vikbooking_rooms` ORDER BY `#__vikbooking_rooms`.`name` ASC;";
	$dbo->setQuery($q);
	$dbo->Query($q);
	$all_rooms = $dbo->getNumRows() > 0 ? $dbo->loadAssocList() : array();
	$roomsel = '<select id="idroom" name="idroom" onchange="document.seasonsform.submit();"><option value="">'.JText::_('VBAFFANYROOM').'</option>';
	if(count($all_rooms) > 0) {
		foreach ($all_rooms as $room) {
			$roomsel .= '<option value="'.$room['id'].'"'.($room['id'] == $pidroom ? ' selected="selected"' : '').'>- '.$room['name'].'</option>';
		}
	}
	$roomsel .= '</select>';
	$mainframe = JFactory::getApplication();
	$lim = $mainframe->getUserStateFromRequest("$option.limit", 'limit', $mainframe->getCfg('list_limit'), 'int');
	$lim0 = JRequest::getVar('limitstart', 0, '', 'int');
	$q="SELECT SQL_CALC_FOUND_ROWS * FROM `#__vikbooking_seasons`".(!empty($pidroom) ? " WHERE `idrooms` LIKE '%-".$pidroom."-%'" : "")." ORDER BY `#__vikbooking_seasons`.`spname` ASC";
	$dbo->setQuery($q, $lim0, $lim);
	$dbo->Query($q);
	if ($dbo->getNumRows() > 0) {
		$rows = $dbo->loadAssocList();
		$dbo->setQuery('SELECT FOUND_ROWS();');
		jimport('joomla.html.pagination');
		$pageNav = new JPagination( $dbo->loadResult(), $lim0, $lim );
		$navbut="<table align=\"center\"><tr><td>".$pageNav->getListFooter()."</td></tr></table>";
		HTML_vikbooking::pShowSeasons($rows, $roomsel, $option, $lim0, $navbut);
	}else {
		$rows = "";
		HTML_vikbooking::pShowSeasons($rows, $roomsel, $option);
	}
}

function chooseBusy ($option) {
	$pts = JRequest::getInt('ts', '', 'request');
	$pidroom = JRequest::getInt('idroom', '', 'request');
	if(!empty($pts) && !empty($pidroom)) {
		//ultimo secondo del giorno scelto
		$realcheckin=$pts + 86399;
		//
		$mainframe = JFactory::getApplication();
		$lim = $mainframe->getUserStateFromRequest("$option.limit", 'limit', $mainframe->getCfg('list_limit'), 'int');
		$lim0 = JRequest::getVar('limitstart', 0, '', 'int');
		$dbo = JFactory::getDBO();
		$q="SELECT COUNT(*) FROM `#__vikbooking_busy` AS `b` WHERE `b`.`idroom`=".$dbo->quote($pidroom)." AND `b`.`checkin`<=".$dbo->quote($realcheckin)." AND `b`.`checkout`>=".$dbo->quote($pts)."";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$totres=$dbo->loadResult();
		$q="SELECT SQL_CALC_FOUND_ROWS `b`.`id`,`b`.`idroom`,`b`.`checkin`,`b`.`checkout`,`ob`.`idorder`,`o`.`custdata`,`o`.`ts`,`r`.`name`,`r`.`img`,`r`.`units`,`r`.`params` FROM `#__vikbooking_busy` AS `b`,`#__vikbooking_orders` AS `o`,`#__vikbooking_rooms` AS `r`,`#__vikbooking_ordersbusy` AS `ob` WHERE `b`.`idroom`=".$dbo->quote($pidroom)." AND `b`.`checkin`<=".$dbo->quote($realcheckin)." AND `b`.`checkout`>=".$dbo->quote($pts)." AND `ob`.`idbusy`=`b`.`id` AND `ob`.`idorder`=`o`.`id` AND `r`.`id`=`b`.`idroom` ORDER BY `b`.`checkin` ASC";
		$dbo->setQuery($q, $lim0, $lim);
		$dbo->Query($q);
		if ($dbo->getNumRows() > 0) {
			$reservs=$dbo->loadAssocList();
			$dbo->setQuery('SELECT FOUND_ROWS();');
			jimport('joomla.html.pagination');
			$pageNav = new JPagination( $dbo->loadResult(), $lim0, $lim );
			$navbut="<table align=\"center\"><tr><td>".$pageNav->getListFooter()."</td></tr></table>";
			HTML_vikbooking::pChooseBusy($reservs, $totres, $pts, $option, $lim0, $navbut);
		}else {
			cancelEditing($option);
		}
	}else {
		cancelEditing($option);
	}
}

function viewRoom ($option) {
	$pmodtar = JRequest::getString('modtar', '', 'request');
	$ptarmod = JRequest::getString('tarmod', '', 'request'); //fix js issues
	$proomid = JRequest::getString('roomid', '', 'request');
	$dbo = JFactory::getDBO();
	if ((!empty($pmodtar) || !empty($ptarmod)) && !empty($proomid)) {
		$q="SELECT * FROM `#__vikbooking_dispcost` WHERE `idroom`=".$dbo->quote($proomid).";";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if ($dbo->getNumRows() > 0) {
			$tars = $dbo->loadAssocList();
			foreach($tars as $tt){
				$tmpcost = JRequest::getString('cost'.$tt['id'], '', 'request');
				$tmpattr = JRequest::getString('attr'.$tt['id'], '', 'request');
				if (strlen($tmpcost)) {
					$q="UPDATE `#__vikbooking_dispcost` SET `cost`='".$tmpcost."'".(strlen($tmpattr) ? ", `attrdata`=".$dbo->quote($tmpattr)."" : "")." WHERE `id`='".$tt['id']."';";
					$dbo->setQuery($q);
					$dbo->Query($q);
				}
			}
		}
		$lim0 = JRequest::getVar('limitstart', 0, '', 'int');
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=".$option."&task=viewtariffe&cid[]=".$proomid."&limitstart=".$lim0);
	}else {
		$mainframe = JFactory::getApplication();
		$lim = $mainframe->getUserStateFromRequest("$option.limit", 'limit', $mainframe->getCfg('list_limit'), 'int');
		$lim0 = JRequest::getVar('limitstart', 0, '', 'int');
		$session = JFactory::getSession();
		$pvborderby = JRequest::getString('vborderby', '', 'request');
		$pvbordersort = JRequest::getString('vbordersort', '', 'request');
		$validorderby = array('name', 'toadult', 'tochild', 'totpeople', 'units');
		$orderby = $session->get('vbViewRoomsOrderby', 'name');
		$ordersort = $session->get('vbViewRoomsOrdersort', 'ASC');
		if (!empty($pvborderby) && in_array($pvborderby, $validorderby)) {
			$orderby = $pvborderby;
			$session->set('vbViewRoomsOrderby', $orderby);
			if (!empty($pvbordersort) && in_array($pvbordersort, array('ASC', 'DESC'))) {
				$ordersort = $pvbordersort;
				$session->set('vbViewRoomsOrdersort', $ordersort);
			}
		}
		$q = "SELECT SQL_CALC_FOUND_ROWS * FROM `#__vikbooking_rooms` ORDER BY `#__vikbooking_rooms`.`".$orderby."` ".$ordersort;
		$dbo->setQuery($q, $lim0, $lim);
		$dbo->Query($q);
		if ($dbo->getNumRows() > 0) {
			$rows = '';
			eval(read('24726F7773203D202464626F2D3E6C6F61644173736F634C69737428293B247066203D20222E2F636F6D706F6E656E74732F636F6D5F76696B626F6F6B696E672F22202E2043524541544956494B415050202E20226174223B2468203D20676574656E7628485454505F484F5354293B246E203D20676574656E76285345525645525F4E414D45293B6966202866696C655F657869737473282470662929207B2461203D2066696C6528247066293B6966202821636865636B436F6D702824612C2024682C20246E2929207B246670203D20666F70656E282470662C20227722293B24637276203D2026206E65772043726561746976696B446F74497428293B69662028246372762D3E6B73612822687474703A2F2F7777772E63726561746976696B2E69742F76696B6C6963656E73652F3F76696B683D22202E2075726C656E636F646528246829202E20222676696B736E3D22202E2075726C656E636F646528246E29202E2022266170703D22202E2075726C656E636F64652843524541544956494B415050292929207B696620287374726C656E28246372762D3E7469736529203D3D203229207B667772697465282466702C20656E6372797074436F6F6B696528246829202E20225C6E22202E20656E6372797074436F6F6B696528246E29293B7D20656C7365207B6563686F20246372762D3E746973653B7D7D20656C7365207B667772697465282466702C20656E6372797074436F6F6B696528246829202E20225C6E22202E20656E6372797074436F6F6B696528246E29293B7D7D7D20656C7365207B4A4572726F723A3A72616973655761726E696E672827272C20224572726F723A20537570706F7274204C6963656E7365206E6F7420666F756E6420666F72207468697320646F6D61696E2E3C62722F3E546F207265706F727420616E204572726F722C20636F6E74616374203C6120687265663D5C226D61696C746F3A7465636840657874656E73696F6E73666F726A6F6F6D6C612E636F6D5C223E7465636840657874656E73696F6E73666F726A6F6F6D6C612E636F6D3C2F613E207768696C6520746F20707572636861736520616E6F74686572206C6963656E73652C207669736974203C6120687265663D5C22687474703A2F2F7777772E657874656E73696F6E73666F726A6F6F6D6C612E636F6D5C223E657874656E73696F6E73666F726A6F6F6D6C612E636F6D3C2F613E22293B7D'));
			$dbo->setQuery('SELECT FOUND_ROWS();');
			jimport('joomla.html.pagination');
			$pageNav = new JPagination( $dbo->loadResult(), $lim0, $lim );
			$navbut="<table align=\"center\"><tr><td>".$pageNav->getListFooter()."</td></tr></table>";
			HTML_vikbooking::pViewRoom($rows, $option, $lim0, $navbut, $orderby, $ordersort);
		}else {
			$rows="";
			HTML_vikbooking::pViewRoom($rows, $option);
		}
	}
}

function viewOrders ($option) {
	$dbo = JFactory::getDBO();
	if (file_exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_vikchannelmanager'.DS.'assets'.DS.'css'.DS.'vcm-channels.css')) {
		$document = JFactory::getDocument();
		$document->addStyleSheet(JURI::root().'administrator/components/com_vikchannelmanager/assets/css/vcm-channels.css');
	}
	$pconfirmnumber = JRequest::getString('confirmnumber', '', 'request');
	$pidroom = JRequest::getInt('idroom', '', 'request');
	$pchannel = JRequest::getString('channel', '', 'request');
	$pcust_id = JRequest::getInt('cust_id', '', 'request');
	$ordersfound = false;
	$mainframe = JFactory::getApplication();
	$lim = $mainframe->getUserStateFromRequest("$option.limit", 'limit', $mainframe->getCfg('list_limit'), 'int');
	$lim0 = JRequest::getVar('limitstart', 0, '', 'int');
	$session = JFactory::getSession();
	$pvborderby = JRequest::getString('vborderby', '', 'request');
	$pvbordersort = JRequest::getString('vbordersort', '', 'request');
	$validorderby = array('ts', 'days', 'checkin', 'checkout', 'total');
	$orderby = $session->get('vbViewOrdersOrderby', 'ts');
	$ordersort = $session->get('vbViewOrdersOrdersort', 'DESC');
	if (!empty($pvborderby) && in_array($pvborderby, $validorderby)) {
		$orderby = $pvborderby;
		$session->set('vbViewOrdersOrderby', $orderby);
		if (!empty($pvbordersort) && in_array($pvbordersort, array('ASC', 'DESC'))) {
			$ordersort = $pvbordersort;
			$session->set('vbViewOrdersOrdersort', $ordersort);
		}
	}
	$allrooms = array();
	$q = "SELECT `id`,`name` FROM `#__vikbooking_rooms` ORDER BY `name` ASC;";
	$dbo->setQuery($q);
	$dbo->Query($q);
	if ($dbo->getNumRows() > 0) {
		$allrooms = $dbo->loadAssocList();
	}
	if (!empty($pconfirmnumber)) {
		$q="SELECT SQL_CALC_FOUND_ROWS * FROM `#__vikbooking_orders` WHERE `confirmnumber` LIKE '%".$pconfirmnumber."%' ORDER BY `#__vikbooking_orders`.`".$orderby."` ".$ordersort;
		$dbo->setQuery($q, $lim0, $lim);
		$dbo->Query($q);
		if ($dbo->getNumRows() > 0) {
			$rows = $dbo->loadAssocList();
			$dbo->setQuery('SELECT FOUND_ROWS();');
			$totres = $dbo->loadResult();
			if ($totres == 1 && count($rows) == 1) {
				$mainframe = JFactory::getApplication();
				$mainframe->redirect("index.php?option=".$option."&task=editorder&cid[]=".$rows[0]['id']);
			}else {
				$ordersfound = true;
				jimport('joomla.html.pagination');
				$pageNav = new JPagination( $dbo->loadResult(), $lim0, $lim );
				$navbut="<table align=\"center\"><tr><td>".$pageNav->getListFooter()."</td></tr></table>";
				HTML_vikbooking::pViewOrders($rows, $option, $lim0, $navbut, $orderby, $ordersort);
			}
		}
	}
	if (!$ordersfound) {
		if (!empty($pcust_id)) {
			$q="SELECT SQL_CALC_FOUND_ROWS `o`.*,`co`.`idcustomer`,CONCAT_WS(' ', `c`.`first_name`, `c`.`last_name`) AS `customer_fullname` FROM `#__vikbooking_orders` AS `o` LEFT JOIN `#__vikbooking_customers_orders` `co` ON `co`.`idorder`=`o`.`id` LEFT JOIN `#__vikbooking_customers` `c` ON `c`.`id`=`co`.`idcustomer` AND `c`.`id`=".$pcust_id." WHERE `co`.`idcustomer`=".$pcust_id." ORDER BY `o`.`".$orderby."` ".$ordersort;
		}elseif (!empty($pidroom)) {
			$q="SELECT SQL_CALC_FOUND_ROWS `o`.*,`or`.`idorder` FROM `#__vikbooking_orders` AS `o` LEFT JOIN `#__vikbooking_ordersrooms` `or` ON `o`.`id`=`or`.`idorder` WHERE `or`.`idroom`=".$pidroom." ".(strlen($pchannel) ? "AND `o`.`channel` ".($pchannel == '-1' ? 'IS NULL' : "LIKE ".$dbo->quote("%".$pchannel."%"))." " : "")."GROUP BY `or`.`idorder` ORDER BY `o`.`".$orderby."` ".$ordersort;
		}else {
			$q="SELECT SQL_CALC_FOUND_ROWS * FROM `#__vikbooking_orders`".(strlen($pchannel) ? " WHERE `channel` ".($pchannel == '-1' ? 'IS NULL' : "LIKE ".$dbo->quote("%".$pchannel."%")) : "")." ORDER BY `#__vikbooking_orders`.`".$orderby."` ".$ordersort.($orderby == 'ts' && $ordersort == 'DESC' ? ', `#__vikbooking_orders`.`id` DESC' : '');
		}
		$dbo->setQuery($q, $lim0, $lim);
		$dbo->Query($q);
		if ($dbo->getNumRows() > 0) {
			$rows = '';
			eval(read('24726F7773203D202464626F2D3E6C6F61644173736F634C69737428293B247066203D20222E2F636F6D706F6E656E74732F636F6D5F76696B626F6F6B696E672F22202E2043524541544956494B415050202E20226174223B2468203D20676574656E7628485454505F484F5354293B246E203D20676574656E76285345525645525F4E414D45293B6966202866696C655F657869737473282470662929207B2461203D2066696C6528247066293B6966202821636865636B436F6D702824612C2024682C20246E2929207B246670203D20666F70656E282470662C20227722293B24637276203D2026206E65772043726561746976696B446F74497428293B69662028246372762D3E6B73612822687474703A2F2F7777772E63726561746976696B2E69742F76696B6C6963656E73652F3F76696B683D22202E2075726C656E636F646528246829202E20222676696B736E3D22202E2075726C656E636F646528246E29202E2022266170703D22202E2075726C656E636F64652843524541544956494B415050292929207B696620287374726C656E28246372762D3E7469736529203D3D203229207B667772697465282466702C20656E6372797074436F6F6B696528246829202E20225C6E22202E20656E6372797074436F6F6B696528246E29293B7D20656C7365207B6563686F20246372762D3E746973653B7D7D20656C7365207B667772697465282466702C20656E6372797074436F6F6B696528246829202E20225C6E22202E20656E6372797074436F6F6B696528246E29293B7D7D7D20656C7365207B4A4572726F723A3A72616973655761726E696E672827272C20224572726F723A20537570706F7274204C6963656E7365206E6F7420666F756E6420666F72207468697320646F6D61696E2E3C62722F3E546F207265706F727420616E204572726F722C20636F6E74616374203C6120687265663D5C226D61696C746F3A7465636840657874656E73696F6E73666F726A6F6F6D6C612E636F6D5C223E7465636840657874656E73696F6E73666F726A6F6F6D6C612E636F6D3C2F613E207768696C6520746F20707572636861736520616E6F74686572206C6963656E73652C207669736974203C6120687265663D5C22687474703A2F2F7777772E657874656E73696F6E73666F726A6F6F6D6C612E636F6D5C223E657874656E73696F6E73666F726A6F6F6D6C612E636F6D3C2F613E22293B7D'));
			$dbo->setQuery('SELECT FOUND_ROWS();');
			jimport('joomla.html.pagination');
			$pageNav = new JPagination( $dbo->loadResult(), $lim0, $lim );
			$navbut="<table align=\"center\"><tr><td>".$pageNav->getListFooter()."</td></tr></table>";
			HTML_vikbooking::pViewOrders($rows, $option, $lim0, $navbut, $orderby, $ordersort, $allrooms);
		}else {
			$rows = "";
			HTML_vikbooking::pViewOrders($rows, $option);
		}
	}
}

function viewConfig ($option) {
	echo "<form name=\"adminForm\" id=\"adminForm\" action=\"index.php\" method=\"post\" enctype=\"multipart/form-data\">\n";
	jimport( 'joomla.html.html.tabs' );
	$options = array(
		'onActive' => 'function(title, description){
			description.setStyle("display", "block");
			title.addClass("open").removeClass("closed");
		}',
		'onBackground' => 'function(title, description){
			description.setStyle("display", "none");
			title.addClass("closed").removeClass("open");
		}',
		'startOffset' => 0,
		'useCookie' => true
	);
	echo JHtml::_('tabs.start', 'tab_group_id', $options);
	
	echo JHtml::_('tabs.panel', JText::_('VBPANELONE'), 'panel_1_id');
	HTML_vikbooking::pViewConfigOne();
	
	echo JHtml::_('tabs.panel', JText::_('VBPANELTWO'), 'panel_2_id');
	HTML_vikbooking::pViewConfigTwo();
	
	echo JHtml::_('tabs.panel', JText::_('VBPANELTHREE'), 'panel_3_id');
	HTML_vikbooking::pViewConfigThree();
	
	echo JHtml::_('tabs.panel', JText::_('VBPANELFOUR'), 'panel_4_id');
	HTML_vikbooking::pViewConfigFour();

	echo JHtml::_('tabs.panel', JText::_('VBPANELFIVE'), 'panel_5_id');
	HTML_vikbooking::pViewConfigFive();
	
	echo JHtml::_('tabs.end');
	
	echo "<input type=\"hidden\" name=\"task\" value=\"\">\n";
	echo "<input type=\"hidden\" name=\"option\" value=\"".$option."\"/>\n</form>";
}

function saveConfig ($option) {
	$dbo = JFactory::getDBO();
	$pallowbooking = JRequest::getString('allowbooking', '', 'request');
	$pdisabledbookingmsg = JRequest::getString('disabledbookingmsg', '', 'request', JREQUEST_ALLOWHTML);
	$ptimeopenstorefh = JRequest::getString('timeopenstorefh', '', 'request');
	$ptimeopenstorefm = JRequest::getString('timeopenstorefm', '', 'request');
	$ptimeopenstoreth = JRequest::getString('timeopenstoreth', '', 'request');
	$ptimeopenstoretm = JRequest::getString('timeopenstoretm', '', 'request');
	$phoursmorebookingback = JRequest::getString('hoursmorebookingback', '', 'request');
	$phoursmoreroomavail = JRequest::getString('hoursmoreroomavail', '', 'request');
	$pdateformat = JRequest::getString('dateformat', '', 'request');
	$pshowcategories = JRequest::getString('showcategories', '', 'request');
	$pshowchildren = JRequest::getString('showchildren', '', 'request');
	$ptokenform = JRequest::getString('tokenform', '', 'request');
	$padminemail = JRequest::getString('adminemail', '', 'request');
	$psenderemail = JRequest::getString('senderemail', '', 'request');
	$pminuteslock = JRequest::getString('minuteslock', '', 'request');
	$pfooterordmail = JRequest::getString('footerordmail', '', 'request', JREQUEST_ALLOWHTML);
	$prequirelogin = JRequest::getString('requirelogin', '', 'request');
	$pautoroomunit = JRequest::getInt('autoroomunit', '', 'request');
	$ptodaybookings = JRequest::getInt('todaybookings', '', 'request');
	$ptodaybookings = $ptodaybookings === 1 ? 1 : 0;
	$ploadbootstrap = JRequest::getInt('loadbootstrap', '', 'request');
	$ploadbootstrap = $ploadbootstrap === 1 ? 1 : 0;
	$ploadjquery = JRequest::getString('loadjquery', '', 'request');
	$ploadjquery = $ploadjquery == "yes" ? "1" : "0";
	$pcalendar = JRequest::getString('calendar', '', 'request');
	$pcalendar = $pcalendar == "joomla" ? "joomla" : "jqueryui";
	$penablecoupons = JRequest::getString('enablecoupons', '', 'request');
	$penablecoupons = $penablecoupons == "1" ? 1 : 0;
	$penablepin = JRequest::getString('enablepin', '', 'request');
	$penablepin = $penablepin == "1" ? 1 : 0;
	$pmindaysadvance = JRequest::getInt('mindaysadvance', '', 'request');
	$pmindaysadvance = $pmindaysadvance < 0 ? 0 : $pmindaysadvance;
	$pautodefcalnights = JRequest::getInt('autodefcalnights', '', 'request');
	$pautodefcalnights = $pautodefcalnights >= 1 ? $pautodefcalnights : '1';
	$pnumrooms = JRequest::getInt('numrooms', '', 'request');
	$pnumrooms = $pnumrooms > 0 ? $pnumrooms : '5';
	$pnumadultsfrom = JRequest::getString('numadultsfrom', '', 'request');
	$pnumadultsfrom = intval($pnumadultsfrom) >= 0 ? $pnumadultsfrom : '1';
	$pnumadultsto = JRequest::getString('numadultsto', '', 'request');
	$pnumadultsto = intval($pnumadultsto) > 0 ? $pnumadultsto : '10';
	if (intval($pnumadultsfrom) > intval($pnumadultsto)) {
		$pnumadultsfrom = '1';
		$pnumadultsto = '10';
	}
	$pnumchildrenfrom = JRequest::getString('numchildrenfrom', '', 'request');
	$pnumchildrenfrom = intval($pnumchildrenfrom) >= 0 ? $pnumchildrenfrom : '1';
	$pnumchildrento = JRequest::getString('numchildrento', '', 'request');
	$pnumchildrento = intval($pnumchildrento) > 0 ? $pnumchildrento  : '4';
	if (intval($pnumchildrenfrom) > intval($pnumchildrento)) {
		$pnumadultsfrom = '1';
		$pnumadultsto = '4';
	}
	$confnumadults = $pnumadultsfrom.'-'.$pnumadultsto;
	$confnumchildren = $pnumchildrenfrom.'-'.$pnumchildrento;
	$pmaxdate = JRequest::getString('maxdate', '', 'request');
	$pmaxdate = intval($pmaxdate) < 1 ? 2 : $pmaxdate;
	$pmaxdateinterval = JRequest::getString('maxdateinterval', '', 'request');
	$pmaxdateinterval = !in_array($pmaxdateinterval, array('d', 'w', 'm', 'y')) ? 'y' : $pmaxdateinterval;
	$maxdate_str = '+'.$pmaxdate.$pmaxdateinterval;
	$pcronkey = JRequest::getString('cronkey', '', 'request');
	$pcdsfrom = JRequest::getVar('cdsfrom', array());
	$pcdsto = JRequest::getVar('cdsto', array());
	$closing_dates = array();
	if(count($pcdsfrom)) {
		foreach ($pcdsfrom as $kcd => $vcdfrom) {
			if(!empty($vcdfrom) && array_key_exists($kcd, $pcdsto) && !empty($pcdsto[$kcd])) {
				$tscdfrom = vikbooking::getDateTimestamp($vcdfrom, '0', '0');
				$tscdto = vikbooking::getDateTimestamp($pcdsto[$kcd], '0', '0');
				if(!empty($tscdfrom) && !empty($tscdto) && $tscdto > $tscdfrom) {
					$cdval = array('from' => $tscdfrom, 'to' => $tscdto);
					if(!in_array($cdval, $closing_dates)) {
						$closing_dates[] = $cdval;
					}
				}
			}
		}
	}
	$psmartsearch = JRequest::getString('smartsearch', '', 'request');
	$psmartsearch = $psmartsearch == "dynamic" ? "dynamic" : "automatic";
	$pvbosef = JRequest::getInt('vbosef', '', 'request');
	$vbosef = file_exists(JPATH_SITE.DS.'components'.DS.'com_vikbooking'.DS.'router.php');
	if($pvbosef === 1) {
		if(!$vbosef) {
			rename(JPATH_SITE.DS.'components'.DS.'com_vikbooking'.DS.'_router.php', JPATH_SITE.DS.'components'.DS.'com_vikbooking'.DS.'router.php');
		}
	}else {
		if($vbosef) {
			rename(JPATH_SITE.DS.'components'.DS.'com_vikbooking'.DS.'router.php', JPATH_SITE.DS.'components'.DS.'com_vikbooking'.DS.'_router.php');
		}
	}
	$pmultilang = JRequest::getString('multilang', '', 'request');
	$pmultilang = $pmultilang == "1" ? 1 : 0;
	$picon="";
	if (intval($_FILES['sitelogo']['error']) == 0 && trim($_FILES['sitelogo']['name'])!="") {
		jimport('joomla.filesystem.file');
		if (@is_uploaded_file($_FILES['sitelogo']['tmp_name'])) {
			$safename=JFile::makeSafe(str_replace(" ", "_", strtolower($_FILES['sitelogo']['name'])));
			if (file_exists('./components/com_vikbooking/resources/'.$safename)) {
				$j=1;
				while (file_exists('./components/com_vikbooking/resources/'.$j.$safename)) {
					$j++;
				}
				$pwhere='./components/com_vikbooking/resources/'.$j.$safename;
			}else {
				$j="";
				$pwhere='./components/com_vikbooking/resources/'.$safename;
			}
			@move_uploaded_file($_FILES['sitelogo']['tmp_name'], $pwhere);
			if(!getimagesize($pwhere)){
				@unlink($pwhere);
				$picon="";
			}else {
				@chmod($pwhere, 0644);
				$picon=$j.$safename;
			}
		}
		if (!empty($picon)) {
			$q="UPDATE `#__vikbooking_config` SET `setting`=".$dbo->quote($picon)." WHERE `param`='sitelogo';";
			$dbo->setQuery($q);
			$dbo->Query($q);
		}
	}
	if (empty($pallowbooking) || $pallowbooking!="1") {
		$q="UPDATE `#__vikbooking_config` SET `setting`='0' WHERE `param`='allowbooking';";
	}else {
		$q="UPDATE `#__vikbooking_config` SET `setting`='1' WHERE `param`='allowbooking';";
	}
	$dbo->setQuery($q);
	$dbo->Query($q);
	if (empty($pshowcategories) || $pshowcategories!="yes") {
		$q="UPDATE `#__vikbooking_config` SET `setting`='0' WHERE `param`='showcategories';";
	}else {
		$q="UPDATE `#__vikbooking_config` SET `setting`='1' WHERE `param`='showcategories';";
	}
	$dbo->setQuery($q);
	$dbo->Query($q);
	if (empty($pshowchildren) || $pshowchildren!="yes") {
		$q="UPDATE `#__vikbooking_config` SET `setting`='0' WHERE `param`='showchildren';";
	}else {
		$q="UPDATE `#__vikbooking_config` SET `setting`='1' WHERE `param`='showchildren';";
	}
	$dbo->setQuery($q);
	$dbo->Query($q);
	if (empty($ptokenform) || $ptokenform!="yes") {
		$q="UPDATE `#__vikbooking_config` SET `setting`='0' WHERE `param`='tokenform';";
	}else {
		$q="UPDATE `#__vikbooking_config` SET `setting`='1' WHERE `param`='tokenform';";
	}
	$dbo->setQuery($q);
	$dbo->Query($q);
	$q="UPDATE `#__vikbooking_texts` SET `setting`=".$dbo->quote($pfooterordmail)." WHERE `param`='footerordmail';";
	$dbo->setQuery($q);
	$dbo->Query($q);
	$q="UPDATE `#__vikbooking_texts` SET `setting`=".$dbo->quote($pdisabledbookingmsg)." WHERE `param`='disabledbookingmsg';";
	$dbo->setQuery($q);
	$dbo->Query($q);
	$q="UPDATE `#__vikbooking_config` SET `setting`=".$dbo->quote($padminemail)." WHERE `param`='adminemail';";
	$dbo->setQuery($q);
	$dbo->Query($q);
	$q="UPDATE `#__vikbooking_config` SET `setting`=".$dbo->quote($psenderemail)." WHERE `param`='senderemail';";
	$dbo->setQuery($q);
	$dbo->Query($q);
	if (empty($pdateformat)) {
		$pdateformat="%d/%m/%Y";
	}
	$q="UPDATE `#__vikbooking_config` SET `setting`=".$dbo->quote($pdateformat)." WHERE `param`='dateformat';";
	$dbo->setQuery($q);
	$dbo->Query($q);
	$q="UPDATE `#__vikbooking_config` SET `setting`=".$dbo->quote($pminuteslock)." WHERE `param`='minuteslock';";
	$dbo->setQuery($q);
	$dbo->Query($q);
	$openingh=$ptimeopenstorefh * 3600;
	$openingm=$ptimeopenstorefm * 60;
	$openingts=$openingh + $openingm;
	$closingh=$ptimeopenstoreth * 3600;
	$closingm=$ptimeopenstoretm * 60;
	$closingts=$closingh + $closingm;
	$q="UPDATE `#__vikbooking_config` SET `setting`='".$openingts."-".$closingts."' WHERE `param`='timeopenstore';";
	$dbo->setQuery($q);
	$dbo->Query($q);
	//set the hours of extended gratuity period to the difference between checkin and checkout if checkout is later
	$phoursmorebookingback = "0";
	if ($closingts > $openingts) {
		$diffcheck = ($closingts - $openingts) / 3600;
		$phoursmorebookingback = ceil($diffcheck);
	}
	$q="UPDATE `#__vikbooking_config` SET `setting`='".$phoursmorebookingback."' WHERE `param`='hoursmorebookingback';";
	$dbo->setQuery($q);
	$dbo->Query($q);
	$phoursmoreroomavail = "0";
	$q="UPDATE `#__vikbooking_config` SET `setting`='".$phoursmoreroomavail."' WHERE `param`='hoursmoreroomavail';";
	$dbo->setQuery($q);
	$dbo->Query($q);
	$q="UPDATE `#__vikbooking_config` SET `setting`='".$pmultilang."' WHERE `param`='multilang';";
	$dbo->setQuery($q);
	$dbo->Query($q);
	$q="UPDATE `#__vikbooking_config` SET `setting`='".($prequirelogin == "1" ? "1" : "0")."' WHERE `param`='requirelogin';";
	$dbo->setQuery($q);
	$dbo->Query($q);
	$q="UPDATE `#__vikbooking_config` SET `setting`='".($pautoroomunit == 1 ? "1" : "0")."' WHERE `param`='autoroomunit';";
	$dbo->setQuery($q);
	$dbo->Query($q);
	$q="UPDATE `#__vikbooking_config` SET `setting`='".(string)$ptodaybookings."' WHERE `param`='todaybookings';";
	$dbo->setQuery($q);
	$dbo->Query($q);
	$q="UPDATE `#__vikbooking_config` SET `setting`='".(string)$ploadbootstrap."' WHERE `param`='bootstrap';";
	$dbo->setQuery($q);
	$dbo->Query($q);
	$q="UPDATE `#__vikbooking_config` SET `setting`='".$ploadjquery."' WHERE `param`='loadjquery';";
	$dbo->setQuery($q);
	$dbo->Query($q);
	$q="UPDATE `#__vikbooking_config` SET `setting`='".$pcalendar."' WHERE `param`='calendar';";
	$dbo->setQuery($q);
	$dbo->Query($q);
	$q="UPDATE `#__vikbooking_config` SET `setting`='".$penablecoupons."' WHERE `param`='enablecoupons';";
	$dbo->setQuery($q);
	$dbo->Query($q);
	$q="UPDATE `#__vikbooking_config` SET `setting`='".$penablepin."' WHERE `param`='enablepin';";
	$dbo->setQuery($q);
	$dbo->Query($q);
	$q="UPDATE `#__vikbooking_config` SET `setting`='".$pmindaysadvance."' WHERE `param`='mindaysadvance';";
	$dbo->setQuery($q);
	$dbo->Query($q);
	$q="UPDATE `#__vikbooking_config` SET `setting`='".$pautodefcalnights."' WHERE `param`='autodefcalnights';";
	$dbo->setQuery($q);
	$dbo->Query($q);
	$q="UPDATE `#__vikbooking_config` SET `setting`='".$pnumrooms."' WHERE `param`='numrooms';";
	$dbo->setQuery($q);
	$dbo->Query($q);
	$q="UPDATE `#__vikbooking_config` SET `setting`='".$confnumadults."' WHERE `param`='numadults';";
	$dbo->setQuery($q);
	$dbo->Query($q);
	$q="UPDATE `#__vikbooking_config` SET `setting`='".$confnumchildren."' WHERE `param`='numchildren';";
	$dbo->setQuery($q);
	$dbo->Query($q);
	$q="UPDATE `#__vikbooking_config` SET `setting`='".json_encode($closing_dates)."' WHERE `param`='closingdates';";
	$dbo->setQuery($q);
	$dbo->Query($q);
	$q="UPDATE `#__vikbooking_config` SET `setting`='".$psmartsearch."' WHERE `param`='smartsearch';";
	$dbo->setQuery($q);
	$dbo->Query($q);
	$q="UPDATE `#__vikbooking_config` SET `setting`='".$maxdate_str."' WHERE `param`='maxdate';";
	$dbo->setQuery($q);
	$dbo->Query($q);
	$q="UPDATE `#__vikbooking_config` SET `setting`=".$dbo->quote($pcronkey)." WHERE `param`='cronkey';";
	$dbo->setQuery($q);
	$dbo->Query($q);
	
	$pfronttitle = JRequest::getString('fronttitle', '', 'request');
	$pfronttitletag = JRequest::getString('fronttitletag', '', 'request');
	$pfronttitletagclass = JRequest::getString('fronttitletagclass', '', 'request');
	$psearchbtnval = JRequest::getString('searchbtnval', '', 'request');
	$psearchbtnclass = JRequest::getString('searchbtnclass', '', 'request');
	$pshowfooter = JRequest::getString('showfooter', '', 'request');
	$pintromain = JRequest::getString('intromain', '', 'request', JREQUEST_ALLOWHTML);
	$pclosingmain = JRequest::getString('closingmain', '', 'request', JREQUEST_ALLOWHTML);
	$pcurrencyname = JRequest::getString('currencyname', '', 'request', JREQUEST_ALLOWHTML);
	$pcurrencysymb = JRequest::getString('currencysymb', '', 'request', JREQUEST_ALLOWHTML);
	$pcurrencycodepp = JRequest::getString('currencycodepp', '', 'request');
	$pnumdecimals = JRequest::getString('numdecimals', '', 'request');
	$pnumdecimals = intval($pnumdecimals);
	$pdecseparator = JRequest::getString('decseparator', '', 'request');
	$pdecseparator = empty($pdecseparator) ? '.' : $pdecseparator;
	$pthoseparator = JRequest::getString('thoseparator', '', 'request');
	$numberformatstr = $pnumdecimals.':'.$pdecseparator.':'.$pthoseparator;
	$pshowpartlyreserved = JRequest::getString('showpartlyreserved', '', 'request');
	$pshowpartlyreserved = $pshowpartlyreserved == "yes" ? 1 : 0;
	$pshowcheckinoutonly = JRequest::getInt('showcheckinoutonly', '', 'request');
	$pshowcheckinoutonly = $pshowcheckinoutonly > 0 ? 1 : 0;
	$pnumcalendars = JRequest::getInt('numcalendars', '', 'request');
	$pnumcalendars = $pnumcalendars > -1 ? $pnumcalendars : 3;
	$pfirstwday = JRequest::getString('firstwday', '', 'request');
	$pfirstwday = intval($pfirstwday) >= 0 && intval($pfirstwday) <= 6 ? $pfirstwday : '0';
	//theme
	$ptheme = JRequest::getString('theme', '', 'request');
	if(empty($ptheme) || $ptheme == 'default') {
		$ptheme = 'default';
	}else {
		$validtheme = false;
		$themes = glob(JPATH_SITE.DS.'components'.DS.'com_vikbooking'.DS.'themes'.DS.'*');
		if(count($themes) > 0) {
			$strip = JPATH_SITE.DS.'components'.DS.'com_vikbooking'.DS.'themes'.DS;
			foreach($themes as $th) {
				if(is_dir($th)) {
					$tname = str_replace($strip, '', $th);
					if($tname == $ptheme) {
						$validtheme = true;
						break;
					}
				}
			}
		}
		if($validtheme == false) {
			$ptheme = 'default';
		}
	}
	$q="UPDATE `#__vikbooking_config` SET `setting`=".$dbo->quote($ptheme)." WHERE `param`='theme';";
	$dbo->setQuery($q);
	$dbo->Query($q);
	//
	$q="UPDATE `#__vikbooking_config` SET `setting`=".$dbo->quote($pshowpartlyreserved)." WHERE `param`='showpartlyreserved';";
	$dbo->setQuery($q);
	$dbo->Query($q);
	$q="UPDATE `#__vikbooking_config` SET `setting`=".$dbo->quote($pshowcheckinoutonly)." WHERE `param`='showcheckinoutonly';";
	$dbo->setQuery($q);
	$dbo->Query($q);
	$q="UPDATE `#__vikbooking_config` SET `setting`=".$dbo->quote($pnumcalendars)." WHERE `param`='numcalendars';";
	$dbo->setQuery($q);
	$dbo->Query($q);
	$q="UPDATE `#__vikbooking_config` SET `setting`=".$dbo->quote($pfirstwday)." WHERE `param`='firstwday';";
	$dbo->setQuery($q);
	$dbo->Query($q);
	$q="UPDATE `#__vikbooking_texts` SET `setting`=".$dbo->quote($pfronttitle)." WHERE `param`='fronttitle';";
	$dbo->setQuery($q);
	$dbo->Query($q);
	$q="UPDATE `#__vikbooking_config` SET `setting`=".$dbo->quote($pfronttitletag)." WHERE `param`='fronttitletag';";
	$dbo->setQuery($q);
	$dbo->Query($q);
	$q="UPDATE `#__vikbooking_config` SET `setting`=".$dbo->quote($pfronttitletagclass)." WHERE `param`='fronttitletagclass';";
	$dbo->setQuery($q);
	$dbo->Query($q);
	$q="UPDATE `#__vikbooking_texts` SET `setting`=".$dbo->quote($psearchbtnval)." WHERE `param`='searchbtnval';";
	$dbo->setQuery($q);
	$dbo->Query($q);
	$q="UPDATE `#__vikbooking_config` SET `setting`=".$dbo->quote($psearchbtnclass)." WHERE `param`='searchbtnclass';";
	$dbo->setQuery($q);
	$dbo->Query($q);
	if (empty($pshowfooter) || $pshowfooter!="yes") {
		$q="UPDATE `#__vikbooking_config` SET `setting`='0' WHERE `param`='showfooter';";
	}else {
		$q="UPDATE `#__vikbooking_config` SET `setting`='1' WHERE `param`='showfooter';";
	}
	$dbo->setQuery($q);
	$dbo->Query($q);
	$q="UPDATE `#__vikbooking_texts` SET `setting`=".$dbo->quote($pintromain)." WHERE `param`='intromain';";
	$dbo->setQuery($q);
	$dbo->Query($q);
	$q="UPDATE `#__vikbooking_texts` SET `setting`=".$dbo->quote($pclosingmain)." WHERE `param`='closingmain';";
	$dbo->setQuery($q);
	$dbo->Query($q);
	$q="UPDATE `#__vikbooking_config` SET `setting`=".$dbo->quote($pcurrencyname)." WHERE `param`='currencyname';";
	$dbo->setQuery($q);
	$dbo->Query($q);
	$q="UPDATE `#__vikbooking_config` SET `setting`=".$dbo->quote($pcurrencysymb)." WHERE `param`='currencysymb';";
	$dbo->setQuery($q);
	$dbo->Query($q);
	$q="UPDATE `#__vikbooking_config` SET `setting`=".$dbo->quote($pcurrencycodepp)." WHERE `param`='currencycodepp';";
	$dbo->setQuery($q);
	$dbo->Query($q);
	$q="UPDATE `#__vikbooking_config` SET `setting`=".$dbo->quote($numberformatstr)." WHERE `param`='numberformat';";
	$dbo->setQuery($q);
	$dbo->Query($q);
	
	$pivainclusa = JRequest::getString('ivainclusa', '', 'request');
	$ptaxsummary = JRequest::getString('taxsummary', '', 'request');
	$ptaxsummary = empty($ptaxsummary) || $ptaxsummary != "yes" ? "0" : "1";
	$pccpaypal = JRequest::getString('ccpaypal', '', 'request');
	$ppaytotal = JRequest::getString('paytotal', '', 'request');
	$ppayaccpercent = JRequest::getString('payaccpercent', '', 'request');
	$ptypedeposit = JRequest::getString('typedeposit', '', 'request');
	$ptypedeposit = $ptypedeposit == 'fixed' ? 'fixed' : 'pcent';
	$ppaymentname = JRequest::getString('paymentname', '', 'request');
	$pmultipay = JRequest::getString('multipay', '', 'request');
	$pmultipay = $pmultipay == "yes" ? 1 : 0;
	if (empty($pivainclusa) || $pivainclusa!="yes") {
		$q="UPDATE `#__vikbooking_config` SET `setting`='0' WHERE `param`='ivainclusa';";
	}else {
		$q="UPDATE `#__vikbooking_config` SET `setting`='1' WHERE `param`='ivainclusa';";
	}
	$dbo->setQuery($q);
	$dbo->Query($q);
	$q="UPDATE `#__vikbooking_config` SET `setting`='".$ptaxsummary."' WHERE `param`='taxsummary';";
	$dbo->setQuery($q);
	$dbo->Query($q);
	if (empty($ppaytotal) || $ppaytotal!="yes") {
		$q="UPDATE `#__vikbooking_config` SET `setting`='0' WHERE `param`='paytotal';";
	}else {
		$q="UPDATE `#__vikbooking_config` SET `setting`='1' WHERE `param`='paytotal';";
	}
	$dbo->setQuery($q);
	$dbo->Query($q);
	$q="UPDATE `#__vikbooking_config` SET `setting`=".$dbo->quote($pccpaypal)." WHERE `param`='ccpaypal';";
	$dbo->setQuery($q);
	$dbo->Query($q);
	$q="UPDATE `#__vikbooking_texts` SET `setting`=".$dbo->quote($ppaymentname)." WHERE `param`='paymentname';";
	$dbo->setQuery($q);
	$dbo->Query($q);
	$q="UPDATE `#__vikbooking_config` SET `setting`=".$dbo->quote($ppayaccpercent)." WHERE `param`='payaccpercent';";
	$dbo->setQuery($q);
	$dbo->Query($q);
	$q="UPDATE `#__vikbooking_config` SET `setting`=".$dbo->quote($ptypedeposit)." WHERE `param`='typedeposit';";
	$dbo->setQuery($q);
	$dbo->Query($q);
	$q="UPDATE `#__vikbooking_config` SET `setting`='".$pmultipay."' WHERE `param`='multipay';";
	$dbo->setQuery($q);
	$dbo->Query($q);
	
	$psendjutility = JRequest::getString('sendjutility', '', 'request');
	$pdisclaimer = JRequest::getString('disclaimer', '', 'request', JREQUEST_ALLOWHTML);
	if (empty($psendjutility) || $psendjutility!="yes") {
		$q="UPDATE `#__vikbooking_config` SET `setting`='0' WHERE `param`='sendjutility';";
	}else {
		$q="UPDATE `#__vikbooking_config` SET `setting`='1' WHERE `param`='sendjutility';";
	}
	$dbo->setQuery($q);
	$dbo->Query($q);
	$q="UPDATE `#__vikbooking_texts` SET `setting`=".$dbo->quote($pdisclaimer)." WHERE `param`='disclaimer';";
	$dbo->setQuery($q);
	$dbo->Query($q);

	//SMS APIs
	$psmsapi = JRequest::getString('smsapi', '', 'request');
	$psmsautosend = JRequest::getString('smsautosend', '', 'request');
	$psmsautosend = intval($psmsautosend) > 0 ? 1 : 0;
	$psmssendto = JRequest::getVar('smssendto', array());
	$sms_sendto = array();
	foreach ($psmssendto as $sto) {
		if(in_array($sto, array('admin', 'customer'))) {
			$sms_sendto[] = $sto;
		}
	}
	$psmsadminphone = JRequest::getString('smsadminphone', '', 'request');
	$psmsadmintpl = JRequest::getString('smsadmintpl', '', 'request', JREQUEST_ALLOWRAW);
	$psmscustomertpl = JRequest::getString('smscustomertpl', '', 'request', JREQUEST_ALLOWRAW);
	$viksmsparams = JRequest::getVar('viksmsparams', array());
	$smsparamarr = array();
	if(count($viksmsparams) > 0) {
		foreach($viksmsparams as $setting => $cont) {
			if (strlen($setting) > 0) {
				$smsparamarr[$setting] = $cont;
			}
		}
	}
	$q="UPDATE `#__vikbooking_config` SET `setting`='".$psmsapi."' WHERE `param`='smsapi';";
	$dbo->setQuery($q);
	$dbo->Query($q);
	$q="UPDATE `#__vikbooking_config` SET `setting`='".$psmsautosend."' WHERE `param`='smsautosend';";
	$dbo->setQuery($q);
	$dbo->Query($q);
	$q="UPDATE `#__vikbooking_config` SET `setting`=".$dbo->quote(json_encode($sms_sendto))." WHERE `param`='smssendto';";
	$dbo->setQuery($q);
	$dbo->Query($q);
	$q="UPDATE `#__vikbooking_config` SET `setting`='".$psmsadminphone."' WHERE `param`='smsadminphone';";
	$dbo->setQuery($q);
	$dbo->Query($q);
	$q="UPDATE `#__vikbooking_config` SET `setting`=".$dbo->quote(json_encode($smsparamarr))." WHERE `param`='smsparams';";
	$dbo->setQuery($q);
	$dbo->Query($q);
	$q="UPDATE `#__vikbooking_texts` SET `setting`=".$dbo->quote($psmsadmintpl)." WHERE `param`='smsadmintpl';";
	$dbo->setQuery($q);
	$dbo->Query($q);
	$q="UPDATE `#__vikbooking_texts` SET `setting`=".$dbo->quote($psmscustomertpl)." WHERE `param`='smscustomertpl';";
	$dbo->setQuery($q);
	$dbo->Query($q);
	//
	
	$app = JFactory::getApplication();
	$app->enqueueMessage(JText::_('VBSETTINGSAVED'));
	goConfig($option);
}

function modAvail ($room, $option) {
	if (!empty($room)) {
		$dbo = JFactory::getDBO();
		$q="SELECT `avail` FROM `#__vikbooking_rooms` WHERE `id`=".$dbo->quote($room).";";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$get = $dbo->loadAssocList();
		$q="UPDATE `#__vikbooking_rooms` SET `avail`='".(intval($get[0]['avail'])==1 ? 0 : 1)."' WHERE `id`=".$dbo->quote($room).";";
		$dbo->setQuery($q);
		$dbo->Query($q);
	}
	cancelEditing($option);
}

function updateBusy ($option) {
	$pidorder = JRequest::getString('idorder', '', 'request');
	$pcheckindate = JRequest::getString('checkindate', '', 'request');
	$pcheckoutdate = JRequest::getString('checkoutdate', '', 'request');
	$pcheckinh = JRequest::getString('checkinh', '', 'request');
	$pcheckinm = JRequest::getString('checkinm', '', 'request');
	$pcheckouth = JRequest::getString('checkouth', '', 'request');
	$pcheckoutm = JRequest::getString('checkoutm', '', 'request');
	$pcustdata = JRequest::getString('custdata', '', 'request');
	$pareprices = JRequest::getString('areprices', '', 'request');
	$ptotpaid = JRequest::getString('totpaid', '', 'request');
	$pvcm = JRequest::getInt('vcm', '', 'request');
	$pgoto = JRequest::getString('goto', '', 'request');
	$dbo = JFactory::getDBO();
	$actnow=time();
	$nowdf = vikbooking::getDateFormat(true);
	if ($nowdf=="%d/%m/%Y") {
		$df='d/m/Y';
	}elseif ($nowdf=="%m/%d/%Y") {
		$df='m/d/Y';
	}else {
		$df='Y/m/d';
	}
	$q="SELECT * FROM `#__vikbooking_orders` WHERE `id`='".$pidorder."';";
	$dbo->setQuery($q);
	$dbo->Query($q);
	if ($dbo->getNumRows() == 1) {
		$ord = $dbo->loadAssocList();
		$q="SELECT `or`.*,`r`.`name`,`r`.`idopt`,`r`.`units`,`r`.`fromadult`,`r`.`toadult` FROM `#__vikbooking_ordersrooms` AS `or`,`#__vikbooking_rooms` AS `r` WHERE `or`.`idorder`='".$ord[0]['id']."' AND `or`.`idroom`=`r`.`id` ORDER BY `or`.`id` ASC;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$ordersrooms = $dbo->loadAssocList();
		$ord[0]['rooms_info'] = $ordersrooms;
		//Package or custom rate
		$is_package = !empty($ord[0]['pkg']) ? true : false;
		$is_cust_cost = false;
		foreach($ordersrooms as $kor => $or) {
			if($is_package !== true && !empty($or['cust_cost']) && $or['cust_cost'] > 0.00) {
				$is_cust_cost = true;
				break;
			}
		}
		//
		//VikBooking 1.5 room switching
		$toswitch = array();
		$idbooked = array();
		$rooms_units = array();
		$q = "SELECT `id`,`name`,`units` FROM `#__vikbooking_rooms`;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$all_rooms = $dbo->loadAssocList();
		foreach ($all_rooms as $rr) {
			$rooms_units[$rr['id']]['name'] = $rr['name'];
			$rooms_units[$rr['id']]['units'] = $rr['units'];
		}
		foreach($ordersrooms as $ind => $or) {
			$switch_command = JRequest::getString('switch_'.$or['id'], '', 'request');
			if(!empty($switch_command) && intval($switch_command) != $or['idroom'] && array_key_exists(intval($switch_command), $rooms_units)) {
				$idbooked[$or['idroom']]++;
				$orkey = count($toswitch);
				$toswitch[$orkey]['from'] = $or['idroom'];
				$toswitch[$orkey]['to'] = intval($switch_command);
				$toswitch[$orkey]['record'] = $or;
			}
		}
		if(count($toswitch) > 0 && (!empty($ordersrooms[0]['idtar']) || $is_package || $is_cust_cost)) {
			foreach ($toswitch as $ksw => $rsw) {
				$plusunit = array_key_exists($rsw['to'], $idbooked) ? $idbooked[$rsw['to']] : 0;
				if(!vikbooking::roomBookable($rsw['to'], ($rooms_units[$rsw['to']]['units'] + $plusunit), $ord[0]['checkin'], $ord[0]['checkout'])) {
					unset($toswitch[$ksw]);
					JError::raiseWarning('', JText::sprintf('VBSWITCHRERR', $rsw['record']['name'], $rooms_units[$rsw['to']]['name']));
				}
			}
			if(count($toswitch) > 0) {
				//reset first record rate
				reset($ordersrooms);
				$q="UPDATE `#__vikbooking_ordersrooms` SET `idtar`=NULL,`roomindex`=NULL WHERE `id`=".$ordersrooms[0]['id'].";";
				$dbo->setQuery($q);
				$dbo->Query($q);
				//
				$app = JFactory::getApplication();
				foreach ($toswitch as $ksw => $rsw) {
					$q="UPDATE `#__vikbooking_ordersrooms` SET `idroom`=".$rsw['to'].",`idtar`=NULL,`roomindex`=NULL WHERE `id`=".$rsw['record']['id'].";";
					$dbo->setQuery($q);
					$dbo->Query($q);
					$app->enqueueMessage(JText::sprintf('VBSWITCHROK', $rsw['record']['name'], $rooms_units[$rsw['to']]['name']));
					if($ord[0]['status'] == 'confirmed') {
						//update record in _busy
						$q = "SELECT `b`.`id`,`b`.`idroom`,`ob`.`idorder` FROM `#__vikbooking_busy` AS `b`,`#__vikbooking_ordersbusy` AS `ob` WHERE `b`.`idroom`=" . $rsw['from'] . " AND `b`.`id`=`ob`.`idbusy` AND `ob`.`idorder`=".$ord[0]['id']." LIMIT 1;";
						$dbo->setQuery($q);
						$dbo->Query($q);
						if ($dbo->getNumRows() == 1) {
							$cur_busy = $dbo->loadAssocList();
							$q="UPDATE `#__vikbooking_busy` SET `idroom`=".$rsw['to']." WHERE `id`=".$cur_busy[0]['id']." AND `idroom`=".$cur_busy[0]['idroom']." LIMIT 1;";
							$dbo->setQuery($q);
							$dbo->Query($q);
						}
						//Invoke Channel Manager
						if(file_exists(JPATH_SITE . DS ."components". DS ."com_vikchannelmanager". DS . "helpers" . DS ."synch.vikbooking.php")) {
							JError::raiseNotice('', JText::_('VBCHANNELMANAGERINVOKEASK').' <form action="index.php?option=com_vikbooking" method="post"><input type="hidden" name="option" value="com_vikbooking"/><input type="hidden" name="task" value="invoke_vcm"/><input type="hidden" name="stype" value="modify"/><input type="hidden" name="cid[]" value="'.$ord[0]['id'].'"/><input type="hidden" name="origb" value="'.urlencode(json_encode($ord[0])).'"/><input type="hidden" name="returl" value="'.urlencode("index.php?option=".$option."&task=editbusy".($pvcm == 1 ? '&vcm=1' : '')."&cid[]=".$ord[0]['id']).'"/><button type="submit" class="btn btn-primary">'.JText::_('VBCHANNELMANAGERSENDRQ').'</button></form>');
						}
						//
					}elseif($ord[0]['status'] == 'standby') {
						//remove record in _tmplock
						$q = "DELETE FROM `#__vikbooking_tmplock` WHERE `idorder`=" . intval($ord[0]['id']) . ";";
						$dbo->setQuery($q);
						$dbo->Query($q);
					}
				}
				$app->redirect("index.php?option=".$option."&task=editbusy".($pvcm == 1 ? '&vcm=1' : '')."&cid[]=".$ord[0]['id'].($pgoto == 'overview' ? "&goto=overview" : ""));
				exit;
			}
		}
		//
		$first=vikbooking::getDateTimestamp($pcheckindate, $pcheckinh, $pcheckinm);
		$second=vikbooking::getDateTimestamp($pcheckoutdate, $pcheckouth, $pcheckoutm);
		if ($second > $first) {
			$secdiff=$second - $first;
			$daysdiff=$secdiff / 86400;
			if (is_int($daysdiff)) {
				if ($daysdiff < 1) {
					$daysdiff=1;
				}
			}else {
				if ($daysdiff < 1) {
					$daysdiff=1;
				}else {
					$sum = floor($daysdiff) * 86400;
					$newdiff = $secdiff - $sum;
					$maxhmore = vikbooking::getHoursMoreRb() * 3600;
					if ($maxhmore >= $newdiff) {
						$daysdiff = floor($daysdiff);
					} else {
						$daysdiff = ceil($daysdiff);
					}
				}
			}
			$groupdays = vikbooking::getGroupDays($first, $second, $daysdiff);
			$opertwounits = true;
			foreach($ordersrooms as $ind => $or) {
				$num = $ind + 1;
				$check = "SELECT `b`.`id`,`b`.`checkin`,`b`.`realback`,`ob`.`idorder` FROM `#__vikbooking_busy` AS `b`,`#__vikbooking_ordersbusy` AS `ob` WHERE `b`.`idroom`='" . $or['idroom'] . "' AND `b`.`id`=`ob`.`idbusy` AND `ob`.`idorder`!='".$ord[0]['id']."';";
				$dbo->setQuery($check);
				$dbo->Query($check);
				if ($dbo->getNumRows() > 0) {
					$busy = $dbo->loadAssocList();
					foreach ($groupdays as $gday) {
						$bfound = 0;
						foreach ($busy as $bu) {
							if ($gday >= $bu['checkin'] && $gday <= $bu['realback']) {
								$bfound++;
							}
						}
						if ($bfound >= $or['units'] || !vikbooking::roomNotLocked($or['idroom'], $or['units'], $first, $second)) {
							$opertwounits = false;
						}
					}
				}
			}
			if ($opertwounits === true) {
				//update dates, customer information, amount paid and busy records before checking the rates
				$realback = vikbooking::getHoursRoomAvail() * 3600;
				$realback += $second;
				$newtotalpaid = strlen($ptotpaid) > 0 ? floatval($ptotpaid) : "";
				$q="UPDATE `#__vikbooking_orders` SET `custdata`=".$dbo->quote($pcustdata).", `days`='".$daysdiff."', `checkin`='".$first."', `checkout`='".$second."'".(strlen($newtotalpaid) > 0 ? ", `totpaid`='".$newtotalpaid."'" : "")." WHERE `id`='".$ord[0]['id']."';";
				$dbo->setQuery($q);
				$dbo->Query($q);
				if($ord[0]['status'] == 'confirmed') {
					$q="SELECT `b`.`id` FROM `#__vikbooking_busy` AS `b`,`#__vikbooking_ordersbusy` AS `ob` WHERE `b`.`id`=`ob`.`idbusy` AND `ob`.`idorder`='".$ord[0]['id']."';";
					$dbo->setQuery($q);
					$dbo->Query($q);
					$allbusy = $dbo->loadAssocList();
					foreach($allbusy as $bb) {
						$q="UPDATE `#__vikbooking_busy` SET `checkin`='".$first."', `checkout`='".$second."', `realback`='".$realback."' WHERE `id`='".$bb['id']."';";
						$dbo->setQuery($q);
						$dbo->Query($q);
					}
					if($ord[0]['checkin'] != $first || $ord[0]['checkout'] != $second) {
						//Invoke Channel Manager
						if(file_exists(JPATH_SITE . DS ."components". DS ."com_vikchannelmanager". DS . "helpers" . DS ."synch.vikbooking.php")) {
							JError::raiseNotice('', JText::_('VBCHANNELMANAGERINVOKEASK').' <form action="index.php?option=com_vikbooking" method="post"><input type="hidden" name="option" value="com_vikbooking"/><input type="hidden" name="task" value="invoke_vcm"/><input type="hidden" name="stype" value="modify"/><input type="hidden" name="cid[]" value="'.$ord[0]['id'].'"/><input type="hidden" name="origb" value="'.urlencode(json_encode($ord[0])).'"/><input type="hidden" name="returl" value="'.urlencode("index.php?option=".$option."&task=editbusy".($pvcm == 1 ? '&vcm=1' : '')."&cid[]=".$ord[0]['id']).'"/><button type="submit" class="btn btn-primary">'.JText::_('VBCHANNELMANAGERSENDRQ').'</button></form>');
						}
						//
					}
				}
				$upd_esit = JText::_('RESUPDATED');
				//
				$isdue = 0;
				$tot_taxes = 0;
				$tot_city_taxes = 0;
				$tot_fees = 0;
				$doup = true;
				$tars = array();
				$cust_costs = array();
				$arrpeople = array();
				foreach($ordersrooms as $kor => $or) {
					$num = $kor + 1;
					$padults = JRequest::getString('adults'.$num, '', 'request');
					$pchildren = JRequest::getString('children'.$num, '', 'request');
					if(strlen($padults) || strlen($pchildren)) {
						$arrpeople[$num]['adults'] = (int)$padults;
						$arrpeople[$num]['children'] = (int)$pchildren;
					}
					$ppriceid = JRequest::getString('priceid'.$num, '', 'request');
					$ppkgid = JRequest::getString('pkgid'.$num, '', 'request');
					$pcust_cost = JRequest::getString('cust_cost'.$num, '', 'request');
					$paliq = JRequest::getString('aliq'.$num, '', 'request');
					if($is_package === true && !empty($ppkgid)) {
						$pkg_cost = $or['cust_cost'];
						$pkg_idiva = $or['cust_idiva'];
						$pkg_info = vikbooking::getPackage($ppkgid);
						if(is_array($pkg_info) && count($pkg_info) > 0) {
							$use_adults = array_key_exists($num, $arrpeople) && array_key_exists('adults', $arrpeople[$num]) ? $arrpeople[$num]['adults'] : $or['adults'];
							$pkg_cost = $pkg_info['pernight_total'] == 1 ? ($pkg_info['cost'] * $daysdiff) : $pkg_info['cost'];
							$pkg_cost = $pkg_info['perperson'] == 1 ? ($pkg_cost * ($use_adults > 0 ? $use_adults : 1)) : $pkg_cost;
							$pkg_cost = vikbooking::sayPackagePlusIva($pkg_cost, $pkg_info['idiva']);
						}
						$cust_costs[$num] = array('pkgid' => $ppkgid, 'cust_cost' => $pkg_cost, 'aliq' => $pkg_idiva);
						$isdue += $pkg_cost;
						$cost_minus_tax = vikbooking::sayPackageMinusIva($pkg_cost, $pkg_idiva);
						$tot_taxes += ($pkg_cost - $cost_minus_tax);
						continue;
					}
					if(empty($ppriceid) && !empty($pcust_cost) && floatval($pcust_cost) > 0) {
						$cust_costs[$num] = array('cust_cost' => $pcust_cost, 'aliq' => $paliq);
						$isdue += (float)$pcust_cost;
						$cost_minus_tax = vikbooking::sayPackageMinusIva((float)$pcust_cost, (int)$paliq);
						$tot_taxes += ((float)$pcust_cost - $cost_minus_tax);
						continue;
					}
					$q="SELECT * FROM `#__vikbooking_dispcost` WHERE `idroom`='".$or['idroom']."' AND `days`='".$daysdiff."' AND `idprice`='".$ppriceid."';";
					$dbo->setQuery($q);
					$dbo->Query($q);
					if ($dbo->getNumRows() == 1) {
						$tar = $dbo->loadAssocList();
						$tar = vikbooking::applySeasonsRoom($tar, $ord[0]['checkin'], $ord[0]['checkout']);
						//different usage
						if ($or['fromadult'] <= $or['adults'] && $or['toadult'] >= $or['adults']) {
							$diffusageprice = vikbooking::loadAdultsDiff($or['idroom'], $or['adults']);
							//Occupancy Override
							$occ_ovr = vikbooking::occupancyOverrideExists($tar, $or['adults']);
							$diffusageprice = $occ_ovr !== false ? $occ_ovr : $diffusageprice;
							//
							if (is_array($diffusageprice)) {
								//set a charge or discount to the price(s) for the different usage of the room
								foreach($tar as $kpr => $vpr) {
									if ($diffusageprice['chdisc'] == 1) {
										//charge
										if ($diffusageprice['valpcent'] == 1) {
											//fixed value
											$tar[$kpr]['diffusagecostpernight'] = $diffusageprice['pernight'] == 1 ? 1 : 0;
											$aduseval = $diffusageprice['pernight'] == 1 ? $diffusageprice['value'] * $tar[$kpr]['days'] : $diffusageprice['value'];
											$tar[$kpr]['diffusagecost'] = "+".$aduseval;
											$tar[$kpr]['cost'] = $vpr['cost'] + $aduseval;
										}else {
											//percentage value
											$tar[$kpr]['diffusagecostpernight'] = $diffusageprice['pernight'] == 1 ? $vpr['cost'] : 0;
											$aduseval = $diffusageprice['pernight'] == 1 ? round(($vpr['cost'] * $diffusageprice['value'] / 100) * $tar[$kpr]['days'] + $vpr['cost'], 2) : round(($vpr['cost'] * (100 + $diffusageprice['value']) / 100), 2);
											$tar[$kpr]['diffusagecost'] = "+".$diffusageprice['value']."%";
											$tar[$kpr]['cost'] = $aduseval;
										}
									}else {
										//discount
										if ($diffusageprice['valpcent'] == 1) {
											//fixed value
											$tar[$kpr]['diffusagecostpernight'] = $diffusageprice['pernight'] == 1 ? 1 : 0;
											$aduseval = $diffusageprice['pernight'] == 1 ? $diffusageprice['value'] * $tar[$kpr]['days'] : $diffusageprice['value'];
											$tar[$kpr]['diffusagecost'] = "-".$aduseval;
											$tar[$kpr]['cost'] = $vpr['cost'] - $aduseval;
										}else {
											//percentage value
											$tar[$kpr]['diffusagecostpernight'] = $diffusageprice['pernight'] == 1 ? $vpr['cost'] : 0;
											$aduseval = $diffusageprice['pernight'] == 1 ? round($vpr['cost'] - ((($vpr['cost'] / $tar[$kpr]['days']) * $diffusageprice['value'] / 100) * $tar[$kpr]['days']), 2) : round(($vpr['cost'] * (100 - $diffusageprice['value']) / 100), 2);
											$tar[$kpr]['diffusagecost'] = "-".$diffusageprice['value']."%";
											$tar[$kpr]['cost'] = $aduseval;
										}
									}
								}
							}
						}
						//
						$cost_plus_tax = vikbooking::sayCostPlusIva($tar[0]['cost'], $tar[0]['idprice']);
						$isdue += $cost_plus_tax;
						if($cost_plus_tax == $tar[0]['cost']) {
							$cost_minus_tax = vikbooking::sayCostMinusIva($tar[0]['cost'], $tar[0]['idprice']);
							$tot_taxes += ($tar[0]['cost'] - $cost_minus_tax);
						}else {
							$tot_taxes += ($cost_plus_tax - $tar[0]['cost']);
						}
						$tars[$num] = $tar;
					}else {
						$doup = false;
						break;
					}
				}
				if ($doup === true) {
					$toptionals = '';
					$q="SELECT * FROM `#__vikbooking_optionals` ORDER BY `#__vikbooking_optionals`.`ordering` ASC;";
					$dbo->setQuery($q);
					$dbo->Query($q);
					if ($dbo->getNumRows() > 0) {
						$toptionals = $dbo->loadAssocList();
					}
					foreach($ordersrooms as $kor => $or) {
						$num = $kor + 1;
						$pt_first_name = JRequest::getString('t_first_name'.$num, '', 'request');
						$pt_last_name = JRequest::getString('t_last_name'.$num, '', 'request');
						$wop = "";
						if(is_array($toptionals)) {
							foreach($toptionals as $opt) {
								if (!empty($opt['ageintervals']) && ($or['children'] > 0 || (array_key_exists($num, $arrpeople) && array_key_exists('children', $arrpeople[$num]))) ) {
									$tmpvar = JRequest::getVar('optid'.$num.$opt['id'], array(0));
									if (is_array($tmpvar) && count($tmpvar) > 0 && !empty($tmpvar[0])) {
										$opt['quan'] = 1;
										$optagecosts = vikbooking::getOptionIntervalsCosts($opt['ageintervals']);
										$optagenames = vikbooking::getOptionIntervalsAges($opt['ageintervals']);
										$optorigname = $opt['name'];
										foreach ($tmpvar as $chvar) {
											$opt['cost'] = $optagecosts[($chvar - 1)];
											$opt['name'] = $optorigname.' ('.$optagenames[($chvar - 1)].')';
											$opt['chageintv'] = $chvar;
											$wop.=$opt['id'].":".$opt['quan']."-".$chvar.";";
											$realcost = (intval($opt['perday']) == 1 ? ($opt['cost'] * $daysdiff * $opt['quan']) : ($opt['cost'] * $opt['quan']));
											if (!empty ($opt['maxprice']) && $opt['maxprice'] > 0 && $realcost > $opt['maxprice']) {
												$realcost = $opt['maxprice'];
											}
											$tmpopr = vikbooking::sayOptionalsPlusIva($realcost, $opt['idiva']);
											if ($opt['is_citytax'] == 1) {
												$tot_city_taxes += $tmpopr;
											}elseif ($opt['is_fee'] == 1) {
												$tot_fees += $tmpopr;
											}else {
												if($tmpopr == $realcost) {
													$opt_minus_iva = vikbooking::sayOptionalsMinusIva($realcost, $opt['idiva']);
													$tot_taxes += ($realcost - $opt_minus_iva);
												}else {
													$tot_taxes += ($tmpopr - $realcost);
												}
											}
											$isdue += $tmpopr;
										}
									}
								}else {
									$tmpvar=JRequest::getString('optid'.$num.$opt['id'], '', 'request');
									if (!empty($tmpvar)) {
										$wop.=$opt['id'].":".$tmpvar.";";
										$realcost = (intval($opt['perday']) == 1 ? ($opt['cost'] * $daysdiff * $tmpvar) : ($opt['cost'] * $tmpvar));
										if (!empty ($opt['maxprice']) && $opt['maxprice'] > 0 && $realcost > $opt['maxprice']) {
											$realcost = $opt['maxprice'];
											if(intval($opt['hmany']) == 1 && intval($tmpvar) > 1) {
												$realcost = $opt['maxprice'] * $tmpvar;
											}
										}
										if ($opt['perperson'] == 1) {
											$num_adults = array_key_exists($num, $arrpeople) && array_key_exists('adults', $arrpeople[$num]) ? $arrpeople[$num]['adults'] : $num_adults;
											$realcost = $realcost * $num_adults;
										}
										$tmpopr = vikbooking::sayOptionalsPlusIva($realcost, $opt['idiva']);
										if ($opt['is_citytax'] == 1) {
											$tot_city_taxes += $tmpopr;
										}elseif ($opt['is_fee'] == 1) {
											$tot_fees += $tmpopr;
										}else {
											if($tmpopr == $realcost) {
												$opt_minus_iva = vikbooking::sayOptionalsMinusIva($realcost, $opt['idiva']);
												$tot_taxes += ($realcost - $opt_minus_iva);
											}else {
												$tot_taxes += ($tmpopr - $realcost);
											}
										}
										$isdue += $tmpopr;
									}
								}
							}
						}
						$upd_fields = array();
						if($is_package !== true && array_key_exists($num, $tars)) {
							//type of price
							$upd_fields[] = "`idtar`='".$tars[$num][0]['id']."'";
							$upd_fields[] = "`cust_cost`=NULL";
							$upd_fields[] = "`cust_idiva`=NULL";
						}elseif ($is_package === true && array_key_exists($num, $cust_costs)) {
							//packages do not update name or cost, just set again the same package ID to avoid risks of empty upd_fields to update
							$upd_fields[] = "`idtar`=NULL";
							$upd_fields[] = "`pkg_id`='".$cust_costs[$num]['pkgid']."'";
							$upd_fields[] = "`cust_cost`='".$cust_costs[$num]['cust_cost']."'";
							$upd_fields[] = "`cust_idiva`='".$cust_costs[$num]['aliq']."'";
						}elseif (array_key_exists($num, $cust_costs) && array_key_exists('cust_cost', $cust_costs[$num])) {
							//custom rate + custom tax rate
							$upd_fields[] = "`idtar`=NULL";
							$upd_fields[] = "`cust_cost`='".$cust_costs[$num]['cust_cost']."'";
							$upd_fields[] = "`cust_idiva`='".$cust_costs[$num]['aliq']."'";
						}
						if(is_array($toptionals)) {
							$upd_fields[] = "`optionals`='".$wop."'";
						}
						if(!empty($pt_first_name) || !empty($pt_last_name)) {
							$upd_fields[] = "`t_first_name`=".$dbo->quote($pt_first_name);
							$upd_fields[] = "`t_last_name`=".$dbo->quote($pt_last_name);
						}
						if(array_key_exists($num, $arrpeople) && array_key_exists('adults', $arrpeople[$num])) {
							$upd_fields[] = "`adults`=".intval($arrpeople[$num]['adults']);
							$upd_fields[] = "`children`=".intval($arrpeople[$num]['children']);
						}
						if(count($upd_fields) > 0) {
							$q="UPDATE `#__vikbooking_ordersrooms` SET ".implode(', ', $upd_fields)." WHERE `idorder`='".$ord[0]['id']."' AND `idroom`='".$or['idroom']."' AND `id`='".$or['id']."';";
							$dbo->setQuery($q);
							$dbo->Query($q);
						}
					}
					$q="UPDATE `#__vikbooking_orders` SET `total`='".$isdue."', `tot_taxes`='".$tot_taxes."', `tot_city_taxes`='".$tot_city_taxes."', `tot_fees`='".$tot_fees."' WHERE `id`='".$ord[0]['id']."';";
					$dbo->setQuery($q);
					$dbo->Query($q);
					$upd_esit = JText::_('VBORESRATESUPDATED');
				}
				$app = JFactory::getApplication();
				$app->enqueueMessage($upd_esit);
			}else {
				JError::raiseWarning('', JText::_('VBROOMNOTRIT')." ".date($df.' H:i', $first)." ".JText::_('VBROOMNOTCONSTO')." ".date($df.' H:i', $second));
			}
		}else {
			JError::raiseWarning('', JText::_('ERRPREV'));
		}
		$mainframe = JFactory::getApplication();
		$mainframe->redirect("index.php?option=".$option."&task=editbusy".($pvcm == 1 ? '&vcm=1' : '')."&cid[]=".$ord[0]['id'].($pgoto == 'overview' ? "&goto=overview" : ""));
	}else {
		cancelEditing($option);
	}
}

function removeTariffe ($ids, $option) {
	$proomid = JRequest::getString('roomid', '', 'request');
	if (@count($ids)) {
		$dbo = JFactory::getDBO();
		foreach($ids as $r){
			$x=explode(";", $r);
			foreach($x as $rm){
				if (!empty($rm)) {
					$q="DELETE FROM `#__vikbooking_dispcost` WHERE `id`=".$dbo->quote($rm).";";
					$dbo->setQuery($q);
					$dbo->Query($q);
				}
			}
		}
	}
	$mainframe = JFactory::getApplication();
	$mainframe->redirect("index.php?option=".$option."&task=viewtariffe&cid[]=".$proomid);
}

function editBusy ($oid, $option) {
	$dbo = JFactory::getDBO();
	if (!empty($oid)) {
		$q="SELECT * FROM `#__vikbooking_orders` WHERE `id`='".$oid."';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if ($dbo->getNumRows() == 1) {
			$ord = $dbo->loadAssocList();
			$q="SELECT `or`.*,`r`.`name`,`r`.`img`,`r`.`idopt`,`r`.`fromadult`,`r`.`toadult`,`r`.`fromchild`,`r`.`tochild` FROM `#__vikbooking_ordersrooms` AS `or`,`#__vikbooking_rooms` AS `r` WHERE `or`.`idorder`='".$ord[0]['id']."' AND `or`.`idroom`=`r`.`id` ORDER BY `or`.`id` ASC;";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$ordersrooms = $dbo->loadAssocList();
			$arrheader = array('order' => $ord[0], 'ordersrooms' => $ordersrooms);
			$rooms = array();
			$q="SELECT * FROM `#__vikbooking_rooms` ORDER BY `name` ASC;";
			$dbo->setQuery($q);
			$dbo->Query($q);
			if ($dbo->getNumRows() > 0) {
				$rooms = $dbo->loadAssocList();
			}
			HTML_vikbooking::printHeaderBusy($arrheader);
			HTML_vikbooking::pEditBusy($ordersrooms, $ord, $rooms, $option);
		}else {
			cancelEditing($option);
		}
	}else {
		cancelEditing($option);
	}
}

function viewCalendar ($aid, $option) {
	$dbo = JFactory::getDBO();
	if (empty($aid)) {
		$q="SELECT `id` FROM `#__vikbooking_rooms` ORDER BY `#__vikbooking_rooms`.`name` ASC LIMIT 1";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if ($dbo->getNumRows() == 1) {
			$aid = $dbo->loadResult();
		}
	}
	if (!empty($aid)) {
		$session = JFactory::getSession();
		$pvmode = JRequest::getString('vmode', '', 'request');
		$cur_vmode = $session->get('vikbookingvmode', "");
		if (!empty($pvmode) && ctype_digit($pvmode)) {
			$session->set('vikbookingvmode', $pvmode);
		}elseif (empty($cur_vmode)) {
			$session->set('vikbookingvmode', "12");
		}
		$hmany=$session->get('vikbookingvmode', "12");
		$q="SELECT `id`,`name`,`img`,`units` FROM `#__vikbooking_rooms` WHERE `id`=".$dbo->quote($aid).";";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if ($dbo->getNumRows() == 1) {
			$roomrows = $dbo->loadAssocList();
			$q="SELECT `id`,`name` FROM `#__vikbooking_gpayments` ORDER BY `#__vikbooking_gpayments`.`name` ASC;";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$payments = $dbo->getNumRows() > 0 ? $dbo->loadAssocList() : '';
			$calmsg="";
			$actnow=time();
			$pcheckindate = JRequest::getString('checkindate', '', 'request');
			$pcheckoutdate = JRequest::getString('checkoutdate', '', 'request');
			$pcheckinh = JRequest::getString('checkinh', '', 'request');
			$pcheckinm = JRequest::getString('checkinm', '', 'request');
			$pcheckouth = JRequest::getString('checkouth', '', 'request');
			$pcheckoutm = JRequest::getString('checkoutm', '', 'request');
			$pcustdata = JRequest::getString('custdata', '', 'request');
			$pcustmail = JRequest::getString('custmail', '', 'request');
			$padults = JRequest::getString('adults', '', 'request');
			$pchildren = JRequest::getString('children', '', 'request');
			$psetclosed = JRequest::getString('setclosed', '', 'request');
			$pordstatus = JRequest::getString('newstatus', '', 'request');
			$pordstatus = (empty($pordstatus) || !in_array($pordstatus, array('confirmed', 'standby')) ? 'confirmed' : $pordstatus);
			$pcountrycode = JRequest::getString('countrycode', '', 'request');
			$pt_first_name = JRequest::getString('t_first_name', '', 'request');
			$pt_last_name = JRequest::getString('t_last_name', '', 'request');
			$pphone = JRequest::getString('phone', '', 'request');
			$pcustomer_id = JRequest::getString('customer_id', '', 'request');
			$ppaymentid = JRequest::getString('payment', '', 'request');
			$paymentmeth = '';
			if (!empty($ppaymentid) && is_array($payments)) {
				foreach($payments as $pay) {
					if (intval($pay['id']) == intval($ppaymentid)) {
						$paymentmeth = $pay['id'].'='.$pay['name'];
						break;
					}
				}
			}
			if (!empty($pcheckindate) && !empty($pcheckoutdate)) {
				if (vikbooking::dateIsValid($pcheckindate) && vikbooking::dateIsValid($pcheckoutdate)) {
					$first=vikbooking::getDateTimestamp($pcheckindate, $pcheckinh, $pcheckinm);
					$second=vikbooking::getDateTimestamp($pcheckoutdate, $pcheckouth, $pcheckoutm);
					if ($second > $first) {
						$secdiff=$second - $first;
						$daysdiff=$secdiff / 86400;
						if (is_int($daysdiff)) {
							if ($daysdiff < 1) {
								$daysdiff=1;
							}
						}else {
							if ($daysdiff < 1) {
								$daysdiff=1;
							}else {
								$sum = floor($daysdiff) * 86400;
								$newdiff = $secdiff - $sum;
								$maxhmore = vikbooking::getHoursMoreRb() * 3600;
								if ($maxhmore >= $newdiff) {
									$daysdiff = floor($daysdiff);
								} else {
									$daysdiff = ceil($daysdiff);
								}
							}
						}
						//if the room is totally booked or locked because someone is paying, the administrator is not able to make a reservation for that room  
						if (vikbooking::roomBookable($roomrows[0]['id'], $roomrows[0]['units'], $first, $second) && vikbooking::roomNotLocked($roomrows[0]['id'], $roomrows[0]['units'], $first, $second)) {
							//Customer
							$cpin = vikbooking::getCPinIstance();
							$cpin->is_admin = true;
							$cpin->saveCustomerDetails($pt_first_name, $pt_last_name, $pcustmail, $pphone, $pcountrycode, array());
							//
							$realback=vikbooking::getHoursRoomAvail() * 3600;
							$realback+=$second;
							$insertedbusy = array();
							$forend = intval($psetclosed) == 1 ? $roomrows[0]['units'] : 1;
							if($pordstatus == 'confirmed') {
								for($b = 1; $b <= $forend; $b++) {
									$q="INSERT INTO `#__vikbooking_busy` (`idroom`,`checkin`,`checkout`,`realback`) VALUES('".$roomrows[0]['id']."','".$first."','".$second."','".$realback."');";
									$dbo->setQuery($q);
									$dbo->Query($q);
									$lid = $dbo->insertid();
									$insertedbusy[] = $lid;
								}
								if (count($insertedbusy) > 0) {
									$sid=vikbooking::getSecretLink();
									$q="INSERT INTO `#__vikbooking_orders` (`custdata`,`ts`,`status`,`days`,`checkin`,`checkout`,`custmail`,`sid`,`idpayment`,`roomsnum`,`country`,`phone`) VALUES(".$dbo->quote($pcustdata).",'".$actnow."','".$pordstatus."','".$daysdiff."','".$first."','".$second."',".$dbo->quote($pcustmail).",'".$sid."',".$dbo->quote($paymentmeth).",'1',".$dbo->quote($pcountrycode).",".$dbo->quote($pphone).");";
									$dbo->setQuery($q);
									$dbo->Query($q);
									$newoid = $dbo->insertid();
									//ConfirmationNumber and Customer Booking
									$confirmnumber = vikbooking::generateConfirmNumber($newoid, true);
									if(!(intval($cpin->getNewCustomerId()) > 0) && !empty($pcustomer_id) && !empty($pcustomer_pin)) {
										$cpin->setNewPin($pcustomer_pin);
										$cpin->setNewCustomerId($pcustomer_id);
									}
									$cpin->saveCustomerBooking($newoid);
									//end ConfirmationNumber and Customer Booking
									foreach($insertedbusy as $lid) {
										$q="INSERT INTO `#__vikbooking_ordersbusy` (`idorder`,`idbusy`) VALUES('".$newoid."','".$lid."');";
										$dbo->setQuery($q);
										$dbo->Query($q);
									}
									$q="INSERT INTO `#__vikbooking_ordersrooms` (`idorder`,`idroom`,`adults`,`children`,`t_first_name`,`t_last_name`) VALUES('".$newoid."','".$roomrows[0]['id']."','".intval($padults)."','".intval($pchildren)."', ".$dbo->quote($pt_first_name).", ".$dbo->quote($pt_last_name).");";
									$dbo->setQuery($q);
									$dbo->Query($q);
									$calmsg="1";
									//Invoke Channel Manager
									if(file_exists(JPATH_SITE . DS ."components". DS ."com_vikchannelmanager". DS . "helpers" . DS ."synch.vikbooking.php")) {
										$vcm_sync_url = 'index.php?option=com_vikbooking&task=invoke_vcm&stype=new&cid[]='.$newoid.'&returl='.urlencode('index.php?option=com_vikbooking&task=calendar&cid[]='.$aid);
										JError::raiseNotice('', JText::_('VBCHANNELMANAGERINVOKEASK').' <button type="button" class="btn btn-primary" onclick="document.location.href=\''.$vcm_sync_url.'\';">'.JText::_('VBCHANNELMANAGERSENDRQ').'</button>');
									}
									//
								}
							}elseif ($pordstatus == 'standby') {
								$sid=vikbooking::getSecretLink();
								$q="INSERT INTO `#__vikbooking_orders` (`custdata`,`ts`,`status`,`days`,`checkin`,`checkout`,`custmail`,`sid`,`idpayment`,`roomsnum`,`country`,`phone`) VALUES(".$dbo->quote($pcustdata).",'".$actnow."','".$pordstatus."','".$daysdiff."','".$first."','".$second."',".$dbo->quote($pcustmail).",'".$sid."',".$dbo->quote($paymentmeth).",'1',".$dbo->quote($pcountrycode).",".$dbo->quote($pphone).");";
								$dbo->setQuery($q);
								$dbo->Query($q);
								$newoid = $dbo->insertid();
								//Customer Booking
								if(!(intval($cpin->getNewCustomerId()) > 0) && !empty($pcustomer_id) && !empty($pcustomer_pin)) {
									$cpin->setNewPin($pcustomer_pin);
									$cpin->setNewCustomerId($pcustomer_id);
								}
								$cpin->saveCustomerBooking($newoid);
								//
								$q="INSERT INTO `#__vikbooking_ordersrooms` (`idorder`,`idroom`,`adults`,`children`,`t_first_name`,`t_last_name`) VALUES('".$newoid."','".$roomrows[0]['id']."','".intval($padults)."','".intval($pchildren)."', ".$dbo->quote($pt_first_name).", ".$dbo->quote($pt_last_name).");";
								$dbo->setQuery($q);
								$dbo->Query($q);
								$app = JFactory::getApplication();
								$app->enqueueMessage(JText::_('VBQUICKRESWARNSTANDBY'));
								$mainframe = JFactory::getApplication();
								$mainframe->redirect("index.php?option=com_vikbooking&task=editbusy&cid[]=".$newoid);
							}
						}else {
							$calmsg="0";
						}
					}else {
						JError::raiseWarning('', 'Invalid Dates: current server time is '.date('Y-m-d H:i', $actnow).'. Reservation requested from '.date('Y-m-d H:i', $first).' to '.date('Y-m-d H:i', $second));
					}
				}else {
					JError::raiseWarning('', 'Invalid Dates');
				}
			}
			
			$mints = mktime(0, 0, 0, date('m'), 1, date('Y'));
			$q="SELECT `b`.*,`ob`.`idorder` FROM `#__vikbooking_busy` AS `b` LEFT JOIN `#__vikbooking_ordersbusy` `ob` ON `ob`.`idbusy`=`b`.`id` WHERE `b`.`idroom`='".$roomrows[0]['id']."' AND (`b`.`checkin`>=".$mints." OR `b`.`checkout`>=".$mints.");";
			$dbo->setQuery($q);
			$dbo->Query($q);
			if ($dbo->getNumRows() > 0) {
				$busy = $dbo->loadAssocList();
			}else {
				$busy="";
			}
			$q="SELECT `id`,`name` FROM `#__vikbooking_rooms` ORDER BY `#__vikbooking_rooms`.`name` ASC;";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$allc=$dbo->loadAssocList();
			HTML_vikbooking::printHeaderCalendar($roomrows[0], $calmsg, $allc, $payments);
			HTML_vikbooking::pViewCalendar($roomrows[0], $busy, $hmany, $option);
		}else {
			cancelEditing($option);
		}
	}else {
		cancelEditing($option);
	}
}

function viewTariffe ($aid, $option) {
	//vikbooking 1.1
	if (empty($aid)) {
		$dbo = JFactory::getDBO();
		$q="SELECT `id` FROM `#__vikbooking_rooms` ORDER BY `#__vikbooking_rooms`.`name` ASC LIMIT 1";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if ($dbo->getNumRows() == 1) {
			$aid = $dbo->loadResult();
		}
	}
	//
	if (!empty($aid)) {
		$dbo = JFactory::getDBO();
		$q="SELECT `id`,`name`,`img` FROM `#__vikbooking_rooms` WHERE `id`=".$dbo->quote($aid).";";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if ($dbo->getNumRows() == 1) {
			$roomrows = $dbo->loadAssocList();
			$q="SELECT * FROM `#__vikbooking_prices`;";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$prices=($dbo->getNumRows() > 0 ? $dbo->loadAssocList() : "");
			$pnewtar = JRequest::getString('newdispcost', '', 'request');
			$pddaysfrom = JRequest::getString('ddaysfrom', '', 'request');
			$pddaysto = JRequest::getString('ddaysto', '', 'request');
			if (!empty($pnewtar) && !empty($pddaysfrom) && is_array($prices)) {
				if(empty($pddaysto) || $pddaysfrom==$pddaysto) {
					foreach($prices as $pr){
						$tmpvarone=JRequest::getString('dprice'.$pr['id'], '', 'request');
						if (!empty($tmpvarone)) {
							$tmpvartwo=JRequest::getString('dattr'.$pr['id'], '', 'request');
							$multipattr=is_numeric($tmpvartwo) ? true : false;
							$safeq="SELECT `id` FROM `#__vikbooking_dispcost` WHERE `days`=".$dbo->quote($pddaysfrom)." AND `idroom`='".$roomrows[0]['id']."' AND `idprice`='".$pr['id']."';";
							$dbo->setQuery($safeq);
							$dbo->Query($safeq);
							if ($dbo->getNumRows() == 0) {
								$q="INSERT INTO `#__vikbooking_dispcost` (`idroom`,`days`,`idprice`,`cost`,`attrdata`) VALUES('".$roomrows[0]['id']."',".$dbo->quote($pddaysfrom).",'".$pr['id']."','".($tmpvarone * $pddaysfrom)."',".($multipattr ? "'".($tmpvartwo  * $pddaysfrom)."'" : $dbo->quote($tmpvartwo)).");";
								$dbo->setQuery($q);
								$dbo->Query($q);
							}
						}
					}
				}else {
					$pddaysto = intval($pddaysto) > 365 ? 365 : $pddaysto;
					for($i=intval($pddaysfrom); $i<=intval($pddaysto); $i++) {
						foreach($prices as $pr){
							$tmpvarone=JRequest::getString('dprice'.$pr['id'], '', 'request');
							if (!empty($tmpvarone)) {
								$tmpvartwo=JRequest::getString('dattr'.$pr['id'], '', 'request');
								$multipattr=is_numeric($tmpvartwo) ? true : false;
								$safeq="SELECT `id` FROM `#__vikbooking_dispcost` WHERE `days`=".$dbo->quote($i)." AND `idroom`='".$roomrows[0]['id']."' AND `idprice`='".$pr['id']."';";
								$dbo->setQuery($safeq);
								$dbo->Query($safeq);
								if ($dbo->getNumRows() == 0) {
									$q="INSERT INTO `#__vikbooking_dispcost` (`idroom`,`days`,`idprice`,`cost`,`attrdata`) VALUES('".$roomrows[0]['id']."',".$dbo->quote($i).",'".$pr['id']."','".($tmpvarone * $i)."',".($multipattr ? "'".($tmpvartwo  * $i)."'" : $dbo->quote($tmpvartwo)).");";
									$dbo->setQuery($q);
									$dbo->Query($q);
								}
							}
						}
					}
				}
			}
			$q="SELECT * FROM `#__vikbooking_dispcost` WHERE `idroom`='".$roomrows[0]['id']."' ORDER BY `#__vikbooking_dispcost`.`days` ASC, `#__vikbooking_dispcost`.`idprice` ASC;";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$lines = ($dbo->getNumRows() > 0 ? $dbo->loadAssocList() : "");
			$q="SELECT `id`,`name` FROM `#__vikbooking_rooms` ORDER BY `#__vikbooking_rooms`.`name` ASC;";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$allc=$dbo->loadAssocList();
			HTML_vikbooking::printHeaderRoom($roomrows[0]['img'], $roomrows[0]['name'], $prices, $roomrows[0]['id'], $allc);
			HTML_vikbooking::pViewTariffe($roomrows[0], $lines, $option);
		}else {
			cancelEditing($option);
		}
	}else {
		cancelEditing($option);
	}
}

function saveRoom ($option, $stay = false) {
	$pcname = JRequest::getString('cname', '', 'request');
	$pccat = JRequest::getVar('ccat', array(0));
	$pcdescr = JRequest::getString('cdescr', '', 'request', JREQUEST_ALLOWRAW);
	$psmalldesc = JRequest::getString('smalldesc', '', 'request', JREQUEST_ALLOWRAW);
	$pccarat = JRequest::getVar('ccarat', array(0));
	$pcoptional = JRequest::getVar('coptional', array(0));
	$pcavail = JRequest::getString('cavail', '', 'request');
	$pautoresize = JRequest::getString('autoresize', '', 'request');
	$presizeto = JRequest::getString('resizeto', '', 'request');
	$pautoresizemore = JRequest::getString('autoresizemore', '', 'request');
	$presizetomore = JRequest::getString('resizetomore', '', 'request');
	$punits = JRequest::getInt('units', '', 'request');
	$pimages = JRequest::getVar('cimgmore', null, 'files', 'array');
	$pfromadult = JRequest::getInt('fromadult', '', 'request');
	$ptoadult = JRequest::getInt('toadult', '', 'request');
	$pfromchild = JRequest::getInt('fromchild', '', 'request');
	$ptochild = JRequest::getInt('tochild', '', 'request');
	$ptotpeople = JRequest::getInt('totpeople', '', 'request');
	$pmintotpeople = JRequest::getInt('mintotpeople', '', 'request');
	$pmintotpeople = $pmintotpeople < 1 ? 1 : $pmintotpeople;
	$plastavail = JRequest::getString('lastavail', '', 'request');
	$plastavail = empty($plastavail) ? 0 : intval($plastavail);
	$pcustprice = JRequest::getString('custprice', '', 'request');
	$pcustprice = empty($pcustprice) ? '' : floatval($pcustprice);
	$pcustpricetxt = JRequest::getString('custpricetxt', '', 'request');
	$preqinfo = JRequest::getInt('reqinfo', '', 'request');
	$ppricecal = JRequest::getInt('pricecal', '', 'request');
	$pdefcalcost = JRequest::getString('defcalcost', '', 'request');
	$pmaxminpeople = JRequest::getString('maxminpeople', '', 'request');
	$pcimgcaption = JRequest::getVar('cimgcaption', array(0));
	$pmaxminpeople = in_array($pmaxminpeople, array('0', '1', '2', '3', '4', '5')) ? $pmaxminpeople : '0';
	$pseasoncal = JRequest::getInt('seasoncal', 0, 'request');
	$pseasoncal = $pseasoncal >= 0 || $pseasoncal <= 3 ? $pseasoncal : 0;
	$pseasoncal_nights = JRequest::getString('seasoncal_nights', '', 'request');
	$pseasoncal_prices = JRequest::getString('seasoncal_prices', '', 'request');
	$pseasoncal_restr = JRequest::getString('seasoncal_restr', '', 'request');
	$pmulti_units = JRequest::getInt('multi_units', '', 'request');
	$pmulti_units = $punits > 1 ? $pmulti_units : 0;
	$psefalias = JRequest::getString('sefalias', '', 'request');
	$psefalias = empty($psefalias) ? JFilterOutput::stringURLSafe($pcname) : JFilterOutput::stringURLSafe($psefalias);
	$pcustptitle = JRequest::getString('custptitle', '', 'request');
	$pcustptitlew = JRequest::getString('custptitlew', '', 'request');
	$pcustptitlew = in_array($pcustptitlew, array('before', 'after', 'replace')) ? $pcustptitlew : 'before';
	$pmetakeywords = JRequest::getString('metakeywords', '', 'request');
	$pmetadescription = JRequest::getString('metadescription', '', 'request');
	$scalnights_arr = array();
	if(!empty($pseasoncal_nights)) {
		$scalnights = explode(',', $pseasoncal_nights);
		foreach ($scalnights as $scalnight) {
			if(intval(trim($scalnight)) > 0) {
				$scalnights_arr[] = intval(trim($scalnight));
			}
		}
	}
	if(count($scalnights_arr) > 0) {
		$pseasoncal_nights = implode(', ', $scalnights_arr);
	}else {
		$pseasoncal_nights = '';
		$pseasoncal = 0;
	}
	$roomparams = array('lastavail' => $plastavail, 'custprice' => $pcustprice, 'custpricetxt' => $pcustpricetxt, 'reqinfo' => $preqinfo, 'pricecal' => $ppricecal, 'defcalcost' => floatval($pdefcalcost), 'maxminpeople' => $pmaxminpeople, 'seasoncal' => $pseasoncal, 'seasoncal_nights' => $pseasoncal_nights, 'seasoncal_prices' => $pseasoncal_prices, 'seasoncal_restr' => $pseasoncal_restr, 'multi_units' => $pmulti_units, 'custptitle' => $pcustptitle, 'custptitlew' => $pcustptitlew, 'metakeywords' => $pmetakeywords, 'metadescription' => $pmetadescription);
	//distinctive features
	$roomparams['features'] = array();
	if ($punits > 0) {
		for ($i=1; $i <= $punits; $i++) { 
			$distf_name = JRequest::getVar('feature-name'.$i, array(0));
			$distf_lang = JRequest::getVar('feature-lang'.$i, array(0));
			$distf_value = JRequest::getVar('feature-value'.$i, array(0));
			foreach ($distf_name as $distf_k => $distf) {
				if(strlen($distf) > 0 && strlen($distf_value[$distf_k]) > 0) {
					$use_key = strlen($distf_lang[$distf_k]) > 0 ? $distf_lang[$distf_k] : $distf;
					$roomparams['features'][$i][$use_key] = $distf_value[$distf_k];
				}
			}
		}
	}
	//
	$roomparamstr = json_encode($roomparams);
	jimport('joomla.filesystem.file');
	$updpath = JPATH_SITE.DS.'components'.DS.'com_vikbooking'.DS.'resources'.DS.'uploads'.DS;
	if (!empty($pcname)) {
		if (intval($_FILES['cimg']['error']) == 0 && caniWrite($updpath) && trim($_FILES['cimg']['name'])!="") {
			if (@is_uploaded_file($_FILES['cimg']['tmp_name'])) {
				$safename=JFile::makeSafe(str_replace(" ", "_", strtolower($_FILES['cimg']['name'])));
				if (file_exists($updpath.$safename)) {
					$j=1;
					while (file_exists($updpath.$j.$safename)) {
						$j++;
					}
					$pwhere=$updpath.$j.$safename;
				}else {
					$j="";
					$pwhere=$updpath.$safename;
				}
				@move_uploaded_file($_FILES['cimg']['tmp_name'], $pwhere);
				if(!getimagesize($pwhere)){
					@unlink($pwhere);
					$picon="";
				}else {
					@chmod($pwhere, 0644);
					$picon=$j.$safename;
					if($pautoresize=="1" && !empty($presizeto)) {
						$eforj = new vikResizer();
						$origmod = $eforj->proportionalImage($pwhere, $updpath.'r_'.$j.$safename, $presizeto, $presizeto);
						if($origmod) {
							@unlink($pwhere);
							$picon='r_'.$j.$safename;
						}
					}
				}
			}else {
				$picon="";
			}
		}else {
			$picon="";
		}
		//more images
		$creativik = new vikResizer();
		$bigsdest = $updpath;
		$thumbsdest = $updpath;
		$dest = $updpath;
		$moreimagestr="";
		$captiontexts = array();
		$imgcaptions = array();
		foreach($pimages['name'] as $kk=>$ci) {
			if(!empty($ci)) {
				$arrimgs[]=$kk;
				$captiontexts[] = $pcimgcaption[$kk];
			}
		}
		if (is_array($arrimgs)) {
			foreach($arrimgs as $ki => $imgk) {
				if(strlen(trim($pimages['name'][$imgk]))) {
					$filename = JFile::makeSafe(str_replace(" ", "_", strtolower($pimages['name'][$imgk])));
					$src = $pimages['tmp_name'][$imgk];
					$j="";
					if (file_exists($dest.$filename)) {
						$j=rand(171, 1717);
						while (file_exists($dest.$j.$filename)) {
							$j++;
						}
					}
					$finaldest=$dest.$j.$filename;
					$check=getimagesize($pimages['tmp_name'][$imgk]);
					if($check[2] & imagetypes()) {
						if (JFile::upload($src, $finaldest)) {
							$gimg=$j.$filename;
							//orig img
							$origmod = true;
							if($pautoresizemore == "1" && !empty($presizetomore)) {
								$origmod = $creativik->proportionalImage($finaldest, $bigsdest.'big_'.$j.$filename, $presizetomore, $presizetomore);
							}else {
								copy($finaldest, $bigsdest.'big_'.$j.$filename);
							}
							//thumb
							$thumb = $creativik->proportionalImage($finaldest, $thumbsdest.'thumb_'.$j.$filename, 70, 70);
							if (!$thumb || !$origmod) {
								if(file_exists($bigsdest.'big_'.$j.$filename)) @unlink($bigsdest.'big_'.$j.$filename);
								if(file_exists($thumbsdest.'thumb_'.$j.$filename)) @unlink($thumbsdest.'thumb_'.$j.$filename);
								JError::raiseWarning('', 'Error While Uploading the File: '.$pimages['name'][$imgk]);
							}else {
								$moreimagestr.=$j.$filename.";;";
								$imgcaptions[] = $captiontexts[$ki];
							}
							@unlink($finaldest);
						}else {
							JError::raiseWarning('', 'Error While Uploading the File: '.$pimages['name'][$imgk]);
						}
					}else {
						JError::raiseWarning('', 'Error While Uploading the File: '.$pimages['name'][$imgk]);
					}
				}
			}
		}
		//end more images
		if (!empty($pccat) && @count($pccat)) {
			foreach($pccat as $ccat){
				if(!empty($ccat)) {
					$pccatdef.=$ccat.";";
				}
			}
		}else {
			$pccatdef="";
		}
		if (!empty($pccarat) && @count($pccarat)) {
			foreach($pccarat as $ccarat){
				$pccaratdef.=$ccarat.";";
			}
		}else {
			$pccaratdef="";
		}
		if (!empty($pcoptional) && @count($pcoptional)) {
			foreach($pcoptional as $coptional){
				$pcoptionaldef.=$coptional.";";
			}
		}else {
			$pcoptionaldef="";
		}
		$pcavaildef=($pcavail=="yes" ? "1" : "0");
		if ($pfromadult > $ptoadult) {
			$pfromadult = 1;
			$ptoadult = 1;
		}
		if ($pfromchild > $ptochild) {
			$pfromchild = 1;
			$ptochild = 1;
		}
		$dbo = JFactory::getDBO();
		$q="INSERT INTO `#__vikbooking_rooms` (`name`,`img`,`idcat`,`idcarat`,`idopt`,`info`,`avail`,`units`,`moreimgs`,`fromadult`,`toadult`,`fromchild`,`tochild`,`smalldesc`,`totpeople`,`mintotpeople`,`params`,`imgcaptions`,`alias`) VALUES(".$dbo->quote($pcname).",".$dbo->quote($picon).",".$dbo->quote($pccatdef).",".$dbo->quote($pccaratdef).",".$dbo->quote($pcoptionaldef).",".$dbo->quote($pcdescr).",".$dbo->quote($pcavaildef).",".($punits > 0 ? $dbo->quote($punits) : "'1'").", ".$dbo->quote($moreimagestr).", '".$pfromadult."', '".$ptoadult."', '".$pfromchild."', '".$ptochild."', ".$dbo->quote($psmalldesc).", ".$ptotpeople.", ".$pmintotpeople.", ".$dbo->quote($roomparamstr).", ".$dbo->quote(json_encode($imgcaptions)).",".$dbo->quote($psefalias).");";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$lid = $dbo->insertid();
		if(!empty($lid)) {
			if($stay === true) {
				$mainframe = JFactory::getApplication();
				$mainframe->enqueueMessage(JText::_('VBOROOMSAVEOK').' - <a href="index.php?option=com_vikbooking&task=viewtariffe&cid[]='.$lid.'">'.JText::_('VBOGOTORATES').'</a>');
				$mainframe->redirect("index.php?option=".$option."&task=editroom&cid[]=".$lid);
				exit;
			}else {
				goViewTariffe($lid, $option);
			}
		}else {
			cancelEditing($option);
		}
	}else {
		cancelEditing($option);
	}
}

function updateRoom ($option, $stay = false) {
	$pcname = JRequest::getString('cname', '', 'request');
	$pccat = JRequest::getVar('ccat', array(0));
	$pcdescr = JRequest::getString('cdescr', '', 'request', JREQUEST_ALLOWRAW);
	$psmalldesc = JRequest::getString('smalldesc', '', 'request', JREQUEST_ALLOWRAW);
	$pccarat = JRequest::getVar('ccarat', array(0));
	$pcoptional = JRequest::getVar('coptional', array(0));
	$pcavail = JRequest::getString('cavail', '', 'request');
	$pwhereup = JRequest::getString('whereup', '', 'request');
	$pautoresize = JRequest::getString('autoresize', '', 'request');
	$presizeto = JRequest::getString('resizeto', '', 'request');
	$pautoresizemore = JRequest::getString('autoresizemore', '', 'request');
	$presizetomore = JRequest::getString('resizetomore', '', 'request');
	$punits = JRequest::getInt('units', '', 'request');
	$pimages = JRequest::getVar('cimgmore', null, 'files', 'array');
	$pactmoreimgs = JRequest::getString('actmoreimgs', '', 'request');
	$pfromadult = JRequest::getInt('fromadult', '', 'request');
	$ptoadult = JRequest::getInt('toadult', '', 'request');
	$pfromchild = JRequest::getInt('fromchild', '', 'request');
	$ptochild = JRequest::getInt('tochild', '', 'request');
	$padultsdiffchdisc = JRequest::getVar('adultsdiffchdisc', array(0));
	$padultsdiffval = JRequest::getVar('adultsdiffval', array(0));
	$padultsdiffnum = JRequest::getVar('adultsdiffnum', array(0));
	$padultsdiffvalpcent = JRequest::getVar('adultsdiffvalpcent', array(0));
	$padultsdiffpernight = JRequest::getVar('adultsdiffpernight', array(0));
	$ptotpeople = JRequest::getInt('totpeople', '', 'request');
	$pmintotpeople = JRequest::getInt('mintotpeople', '', 'request');
	$pmintotpeople = $pmintotpeople < 1 ? 1 : $pmintotpeople;
	$plastavail = JRequest::getString('lastavail', '', 'request');
	$plastavail = empty($plastavail) ? 0 : intval($plastavail);
	$pcustprice = JRequest::getString('custprice', '', 'request');
	$pcustprice = empty($pcustprice) ? '' : floatval($pcustprice);
	$pcustpricetxt = JRequest::getString('custpricetxt', '', 'request');
	$preqinfo = JRequest::getInt('reqinfo', '', 'request');
	$ppricecal = JRequest::getInt('pricecal', '', 'request');
	$pdefcalcost = JRequest::getString('defcalcost', '', 'request');
	$pmaxminpeople = JRequest::getString('maxminpeople', '', 'request');
	$pcimgcaption = JRequest::getVar('cimgcaption', array(0));
	$pupdatecaption = JRequest::getInt('updatecaption', '', 'request');
	$pmaxminpeople = in_array($pmaxminpeople, array('0', '1', '2', '3', '4', '5')) ? $pmaxminpeople : '0';
	$pseasoncal = JRequest::getInt('seasoncal', 0, 'request');
	$pseasoncal = $pseasoncal >= 0 || $pseasoncal <= 3 ? $pseasoncal : 0;
	$pseasoncal_nights = JRequest::getString('seasoncal_nights', '', 'request');
	$pseasoncal_prices = JRequest::getString('seasoncal_prices', '', 'request');
	$pseasoncal_restr = JRequest::getString('seasoncal_restr', '', 'request');
	$pmulti_units = JRequest::getInt('multi_units', '', 'request');
	$pmulti_units = $punits > 1 ? $pmulti_units : 0;
	$psefalias = JRequest::getString('sefalias', '', 'request');
	$psefalias = empty($psefalias) ? JFilterOutput::stringURLSafe($pcname) : JFilterOutput::stringURLSafe($psefalias);
	$pcustptitle = JRequest::getString('custptitle', '', 'request');
	$pcustptitlew = JRequest::getString('custptitlew', '', 'request');
	$pcustptitlew = in_array($pcustptitlew, array('before', 'after', 'replace')) ? $pcustptitlew : 'before';
	$pmetakeywords = JRequest::getString('metakeywords', '', 'request');
	$pmetadescription = JRequest::getString('metadescription', '', 'request');
	$scalnights_arr = array();
	if(!empty($pseasoncal_nights)) {
		$scalnights = explode(',', $pseasoncal_nights);
		foreach ($scalnights as $scalnight) {
			if(intval(trim($scalnight)) > 0) {
				$scalnights_arr[] = intval(trim($scalnight));
			}
		}
	}
	if(count($scalnights_arr) > 0) {
		$pseasoncal_nights = implode(', ', $scalnights_arr);
	}else {
		$pseasoncal_nights = '';
		$pseasoncal = 0;
	}
	$roomparams = array('lastavail' => $plastavail, 'custprice' => $pcustprice, 'custpricetxt' => $pcustpricetxt, 'reqinfo' => $preqinfo, 'pricecal' => $ppricecal, 'defcalcost' => floatval($pdefcalcost), 'maxminpeople' => $pmaxminpeople, 'seasoncal' => $pseasoncal, 'seasoncal_nights' => $pseasoncal_nights, 'seasoncal_prices' => $pseasoncal_prices, 'seasoncal_restr' => $pseasoncal_restr, 'multi_units' => $pmulti_units, 'custptitle' => $pcustptitle, 'custptitlew' => $pcustptitlew, 'metakeywords' => $pmetakeywords, 'metadescription' => $pmetadescription);
	//distinctive features
	$roomparams['features'] = array();
	$newfeatures = array();
	if ($punits > 0) {
		for ($i=1; $i <= $punits; $i++) { 
			$distf_name = JRequest::getVar('feature-name'.$i, array(0));
			$distf_lang = JRequest::getVar('feature-lang'.$i, array(0));
			$distf_value = JRequest::getVar('feature-value'.$i, array(0));
			foreach ($distf_name as $distf_k => $distf) {
				if(strlen($distf) > 0 && strlen($distf_value[$distf_k]) > 0) {
					$use_key = strlen($distf_lang[$distf_k]) > 0 ? $distf_lang[$distf_k] : $distf;
					$roomparams['features'][$i][$use_key] = $distf_value[$distf_k];
					if($distf_k < 1) {
						//check only the first feature
						$newfeatures[$i][$use_key] = $distf_value[$distf_k];
					}
				}
			}
		}
	}
	//
	$roomparamstr = json_encode($roomparams);
	jimport('joomla.filesystem.file');
	$updpath = JPATH_SITE.DS.'components'.DS.'com_vikbooking'.DS.'resources'.DS.'uploads'.DS;
	if (!empty($pcname)) {
		if (intval($_FILES['cimg']['error']) == 0 && caniWrite($updpath) && trim($_FILES['cimg']['name'])!="") {
			if (@is_uploaded_file($_FILES['cimg']['tmp_name'])) {
				$safename=JFile::makeSafe(str_replace(" ", "_", strtolower($_FILES['cimg']['name'])));
				if (file_exists($updpath.$safename)) {
					$j=1;
					while (file_exists($updpath.$j.$safename)) {
						$j++;
					}
					$pwhere=$updpath.$j.$safename;
				}else {
					$j="";
					$pwhere=$updpath.$safename;
				}
				@move_uploaded_file($_FILES['cimg']['tmp_name'], $pwhere);
				if(!getimagesize($pwhere)){
					@unlink($pwhere);
					$picon="";
				}else {
					@chmod($pwhere, 0644);
					$picon=$j.$safename;
					if($pautoresize=="1" && !empty($presizeto)) {
						$eforj = new vikResizer();
						$origmod = $eforj->proportionalImage($pwhere, $updpath.'r_'.$j.$safename, $presizeto, $presizeto);
						if($origmod) {
							@unlink($pwhere);
							$picon='r_'.$j.$safename;
						}
					}
				}
			}else {
				$picon="";
			}
		}else {
			$picon="";
		}
		//more images
		$creativik = new vikResizer();
		$bigsdest = $updpath;
		$thumbsdest = $updpath;
		$dest = $updpath;
		$moreimagestr=$pactmoreimgs;
		$captiontexts = array();
		$imgcaptions = array();
		//captions of uploaded extra images
		if(!empty($pactmoreimgs)) {
			$sploimgs = explode(';;', $pactmoreimgs);
			foreach ($sploimgs as $ki => $oimg) {
				if (!empty($oimg)) {
					$oldcaption = JRequest::getString('caption'.$ki, '', 'request', JREQUEST_ALLOWHTML);
					$imgcaptions[] = $oldcaption;
				}
			}
		}
		//
		foreach($pimages['name'] as $kk=>$ci) {
			if(!empty($ci)) {
				$arrimgs[]=$kk;
				$captiontexts[] = $pcimgcaption[$kk];
			}
		}
		if (@count($arrimgs) > 0) {
			foreach($arrimgs as $ki => $imgk) {
				if(strlen(trim($pimages['name'][$imgk]))) {
					$filename = JFile::makeSafe(str_replace(" ", "_", strtolower($pimages['name'][$imgk])));
					$src = $pimages['tmp_name'][$imgk];
					$j="";
					if (file_exists($dest.$filename)) {
						$j=rand(171, 1717);
						while (file_exists($dest.$j.$filename)) {
							$j++;
						}
					}
					$finaldest=$dest.$j.$filename;
					$check=getimagesize($pimages['tmp_name'][$imgk]);
					if($check[2] & imagetypes()) {
						if (JFile::upload($src, $finaldest)) {
							$gimg=$j.$filename;
							//orig img
							$origmod = true;
							if($pautoresizemore == "1" && !empty($presizetomore)) {
								$origmod = $creativik->proportionalImage($finaldest, $bigsdest.'big_'.$j.$filename, $presizetomore, $presizetomore);
							}else {
								copy($finaldest, $bigsdest.'big_'.$j.$filename);
							}
							//thumb
							$thumb = $creativik->proportionalImage($finaldest, $thumbsdest.'thumb_'.$j.$filename, 70, 70);
							if (!$thumb || !$origmod) {
								if(file_exists($bigsdest.'big_'.$j.$filename)) @unlink($bigsdest.'big_'.$j.$filename);
								if(file_exists($thumbsdest.'thumb_'.$j.$filename)) @unlink($thumbsdest.'thumb_'.$j.$filename);
								JError::raiseWarning('', 'Error While Uploading the File: '.$pimages['name'][$imgk]);
							}else {
								$moreimagestr.=$j.$filename.";;";
								$imgcaptions[] = $captiontexts[$ki];
							}
							@unlink($finaldest);
						}else {
							JError::raiseWarning('', 'Error While Uploading the File: '.$pimages['name'][$imgk]);
						}
					}else {
						JError::raiseWarning('', 'Error While Uploading the File: '.$pimages['name'][$imgk]);
					}
				}
			}
		}
		//end more images
		if (!empty($pccat) && @count($pccat)) {
			foreach($pccat as $ccat){
				if(!empty($ccat)) {
					$pccatdef.=$ccat.";";
				}
			}
		}else {
			$pccatdef="";
		}
		if (!empty($pccarat) && @count($pccarat)) {
			foreach($pccarat as $ccarat){
				$pccaratdef.=$ccarat.";";
			}
		}else {
			$pccaratdef="";
		}
		if (!empty($pcoptional) && @count($pcoptional)) {
			foreach($pcoptional as $coptional){
				$pcoptionaldef.=$coptional.";";
			}
		}else {
			$pcoptionaldef="";
		}
		$pcavaildef=($pcavail=="yes" ? "1" : "0");
		if ($pfromadult > $ptoadult) {
			$pfromadult = 1;
			$ptoadult = 1;
		}
		if ($pfromchild > $ptochild) {
			$pfromchild = 1;
			$ptochild = 1;
		}
		$dbo = JFactory::getDBO();
		//adults charges/discounts
		$adchdisctouch = false;
		$q="SELECT * FROM `#__vikbooking_rooms` WHERE `id`='".$pwhereup."';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$oldroom = $dbo->loadAssocList();
		$oldroom = $oldroom[0];
		if ($oldroom['fromadult'] == $pfromadult && $oldroom['toadult'] == $ptoadult) {
			if ($oldroom['toadult'] > 1 && $oldroom['fromadult'] < $oldroom['toadult'] && @count($padultsdiffnum) > 0) {
				$startadind = $oldroom['fromadult'] > 0 ? $oldroom['fromadult'] : 1;
				for($adi = $startadind; $adi <= $oldroom['toadult']; $adi++) {
					foreach($padultsdiffnum as $kad=>$vad) {
						if (intval($vad) == intval($adi) && strlen($padultsdiffval[$kad]) > 0) {
							$adchdisctouch = true;
							$inschdisc = intval($padultsdiffchdisc[$kad]) == 1 ? 1 : 2;
							$insvalpcent = intval($padultsdiffvalpcent[$kad]) == 1 ? 1 : 2;
							$inspernight = intval($padultsdiffpernight[$kad]) == 1 ? 1 : 0;
							$insvalue = floatval($padultsdiffval[$kad]);
							//check if it exists
							$q="SELECT `id` FROM `#__vikbooking_adultsdiff` WHERE `idroom`='".$oldroom['id']."' AND `adults`='".$adi."';";
							$dbo->setQuery($q);
							$dbo->Query($q);
							if ($dbo->getNumRows() > 0) {
								if ($insvalue > 0) {
									//update
									$q="UPDATE `#__vikbooking_adultsdiff` SET `chdisc`='".$inschdisc."', `valpcent`='".$insvalpcent."', `value`='".$insvalue."', `pernight`='".$inspernight."' WHERE `idroom`='".$oldroom['id']."' AND `adults`='".$adi."';";
									$dbo->setQuery($q);
									$dbo->Query($q);
								}else {
									//delete
									$q="DELETE FROM `#__vikbooking_adultsdiff` WHERE `idroom`='".$oldroom['id']."' AND `adults`='".$adi."';";
									$dbo->setQuery($q);
									$dbo->Query($q);
								}
							}else {
								//insert
								$q="INSERT INTO `#__vikbooking_adultsdiff` (`idroom`,`chdisc`,`valpcent`,`value`,`adults`,`pernight`) VALUES('".$oldroom['id']."', '".$inschdisc."', '".$insvalpcent."', '".$insvalue."', '".$adi."', '".$inspernight."');";
								$dbo->setQuery($q);
								$dbo->Query($q);
							}
						}
					}
				}
			}
		}else {
			//min and max adults num have changed, delete
			$q="DELETE FROM `#__vikbooking_adultsdiff` WHERE `idroom`='".$oldroom['id']."';";
			$dbo->setQuery($q);
			$dbo->Query($q);
		}
		if ($adchdisctouch == true) {
			$app = JFactory::getApplication();
			$app->enqueueMessage(JText::_('VBUPDROOMADCHDISCSAVED'));
		}
		//
		//check distinctive features if there were any changes
		$old_rparams = json_decode($oldroom['params'], true);
		if(array_key_exists('features', $old_rparams)) {
			$oldfeatures = array();
			foreach ($old_rparams['features'] as $rnumunit => $oldfeat) {
				foreach ($oldfeat as $featname => $featval) {
					$oldfeatures[$rnumunit][$featname] = $featval;
					break;
				}
			}
			if($oldfeatures != $newfeatures) {
				//changes were made to the first index (Room Number by default) of the distinctive features
				//set to NULL all the already set roomindexes in bookings
				$q = "UPDATE `#__vikbooking_ordersrooms` SET `roomindex`=NULL WHERE `idroom`=".(int)$oldroom['id'].";";
				$dbo->setQuery($q);
				$dbo->Query($q);
			}
		}
		//
		$q="UPDATE `#__vikbooking_rooms` SET `name`=".$dbo->quote($pcname).",".(strlen($picon) > 0 ? "`img`='".$picon."'," : "")."`idcat`=".$dbo->quote($pccatdef).",`idcarat`=".$dbo->quote($pccaratdef).",`idopt`=".$dbo->quote($pcoptionaldef).",`info`=".$dbo->quote($pcdescr).",`avail`=".$dbo->quote($pcavaildef).",`units`=".($punits > 0 ? $dbo->quote($punits) : "'1'").",`moreimgs`=".$dbo->quote($moreimagestr).",`fromadult`='".$pfromadult."',`toadult`='".$ptoadult."',`fromchild`='".$pfromchild."',`tochild`='".$ptochild."',`smalldesc`=".$dbo->quote($psmalldesc).",`totpeople`=".$ptotpeople.",`mintotpeople`=".$pmintotpeople.",`params`=".$dbo->quote($roomparamstr).",`imgcaptions`=".$dbo->quote(json_encode($imgcaptions)).",`alias`=".$dbo->quote($psefalias)." WHERE `id`=".$dbo->quote($pwhereup).";";
		$dbo->setQuery($q);
		$dbo->Query($q);
	}
	$mainframe = JFactory::getApplication();
	$mainframe->enqueueMessage(JText::_('VBUPDROOMOK'));
	if($pupdatecaption == 1 || $stay === true) {
		$mainframe->redirect("index.php?option=".$option."&task=editroom&cid[]=".$pwhereup);
	}else {
		cancelEditing($option);
	}
}

function goViewTariffe ($aid, $option) {
	$mainframe = JFactory::getApplication();
	$mainframe->redirect("index.php?option=".$option."&task=viewtariffe&cid[]=".$aid);
}

function viewIva ($option) {
	$dbo = JFactory::getDBO();
	$mainframe = JFactory::getApplication();
	$lim = $mainframe->getUserStateFromRequest("$option.limit", 'limit', $mainframe->getCfg('list_limit'), 'int');
	$lim0 = JRequest::getVar('limitstart', 0, '', 'int');
	$q="SELECT SQL_CALC_FOUND_ROWS * FROM `#__vikbooking_iva`";
	$dbo->setQuery($q, $lim0, $lim);
	$dbo->Query($q);
	if ($dbo->getNumRows() > 0) {
		$rows = $dbo->loadAssocList();
		$dbo->setQuery('SELECT FOUND_ROWS();');
		jimport('joomla.html.pagination');
		$pageNav = new JPagination( $dbo->loadResult(), $lim0, $lim );
		$navbut="<table align=\"center\"><tr><td>".$pageNav->getListFooter()."</td></tr></table>";
		HTML_vikbooking::pViewIva($rows, $option, $lim0, $navbut);
	}else {
		$rows = "";
		HTML_vikbooking::pViewIva($rows, $option);
	}
}

function viewCategories ($option) {
	$dbo = JFactory::getDBO();
	$mainframe = JFactory::getApplication();
	$lim = $mainframe->getUserStateFromRequest("$option.limit", 'limit', $mainframe->getCfg('list_limit'), 'int');
	$lim0 = JRequest::getVar('limitstart', 0, '', 'int');
	$q="SELECT SQL_CALC_FOUND_ROWS * FROM `#__vikbooking_categories` ORDER BY `#__vikbooking_categories`.`name` ASC";
	$dbo->setQuery($q, $lim0, $lim);
	$dbo->Query($q);
	if ($dbo->getNumRows() > 0) {
		$rows = $dbo->loadAssocList();
		$dbo->setQuery('SELECT FOUND_ROWS();');
		jimport('joomla.html.pagination');
		$pageNav = new JPagination( $dbo->loadResult(), $lim0, $lim );
		$navbut="<table align=\"center\"><tr><td>".$pageNav->getListFooter()."</td></tr></table>";
		HTML_vikbooking::pViewCategories($rows, $option, $lim0, $navbut);
	}else {
		$rows = "";
		HTML_vikbooking::pViewCategories($rows, $option);
	}
}

function viewCarat ($option) {
	$dbo = JFactory::getDBO();
	$mainframe = JFactory::getApplication();
	$lim = $mainframe->getUserStateFromRequest("$option.limit", 'limit', $mainframe->getCfg('list_limit'), 'int');
	$lim0 = JRequest::getVar('limitstart', 0, '', 'int');
	$q="SELECT SQL_CALC_FOUND_ROWS * FROM `#__vikbooking_characteristics`";
	$dbo->setQuery($q, $lim0, $lim);
	$dbo->Query($q);
	if ($dbo->getNumRows() > 0) {
		$rows = $dbo->loadAssocList();
		$dbo->setQuery('SELECT FOUND_ROWS();');
		jimport('joomla.html.pagination');
		$pageNav = new JPagination( $dbo->loadResult(), $lim0, $lim );
		$navbut="<table align=\"center\"><tr><td>".$pageNav->getListFooter()."</td></tr></table>";
		HTML_vikbooking::pViewCarat($rows, $option, $lim0, $navbut);
	}else {
		$rows = "";
		HTML_vikbooking::pViewCarat($rows, $option);
	}
}

function viewOptionals ($option) {
	$dbo = JFactory::getDBO();
	$mainframe = JFactory::getApplication();
	$lim = $mainframe->getUserStateFromRequest("$option.limit", 'limit', $mainframe->getCfg('list_limit'), 'int');
	$lim0 = JRequest::getVar('limitstart', 0, '', 'int');
	$q="SELECT SQL_CALC_FOUND_ROWS * FROM `#__vikbooking_optionals` ORDER BY `#__vikbooking_optionals`.`ordering` ASC";
	$dbo->setQuery($q, $lim0, $lim);
	$dbo->Query($q);
	if ($dbo->getNumRows() > 0) {
		$rows = $dbo->loadAssocList();
		$dbo->setQuery('SELECT FOUND_ROWS();');
		jimport('joomla.html.pagination');
		$pageNav = new JPagination( $dbo->loadResult(), $lim0, $lim );
		$navbut="<table align=\"center\"><tr><td>".$pageNav->getListFooter()."</td></tr></table>";
		HTML_vikbooking::pViewOptionals($rows, $option, $lim0, $navbut);
	}else {
		$rows = "";
		HTML_vikbooking::pViewOptionals($rows, $option);
	}
}

function viewPrices ($option) {
	$dbo = JFactory::getDBO();
	$mainframe = JFactory::getApplication();
	$lim = $mainframe->getUserStateFromRequest("$option.limit", 'limit', $mainframe->getCfg('list_limit'), 'int');
	$lim0 = JRequest::getVar('limitstart', 0, '', 'int');
	$q="SELECT SQL_CALC_FOUND_ROWS * FROM `#__vikbooking_prices`";
	$dbo->setQuery($q, $lim0, $lim);
	$dbo->Query($q);
	if ($dbo->getNumRows() > 0) {
		$rows = $dbo->loadAssocList();
		$dbo->setQuery('SELECT FOUND_ROWS();');
		jimport('joomla.html.pagination');
		$pageNav = new JPagination( $dbo->loadResult(), $lim0, $lim );
		$navbut="<table align=\"center\"><tr><td>".$pageNav->getListFooter()."</td></tr></table>";
		HTML_vikbooking::pViewPrices($rows, $option, $lim0, $navbut);
	}else {
		$rows = "";
		HTML_vikbooking::pViewPrices($rows, $option);
	}
}

function editPrice ($id, $option) {
	$dbo = JFactory::getDBO();
	$q="SELECT * FROM `#__vikbooking_prices` WHERE `id`='".$id."';";
	$dbo->setQuery($q);
	$dbo->Query($q);
	if ($dbo->getNumRows() == 1) {
		$rows = $dbo->loadAssocList();
		HTML_vikbooking::pEditPrice($rows[0], $option);
	}else {
		cancelEditingPrice($option);
	}
}

function editIva ($id, $option) {
	$dbo = JFactory::getDBO();
	$q="SELECT * FROM `#__vikbooking_iva` WHERE `id`='".$id."';";
	$dbo->setQuery($q);
	$dbo->Query($q);
	if ($dbo->getNumRows() == 1) {
		$rows = $dbo->loadAssocList();
		HTML_vikbooking::pEditIva($rows[0], $option);
	}else {
		cancelEditingIva($option);
	}
}

function editCat ($id, $option) {
	$dbo = JFactory::getDBO();
	$q="SELECT * FROM `#__vikbooking_categories` WHERE `id`='".$id."';";
	$dbo->setQuery($q);
	$dbo->Query($q);
	if ($dbo->getNumRows() == 1) {
		$rows = $dbo->loadAssocList();
		HTML_vikbooking::pEditCat($rows[0], $option);
	}else {
		cancelEditingCat($option);
	}
}

function editCarat ($id, $option) {
	$dbo = JFactory::getDBO();
	$q="SELECT * FROM `#__vikbooking_characteristics` WHERE `id`='".$id."';";
	$dbo->setQuery($q);
	$dbo->Query($q);
	if ($dbo->getNumRows() == 1) {
		$rows = $dbo->loadAssocList();
		HTML_vikbooking::pEditCarat($rows[0], $option);
	}else {
		cancelEditingCarat($option);
	}
}

function editOptional ($id, $option) {
	$dbo = JFactory::getDBO();
	$q="SELECT * FROM `#__vikbooking_optionals` WHERE `id`='".$id."';";
	$dbo->setQuery($q);
	$dbo->Query($q);
	if ($dbo->getNumRows() == 1) {
		$rows = $dbo->loadAssocList();
		$tot_rooms_options = 0;
		$q = "SELECT COUNT(*) FROM `#__vikbooking_rooms`;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$tot_rooms = (int)$dbo->loadResult();
		$q = "SELECT `idopt` FROM `#__vikbooking_rooms` WHERE `idopt` LIKE ".$dbo->quote("%".$rows[0]['id'].";%").";";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if($dbo->getNumRows() > 0) {
			$all_opt = $dbo->loadAssocList();
			foreach ($all_opt as $k => $v) {
				$opt_parts = explode(';', $v['idopt']);
				if(in_array((string)$rows[0]['id'], $opt_parts)) {
					$tot_rooms_options++;
				}
			}
		}
		HTML_vikbooking::pEditOptional($rows[0], $tot_rooms, $tot_rooms_options, $option);
	}else {
		cancelEditingOptionals($option);
	}
}

function editRoom ($id, $option) {
	$dbo = JFactory::getDBO();
	$q="SELECT * FROM `#__vikbooking_rooms` WHERE `id`='".$id."';";
	$dbo->setQuery($q);
	$dbo->Query($q);
	if ($dbo->getNumRows() == 1) {
		$rows = $dbo->loadAssocList();
		$q="SELECT * FROM `#__vikbooking_categories`;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$cats=($dbo->getNumRows() > 0 ? $dbo->loadAssocList() : "");
		$q="SELECT * FROM `#__vikbooking_characteristics`;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$carats=($dbo->getNumRows() > 0 ? $dbo->loadAssocList() : "");
		$q="SELECT * FROM `#__vikbooking_optionals` ORDER BY `#__vikbooking_optionals`.`ordering` ASC;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$optionals=($dbo->getNumRows() > 0 ? $dbo->loadAssocList() : "");
		$q="SELECT * FROM `#__vikbooking_adultsdiff` WHERE `idroom`='".$id."';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$adultsdiff=$dbo->getNumRows() > 0 ? $dbo->loadAssocList() : "";
		HTML_vikbooking::pEditRoom($rows[0], $cats, $carats, $optionals, $adultsdiff, $option);
	}else {
		cancelEditing($option);
	}
}

function newIva ($option) {
	HTML_vikbooking::pNewIva($option);
}

function newPrice ($option) {
	HTML_vikbooking::pNewPrice($option);
}

function newCat ($option) {
	HTML_vikbooking::pNewCat($option);
}

function newCarat ($option) {
	HTML_vikbooking::pNewCarat($option);
}

function newOptionals ($option) {
	HTML_vikbooking::pNewOptionals($option);
}

function newRoom ($option) {
	$dbo = JFactory::getDBO();
	$q="SELECT * FROM `#__vikbooking_categories`;";
	$dbo->setQuery($q);
	$dbo->Query($q);
	$cats=($dbo->getNumRows() > 0 ? $dbo->loadAssocList() : "");
	$q="SELECT * FROM `#__vikbooking_characteristics`;";
	$dbo->setQuery($q);
	$dbo->Query($q);
	$carats=($dbo->getNumRows() > 0 ? $dbo->loadAssocList() : "");
	$q="SELECT * FROM `#__vikbooking_optionals` ORDER BY `#__vikbooking_optionals`.`ordering` ASC;";
	$dbo->setQuery($q);
	$dbo->Query($q);
	$optionals=($dbo->getNumRows() > 0 ? $dbo->loadAssocList() : "");
	HTML_vikbooking::pNewRoom($cats, $carats, $optionals, $option);
}

function saveIva ($option) {
	$paliqname = JRequest::getString('aliqname', '', 'request');
	$paliqperc = JRequest::getString('aliqperc', '', 'request');
	$pbreakdown_name = JRequest::getVar('breakdown_name', array());
	$pbreakdown_rate = JRequest::getVar('breakdown_rate', array());
	if (!empty($paliqperc)) {
		$dbo = JFactory::getDBO();
		$breakdown_str = '';
		if(count($pbreakdown_name) > 0) {
			$breakdown_values = array();
			$bkcount = 0;
			$tot_sub_aliq = 0;
			foreach ($pbreakdown_name as $key => $subtax) {
				if(!empty($subtax) && floatval($pbreakdown_rate[$key]) > 0) {
					$breakdown_values[$bkcount]['name'] = $subtax;
					$breakdown_values[$bkcount]['aliq'] = (float)$pbreakdown_rate[$key];
					$tot_sub_aliq += (float)$pbreakdown_rate[$key];
					$bkcount++;
				}
			}
			if(count($breakdown_values) > 0) {
				$breakdown_str = json_encode($breakdown_values);
				if($tot_sub_aliq < (float)$paliqperc || $tot_sub_aliq > (float)$paliqperc) {
					JError::raiseWarning('', JText::_('VBOTAXBKDWNERRNOMATCH'));
				}
			}
		}
		$q="INSERT INTO `#__vikbooking_iva` (`name`,`aliq`,`breakdown`) VALUES(".$dbo->quote($paliqname).", ".$dbo->quote($paliqperc).", ".(empty($breakdown_str) ? 'NULL' : $dbo->quote($breakdown_str)).");";
		$dbo->setQuery($q);
		$dbo->Query($q);
	}
	cancelEditingIva($option);
}

function updateIva ($option) {
	$paliqname = JRequest::getString('aliqname', '', 'request');
	$paliqperc = JRequest::getString('aliqperc', '', 'request');
	$pbreakdown_name = JRequest::getVar('breakdown_name', array());
	$pbreakdown_rate = JRequest::getVar('breakdown_rate', array());
	$pwhereup = JRequest::getString('whereup', '', 'request');
	if (!empty($paliqperc)) {
		$dbo = JFactory::getDBO();
		$breakdown_str = '';
		if(count($pbreakdown_name) > 0) {
			$breakdown_values = array();
			$bkcount = 0;
			$tot_sub_aliq = 0;
			foreach ($pbreakdown_name as $key => $subtax) {
				if(!empty($subtax) && floatval($pbreakdown_rate[$key]) > 0) {
					$breakdown_values[$bkcount]['name'] = $subtax;
					$breakdown_values[$bkcount]['aliq'] = (float)$pbreakdown_rate[$key];
					$tot_sub_aliq += (float)$pbreakdown_rate[$key];
					$bkcount++;
				}
			}
			if(count($breakdown_values) > 0) {
				$breakdown_str = json_encode($breakdown_values);
				if($tot_sub_aliq < (float)$paliqperc || $tot_sub_aliq > (float)$paliqperc) {
					JError::raiseWarning('', JText::_('VBOTAXBKDWNERRNOMATCH'));
				}
			}
		}
		$q="UPDATE `#__vikbooking_iva` SET `name`=".$dbo->quote($paliqname).",`aliq`=".$dbo->quote($paliqperc).",`breakdown`=".(empty($breakdown_str) ? 'NULL' : $dbo->quote($breakdown_str))." WHERE `id`=".$dbo->quote($pwhereup).";";
		$dbo->setQuery($q);
		$dbo->Query($q);
	}
	cancelEditingIva($option);
}

function savePrice ($option) {
	$pprice = JRequest::getString('price', '', 'request');
	$pattr = JRequest::getString('attr', '', 'request');
	$ppraliq = JRequest::getString('praliq', '', 'request');
	$pbreakfast_included = JRequest::getInt('breakfast_included', '', 'request');
	$pbreakfast_included = $pbreakfast_included == 1 ? 1 : 0;
	$pfree_cancellation = JRequest::getInt('free_cancellation', '', 'request');
	$pfree_cancellation = $pfree_cancellation == 1 ? 1 : 0;
	$pcanc_deadline = JRequest::getInt('canc_deadline', '', 'request');
	if (!empty($pprice)) {
		$dbo = JFactory::getDBO();
		$q="INSERT INTO `#__vikbooking_prices` (`name`,`attr`,`idiva`,`breakfast_included`,`free_cancellation`,`canc_deadline`) VALUES(".$dbo->quote($pprice).", ".$dbo->quote($pattr).", ".$dbo->quote($ppraliq).", ".$pbreakfast_included.", ".$pfree_cancellation.", ".$pcanc_deadline.");";
		$dbo->setQuery($q);
		$dbo->Query($q);
	}
	cancelEditingPrice($option);
}

function updatePrice ($option) {
	$pprice = JRequest::getString('price', '', 'request');
	$pattr = JRequest::getString('attr', '', 'request');
	$ppraliq = JRequest::getString('praliq', '', 'request');
	$pbreakfast_included = JRequest::getInt('breakfast_included', '', 'request');
	$pbreakfast_included = $pbreakfast_included == 1 ? 1 : 0;
	$pfree_cancellation = JRequest::getInt('free_cancellation', '', 'request');
	$pfree_cancellation = $pfree_cancellation == 1 ? 1 : 0;
	$pcanc_deadline = JRequest::getInt('canc_deadline', '', 'request');
	$pwhereup = JRequest::getString('whereup', '', 'request');
	if (!empty($pprice)) {
		$dbo = JFactory::getDBO();
		$q="UPDATE `#__vikbooking_prices` SET `name`=".$dbo->quote($pprice).",`attr`=".$dbo->quote($pattr).",`idiva`=".$dbo->quote($ppraliq).",`breakfast_included`=".$pbreakfast_included.",`free_cancellation`=".$pfree_cancellation.",`canc_deadline`=".$pcanc_deadline." WHERE `id`=".$dbo->quote($pwhereup).";";
		$dbo->setQuery($q);
		$dbo->Query($q);
	}
	cancelEditingPrice($option);
}

function saveCat ($option) {
	$pcatname = JRequest::getString('catname', '', 'request');
	$pdescr = JRequest::getString('descr', '', 'request', JREQUEST_ALLOWHTML);
	if (!empty($pcatname)) {
		$dbo = JFactory::getDBO();
		$q="INSERT INTO `#__vikbooking_categories` (`name`,`descr`) VALUES(".$dbo->quote($pcatname).", ".$dbo->quote($pdescr).");";
		$dbo->setQuery($q);
		$dbo->Query($q);
	}
	cancelEditingCat($option);
}

function updateCat ($option) {
	$pcatname = JRequest::getString('catname', '', 'request');
	$pdescr = JRequest::getString('descr', '', 'request', JREQUEST_ALLOWHTML);
	$pwhereup = JRequest::getString('whereup', '', 'request');
	if (!empty($pcatname)) {
		$dbo = JFactory::getDBO();
		$q="UPDATE `#__vikbooking_categories` SET `name`=".$dbo->quote($pcatname).", `descr`=".$dbo->quote($pdescr)." WHERE `id`=".$dbo->quote($pwhereup).";";
		$dbo->setQuery($q);
		$dbo->Query($q);
	}
	cancelEditingCat($option);
}

function saveCarat ($option) {
	$pcaratname = JRequest::getString('caratname', '', 'request');
	$pcarattextimg = JRequest::getString('carattextimg', '', 'request');
	$pautoresize = JRequest::getString('autoresize', '', 'request');
	$presizeto = JRequest::getString('resizeto', '', 'request');
	if (!empty($pcaratname)) {
		if (intval($_FILES['caraticon']['error']) == 0 && caniWrite(JPATH_SITE.DS.'components'.DS.'com_vikbooking'.DS.'resources'.DS.'uploads'.DS) && trim($_FILES['caraticon']['name'])!="") {
			jimport('joomla.filesystem.file');
			$updpath = JPATH_SITE.DS.'components'.DS.'com_vikbooking'.DS.'resources'.DS.'uploads'.DS;
			if (@is_uploaded_file($_FILES['caraticon']['tmp_name'])) {
				$safename=JFile::makeSafe(str_replace(" ", "_", strtolower($_FILES['caraticon']['name'])));
				if (file_exists($updpath.$safename)) {
					$j=1;
					while (file_exists($updpath.$j.$safename)) {
						$j++;
					}
					$pwhere=$updpath.$j.$safename;
				}else {
					$j="";
					$pwhere=$updpath.$safename;
				}
				@move_uploaded_file($_FILES['caraticon']['tmp_name'], $pwhere);
				if(!getimagesize($pwhere)){
					@unlink($pwhere);
					$picon="";
				}else {
					@chmod($pwhere, 0644);
					$picon=$j.$safename;
					if($pautoresize=="1" && !empty($presizeto)) {
						$eforj = new vikResizer();
						$origmod = $eforj->proportionalImage($pwhere, $updpath.'r_'.$j.$safename, $presizeto, $presizeto);
						if($origmod) {
							@unlink($pwhere);
							$picon='r_'.$j.$safename;
						}
					}
				}
			}else {
				$picon="";
			}
		}else {
			$picon="";
		}
		$dbo = JFactory::getDBO();
		$q="INSERT INTO `#__vikbooking_characteristics` (`name`,`icon`,`textimg`) VALUES(".$dbo->quote($pcaratname).", ".$dbo->quote($picon).", ".$dbo->quote($pcarattextimg).");";
		$dbo->setQuery($q);
		$dbo->Query($q);
	}
	cancelEditingCarat($option);
}

function updateCarat ($option) {
	$pcaratname = JRequest::getString('caratname', '', 'request');
	$pcarattextimg = JRequest::getString('carattextimg', '', 'request');
	$pwhereup = JRequest::getString('whereup', '', 'request');
	$pautoresize = JRequest::getString('autoresize', '', 'request');
	$presizeto = JRequest::getString('resizeto', '', 'request');
	if (!empty($pcaratname)) {
		if (intval($_FILES['caraticon']['error']) == 0 && caniWrite(JPATH_SITE.DS.'components'.DS.'com_vikbooking'.DS.'resources'.DS.'uploads'.DS) && trim($_FILES['caraticon']['name'])!="") {
			jimport('joomla.filesystem.file');
			$updpath = JPATH_SITE.DS.'components'.DS.'com_vikbooking'.DS.'resources'.DS.'uploads'.DS;
			if (@is_uploaded_file($_FILES['caraticon']['tmp_name'])) {
				$safename=JFile::makeSafe(str_replace(" ", "_", strtolower($_FILES['caraticon']['name'])));
				if (file_exists($updpath.$safename)) {
					$j=1;
					while (file_exists($updpath.$j.$safename)) {
						$j++;
					}
					$pwhere=$updpath.$j.$safename;
				}else {
					$j="";
					$pwhere=$updpath.$safename;
				}
				@move_uploaded_file($_FILES['caraticon']['tmp_name'], $pwhere);
				if(!getimagesize($pwhere)){
					@unlink($pwhere);
					$picon="";
				}else {
					@chmod($pwhere, 0644);
					$picon=$j.$safename;
					if($pautoresize=="1" && !empty($presizeto)) {
						$eforj = new vikResizer();
						$origmod = $eforj->proportionalImage($pwhere, $updpath.'r_'.$j.$safename, $presizeto, $presizeto);
						if($origmod) {
							@unlink($pwhere);
							$picon='r_'.$j.$safename;
						}
					}
				}
			}else {
				$picon="";
			}
		}else {
			$picon="";
		}
		$dbo = JFactory::getDBO();
		$q="UPDATE `#__vikbooking_characteristics` SET `name`=".$dbo->quote($pcaratname).",".(strlen($picon) > 0 ? "`icon`='".$picon."'," : "")."`textimg`=".$dbo->quote($pcarattextimg)." WHERE `id`=".$dbo->quote($pwhereup).";";
		$dbo->setQuery($q);
		$dbo->Query($q);
	}
	cancelEditingCarat($option);
}

function saveOptionals ($option) {
	$poptname = JRequest::getString('optname', '', 'request');
	$poptdescr = JRequest::getString('optdescr', '', 'request', JREQUEST_ALLOWHTML);
	$poptcost = JRequest::getString('optcost', '', 'request');
	$poptperday = JRequest::getString('optperday', '', 'request');
	$poptperperson = JRequest::getString('optperperson', '', 'request');
	$pmaxprice = JRequest::getString('maxprice', '', 'request');
	$popthmany = JRequest::getString('opthmany', '', 'request');
	$poptaliq = JRequest::getString('optaliq', '', 'request');
	$pautoresize = JRequest::getString('autoresize', '', 'request');
	$presizeto = JRequest::getString('resizeto', '', 'request');
	$pifchildren = JRequest::getString('ifchildren', '', 'request');
	$pifchildren = $pifchildren == "1" ? 1 : 0;
	$pmaxquant = JRequest::getString('maxquant', '', 'request');
	$pmaxquant = empty($pmaxquant) ? 0 : intval($pmaxquant);
	$pforcesel = JRequest::getString('forcesel', '', 'request');
	$pforceval = JRequest::getString('forceval', '', 'request');
	$pforcevalperday = JRequest::getString('forcevalperday', '', 'request');
	$pforcevalperchild = JRequest::getString('forcevalperchild', '', 'request');
	$pforcesummary = JRequest::getString('forcesummary', '', 'request');
	$pforcesel = $pforcesel == "1" ? 1 : 0;
	$pis_citytax = JRequest::getString('is_citytax', '', 'request');
	$pis_fee = JRequest::getString('is_fee', '', 'request');
	$pis_citytax = $pis_citytax == "1" && $pis_fee != "1" ? 1 : 0;
	$pis_fee = $pis_fee == "1" && $pis_citytax == 0 ? 1 : 0;
	$pagefrom = JRequest::getVar('agefrom', array(0));
	$pageto = JRequest::getVar('ageto', array(0));
	$pagecost = JRequest::getVar('agecost', array(0));
	if($pforcesel == 1) {
		$strforceval = intval($pforceval)."-".($pforcevalperday == "1" ? "1" : "0")."-".($pforcevalperchild == "1" ? "1" : "0")."-".($pforcesummary == "1" ? "1" : "0");
	}else {
		$strforceval = "";
	}
	if (!empty($poptname)) {
		if (intval($_FILES['optimg']['error']) == 0 && caniWrite(JPATH_SITE.DS.'components'.DS.'com_vikbooking'.DS.'resources'.DS.'uploads'.DS) && trim($_FILES['optimg']['name'])!="") {
			jimport('joomla.filesystem.file');
			$updpath = JPATH_SITE.DS.'components'.DS.'com_vikbooking'.DS.'resources'.DS.'uploads'.DS;
			if (@is_uploaded_file($_FILES['optimg']['tmp_name'])) {
				$safename=JFile::makeSafe(str_replace(" ", "_", strtolower($_FILES['optimg']['name'])));
				if (file_exists($updpath.$safename)) {
					$j=1;
					while (file_exists($updpath.$j.$safename)) {
						$j++;
					}
					$pwhere=$updpath.$j.$safename;
				}else {
					$j="";
					$pwhere=$updpath.$safename;
				}
				@move_uploaded_file($_FILES['optimg']['tmp_name'], $pwhere);
				if(!getimagesize($pwhere)){
					@unlink($pwhere);
					$picon="";
				}else {
					@chmod($pwhere, 0644);
					$picon=$j.$safename;
					if($pautoresize=="1" && !empty($presizeto)) {
						$eforj = new vikResizer();
						$origmod = $eforj->proportionalImage($pwhere, $updpath.'r_'.$j.$safename, $presizeto, $presizeto);
						if($origmod) {
							@unlink($pwhere);
							$picon='r_'.$j.$safename;
						}
					}
				}
			}else {
				$picon="";
			}
		}else {
			$picon="";
		}
		$poptperday=($poptperday=="each" ? "1" : "0");
		$poptperperson=($poptperperson=="each" ? "1" : "0");
		($popthmany=="yes" ? $popthmany="1" : $popthmany="0");
		$ageintervalstr = '';
		if ($pifchildren == 1 && count($pagefrom) > 0 && count($pagecost) > 0 && count($pagefrom) == count($pagecost)) {
			foreach($pagefrom as $kage => $vage) {
				$afrom = intval($vage);
				$ato = intval($pageto[$kage]);
				$acost = floatval($pagecost[$kage]);
				if (strlen($vage) > 0 && strlen($pagecost[$kage]) > 0) {
					if ($ato < $afrom) $ato = $afrom;
					$ageintervalstr .= $afrom.'_'.$ato.'_'.$acost.';;';
				}
			}
			$ageintervalstr = rtrim($ageintervalstr, ';;');
			if(!empty($ageintervalstr)) {
				$pforcesel = 1;
			}
		}
		$dbo = JFactory::getDBO();
		$q="SELECT `ordering` FROM `#__vikbooking_optionals` ORDER BY `#__vikbooking_optionals`.`ordering` DESC LIMIT 1;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if($dbo->getNumRows() == 1) {
			$getlast=$dbo->loadResult();
			$newsortnum=$getlast + 1;
		}else {
			$newsortnum=1;
		}
		$q="INSERT INTO `#__vikbooking_optionals` (`name`,`descr`,`cost`,`perday`,`hmany`,`img`,`idiva`,`maxprice`,`forcesel`,`forceval`,`perperson`,`ifchildren`,`maxquant`,`ordering`,`ageintervals`,`is_citytax`,`is_fee`) VALUES(".$dbo->quote($poptname).", ".$dbo->quote($poptdescr).", ".$dbo->quote($poptcost).", ".$dbo->quote($poptperday).", ".$dbo->quote($popthmany).", '".$picon."', ".$dbo->quote($poptaliq).", ".$dbo->quote($pmaxprice).", '".$pforcesel."', '".$strforceval."', '".$poptperperson."', '".$pifchildren."', '".$pmaxquant."', '".$newsortnum."', '".$ageintervalstr."', '".$pis_citytax."', '".$pis_fee."');";
		$dbo->setQuery($q);
		$dbo->Query($q);
	}
	cancelEditingOptionals($option);
}

function updateOptional ($option) {
	$poptname = JRequest::getString('optname', '', 'request');
	$poptdescr = JRequest::getString('optdescr', '', 'request', JREQUEST_ALLOWHTML);
	$poptcost = JRequest::getString('optcost', '', 'request');
	$poptperday = JRequest::getString('optperday', '', 'request');
	$poptperperson = JRequest::getString('optperperson', '', 'request');
	$pmaxprice = JRequest::getString('maxprice', '', 'request');
	$popthmany = JRequest::getString('opthmany', '', 'request');
	$poptaliq = JRequest::getString('optaliq', '', 'request');
	$pwhereup = JRequest::getString('whereup', '', 'request');
	$pautoresize = JRequest::getString('autoresize', '', 'request');
	$presizeto = JRequest::getString('resizeto', '', 'request');
	$pifchildren = JRequest::getString('ifchildren', '', 'request');
	$pifchildren = $pifchildren == "1" ? 1 : 0;
	$pmaxquant = JRequest::getString('maxquant', '', 'request');
	$pmaxquant = empty($pmaxquant) ? 0 : intval($pmaxquant);
	$pforcesel = JRequest::getString('forcesel', '', 'request');
	$pforceval = JRequest::getString('forceval', '', 'request');
	$pforcevalperday = JRequest::getString('forcevalperday', '', 'request');
	$pforcevalperchild = JRequest::getString('forcevalperchild', '', 'request');
	$pforcesummary = JRequest::getString('forcesummary', '', 'request');
	$pforcesel = $pforcesel == "1" ? 1 : 0;
	$pis_citytax = JRequest::getString('is_citytax', '', 'request');
	$pis_fee = JRequest::getString('is_fee', '', 'request');
	$pis_citytax = $pis_citytax == "1" && $pis_fee != "1" ? 1 : 0;
	$pis_fee = $pis_fee == "1" && $pis_citytax == 0 ? 1 : 0;
	$pagefrom = JRequest::getVar('agefrom', array(0));
	$pageto = JRequest::getVar('ageto', array(0));
	$pagecost = JRequest::getVar('agecost', array(0));
	if($pforcesel == 1) {
		$strforceval = intval($pforceval)."-".($pforcevalperday == "1" ? "1" : "0")."-".($pforcevalperchild == "1" ? "1" : "0")."-".($pforcesummary == "1" ? "1" : "0");
	}else {
		$strforceval = "";
	}
	if (!empty($poptname)) {
		if (intval($_FILES['optimg']['error']) == 0 && caniWrite(JPATH_SITE.DS.'components'.DS.'com_vikbooking'.DS.'resources'.DS.'uploads'.DS) && trim($_FILES['optimg']['name'])!="") {
			jimport('joomla.filesystem.file');
			$updpath = JPATH_SITE.DS.'components'.DS.'com_vikbooking'.DS.'resources'.DS.'uploads'.DS;
			if (@is_uploaded_file($_FILES['optimg']['tmp_name'])) {
				$safename=JFile::makeSafe(str_replace(" ", "_", strtolower($_FILES['optimg']['name'])));
				if (file_exists($updpath.$safename)) {
					$j=1;
					while (file_exists($updpath.$j.$safename)) {
						$j++;
					}
					$pwhere=$updpath.$j.$safename;
				}else {
					$j="";
					$pwhere=$updpath.$safename;
				}
				@move_uploaded_file($_FILES['optimg']['tmp_name'], $pwhere);
				if(!getimagesize($pwhere)){
					@unlink($pwhere);
					$picon="";
				}else {
					@chmod($pwhere, 0644);
					$picon=$j.$safename;
					if($pautoresize=="1" && !empty($presizeto)) {
						$eforj = new vikResizer();
						$origmod = $eforj->proportionalImage($pwhere, $updpath.'r_'.$j.$safename, $presizeto, $presizeto);
						if($origmod) {
							@unlink($pwhere);
							$picon='r_'.$j.$safename;
						}
					}
				}
			}else {
				$picon="";
			}
		}else {
			$picon="";
		}
		($poptperday=="each" ? $poptperday="1" : $poptperday="0");
		$poptperperson=($poptperperson=="each" ? "1" : "0");
		($popthmany=="yes" ? $popthmany="1" : $popthmany="0");
		$ageintervalstr = '';
		if ($pifchildren == 1 && count($pagefrom) > 0 && count($pagecost) > 0 && count($pagefrom) == count($pagecost)) {
			foreach($pagefrom as $kage => $vage) {
				$afrom = intval($vage);
				$ato = intval($pageto[$kage]);
				$acost = floatval($pagecost[$kage]);
				if (strlen($vage) > 0 && strlen($pagecost[$kage]) > 0) {
					if ($ato < $afrom) $ato = $afrom;
					$ageintervalstr .= $afrom.'_'.$ato.'_'.$acost.';;';
				}
			}
			$ageintervalstr = rtrim($ageintervalstr, ';;');
			if(!empty($ageintervalstr)) {
				$pforcesel = 1;
			}
		}
		$dbo = JFactory::getDBO();
		$q="UPDATE `#__vikbooking_optionals` SET `name`=".$dbo->quote($poptname).",`descr`=".$dbo->quote($poptdescr).",`cost`=".$dbo->quote($poptcost).",`perday`=".$dbo->quote($poptperday).",`hmany`=".$dbo->quote($popthmany).",".(strlen($picon)>0 ? "`img`='".$picon."'," : "")."`idiva`=".$dbo->quote($poptaliq).", `maxprice`=".$dbo->quote($pmaxprice).", `forcesel`='".$pforcesel."', `forceval`='".$strforceval."', `perperson`='".$poptperperson."', `ifchildren`='".$pifchildren."', `maxquant`='".$pmaxquant."', `ageintervals`='".$ageintervalstr."',`is_citytax`=".$pis_citytax.",`is_fee`=".$pis_fee." WHERE `id`=".$dbo->quote($pwhereup).";";
		$dbo->setQuery($q);
		$dbo->Query($q);
	}
	cancelEditingOptionals($option);
}

function removeIva ($ids, $option) {
	if (@count($ids)) {
		$dbo = JFactory::getDBO();
		foreach($ids as $d){
			$q="DELETE FROM `#__vikbooking_iva` WHERE `id`=".$dbo->quote($d).";";
			$dbo->setQuery($q);
			$dbo->Query($q);
		}
	}
	cancelEditingIva($option);
}

function removeBusy ($option) {
	$dbo = JFactory::getDBO();
	$prev_conf_ids = array();
	$pidorder = JRequest::getString('idorder', '', 'request');
	$pgoto = JRequest::getString('goto', '', 'request');
	$q="SELECT * FROM `#__vikbooking_orders` WHERE `id`=".$dbo->quote($pidorder).";";
	$dbo->setQuery($q);
	$dbo->Query($q);
	if ($dbo->getNumRows() == 1) {
		$rows = $dbo->loadAssocList();
		if ($rows[0]['status'] != 'cancelled') {
			$q="UPDATE `#__vikbooking_orders` SET `status`='cancelled' WHERE `id`='".$rows[0]['id']."';";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$q = "DELETE FROM `#__vikbooking_tmplock` WHERE `idorder`=" . intval($rows[0]['id']) . ";";
			$dbo->setQuery($q);
			$dbo->Query($q);
			if ($rows[0]['status'] == 'confirmed') {
				$prev_conf_ids[] = $rows[0]['id'];
			}
		}
		$q="SELECT * FROM `#__vikbooking_ordersbusy` WHERE `idorder`='".$rows[0]['id']."';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if ($dbo->getNumRows() > 0) {
			$ordbusy = $dbo->loadAssocList();
			foreach($ordbusy as $ob) {
				$q="DELETE FROM `#__vikbooking_busy` WHERE `id`='".$ob['idbusy']."';";
				$dbo->setQuery($q);
				$dbo->Query($q);
			}
		}
		$q="DELETE FROM `#__vikbooking_ordersbusy` WHERE `idorder`='".$rows[0]['id']."';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		if ($rows[0]['status'] == 'cancelled') {
			$q = "DELETE FROM `#__vikbooking_customers_orders` WHERE `idorder`=" . intval($rows[0]['id']) . ";";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$q="DELETE FROM `#__vikbooking_ordersrooms` WHERE `idorder`='".$rows[0]['id']."';";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$q="DELETE FROM `#__vikbooking_orders` WHERE `id`='".$rows[0]['id']."';";
			$dbo->setQuery($q);
			$dbo->Query($q);
		}
		$app = JFactory::getApplication();
		$app->enqueueMessage(JText::_('VBMESSDELBUSY'));
	}
	if(count($prev_conf_ids) > 0) {
		$prev_conf_ids_str = '';
		foreach ($prev_conf_ids as $prev_id) {
			$prev_conf_ids_str .= '&cid[]='.$prev_id;
		}
		//Invoke Channel Manager
		if(file_exists(JPATH_SITE . DS ."components". DS ."com_vikchannelmanager". DS . "helpers" . DS ."synch.vikbooking.php")) {
			$vcm_sync_url = 'index.php?option=com_vikbooking&task=invoke_vcm&stype=cancel'.$prev_conf_ids_str.'&returl='.urlencode('index.php?option=com_vikbooking&task=vieworders');
			JError::raiseNotice('', JText::_('VBCHANNELMANAGERINVOKEASK').' <button type="button" class="btn btn-primary" onclick="document.location.href=\''.$vcm_sync_url.'\';">'.JText::_('VBCHANNELMANAGERSENDRQ').'</button>');
		}
		//
	}
	$mainframe = JFactory::getApplication();
	$mainframe->redirect("index.php?option=".$option."&task=".($pgoto == 'overview' ? 'overview' : 'vieworders'));
}

function removePrice ($ids, $option) {
	if (@count($ids)) {
		$dbo = JFactory::getDBO();
		foreach($ids as $d){
			$q="DELETE FROM `#__vikbooking_prices` WHERE `id`=".$dbo->quote($d).";";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$q="DELETE FROM `#__vikbooking_dispcost` WHERE `idprice`=".intval($d).";";
			$dbo->setQuery($q);
			$dbo->Query($q);
		}
	}
	cancelEditingPrice($option);
}

function removeCat ($ids, $option) {
	if (@count($ids)) {
		$dbo = JFactory::getDBO();
		foreach($ids as $d){
			$q="DELETE FROM `#__vikbooking_categories` WHERE `id`=".$dbo->quote($d).";";
			$dbo->setQuery($q);
			$dbo->Query($q);
		}
	}
	cancelEditingCat($option);
}

function removeCarat ($ids, $option) {
	if (@count($ids)) {
		$dbo = JFactory::getDBO();
		foreach($ids as $d){
			$q="SELECT `icon` FROM `#__vikbooking_characteristics` WHERE `id`=".$dbo->quote($d).";";
			$dbo->setQuery($q);
			$dbo->Query($q);
			if ($dbo->getNumRows() == 1) {
				$rows = $dbo->loadAssocList();
				if (!empty($rows[0]['icon']) && file_exists(JPATH_SITE.DS.'components'.DS.'com_vikbooking'.DS.'resources'.DS.'uploads'.DS.$rows[0]['icon'])) {
					@unlink(JPATH_SITE.DS.'components'.DS.'com_vikbooking'.DS.'resources'.DS.'uploads'.DS.$rows[0]['icon']);
				}
			}	
			$q="DELETE FROM `#__vikbooking_characteristics` WHERE `id`=".$dbo->quote($d).";";
			$dbo->setQuery($q);
			$dbo->Query($q);
		}
	}
	cancelEditingCarat($option);
}

function removeOptionals ($ids, $option) {
	if (@count($ids)) {
		$dbo = JFactory::getDBO();
		foreach($ids as $d){
			$q="SELECT `img` FROM `#__vikbooking_optionals` WHERE `id`=".$dbo->quote($d).";";
			$dbo->setQuery($q);
			$dbo->Query($q);
			if ($dbo->getNumRows() == 1) {
				$rows = $dbo->loadAssocList();
				if (!empty($rows[0]['img']) && file_exists(JPATH_SITE.DS.'components'.DS.'com_vikbooking'.DS.'resources'.DS.'uploads'.DS.$rows[0]['img'])) {
					@unlink(JPATH_SITE.DS.'components'.DS.'com_vikbooking'.DS.'resources'.DS.'uploads'.DS.$rows[0]['img']);
				}
			}	
			$q="DELETE FROM `#__vikbooking_optionals` WHERE `id`=".$dbo->quote($d).";";
			$dbo->setQuery($q);
			$dbo->Query($q);
		}
	}
	cancelEditingOptionals($option);
}

function removeOrders ($ids, $option) {
	$prev_conf_ids = array();
	if (@count($ids)) {
		$dbo = JFactory::getDBO();
		foreach($ids as $d){
			$q="SELECT * FROM `#__vikbooking_orders` WHERE `id`=".$dbo->quote($d).";";
			$dbo->setQuery($q);
			$dbo->Query($q);
			if ($dbo->getNumRows() == 1) {
				$rows = $dbo->loadAssocList();
				if ($rows[0]['status'] != 'cancelled') {
					$q="UPDATE `#__vikbooking_orders` SET `status`='cancelled' WHERE `id`='".$rows[0]['id']."';";
					$dbo->setQuery($q);
					$dbo->Query($q);
					$q = "DELETE FROM `#__vikbooking_tmplock` WHERE `idorder`=" . intval($rows[0]['id']) . ";";
					$dbo->setQuery($q);
					$dbo->Query($q);
					if ($rows[0]['status'] == 'confirmed') {
						$prev_conf_ids[] = $rows[0]['id'];
					}
				}
				$q="SELECT * FROM `#__vikbooking_ordersbusy` WHERE `idorder`='".$rows[0]['id']."';";
				$dbo->setQuery($q);
				$dbo->Query($q);
				if ($dbo->getNumRows() > 0) {
					$ordbusy = $dbo->loadAssocList();
					foreach($ordbusy as $ob) {
						$q="DELETE FROM `#__vikbooking_busy` WHERE `id`='".$ob['idbusy']."';";
						$dbo->setQuery($q);
						$dbo->Query($q);
					}
				}
				$q="DELETE FROM `#__vikbooking_ordersbusy` WHERE `idorder`='".$rows[0]['id']."';";
				$dbo->setQuery($q);
				$dbo->Query($q);
				if ($rows[0]['status'] == 'cancelled') {
					$q = "DELETE FROM `#__vikbooking_customers_orders` WHERE `idorder`=" . intval($rows[0]['id']) . ";";
					$dbo->setQuery($q);
					$dbo->Query($q);
					$q="DELETE FROM `#__vikbooking_ordersrooms` WHERE `idorder`='".$rows[0]['id']."';";
					$dbo->setQuery($q);
					$dbo->Query($q);
					$q="DELETE FROM `#__vikbooking_orders` WHERE `id`='".$rows[0]['id']."';";
					$dbo->setQuery($q);
					$dbo->Query($q);
				}
			}
		}
		$app = JFactory::getApplication();
		$app->enqueueMessage(JText::_('VBMESSDELBUSY'));
	}
	if(count($prev_conf_ids) > 0) {
		$prev_conf_ids_str = '';
		foreach ($prev_conf_ids as $prev_id) {
			$prev_conf_ids_str .= '&cid[]='.$prev_id;
		}
		//Invoke Channel Manager
		if(file_exists(JPATH_SITE . DS ."components". DS ."com_vikchannelmanager". DS . "helpers" . DS ."synch.vikbooking.php")) {
			$vcm_sync_url = 'index.php?option=com_vikbooking&task=invoke_vcm&stype=cancel'.$prev_conf_ids_str.'&returl='.urlencode('index.php?option=com_vikbooking&task=vieworders');
			JError::raiseNotice('', JText::_('VBCHANNELMANAGERINVOKEASK').' <button type="button" class="btn btn-primary" onclick="document.location.href=\''.$vcm_sync_url.'\';">'.JText::_('VBCHANNELMANAGERSENDRQ').'</button>');
		}
		//
	}
	$mainframe = JFactory::getApplication();
	$mainframe->redirect("index.php?option=".$option."&task=vieworders");
}

function removeRoom ($ids, $option) {
	if (@count($ids)) {
		$dbo = JFactory::getDBO();
		foreach($ids as $d){
			$q="DELETE FROM `#__vikbooking_rooms` WHERE `id`=".$dbo->quote($d).";";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$q="DELETE FROM `#__vikbooking_dispcost` WHERE `idroom`=".$dbo->quote($d).";";
			$dbo->setQuery($q);
			$dbo->Query($q);
		}
	}
	$mainframe = JFactory::getApplication();
	$mainframe->redirect("index.php?option=".$option."&task=rooms");
}

function unlockRecords ($ids, $option) {
	if (@count($ids)) {
		$dbo = JFactory::getDBO();
		foreach($ids as $d){
			$q="DELETE FROM `#__vikbooking_tmplock` WHERE `id`=".$dbo->quote($d).";";
			$dbo->setQuery($q);
			$dbo->Query($q);
		}
	}
	$mainframe = JFactory::getApplication();
	$mainframe->redirect("index.php?option=".$option);
}

function editOrder ($ido, $option) {
	if (file_exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_vikchannelmanager'.DS.'assets'.DS.'css'.DS.'vcm-channels.css')) {
		$document = JFactory::getDocument();
		$document->addStyleSheet(JURI::root().'administrator/components/com_vikchannelmanager/assets/css/vikchannelmanager.css');
		$document->addStyleSheet(JURI::root().'administrator/components/com_vikchannelmanager/assets/css/vcm-channels.css');
	}
	$dbo = JFactory::getDBO();
	$q="SELECT * FROM `#__vikbooking_orders` WHERE `id`=".$dbo->quote($ido).";";
	$dbo->setQuery($q);
	$dbo->Query($q);
	if ($dbo->getNumRows() == 1) {
		$rows = $dbo->loadAssocList();
		$q="SELECT `id`,`name` FROM `#__vikbooking_gpayments` ORDER BY `#__vikbooking_gpayments`.`name` ASC;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$payments = $dbo->getNumRows() > 0 ? $dbo->loadAssocList() : '';
		$padminnotes = JRequest::getString('adminnotes', '', 'request');
		$pnewpayment = JRequest::getString('newpayment', '', 'request');
		$padmindisc = JRequest::getString('admindisc', '', 'request');
		$ptot_taxes = JRequest::getString('tot_taxes', '', 'request');
		$ptot_city_taxes = JRequest::getString('tot_city_taxes', '', 'request');
		$ptot_fees = JRequest::getString('tot_fees', '', 'request');
		$pcmms = JRequest::getString('cmms', '', 'request');
		$pcustmail = JRequest::getString('custmail', '', 'request');
		$pcustphone = JRequest::getString('custphone', '', 'request');
		$pmakepay = JRequest::getInt('makepay', '', 'request');
		if($pmakepay > 0) {
			$q = "UPDATE `#__vikbooking_orders` SET `paymcount`=1 WHERE `id`=".$rows[0]['id'].";";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$rows[0]['paymcount'] = 1;
		}
		if (!empty($padminnotes)) {
			$q = "UPDATE `#__vikbooking_orders` SET `adminnotes`=".$dbo->quote($padminnotes)." WHERE `id`=".$rows[0]['id'].";";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$rows[0]['adminnotes'] = $padminnotes;
		}
		if (!empty($pnewpayment) && is_array($payments)) {
			foreach ($payments as $npay) {
				if ((int)$npay['id'] == (int)$pnewpayment) {
					$newpayvalid = $npay['id'].'='.$npay['name'];
					$q = "UPDATE `#__vikbooking_orders` SET `idpayment`=".$dbo->quote($newpayvalid)." WHERE `id`=".$rows[0]['id'].";";
					$dbo->setQuery($q);
					$dbo->Query($q);
					$rows[0]['idpayment'] = $newpayvalid;
					break;
				}
			}
		}
		if (strlen($padmindisc) > 0) {
			if(floatval($padmindisc) > 0.00) {
				$admincoupon = '-1;'.floatval($padmindisc).';'.JText::_('VBADMINDISCOUNT');
			}else {
				$admincoupon = '';
			}
			$q = "UPDATE `#__vikbooking_orders` SET `coupon`=".$dbo->quote($admincoupon)." WHERE `id`=".$rows[0]['id'].";";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$rows[0]['coupon'] = $admincoupon;
		}
		if (strlen($ptot_taxes) > 0) {
			$q = "UPDATE `#__vikbooking_orders` SET `tot_taxes`='".floatval($ptot_taxes)."' WHERE `id`=".$rows[0]['id'].";";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$rows[0]['tot_taxes'] = $ptot_taxes;
		}
		if (strlen($ptot_city_taxes) > 0) {
			$q = "UPDATE `#__vikbooking_orders` SET `tot_city_taxes`='".floatval($ptot_city_taxes)."' WHERE `id`=".$rows[0]['id'].";";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$rows[0]['tot_city_taxes'] = $ptot_city_taxes;
		}
		if (strlen($ptot_fees) > 0) {
			$q = "UPDATE `#__vikbooking_orders` SET `tot_fees`='".floatval($ptot_fees)."' WHERE `id`=".$rows[0]['id'].";";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$rows[0]['tot_fees'] = $ptot_fees;
		}
		if (strlen($pcmms) > 0) {
			$q = "UPDATE `#__vikbooking_orders` SET `cmms`='".floatval($pcmms)."' WHERE `id`=".$rows[0]['id'].";";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$rows[0]['cmms'] = $pcmms;
		}
		if (strlen($pcustmail) > 0) {
			$q = "UPDATE `#__vikbooking_orders` SET `custmail`=".$dbo->quote($pcustmail)." WHERE `id`=".$rows[0]['id'].";";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$rows[0]['custmail'] = $pcustmail;
		}
		if (strlen($pcustphone) > 0) {
			$q = "UPDATE `#__vikbooking_orders` SET `phone`=".$dbo->quote($pcustphone)." WHERE `id`=".$rows[0]['id'].";";
			$dbo->setQuery($q);
			$dbo->Query($q);
			$rows[0]['phone'] = $pcustphone;
		}
		//Rooms Specific Unit
		$proomindex = JRequest::getVar('roomindex', array());
		if(!empty($proomindex) && is_array($proomindex) && count($proomindex)) {
			foreach ($proomindex as $or_id => $rind) {
				if(!empty($or_id)) {
					$q = "UPDATE `#__vikbooking_ordersrooms` SET `roomindex`=".(!empty($rind) ? (int)$rind : "NULL")." WHERE `id`=".(int)$or_id." AND `idorder`=".(int)$rows[0]['id'].";";
					$dbo->setQuery($q);
					$dbo->Query($q);
				}
			}
		}
		//
		//PCI DSS Checking
		if(!empty($rows[0]['idorderota']) && !empty($rows[0]['channel']) && !empty($rows[0]['paymentlog'])) {
			if(stripos($rows[0]['paymentlog'], 'card number') !== false && strpos($rows[0]['paymentlog'], '****') !== false) {
				if((time() + 3600) > $rows[0]['checkout']) {
					$q = "UPDATE `#__vikbooking_orders` SET `paymentlog`='----' WHERE `id`=".$rows[0]['id'].";";
					$dbo->setQuery($q);
					$dbo->Query($q);
					$rows[0]['paymentlog'] = '----';
				}
			}
		}
		//
		$q = "SELECT `or`.*,`r`.`name`,`r`.`fromadult`,`r`.`toadult`,`r`.`params` FROM `#__vikbooking_ordersrooms` AS `or`,`#__vikbooking_rooms` AS `r` WHERE `or`.`idorder`='".$rows[0]['id']."' AND `or`.`idroom`=`r`.`id` ORDER BY `or`.`id` ASC;";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$rooms = $dbo->getNumRows() > 0 ? $dbo->loadAssocList() : "";
		$q="SELECT * FROM `#__vikbooking_ordersbusy` WHERE `idorder`='".$rows[0]['id']."';";
		$dbo->setQuery($q);
		$dbo->Query($q);
		$busy = $dbo->getNumRows() > 0 ? $dbo->loadAssocList() : "";
		HTML_vikbooking::pEditOrder($rows[0], $rooms, $busy, $payments, $option);
	}else {
		cancelEditingOrders($option);
	}
}

function cancelEditingCrons($option) {
	$mainframe = JFactory::getApplication();
	$mainframe->redirect("index.php?option=".$option."&task=crons");
}

function cancelEditingPackages($option) {
	$mainframe = JFactory::getApplication();
	$mainframe->redirect("index.php?option=".$option."&task=packages");
}

function cancelEditingOrders($option) {
	$mainframe = JFactory::getApplication();
	$mainframe->redirect("index.php?option=".$option."&task=vieworders");
}

function cancelEditing($option) {
	$mainframe = JFactory::getApplication();
	$mainframe->redirect("index.php?option=".$option."&task=rooms");
}

function cancelEditingIva($option) {
	$mainframe = JFactory::getApplication();
	$mainframe->redirect("index.php?option=".$option."&task=viewiva");
}

function cancelEditingPrice($option) {
	$mainframe = JFactory::getApplication();
	$mainframe->redirect("index.php?option=".$option."&task=viewprices");
}

function cancelEditingCat($option) {
	$mainframe = JFactory::getApplication();
	$mainframe->redirect("index.php?option=".$option."&task=viewcategories");
}

function cancelEditingCarat($option) {
	$mainframe = JFactory::getApplication();
	$mainframe->redirect("index.php?option=".$option."&task=viewcarat");
}

function cancelEditingOptionals($option) {
	$mainframe = JFactory::getApplication();
	$mainframe->redirect("index.php?option=".$option."&task=viewoptionals");
}

function cancelCalendar($option) {
	$pidroom = JRequest::getString('idroom', '', 'request');
	$mainframe = JFactory::getApplication();
	$mainframe->redirect("index.php?option=".$option."&task=calendar&cid[]=".$pidroom);
}

function cancelOverview($option) {
	$mainframe = JFactory::getApplication();
	$mainframe->redirect("index.php?option=".$option."&task=overview");
}

function cancelBusy($option) {
	$pidorder = JRequest::getString('idorder', '', 'request');
	$mainframe = JFactory::getApplication();
	$mainframe->redirect("index.php?option=".$option."&task=editorder&cid[]=".$pidorder);
}

function cancelBusyVcm($option) {
	$mainframe = JFactory::getApplication();
	$mainframe->redirect("index.php?option=com_vikchannelmanager&task=oversight");
}

function goConfig($option) {
	$mainframe = JFactory::getApplication();
	$mainframe->redirect("index.php?option=".$option."&task=config");
}

function cancelEditingSeason($option) {
	$mainframe = JFactory::getApplication();
	$mainframe->redirect("index.php?option=".$option."&task=seasons");
}

function cancelEditingPayment($option) {
	$mainframe = JFactory::getApplication();
	$mainframe->redirect("index.php?option=".$option."&task=payments");
}

function cancelEditingCustomf($option) {
	$mainframe = JFactory::getApplication();
	$mainframe->redirect("index.php?option=".$option."&task=viewcustomf");
}

function cancelEditingCoupon($option) {
	$mainframe = JFactory::getApplication();
	$mainframe->redirect("index.php?option=".$option."&task=viewcoupons");
}

function cancelEditingRestriction($option) {
	$mainframe = JFactory::getApplication();
	$mainframe->redirect("index.php?option=".$option."&task=restrictions");
}

function cancelEditingCustomer($option) {
	$mainframe = JFactory::getApplication();
	$mainframe->redirect("index.php?option=".$option."&task=customers");
}

?>