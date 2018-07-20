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

jimport('joomla.application.component.modellist');
JTable::addIncludePath(DS.'components'.DS.JRequest::getVar('option').DS.'tables');
require_once( JPATH_COMPONENT_ADMINISTRATOR.'/library/category_lib.php');

class JBusinessDirectoryModelEvents extends JModelList{ 
	
	function __construct(){
		
		parent::__construct();
		
		$this->appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		
		$this->searchFilter = array();
		
		$this->keyword = JRequest::getVar('searchkeyword');
		
		$this->categoryId = JRequest::getVar('categoryId',null);
		if(empty($this->categoryId)){
			$this->categoryId = JRequest::getVar('eventCategoryId',null);
		}
		$this->typeSearch = JRequest::getVar('typeSearch',null);
		$this->categorySearch = JRequest::getVar('categorySearch',null);
		$this->menuCategoryId = JRequest::getVar('menuCategoryId',null);
		$this->startDate = JRequest::getVar('startDate');
		$this->endDate = JRequest::getVar('endDate');
		$this->zipCode = JRequest::getVar('zipcode');
		$this->preserve = JRequest::getVar('preserve',null);
		$this->companyId = JRequest::getVar('companyId',null);
		$this->citySearch = JRequest::getVar('citySearch',null);
		$this->regionSearch = JRequest::getVar('regionSearch',null);
		$this->radius = JRequest::getVar('radius');
		$this->orderBy = JRequest::getVar("orderBy", $this->appSettings->order_search_events);
		
		$resetSearch = JRequest::getVar('resetSearch',null);
		
		
		if(isset($this->categorySearch) && empty($this->categoryId) &&  isset($this->preserve)){
			$this->categoryId = $this->categorySearch;
		}
		
		if(!empty($this->menuCategoryId) && empty($this->categoryId) && !isset($this->preserve)){
			$this->categoryId = $this->menuCategoryId;
		}
		
		if(isset($this->categoryId)){
			$this->categoryId = intval($this->categoryId);
		}
		
		$session = JFactory::getSession();
		if(isset($this->categoryId) || !empty($resetSearch)){
			$session->set('ev-categorySearch', $this->categoryId);
			$session->set('ev-searchkeyword', "");
			$session->set('ev-typeSearch',"");
			$session->set('ev-startDate',"");
			$session->set('ev-endDate',"");
			$session->set('ev-citySearch',"");
			$session->set('ve-regionSearch',"");
		}
		
		if(isset($this->typeSearch)){
			$this->typeSearch = intval($this->typeSearch);
			$session->set('ev-typeSearch', $this->typeSearch);
		}
		
		if(isset($this->startDate)){
			$session->set('ev-startDate', $this->startDate);
		}
		
		if(isset($this->endDate)){
			$session->set('ev-endDate', $this->endDate);
		}
		
		if(isset($this->keyword)){
			$session->set('ev-searchkeyword', $this->keyword);
		}
		
		if(isset($this->citySearch)){
			$session->set('ev-citySearch', $this->citySearch);
		}
		
		if(isset($this->regionSearch)){
			$session->set('ev-regionSearch', $this->regionSearch);
		}
		
		if(isset($this->zipCode)){
			$session->set('ev-zipcode', $this->zipCode);
		}
		
		if(isset($this->radius)){
			$this->radius = intval($this->radius);
			$session->set('ev-radius', $this->radius);
		}
		
		
		$this->keyword = $session->get('ev-searchkeyword');
		$this->startDate = $session->get('ev-startDate');
		$this->typeSearch = $session->get('ev-typeSearch');
		$this->endDate = $session->get('ev-endDate');
		$this->categorySearch = $session->get('ev-categorySearch');
		$this->citySearch = $session->get('ev-citySearch');
		$this->regionSearch = $session->get('ev-regionSearch');
		$this->zipCode = $session->get('ev-zipcode');
		$this->radius = $session->get('ev-radius');
		$this->location = null;

		$geolocation = JRequest::getVar('geolocation',null);
		if(isset($geolocation)){
			$session->set("geolocation",$geolocation);
		}
		$geolocation = $session->get("geolocation");
		// test if geo location is determined and set location array
		if($this->appSettings->enable_geolocation && $geolocation){
			$geoLatitutde = JRequest::getVar('geo-latitude',null);
			$geoLongitude = JRequest::getVar('geo-longitude',null);

			if(!empty($geoLatitutde)){
				$session->set('geo-latitude', $geoLatitutde);
			}
			if(!empty($geoLongitude)){
				$session->set('geo-longitude', $geoLongitude);
			}
			$geoLatitutde = $session->get('geo-latitude');
			$geoLongitude = $session->get('geo-longitude');

			if(!empty($geoLatitutde) && !empty($geoLongitude)){
				$this->location =  array();
				$this->location["latitude"] = $geoLatitutde;
				$this->location["longitude"] = $geoLongitude;
			}
		}
		
		if($this->appSettings->metric==0){
			$this->radius  = $this->radius * 0.621371;
		}
		
		$mainframe = JFactory::getApplication();
		
		// Get pagination request variables
		$limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		$limitstart = JRequest::getVar('limitstart', 0, '', 'int');
		
		// In case limit has been changed, adjust it
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
		
		$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		$this->enablePackages = $appSettings->enable_packages;
		$this->showPendingApproval = $appSettings->show_pending_approval==1;
		
		if(isset($this->zipCode) && $this->zipCode!=""){
			$this->location = JBusinessUtil::getCoordinates($this->zipCode);
		}

		if(!empty($this->location)){
			$session->set("location",$this->location);
		}
		
	}

