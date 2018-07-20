<?php
/*------------------------------------------------------------------------
# category.php - Ossolution Services Booking
# ------------------------------------------------------------------------
# author    Ossolution team
# copyright Copyright (C) 2012 joomdonation.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomdonation.com
# Technical Support:  Forum - http://www.joomdonation.com/forum.html
*/

// No direct access.
defined('_JEXEC') or die;

class OSappscheduleCategory{
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
			case "category_listing":
				OSappscheduleCategory::listCategories();
			break;
		}
	}
	
	/**
	 * List Categories
	 *
	 */
	function listCategories(){
		global $mainframe,$configClass;
		$document = JFactory::getDocument();
		$menus = JSite::getMenu();
		$menu = $menus->getActive();
        $list_type = 0;
		if (is_object($menu)) {
	        $params = new JRegistry() ;
	        $params->loadString($menu->params);
            if($params->get('page_title') != ""){
                $document->setTitle($params->get('page_title'));
            }else{
                $document->setTitle($configClass['business_name'].' | '.JText::_('OS_LIST_ALL_CATEGORIES'));
            }
            $list_type = $params->get('list_type',0);
		}else{
            $document->setTitle($configClass['business_name'].' | '.JText::_('OS_LIST_ALL_CATEGORIES'));
        }

		$db = JFactory::getDbo();
		$db->setQuery("Select * from #__app_sch_categories where published = '1' order by category_name");
		$categories = $db->loadObjectList();
		HTML_OSappscheduleCategory::listCategories($categories,$params,$list_type);
	}
}
?>