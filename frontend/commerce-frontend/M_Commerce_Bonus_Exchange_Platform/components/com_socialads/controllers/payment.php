<?php
/**
 * @version    SVN:<SVN_ID>
 * @package    Com_Socialads
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved
 * @license    GNU General Public License version 2, or later
 */

// No direct access
defined('_JEXEC') or die(';)');
jimport('joomla.application.component.controller');

require_once JPATH_SITE . '/components/com_socialads/helper.php';
include_once JPATH_SITE . '/components/com_socialads/controller.php';

/**
 * Payment list controller class.
 *
 * @since  1.6
 */
class SocialadsControllerPayment extends JControllerLegacy
{
	/**
	 * Method to get gateway html.
	 *
	 * @return  void
	 *
	 * @since   3.0
	 **/
	public function getPaymentGatewayHtml()
	{
		$db = JFactory::getDBO();
		$jinput = JFactory::getApplication()->input;

		$model = $this->getModel('payment');
		$selectedGateway = $jinput->get('gateway', '');
		$order_id = $jinput->get('order_id', '');
		$payPerAd = $jinput->get('payPerAd', 0, 'INT');
		$return = '';

		if (!empty($selectedGateway) && !empty($order_id))
		{
			$model->updateOrderGateway($selectedGateway, $order_id);
			$payhtml = $model->getHTML($selectedGateway, $order_id, $payPerAd);
			$return = !empty($payhtml[0])? $payhtml[0]:'';
		}

		echo $return;
		jexit();
	}