	function getSearchParameters(){
	
		$categoryService = new JBusinessDirectorCategoryLib();
	
		$categoriesIds = null;
		if(!empty($this->categoryId)){
			$categoriesIds = $categoryService->getCategoryLeafs($this->categoryId, CATEGORY_TYPE_EVENT);
			if(count($categoriesIds)== 0 && isset($this->categoryId)){
				$categoriesIds = array($this->categoryId);
			}else{
				$categoriesIds[] = $this->categoryId;
			}
		}
		
		$searchDetails = array();
		$searchDetails["keyword"] = $this->keyword;
		$searchDetails["categoriesIds"] = $categoriesIds;
		$searchDetails["startDate"] = JBusinessUtil::convertToMysqlFormat($this->startDate);
		$searchDetails["endDate"] = JBusinessUtil::convertToMysqlFormat($this->endDate);
		$searchDetails["typeSearch"] = $this->typeSearch;
		$searchDetails["companyId"] = $this->companyId;
		$searchDetails["citySearch"] = $this->citySearch;
		$searchDetails["regionSearch"] = $this->regionSearch;
		
		if(!empty($this->location)){
			$searchDetails["latitude"] = $this->location["latitude"];
			$searchDetails["longitude"] = $this->location["longitude"];
		}

		$searchDetails["radius"] = $this->radius;
		$searchDetails["enablePackages"] = $this->enablePackages;
		$searchDetails["showPendingApproval"] = $this->showPendingApproval;
		$searchDetails["orderBy"] = $this->orderBy;
		$searchDetails["multilingual"] = $this->appSettings->enable_multilingual;
	
		return $searchDetails;
	}
	
	function getTotalEvents()
	{
		// Load the content if it doesn't already exist
		if (empty($this->_total)) {
			$eventsTable = JTable::getInstance("Event", "JTable");
			$searchDetails = $this->getSearchParameters();
						
			$this->_total = $eventsTable->getTotalEventsByCategories($searchDetails);
		}
		return $this->_total;
	}
	
