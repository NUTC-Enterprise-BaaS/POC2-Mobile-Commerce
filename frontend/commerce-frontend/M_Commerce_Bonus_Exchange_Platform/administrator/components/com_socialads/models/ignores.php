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
	 * @see  JController
	 *
	 * @since  1.6
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
				'username', 'u.username',

			);
		}

		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * @param   integer  $ordering   An optional associative array of configuration settings.
	 * @param   integer  $direction  An optional associative array of configuration settings.
	 *
	 * @return  integer
	 *
	 * Note. Calling getState in this method will result in recursion.
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

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
		$query->select('u.id, u.username as ignored_by, a.ad_feedback, a.idate');
		$query->from('`#__ad_ignore` AS a');

		// Join over the user field 'irnored_by'
		$query->join('RIGHT', '#__users AS u ON a.userid = u.id');
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
