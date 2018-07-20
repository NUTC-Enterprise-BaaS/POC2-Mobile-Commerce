<?php
/**
 * @version    SVN:<SVN_ID>
 * @package    Com_Socialads
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved
 * @license    GNU General Public License version 2, or later
 */
defined('_JEXEC') or die;

/**
 * payment helper class
 *
 * @since  1.6
 */
class SocialadsPaymentHelper
{
	/**
	 * This function is to update end date for date type ad
	 *
	 * @param   integer  $ad_id    An ad_id of ad
	 * @param   integer  $no_days  number of days
	 *
	 * @return  items
	 *
	 * @since  1.6
	 */
	public function adddays($ad_id,$no_days)
	{
		$db = JFactory::getDBO();
		$qry = "SELECT * FROM #__ad_data WHERE ad_id =" . $ad_id;
		$db->setQuery($qry);
		$adDetails = $db->loadObjectlist();

		$addata = new stdClass;
		$addata->ad_id = $ad_id;

		if ($adDetails[0]->ad_enddate == "0000-00-00")
		{
			$timestmp = strtotime(date("Y-m-d", strtotime($adDetails[0]->ad_startdate)) . " +" . $no_days . " day");
		}
		else
		{
			$timestmp = strtotime(date("Y-m-d", strtotime($adDetails[0]->ad_enddate)) . " +" . $no_days . " day");
		}

		$addata->ad_enddate = date("Y-m-d H:i:s", $timestmp);
		$db->updateObject('#__ad_data', $addata, 'ad_id');
	}

