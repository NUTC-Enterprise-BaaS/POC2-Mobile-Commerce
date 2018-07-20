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

$er_l = isset($_REQUEST['error_reporting']) && intval($_REQUEST['error_reporting'] == '-1') ? -1 : 0;
defined('VIKBOOKING_ERROR_REPORTING') OR define('VIKBOOKING_ERROR_REPORTING', $er_l);
error_reporting(VIKBOOKING_ERROR_REPORTING);

//Joomla 3.x
if(!defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}
//

require_once(JPATH_SITE . DS ."components". DS ."com_vikbooking". DS . "helpers" . DS ."lib.vikbooking.php");

$document = JFactory::getDocument();
if(vikbooking::loadBootstrap()) {
	$document->addStyleSheet(JURI::root().'components/com_vikbooking/resources/bootstrap.min.css');
	$document->addStyleSheet(JURI::root().'components/com_vikbooking/resources/bootstrap-theme.min.css');
}
$document->addStyleSheet(JURI::root().'components/com_vikbooking/vikbooking_styles.css');
$document->addStyleSheet(JURI::root().'components/com_vikbooking/vikbooking_custom.css');

vikbooking::detectUserAgent();

vikbooking::invokeChannelManager();

jimport('joomla.application.component.controller');
$controller = JControllerLegacy::getInstance('Vikbooking');
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();

?>
