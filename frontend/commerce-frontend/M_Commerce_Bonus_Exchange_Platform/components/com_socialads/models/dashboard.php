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
class SocialadsModelDashboard extends JModelList
{
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
		// Find number of messages per day
			$query->select(
			'DATE(time) as date, COUNT(IF(display_type="1",1, NULL)) as click , COUNT(IF(display_type="0",1, NULL)) as impression, d.ad_id, d.camp_id'
			);
			$query->from($db->quoteName('#__ad_stats', 'as'));
			$query->join('LEFT', $db->quoteName('#__ad_data', 'd') . 'ON' . $db->quoteName('as.ad_id') . '=' . $db->quoteName('d.ad_id'));
			$query->where($db->quoteName('d.created_by') . '=' . $user->id);
			$query->where("DATE(time) BETWEEN DATE('" . $from_date . "') AND DATE('" . $to_date . "')");
			$query->group('DATE(time)');
			$query->order('DATE(time)');
			$db->setQuery($query);
			$stats = $db->loadObjectlist();

			// Query to get data from archive stats
			$query1->select('DATE(aas.date) as date, aas.click, aas.impression, d.ad_id, d.camp_id');
			$query1->from($db->quoteName('#__ad_archive_stats', 'aas'));
			$query1->join('LEFT', $db->quoteName('#__ad_data', 'd') . 'ON' . $db->quoteName('aas.ad_id') . '=' . $db->quoteName('d.ad_id'));
			$query1->where($db->quoteName('d.created_by') . '=' . $user->id);
			$query1->where("DATE(aas.date) BETWEEN DATE('" . $from_date . "') AND DATE('" . $to_date . "')");
			$query1->group('DATE(aas.date)');
			$query1->order('DATE(aas.date)');
			$db->setQuery($query1);

			$archivestats = $db->loadObjectlist();

			$statistics = array_merge($stats, $archivestats);

			for ($i = 31, $k = 0; $i >= 0; $i--, $k++)
			{
				$data[$k] = new stdClass;
				$data[$k]->date = date('Y-m-d', strtotime(date('Y-m-d') . ' - ' . $i . ' days'));
				$data[$k]->click = 0;
				$data[$k]->impression = 0;
				$data[$k]->ad_id = 0;
				$data[$k]->camp_id = 0;
			}

			for ($i = 0; $i <= 31; $i++)
			{
				for ($k = 0; $k < count($statistics); $k++)
				{
					if ($data[$i]->date == $statistics[$k]->date)
					{
						$data[$i]->click = $statistics[$k]->click;
						$data[$i]->impression = $statistics[$k]->impression;
						$data[$i]->ad_id = $statistics[$k]->ad_id;
						$data[$i]->camp_id = $statistics[$k]->camp_id;
					}
				}
			}

		return $data;
	}

	/**
	 * Function used to show active ads for a particular user.
	 *
	 * @return  integer
	 *
	 * @since  1.6
	 */
	public function getActiveAdCount()
	{
		$db = JFactory::getDBO();
		$user  = JFactory::getUser();
		$query = $db->getQuery(true);
		$query->select('count(ad.ad_id)');
		$query->from($db->quoteName('#__ad_data', 'ad'));
		$query->where($db->quoteName('ad.created_by') . '=' . $user->id . " AND ad.state = 1");
		$db->setQuery($query);
		$activeAds = $db->loadresult();

		return $activeAds;
	}

	/**
	 * Function used to show active ads for a particular user.
	 *
	 * @return  integer
	 *
	 * @since  1.6
	 */
	public function getInactiveAdCount()
	{
		$db = JFactory::getDBO();
		$user  = JFactory::getUser();
		$query = $db->getQuery(true);
		$query->select('count(ad.ad_id)');
		$query->from($db->quoteName('#__ad_data', 'ad'));
		$query->where($db->quoteName('ad.created_by') . '=' . $user->id . " AND ad.state = 0");
		$db->setQuery($query);
		$inactiveAds = $db->loadresult();

		return $inactiveAds;
	}

	/**
	 * To get All orders income
	 *
	 * @return  Int
	 *
	 * @since  1.6
	 */
	public function getAllOrdersIncome()
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$user  = JFactory::getUser();

		// Query to get data for all time income
		$query->select('FORMAT(SUM(amount), 2)');
		$query->from($db->quoteName('#__ad_orders'));
		$query->where($db->quoteName('status') . "='C'");
		$query->where($db->quoteName('comment') . "!='AUTO_GENERATED'");
		$query->where($db->quoteName('payee_id') . "=" . $user->id);
		$query->where($db->quoteName('processor') . " NOT IN ('jomsocialpoints','alphauserpoints') OR extras='points'");

		$db->setQuery($query);
		$totalamount = $db->loadResult();

		return $totalamount;
	}

	/**
	 * To get top ads
	 *
	 * @return  Int
	 *
	 * @since  1.6
	 */
	public function getTopAds()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$user  = JFactory::getUser();

		// Query to get data for top ads
		$query->select('ad_id, ad_title, (clicks / impressions) as ctr');
		$query->from($db->quoteName('#__ad_data'));
		$query->where($db->quoteName('created_by') . "=" . $user->id);
		$query->order('ctr DESC');
		$query->setLimit('5');
		$db->setQuery($query);
		$topads = $db->loadAssocList();

		return $topads;
	}

	/**
	 * To get pending orders details
	 *
	 * @param   Int  $paymentmode  mode of payment
	 *
	 * @return  string
	 *
	 * @since  1.6
	 */
	public function getPendingOrders($paymentmode)
	{
		$db     = JFactory::getDbo();
		$query  = $db->getQuery(true);
		$query1  = $db->getQuery(true);
		$user  = JFactory::getUser();

		if ($paymentmode == "pay_per_ad_mode")
		{
			$query->select('p.ad_id');
			$query->from('`#__ad_payment_info` AS p');
			$query->select("o.id, o.amount");
			$query->join('LEFT', '`#__ad_orders` AS o ON o.id=p.order_id');
			$query->select("d.ad_id, d.ad_title");
			$query->join('LEFT', '`#__ad_data` AS d ON d.ad_id=p.ad_id');
			$query->where($db->quoteName('d.created_by') . ' = ' . $user->id);
			$query->where($db->quoteName('o.status') . "='P'");
			$query->setLimit('5');

			$db->setQuery($query);
			$pendingorders = $db->loadObjectList();
		}
		elseif ($paymentmode == "wallet_mode")
		{
			// Query to get data for all time orders count for wallet mode
			$query = $db->getQuery(true);
			$query1 = $db->getQuery(true);

			// Select the required fields from the table.
			$query->select('DISTINCT(order_id)');
			$query->from($db->quoteName('#__ad_payment_info'));
			$db->setQuery($query);
			$payment_order_ids = $db->loadAssocList();
			$payment_order_id  = "";

			foreach ($payment_order_ids as $value)
			{
				$payment_order_id .= implode(" ", $value);
				$payment_order_id .= " ";
			}

			$payment_order_id = explode(" ", trim($payment_order_id));
			$payment_order_id = implode(", ", $payment_order_id);

			$query1->select('a.id, a.amount');
			$query1->from('`#__ad_orders` AS a');

			if (!empty($payment_order_id))
			{
				$query1->where($db->quoteName('a.id') . " NOT IN ($payment_order_id)");
				$query1->where($db->quoteName('a.payee_id') . "=" . $user->id);
			}

			$query->setLimit('5');
			$db->setQuery($query1);
			$pendingorders = $db->loadObjectList();
		}

		return $pendingorders;
	}
}