	/** send invoice is  when order is confirmd from backend and front end ( for pay per ad as well as wallet ad)
	 *
	 * @param   integer  $order_id  An ad_id of ad
	 * @param   integer  $pg_nm     number of days
	 * @param   integer  $payPerAd  number of days
	 *
	 * @return  void
	 *
	 * @since  1.6
	 */
	public function getInvoiceDetail($order_id,$pg_nm,$payPerAd= "pay_per_ad_mode")
	{
		if (empty($order_id))
		{
			return;
		}

		$mainframe = JFactory::getApplication();
		$site = $mainframe->getCfg('sitename');

		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('p.ad_id, o.id, o.amount,o.mdate, o.status, o.processor,o.transaction_id, o.payee_id');
		$query->from('`#__ad_orders` AS o');
		$query->join('LEFT', '`#__ad_payment_info` AS p ON p.order_id=o.id');
		$query->select("u.username,u.id, u.email");
		$query->join('LEFT', '`#__users` AS u ON u.id=o.payee_id');
		$query->where('o.id =' . $order_id);
		$db->setQuery($query);
		$orderDetails = $db->loadObject();
		$params = JComponentHelper::getParams('com_socialads');
		$payPerAd = $params->get('payment_mode');
		$body = JText::_('COM_SOCIALADS_INVOICE_PAY_PAYMENT_BODY');

		// GET BILL INFO
		JLoader::import('adform', JPATH_SITE . '/components/com_socialads/models');
		$socialadsModelAdform = new SocialadsModelAdform;
		$billinfo = $socialadsModelAdform->getbillDetails($orderDetails->id);

		// DEFINE ORDER STATUS
		if ($orderDetails->status == 'C')
		{
			$orderstatus = JText::_('COM_SOCIALADS_INVOICE_STATUS_COMPLEATE');
		}
		elseif ($orderDetails->status == 'P')
		{
			$orderstatus = JText::_('COM_SOCIALADS_INVOICE_STATUS_PENDING');
		}
		elseif ($orderDetails->status == 'E')
		{
			$orderstatus = JText::_('COM_SOCIALADS_INVOICE_STATUS_COMPLEATE_ERROR_OCCURED');
		}
		else
		{
			$orderstatus = JText::_('COM_SOCIALADS_INVOICE_AMOUNT_CANCELLED');
		}

		$inv_adver_Html = "";
		$sa_displayblocks = array();

		// If ad wallet
		if ($payPerAd == "wallet_mode")
		{
			$sa_displayblocks = array('invoiceDetail' => 1, 'billingDetail' => 0, 'adsDetail' => 0);
		}

		$billpath = SaCommonHelper::getViewpath('payment', 'invoice');
		ob_start();
		include $billpath;
		$inv_adver_Html = ob_get_contents();
		ob_end_clean();

		jimport('joomla.utilities.utility');

		global $mainframe;
		$app = JFactory::getApplication();
		$mainframe = JFactory::getApplication();
		$sitelink = JUri::root();
		$manageAdLink = "<a href='" . $sitelink . "administrator/index.php?option=com_socialads&view=forms'
		targe='_blank'>" . JText::_("COM_SOCIALADS_EMAIL_THIS_LINK") . "</a>";

		// GET config details
		$frommail  	= $mainframe->getCfg('mailfrom');
		$fromname = $mainframe->getCfg('fromname');

		// $adTitle = SaCommonHelper::getAdTitle($orderDetails->ad_id);
		$siteName = $mainframe->getCfg('sitename');
		$today = date('Y-m-d H:i:s');

		if (empty($orderDetails->payee_id))
		{
			// Return payee id not found
			return;
		}

		$SocialadsPaymentHelper = new SocialadsPaymentHelper;
		$user = JFactory::getUser($orderDetails->payee_id);
		$adUserName = $user->username;
		$recipient = $user->email;
		$displayOrderid = sprintf("%05s", $order_id);
		$adTitle = $SocialadsPaymentHelper->getAdTitle($orderDetails->ad_id);

		// If paper mode
		if ($payPerAd == "pay_per_ad_mode")
		{
			$approve_msg = JText::_("COM_SOCIALADS_INVOICE_MAIL_ADMIN_APPROCE_NO_MSG");

			if ($orderDetails->status == 'C')
			{
				$approve_msg = JText::_("COM_SOCIALADS_INVOICE_MAIL_ADMIN_APPROCE_YES_MSG");
			}

			// FOR ADVERTISER INVOICE AND ORDER CONFIRM MAIL
			$advertiserEmailBody = JText::_("COM_SOCIALADS_INVOICE_MAIL_CONTENT");

			// NOW find & REPLACE TAG
			$find = array('[SEND_TO_NAME]','[ADVERTISER_NAME]','[SITENAME]','[SITELINK]','[ADTITLE]','[CONTENT]',
			'[TIMESTAMP]','[ORDERID]','[ADMIN_APPROVAL_MSG]');
			$replace = array($adUserName, $adUserName, $siteName, $sitelink, $adTitle, $today, $displayOrderid, $approve_msg);
			$advertiserEmailBody = str_replace($find, $replace, $advertiserEmailBody);
			$advertiserEmailBody = $advertiserEmailBody . "<br> <br>" . $inv_adver_Html;

			$subject = JText::sprintf('COM_SOCIALADS_INVOICE_MAIL_SUB', $displayOrderid);
			$status  = SaCommonHelper::sendmail($recipient, $subject, $advertiserEmailBody, '', 0, "");

			// -------------- ADMIN INVOICE MAIL  COPY --------------------
			$adminEmailBody = JText::_("COM_SOCIALADS_INVOICE_MAIL_CONTENT_ADMIN_COPY");
			$orderPrice = $orderDetails->amount . " " . $params->get('currency');
			$admin_approve_msg = '';
			$admin_approve_msg = '';

			if ($orderDetails->status == 'C')
			{
				$admin_approve_msg = JText::sprintf("COM_SOCIALADS_APPRVE_MAIL_TO_ADMIN_ADD_MSG", $manageAdLink);
			}

			$find = array('[SEND_TO_NAME]', '[ADVERTISER_NAME]', '[SITENAME]', '[VALUE]', '[ORDERID]', '[ADMIN_APPROVAL_MSG]');
			$replace = array($fromname, $adUserName, $siteName, $orderPrice, $displayOrderid, $admin_approve_msg);
			$adminEmailBody = str_replace($find, $replace, $adminEmailBody);
			$adminEmailBody = $adminEmailBody . "<br> <br>" . $inv_adver_Html;
			$subject = JText::sprintf('COM_SOCIALADS_INVOICE_MAIL_ADVERTISER_ADMIN_SUBJECT', $displayOrderid);
			$status  = SaCommonHelper::sendmail($frommail, $subject, $adminEmailBody, '', 0, "");
		}
		else
		{
			// ADVERTISER MAIL
			$advertiserEmailBody = JText::_("COM_SOCIALADS_WALLET_ADDED_BALACE_ADVETISER_EMAIL");

			// NOW find & REPLACE TAG
			$find = array('[SEND_TO_NAME]', '[SITENAME]', '[ORDERID]');
			$replace = array($adUserName, $siteName, $displayOrderid);
			$advertiserEmailBody = str_replace($find, $replace, $advertiserEmailBody);
			$advertiserEmailBody = $advertiserEmailBody . "<br> <br>" . $inv_adver_Html;
			$subject = JText::sprintf('COM_SOCIALADS_WALLET_ADDED_BALACE_ADVETISER_EMAIL_SUBJECT', $displayOrderid);
			$status = SaCommonHelper::sendmail($recipient, $subject, $advertiserEmailBody, '', 0, "");

			// ADMIN INVOICE MAIL  COPY
			$adminEmailBody = JText::_("COM_SOCIALADS_INVOICE_MAIL_CONTENT_ADMIN_COPY");
			$orderPrice = $orderDetails->amount . " " . $params->get('currency');

			$find = array('[SEND_TO_NAME]', '[ADVERTISER_NAME]', '[SITENAME]', '[VALUE]', '[ORDERID]', '[ADMIN_APPROVAL_MSG]');
			$replace = array($fromname, $adUserName, $siteName, $orderPrice, $displayOrderid, '');
			$adminEmailBody = str_replace($find, $replace, $adminEmailBody);
			$adminEmailBody = $adminEmailBody . "<br> <br>" . $inv_adver_Html;
			$subject = JText::sprintf('COM_SOCIALADS_INVOICE_MAIL_ADVERTISER_ADMIN_SUBJECT', $displayOrderid);
			$status  = SaCommonHelper::sendmail($frommail, $subject, $adminEmailBody, '', 0, "");

		/**
		if (empty($c_code))
		{
			$c_code = $input->get('coupon_code','','STRING');
		}

		$count='';

		if ($c_code)
		{
			$query="SELECT value,val_type
				FROM #__ad_coupon
				WHERE ((CURDATE( ) BETWEEN from_date AND exp_date) OR from_date = '0000-00-00 00:00:00')
				AND (max_use  > (SELECT COUNT(api.coupon_code) FROM #__ad_payment_info as api WHERE api.coupon_code =".$db->quote($db->escape($c_code)). "
				AND api.status='C')
				OR max_use=0)
				AND (max_per_user > (SELECT COUNT(api.coupon_code) FROM #__ad_payment_info as api WHERE api.coupon_code = ".$db->quote($db->escape($c_code))."
				AND api.payee_id= ".$user->id." AND api.status='C') OR max_per_user=0)
				AND published = 1
				AND code=".$db->quote($db->escape($c_code));
			$db->setQuery($query);
			$count = $db->loadObjectList();
		}

		return $count;
		**/
		}
	}

