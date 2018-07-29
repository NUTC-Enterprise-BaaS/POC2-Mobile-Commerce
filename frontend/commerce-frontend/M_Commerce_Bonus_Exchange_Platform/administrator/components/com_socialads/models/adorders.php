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
class SocialadsModelAdorders extends JModelList
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
				'id', 'o.id',
				'd.ad_id',
				'd.ad_title',
				'p.ad_credits_qty',
				'status', 'o.status',
				'processor', 'o.processor',
				'ad_payment_type', 'd.ad_payment_type',
				'u.username',
				'amount', 'o.amount',
				'cdate', 'o.cdate'
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

		// Load the parameters.
		$params = JComponentHelper::getParams('com_socialads');
		$this->setState('params', $params);

		// Filter for gateway options.
		$gateway = $app->getUserStateFromRequest($this->context . '.filter.gatewaylist', 'filter_gatewaylist', '', 'string');
		$this->setState('filter.gatewaylist', $gateway);

		// Filter provider.
		$accepted_status = $app->getUserStateFromRequest($this->context . '.filter.status', 'filter_status', '', 'string');
		$this->setState('filter.status', $accepted_status);

		// List state information.
		parent::populateState('o.id', 'desc');
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
	 * @return  string   A store id.
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
						'list.select', 'DISTINCT p.*'
				)
		);
		$query->from('`#__ad_payment_info` AS p');
		$query->select("o.id, o.amount, o.status, o.processor, o.comment, o.coupon, o.payee_id, o.prefix_oid");
		$query->join('LEFT', '`#__ad_orders` AS o ON o.id=p.order_id');

		$query->select("d.ad_id, d.ad_title, d.ad_payment_type, d.ad_startdate, d.ad_enddate");
		$query->join('LEFT', '`#__ad_data` AS d ON d.ad_id=p.ad_id');
		$query->select("u.username,u.id as user_id, u.email");
		$query->join('LEFT', '`#__users` AS u ON u.id=o.payee_id');

		// Filter by search in title
		$search = $this->getState('filter.search');
		$ostatus = $this->getState('filter.status');

		if (!empty($ostatus))
		{
			$query->where('o.status = ' . "'" . $ostatus . "'");
		}

		if (!empty($search))
		{
			if (stripos($search, 'prefix_oid:') === 0)
			{
				$query->where('o.prefix_oid = ' . (int) substr($search, 3));
			}
			else
			{
				$search = $db->Quote('%' . $db->escape($search, true) . '%');

				$query->where('( o.prefix_oid LIKE ' . $search .
					'  OR  p.ad_id LIKE ' . $search .
					'  OR  d.ad_title LIKE ' . $search .
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

		$filter_gateway = $this->state->get("filter.gatewaylist");

		if ($filter_gateway)
		{
			$query->where("o.processor = '" . $db->escape($filter_gateway) . "'");
		}

		return $query;
	}

	/**
	 * To reduce ad credits if the order is cancled
	 *
	 * @param   string  $id  Order id.
	 *
	 * @return  items
	 *
	 * @since  1.6
	 */
	public function reduceAdCredits($id)
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('p.ad_id,p.ad_credits_qty');
		$query->from('`#__ad_payment_info` AS p');
		$query->join('LEFT', '`#__ad_orders` AS o ON o.id=p.order_id');
		$query->where('o.id =' . $id);
		$query->where('o.status' . " = 'C'");
		$this->_db->setQuery($query);
		$result = $this->_db->loadObject();

		if ($result->ad_credits_qty > 0)
		{
			$sql = "UPDATE #__ad_data
			SET ad_credits = ad_credits - $result->ad_credits_qty, ad_credits_balance = ad_credits_balance - $result->ad_credits_qty
			WHERE ad_id='" . $result->ad_id . "'";
			$db->setQuery($sql);
			$db->execute();
		}

		return;
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
	 * Store the staus value changed in list view of orders
	 *
	 * @return  Boolean value
	 *
	 * @since  1.6
	 */
	public function store()
	{
		$data = JRequest::get('post');
		$id = $data['id'];
		$status = $data['status'];
		$paymentHelper = new SocialadsPaymentHelper;

		if ($status == 'RF')
		{
			$this->reduceAdCredits($id);
			$query = "UPDATE #__ad_orders SET status ='RF' WHERE id =" . $id;
			$this->_db->setQuery($query);

			if (!$this->_db->execute())
			{
				return 2;
			}

			SaCommonHelper::new_pay_mail($id);

			return 3;
		}
		elseif ($status == 'E')
		{
			$this->reduceAdCredits($id);
			$query = "UPDATE #__ad_orders SET status ='E' WHERE id =" . $id;
			$this->_db->setQuery($query);

			if (!$this->_db->execute())
			{
				return 2;
			}

			// $socialadshelper->new_pay_mail($id);

			return 3;
		}

		elseif ($status == 'P')
		{
			$this->reduceAdCredits($id);

			$query = "UPDATE #__ad_orders SET status ='P' WHERE id =" . $id;
			$this->_db->setQuery($query);

			if (!$this->_db->execute())
			{
				return 2;
			}
		}
		elseif ($status == 'C')
		{
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);
			$query->select('p.ad_id,p.ad_credits_qty, o.*');
			$query->from('`#__ad_payment_info` AS p');
			$query->join('LEFT', '`#__ad_orders` AS o ON o.id=p.order_id');
			$query->where('o.id =' . $id);
			$this->_db->setQuery($query);
			$result = $this->_db->loadObject();
			$query = "UPDATE #__ad_orders SET status ='C' WHERE id =" . $id;
			$this->_db->setQuery($query);

			if (!$this->_db->execute())
			{
				return 2;
			}

			// Entry for transaction table
			$query = "SELECT id FROM #__ad_orders WHERE id = " . $id;
			$this->_db->setQuery($query);
			$ad = $this->_db->loadresult();

			JLoader::import('payment', JPATH_SITE . '/components/com_socialads/models');
			$socialadsModelpayment = new socialadsModelpayment;

			if (empty($ad))
			{
				// Add wallet
				$comment = 'COM_SOCIALADS_WALLET_ADS_PAYMENT';
				$transc = $socialadsModelpayment->add_transc($result->original_amount, $id, $comment);
				$sendmail = $socialadsModelpayment->SendOrderMAil($id, $data['processor'], $payPerAd = 0);
			}
			else
			{
				// Pay per ad
				$sendmail = $socialadsModelpayment->SendOrderMAil($id, $data['processor'], $payPerAd = 1);
			}

			$adid = $result->ad_id;
			$qryad = "SELECT ad_payment_type FROM #__ad_data WHERE ad_id =" . $adid;
			$this->_db->setQuery($qryad);
			$ad_payment_type = $this->_db->loadResult();

			if ($ad_payment_type != 2)
			{
				$query = "UPDATE #__ad_data SET ad_credits = ad_credits + $result->ad_credits_qty,
				ad_credits_balance = ad_credits_balance + $result->ad_credits_qty WHERE ad_id=" . $result->ad_id;
				$this->_db->setQuery($query);
				$this->_db->execute();
			}

			// Added for date type ads

			if (empty($subscriptiondata[0]->subscr_id) and ($ad_payment_type == 2))
			{
				$paymentHelper->adddays($adid, $result->ad_credits_qty);
			}

			// End for date type ads
		}
		else
		{
			$query = "UPDATE #__ad_orders SET status ='P' WHERE id =" . $id;
			$this->_db->setQuery($query);

			if (!$this->_db->execute())
			{
				return 2;
			}
		}

		return 1;
	}
}
