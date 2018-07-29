<?php
/**
 * @version    SVN:<SVN_ID>
 * @package    Com_Socialads
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved
 * @license    GNU General Public License version 2, or later
 */

defined('_JEXEC') or die(';)');

jimport('joomla.application.component.model');

/**
 * Methods supporting ad sumary view.
 *
 * @since  1.6
 */
class SocialadsModelAdsummary extends JModelList
{
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
		$db    = $this->getDbo();
		$query = $db->getQuery(true);
		$user = JFactory::getUser();
		$input = JFactory::getApplication()->input;
		$this->items = $this->get('Items');
		$adid = $input->get('adid');

		// Select the required fields from the table.
		$query->select($db->quoteName(array('p.cdate', 'p.subscr_id', 'p.ad_credits_qty', 'o.transaction_id', 'o.amount', 'o.status', 'o.processor')));

		// $query->select("z.zone_name");
		$query->from($db->quoteName('#__ad_payment_info', 'p'));
		$query->join('LEFT', $db->quoteName('#__ad_orders', 'o') . 'ON' . $db->quoteName('p.order_id') . '=' . $db->quoteName('o.id'));
		$query->where($db->quoteName('p.ad_id') . '=' . $adid);

		$db->setQuery($query);

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

		return $items;
	}

	/**
	 * Method to get ad type
	 *
	 * @param   array  $ad_id  The array of ad ids.
	 *
	 * @return  true or false
	 *
	 * @since   2.2
	 */
	public function getadtype($ad_id)
	{
		$db    = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select($db->quoteName(array('ad_noexpiry' ,'ad_alternative', 'ad_enddate', 'ad_startdate', 'ad_credits','camp_id')));
		$query->from($db->quoteName('#__ad_data'));
		$query->where($db->quoteName('ad_id') . '=' . $ad_id);
		$this->_db->setQuery($query);
		$id = $this->_db->loadObjectList();

		return $id;
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
	public function getTable($type = 'Ad', $prefix = 'SocialadsTable', $config = array())
	{
		$this->addTablePath(JPATH_COMPONENT_ADMINISTRATOR . '/tables');

		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Returns data for pie chart in adsummary view.
	 *
	 * @return   Array    Data for pie chart
	 *
	 * @since    1.6
	 */
	public function getstatsforpiechart()
	{
		$input = JFactory::getApplication()->input;
		$post = $input->post;
		$adid = $input->get('adid');
		$from = $post->get('from');
		$to = $post->get('to');

		if (isset($to))
		{
			$to_date = $to;
		}
		else
		{
			$to_date = date('Y-m-d');
		}

		if (isset($from))
		{
			$from_date = $from;
		}
		else
		{
			$from_date = date('Y-m-d', strtotime(date('Y-m-d') . ' - 30 days'));
		}

		$arch_where = " AND DATE(date) BETWEEN DATE('" . $from_date . "') AND DATE('" . $to_date . "')";
		$where = " AND DATE(time) BETWEEN DATE('" . $from_date . "') AND DATE('" . $to_date . "')";

		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query1 = $db->getQuery(true);
		$query2 = $db->getQuery(true);
		$query3 = $db->getQuery(true);

		// To get impression count from stats
		$query->select("COUNT(s.id) as value");
		$query->from('#__ad_stats as s');
		$query->where('s.display_type = 0');
		$query->where('s.ad_id =' . $adid);
		$query->where("DATE(time) BETWEEN DATE('" . $from_date . "') AND DATE('" . $to_date . "')");
		$db->setQuery($query);
		$statsforpie = $db->loadResult();

		// TO get impressions from aechive stats
		$query1->select("SUM(impression) as value");
		$query1->from('#__ad_archive_stats as aas');
		$query1->where('impression<>0');
		$query1->where('aas.ad_id =' . $adid);
		$query1->where("DATE(date) BETWEEN DATE('" . $from_date . "') AND DATE('" . $to_date . "')");
		$db->setQuery($query1);
		$acrh_imp_statistics = $db->loadResult();

		$result[0] = $statsforpie + $acrh_imp_statistics;

		// To get clcik count from stats
		$query2->select("COUNT(s.id) as value");
		$query2->from('#__ad_stats as s');
		$query2->where('s.display_type = 1');
		$query2->where('s.ad_id =' . $adid);
		$query2->where("DATE(time) BETWEEN DATE('" . $from_date . "') AND DATE('" . $to_date . "')");
		$db->setQuery($query2);
		$statsforpie = $db->loadResult();

		// TO get click from aechive stats
		$query3->select("SUM(click) as value");
		$query3->from('#__ad_archive_stats as aas');
		$query3->where('impression<>0');
		$query3->where('aas.ad_id =' . $adid);
		$query3->where("DATE(date) BETWEEN DATE('" . $from_date . "') AND DATE('" . $to_date . "')");
		$db->setQuery($query3);
		$acrh_imp_statistics = $db->loadResult();

		$result[1] = $statsforpie + $acrh_imp_statistics;

		return $result;
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
		$post = $input->post;
		$ad_id = $input->get('adid');
		$from = $post->get('from');
		$to = $post->get('to');
		$statistics = array();

		if (isset($to))
		{
			$to_date = $to;
		}
		else
		{
			$to_date = date('Y-m-d');
		}

		if (isset($from))
		{
			$from_date = $from;
		}
		else
		{
			$from_date = date('Y-m-d', strtotime(date('Y-m-d') . ' - 30 days'));
		}

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query1 = $db->getQuery(true);

		// Query to get data from stats table
		$query->select('DATE(time) as date, COUNT(IF(display_type="1",1, NULL)) as click , COUNT(IF(display_type="0",1, NULL)) as impression');
		$query->from('#__ad_stats');
		$query->where("ad_id=" . $ad_id);
		$query->where("DATE(time) BETWEEN DATE('" . $from_date . "') AND DATE('" . $to_date . "')");
		$query->group('DATE(time)');
		$query->order('DATE(time)');
		$db->setQuery($query);
		$stats = $db->loadObjectlist();

		// Query to get data from archive stats
		$query1->select('DATE(date) as date, click, impression');
		$query1->from('#__ad_archive_stats');
		$query1->where("ad_id=" . $ad_id);
		$query1->where("DATE(date) BETWEEN DATE('" . $from_date . "') AND DATE('" . $to_date . "')");
		$query1->group('DATE(date)');
		$query1->order('DATE(date)');
		$db->setQuery($query1);
		$archivestats = $db->loadObjectlist();

		$statistics = array_merge($stats, $archivestats);

		return $statistics;
	}
}