	/** Function to get a user information
	 *
	 * @param   integer  $order_id  An ad_id of ad
	 *
	 * @return  array
	 *
	 * @since  1.6
	 */
	public function userInfo($order_id)
	{
		$db            = JFactory::getDbo();
		$query         = $db->getQuery(true);
		$query->select('au.*');
		$query->from($db->quoteName('#__ad_users', 'au'));
		$query->where('ao.id ="' . $order_id . '"');
		$query->join('LEFT', $db->quoteName('#__ad_orders', 'ao') . 'ON' . $db->quoteName('ao.payee_id') . '=' . $db->quoteName('au.user_id'));
		$query->order($db->quoteName('au.id') . ' DESC');
		$db->setQuery($query);
		$billDetails = $db->loadAssoc();

		return $billDetails;
	}

	/**
	 * This function get recurring gateways
	 *
	 * @return  array
	 *
	 * @since  1.6
	 */
	public function getRecurringGateways()
	{
		JPluginHelper::importPlugin('payment');
		$dispatcher = JDispatcher::getInstance();
		$re_selectbox = array();
		$newvar = JPluginHelper::getPlugin('payment');
		$sa_params = JComponentHelper::getParams('com_socialads');
		$slectedGateway = (array) $sa_params->get('gateways', 'paypal', 'STRING');

		if ($newvar)
		{
			foreach ($newvar as $myparam)
			{
				if (in_array($myparam->name, $slectedGateway))
				{
					$plugin = JPluginHelper::getPlugin('payment', $myparam->name);
					$gateway_style = "";
					$pluginParams = json_decode($plugin->params);

					if (!empty($pluginParams->arb_support))
					{
						$re_selectbox[] = $myparam->name;
					}
				}
			}

			return implode(',', $re_selectbox);
		}

		return '';
	}

