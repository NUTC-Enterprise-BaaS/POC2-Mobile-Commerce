<?php
/**
 * @version    SVN:<SVN_ID>
 * @package    Com_Socialads
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved
 * @license    GNU General Public License version 2, or later
 */

// No direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');

/**
 * payment model class.
 *
 * @since  1.6
 */
class SocialadsModelPayment extends JModelForm
{
	/**
	 * This function to get a form data
	 *
	 * @param   integer  $data      database values
	 * @param   boolean  $loadData  load data
	 *
	 * @return  array
	 *
	 * @since  1.6
	 **/
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_socialads', 'payment', array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form))
		{
			return false;
		}

		return $form;
	}

	/**
	 * This function to get order details
	 *
	 * @param   integer  $tid  order id of a order
	 *
	 * @return  array
	 *
	 * @since  1.6
	 **/
	public function getdetails($tid)
	{
		// Get a db connection.
		$db = JFactory::getDbo();

		// Create a new query object.
		$query = $db->getQuery(true);
			$query -> select($db->quoteName(array('o.payee_id', 'o.processor', 'o.amount', 'o.original_amount', 'o.coupon', 'o.prefix_oid')))
					->from($db->quoteName('#__ad_orders', 'o'))
					->where($db->quoteName('o.id') . '=' . $tid);

		$this->_db->setQuery($query);

		$details = $this->_db->loadObjectlist();
		$orderdata = array('payment_type' => '',
		'order_id' => $tid,
		'pg_plugin' => $details[0]->processor,
		'user' => $details[0]->payee_id,
		'amount' => $details[0]->amount,
		'original_amount' => $details[0]->original_amount,
		'prefix_oid' => $details[0]->prefix_oid,
		'coupon' => $details[0]->coupon,
		'success_message' => '');

		return $orderdata;
	}

	/**
	 * This function to get payment plugin
	 *
	 * @param   string   $pg_plugin  payment gateway name
	 * @param   integer  $order_id   order id of a order
	 * @param   integer  $payPerAd   Default variable for payment mode
	 *
	 * @return  array
	 *
	 * @since  1.6
	 **/
	public function getPaymentVars($pg_plugin, $order_id, $payPerAd = "pay_per_ad_mode")
	{
		global $mainframe;
		$mainframe = JFactory::getApplication();
		$orderdata = $this->getdetails($order_id);

		$vars = new stdclass;
		$vars->order_id = $orderdata['order_id'];

		if (!empty($orderdata['payment_type']))
		{
			$vars->payment_type = $orderdata['payment_type'];
		}
		else
		{
			$vars->payment_type = "";
		}

		$order_user = JFactory::getUser($orderdata['user']);
		$vars->user_id = $orderdata['user'];
		$vars->user_name = $order_user->name;
		$vars->user_firstname = $order_user->name;
		$vars->user_email = $order_user->email;
		$params = JComponentHelper::getParams('com_socialads');
		$payPerAd = $params->get('payment_mode');

		if ($payPerAd == 'wallet_mode')
		{
			$vars->item_name = JText::_('COM_SOCIALADS_ADWALLET_PAYMENT_DESC');
		}
		else
		{
			$vars->item_name = JText::_('COM_SOCIALADS_PAY_PER_AD_PAYMENT_DESC');
		}

		$msg_fail = JText::_('COM_SOCIALAD_PAYMENT_ERROR_IN_SAVING_DETAILS');

		$calledFrom = "&adminCall=0";

		if ($mainframe->isAdmin())
		{
			$calledFrom = "&adminCall=1";
			$vars->return = JRoute::_(JUri::root() . "administrator/index.php?option=com_socialads&view=forms" . $calledFrom, false);
		}
		else
		{
			$defaultMsg = '';

			if ($pg_plugin == 'paypal')
			{
				$defaultMsg = "&saDefMsg=1";
			}

			$vars->return = JRoute::_(JUri::root() . "index.php?option=com_socialads&view=ads&layout=default" . $defaultMsg, false);
		}

		$vars->submiturl = JRoute::_(JUri::root() . "administrator/index.php?com_socialads&view=orders", $msg_fail);

		// CANCEL URL
		if ($mainframe->isAdmin())
		{
			$vars->cancel_return = JRoute::_(JUri::root() . "administrator/index.php?com_socialads&view=orders", $msg_fail);
		}
		else
		{
			$vars->cancel_return = JRoute::_(JUri::root() . "index.php?option=com_socialads&view=ads&layout=default&processor=" . $pg_plugin, $msg_fail);
		}

		// Notify_url URL
		if ($mainframe->isAdmin())
		{
			// @TODO support payper ad mode in processpayment function
			// If (empty($payPerAd))
			{
				// Call dummy controller url
				$vars->url = $vars->notify_url = JRoute::_(
					JUri::root() . "administrator/index.php?option=com_socialads&task=pay.processpayment&pg_nm=" .
					$pg_plugin . "&pg_action=onTP_Processpayment&order_id=" .
					$orderdata['prefix_oid'] . "&original_amount=" . $orderdata['original_amount'] .
					"&mode=" . $payPerAd . $calledFrom, false
				);
			}
		}
		else
		{
			/* @TODO support payper ad mode in processpayment function
			 If (empty($payPerAd)) */

			$vars->url = $vars->notify_url = JRoute::_(
				JUri::root() . "index.php?option=com_socialads&task=payment.processpayment&pg_nm=" .
				$pg_plugin . "&pg_action=onTP_Processpayment&order_id=" . $orderdata['prefix_oid'] .
				"&original_amount=" . $orderdata['original_amount'] . "&mode=" .
				$payPerAd . $calledFrom, false
				);
		}

		$vars->currency_code = $params->get('currency');
		$vars->amount = $orderdata['amount'];
		$vars->client = "socialads";
		$vars->success_message = $orderdata['success_message'];

		/**
		if ($vars->payment_type=='recurring')
		{
			$vars->notify_url= $vars->url=$vars->url."&payment_type=recurring";
			$vars->recurring_startdate=$orderdata['recurring_startdate'];
			$vars->recurring_payment_interval_unit="days";
			$vars->recurring_payment_interval_totaloccurances=$orderdata['recurring_payment_interval_totaloccurances'];
			$vars->recurring_payment_interval_length=$orderdata['recurring_payment_interval_length'];
		}
		**/

		$vars->userInfo = $this->userInfo($order_id, $orderdata['user']);

		return $vars;
	}

	/**
	 * This function to get users info
	 *
	 * @param   integer  $order_id  order id of a order
	 * @param   integer  $userid    user id of a perticular user
	 *
	 * @return  array
	 *
	 * @since  1.6
	 **/
	public function userInfo($order_id, $userid = '')
	{
		if (empty($userid))
		{
			$user = JFactory::getUser();
			$userid = $user->id;
		}

		$db = JFactory::getDBO();
		$query = "Select `user_id`,`user_email`,`firstname`,`lastname`,`country_code`,`state_code`,`address`,
		`city`,`phone`,`zipcode` FROM #__ad_users WHERE user_id=" . $userid . ' ORDER BY `id` DESC';
		$db->setQuery($query);
		$billDetails = $db->loadAssoc();

		// Make address in 2 lines
		$billDetails['add_line1'] = $billDetails['address'];
		$billDetails['add_line2'] = '';

		// Remove new line
		$remove_character = array("\n", "\r\n", "\r");
		$billDetails['add_line1'] = str_replace($remove_character, ' ', $billDetails['add_line1']);
		$billDetails['add_line2'] = str_replace($remove_character, ' ', $billDetails['add_line2']);

		return $billDetails;
	}

	/**
	 * This function to confirm payment
	 *
	 * @param   string   $pg_plugin  payment gateway name
	 * @param   integer  $oid        order id of a order
	 *
	 * @return  void
	 *
	 * @since  1.6
	 **/
	public function confirmpayment($pg_plugin, $oid)
	{
		$post = JRequest::get('post');
		$vars = $this->getPaymentVars($pg_plugin, $oid);

		if (!empty($post) && !empty($vars))
		{
			JPluginHelper::importPlugin('payment', $pg_plugin);
			$dispatcher = JDispatcher::getInstance();
			$result = $dispatcher->trigger('onTP_ProcessSubmit', array($post,$vars));
		}
		else
		{
			JFactory::getApplication()->enqueueMessage(JText::_('SOME_ERROR_OCCURRED'), 'error');
		}
	}

	/**
	 * This function to get payment plugin data
	 *
	 * @return  array
	 *
	 * @since  1.6
	 **/
	public function getAPIpluginData()
	{
		$condtion = array(0 => '\'payment\'');
		$condtionatype = join(',', $condtion);

		if (JVERSION >= '1.6.0')
		{
			$query = "SELECT extension_id as id,name,element,enabled as published FROM #__extensions WHERE folder in ($condtionatype) AND enabled=1";
		}
		else
		{
			$query = "SELECT id,name,element,published FROM #__plugins WHERE folder in ($condtionatype) AND published=1";
		}

		$this->_db->setQuery($query);
		$paymentPluginData = $this->_db->loadobjectList();

		if (JVERSION >= '1.6.0')
		{
			foreach ($paymentPluginData as $payParam)
			{
						// Code to get the plugin param name
						$plugin = JPluginHelper::getPlugin('payment', $payParam->element);
						$params = new JRegistry($plugin->params);
						$pluginName = $params->get('plugin_name', $payParam->name, 'STRING');
						$payParam->name = $pluginName;
			}
		}

		return $paymentPluginData;
	}

	/**
	 * This function to create a order
	 *
	 * @param   array  $orderdata  order data
	 *
	 * @return  integer
	 *
	 * @since  1.6
	 **/
	public function createorder($orderdata = '')
	{
		$user = JFactory::getUser();
		$db = JFactory::getDBO();
		$paymentdata = new stdClass;
		$paymentdata->id = '';
		$paymentdata->cdate = date('Y-m-d H:i:s');
		$paymentdata->ip_address = $_SERVER["REMOTE_ADDR"];

		// $paymentdata->ad_id = $orderdata['adid'];

		$paymentdata->processor = $orderdata['pg_plugin'];

		// $paymentdata->ad_credits_qty = $orderdata['credits'];
		$paymentdata->amount = $orderdata['amount'];
		$paymentdata->original_amount = $orderdata['original_amount'];

		if (empty($orderdata['status']) or $orderdata['status'] == 'p')
		{
			$paymentdata->status = 'P';
		}
		else
		{
			$paymentdata->status = $orderdata['status'];
		}

		$paymentdata->coupon = $orderdata['coupon'];

		if (empty($orderdata['payee_id']))
		{
			$paymentdata->payee_id = $user->id;
		}
		else
		{
			$paymentdata->payee_id = $orderdata['payee_id'];
		}

		if (isset($orderdata['comment']))
		{
			$paymentdata->comment = $orderdata['comment'];
		}

		$sticketid = $this->checkduplicaterecord($paymentdata);

		if (!$sticketid)
		{
			if (!$db->insertObject('#__ad_orders', $paymentdata, 'id'))
			{
				echo $db->stderr();

				return false;
			}

			$orderid = $db->insertID();

			$sa_params = JComponentHelper::getParams('com_socialads');
			$order_prefix = (string) $sa_params->get('order_prefix');

			// String length should not be more than 5
			$order_prefix = substr($order_prefix, 0, 5);

			// Take separator set by admin
			$separator = (string) $sa_params->get('separator');

			$res = new stdclass;

			$res->prefix_oid = $order_prefix . $separator;

			// Check if we have to add random number to order id
			$use_random_orderid = (int) $sa_params->get('random_orderid');
			$socialadPaymentHelper = new SocialadsPaymentHelper;

			if ($use_random_orderid)
			{
				$random_numer = $socialadPaymentHelper->_random(5);
				$res->prefix_oid .= $random_numer . $separator;

				// This length shud be such that it matches the column lenth of primary key
				// It is used to add pading
				$len = (23 - 5 - 2 - 5);

				// Order_id_column_field_length - prefix_length - no_of_underscores - length_of_random number
			}
			else
			{
				// This length shud be such that it matches the column lenth of primary key
				// It is used to add pading
				$len = (23 - 5 - 2);
			}

			$maxlen = 23 - strlen($res->prefix_oid) - strlen($orderid);

			$padding_count = (int) $sa_params->get('padding_count');

			// Use padding length set by admin only if it is les than allowed(calculate) length

			if ($padding_count > $maxlen)
			{
				$padding_count = $maxlen;
			}

			if (strlen((string) $orderid) <= $len)
			{
				$append = '';

				for ($z = 0;$z < $padding_count;$z++)
				{
					$append .= '0';
				}

				$append = $append . $orderid;
			}

			$res->id = $orderid;
			$res->prefix_oid = $res->prefix_oid . $append;

			if (!$db->updateObject('#__ad_orders', $res, 'id'))
			{
				// Return false;
			}
		}
		else
		{
			$sticketid;
			$paymentdata->processor;
			$query = "UPDATE #__ad_orders SET amount=$paymentdata->amount , processor='$paymentdata->processor' WHERE id=$sticketid";
			$this->_db->setQuery($query);
			$this->_db->execute($query);

			$orderid = $sticketid;
		}

		// Send mail for status pending

		$session = JFactory::getSession();

		if ($session->has('order_id'))
		{
			$session->clear('order_id');
		}

		$session->set('order_id', $orderid);

		return $orderid;
	}

	/**
	 * This function to check duplicate record
	 *
	 * @param   array  $res1  order related data to check already exist
	 *
	 * @return  integer
	 *
	 * @since  1.6
	 **/
	public function checkduplicaterecord($res1)
	{
		// Clone object for php
		$res2 = clone $res1;
		$db = JFactory::getDBO();
		$res2->original_amount = number_format($res2->original_amount, 2, '.', '');
		$res2->cdate = date('Y-m-d', strtotime($res2->cdate));

		$query = "select id from #__ad_orders where payee_id=" . $db->quote($res2->payee_id) .
		" AND  status='P' AND DATE_FORMAT(cdate,'%Y-%m-%d')=" . $db->quote($res2->cdate) . " AND original_amount=" .
		$res2->original_amount;
		$db->setQuery($query);

		return $id = $db->loadresult();
	}

	/**
	 * This function to process a payment
	 *
	 * @param   array    $post          payment related data
	 * @param   string   $pg_nm         payment gateway name
	 * @param   string   $pg_action     payment gateway name
	 * @param   integer  $order_id      order id of a order
	 * @param   integer  $org_amt       original amount
	 * @param   integer  $payment_mode  pricing mode, Wallet mode or Pay per Ad mode
	 *
	 * @return  string
	 *
	 * @since  1.6
	 **/
	public function processpayment($post, $pg_nm, $pg_action, $order_id, $org_amt, $payment_mode = 'pay_per_ad_mode')
	{
		$return_resp = array();
		$db          = JFactory::getDBO();
		$input       = JFactory::getApplication()->input;
		$isadmin     = $input->get('adminCall', 0, 'INTEGER');

		// Authorise Post Data
		if ($post['plugin_payment_method'] == 'onsite')
		{
			$plugin_payment_method = $post['plugin_payment_method'];
		}

		// Get VARS
		$vars = $this->getPaymentVars($pg_nm, $order_id);

		// END vars
		$dispatcher = JDispatcher::getInstance();
		JPluginHelper::importPlugin('payment', $pg_nm);
		$data = $dispatcher->trigger($pg_action, array($post, $vars));
		$data = $data[0];

		if ($payment_mode == 'pay_per_ad_mode')
		{
			$ads_itemid = SaCommonHelper::getSocialadsItemid('ads');
		}
		else
		{
			$wallet_itemid = SaCommonHelper::getSocialadsItemid('wallet');
		}

		// Get order id
		if (empty($order_id))
		{
			$order_id = $data['order_id'];
		}

		$return_resp['return'] = $data['return'];
		$processed             = 0;
		$res                   = $this->storelog($pg_nm, $data);
		$processed             = $this->Dataprocessed($data['transaction_id'], $order_id);

		$query = "SELECT o.amount
		 FROM #__ad_orders  as o
		 where o.id=" . $order_id;
		$this->_db->setQuery($query);
		$order_amount = $this->_db->loadResult();

		$return_resp['status'] = '0';

		$component_link = 'index.php?option=com_socialads';

		if ($data['status'] == 'C' && $order_amount == $data['total_paid_amt'])
		{
			if ($processed == 0)
			{
				$this->saveOrder($data, $order_id, $pg_nm, $payment_mode);

				if ($payment_mode == 'pay_per_ad_mode')
				{
					$link = empty($isadmin) ? $component_link . '&view=forms&itemid=' . $ads_itemid : 'administrator/index.php?option=com_socialads&view=forms';
				}
				else
				{
					$link = empty($isadmin) ? $component_link .
					'&view=wallet&itemid=' . $wallet_itemid : 'administrator/index.php?option=com_socialads&view=wallets';
				}

				// @TODO - manoj -needs to chk what urls to pass
				// $return_resp['return'] = JUri::root() . substr(JRoute::_($link, false), strlen(JUri::base(true)) + 1);
				$return_resp['return'] = JRoute::_(JUri::root() . $link, false);
			}

			$return_resp['msg'] = $data['success'];
			$return_resp['status'] = '1';
		}
		elseif (!empty($data['status']))
		{
			if ($plugin_payment_method and  $data['status'] == 'P')
			{
				if ($payment_mode == 'pay_per_ad_mode')
				{
					$link = empty($isadmin) ? $component_link . '&view=forms&itemid=' . $ads_itemid : 'administrator/index.php?option=com_socialads&view=forms';
				}
				else
				{
					$link = empty($isadmin) ? $component_link . '&view=wallet&itemid=' .
					$wallet_itemid : 'administrator/index.php?option=com_socialads&view=wallets';
				}

				// @TODO - manoj -needs to chk what urls to pass
				// $return_resp['return'] = JUri::root() . substr(JRoute::_($link, false), strlen(JUri::base(true)) + 1);
				$return_resp['return'] = JRoute::_(JUri::root() . $link, false);
			}

			if ($order_amount != $data['total_paid_amt'])
			{
				$data['status'] = 'E';
				$this->cancelOrder($data, $order_id, $pg_nm);
			}
			elseif ($data['status'] != 'C')
			{
				$data['status'] = 'P';
				$this->cancelOrder($data, $order_id, $pg_nm);
			}
			elseif ($data['status'] != 'C' and $processed == 0)
			{
				$data['status'] = 'P';
				$this->updateOrderStatus($data, $order_id, $pg_nm);
			}

			$return_resp['status'] = '0';

			if (!empty($data['error']))
			{
				$return_resp['msg'] = $data['error']['code'] . $data['error']['desc'];
			}

			if ($payment_mode == 'pay_per_ad_mode')
			{
				$link = ($isadmin == 0) ? $component_link . '&view=ads&itemid=' . $ads_itemid : 'administrator/index.php?option=com_socialads&view=forms';
			}
			elseif ($payment_mode == 'wallet_mode')
			{
				$link = ($isadmin == 0) ? $component_link . '&view=wallet&itemid=' . $wallet_itemid : 'administrator/index.php?option=com_socialads&view=wallets';
			}

			$return_resp['return'] = JRoute::_(JUri::root() . $link, false);
		}

		// $this->SendOrderMAil($order_id,$pg_nm);
		// As we have not going to send any mail till order confirm
		return $return_resp;
	}

	/**
	 * This function to save order. @TODO support payper ad mode in this function.
	 *
	 * @param   array    $data          order related data
	 * @param   integer  $orderid       order id of a order
	 * @param   string   $pg_nm         payment gateway name
	 * @param   integer  $payment_mode  pricing mode, Wallet mode or Pay per Ad mode
	 *
	 * @return  boolean
	 *
	 * @since  1.6
	 **/
	public function saveOrder($data, $orderid, $pg_nm, $payment_mode = 'pay_per_ad_mode')
	{
		$db = JFactory::getDBO();
		$paymentdata = new stdClass;
		$paymentdata->id = $orderid;
		$paymentdata->transaction_id = $data['transaction_id'];
		$socialadPaymentHelper = new SocialadsPaymentHelper;

		if (!empty($pg_nm))
		{
			$paymentdata->processor = $pg_nm;
		}

		if ($data['status'] == 'C')
		{
			$paymentdata->status = 'C';

			/* //@TODO Recurring code
			if (!empty($data['payment_type']) && $data['payment_type'] == 'recurring')
			{
				$paymentdata->subscr_id = $data['subscr_id'];

				if (empty($data['payment_number']))
				{
					$paymentdata->status = 'P';
				}
			}
			*/

			if ($payment_mode == 'pay_per_ad_mode')
			{
				// ^ changed in v3.1 + Manoj
				// WHERE id =" . $orderid; => id to order_id
				$query = "SELECT subscr_id, ad_credits_qty, ad_id
				 FROM #__ad_payment_info
				 WHERE order_id =" . $orderid;
				$db->setQuery($query);
				$ad_payment_info = $db->loadObject();

				if (!$ad_payment_info->ad_credits_qty)
				{
					$ad_payment_info->ad_credits_qty = 0;
				}

				// Added for date type ads
				$adid = $ad_payment_info->ad_id;
				$query = "SELECT ad_payment_type
				 FROM #__ad_data
				 WHERE ad_id =" . $adid;
				$db->setQuery($query);
				$ad_payment_type = $db->loadResult();

				if (($ad_payment_type == 2))
				{
					$socialadPaymentHelper->adddays($adid, $ad_payment_info->ad_credits_qty);
				}
				else
				{
					$query = "UPDATE #__ad_data
					 SET ad_credits = ad_credits + " . $ad_payment_info->ad_credits_qty . ",
					 ad_credits_balance = ad_credits_balance + " . $ad_payment_info->ad_credits_qty . "
					 WHERE ad_id=" . $ad_payment_info->ad_id;
					$db->setQuery($query);
					$db->execute();
				}
			}
			else
			{
				$query = "SELECT original_amount
				 FROM #__ad_orders
				 WHERE id =" . $orderid;
				$db->setQuery($query);
				$tol_amt = $db->loadresult();
				$comment = "COM_SOCIALADS_WALLET_ADS_PAYMENT";
				$transc = $this->add_transc($tol_amt, $orderid, $comment);
			}
		}

		$paymentdata->extras = $data['raw_data'];

		if (!$db->updateObject('#__ad_orders', $paymentdata, 'id'))
		{
			echo $db->stderr();

			return false;
		}

		if ($paymentdata->status == 'C')
		{
			$socialadsModelpayment = new socialadsModelpayment;
			$sendmail = $socialadsModelpayment->SendOrderMAil($orderid, $pg_nm);

			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * This function to cancel order
	 *
	 * @param   array    $data      order related data
	 * @param   integer  $order_id  order id of a order
	 * @param   string   $pg_nm     payment gateway name
	 *
	 * @return  boolean
	 *
	 * @since  1.6
	 **/
	public function cancelOrder($data, $order_id, $pg_nm)
	{
			$query = "UPDATE #__ad_orders SET status ='{$data['status']}',extras='{$data['raw_data']}' WHERE id =" . $order_id;
			$this->_db->setQuery($query);

			if (!$this->_db->execute())
			{
				echo $db->stderr();

				return false;
			}
	}

	/**
	 * This function to update order status
	 *
	 * @param   array    $data      order related data
	 * @param   integer  $order_id  order id of a order
	 * @param   string   $pg_nm     payment gateway name
	 *
	 * @return  boolean
	 *
	 * @since  1.6
	 **/
	public function updateOrderStatus($data, $order_id, $pg_nm)
	{
			$query = "UPDATE #__ad_orders SET status ='{$data['status']}',extras='{$data['raw_data']}' WHERE id =" . $order_id;
			$this->_db->setQuery($query);

			if (!$this->_db->execute())
			{
				echo $db->stderr();

				return false;
			}
	}

	/**
	 * This function to check data is avilable for that order
	 *
	 * @param   integer  $transaction_id  transaction id of order
	 * @param   integer  $order_id        order id of a order
	 *
	 * @return  integer
	 *
	 * @since  1.6
	 **/
	public function Dataprocessed($transaction_id, $order_id)
	{
		$where = '';
		$db = JFactory::getDBO();

		$query = "SELECT id FROM #__ad_orders WHERE id={$order_id} AND status='C'" . $where;
		$db->setQuery($query);
		$paymentdata = $db->loadResult();

		if (!empty($paymentdata))
		{
			return 1;
		}
		else
		{
			return 0;
		}
	}

	/**
	 * This function to store payment transactions
	 *
	 * @param   string  $pg_plugin  payment gateway name
	 * @param   string  $data       payment related data
	 *
	 * @return  void
	 *
	 * @since  1.6
	 **/
	public function storelog($pg_plugin, $data)
	{
		$data1 = array();
		$data1['raw_data'] = $data['raw_data'];
		$data1['JT_CLIENT'] = "com_socialads";
		$dispatcher = JDispatcher::getInstance();
		JPluginHelper::importPlugin('payment', $pg_plugin);
		$data = $dispatcher->trigger('onTP_Storelog', array($data1));
	}

	/**
	 * This function to send order email to advertiser
	 *
	 * @param   integer  $order_id  order id of perticular process
	 * @param   string   $pg_nm     payment gateway name
	 * @param   integer  $payPerAd  payment mode pay per ad or ad wallet
	 *
	 * @return  void
	 *
	 * @since  1.6
	 **/
	public function SendOrderMAil($order_id, $pg_nm, $payPerAd = "wallet_mode")
	{
		// Require when we call from backend
		require_once JPATH_SITE . "/components/com_socialads/helpers/payment.php";
		$socialadPaymentHelper = new SocialadsPaymentHelper;

		$sendInvoice = 1;

		if ($sendInvoice == 1)
		{
			// $status = $socialadshelper->sendInvoice($order_id,$pg_nm);
			$details = $socialadPaymentHelper->getInvoiceDetail($order_id, $pg_nm, $payPerAd);
		}
	}

	/**
	 * This function for payment transcation
	 *
	 * @param   integer  $org_amt   original amount
	 * @param   integer  $order_id  order id of perticular process
	 * @param   integer  $comment   comment given while processing payment
	 *
	 * @return  array
	 *
	 * @since  1.6
	 **/
	public function add_transc($org_amt, $order_id, $comment)
	{
		$db = JFactory::getDBO();
		$query = "SELECT payee_id FROM #__ad_orders WHERE id =" . $order_id;
		$db->setQuery($query);
		$userid = $db->loadresult();

		$date = microtime(true);
		$date1 = date('Y-m-d');
		$query = "SELECT balance FROM #__ad_wallet_transc WHERE time = (SELECT MAX(time)  FROM #__ad_wallet_transc WHERE user_id="
		. $userid . ")";
		$db->setQuery($query);
		$bal = $db->loadresult();
		$balance = $bal + $org_amt;
		$amount_due = new stdClass;
		$amount_due->id = '';
		$amount_due->time = $date;
		$amount_due->user_id = $userid;
		$amount_due->spent = '';
		$amount_due->earn = $org_amt;
		$amount_due->balance = $balance;
		$amount_due->type = 'O';
		$amount_due->type_id = $order_id;
		$amount_due->comment = $comment;

		if (!$db->insertObject('#__ad_wallet_transc', $amount_due, 'id'))
		{
			echo $db->stderr();

			return false;
		}

		return $db->insertID();
	}

	/**
	 * This function get HTML for plugin process
	 *
	 * @param   string   $pg_plugin  payment gateway plugin name
	 * @param   integer  $order_id   order id of perticular process
	 * @param   integer  $payPerAd   payment mode pay per ad or ad wallet
	 *
	 * @return  html code
	 *
	 * @since  1.6
	 **/
	public function getHTML($pg_plugin, $order_id, $payPerAd = "pay_per_ad_mode")
	{
		$vars = $this->getPaymentVars($pg_plugin, $order_id, $payPerAd);
		$pg_plugin = trim($pg_plugin);
		JPluginHelper::importPlugin('payment', $pg_plugin);
		$dispatcher = JDispatcher::getInstance();
		$html = $dispatcher->trigger('onTP_GetHTML', array($vars));

		return $html;
	}

	/**
	 * This function update order gateway on change of gateway
	 *
	 * @param   string   $selectedGateway  Gateway selected to do payment
	 * @param   integer  $order_id         order id
	 *
	 * @return  integer
	 *
	 * @since  1.6
	 **/
	public function updateOrderGateway($selectedGateway, $order_id)
	{
		$db = JFactory::getDBO();
		$row = new stdClass;
		$row->id = $order_id;
		$row->processor = $selectedGateway;

		if (!$this->_db->updateObject('#__ad_orders', $row, 'id'))
		{
			echo $this->_db->stderr();

			return 0;
		}

		return 1;
	}

	/**
	 * Processor Free Order
	 *
	 * @params  void
	 *
	 * @return  redirect backend or fronted view
	 */
	public function processFreeOrder()
	{
		$mainframe = JFactory::getApplication();
		$jinput    = JFactory::getApplication()->input;
		$order_id  = $jinput->get('order_id', '', 'STRING');

		require_once JPATH_SITE . "/components/com_socialads/helpers/common.php";

		$adDetail = $this->syncOrderDetail($order_id);

		// If order amount is 0 due to coupon
		if ($adDetail->amount == 0  && !empty($adDetail->coupon))
		{
			$db  = JFactory::getDBO();
			$row = new stdClass;
			$row->status = 'C';
			$row->id = $order_id;

			if (!$db->updateObject('#__ad_orders', $row, 'id'))
			{
				echo $this->_db->stderr();
			}

			$data                 = array();
			$data['status']       = 'C';
			$data['payment_type'] = '';
			$data['raw_data']     = '';
			$pg_nm                = JText::_("COM_SOCIALADS_ADORDERS_VIA_COUPON");

			$this->saveOrder($data, $order_id, $pg_nm);
		}

		$response['msg'] = JText::_('COM_SOCIALADS_DETAILS_SAVE');

		if ($mainframe->isAdmin())
		{
			$link = 'index.php?option=com_socialads&view=forms';
		}
		else
		{
			$Itemid = SaCommonHelper::getSocialadsItemid('ads');
			$link   = JUri::base() . substr(JRoute::_('index.php?option=com_socialads&view=ads&Itemid=' . $Itemid, false), strlen(JUri::base(true)) + 1);
		}

		$mainframe->redirect($link, $response['msg']);
	}

	/**
	 * This function deduct tax amount from discounted amount and store it in orders final amount
	 *
	 * @param   int  $order_id  Order table primary key
	 *
	 * @return  Object
	 *
	 * @since 3.1
	 */
	public function syncOrderDetail($order_id)
	{
		$db  = JFactory::getDBO();
		$val = 0;

		// Require when we call from backend
		require_once JPATH_SITE . "/components/com_socialads/helpers/payment.php";
		$socialadPaymentHelper = new SocialadsPaymentHelper;

		$query = $db->getQuery(true);

		$query->select("a.`original_amount`,a.`coupon`,a.`tax`,a.tax_details");
		$query->from("#__ad_orders AS a");
		$query->where("a.id = $order_id AND a.status != 'C'");

		$db->setQuery($query);
		$orderData = $db->loadAssoc();

		if (!empty($orderData) &&  !empty($orderData['coupon']))
		{
			$adcop = $socialadPaymentHelper->getCoupon($orderData['coupon']);

			if ($adcop)
			{
				// Discount rate
				if ($adcop[0]->val_type == 1)
				{
					$val = ($adcop[0]->value / 100) * $orderData['original_amount'];
				}
				else
				{
					$val = $adcop[0]->value;
				}
			}
			else
			{
				$val = 0;
			}
		}

		$discountedPrice = $orderData['original_amount'] - $val;

		// @TODO:need to check plugim type..
		$dispatcher = JDispatcher::getInstance();
		JPluginHelper::importPlugin('adstax');

		// Call the plugin and get the result
		$taxresults = $dispatcher->trigger('addTax', array($discountedPrice));

		$appliedTax = 0;

		if (!empty($taxresults) )
		{
			foreach ($taxresults as $tax)
			{
				if (!empty($tax) )
				{
						$appliedTax += $tax[1];
				}
			}
		}

		$amountAfterTax = $discountedPrice + $appliedTax;

		if ($amountAfterTax <= 0)
		{
			$amountAfterTax = 0;
		}

		$row              = new stdClass;
		$row->id          = $order_id;
		$row->tax         = $appliedTax;
		$row->amount      = $amountAfterTax;
		$row->coupon      = $val ? $orderData['coupon'] : '';
		$row->tax_details = json_encode($taxresults);

		if (!$db->updateObject('#__ad_orders', $row, 'id'))
		{
			echo $this->_db->stderr();
		}

		return $row;
	}
}