	function getPagination()
	{
		// Load the content if it doesn't already exist
		if (empty($this->_pagination)) {
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination($this->getTotalEvents(), $this->getState('limitstart'), $this->getState('limit') );
			$this->_pagination->setAdditionalUrlParam('controller','search');
			if(isset($this->categoryId) && $this->categoryId!='')
				$this->_pagination->setAdditionalUrlParam('categoryId',$this->categoryId);
			$this->_pagination->setAdditionalUrlParam('categoryId',$this->categoryId);
			if(isset($this->categorySearch) && $this->categorySearch!='')
				$this->_pagination->setAdditionalUrlParam('categorySearch',$this->categorySearch);
			if(isset($this->keyword) && $this->keyword!='')
				$this->_pagination->setAdditionalUrlParam('searchkeyword',$this->keyword);

			$orderBy = JRequest::getVar("orderBy", "packageOrder desc");
			if(!empty($orderBy))
				$this->_pagination->setAdditionalUrlParam('orderBy',$orderBy);
				
			if(!empty($this->citySearch))
				$this->_pagination->setAdditionalUrlParam('citySearch',$this->citySearch);
				
			if(!empty($this->zipCode))
				$this->_pagination->setAdditionalUrlParam('zipcode',$this->zipCode);
				
			if(!empty($this->regionSearch))
				$this->_pagination->setAdditionalUrlParam('regionSearch',$this->regionSearch);
				
			if(!empty($this->typeSearch))
				$this->_pagination->setAdditionalUrlParam('typeSearch',$this->typeSearch);
				
			if(!empty($this->radius))
				$this->_pagination->setAdditionalUrlParam('radius',$this->radius);
			if(!empty($this->startDate))
				$this->_pagination->setAdditionalUrlParam('startDate',$this->startDate);
			if(!empty($this->endDate))
				$this->_pagination->setAdditionalUrlParam('endDate',$this->endDate);
				
			if(!empty($this->preserve))
				$this->_pagination->setAdditionalUrlParam('preserve',$this->preserve);
			
			$this->_pagination->setAdditionalUrlParam('view','events');
		}
		return $this->_pagination;
	}
	
	function getEvents(){
		$eventsTable = JTable::getInstance("Event", "JTable");
		$searchDetails = $this->getSearchParameters();
		$events = $eventsTable->getEventsByCategories($searchDetails, $this->getState('limitstart'), $this->getState('limit'));
		if($searchDetails["orderBy"]=="rand()"){
			shuffle($events);
		}	
	
		if($this->appSettings->enable_multilingual){
			JBusinessDirectoryTranslations::updateEventsTranslation($events);
			JBusinessDirectoryTranslations::updateEventTypesTranslation($events);
		}

		JRequest::setVar("search-results",$events);
		
		return $events;
	}
	
	function getCalendarEvents(){
		$events = $this->getEvents();
		
		$calendarEvents = array();
		foreach($events as $event){
			$calendarEvent = array();
			$calendarEvent["id"] = $event->id;
			$calendarEvent["title"] = $event->name;
			$calendarEvent["allDay"] = false;
			$calendarEvent["start"] = $event->start_date."T".$event->start_time;
			$calendarEvent["end"] = $event->end_date."T".$event->end_time;
			$calendarEvent["url"] =  JBusinessUtil::getEventLink($event->id, $event->alias);
			$calendarEvent["editable"] = false;
			$calendarEvent["overlap"] = false;
			$calendarEvents[] = $calendarEvent;
		}
		
		return $calendarEvents;		
	}
	
	
	function getSeachFilter(){
		
		$searchDetails = $this->getSearchParameters();
		
		$mtime = microtime();
		$mtime = explode(" ",$mtime);
		$mtime = $mtime[1] + $mtime[0];
		$starttime = $mtime; 
    
		$eventsTable = JTable::getInstance("Event", "JTable");
		$categoryService = new JBusinessDirectorCategoryLib();
		
		$category=array();
		//dump($this->categoryId);
		if(!empty($this->categoryId)){
			$category = $categoryService->getCompleteCategoryById($this->categoryId, CATEGORY_TYPE_EVENT);
		} else {
			$category["subCategories"] = $categoryService->getCategories(CATEGORY_TYPE_EVENT);
			$category["path"]=array();
			//dump($category["subCategories"]);
		}
		//dump($category);
		$subcategories= array();
		$enableSelection = false;
		
		if($this->appSettings->enable_multilingual && !empty($category["path"])){
			$categoryTranslations = JBusinessDirectoryTranslations::getCategoriesTranslations();
			foreach($category["path"] as &$path){
				if(!empty($categoryTranslations[$path[0]])){
					$path[1] = $categoryTranslations[$path[0]]->name;
				}
			}
		}

		if(isset($category["path"]))
			$this->searchFilter["path"]=$category["path"];
		if(isset($category["subCategories"]) && count($category["subCategories"])>0){
			$subcategories = $category["subCategories"];
		}else {
			if(isset($category["path"])) {
				$parentCategories = $category["path"];
				//dump($parentCategories);
				if (count($parentCategories) > 0) {
					$categoryId = $parentCategories[count($parentCategories)][0];
					//dump($categoryId);
					$parentCategory = $categoryService->getCompleteCategoryById($categoryId, CATEGORY_TYPE_EVENT);
					$subcategories = $parentCategory["subCategories"];
					$this->searchFilter["enableSelection"] = 1;
					$enableSelection = true;
				}
			}
		}
		
		//dump($subcategories);
		if(isset($subcategories) && $subcategories!=''){
			foreach($subcategories as $cat){
				if(!is_array($cat))
					continue;
				if($this->appSettings->enable_multilingual){
					JBusinessDirectoryTranslations::updateEntityTranslation($cat[0],CATEGORY_TRANSLATION);
				}
				
				$childCategoryIds = $categoryService->getCategoryChilds($cat);
				if(count($childCategoryIds)==0){
					$childCategoryIds = array($cat[0]->id);
				}else{
					$childCategoryIds[] = $cat[0]->id;
				}
				$searchDetails["categoriesIds"] = $childCategoryIds;
				//dump($childCategoryIds);
				$companiesNumber = $eventsTable->getTotalEventsByCategories($searchDetails, $this->enablePackages, $this->showPendingApproval);
				//dump($companiesNumber);
				if($companiesNumber >0)
					$this->searchFilter["categories"][]=array($cat, $companiesNumber);
			}
		}
		
		$mtime = microtime();
	    $mtime = explode(" ",$mtime);
	    $mtime = $mtime[1] + $mtime[0];
	    $endtime = $mtime;
	    $totaltime = ($endtime - $starttime);
	    //echo "This function was done in ".$totaltime." seconds";
	    
	   // dump($this->searchFilter);
		return $this->searchFilter;
	}
	
