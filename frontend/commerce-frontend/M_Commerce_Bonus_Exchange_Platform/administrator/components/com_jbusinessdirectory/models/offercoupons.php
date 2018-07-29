<?php
/**
 * @package     JBusinessDirectory
 * @subpackage  com_jbusinessdirectory
 *
 * @copyright   Copyright (C) 2007 - 2015 CMS Junkie. All rights reserved.
 * @license     GNU General Public License version 2 or later; 
 */

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.modellist');

/**
 * List Model.
 *
 * @package    JBusinessDirectory
 * @subpackage com_jbusinessdirectory
 */
class JBusinessDirectoryModelOfferCoupons extends JModelList {

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
				'id', 'ofc.id',
				'company', 'co.name',
				'phone', 'co.phone',
				'offer', 'of.subject',
				'expiration_time', 'of.endDate',
				'coupon', 'ofc.code',
				'generated_time', 'ofc.generated_time'
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
	public function getItems() {
		// Get a storage key.
		$store = $this->getStoreId('getItems');

		// Try to load the data from internal storage.
		if (!empty($this->cache[$store])) {
			return $this->cache[$store];
		}

		// Load the list items.
		$items = parent::getItems();

		// If emtpy or an error, just return.
		if (empty($items)) {
			return array();
		}
		
		foreach($items as $item) {
			$item->generated_time = JBusinessUtil::convertToFormat($item->generated_time);
			$item->expiration_time = JBusinessUtil::convertToFormat($item->expiration_time);
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
	protected function getListQuery() {

		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		
		// Select all fields from the table.
		$query->select($this->getState('list.select', 'ofc.*'));
		$query->from($db->quoteName('#__jbusinessdirectory_company_offer_coupons').' AS ofc');
		
		// Join over the offer
		$query->select('of.subject as offer, of.endDate as expiration_time');
		$query->join('LEFT', $db->quoteName('#__jbusinessdirectory_company_offers').' AS of ON of.id=ofc.offer_id');

		// Join over the company
		$query->select('co.name as company, co.phone as phone, co.id as company_id');
		$query->join('LEFT', $db->quoteName('#__jbusinessdirectory_companies').' AS co ON co.id=of.companyId');
		
		// Filter by search.
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			$query->where("co.name LIKE '%".trim($db->escape($search))."%' or 
							of.subject LIKE '%".trim($db->escape($search))."%' or
							ofc.code LIKE '%".trim($db->escape($search))."%'");
		}
	
		$query->group('ofc.id');

		// Add the list ordering clause.
		$query->order($db->escape($this->getState('list.ordering', 'ofc.id')).' '.$db->escape($this->getState('list.direction', 'ASC')));

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
	protected function populateState($ordering = null, $direction = null) {

		$app = JFactory::getApplication('administrator');

		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);
	
		// Check if the ordering field is in the white list, otherwise use the incoming value.
		$value = $app->getUserStateFromRequest($this->context.'.ordercol', 'filter_order', $ordering);
		$this->setState('list.ordering', $value);
	
		// Check if the ordering direction is valid, otherwise use the incoming value.
		$value = $app->getUserStateFromRequest($this->context.'.orderdirn', 'filter_order_Dir', $direction);
		$this->setState('list.direction', $value);
		
		// List state information.
		parent::populateState('ofc.id', 'desc');
	}
}