	/**
	 * Method to makePayment.
	 *
	 * @return  void
	 *
	 * @since   3.0
	 **/
	public function makePayment()
	{
		$input = JFactory::getApplication()->input;

		// Used for recurring
		// $arb_flag = ($input->get('arb_flag')) ? $input->get('arb_flag') : 0;
		$SocialadsPaymentHelper = new SocialadsPaymentHelper;

		// To check whether coupon code is exist in database, used/or not by same user and how many times it is used
		$couponGet = $SocialadsPaymentHelper->getcoupon();
		$mod = $this->getModel('payment');

		// Amount added in amount tab
		$amount = $input->get('amount', '', 'FLOAT');
		$amt = $amount;
		$cop = $input->get('cop', '', 'STRING');
		$processor = $input->get('processor', '', 'STRING');
		$cop_dis_opn_hide = $input->get('cop_dis_opn_hide', '', 'INT');
		JRequest::setVar('coupon_code', $cop);

		if ($cop_dis_opn_hide == 0)
		{
			$adcop = $couponGet;

			if ($adcop)
			{
				if ($adcop[0]->val_type == 1)
				{
					// Discount rate
					$val = ($adcop[0]->value / 100) * $amount;
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

			$amt = round($amount - $val, 2);
		}

		if ($amt <= 0)
		{
			$amt = 0;
		}

		$user = JFactory::getUser();
		$option = $processor;
		JPluginHelper::importPlugin('socialads', $option);
		$dispatcher = JDispatcher::getInstance();

		if ($amt <= 0 && $adcop)
		{
			$paymentdata = new stdClass;
			$paymentdata->id = '';
			$paymentdata->ad_id = 0;
			$paymentdata->cdate = date('Y-m-d H:i:s');
			$paymentdata->processor = $option;
			$paymentdata->ad_amount = $amt;
			$paymentdata->ad_original_amt = $amount;
			$paymentdata->status = 'C';
			$paymentdata->ad_coupon = $cop;
			$paymentdata->payee_id = $user->id;
			$paymentdata->ip_address = $_SERVER["REMOTE_ADDR"];
			$sticketid = $this->checkduplicaterecord($paymentdata);

			if (!$sticketid)
			{
				if (!$db->insertObject('#__ad_payment_info', $paymentdata, 'id'))
				{
					echo $db->stderr();

					return false;
				}
			}
			else
			{
				$this->setSession_ticketid($sticketid);

				return $sticketid;
			}

			echo "<div class='coupon_discount_all'> </div>";
			jexit();
		}
		else
		{
			$payment_type = $recurring_startdate = "";
			$success_msg = '';
			$totalamt = $amt;

			if ($option == 'jomsocialpoints' or $option == 'alphauserpoints')
			{
				$plugin = JPluginHelper::getPlugin('payment', $option);
				$pluginParams = json_decode($plugin->params);
				$totalamt = $amt;
				$success_msg = JText::sprintf('TOTAL_POINTS_DEDUCTED_MESSAGE', $amt);
			}

			$orderdata = array(
								'payment_type' => $payment_type, 'order_id' => '', 'pg_plugin' => $option, 'user' => $user, 'adid' => 0,
								'amount' => $totalamt, 'original_amount' => $amount, 'coupon' => $cop, 'success_message' => $success_msg
								);

			// Here orderid is id in payment_info table
			$orderid = $mod->createorder($orderdata);

			if (!$orderid)
			{
				echo $msg = JText::_('ERROR_SAVE');
				exit();
			}

			$orderdata['order_id'] = $orderid;
			$html = $mod->getHTML($processor, $orderid);

			if (!empty($html))
			{
				echo $html[0];
			}

			jexit();
		}
	}

	/**
	 * Function get called when user click on confirm payment
	 *
	 * @return  void
	 *
	 * @since  1.6
	 *
	 **/
	public function confirmpayment()
	{
		$model = $this->getModel('payment');
		$session = JFactory::getSession();
		$jinput = JFactory::getApplication()->input;
		$order_id = $session->get('order_id');
		$pg_plugin = $jinput->get('processor');
		$response = $model->confirmpayment($pg_plugin, $order_id);
	}

	/**
	 * Function process payment
	 *
	 * @return  void
	 *
	 * @since  1.6
	 *
	 **/
	public function processpayment()
	{
		$mainframe = JFactory::getApplication();
		$input = JFactory::getApplication()->input;
		$session = JFactory::getSession();

		if ($session->has('payment_submitpost'))
		{
			$post = $session->get('payment_submitpost');
			$session->clear('payment_submitpost');
		}
		else
		{
			$post = JRequest::get('post');
		}

		$org_amt = $input->get('original_amt', '', 'FLOAT');

		$pg_nm = $input->get('pg_nm');
		$pg_action = $input->get('pg_action');
		$model = $this->getModel('payment');
		$prefix_oid = $input->get('order_id', '', 'STRING');
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query -> select($db->quoteName('o.id'))
				->from($db->quoteName('#__ad_orders', 'o'))
				->where($db->quoteName('o.prefix_oid') . 'LIKE "%' . $prefix_oid . '%"');
		$db->setQuery($query);
		$order_id = $db->loadResult();
		$mode = $input->get('mode', "pay_per_ad_mode", 'STRING');

		if (empty($post) || empty($pg_nm) )
		{
			JFactory::getApplication()->enqueueMessage(JText::_('SOME_ERROR_OCCURRED'), 'error');

			return;
		}

		$response = $model->processpayment($post, $pg_nm, $pg_action, $order_id, $org_amt, $mode);

		// $response['msg'] = trim($response['msg']);

		if (empty($response['msg']))
		{
			$response['msg'] = JText::_('COM_SOCIALADS_PAYMENT_THANK_YOU_FOR_ORDER');
		}

		$mainframe->redirect($response['return'], $response['msg']);
	}

	/**
	 * Function to add payment
	 *
	 * @return  void
	 *
	 * @since  1.6
	 *
	 **/
	public function addCouponPayment()
	{
		$mainframe = JFactory::getApplication();
		$input = JFactory::getApplication()->input;
		$user = JFactory::getUser();
		$coupon_code = $input->get('coupon_code', '', 'STRING');
		$value = $input->get('value', '', 'FLOAT');
		$mod = $this->getModel('payment');
		$comment = 'COM_SOCIALADS_WALLET_COUPON_ADDED';
		$success_msg = JText::sprintf('TOTAL_POINTS_DEDUCTED_MESSAGE', $value);
		$orderdata = array('order_id' => '', 'pg_plugin' => '',
		'user' => $user, 'adid' => 0, 'amount' => $value, 'original_amount' => $value, 'coupon' => $coupon_code,
		'success_message' => $success_msg, 'status' => 'C', 'comment' => $comment);
		$orderid = $mod->createorder($orderdata);
		$transc = $mod->add_transc($value, $orderid, $comment);
		$json = $orderid;
		$content = json_encode($json);
		echo $content;

		jexit();
	}

	/**
	 * Function to add payment
	 *
	 * @param   string  $c_code  Coupon
	 *
	 * @return  void
	 *
	 * @since  1.6
	 *
	 **/
	public function getcoupon($c_code = '')
	{
		$user  = JFactory::getUser();
		$db    = JFactory::getDBO();
		$input = JFactory::getApplication()->input;

		if (empty($c_code))
		{
			$c_code = $input->get('coupon_code', '', 'STRING');
		}

		$count   = '';
		$SocialadsPaymentHelper = new SocialadsPaymentHelper;
		$count   = $SocialadsPaymentHelper->getcoupon($c_code);
		$retdata = '';

		if ($count)
		{
			$c[] = array(
				"value"    => $count[0]->value,
				"val_type" => $count[0]->val_type
			);

			$retdata = json_encode($c);
		}
		else
		{
			$retdata = 0;
		}

		if (empty($c_code))
		{
			echo $retdata;
		}
		else
		{
			echo $retdata;
		}

		jexit();
	}

	/**
	 * Function to get jomsocial or alphauser points
	 *
	 * @return  void
	 *
	 * @since  1.6
	 *
	 **/
	public function getpoints()
	{
		$user = JFactory::getUser();
		$db = JFactory::getDBO();
		$input = JFactory::getApplication()->input;
		$count = -1;
		$plugin = JPluginHelper::getPlugin('payment', $input->get('plugin_name', '', 'STRING'));
		$pluginParams = json_decode($plugin->params);

		switch ($input->get('plugin_name', '', 'STRING'))
		{
			case 'jomsocialpoints':
				$query = "SELECT points FROM #__community_users WHERE userid=" . $user->id;
				$db->setQuery($query);
				$count = $db->loadResult();
				$conversion1 = $pluginParams->conversion;
				echo $count . "|" . $conversion1;
			break;

			// AlphaUserPoints Plugin Payment
			case 'alphauserpoints':
				$query = "SELECT points FROM #__alpha_userpoints where userid=" . $user->id;
				$db->setQuery($query);
				$count = $db->loadResult();
				$conversion2 = $pluginParams->conversion;
				echo $count . "|" . $conversion2;
			break;

			default: echo $count;
		}

		jexit();
	}

	/**
	 * Process free order
	 *
	 * @params  void
	 *
	 * @since  1.0
	 *
	 * @return void
	 */
	public function sa_processFreeOrder()
	{
		$model = $this->getModel('payment');
		$model->processFreeOrder();
	}
}