	/**
	 * This function gives coupon code
	 *
	 * @param   string  $c_code  Coupon code
	 *
	 * @return  count
	 *
	 * @since  1.6
	 */
	public function getcoupon($c_code = '')
	{
		$input = JFactory::getApplication()->input;
		$user  = JFactory::getUser();
		$db    = JFactory::getDBO();

		if (empty($c_code))
		{
			$c_code = $input->get('coupon_code', '', 'STRING');
		}

		$count = '';

		if ($c_code)
		{
			$query = "SELECT value, val_type
			 FROM #__ad_coupon
			 WHERE (
				(NOW() BETWEEN from_date AND exp_date) OR from_date = '0000-00-00 00:00:00'
			 )
			 AND (
				 max_use  > (
					 SELECT COUNT(api.coupon)
					 FROM #__ad_orders as api
					 WHERE api.coupon =" . $db->quote($db->escape($c_code)) . "
					 AND api.status='C'
					)
				 OR max_use=0
				)
			 AND (
				max_per_user > (
					 SELECT COUNT(api.coupon)
					 FROM #__ad_orders as api
					 WHERE api.coupon = " . $db->quote($db->escape($c_code)) . "
					 AND api.payee_id= " . $user->id . "
					 AND api.status='C'
					)
					OR max_per_user=0
				)
			 AND state = 1
			 AND code=" . $db->quote($db->escape($c_code));
			$db->setQuery($query);
			$count = $db->loadObjectList();
		}

		return $count;
	}

	/**
	 * Fetch ad detail and recaculate and sync order details
	 *
	 * @param   integer  $order_id    $order_id
	 * @param   integer  $syncDetail  wherther to sync order detail or not. While order detail page with status = p then only keep to 1
	 *
	 * @return  array
	 *
	 * @since  3.0
	 *
	 **/
	public function getOrderAndAdDetail($order_id, $syncDetail=0)
	{
		$SocialadsPaymentHelper = new SocialadsPaymentHelper;

		if ($syncDetail == 1)
		{
			$SocialadsPaymentHelper->syncOrderDetail($order_id);
		}

		$db = JFactory::getDBO();
		$query = "SELECT   b.ad_id FROM #__ad_payment_info AS b
				WHERE b.order_id=" . $order_id;
		$db->setQuery($query);
		$adid = $db->loadResult();

		if (!empty($adid))
		{
			/*
				Add pay per ad
				$query = "SELECT  pi.*, a.*, b.* FROM #__ad_data as a
				LEFT JOIN  #__ad_payment_info AS pi
				ON pi.ad_id=a.ad_id
				LEFT JOIN  #__ad_orders AS b
				ON b.id=pi.order_id
				WHERE b.id= $order_id";
			*/
			$query = $db->getQuery(true);
			$query->select('a.*, o.original_amount, o.prefix_oid, o.amount, o.status, o.coupon, o.tax, o.processor, pi.ad_credits_qty');
			$query->from('#__ad_data AS a');
			$query->join('LEFT', '#__ad_payment_info AS pi ON pi.ad_id=a.ad_id');
			$query->join('LEFT', '#__ad_orders AS o ON o.id = pi.order_id');
			$query->where('o.id = ' . $order_id);
		}
		else
		{
			// Add wallet mode
			$query = "SELECT  o.*
			 FROM #__ad_orders AS o
			 WHERE o.id=" . $order_id;
		}

		$db->setQuery($query);

		return $addata = $db->loadAssoc();
	}

