<?php
/*------------------------------------------------------------------------
# cron.php - Ossolution Services Booking
# ------------------------------------------------------------------------
# author    Ossolution team
# copyright Copyright (C) 2012 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/
// No direct access.
// defined('_JEXEC') or die;
//define('_JEXEC', true);
define('_JEXEC', 1);
define('DS', DIRECTORY_SEPARATOR);
error_reporting(E_ALL);	
if (file_exists(dirname(__FILE__) . '/defines.php')) {
	include_once dirname(__FILE__) . '/defines.php';
}

if (!defined('_JDEFINES')) {
	//$parts = explode(DIRECTORY_SEPARATOR, dirname(__FILE__));
	//array_pop($parts);array_pop($parts);array_pop($parts);
	define('JPATH_BASE', dirname(dirname(__DIR__)));
	require_once JPATH_BASE.'/includes/defines.php';
}

define('REPLACE_PATH', str_replace(JPATH_BASE,'',dirname(__FILE__)));

require_once JPATH_BASE.'/includes/framework.php';

jimport('joomla.database.database');
jimport('joomla.application.input');
jimport('joomla.event.dispatcher');
jimport('joomla.application.input');
jimport('joomla.event.dispatcher');
jimport('joomla.environment.response');
jimport('joomla.environment.uri');
jimport('joomla.log.log');
jimport('joomla.application.component.helper');
jimport('joomla.methods');
jimport('joomla.factory');

$app = JFactory::getApplication('site');
$app->initialise();

include (JPATH_ROOT.'/components/com_osservicesbooking/classes/default.php');
include (JPATH_ROOT.'/components/com_osservicesbooking/helpers/common.php');
include (JPATH_ROOT.'/administrator/components/com_osservicesbooking/helpers/helper.php');

$db = JFactory::getDbo();
$configClass = OSBHelper::loadConfig();
$current_time = HelperOSappscheduleCommon::getRealTime();
$reminder = $configClass['value_sch_reminder_email_before'];
$reminder = $current_time + $reminder*3600;
$query = "Select a.* from #__app_sch_order_items as a"
		." inner join #__app_sch_orders as b on b.id = a.order_id"
		." where a.start_time <= '$reminder' and a.start_time > '$current_time' and b.order_status = 'S' and a.id not in (Select order_item_id from #__app_sch_cron)";
$db->setQuery($query);
$rows = $db->loadObjectList();
if(count($rows) > 0){
	for($i=0;$i<count($rows);$i++){
		$row = $rows[$i];
		HelperOSappscheduleCommon::sendEmail('reminder',$row->id);
		HelperOSappscheduleCommon::sendSMS('reminder',$row->order_id);
		//add into the cron table
		$db->setQuery("Insert into #__app_sch_cron (id,order_item_id) values (NULL,'$row->id')");
		$db->query();
	}
}
?>