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

class modJBusinessOfferSearchHelper{
	
	static function getMainCategories(){
		$db = JFactory::getDBO();
		$query = ' SELECT * FROM #__jbusinessdirectory_categories 
					where parent_id=1 and published=1 and type='.CATEGORY_TYPE_OFFER.' 
					order by name';
		$db->setQuery($query);
		return $db->loadObjectList();
	}
	
	static function getSubCategories(){
		$db = JFactory::getDBO();
		$query = ' SELECT c.* FROM #__jbusinessdirectory_categories c
                   inner join  #__jbusinessdirectory_categories  cc  on c.parent_id = cc.id  
                   where c.parent_id!=1  and cc.parent_id = 1 and c.published=1 and c.type='.CATEGORY_TYPE_OFFER.'
                   order by c.name';
		$db->setQuery($query,0,1000);
		$result = $db->loadObjectList();

		return $result;
	}

	static function getCities(){
		$db = JFactory::getDBO();
		$query = ' SELECT distinct city FROM #__jbusinessdirectory_company_offers where state =1 and city!="" order by city asc';
		$db->setQuery($query);
		return $db->loadObjectList();
	}
	
	static function getRegions(){
		$db = JFactory::getDBO();
		$query = 'SELECT distinct county FROM #__jbusinessdirectory_company_offers where state =1 and county!="" order by county asc';
		$db->setQuery($query);
		return $db->loadObjectList();
	}
	
	static function getActivityCities(){
		$db = JFactory::getDBO();
		$query = ' SELECT distinct name as city FROM #__jbusinessdirectory_cities order by name asc';
		$db->setQuery($query);
		return $db->loadObjectList();
	}

	static function getOffers($maxOffers) {

		$offers = JRequest::getVar("search-results");
		if(!empty($offers)) {
			return $offers;
		}

		JTable::addIncludePath(JPATH_ROOT.'/administrator/components/com_jbusinessdirectory/tables');
		$offersTable = JTable::getInstance('Offer','JTable');
		$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		$searchDetails = array();
		$searchDetails["enablePackages"] = $appSettings->enable_packages;
		$searchDetails["showPendingApproval"] = $appSettings->show_pending_approval==1;;

		$offers =  $offersTable->getOffersByCategories($searchDetails, 0, $maxOffers);

		foreach($offers as $offer) {
			$attributesTable =  JTable::getInstance('OfferAttributes','JTable');
			$offer->customAttributes = $attributesTable->getOfferAttributes($offer->id);
		}

		return $offers;
	}
}
?>
