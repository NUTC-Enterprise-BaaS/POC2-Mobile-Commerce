<?php
/*------------------------------------------------------------------------
# JBusinessDirectory
# author CMSJunkie
# copyright Copyright (C) 2012 cmsjunkie.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.cmsjunkie.com
# Technical Support:  Forum - http://www.cmsjunkie.com/forum/j-businessdirectory/?p=1
-------------------------------------------------------------------------*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modelitem');

class JBusinessDirectoryModelReports extends JModelList { 

	/**
	 * Constructor.
	 *
	 * @param   array  An optional associative array of configuration settings.
	 *
	 * @see     JController
	 * @since   1.6
	 */
	public function __construct($config = array()) {
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 'r.id',
				'name', 'r.name',
				'description', 'r.description'
			);
		}

		parent::__construct($config);
	}
	
	
	/**
	 * Overrides the getItems method to attach additional metrics to the list.
	 *
	 * @return  mixed  An array of data items on success, false on failure.
	 *
	 * @since   1.6.1
	 */
	public function getItems()
	{
		// Get a storage key.
		$store = $this->getStoreId('getItems');
	
		// Try to load the data from internal storage.
		if (!empty($this->cache[$store]))
		{
			return $this->cache[$store];
		}
	
		// Load the list items.
		$items = parent::getItems();
	
		// If emtpy or an error, just return.
		if (empty($items)){
			return array();
		}
	
		// Add the items to the internal cache.
		$this->cache[$store] = $items;
	
		return $this->cache[$store];
	}
	
	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return  string  An SQL query
	 *
	 * @since   1.6
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);
	
	
		// Select all fields from the table.
		$query->select($this->getState('list.select', 'r.*'));
		$query->from($db->quoteName('#__jbusinessdirectory_reports').' AS r');
	
	
		$query->group('r.id');
	
		// Add the list ordering clause.
		$query->order($db->escape($this->getState('list.ordering', 'r.id')).' '.$db->escape($this->getState('list.direction', 'ASC')));
	
		return $query;
	}
	
	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc|desc).
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		$app = JFactory::getApplication('administrator');
	
		// Check if the ordering field is in the white list, otherwise use the incoming value.
		$value = $app->getUserStateFromRequest($this->context.'.ordercol', 'filter_order', $ordering);
		$this->setState('list.ordering', $value);
	
		// Check if the ordering direction is valid, otherwise use the incoming value.
		$value = $app->getUserStateFromRequest($this->context.'.orderdirn', 'filter_order_Dir', $direction);
		$this->setState('list.direction', $value);
	
		// List state information.
		parent::populateState('r.id', 'desc');
	}
	

	function getReports() {
		$reportTable = JTable::getInstance("Report", "JTable");
		$reports = $reportTable->getReports();
		
		return $reports;
	}
	
	function getReportData() {
		$reportId = JRequest::getVar("reportId");

		if(empty($reportId))
			return null;
		
		$reportTable = JTable::getInstance("Report", "JTable");
		$report = $reportTable->getReport($reportId);
		
		if($report->type == 1) {
			$reportData = $reportTable->getConferenceReportData($report->selected_params);
		}
		else {
			$orderBy = JRequest::getVar("orderBy","cp.id");
			$reportData = $reportTable->getReportData($report->selected_params, $orderBy);
		}
		
		$generatedReport = new stdClass();
		$generatedReport->headers = explode(",",$report->selected_params);
		$generatedReport->customHeaders = explode(",",$report->custom_params);
		$generatedReport->data = $reportData;
		$generatedReport->report = $report;

		if($report->type == 0) {
			$attributesTable = JTable::getInstance("Attribute", "JTable");
			$generatedReport->attributes = $attributesTable->getAttributes();
			$generatedReport->customHeaders= $this->processHeaders($generatedReport->customHeaders, $generatedReport->attributes);
			
			$attributeOptionsTable = JTable::getInstance("AttributeOptions", "JTable");
			$attributeOptions = $attributeOptionsTable->getAllAttributeOptions();
		
			$generatedReport->data = $this->processData($generatedReport->data, $attributeOptions);
		}
		
		return $generatedReport;
	}
	
	function processData($reportData, $attributeOptions) {
		foreach($reportData as $data){
			$data->customAttributes = array(); 
			$customAttributes = explode("#",$data->custom_attributes);
			foreach($customAttributes as $customAttribute){
				$values = explode("||",$customAttribute);
				$obj = new stdClass();
			
				if(count($values)<3)
					continue;
				
				$obj->name = $values[0];
				$obj->code = $values[1];
				$obj->atr_code = $values[2];
				$obj->value = $values[3];
				if($obj->atr_code !="input"){
					$values = explode(",",$obj->value);
					$result = array();
					foreach($values as $value){
						foreach($attributeOptions as $attributeOption){
							if($value == $attributeOption->id){
								$result[] = $attributeOption->name;
							}
						}
					}
					if(!empty($result))
						$obj->value = implode(",",$result);
				}
				$data->customAttributes[$obj->name] = $obj;
			}
		}
		return $reportData;
	}
	
	function processHeaders($headers, $attributes) {
		$result = array();
		foreach($headers as $header){
			foreach($attributes as $attribute){
				if($attribute->code == $header){
					$result[] = $attribute->name;
				}
			}
		}
		return $result;
	}

	function exportReportToCSV($generatedReport) {
		require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/helper.php';
		
		$csv_output="";
		$params = JBusinessDirectoryHelper::getCompanyParams();
		$conferenceParams =  JBusinessDirectoryHelper::getConferenceParams();

		if($generatedReport->report->type == 1) {
			foreach ($generatedReport->headers as $header) {
				$csv_output .= JText::_($conferenceParams[$header]);
				$csv_output .= ";";
			}
		} else {
			foreach ($generatedReport->headers as $header) {
				$csv_output .= JText::_($params[$header]);
				$csv_output .= ";";
			}
			foreach ($generatedReport->customHeaders as $header) {
				$csv_output .=  $header;
				$csv_output .= ";";
			}
		}

		$csv_output .= "\n";
		
		if($generatedReport->report->type == 1) {
			foreach ($generatedReport->data as $data) {
				foreach ($generatedReport->headers as $header) {
					$csv_output .= $data->$header;
					$csv_output .= ";";
				}
				$csv_output .= "\n";
			}
		} else {
			foreach ($generatedReport->data as $data) {
				foreach ($generatedReport->headers as $header) {
					$csv_output .= $data->$header;
					$csv_output .= ";";
				}
				foreach ($generatedReport->customHeaders as $header) {
					$csv_output .=  !empty($data->customAttributes[$header])?$data->customAttributes[$header]->value:"";
					$csv_output .= ";";
				}
				$csv_output .= "\n";
			}
		}
		
		$fileName = "jbusinessdirectory_report";
		header("Content-type: application/vnd.ms-excel");
		header("Content-disposition: csv" . date("Y-m-d") . ".csv");
		header("Content-disposition: filename=".$fileName.".csv");
		print $csv_output;
	}
}
?>