	function getCategories(){
		$categoryService = new JBusinessDirectorCategoryLib();
		return $categoryService->getCategories(CATEGORY_TYPE_EVENT);
		
	}	
	
	function getLocation(){
		return $this->location;
	}
	
	function getCategoryId(){
		return $this->categoryId;
	}
	
	function getCategory(){
		$categoryTable = $this->getTable("Category", "JBusinessTable");
		$category = $categoryTable->getCategoryById($this->categoryId);
	
		if($this->appSettings->enable_multilingual){
			JBusinessDirectoryTranslations::updateEntityTranslation($category, CATEGORY_TRANSLATION);
		}
		
		return $category;
	}

	function getEventType(){
		$type = null;
		if(!empty($this->typeSearch)) {
			$eventTypesTable = $this->getTable("EventType","JTable");
			$type = $eventTypesTable->getEventType($this->typeSearch);

			if($this->appSettings->enable_multilingual) {
				JBusinessDirectoryTranslations::updateEntityTranslation($type, TYPE_TRANSLATION);
			}
		}
		return $type;
	}
	
	function getSortByConfiguration(){
		$states = array();
		$state = new stdClass();
		$state->value = '';
		$state->text = JTEXT::_("LNG_RELEVANCE");
		$states[] = $state;
		$state = new stdClass();
		$state->value = 'id desc';
		$state->text = JTEXT::_("LNG_LAST_ADDED");
		$states[] = $state;
		$state = new stdClass();
		$state->value = 'id asc';
		$state->text = JTEXT::_("LNG_FIRST_ADDED");
		$states[] = $state;
		$state = new stdClass();
		$state->value = 'start_date asc';
		$state->text = JTEXT::_("LNG_EARLIEST_DATE");
		$states[] = $state;
		$state = new stdClass();
		$state->value = 'start_date desc';
		$state->text = JTEXT::_("LNG_LATEST_DATE");
		$states[] = $state;
		$state = new stdClass();
		$state->value = 'name';
		$state->text = JTEXT::_("LNG_NAME");
		$states[] = $state;
		$state = new stdClass();
		$state->value = 'city';
		$state->text = JTEXT::_("LNG_CITY");
		$states[] = $state;
		$state = new stdClass();
		$state->value = 'rand()';
		$state->text = JTEXT::_("LNG_RANDOM");
		$states[] = $state;
	
		return $states;
	}
}
?>