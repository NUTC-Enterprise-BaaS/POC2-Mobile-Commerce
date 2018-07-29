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
class SocialadsModelZones extends JModelList
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
				'ordering', 'a.ordering',
				'state', 'a.state',
				'zone_name', 'a.zone_name',
				'published', 'a.published',
				'orientation', 'a.orientation',
				'ad_type', 'a.ad_type',
				'max_title', 'a.max_title',
				'max_des', 'a.max_des',
				'img_width', 'a.img_width',
				'img_height', 'a.img_height',
				'per_click', 'a.per_click',
				'per_imp', 'a.per_imp',
				'per_day', 'a.per_day',
				'num_ads', 'a.num_ads',
				'layout', 'a.layout',

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
	 * @since  1.6
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
	 * @return  JDatabaseQuery
	 *
	 * @since  1.6
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
				$this->getState(
						'list.select', 'DISTINCT a.*'
				)
		);
		$query->from('`#__ad_zone` AS a');

		// Join over the users for the checked out user
		$query->select("uc.name AS editor");
		$query->join("LEFT", "#__users AS uc ON uc.id=a.checked_out");

		// Join over the user field 'created_by'

		/*
		 * $query->select('created_by.name AS created_by');
		$query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');
		*/

		// Filter by published state
		$published = $this->getState('filter.state');

		if (is_numeric($published))
		{
			$query->where('a.state = ' . (int) $published);
		}
		elseif ($published === '')
		{
			$query->where('(a.state IN (0, 1))');
		}

		// Filter by search in title
		$search = $this->getState('filter.search');

		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('a.id = ' . (int) substr($search, 3));
			}
			else
			{
				$search = $db->Quote('%' . $db->escape($search, true) . '%');
							$query->where('( a.id LIKE ' . $search .
						'  OR  a.zone_name LIKE ' . $search .
						'  OR  a.ad_type LIKE ' . $search .
						' )'
						);
			}
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
	 * Get module for zone.
	 *
	 * @return  module
	 *
	 * @since  1.6
	 */
	public function getZoneModules()
	{
		$db = JFactory::getDBO();
		$query = "SELECT params FROM #__modules WHERE published = 1 AND module LIKE '%mod_socialads%'";
		$db->setQuery($query);
		$params = $db->loadObjectList();
		$module = array();

		foreach ($params as $params)
		{
			$params1 = str_replace('"', '', $params->params);

			if (JVERSION >= '1.6.0')
			{
				$single = explode(",", $params1);
			}
			else
			{
				$single = explode("\n", $params1);
			}

			foreach ($single as $single)
			{
				if (JVERSION >= '1.6.0')
				{
					$name = explode(":", $single);
				}
				else
				{
					$name = explode("=", $single);
				}

				if ($name[0] == 'zone')
				{
					$module[] = $name[1];
				}
			}
		}

		return $module;
	}

	/**
	 * Function used while delelting zones number of ads depend on that zones count.
	 *
	 * @param   integer  $selzoneid  The id of the zone
	 *
	 * @return  integer
	 *
	 * @since  1.6
	 */
	public function getZoneaddatacount($selzoneid)
	{
		$db = JFactory::getDBO();
		$query = "SELECT count(d.ad_id) FROM #__ad_data AS d LEFT JOIN #__ad_zone AS z ON d.ad_zone=z.id WHERE z.id=
		" . $selzoneid . " AND d.state = 1 ";
		$db->setQuery($query);
		$createid = $db->loadresult();

		return $createid;
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
