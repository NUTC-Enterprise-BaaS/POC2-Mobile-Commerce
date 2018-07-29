<?php
/**
 * @version    SVN:<SVN_ID>
 * @package    SocialAds
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved
 * @license    GNU General Public License version 2, or later
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Socialads records.
 *
 * @since  1.6
 */
class SocialadsModelForms extends JModelList
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
			'ordering', 'a.ordering',
			'state', 'a.state', 'created_by', 'a.created_by',
			'ad_id', 'a.ad_id',
			'created_by', 'a.created_by',
			'ad_url1', 'a.ad_url1',
			'ad_url2', 'a.ad_url2',
			'ad_title', 'a.ad_title',
			'ad_body', 'a.ad_body',
			'ad_image', 'a.ad_image',
			'ad_startdate', 'a.ad_startdate',
			'ad_enddate', 'a.ad_enddate',
			'ad_noexpiry', 'a.ad_noexpiry',
			'ad_payment_type', 'a.ad_payment_type',
			'ad_credits', 'a.ad_credits',
			'ad_credits_balance', 'a.ad_credits_balance',
			'ad_created_date', 'a.ad_created_date',
			'ad_modified_date', 'a.ad_modified_date',
			'ad_published', 'a.ad_published',
			'ad_approved', 'a.ad_approved',
			'ad_alternative', 'a.ad_alternative',
			'ad_guest', 'a.ad_guest',
			'ad_affiliate', 'a.ad_affiliate',
			'ad_zone', 'a.ad_zone',
			'layout', 'a.layout',
			'camp_id', 'a.camp_id',
			'bid_value', 'a.bid_value',
			'campaign', 'c.campaign',
			'clicks', 'a.clicks',
			'impressions', 'a.impressions',
			'status', 'ao.status'
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
		$published = $app->getUserStateFromRequest($this->context . '.filter.state', 'filter_published', '', 'string');
		$this->setState('filter.state', $published);

		$campaign = $app->getUserStateFromRequest($this->context . '.filter.campaignslist', 'filter_campaignslist', '', 'string');
		$this->setState('filter.campaignslist', $campaign);

		$zone = $app->getUserStateFromRequest($this->context . '.filter.zonelist', 'filter_zonelist', '', 'string');
		$this->setState('filter.zonelist', $zone);

		// Filter provider.
		$accepted_status = $app->getUserStateFromRequest($this->context . '.filter.ad_approved', 'filter_ad_approved', '', 'string');
		$this->setState('filter.ad_approved', $accepted_status);

		// Load the parameters.
		$params = JComponentHelper::getParams('com_socialads');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('a.ad_id', 'desc');
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
		$query->from('`#__ad_data` AS a');

		// Join over the users for the checked out user
		$query->select($db->quoteName('c.campaign'));
		$query->select("uc.name AS editor");

		// Join over the user field 'created_by'
		$query->select('created_by.name AS created_by');
		$query->select("z.zone_name");
		$query->select($db->quoteName('ao.status'));
		$query->join('LEFT', $db->quoteName('#__ad_campaign', 'c') . 'ON' . $db->quoteName('a.camp_id') . '=' . $db->quoteName('c.id'));
		$query->join("LEFT", "#__users AS uc ON uc.id=a.checked_out");
		$query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');
		$query->join('LEFT', '#__ad_zone AS z ON a.ad_zone = z.id');
		$query->join('LEFT', $db->quoteName('#__ad_payment_info', 'p') . 'ON' . $db->quoteName('p.ad_id') . '=' . $db->quoteName('a.ad_id'));
		$query->join('LEFT', $db->quoteName('#__ad_orders', 'ao') . 'ON' . $db->quoteName('p.order_id') . '=' . $db->quoteName('ao.id'));
		$query->group($db->quoteName('a.ad_id'));
		$db->setQuery($query);

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

		// Filter by campaign
		$campaign = $this->getState('filter.campaignslist');

		if ($campaign)
		{
			$query->where('a.camp_id = ' . (int) $campaign);
		}

		// Filter for zone
		$zone = $this->getState('filter.zonelist');

		if ($zone)
		{
			$query->where('a.ad_zone = ' . (int) $zone);
		}

		$ostatus = $this->getState('filter.ad_approved');

		if ($ostatus != '' && $ostatus != '-1')
		{
			$query->where('a.ad_approved = ' . "'" . $ostatus . "'");
		}

		// Filter by search in title
		$search = $this->getState('filter.search');

		if (!empty($search))
		{
			if (stripos($search, 'ad_id:') === 0)
			{
				$query->where('a.ad_id = ' . (int) substr($search, 3));
			}
			else
			{
				$search = $db->Quote('%' . $db->escape($search, true) . '%');

				$query->where('( a.ad_id LIKE ' . $search .
					'  OR  a.ad_title LIKE ' . $search .
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
	 * Method to get approved ads.
	 *
	 * @return  integer
	 *
	 * @since  1.6
	 */
	public function getApproveAds()
	{
		$db = JFactory::getDBO();
		global $mainframe, $option;
		$mainframe = JFactory::getApplication();
		$input = JFactory::getApplication()->input;
		$option = $input->get('option', '', 'STRING');
		$where = '';

		if (empty($this->_data))
		{
			$query = $this->_buildQuery();
			$query = $query . ' ' . $where;

			if ($filter_order)
			{
				$qry = "SHOW COLUMNS FROM #__ad_data";
				$db->setQuery($qry);
				$exists = $db->loadobjectlist();

				foreach ($exists as $key => $value)
				{
					$allowed_fields[] = 'a.' . $value->Field;
				}

				if (in_array($filter_order, $allowed_fields))
				{
					$query .= "ORDER BY $filter_order $filter_order_Dir";
				}
			}
			else
			{
				$query .= "ORDER BY a.`ad_id` DESC";
			}

			$this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
		}

		return $this->_data;
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

	/**
	 * Method to find zone name
	 *
	 * @param   integer  $ad_id    An ad_id for perticular ad
	 * @param   integer  $zone_id  To find perticular zone name
	 *
	 * @return  integer
	 *
	 * @since  1.6
	 */
	public function adzonename($ad_id,$zone_id)
	{
		$db = JFactory::getDBO();
		$query = "SELECT zone_name FROM #__ad_zone as z, #__ad_data as d
						WHERE z.id=d.ad_zone AND z.id=" . $zone_id .
						" AND d.ad_id=" . $ad_id;
				$db->setQuery($query);
				$zone_name = $db->loadresult();

				return $zone_name;
	}

	/**
	 * To get the published zone name
	 *
	 * @return  Integer
	 *
	 * @since  1.6
	 */
	public function getZonelist()
	{
		$db = JFactory::getDBO();
		$query = "SELECT id,zone_name FROM #__ad_zone WHERE state=1";
		$db->setQuery($query);
		$zone_list = $db->loadObjectList();

		return $zone_list;
	}

	/**
	 * Store the staus value changed in list view of ads
	 *
	 * @return  Boolean value
	 *
	 * @since  1.6
	 */
	public function store()
	{
		$data = JRequest::get('post');
		$input = JFactory::getApplication()->input;
		$id = $data['id'];
		$status = $data['status'];
		$query = "UPDATE #__ad_data SET ad_approved =" . $status . " WHERE ad_id =" . $id;
		$this->_db->setQuery($query);

		if ($this->_db->execute())
		{
			jimport('joomla.utilities.utility');
			$this->_db->setQuery(
				"SELECT a.created_by, a.ad_title, a.ad_url2, u.name, u.email
				FROM #__ad_data AS a, #__users AS u
				WHERE a.ad_id=$id AND a.created_by=u.id"
			);
			$result	= $this->_db->loadObject();
			global $mainframe;
			$mainframe = JFactory::getApplication();

			// When ad is approve by site owner
			if ($status == 1)
			{
				$body	= JText::_('COM_SOCIALADS_ADS_APPROVED_AD_MAIL');
				$subject = JText::_('COM_SOCIALADS_APPROVEDAD');
			}
			// When ad is rejected by site owner
			elseif ($status == 2)
			{
				$body = JText::_('COM_SOCIALADS_ADS_REJECTED_MAIL');
				$subject = JText::_('COM_SOCIALADS_ADS_REJECTAD');
				$body = str_replace('[COM_SOCIALADS_ADS_REASON]', $input->get('reason', '', 'STRING'), $body);
			}

			$body	= str_replace('[NAME]', $result->name, $body);
			$ad_title=($result->ad_title != '') ? JText::_("COM_SOCIALADS_TITLE") . ' <b>"' . $result->ad_title .
			'"</b>' : JText::_("COM_SOCIALADS_ADID") . ' : <b>' . $id . '</b>';
			$body = str_replace('[ADTITLE]', $ad_title, $body);
			$body = str_replace('[SITE]', JUri::root(), $body);
			$body = str_replace('[SITENAME]', $mainframe->getCfg('sitename'), $body);
			$from = $mainframe->getCfg('mailfrom');
			$fromname = $mainframe->getCfg('fromname');
			$recipient[] = $result->email;
			$body = nl2br($body);
			$mode = 1;
			$cc = null;
			$bcc = null;
			$bcc = null;
			$attachment = null;
			$replyto = null;
			$replytoname = null;
			JFactory::getMailer()->sendMail($from, $fromname, $recipient, $subject, $body, $mode, $cc, $bcc, $attachment, $replyto, $replytoname);
		}

		return true;
	}

	/**
	 * Store changed zone on list view of ads.
	 *
	 * @return  Integer
	 *
	 * @since  1.6
	 */
	public function updatezone()
	{
		$data = JRequest::get('post');
		$id = $data['id'];
		$zone = $data['zone'];
		$query_lay = "SELECT layout FROM #__ad_zone   where id=" . $zone;
		$this->_db->setQuery($query_lay);
		$layout  = $this->_db->loadresult();
		$layout1 = explode('|', $layout);
		$query = "UPDATE #__ad_data SET ad_zone =" . $zone . " ,layout='{$layout1['0']}' WHERE ad_id =" . $id;
		$this->_db->setQuery($query);
		$this->_db->execute();

		return true;
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
		$db = JFactory::getDBO();
		$query = "SELECT COUNT(adid) FROM #__ad_ignore
					WHERE adid=" . $ad_id;
		$db->setQuery($query);
		$ignorecount = $db->loadresult();

		return $ignorecount;
	}

	/**
	 * Export ads stats into a csv file
	 *
	 * @return  void
	 *
	 * @since  1.6
	 **/
	public function adCsvExport()
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query = "SELECT ad_id,ad_title,ad_alternative,ad_noexpiry,ad_payment_type,created_by,ad_zone,ad_affiliate,clicks,impressions FROM #__ad_data ";
		$db->setQuery($query);
		$results = $db->loadObjectList();
		$csvData = null;
		$csvData .= "Ad_Id,Ad_Title,Ad_Type,Owner,Zone_Name,Clicks,Impressions,CTR,Ignores";
		$csvData .= "\n";
		$filename = "SA_Ads_" . date("Y-m-d_H-i", time());
		header("Content-type: application/vnd.ms-excel");
		header("Content-disposition: csv" . date("Y-m") . ".csv");
		header("Content-disposition: filename=" . $filename . ".csv");

		foreach ($results as $result)
		{
			$csvData .= '"' . $result->ad_id . '"' . ',' . '"' . trim(( $result->ad_title == '' ? JText::_('IMGAD') : $result->ad_title )) . '"' . ',';

			if ($result->ad_alternative == 1)
			{
				$csvData .= '"' . JText::_('COM_SOCIALADS_ADS_AD_TYPE_ALT_AD') . '"' . ',';
			}
			elseif ($result->ad_noexpiry == 1)
			{
				$csvData .= '"' . JText::_('COM_SOCIALADS_ADS_AD_TYPE_UNLTD_AD') . '"' . ',';
			}
			elseif ($result->ad_affiliate == 1)
			{
				$csvData .= '"' . JText::_('COM_SOCIALADS_ADS_AD_TYPE_AFFI') . '"' . ',';
			}
			else
			{
				if ($result->ad_payment_type == 0)
				{
					$csvData .= '"' . JText::_('COM_SOCIALADS_ADS_AD_TYPE_IMPRS') . '"' . ',';
				}
				elseif ($result->ad_payment_type == 1)
				{
						$csvData .= '"' . JText::_('COM_SOCIALADS_ADS_AD_TYPE_CLICKS') . '"' . ',';
				}
				else
				{
					$csvData .= '"' . JText::_('COM_SOCIALADS_ADS_AD_TYPE_PERDATE') . '"' . ',';
				}
			}

			$csvData .= '"' . JFactory::getUser($result->created_by)->username . '"' . ',';
			$zone_name = $this->adzonename($result->ad_id, $result->ad_zone);

			if ($zone_name)
			{
				$csvData .= '"' . $zone_name . '"' . ',';
			}

			$clicks = $result->clicks;

			$impr = $result->impressions;

			if ($impr != 0)
			{
				$ctr = ($clicks) / ($impr);
				$ctr = number_format($ctr, 2);
			}
			else
			{
				$ctr = number_format($clicks, 2);
			}

			$csvData .= '"' . $clicks . '"' . ',' . '"' . $impr . '"' . ',' . '"' . $ctr . '"' . ',' . '"' . $this->getIgnorecount($result->ad_id) . '"' . ',';
			$csvData .= "\n";
		}

		print $csvData;
	exit();
	}
}
