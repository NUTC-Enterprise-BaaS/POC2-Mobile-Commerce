<?php
/**
 * @version    SVN:<SVN_ID>
 * @package    Com_Socialads
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
class SocialadsModelIgnores extends JModelList
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @since   1.6
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
								'id', 'a.id',
				'ordering', 'a.ordering',
				'state', 'a.state',
				'created_by', 'a.created_by',
				'adid', 'a.adid',
				'userid', 'a.userid',
				'ad_dump', 'a.ad_dump',
				'ad_feedback', 'a.ad_feedback',
				'idate', 'a.idate',

			);
		}

		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   String  $ordering   ordering for table
	 *
	 * @param   String  $direction  direction for table
	 *
	 * @return	none
	 *
	 * @since	1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication('site');

		// Load the filter state.
		$search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		// List state information.
		parent::populateState('ignored_by', 'asc');
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return	JDatabaseQuery
	 *
	 * @since	1.6
	 */
	public function getListQuery()
	{
		$input = JFactory::getApplication()->input;
		$adid = $input->get('adid');

		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select($db->quoteName(array('u.id', 'a.ad_feedback', 'a.idate')));
		$query->select($db->quoteName('u.username', 'ignored_by'));
		$query->from($db->quoteName('#__ad_ignore', 'a'));

		// Join over the user field 'irnored_by'
		$query->join('RIGHT', $db->quoteName('#__users', 'u') . 'ON' . $db->quoteName('a.userid') . '=' . $db->quoteName('u.id'));
		$query->where('a.adid = ' . $adid);

		// Filter by search in title
		$search = $this->getState('filter.search');

		if (!empty($search))
		{
			$query->where("(u.username LIKE '" . $search . "' or " . "a.ad_feedback LIKE '" . $search . "')");
		}

		// Add the list ordering clause.
		$orderCol = $this->state->get('list.ordering');
		$orderDirn = $this->state->get('list.direction');

		if ($orderCol && $orderDirn)
		{
			$query->order($db->escape($orderCol . ' ' . $orderDirn));
		}

		return $query;
	}

	/**
	 * Execute SQL query to load the list data.
	 *
	 * @return	data
	 *
	 * @since	1.6
	 */
	public function getItems()
	{
		$items = parent::getItems();

		return $items;
	}
}
