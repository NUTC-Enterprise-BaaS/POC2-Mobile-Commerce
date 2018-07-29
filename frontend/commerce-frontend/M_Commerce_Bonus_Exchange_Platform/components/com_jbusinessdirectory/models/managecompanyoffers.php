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


JTable::addIncludePath(DS.'components'.DS.JRequest::getVar('option').DS.'tables');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'offers.php');

class JBusinessDirectoryModelManageCompanyOffers extends JBusinessDirectoryModelOffers{
	
	
	function __construct(){
		parent::__construct();
		$this->appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		$this->_total = 0;
	}
	
	/**
	 * Returns a Table object, always creating it
	 *
	 * @param   type	The table type to instantiate
	 * @param   string	A prefix for the table class name. Optional.
	 * @param   array  Configuration array for model. Optional.
	 * @return  JTable	A database object
	 */
	public function getTable($type = 'Companies', $prefix = 'JTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	/**
	*
	* @return object with data
	*/
	function getOffers()
	{
		// Load the data	
		$offersTable = $this->getTable("Offer");
		$packagesTable = $this->getTable("Package");

		if (empty( $this->_data )) {
			$this->_data = $offersTable->getUserOffers($this->getCompaniesByUserId(), $this->getState('limitstart'), $this->getState('limit'));
			
			if(!empty($this->_data)){
				foreach($this->_data as $offer) {
	
					$offer->allow_offers = false;
					$offer->expired = false;
	
					if(!$this->appSettings->enable_packages) {
						$offer->allow_offers = true;
					} else {
						$package = $packagesTable->getCurrentActivePackage($offer->companyId);
					
						if(!empty($package->features)){
							$offer->features = $package->features;
						} else {
							$offer->features = array();
						}
	
						if (in_array(COMPANY_OFFERS, $offer->features))
							$offer->allow_offers = true;
	
						if( ( !empty($offer->publish_end_date) && $offer->publish_end_date != '0000-00-00' && strtotime($offer->publish_end_date) && (strtotime(date("Y-m-d")) > strtotime($offer->publish_end_date)) ) 
							|| ( !empty($offer->publish_start_date) && $offer->publish_start_date != '0000-00-00' && strtotime($offer->publish_start_date) && (strtotime(date("Y-m-d")) < strtotime($offer->publish_start_date)) ) )
							$offer->expired = true;
					}
				}
			}
		}

		$this->_data;

		return $this->_data;
	}
	
	function getCompaniesByUserId(){
		$user = JFactory::getUser();
		$companiesTable = $this->getTable("Company");
		$companies =  $companiesTable->getCompaniesByUserId($user->id);
		$result = array();
		foreach($companies as $company){
			$result[] = $company->id;
		}
		return $result;
	}
	
	function getTotal()
	{
		// Load the content if it doesn't already exist
		if (empty($this->_total)) {
			$offersTable = $this->getTable("Offer");
			$this->_total = $offersTable->getTotalUserOffers($this->getCompaniesByUserId());
		}
		return $this->_total;
	}
}
?>