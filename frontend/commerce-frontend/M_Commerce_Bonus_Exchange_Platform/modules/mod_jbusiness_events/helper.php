<?php
/*------------------------------------------------------------------------
# JBusinessDirectory
# author CMSJunkie
# copyright Copyright (C) 2012 cmsjunkie.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.cmsjunkie.com
# Technical Support:  Forum - http://www.cmsjunkie.com/forum/j-businessdirectory/?p=1
-------------------------------------------------------------------------*/

defined( '_JEXEC' ) or die( 'Restricted access' );


require_once JPATH_SITE.'/administrator/components/com_jbusinessdirectory/helpers/translations.php';

abstract class modJBusinessEventsHelper
{
	public static function getList($params){
		
		$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		
		// Get the database object.
		$db = JFactory::getDBO();
		
		
		$featured  = $params->get('only_featured');
		
		$orderBy = " rand() ";
		$ordering  = $params->get('order');
		if($ordering){
			$orderBy ="co.start_date asc";
		}
		
		$nrResults = $params->get('count');
		
		$searchDetails = array();
		$searchDetails["enablePackages"] = $appSettings->enable_packages;
		$searchDetails["showPendingApproval"] = $appSettings->show_pending_approval;
		$searchDetails["orderBy"] = $orderBy;
		$searchDetails["featured"] = $featured;
		
		$categoriesIds = $params->get('categoryIds');		
		if(!(!empty($categoriesIds) && $categoriesIds[0]!= 0 && $categoriesIds[0]!="")){
			$categoriesIds = null;
		}
		$searchDetails["categoriesIds"] = $categoriesIds;
		
		if($params->get('citySearch')){
			$searchDetails["citySearch"] = $params->get('citySearch');
		}
		if($params->get('regionSearch')){
			$searchDetails["regionSearch"] = $params->get('regionSearch');
		}
		
		JTable::addIncludePath(JPATH_ROOT.'/administrator/components/com_jbusinessdirectory/tables');
		$eventsTable = JTable::getInstance("Event", "JTable");
		$events =  $eventsTable->getEventsByCategories($searchDetails, 0, $nrResults);
		
		if($searchDetails["orderBy"]=="rand()"){
			shuffle($events);
		}
		
		foreach($events as $event){
			$event->picture_path = str_replace(" ", "%20", $event->picture_path);
		}
		
		if($appSettings->enable_multilingual){
			JBusinessDirectoryTranslations::updateEventsTranslation($events);
			JBusinessDirectoryTranslations::updateEventTypesTranslation($events);
		}
		
		
		return $events;
	}
}
?>
