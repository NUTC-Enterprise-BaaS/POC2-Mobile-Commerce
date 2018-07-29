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

jimport('joomla.application.component.model');

/**
 * Methods supporting a list of Socialads records.
 *
 * @since  1.6
 */
class SocialadsModelDashboard extends JModelLegacy
{
	/**
	 * Constructor.
	 *
	 * @see  JController
	 *
	 * @since  1.6
	 */
	public function __construct()
	{
		$mainframe = JFactory::getApplication();

		// Get download id
		$params           = JComponentHelper::getParams('com_socialads');
		$this->downloadid = $params->get('downloadid');

		// Setup vars
		$this->updateStreamName = 'Socialads';
		$this->updateStreamType = 'extension';
		$this->updateStreamUrl  = "https://techjoomla.com/component/ars/updates/components/socialads?format=xml&dummy=extension.xml";
		$this->extensionElement = 'com_socialads';
		$this->extensionType    = 'component';

		parent::__construct();
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

		// Query to get data for all time income
		$query->select('FORMAT(SUM(amount), 2)');
		$query->from($db->quoteName('#__ad_orders'));
		$query->where($db->quoteName('status') . "='C'");

		// @TODO - decide what do with orders placed using points gateways
		// $query->where($db->quoteName('processor') . " NOT IN ('jomsocialpoints','alphauserpoints') OR extras='points'");

		$query->where($db->quoteName('comment') . "!='AUTO_GENERATED'");

		$db->setQuery($query);
		$totalamount = $db->loadResult();

		return $totalamount;
	}

	/**
	 * To get month income
	 *
	 * @return  string
	 *
	 * @since  1.6
	 */
	public function getMonthlyIncome()
	{
		$db = JFactory::getDbo();

		$curdate = date('Y-m-d');

		// Lets time travel, back to previous year, same month, same day!
		$date     = strtotime($curdate . ' -1 year');
		$backdate = date('Y-m-d', $date);

		$query = $db->getQuery(true);

		// Query to get data for all month income
		$query->select('FORMAT(SUM(amount), 2) AS tampunt, MONTH( mdate ) AS monthsname');
		$query->from($db->quoteName('#__ad_orders'));
		$query->where($db->quoteName('status') . "='C'");
		$query->where('DATE(mdate)' . "BETWEEN DATE('" . $backdate . " 00:00:01') AND DATE('" . $curdate . " 23:59:59')");

		// @TODO - decide what do with orders placed using points gateways
		// $query->where($db->quoteName('processor') . " NOT IN ('jomsocialpoints','alphauserpoints') OR extras='points'");

		$query->where($db->quoteName('comment') . "!='AUTO_GENERATED'");
		$query->group('YEAR(mdate)');
		$query->group($db->quoteName('MONTHSNAME'));
		$query->order('YEAR(mdate)');
		$query->order('MONTH( mdate )' . 'ASC');

		$db->setQuery($query);
		$totalamount = $db->loadAssocList();

		return $totalamount;
	}

	/**
	 * Returns data for total perodic amount.
	 *
	 * @return   Array    Data for pie chart
	 *
	 * @since    1.6
	 */
	public function getPeriodicOrdersCount()
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

		// Query to get data from perodic orderscount data
		$query->select('FORMAT(SUM(amount),2) AS orderscount');
		$query->from('#__ad_orders');
		$query->where($db->quoteName('status') . "='C'");
		$query->where("DATE(mdate) BETWEEN DATE('" . $from_date . "') AND DATE('" . $to_date . "')");

		// @TODO - decide what do with orders placed using points gateways
		// $query->where($db->quoteName('processor') . " NOT IN ('jomsocialpoints','alphauserpoints') OR extras='points'");

		$query->where($db->quoteName('comment') . "!='AUTO_GENERATED'");

		$db->setQuery($query);
		$totalamount = $db->loadResult();

