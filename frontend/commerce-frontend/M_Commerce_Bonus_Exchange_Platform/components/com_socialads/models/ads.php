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
class SocialadsModelAds extends JModelList
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
				'id',
				'a.ad_id',
				'ordering',
				'a.ordering',
				'state',
				'a.state',
				'a.created_by',
				'ad_id',
				'created_by',
				'a.created_by',
				'ad_url1',
				'a.ad_url1',
				'ad_url2',
				'a.ad_url2',
				'ad_title',
				'a.ad_title',
				'ad_body',
				'a.ad_body',
				'ad_image',
				'a.ad_image',
				'ad_startdate',
				'a.ad_startdate',
				'ad_enddate',
				'a.ad_enddate',
				'ad_noexpiry',
				'a.ad_noexpiry',
				'ad_payment_type',
				'a.ad_payment_type',
				'ad_credits',
				'a.ad_credits',
				'ad_credits_balance',
				'a.ad_credits_balance',
				'ad_created_date',
				'a.ad_created_date',
				'ad_modified_date',
				'a.ad_modified_date',
				'ad_published',
				'a.ad_published',
				'ad_approved',
				'a.ad_approved',
				'ad_alternative',
				'a.ad_alternative',
				'ad_guest',
				'a.ad_guest',
				'ad_affiliate',
				'a.ad_affiliate',
				'ad_zone',
				'a.ad_zone',
				'layout',
				'a.layout',
				'camp_id',
				'a.camp_id',
				'bid_value',
				'a.bid_value',
				'clicks','clicks',
				'impressions','impressions'
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
		$app = JFactory::getApplication('site');

		// Load the filter state.
		$campaignslist = $app->getUserStateFromRequest($this->context . '.filter.campaignslist', 'filter_campaignslist');
		$this->setState('filter.campaignslist', $campaignslist);
		$zoneslist = $app->getUserStateFromRequest($this->context . '.filter.zoneslist', 'filter_zoneslist');
		$this->setState('filter.zoneslist', $zoneslist);
		$adstatus = $app->getUserStateFromRequest($this->context . '.filter.adstatus', 'filter_adstatus');
		$this->setState('filter.adstatus', $adstatus);

		// Load the parameters.
		$ad_params = JComponentHelper::getParams('com_socialads');
		$this->setState('params', $ad_params);

		// List state information.
		parent::populateState('a.ad_id', 'asc');
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
		$user  = JFactory::getUser();
		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select($this->getState('list.select', 'DISTINCT a.*'));
		$query->select($db->quoteName('z.zone_name'));
		$query->select($db->quoteName('ao.status'));
		$query->select($db->quoteName('c.campaign'));

		// $query->select("z.zone_name");
		$query->from($db->quoteName('#__ad_data', 'a'));
		$query->join('LEFT', $db->quoteName('#__ad_campaign', 'c') . 'ON' . $db->quoteName('a.camp_id') . '=' . $db->quoteName('c.id'));
		$query->join('LEFT', $db->quoteName('#__ad_zone', 'z') . 'ON' . $db->quoteName('a.ad_zone') . '=' . $db->quoteName('z.id'));
		$query->join('LEFT', $db->quoteName('#__ad_payment_info', 'p') . 'ON' . $db->quoteName('p.ad_id') . '=' . $db->quoteName('a.ad_id'));
		$query->join('LEFT', $db->quoteName('#__ad_orders', 'ao') . 'ON' . $db->quoteName('ao.id') . '=' . $db->quoteName('p.order_id'));
		$query->where($db->quoteName('a.created_by') . '=' . $user->id);
		$query->group($db->quoteName('a.ad_id'));
		$db->setQuery($query);

		// Filter by search in title
		$campaignslist = $this->getState('filter.campaignslist');

		if ($campaignslist)
		{
			$query->where($db->quoteName('a.camp_id') . '=' . $campaignslist);
		}

		// Filter by zone
		$zoneslist = $this->getState('filter.zoneslist');

		if ($zoneslist)
		{
			$query->where($db->quoteName('z.id') . '=' . $zoneslist);
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
	 * get items
	 *
	 * @return  JDatabaseQuery
	 *
	 * @since   1.6
	 */
	public function getItems()
	{
		$items = parent::getItems();
		$adsData = array();
		$i = 0;

		// Sorting ads according to ad status
		if ($this->getState('filter.adstatus') == "")
		{
			return $items;
		}
		else
		{
			foreach ($items as $item)
			{
				// If ad is not expired
				if ($this->getState('filter.adstatus') == 1)
				{
					if (SaAdEngineHelper::getInstance()->getAdStatus($item->ad_id))
					{
						$adsData[$i] = new stdclass;
						$adsData[$i] = $item;
						$i++;
					}
				}
				elseif ($this->getState('filter.adstatus') == 0)
				{
					// If ad is expired
					if (!SaAdEngineHelper::getInstance()->getAdStatus($item->ad_id))
					{
						$adsData[$i] = new stdclass;
						$adsData[$i] = $item;
						$i++;
					}
				}
			}

			return $adsData;
		}
	}

	/**
	 * Method to find Ignore count of perticular ad
	 *
	 * @param   integer  $ad_id  An ad_id for perticular ad
	 *
	 * @return  integer
	 *
	 * @since  1.6
	 */
	public function getIgnorecount($ad_id)
	{
		$db    = JFactory::getDbo();
		$query = "SELECT COUNT(adid) FROM #__ad_ignore
					WHERE adid=" . $ad_id;
		$db->setQuery($query);
		$ignorecount = $db->loadresult();

		return $ignorecount;
	}

	/**
	 * Method to publish ads
	 *
	 * @param   array  $cid  The array of record ids.
	 *
	 * @return  integer  The number of records updated.
	 *
	 * @since   2.2
	 */
	public function publish($cid)
	{
		$cid   = implode(',', $cid);
		$db    = $this->getDbo();
		$app   = JFactory::getApplication();
		$query = $db->getQuery(true);
		$query->update($db->quoteName('#__ad_data'))->set($db->quoteName('state') . ' = 1')->where($db->quoteName('ad_id') . ' IN (' . $cid . ')');
		$db->setQuery($query);
		$db->execute();
	}

	/**
	 * Method to publish ads
	 *
	 * @param   array  $cid  The array of record ids.
	 *
	 * @return  integer  The number of records updated.
	 *
	 * @since   2.2
	 */
	public function unpublish($cid)
	{
		$cid   = implode(',', $cid);
		$db    = $this->getDbo();
		$app   = JFactory::getApplication();
		$query = $db->getQuery(true);
		$query->update($db->quoteName('#__ad_data'))->set($db->quoteName('state') . ' = 0')->where($db->quoteName('ad_id') . ' IN (' . $cid . ')');
		$db->setQuery($query);
		$db->execute();
	}

	/**
	 * Method to delete ads
	 *
	 * @param   array  $adid  The array of ad ids.
	 *
	 * @return  true or false
	 *
	 * @since   2.2
	 */
	public function delete($adid)
	{
		$table = $this->getTable();
		$table->delete($adid);
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
	public function getTable($type = 'Form', $prefix = 'SocialadsTable', $config = array())
	{
		$this->addTablePath(JPATH_COMPONENT_ADMINISTRATOR . '/tables');

		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Returns data for line chart in adsummary view.
	 *
	 * @return   Array    Data for pie chart
	 *
	 * @since    1.6
	 */
	public function getstatsforlinechart()
	{
		$input = JFactory::getApplication()->input;
		$user  = JFactory::getUser();
		$post  = $input->post;
		$ad_id = $input->get('adid');

		$statistics = array();

		$to_date = date('Y-m-d');
		$from_date = date('Y-m-d', strtotime(date('Y-m-d') . ' - 30 days'));

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query1 = $db->getQuery(true);

		// Query to get data from stats table
		$query->select(
		'DATE(time) as date, COUNT(IF(display_type="1",1, NULL)) as click , COUNT(IF(display_type="0",1, NULL)) as impression, d.ad_id, d.camp_id'
		);
		$query->from($db->quoteName('#__ad_stats', 'as'));
		$query->join('LEFT', $db->quoteName('#__ad_data', 'd') . ' ON ' . $db->quoteName('as.ad_id') . '=' . $db->quoteName('d.ad_id'));
		$query->join('LEFT', $db->quoteName('#__ad_zone', 'z') . ' ON ' . $db->quoteName('d.ad_id') . '=' . $db->quoteName('z.id'));
		$query->where($db->quoteName('d.created_by') . '=' . $user->id);
		$query->where("DATE(time) BETWEEN DATE('" . $from_date . "') AND DATE('" . $to_date . "')");

		// Filter for zone for stats
		if ($this->getState('filter.zoneslist'))
		{
			$query->where($db->quoteName('d.ad_zone') . " = " . $this->getState('filter.zoneslist'));
		}

		// Filter for campaign for stats
		$campaignslist = $this->getState('filter.campaignslist');

		if ($campaignslist)
		{
			$query->where($db->quoteName('d.camp_id') . '=' . $campaignslist);
		}

		$query->group('DATE(time)');
		$query->order('DATE(time)');
		$db->setQuery($query);
		$stats = $db->loadObjectlist();

		// Query to get data from archive stats
		$query1->select('DATE(aas.date) as date, aas.click, aas.impression, d.ad_id, d.camp_id');
		$query1->from($db->quoteName('#__ad_archive_stats', 'aas'));
		$query1->join('LEFT', $db->quoteName('#__ad_data', 'd') . 'ON' . $db->quoteName('aas.ad_id') . '=' . $db->quoteName('d.ad_id'));
		$query1->join('LEFT', $db->quoteName('#__ad_zone', 'z') . 'ON' . $db->quoteName('d.ad_id') . '=' . $db->quoteName('z.id'));
		$query1->where($db->quoteName('d.created_by') . '=' . $user->id);

		// Filter for zone for stats
		if ($this->getState('filter.zoneslist'))
		{
			$query1->where($db->quoteName('d.ad_zone') . " = " . $this->getState('filter.zoneslist'));
		}

		// Filter for campaign for stats
		$campaignslist = $this->getState('filter.campaignslist');

		if ($campaignslist)
		{
			$query1->where($db->quoteName('d.camp_id') . '=' . $campaignslist);
		}

		$query1->where("DATE(aas.date) BETWEEN DATE('" . $from_date . "') AND DATE('" . $to_date . "')");
		$query1->group('DATE(aas.date)');
		$query1->order('DATE(aas.date)');
		$db->setQuery($query1);

		$archivestats = $db->loadObjectlist();

		$statistics = array_merge($stats, $archivestats);

		$adsData = array();
		$i = 0;

		// Sorting ads according to ad status
		if ($this->getState('filter.adstatus') == "")
		{
			return $statistics;
		}
		else
		{
			foreach ($statistics as $item)
			{
				// If ad is not expired
				if ($this->getState('filter.adstatus') == 1)
				{
					if (SaAdEngineHelper::getInstance()->getAdStatus($item->ad_id))
					{
						$adsData[$i] = new stdclass;
						$adsData[$i] = $item;
						$i++;
					}
				}
				elseif ($this->getState('filter.adstatus') == 0)
				{
					// If ad is expired
					if (!SaAdEngineHelper::getInstance()->getAdStatus($item->ad_id))
					{
						$adsData[$i] = new stdclass;
						$adsData[$i] = $item;
						$i++;
					}
				}
			}

			return $adsData;
		}
	}
}
