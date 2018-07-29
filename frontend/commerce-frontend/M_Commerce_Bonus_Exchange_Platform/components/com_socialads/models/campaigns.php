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
 *
 */
class SocialadsModelCampaigns extends JModelList
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @see        JController
	 * @since      1.6
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id',
				'a.id',
				'camp_id',
				'a.camp_id',
				'user_id',
				'a.user_id',
				'ordering',
				'a.ordering',
				'state',
				'a.state',
				'created_by',
				'a.created_by',
				'campaign',
				'a.campaign',
				'daily_budget',
				'a.daily_budget',
				'camp_published',
				'a.camp_published',
				'no_of_ads',
				'clicks',
				'impressions'
			);
		}

		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   STRING  $ordering   ordering
	 *
	 * @param   STRING  $direction  direction
	 *
	 * @return  void
	 *
	 * @since    1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication();

		// Load the filter state.
		$search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		// List state information
		$limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->get('list_limit'));
		$this->setState('list.limit', $limit);

		$limitstart = $app->input->getInt('limitstart', 0);
		$this->setState('list.start', $limitstart);

		if ($list = $app->getUserStateFromRequest($this->context . '.list', 'list', array(), 'array'))
		{
			foreach ($list as $name => $value)
			{
				// Extra validations
				switch ($name)
				{
					case 'fullordering':
						$orderingParts = explode(' ', $value);

						if (count($orderingParts) >= 2)
						{
							// Latest part will be considered the direction
							$fullDirection = end($orderingParts);

							if (in_array(strtoupper($fullDirection), array('ASC', 'DESC', '')))
							{
								$this->setState('list.direction', $fullDirection);
							}

							unset($orderingParts[count($orderingParts) - 1]);

							// The rest will be the ordering
							$fullOrdering = implode(' ', $orderingParts);

							if (in_array($fullOrdering, $this->filter_fields))
							{
								$this->setState('list.ordering', $fullOrdering);
							}
						}
						else
						{
							$this->setState('list.ordering', $ordering);
							$this->setState('list.direction', $direction);
						}
						break;

					case 'ordering':
						if (!in_array($value, $this->filter_fields))
						{
							$value = $ordering;
						}
						break;

					case 'direction':
						if (!in_array(strtoupper($value), array('ASC','DESC','')))
						{
							$value = $direction;
						}
						break;

					case 'limit':
						$limit = $value;
						break;

					// Just to keep the default case
					default:
						$value = $value;
						break;
				}

				$this->setState('list.' . $name, $value);
			}
		}

		// Receive & set filters
		if ($filters = $app->getUserStateFromRequest($this->context . '.filter', 'filter', array(), 'array'))
		{
			foreach ($filters as $name => $value)
			{
				$this->setState('filter.' . $name, $value);
			}
		}

		$ordering = $app->input->get('filter_order');

		if (!empty($ordering))
		{
			$list             = $app->getUserState($this->context . '.list');
			$list['ordering'] = $app->input->get('filter_order');
			$app->setUserState($this->context . '.list', $list);
		}

		$orderingDirection = $app->input->get('filter_order_Dir');

		if (!empty($orderingDirection))
		{
			$list              = $app->getUserState($this->context . '.list');
			$list['direction'] = $app->input->get('filter_order_Dir');
			$app->setUserState($this->context . '.list', $list);
		}

		$list = $app->getUserState($this->context . '.list');

		if (empty($list['ordering']))
		{
			$list['ordering'] = 'ordering';
		}

		if (empty($list['direction']))
		{
			$list['direction'] = 'asc';
		}

		if (isset($list['ordering']))
		{
			$this->setState('list.ordering', $list['ordering']);
		}

		if (isset($list['direction']))
		{
			$this->setState('list.direction', $list['direction']);
		}
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return    JDatabaseQuery
	 *
	 * @since    1.6
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$user = JFactory::getUser();
		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.

		$query->select($this->getState('list.select', 'DISTINCT a.*'));
		$query->select('COUNT(d.camp_id) as no_of_ads, SUM(d.clicks) as clicks, SUM(impressions) as impressions');
		$query->from($db->quoteName('#__ad_campaign', 'a'));

		// Join over the users for the checked out user.
		$query->select($db->quoteName('uc.name', 'editor'));
		$query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');
		$query->join('LEFT', '#__ad_data AS d ON d.camp_id=a.id');

		// Join over the created by field 'created_by'
		$query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');

		$query->where($db->quoteName('a.created_by') . ' = ' . $user->id);

		$query->group('a.id');

		// Filter by search in title
		$search = $this->getState('filter.search');

		if (!empty($search))
		{
			$query->where($db->quoteName('a.campaign') . 'LIKE ' . "'%" . $search . "%'");
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
	 * Method to get item data
	 *
	 * @return  form data
	 *
	 * @since   2.2
	 */
	public function getItems()
	{
		$items = parent::getItems();

		return $items;
	}

	/**
	 * Method to load form data
	 *
	 * @return  form data
	 *
	 * @since   2.2
	 */
	protected function loadFormData()
	{
		$app              = JFactory::getApplication();
		$filters          = $app->getUserState($this->context . '.filter', array());
		$error_dateformat = false;

		foreach ($filters as $key => $value)
		{
			if (strpos($key, '_dateformat') && !empty($value) && !$this->isValidDate($value))
			{
				$filters[$key]    = '';
				$error_dateformat = true;
			}
		}

		if ($error_dateformat)
		{
			$app->enqueueMessage(JText::_("COM_SOCIALADS_SEARCH_FILTER_DATE_FORMAT"), "warning");
			$app->setUserState($this->context . '.filter', $filters);
		}

		return parent::loadFormData();
	}

	/**
	 * Checks if a given date is valid and in an specified format (YYYY-MM-DD)
	 *
	 * @param   string  $date  Contains the date to be checked
	 *
	 * @return date
	 */
	private function isValidDate($date)
	{
		return preg_match("/^(19|20)\d\d[-](0[1-9]|1[012])[-](0[1-9]|[12][0-9]|3[01])$/", $date) && date_create($date);
	}

	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param   type    $type    The table type to instantiate
	 * @param   string  $prefix  A prefix for the table class name. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return   JTable    A database object
	 *
	 * @since    1.6
	 */
	public function getTable($type = 'campaign', $prefix = 'SocialadsTable', $config = array())
	{
		$this->addTablePath(JPATH_COMPONENT_ADMINISTRATOR . '/tables');

		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to delete ads
	 *
	 * @param   array  $cid  The array of ad ids.
	 *
	 * @return  true or false
	 *
	 * @since   2.2
	 */
	public function delete($cid)
	{
		$table = $this->getTable();
		$table->delete($cid);
	}

	/**
	 * Method to publish campaigns
	 *
	 * @param   array  $cid  The array of campaigns ids.
	 *
	 * @return  true or false
	 *
	 * @since   2.2
	 */
	public function publish($cid)
	{
		$cid   = implode(',', $cid);
		$db    = $this->getDbo();
		$app   = JFactory::getApplication();
		$query = $db->getQuery(true);
		$query->update($db->quoteName('#__ad_campaign'))->set($db->quoteName('state') . ' = 1')->where($db->quoteName('id') . ' IN (' . $cid . ')');
		$db->setQuery($query);
		$db->execute();
	}

	/**
	 * Method to unpublish campaigns
	 *
	 * @param   array  $cid  The array of campaign ids.
	 *
	 * @return  true or false
	 *
	 * @since   2.2
	 */
	public function unpublish($cid)
	{
		$cid   = implode(',', $cid);
		$db    = $this->getDbo();
		$app   = JFactory::getApplication();
		$query = $db->getQuery(true);
		$query->update($db->quoteName('#__ad_campaign'))->set($db->quoteName('state') . ' = 0')->where($db->quoteName('id') . ' IN (' . $cid . ')');
		$db->setQuery($query);
		$db->execute();
	}
}
