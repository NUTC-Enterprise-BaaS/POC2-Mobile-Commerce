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
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'models'.DS.'events.php');

class JBusinessDirectoryModelManageCompanyEvents extends JBusinessDirectoryModelEvents{
	
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
	function getEvents()
	{
		// Load the data
		$user = JFactory::getUser();
		$packagesTable = $this->getTable("Package"); 
		$companiesTable = $this->getTable("Company");
		$this->companyIds = $this->getCompaniesByUserId();
		$eventsTable = JTable::getInstance('Event','JTable', array());

		if (empty( $this->_data ) && !empty($this->companyIds)) {
			$this->_data = $eventsTable->getUserEvents($this->companyIds, $this->getState('limitstart'), $this->getState('limit'));

			foreach($this->_data as $event) {

				$event->allow_events = false;
				$event->expired = false;

				if(!$this->appSettings->enable_packages) {
					$event->allow_events = true;
				} else {
					$package = $packagesTable->getCurrentActivePackage($event->company_id);

					if(!empty($package->features)){
						$event->features = $package->features;
					} else {
						$event->features = array();
					}

					if (in_array(COMPANY_EVENTS, $event->features))
						$event->allow_events = true;

					if( !empty($event->end_date) && $event->end_date != '0000-00-00' && (strtotime(date("Y-m-d")) > strtotime($event->end_date)) )
						$event->expired = true;
				}
			}
		}
		
		if(empty($this->_data)){
			$this->_data = array();
		}
		
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
		$this->companyIds = $this->getCompaniesByUserId();
		if (!isset($this->_total) && !empty($this->companyIds)) {
			$eventsTable = JTable::getInstance('Event','JTable', array());
			$this->_total = $eventsTable->getTotalUserEvents($this->companyIds);
		}
		return $this->_total;
	}
}
?>