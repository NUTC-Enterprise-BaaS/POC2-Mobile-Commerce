<?php
/**
 * @version    SVN:<SVN_ID>
 * @package    SocialAds
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved
 * @license    GNU General Public License version 2, or later
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Socialads records.
 *
 * @since  1.6
 */
class SocialadsModelCampaigns extends JModelList
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @see   JController
	 *
	 * @since   1.6
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'a.id',
				'user_id', 'a.user_id',
				'ordering', 'a.ordering',
				'state', 'a.state',
				'uname', 'u.name',
				'campaign', 'a.campaign',
				'daily_budget', 'a.daily_budget',
				'state', 'a.state',
				'no_of_ads', 'no_of_ads',
				'clicks', 'clicks',
				'impressions', 'impressions',
				'ctr','ctr'
			);
		}

		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   integer  $ordering   An optional associative array of configuration settings.
	 * @param   integer  $direction  An optional associative array of configuration settings.
	 *
	 * @return  integer
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

		// Load the filter state.
		$search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$published = $app->getUserStateFromRequest($this->context . '.filter.state', 'filter_published', '', 'string');
		$this->setState('filter.state', $published);

		// Load the parameters.
		$params = JComponentHelper::getParams('com_socialads');
		$this->setState('params', $params);

		// Filter created by.
		$createdBy = $app->getUserStateFromRequest($this->context . '.filter.usernamelist', 'filter_usernamelist', '', 'string');
		$this->setState('filter.usernamelist', $createdBy);

		// List state information.
		parent::populateState('a.id', 'asc');
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string  $id  A prefix for the store id.
	 *
	 * @return  string  A store id.
	 *
	 * @since	1.6
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.state');

		return parent::getStoreId($id);
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return	JDatabaseQuery
	 *
	 * @since	1.6
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$user = JFactory::getUser();
		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.

		$query->select($this->getState('list.select', 'DISTINCT a.*'));
		$query->select('COUNT(d.camp_id) as no_of_ads, SUM(d.clicks) as clicks, SUM(impressions) as impressions, u.name as uname');
		$query->from($db->quoteName('#__ad_campaign', 'a'));

		// Join over the users for the checked out user.
		$query->select($db->quoteName('uc.name', 'editor'));
		$query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');
		$query->join('LEFT', '#__ad_data AS d ON d.camp_id=a.id');

		// Join over the created by field 'created_by'
		$query->join('LEFT', '#__users AS u ON u.id = a.created_by');

		if (!JFactory::getUser()->authorise('core.edit.state', 'com_socialads'))
		{
			$query->where($db->quoteName('a.state') . '= 1');
		}

		$query->group('a.id');

		// Filter by search in title
		$search = $this->getState('filter.search');

		if (!empty($search))
		{
			$search = $db->Quote('%' . $db->escape($search, true) . '%');
			$query->where('( a.id LIKE ' . $search .
					'  OR  a.campaign LIKE ' . $search .
					'  OR  a.daily_budget LIKE ' . $search .
					' )'
					);
		}

		// Filter by username
		$filterCreator = $this->getState('filter.usernamelist');

		if (!empty($filterCreator))
		{
			$query->where($db->quoteName('a.created_by') . '= ' . $filterCreator);
		}

		// Add the list ordering clause.
		$orderCol  = $this->state->get('list.ordering');
		$orderDirn = $this->state->get('list.direction');

		if ($orderCol && $orderDirn)
		{
			$query->order($db->escape($orderCol . ' ' . $orderDirn));
		}

		return $query;
	}

	/**
	 * To get the values from table
	 *
	 * @return  items
	 *
	 * @since  1.6
	 */
	public function getItems()
	{
		$items = parent::getItems();

		return $items;
	}
}