		return $totalamount;
	}

	/**
	 * Returns data for total perodic amount.
	 *
	 * @param   Int  $paymentmode  mode of payment
	 *
	 * @return   Array    Data for pie chart
	 *
	 * @since    1.6
	 */
	public function getPeriodicOrders($paymentmode)
	{
		$input = JFactory::getApplication()->input;
		$post  = $input->post;
		$from  = $post->get('from');
		$to    = $post->get('to');

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

		// Query to get data for all time orders count for wallet mode
		if ($paymentmode == 'wallet_mode')
		{
			$query->select('DISTINCT(order_id)');
			$query->from($db->quoteName('#__ad_payment_info'));
			$db->setQuery($query);
			$payment_order_ids = $db->loadAssocList();
			$payment_order_id = "";

			foreach ($payment_order_ids as $value)
			{
				$payment_order_id .= implode(" ", $value);
				$payment_order_id .= " ";
			}

			$payment_order_id = explode(" ", trim($payment_order_id));
			$payment_order_id = implode(", ", $payment_order_id);

			$query1->select(
			'COUNT(IF(status LIKE "C",1, NULL)) as corders,
			COUNT(IF(status LIKE "P",1, NULL)) as porders,
			COUNT(IF(status NOT IN("C","P"),1, NULL)) as rorders'
			);
			$query1->from($db->quoteName('#__ad_orders'));
			$query1->where("DATE(mdate) BETWEEN DATE('" . $from_date . "') AND DATE('" . $to_date . "')");

			// @TODO - decide what do with orders placed using points gateways
			// $query1->where('(processor' . " NOT IN ('jomsocialpoints','alphauserpoints') OR extras='points')");

			$query1->where($db->quoteName("comment") . " != " . "'AUTO_GENERATED'");

			if (!empty($payment_order_id))
			{
				$query1->where($db->quoteName('id') . " NOT IN ($payment_order_id)");
			}

			$db->setQuery($query1);
			$statsforpie = $db->loadAssocList();
		}
		// Query to get data for all time orders count for pay-per-ad mode
		else
		{
			$query->select(
				'COUNT(IF(status LIKE "C",1, NULL)) as corders,
				COUNT(IF(status LIKE "P",1, NULL)) as porders,
				COUNT(IF(status NOT IN("C","P"),1, NULL)) as rorders'
				);
			$query->from($db->quoteName('#__ad_orders', 'o'));
			$query->join('RIGHT', $db->quoteName('#__ad_payment_info', 'pi') . 'ON' . $db->quoteName('pi.order_id') . '=' . $db->quoteName('o.id'));
			$query->where("DATE(mdate) BETWEEN DATE('" . $from_date . "') AND DATE('" . $to_date . "')");
			$db->setQuery($query);
			$statsforpie = $db->loadAssocList();
		}

		return $statsforpie;
	}

	/**
	 * to get latest version
	 *
	 * @return  void
	 *
	 * @since  1.6
	 */
	public function getLatestVersion()
	{
		// Get current extension ID
		$extension_id = $this->getExtensionId();

		if (!$extension_id)
		{
			return 0;
		}

		$db = $this->getDbo();

		// Get current extension ID
		$query = $db->getQuery(true)
			->select($db->qn(array('version', 'infourl')))
			->from($db->qn('#__updates'))
			->where($db->qn('extension_id') . ' = ' . $db->q($extension_id));
		$db->setQuery($query);
		$latestVersion = $db->loadObject();

		if (empty($latestVersion))
		{
			return 0;
		}
		else
		{
			return $latestVersion;
		}
	}

	/**
	 * Function for periodic orders count
	 *
	 * @return  string
	 *
	 * @since  1.6
	 */
	public function getExtensionId()
	{
		$db = $this->getDbo();

		// Get current extension ID
		$query = $db->getQuery(true)
			->select($db->qn('extension_id'))
			->from($db->qn('#__extensions'))
			->where($db->qn('type') . ' = ' . $db->q($this->extensionType))
			->where($db->qn('element') . ' = ' . $db->q($this->extensionElement));
			$db->setQuery($query);
			$extension_id = $db->loadResult();

		if (empty($extension_id))
		{
			return 0;
		}
		else
		{
			return $extension_id;
		}
	}

	/**
	 * To get ads count
	 *
	 * @return  string
	 *
	 * @since  1.6
	 */
	public function getTotalAds()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		// Query to get total ads count
		$query->select('COUNT(ad_id)');
		$query->from($db->quoteName('#__ad_data'));

		$db->setQuery($query);
		$totalads = $db->loadResult();

		return $totalads;
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
		$db            = JFactory::getDbo();
		$query         = $db->getQuery(true);
		$query1        = $db->getQuery(true);
		$pendingorders = "";

		if ($paymentmode == "wallet_mode")
		{
			$query->select('DISTINCT(order_id)');
			$query->from($db->quoteName('#__ad_payment_info'));
			$db->setQuery($query);
			$payment_order_ids = $db->loadAssocList();
			$payment_order_id = "";

			foreach ($payment_order_ids as $value)
			{
				$payment_order_id .= implode(" ", $value);
				$payment_order_id .= " ";
			}

			$payment_order_id = explode(" ", trim($payment_order_id));
			$payment_order_id = implode(", ", $payment_order_id);

			$query1->select('u.username as uname, o.processor, o.amount');
			$query1->from($db->quoteName('#__ad_orders', 'o'));
			$query1->join('RIGHT', $db->quoteName('#__users', 'u') . 'ON' . $db->quoteName('o.payee_id') . '=' . $db->quoteName('u.id'));
			$query1->where($db->quoteName('o.status') . "='P'");

			if (!empty($payment_order_id))
			{
				$query1->where($db->quoteName('o.id') . " NOT IN ($payment_order_id)");
			}

			$query1->setLimit('5');

			$db->setQuery($query1);
			$pendingorders = $db->loadObjectList();
		}
		elseif ($paymentmode == "pay_per_ad_mode")
		{
			// Query to get data for all time orders count for pay-per-ad mode
			$query->select('u.username as uname, o.processor, o.amount');
			$query->from($db->quoteName('#__ad_orders', 'o'));
			$query->join('RIGHT', $db->quoteName('#__ad_payment_info', 'pi') . 'ON' . $db->quoteName('pi.order_id') . '=' . $db->quoteName('o.id'));
			$query->join('RIGHT', $db->quoteName('#__users', 'u') . 'ON' . $db->quoteName('o.payee_id') . '=' . $db->quoteName('u.id'));
			$query->where($db->quoteName('o.status') . "='P'");

			$db->setQuery($query);
			$pendingorders = $db->loadObjectList();
		}

		return $pendingorders;
	}

	/**
	 * To get All Average CTR
	 *
	 * @return  Int
	 *
	 * @since  1.6
	 */
	public function getAverageCtr()
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$user  = JFactory::getUser();

		// Query to get data average ctr
		$query->select('SUM(clicks) as click, SUM(impressions) as impression');
		$query->from($db->quoteName('#__ad_data'));
		$db->setQuery($query);
		$averagectr = $db->loadAssoc();

		if ($averagectr['impression'] != 0)
		{
			$averagectr = $averagectr['click'] / $averagectr['impression'];
		}
		elseif ($averagectr['click'] == 0 && $averagectr['impression'] == 0)
		{
			$averagectr = 0;
		}

		$averagectr = number_format((float) $averagectr, 6, '.', '');

		return $averagectr;
	}

	/**
	 * To get All Average CTR
	 *
	 * @param   Int  $paymentmode  mode of payment
	 *
	 * @return  Int
	 *
	 * @since  1.6
	 */
	public function getTotalOrders($paymentmode)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query1 = $db->getQuery(true);
		$user  = JFactory::getUser();

		// Query to get data for all time orders count for wallet mode
		if ($paymentmode == "wallet_mode")
		{
			$query->select('DISTINCT(order_id)');
			$query->from($db->quoteName('#__ad_payment_info'));
			$db->setQuery($query);
			$payment_order_ids = $db->loadAssocList();
			$payment_order_id = "";

			foreach ($payment_order_ids as $value)
			{
				$payment_order_id .= implode(" ", $value);
				$payment_order_id .= " ";
			}

			$payment_order_id = explode(" ", trim($payment_order_id));
			$payment_order_id = implode(", ", $payment_order_id);

			$query1->select('COUNT(id)');
			$query1->from($db->quoteName('#__ad_orders'));
			$query1->where($db->quoteName("comment") . " != " . "'AUTO_GENERATED'");

			if (!empty($payment_order_id))
			{
				$query1->where($db->quoteName('id') . " NOT IN ($payment_order_id)");
			}

			$db->setQuery($query1);
			$totalorder = $db->loadResult();
		}
		elseif ($paymentmode == "pay_per_ad_mode")
		{
			// Query to get data for all time orders count for pay-per-ad mode
			$query->select('COUNT(id)');
			$query->from($db->quoteName('#__ad_payment_info'));
			$db->setQuery($query);
			$totalorder = $db->loadResult();
		}

		return $totalorder;
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
		$query->order('ctr DESC');
		$query->setLimit('5');
		$db->setQuery($query);
		$topads = $db->loadAssocList();

		return $topads;
	}

	/**
	 * Get News Feed
	 *
	 * @return  array
	 */
	public function getNewsFeeds()
	{
		$rssUrl  = 'https://techjoomla.com/blog/categories/socialads.html?format=feed&type=rss';

		if (JVERSION > '3.0')
		{
			// Get RSS parsed object
			try
			{
				$rssDoc   = new JFeedFactory;
				$feeds = $rssDoc->getFeed($rssUrl);
			}
			catch (InvalidArgumentException $e)
			{
				return false;
			}
			catch (RunTimeException $e)
			{
				return false;
			}
			catch (LogicException $e)
			{
				return false;
			}

			if (empty($feeds))
			{
				return false;
			}

			if ($feeds)
			{
				$parsedFeeds = array();

				for ($i = 0; $i < 4; $i++)
				{
					if (!$feeds->offsetExists($i))
					{
						break;
					}

					$parsedFeeds[$i] = new stdClass;
					$parsedFeeds[$i]->title = $feeds[$i]->title;

					$parsedFeeds[$i]->link = (! empty($feeds[$i]->uri) || !is_null($feeds[$i]->uri)) ? $feeds[$i]->uri : $feeds[$i]->guid;
					$parsedFeeds[$i]->link = htmlspecialchars($parsedFeeds[$i]->link);

					$parsedFeeds[$i]->text = !empty($feeds[$i]->content) ||  !is_null($feeds[$i]->content) ? $feeds[$i]->content : $feeds[$i]->description;
					$parsedFeeds[$i]->text = JFilterOutput::stripImages($parsedFeeds[$i]->text);
					$parsedFeeds[$i]->text = strip_tags($parsedFeeds[$i]->text);
					$parsedFeeds[$i]->text = JHtml::_('string.truncate', $parsedFeeds[$i]->text, 125);

					$date = $feeds[$i]->updatedDate;
					$parsedFeeds[$i]->date = $date->format('d-M-Y');
				}

				return $parsedFeeds;
			}
			else
			{
				return false;
			}
		}
		else
		{
			$rssDoc = @JFactory::getFeedParser($rssUrl);

			$feed = new stdclass;

			if ($rssDoc != false)
			{
				// Items
				$items = @$rssDoc->get_items();

				// Feed elements
				$feed->items = array_slice($items, 0, 4);

				foreach ($feed->items as $item)
				{
					$data = new stdClass;

					$data->title = @$item->get_title();
					$data->link  = @$item->get_link();
					$data->date  = @strtolower($item->get_date('d-M-Y'));
					$data->text = @JFilterOutput::stripImages($item->get_description());
					$data->text = strip_tags($data->text);
					$data->text = JHtml::_('string.truncate', $data->text, 125);

					$parsedFeeds[] = $data;
				}
			}
			else
			{
				$parsedFeeds = false;
			}

			return $parsedFeeds;
		}
	}
}
