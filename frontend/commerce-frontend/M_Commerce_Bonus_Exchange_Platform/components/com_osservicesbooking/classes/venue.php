<?php
/*------------------------------------------------------------------------
# venue.php - Ossolution Services Booking
# ------------------------------------------------------------------------
# author    Ossolution team
# copyright Copyright (C) 2012 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/

// No direct access.
defined('_JEXEC') or die;

class OSappscheduleVenueFnt{
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
			case "venue_listing":
				OSappscheduleVenueFnt::listVenues();
			break;
		}
	}
	
	/**
	 * List Categories
	 *
	 */
	function listVenues(){
		global $mainframe,$configClass;
		$document = JFactory::getDocument();
		$menus = JSite::getMenu();
		$menu = $menus->getActive();
		if (is_object($menu)) {
	        $params = new JRegistry() ;
	        $params->loadString($menu->params);
		}
		if($params->get('page_title') != ""){
			$document->setTitle($params->get('page_title'));
		}else{
			$document->setTitle($configClass['business_name'].' | '.JText::_('OS_LIST_ALL_VENUES'));
		}
		$db = JFactory::getDbo();
		$db->setQuery("Select * from #__app_sch_venues where published = '1' order by address");
		$venues = $db->loadObjectList();
		if(count($venues) > 0){
			foreach ($venues as $venue){
				$db->setQuery("Select a.* from #__app_sch_services as a inner join #__app_sch_venue_services as b on b.sid = a.id where b.vid = '$venue->id'");
				$services = $db->loadObjectList();
				$service_str = "";
				if(count($services) > 0){
					$tempArr = array();
					foreach ($services as $service){
						$tempArr[] = OSBHelper::getLanguageFieldValue($service,'service_name');
					}
					$service_str = implode(", ",$tempArr);
				}
				$venue->services = $service_str;
			}
		}
		HTML_OSappscheduleVenueFnt::listVenues($venues,$params);
	}
}
?>