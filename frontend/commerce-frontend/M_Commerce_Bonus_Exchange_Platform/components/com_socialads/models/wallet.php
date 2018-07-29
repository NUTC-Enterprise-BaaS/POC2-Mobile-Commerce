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
 * Methods supporting a list of Socialads records.
 *
 * @since  1.6
 */
Class SocialadsModelWallet extends JModelLegacy
{
	/**
	 * Get wallet information
	 *
	 * @param   integer  $user_id  User Id of user
	 *
	 * @return  array
	 *
	 * @since   1.6
	 */
	public function getwallet($user_id)
	{
		$mainframe = JFactory::getApplication();
		$input = JFactory::getApplication()->input;
		$option = $input->get('option', '', 'STRING');
		$month = $mainframe->getUserStateFromRequest($option . 'month', 'month', '', 'int');
		$year = $mainframe->getUserStateFromRequest($option . 'year', 'year', '', 'int');

		$whr = '';
		$whr1 = '';

		if ($month && $year)
		{
			$whr = " AND month(cdate) =" . $month . "   AND year(cdate) =" . $year . "  ";
			$whr1 = " AND month(DATE(FROM_UNIXTIME(a.time))) =" . $month . "  AND year(DATE(FROM_UNIXTIME(a.time))) =" . $year . "  ";
		}
		elseif ($month == '' && $year)
		{
			$whr = " AND year(cdate) =" . $year . "  ";
			$whr1 = " AND year(DATE(FROM_UNIXTIME(a.time))) =" . $year . "  ";
		}

		$all_info = array();
		$query = "SELECT DATE(FROM_UNIXTIME(a.time)) as time,a.spent as spent,type_id,a.earn as credits,balance,comment
		FROM #__ad_wallet_transc as a WHERE a.user_id = " . $user_id . " " . $whr1 . " ORDER BY a.time ASC";
		$this->_db->setQuery($query);
		$ad_stat = $this->_db->loadobjectList();
		$camp_name = $coupon_code = $ad_title = array();

		if (!empty($ad_stat))
		{
			foreach ($ad_stat as $key)
			{
				// To get campaign name
				$query = "SELECT campaign FROM #__ad_campaign WHERE id=" . $key->type_id;
				$this->_db->setQuery($query);
				$camp_name[$key->type_id] = $this->_db->loadresult();

				// To get coupon code
				$query = "SELECT coupon FROM #__ad_orders WHERE id=" . $key->type_id;
				$this->_db->setQuery($query);
				$coupon_code[$key->type_id] = $this->_db->loadresult();

				$ad_til = explode('|', $key->comment);

				if (isset($ad_til[1]))
				{
					$query = "SELECT ad_title FROM #__ad_data WHERE ad_id=" . $ad_til[1];
					$this->_db->setQuery($query);
					$ad_title[$ad_til[1]] = $this->_db->loadresult();
				}
			}
		}

		array_push($all_info, $ad_stat, $camp_name, $coupon_code, $ad_title);

		return $all_info;
	}
}
