<?php
/*------------------------------------------------------------------------
# mod_osbsearch.php - OSB Search
# ------------------------------------------------------------------------
# author    Dang Thuc Dam
# copyright Copyright (C) 2014 joomdonation.com. All Rights Reserved.
# @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or Later
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/
/** ensure this file is being included by a parent file */
defined('_JEXEC') or die('Restricted access');

error_reporting(E_ERROR | E_PARSE | E_COMPILE_ERROR);
require_once JPATH_ROOT.'/administrator/components/com_osservicesbooking/helpers/helper.php';
$document = JFactory::getDocument();
$document->addStyleSheet(JUri::root().'modules/mod_osbsearch/asset/style.css');

$category_id = JRequest::getInt('category_id',0);
$vid		 = JRequest::getInt('vid',0);
$sid		 = JRequest::getInt('sid',0);
$employee_id = JRequest::getInt('employee_id',0);

$db = Jfactory::getDBO();

$language = JFactory::getLanguage();
$tag = $language->getTag();
if($tag == ""){
	$tag = "en-GB";
}
$language->load('com_osservicesbooking', JPATH_SITE, $tag, true);

if (version_compare(JVERSION, '3.0', 'lt')) {
	OSBHelper::loadBootstrapStylesheet();
}else{
	$db->setQuery("Select config_value from #__app_sch_configuation where config_key like 'load_bootstrap'");
	$loadbootstrap = $db->loadResult();
	if($loadbootstrap == 1){
		OSBHelper::loadBootstrapStylesheet();	
	}
}

$moduleclass_sfx = $params->get('moduleclass_sfx','');
$show_venue = $params->get('show_venue',1);
$show_category = $params->get('show_category',1);
$show_employee = $params->get('show_employee',1);
$show_service = $params->get('show_service',1);
$show_date = $params->get('show_date',1);

$optionArr   = array();
$optionArr[] = JHtml::_('select.option','',JText::_('OS_SELECT_SERVICE'));

$db->setQuery("Select id as value, service_name as text from #__app_sch_services order by ordering");
$services = $db->loadObjectList();

$serviceArr = array();
$serviceArr = array_merge($optionArr,$services);
$lists['service'] = JHtml::_('select.genericlist',$serviceArr,'sid','class="input-large"','value','text',$sid);

$optionArr   = array();
$optionArr[] = JHtml::_('select.option','',JText::_('OS_SELECT_CATEGORY'));

$db->setQuery("Select id as value, category_name as text from #__app_sch_categories order by category_name");
$categories = $db->loadObjectList();

$categoryArr = array();
$categoryArr = array_merge($optionArr,$categories);
$lists['category'] = JHtml::_('select.genericlist',$categoryArr,'category_id','class="input-large"','value','text',$category_id);

$optionArr   = array();
$optionArr[] = JHtml::_('select.option','',JText::_('OS_SELECT_VENUE'));

$db->setQuery("Select id as value, address as text from #__app_sch_venues order by address");
$venues = $db->loadObjectList();

$venueArr = array();
$venueArr = array_merge($optionArr,$venues);
$lists['venue'] = JHtml::_('select.genericlist',$venueArr,'vid','class="input-large"','value','text',$vid);

$optionArr   = array();
$optionArr[] = JHtml::_('select.option','',JText::_('OS_SELECT_EMPLOYEE'));

$db->setQuery("Select id as value, employee_name as text from #__app_sch_employee order by employee_name");
$employees = $db->loadObjectList();

$employeeArr = array();
$employeeArr = array_merge($optionArr,$employees);
$lists['employee'] = JHtml::_('select.genericlist',$employeeArr,'employee_id','class="input-large"','value','text',$employee_id);

require( JModuleHelper::getLayoutPath( 'mod_osbsearch' ) );
?>