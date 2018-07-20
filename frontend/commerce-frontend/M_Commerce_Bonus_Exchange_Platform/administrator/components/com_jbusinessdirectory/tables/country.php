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

class JTableCountry extends JTable
{

	var $id				= null;	
	var $country_name			= null;


	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(&$db){

		parent::__construct('#__jbusinessdirectory_countries', 'id', $db);
	}

	function setKey($k)
	{
		$this->_tbl_key = $k;
	}

	function getCountries(){
		$db =JFactory::getDBO();
		$query = "select * from #__jbusinessdirectory_countries order by country_name";
		$db->setQuery($query);
		return $db->loadObjectList();
	}
	
	function getCountry($countryId){
		$db =JFactory::getDBO();
		$query = "select * from #__jbusinessdirectory_countries where id=$countryId";
		$db->setQuery($query);
		return $db->loadObject();
	}

	function getRegionsByCountry($countryId) {
		$where = '';
		if(!empty($countryId))
			$where = 'and countryId="'.$countryId.'"';
		$db = JFactory::getDBO();
		$query = 'select distinct county FROM #__jbusinessdirectory_companies where state=1 and county!="" '.$where.' order by county asc';
		$db->setQuery($query);
		return $db->loadObjectList();
	}

	function getCitiesByRegion($region) {
		$where = '';
		if(!empty($region))
			$where = 'and county="'.$region.'"';
		$db = JFactory::getDBO();
		$query = 'select distinct city FROM #__jbusinessdirectory_companies where state=1 and city!="" '.$where.' order by city asc';
		$db->setQuery($query);
		return $db->loadObjectList();
	}

	function getCitiesByCountry($countryId) {
		$where = '';
		if(!empty($countryId))
			$where = 'and countryId="'.$countryId.'"';
		$db = JFactory::getDBO();
		$query = 'select distinct city FROM #__jbusinessdirectory_companies where state=1 and city!="" '.$where.' order by city asc';
		$db->setQuery($query);
		return $db->loadObjectList();
	}

}