	/**
	 * Function to sync order details
	 *
	 * @param   integer  $order_id  $order_id
	 *
	 * @return  array
	 *
	 * @since  3.0
	 *
	 **/
	public function syncOrderDetail($order_id)
	{
		$db = JFactory::getDBO();
		$query = "SELECT   a.`original_amount`,a.`coupon`,a.`tax`,a.tax_details
		FROM  #__ad_orders AS a
		WHERE a.id= $order_id AND a.status != 'C'";
		$db->setQuery($query);
		$orderData = $db->loadAssoc();
		$val = 0;

		// For coupon discount
		if (!empty($orderData) &&  !empty($orderData['coupon']))
		{
			// Get payment HTML
			$showadmodel = new SocialadsPaymentHelper;
			$adcop = $showadmodel->getcoupon($orderData['coupon']);

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

		// <!-- TAX CALCULATION-->
		$dispatcher = JDispatcher::getInstance();

		// @TODO:need to check plugim type..
		JPluginHelper::importPlugin('adstax');

		// Call the plugin and get the result
		$taxresults = $dispatcher->trigger('addTax', array($discountedPrice));

		$appliedTax = 0;

		if (!empty($taxresults))
		{
			foreach ($taxresults as $tax)
			{
				if (!empty($tax))
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

		$row = new stdClass;
		$row->coupon = '';

		if (!empty($val))
		{
			$row->coupon = $orderData['coupon'];
		}

		$row->id = $order_id;
		$row->tax_details = json_encode($taxresults);
		$row->amount = $amountAfterTax;
		$row->tax = $appliedTax;

		if (!$db->updateObject('#__ad_orders', $row, 'id'))
		{
			echo $this->_db->stderr();
		}

		return $row;
	}

	/**
	 * Function to get slab details
	 *
	 * @param   string  $duration  Slab duration
	 *
	 * @return  array  $json
	 */
	public function getSlabDetails($duration)
	{
		$sa_params = JComponentHelper::getParams('com_socialads');

		foreach ($sa_params->get('configure_slab') as $slab)
		{
			if ($slab['duration'] == $duration)
			{
				return $slab;
			}
		}
	}

	/**
	 * Function to migrate old pricing ads to camp ads
	 *
	 * @param   string  $migrate_need_check  flag to hide campaign
	 *
	 * @return  int  $json
	 */
	public function migrateads_camp($migrate_need_check)
	{
		$sa_params = JComponentHelper::getParams('com_socialads');
		$input = JFactory::getApplication()->input;

		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('created_by');
		$query->from('`#__ad_data` GROUP BY created_by');
		$db->setQuery($query);
		$created_by = $db->loadColumn();
		$json = 0;

		// $msg = JText::_('AUTO_GENERATED');

		// Progess..............
		foreach ($created_by as $creator_id)
		{
			$query = $db->getQuery(true);
			$query->select('ad_id,ad_zone,ad_credits_balance,ad_payment_type,ad_noexpiry');
			$query->from('`#__ad_data`');
			$query->where('created_by = ' . $creator_id);
			$query->where('camp_id = 0');
			$db->setQuery($query);
			$ads_info = $db->loadobjectlist();

			// Create new camp as old camp.....
			$query = $db->getQuery(true);
			$query->select('campaign,id');
			$query->from('`#__ad_campaign`');
			$query->where("campaign = 'Old Ads for user " . $creator_id . "'");

			$query->where('created_by = ' . $creator_id);
			$db->setQuery($query);
			$ifexists_camp = $db->loadobjectlist();

			if ($ads_info)
			{
				foreach ($ads_info as $ad_info)
				{
					// To check if some credit balance exists
					if ($ad_info->ad_credits_balance)
					{
						if ($migrate_need_check == '1')
						{
							return 1;
						}
					}
				}
			}

			if (empty($ifexists_camp))
			{
				$insertcamp = new stdClass;
				$insertcamp->id = '';
				$insertcamp->created_by = $creator_id;
				$insertcamp->campaign = "Old Ads for user " . $creator_id;
				$insertcamp->daily_budget = $sa_params->get('min_pre_balance', 5);
				$insertcamp->state = 1;

				if (!$db->insertObject('#__ad_campaign', $insertcamp, 'id'))
				{
					echo $db->stderr();

					return false;
				}

				$last_id_camp = $db->insertid();
			}
			else
			{
				$last_id_camp = $ifexists_camp['0']->id;
			}

			if ($ads_info)
			{
				foreach ($ads_info as $ad_info)
				{
					// If balance then calculate USD value of credits
					if ($ad_info->ad_credits_balance)
					{
						// Zone pricing calculations
						if ($sa_params->get('zone_pricing', 0) == 1)
						{
							if ($ad_info->ad_zone)
							{
								$query = $db->getQuery(true);
								$query->select('per_imp,per_click');
								$query->from('`#__ad_zone`');
								$query->where('id = ' . $ad_info->ad_zone);
								$db->setQuery($query);
								$zone = $db->loadobjectlist();

								// Convert Per click credits in USD
								if ($ad_info->ad_payment_type == 1)
								{
									$usd_pay = $ad_info->ad_credits_balance * $zone['0']->per_click;
								}
								else
								{
									$usd_pay = $ad_info->ad_credits_balance * $zone['0']->per_imp;
								}
							}
						}
						else
						{
							// Convert Per click credits in USD
							if ($ad_info->ad_payment_type == 1)
							{
								$usd_pay = $ad_info->ad_credits_balance * $sa_params->get('per_clicks', '0.50');
							}
							else
							{
								$usd_pay = $ad_info->ad_credits_balance * $sa_params->get('per_impressions', '0.05');
							}
						}

						$comment_array = array();
						$comment_array[] = 'COM_SOCIALADS_WALLET_VIA_MIGRATTION';
						$comment_array[] = $ad_info->ad_id;
						$comment = implode('|', $comment_array);
						sleep(1);
						$insertpay = new stdClass;
						$insertpay->id = '';

						// $insertpay->ad_id = 0;
						$insertpay->cdate = date('Y-m-d H:i:s');
						$insertpay->mdate = date('Y-m-d H:i:s');
						$insertpay->payee_id = $creator_id;
						$insertpay->amount = $usd_pay;
						$insertpay->status = "C";
						$insertpay->ip_address = $_SERVER["REMOTE_ADDR"];
						$insertpay->original_amount = $usd_pay;
						$insertpay->comment = 'AUTO_GENERATED';

						if (!$db->insertObject('#__ad_orders', $insertpay, 'id'))
						{
							echo $db->stderr();

							return false;
						}

						$last_id_pay = $db->insertid();
						JLoader::import('payment', JPATH_SITE . DS . 'components' . DS . 'com_socialads' . DS . 'models');
						$socialadsModelpayment = new socialadsModelpayment;

						// Entry for camp_transc table
						$transac_id = $socialadsModelpayment->add_transc($usd_pay, $last_id_pay, $comment);

						$data = new stdClass;
						$data->ad_id = $ad_info->ad_id;
						$data->camp_id = $last_id_camp;

						if (!$db->updateObject('#__ad_data', $data, 'ad_id'))
						{
							echo $db->stderr();

							return 0;
						}

						$json = 1;
					}
					elseif ($ad_info->ad_noexpiry == 1)
					{
						$data = new stdClass;
						$data->ad_id = $ad_info->ad_id;
						$data->camp_id = $last_id_camp;

						if (!$db->updateObject('#__ad_data', $data, 'ad_id'))
						{
							echo $db->stderr();

							return 0;
						}

						$json = 1;
					}
				}
			}
		}

		return $json;
	}

	/**
	 * Function to migrate camp ads to old pricing
	 *
	 * @param   string  $migrate_need_check  flag to hide campaign
	 *
	 * @return  int  $json
	 */
	public function migrateads_old($migrate_need_check)
	{
		$sa_params = JComponentHelper::getParams('com_socialads');
		$input = JFactory::getApplication()->input;
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('created_by');
		$query->from('`#__ad_data` GROUP BY created_by');
		$db->setQuery($query);
		$created_by = $db->loadColumn();
		$json = 0;

		foreach ($created_by as $creator_id)
		{
			$query = $db->getQuery(true);
			$query->select('*');
			$query->from('`#__ad_data`');
			$query->where('created_by = ' . $creator_id);
			$query->where('camp_id <> 0');
			$query->where('ad_alternative=0 AND ad_noexpiry=0 AND ad_affiliate=0');
			$db->setQuery($query);
			$ads_info = $db->loadobjectlist();

			// Get balance
			$query = $db->getQuery(true);
			$query->select('id,balance');
			$query->from('`#__ad_wallet_transc`');
			$query->where("time = (select MAX(time) from #__ad_wallet_transc where user_id =" . $creator_id . ")");
			$db->setQuery($query);
			$current_bal = $db->loadobjectlist();

			if ($ads_info)
			{
				if (isset($current_bal[0]->balance))
				{
					if ($current_bal[0]->balance)
					{
						if ($migrate_need_check == '1')
						{
							return 1;
						}
					}
				}
			}

			// Get a paymenet gateway
			$query = $db->getQuery(true);
			$query->select('processor');
			$query->from('`#__ad_orders`');
			$query->where('payee_id = ' . $creator_id);
			$db->setQuery($query);
			$payment_gateway = $db->loadresult();

			if ($ads_info)
			{
				// Get nos of ads
				$count_ads = count($ads_info);

				// Divide bal/nos of ads
				$each_ad_money = 0.00;

				if (isset($current_bal[0]->balance))
				{
					$each_ad_money = $current_bal[0]->balance / $count_ads;
				}

				$each_ad_money_to_use = round($each_ad_money, 2);

				// Convert into credit as per click /imp and zone pricing
				foreach ($ads_info as $ad_info)
				{
					// For each ad calculate USD
					if (isset($current_bal[0]->balance))
					{
						// If for credits avaiable
						if ($current_bal[0]->balance)
						{
							// If balance then convert in USD
							if ($sa_params->get('zone_pricing', 0) == 1)
							{
								// Zone pricing
								if ($ad_info->ad_zone)
								{
									$query = $db->getQuery(true);
									$query->select('per_imp,per_click');
									$query->from('`#__ad_zone`');
									$query->where('id = ' . $ad_info->ad_zone);
									$db->setQuery($query);
									$zone = $db->loadobjectlist();

									if ($ad_info->ad_payment_type == 1)
									{
										// Per click ad
										$ad_info->ad_credits = $each_ad_money_to_use / $zone['0']->per_click;
									}
									else
									{
										$ad_info->ad_credits = $each_ad_money_to_use / $zone['0']->per_imp;
									}
								}
							}
							else
							{
								// Std pricing
								if ($ad_info->ad_payment_type == 1)
								{
									// Per click ad
									$ad_info->ad_credits = $each_ad_money_to_use / $sa_params->get('per_clicks', '0.50');
								}
								else
								{
									$ad_info->ad_credits = $each_ad_money_to_use / $sa_params->get('per_impressions', '0.05');
								}
							}

							sleep(1);
							$insertpay = new stdClass;
							$insertpay->id = '';
							$insertpay->cdate = $ad_info->ad_created_date;
							$insertpay->mdate = date('Y-m-d H:i:s');
							$insertpay->payee_id = $creator_id;
							$insertpay->amount = $each_ad_money_to_use;
							$insertpay->status = "C";
							$insertpay->ip_address = $_SERVER["REMOTE_ADDR"];
							$insertpay->original_amount = $each_ad_money_to_use;
							$insertpay->processor = $payment_gateway;
							$insertpay->comment = 'AUTO_GENERATED';

							if (!$db->insertObject('#__ad_orders', $insertpay, 'id'))
							{
								echo $db->stderr();

								return false;
							}

							$last_id_order = $db->insertid();
							$data = new stdClass;
							$data->id = '';
							$data->order_id = $last_id_order;
							$data->ad_id = $ad_info->ad_id;
							$data->ad_credits_qty = $ad_info->ad_credits;
							$data->cdate = date('Y-m-d H:i:s');

							if (!$db->insertObject('#__ad_payment_info', $data, 'id'))
							{
								echo $db->stderr();

								return 0;
							}

							$data = new stdClass;
							$data->ad_id = $ad_info->ad_id;
							$data->ad_credits = $ad_info->ad_credits;
							$data->ad_credits_balance = $ad_info->ad_credits;
							$data->camp_id = 0;

							if (!$db->updateObject('#__ad_data', $data, 'ad_id'))
							{
								echo $db->stderr();

								return 0;
							}

							/*$query = "UPDATE #__ad_wallet_transc SET balance=0 WHERE id=".$current_bal[0]->id;
							$db->setQuery($query);
							$db->execute();
							*/

							$comment_array = array();
							$comment_array[] = 'SPENT_DONE_FROM_MIGRATION';
							$comment_array[] = $ad_info->ad_id;
							$comment = implode('|', $comment_array);
							$date1 = microtime(true);
							sleep(1);
							$camp_trans = new stdClass;
							$camp_trans->id = '';
							$camp_trans->time = $date1;
							$camp_trans->user_id = $creator_id;
							$camp_trans->spent = $each_ad_money_to_use;
							$camp_trans->earn = '';
							$camp_trans->balance = '';
							$camp_trans->type = 'O';
							$camp_trans->type_id = '';
							$camp_trans->comment = $comment;

							if (!$db->insertObject('#__ad_wallet_transc', $camp_trans, 'id'))
							{
								echo $db->stderr();

								return false;
							}

							$json = 1;
						}
					}
				}
			}

			$query = $db->getQuery(true);
			$fields = array(
				$db->quoteName('camp_id') . ' = 0'
			);
			$conditions = array(
				$db->quoteName('ad_noexpiry') . ' = 1  OR ' . $db->quoteName('ad_affiliate') . ' = 1',
				$db->quoteName('created_by') . ' = ' . $creator_id
			);
			$query->update($db->quoteName('#__ad_data'))->set($fields)->where($conditions);
			$db->setQuery($query);
			$result = $db->execute();
		}

		return $json;
	}

	/**
	 * Function to get ad title
	 *
	 * @param   string  $ad_id  ad id
	 *
	 * @return  ad title
	 */
	public function getAdTitle($ad_id)
	{
		if (empty($ad_id))
		{
			return;
		}

		$db = JFactory::getDBO();
		$query = "SELECT a.ad_title FROM `#__ad_data` as a WHERE a.ad_id=" . $ad_id;
		$db->setQuery($query);

		return  $db->loadResult();
	}

	/**
	 * Function to get random number
	 *
	 * @param   integer  $length  Default length
	 *
	 * @return  integer
	 *
	 * @since   3.1
	 *
	 */
	public function _random($length = 17)
	{
		$salt = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$len = strlen($salt);
		$random = '';

		$stat = @stat(__FILE__);

		if (empty($stat) || !is_array($stat))
		{
			$stat = array(php_uname());
		}

		mt_srand(crc32(microtime() . implode('|', $stat)));

		for ($i = 0; $i < $length; $i ++)
		{
			$random .= $salt[mt_rand(0, $len - 1)];
		}

		return $random;
	}
}
