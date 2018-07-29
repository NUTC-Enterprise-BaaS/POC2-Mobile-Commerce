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

/**
 * Ads Helper class
 *
 * @package  SocialAds
 *
 * @since    3.1
 */
class SaCreditsHelper
{
	/**
	 * Reduces the credits
	 *
	 * @param   string   $adid       Ad ID
	 * @param   integer  $caltype    call type caltype= 0 imprs; caltype =1 clks;
	 * @param   integer  $ad_charge  ad charge
	 * @param   string   $widget     widget
	 *
	 * @return  string
	 *
	 * @since  1.0
	 **/
	public static function reduceCredits($adid, $caltype, $ad_charge, $widget = "")
	{
		$saParams = JComponentHelper::getParams('com_socialads');

		global $mainframe;
		$mainframe = JFactory::getApplication();
		$db        = JFactory::getDbo();
		$user      = JFactory::getUser();
		$userid    = $user->id;

		/* Load language file for plugin frontend*/
		$lang = JFactory::getLanguage();
		$lang->load('com_socialads', JPATH_SITE);

		// ^ ad_creator => created_by
		$sql = "SELECT ad_payment_type, ad_alternative, ad_affiliate, ad_noexpiry, created_by
		 FROM #__ad_data
		 WHERE ad_id='" . $adid . "'";
		$db->setQuery($sql);

		list($result, $alter, $affiliate, $unltd, $creator) = $db->loadRow();

		$sql = "SELECT count(*)
		 FROM #__ad_stats
		 WHERE ad_id='" . $adid . "'
		 AND ip_address = '" . $_SERVER["REMOTE_ADDR"] . "'
		 AND display_type = " . $caltype . "
		 AND time > NOW()-INTERVAL ";

		// Include the time interval for clicks if cal is for the clicks ad...
		if ($caltype == 1)
		{
			$sql .= $saParams->get('interval_clicks') . " SECOND";
		}
		else
		{
			$sql .= $saParams->get('interval_impressions') . " SECOND";
		}

		$db->setQuery($sql);
		$ipresult = $db->loadResult();

		// $adRetriever = new adRetriever();
		if ($caltype == 0 && $result == 1)
		{
			$ad_charge = 0.00;
		}

		if ($saParams->get('payment_mode') == 'wallet_mode')
		{
			if ($ipresult < 1)
			{
				if ($creator != $userid)
				{
					// Reduce credits for impressions
					if ($result == 0 && $alter == 0 && $affiliate == 0 && $unltd == 0 && $caltype == 0)
					{
						self::spentUpdate($adid, $caltype, $ad_charge);
					}
					// Reduce credits for clicks & it is called from the redirector file
					elseif ($result == 1 && $alter == 0 && $affiliate == 0 && $unltd == 0 && $caltype == 1)
					{
						self::spentUpdate($adid, $caltype, $ad_charge);
					}

					if ($alter == 1 || $unltd == 1 || $affiliate == 1)
					{
						SaStatsHelper::putStats($adid, $caltype, $ad_charge, $widget);

						// For Task #31607 increment ad stats in independent column against the ad
						SaStatsHelper::incrementStats($adid, $caltype);
					}
					else
					{
						SaStatsHelper::putStats($adid, $caltype, $ad_charge, $widget);

						// For Task #31607 increment ad stats in independent column against the ad
						SaStatsHelper::incrementStats($adid, $caltype);
					}
				}
			}

			/*
			$query = "SELECT camp_id,ad_creator FROM #__ad_data WHERE ad_id=$adid";
			$db->setQuery($query);
			$campinfo = $db->loadobjectlist();
			$ad_creator = $campinfo['0']->ad_creator;
			*/

			$query = "SELECT SUM(earn)
			 FROM `#__ad_wallet_transc`
			 WHERE user_id=" . $creator;
			$db->setQuery($query);
			$total_amt = $db->loadresult();

			$query = "SELECT balance
			 FROM `#__ad_wallet_transc`
			 WHERE time = (select MAX(time) FROM #__ad_wallet_transc WHERE user_id = " . $creator . ")";
			$db->setQuery($query);
			$remaining_amt = $db->loadresult();

			if ($alter == 0 && $affiliate == 0 && $unltd == 0 && (($caltype == 0 && $result == 0) || ($caltype == 1 && $result == 1)))
			{
				if ($saParams->get('threshold'))
				{
					$low_val = $total_amt * ($saParams->get('threshold') / 100 );

					if ((ceil($low_val)) == $remaining_amt)
					{
						// Self::mailLowBal($adid, $saParams->get('ad_pay_mode'));
						self::mailLowBal($adid, $saParams->get('payment_mode'));
					}

					if ($remaining_amt <= 0)
					{
						// Foreach($campinfo as $key)
						// {

							// As amount is zero camp should be unpublished
							$query = "UPDATE #__ad_campaign
							 SET state = 0
							 WHERE created_by=" . $creator;
							$db->setQuery($query);
							$db->execute();

						// }

						// Send ad expiry mail
						// Self::mailExpir($adid, $saParams->get('ad_pay_mode'));
						self::mailExpir($adid, $saParams->get('payment_mode'));
					}
				}
			}
		}
		else
		{
			if ($ipresult < 1)
			{
				/*$query = "SELECT ad.ad_credits_balance, api.ad_credits_qty
				 FROM #__ad_data as ad
				 LEFT JOIN #__ad_payment_info as api ON ad.ad_id = api.ad_id
				 WHERE ad.ad_id='" . $adid . "'
				 AND api.status='C'
				 ORDER BY api.mdate DESC
				 LIMIT 1";
				 */

				// @TODO - manoj, dj chk once
				$query = "SELECT ad.ad_credits_balance, api.ad_credits_qty
				 FROM #__ad_data as ad
				 LEFT JOIN #__ad_payment_info as api ON ad.ad_id = api.ad_id
				 LEFT JOIN #__ad_orders as ao ON ao.payment_info_id = api.order_id
				 WHERE ad.ad_id='" . $adid . "'
				 AND ao.status='C'
				 ORDER BY ao.mdate DESC
				 LIMIT 1";
				$db->setQuery($query);

				// Get the balance credits and credits brought
				$credits_data = $db->loadObjectList();

				if ($creator != $userid)
				{
					// Reduce credits for impressions
					if ($result == 0 && $alter == 0 && $affiliate == 0 && $unltd == 0 && $caltype == 0)
					{
						self::subCredits($adid);
					}

					// Reduce credits for clicks & it is called from the redirector file
					elseif ($result == 1 && $alter == 0 && $affiliate == 0 && $unltd == 0 && $caltype == 1)
					{
						self::subCredits($adid);
					}

					if ($alter == 0 && $affiliate == 0 && $unltd == 0 && (($caltype == 0 && $result == 0) || ($caltype == 1 && $result == 1)))
					{
						// @TODO - chk once - changed by manoj ^v3.1
						if ($saParams->get('threshold') && isset($credits_data[0]->ad_credits_qty) && $credits_data[0]->ad_credits_qty)
						{
							$low_val = $credits_data[0]->ad_credits_qty * ($saParams->get('threshold') / 100);

							if ((ceil($low_val)) == ($credits_data[0]->ad_credits_balance - 1))
							{
								// Send a Low Balance mail
								self::mailLowBal($adid, $saParams->get('payment_mode'));
							}

							if (($credits_data[0]->ad_credits_balance - 1) == 0)
							{
								// Send a ad expiry mail
								self::mailExpir($adid, $saParams->get('payment_mode'));
							}
						}
					}

					// Update the stats table for the ad
					SaStatsHelper::putStats($adid, $caltype, $ad_charge, $widget);

					// For Task #31607 increment ad stats in independent column against the ad
					SaStatsHelper::incrementStats($adid, $caltype);
				}
			}
		}
	}

	/**
	 * Function to reduce credits
	 *
	 * @param   integer  $adid  [description]
	 *
	 * @return  void
	 *
	 * @since  3.1
	 **/
	public static function subCredits($adid)
	{
		$db  = JFactory::getDbo();
		$sql = "UPDATE #__ad_data
		SET ad_credits_balance = ad_credits_balance-1
		WHERE ad_id='" . $adid . "'
		AND ad_credits_balance>0";
		$db->setQuery($sql);
		$db->execute();

		return;
	}

	/**
	 * Method to send update
	 *
	 * @param   integer  $adid       [description]
	 * @param   integer  $caltype    [description]
	 * @param   integer  $ad_charge  [description]
	 *
	 * @return  void
	 *
	 * @since  3.1
	 **/
	public static function spentUpdate($adid, $caltype, $ad_charge)
	{
		$db = JFactory::getDbo();
		$query = "SELECT a.camp_id,a.ad_zone,s.per_imp,a.created_by, s.per_click
				FROM `#__ad_data` as a INNER JOIN #__ad_zone as s ON s.id = a.ad_zone WHERE ad_id = $adid";
		$db->setQuery($query);
		$camp_zone = $db->loadobjectlist();

		foreach ($camp_zone as $key)
		{
			$date1 = microtime(true);
			$key->c_date = $date1;
			$date2 = date('Y-m-d');
			$key->only_date = $date2;
			$query = "SELECT id FROM #__ad_wallet_transc WHERE DATE(FROM_UNIXTIME(time)) = '" . $key->only_date . "' AND type_id ="
			. $key->camp_id . " AND type = 'C'";
			$db->setQuery($query);
			$check = $db->loadresult();

			$query = "SELECT balance FROM #__ad_wallet_transc WHERE time = (SELECT MAX(time)  FROM #__ad_wallet_transc WHERE user_id="
			. $key->created_by . ")";
			$db->setQuery($query);
			$bal = $db->loadresult();

			if ($check)
			{
				$query = "UPDATE #__ad_wallet_transc SET time ='" . $key->c_date . "', spent = spent +"
						. $ad_charge . ",balance = " . $bal . " - " . $ad_charge . " where id=" . $check;
				$db->setQuery($query);
				$db->execute();
			}
			else
			{
				$query = "INSERT INTO #__ad_wallet_transc
						VALUES ('','" . $key->c_date . "'," . $key->created_by . "," . $ad_charge . ",''," . $bal . " - " .
						$ad_charge . ", 'C' ," . $key->camp_id . ",'DAILY_CLICK_IMP')";
				$db->setQuery($query);
				$db->execute();
			}
		}

		return;
	}

	/**
	 * Send a Low Balance mail
	 *
	 * @param   integer  $adid  [description]
	 * @param   integer  $mode  [description]
	 *
	 * @return  void
	 *
	 * @since  3.1
	 **/
	public static function mailLowBal($adid, $mode)
	{
		global $mainframe;
		$mainframe = JFactory::getApplication();
		$db = JFactory::getDbo();

		if ($mode == 'pay_per_ad_mode')
		{
			$subject = JText::_('SUB_LOWBAL');
			$body    = JText::_('BALANCERL');
		}
		else
		{
			$subject = JText::_('COM_SOCIALADS_LOW_WALBAL_SUBJ');
			$body    = JText::_('COM_SOCIALADS_LOW_WALBAL_BODY');
		}

		$db->setQuery("SELECT a.created_by, a.ad_title, a.ad_url2, u.name, u.email
				FROM #__ad_data AS a, #__users AS u
				WHERE a.ad_id=" . $adid . " AND a.created_by=u.id");
		$result = $db->loadObject();
		$body = str_replace('[SEND_TO_NAME]', $result->name, $body);

		if ($mode == "pay_per_ad_mode")
		{
			$ad_title = ($result->ad_title != '') ? JText::_("PERIDIC_STATS_ADTIT") . ' <b>"' .
			$result->ad_title . '"</b>' : JText::_("PERIDIC_STATS_ADID") . ' : <b>' . $adid . '</b>';
			$body = str_replace('[ADTITLE]', $ad_title, $body);
		}

		$sitename = $mainframe->getCfg('sitename');
		$body     = str_replace('[SITENAME]', $sitename, $body);
		$body     = str_replace('[SITE]', JUri::base(), $body);
		$from = $mainframe->getCfg('mailfrom');
		$fromname = $mainframe->getCfg('fromname');
		$recipient[] = $result->email;
		$body = nl2br($body);
		$mode = "wallet_mode";
		$cc = null;
		$bcc = null;
		$bcc = null;
		$attachment = null;
		$replyto = null;
		$replytoname = null;

		JFactory::getMailer()->sendMail($from, $fromname, $recipient, $subject, $body, $mode, $cc, $bcc, $attachment, $replyto, $replytoname);
	}

	/**
	 * Send a ad expiry mail
	 *
	 * @param   integer  $adid  [description]
	 * @param   integer  $mode  [description]
	 *
	 * @return  void
	 *
	 * @since  3.1
	 **/
	public static function mailExpir($adid, $mode)
	{
		global $mainframe;
		$mainframe = JFactory::getApplication();
		$socialadshelper = new socialadshelper;

		if ($mode == "pay_per_ad_mode")
		{
			$itemid = $socialadshelper->getSocialadsItemid('adform');
		}

		$db = JFactory::getDbo();
		$sql = "UPDATE #__ad_data
				SET state = 0
				WHERE ad_id='" . $adid . "'
				AND ad_alternative <> 1
				AND ad_noexpiry <> 1";
		$db->setQuery($sql);
		$db->execute();

		if ($mode == 'pay_per_ad_mode')
		{
			$body    = JText::_('EXPIRED');
			$subject = JText::_('SUB_EXPR');
		}
		else
		{
			$subject = JText::_('COM_SOCIALADS_WALEXPRI_SUBJ');
			$body    = JText::_('COM_SOCIALADS_WALEXPRI_BODY');
		}

		$query = "SELECT a.created_by, a.ad_title, a.ad_url2, u.name, u.email
				FROM #__ad_data AS a, #__users AS u
				WHERE a.ad_id=" . $adid . "
				AND a.created_by=u.id";
		$db->setQuery($query);
		$result	= $db->loadObject();

		$body = str_replace('[SEND_TO_NAME]', $result->name, $body);

		if ($mode == "pay_per_ad_mode")
		{
			$ad_title = ($result->ad_title != '') ? JText::_("PERIDIC_STATS_ADTIT") . ' <b>"' .
						$result->ad_title . '"</b>' : JText::_("PERIDIC_STATS_ADID") . ' : <b>' . $adid . '</b>';
			$body = str_replace('[ADTITLE]', $ad_title, $body);
		}

		$sitename = $mainframe->getCfg('sitename');
		$body = str_replace('[SITENAME]', $sitename, $body);
		$body = str_replace('[SITE]', JUri::base(), $body);

		if ($mode == "pay_per_ad_mode")
		{
			$edit_ad_link  = JRoute::_(JUri::base() . "index.php?option=com_socialads&view=adform&adid=" . $adid . "&Itemid=" . $itemid);
			$body	= str_replace('[EDITLINK]', $edit_ad_link, $body);
		}

		$from = $mainframe->getCfg('mailfrom');
		$fromname = $mainframe->getCfg('fromname');

		$recipient[] = $result->email;

		$body = nl2br($body);
		$mode = "wallet_mode";
		$cc = null;
		$bcc = null;
		$bcc = null;
		$attachment = null;
		$replyto = null;
		$replytoname = null;

		JFactory::getMailer()->sendMail($from, $fromname, $recipient, $subject, $body, $mode, $cc, $bcc, $attachment, $replyto, $replytoname);
	}

	/**
	 * Check remaining amount ...if > than charge required for ad show ..return true.
	 *
	 * @param   integer  $adid       [description]
	 * @param   integer  $bid_price  [description]
	 *
	 * @return  boolean
	 *
	 * @since  3.1
	 **/
	public static function checkBalance($adid, $bid_price)
	{
		$spent = 0;
		$db    = JFactory::getDBO();
		$date1 = date('Y-m-d');
		$status = 0;

			// $query = "SELECT a.ad_creator, a.camp_id, c.daily_budget
			// INNER JOIN #__ad_campaign as c ON c.camp_id = a.camp_id
			$query = "SELECT a.created_by, a.camp_id, c.daily_budget
			 FROM #__ad_data as a
			 INNER JOIN #__ad_campaign as c ON c.id = a.camp_id
			 WHERE ad_id = " . $adid;
			$db->setQuery($query);
			$info = $db->loadobject();

			if (!empty($info))
			{
				// $ad_creator   = $info->ad_creator;
				$ad_creator   = $info->created_by;
				$camp_id      = $info->camp_id;
				$daily_budget = $info->daily_budget;

				// FROM `#__ad_wallet_transac`
				$query = "SELECT balance
				 FROM `#__ad_wallet_transc`
				 where time = (
					select MAX(time)
					from #__ad_wallet_transc
					where user_id =" . $ad_creator . "
				)";
				$db->setQuery($query);
				$remaining_amt = $db->loadresult();

				// FROM `#__ad_wallet_transac`
				$query = "SELECT spent
				 FROM `#__ad_wallet_transc`
				 where DATE(FROM_UNIXTIME(time)) ='" . $date1 . "'
				 AND type_id = " . $camp_id . "
				 AND type='C'";
				$db->setQuery($query);
				$spent = $db->loadresult();

				if (((($bid_price) - $remaining_amt) <= 0) && ((($spent + $bid_price) - $daily_budget) <= 0))
				{
					$status = 1;
				}
			}

		return $status;
	}
}
