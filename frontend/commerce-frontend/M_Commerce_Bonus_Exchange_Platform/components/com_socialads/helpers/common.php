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

// Component Helper
jimport('joomla.application.component.helper');

/**
 * Helper class
 *
 * @since  1.6
 */
abstract class SaCommonHelper
{
	/**
	 * function to get an ItemId
	 *
	 * @param   string  $view  eg. managead&layout=list
	 *
	 * @return  itemid
	 *
	 * @since 1.6
	 **/
	public static function getSocialadsItemid($view='')
	{
		global $mainframe;
		$mainframe = JFactory::getApplication();
		$input = JFactory::getApplication()->input;

		if ($view && !($mainframe->isAdmin()))
		{
			$JSite = new JSite;
			$menu = $JSite->getMenu();
			$items = $menu->getItems('link', "index.php?option=com_socialads&view=$view");

			if (isset($items[0]))
			{
				$itemid = $items[0]->id;
			}
		}
		else
		{
			$db = JFactory::getDbo();
			$query = "SELECT id FROM #__menu WHERE link LIKE '%index.php?option=com_socialads";

			$query .= '&view=' . $view;
			$query .= "%' AND published = 1 LIMIT 1";
			$db->setQuery($query);
			$itemid = $db->loadResult();
		}

		if (!isset($itemid))
		{
			$itemid = $input->get('Itemid', 0, 'INT');
		}

		return $itemid;
	}

	/**
	 * For payment of coformed orders
	 *
	 * @param   integer  $order_id  Order id of of order
	 *
	 * @return  void
	 *
	 * @since 1.6
	 **/
	public static function new_pay_mail($order_id)
	{
		if (empty($order_id) || $order_id <= 0 )
		{
			return;
		}

		$mainframe = JFactory::getApplication();
		require_once JPATH_SITE . DS . "components" . DS . "com_socialads" . DS . "helper.php";

		// Require when we call from backend
		$SocialadsFrontendhelper = new SocialadsFrontendhelper;
		$db = JFactory::getDbo();
		$query = "SELECT p.payee_id,u.username, u.email,p.status FROM #__ad_orders as p, #__users as u
							WHERE p.payee_id=u.id
							AND p.id=" . $order_id;

		$db->setQuery($query);
		$result	= $db->loadObject();
		$body = JText::_('COM_SOCIALADS_INVOICE_PAY_PAYMENT_BODY');
		$find = array ('[SEND_TO_NAME]', '[ORDERID]', '[SITENAME]', '[STATUS]');

			if ($result->status == 'P')
			{
				$orderstatus = JText::_('ADS_INVOICE_STATUS_PENDING');
			}
			elseif ($result->status == 'RF')
			{
				$orderstatus = JText::_('COM_SOCIALADS_AD_REFUND');
			}
			else
			{
				$orderstatus = JText::_('ADS_INVOICE_AMOUNT_CANCELLED');
			}

		$recipient = $result->email;
		$siteName = $mainframe->getCfg('sitename');
		$displayOrderid = sprintf("%05s", $order_id);
		$replace = array($result->username, $displayOrderid, $siteName, $orderstatus);
		$body = str_replace($find, $replace, $body);
		$subject = JText::sprintf("COM_SOCIALADS_STATUS_CHANGED_MAIL_SUBJECT", $displayOrderid);

		$status  = self::sendmail($recipient, $subject, $body, '', 0, "");
	}

	/**
	 * General send mail function
	 *
	 * @param   string   $recipient       recipient of mail
	 * @param   string   $subject         subject of mail
	 * @param   string   $body            body of mail
	 * @param   string   $bcc_string      bcc_string of mail
	 * @param   integer  $singlemail      singlemail of mail
	 * @param   string   $attachmentPath  attachmentPath of mail
	 *
	 * @return  email
	 *
	 * @since 1.6
	 **/
	public static function sendmail($recipient,$subject,$body,$bcc_string,$singlemail=1,$attachmentPath="")
	{
		jimport('joomla.utilities.utility');
		global $mainframe;
		$mainframe = JFactory::getApplication();
			$from = $mainframe->getCfg('mailfrom');
			$fromname = $mainframe->getCfg('fromname');
			$recipient = trim($recipient);
			$mode = 1;
			$cc = null;
			$bcc = array();

			if ($singlemail == 1)
			{
				if ($bcc_string)
				{
					$bcc = explode(',', $bcc_string);
				}
				else
				{
					$bcc = array('0' => $mainframe->getCfg('mailfrom') );
				}
			}

			$attachment = null;

			if (!empty($attachmentPath))
			{
				$attachment = $attachmentPath;
			}

			$replyto = null;
			$replytoname = null;

		return	JFactory::getMailer()->sendMail($from, $fromname, $recipient, $subject, $body, $mode, $cc, $bcc, $attachment, $replyto, $replytoname);
	}

	/**
	 * checks for view override
	 *
	 * @param   string  $viewname       name of view
	 * @param   string  $layout         layout name eg order
	 * @param   string  $searchTmpPath  it may be admin or site. it is side(admin/site) where to search override view
	 * @param   string  $useViewpath    it may be admin or site. it is side(admin/site) which VIEW shuld be use IF OVERRIDE IS NOT FOUND
	 *
	 * @return  if exit override view then return path
	 *
	 * @since  1.6
	 **/
	public static function getViewpath($viewname, $layout="", $searchTmpPath='SITE', $useViewpath='SITE')
	{
		$searchTmpPath = ($searchTmpPath == 'SITE')?JPATH_SITE:JPATH_ADMINISTRATOR;
		$useViewpath = ($useViewpath == 'SITE')?JPATH_SITE:JPATH_ADMINISTRATOR;
		$app = JFactory::getApplication();

		if (!empty($layout))
		{
			$layoutname = $layout . '.php';
		}
		else
		{
			$layoutname = "default.php";
		}

		$override = $searchTmpPath . DS . 'templates' . DS . $app->getTemplate() . DS . 'html' . DS . 'com_socialads' . DS . $viewname . DS . $layoutname;

		if (JFile::exists($override))
		{
			return $view = $override;
		}
		else
		{
			return $view = $useViewpath . DS . 'components' . DS . 'com_socialads' . DS . 'views' . DS . $viewname . DS . 'tmpl' . DS . $layoutname;
		}
	}
	// End of getViewpath()

	/**
	 * Function to get ad info
	 *
	 * @param   integer  $adid    ad id of a ad
	 * @param   string   $adinfo  ad information
	 *
	 * @return  array
	 *
	 * @since  1.6
	 */
	public static function getAdInfo($adid = 0, $adinfo = '*')
	{
		// Get a db connection.
		$db = JFactory::getDbo();

		// Create a new query object.
		$query = $db->getQuery(true);

		// Select all records from the user profile table where key begins with "custom.".
		// Order it by the ordering field.
		$query->select($adinfo);
		$query->from('#__ad_data');
		$query->where('ad_id =' . (int) $adid);

		$db->setQuery($query);
		$details = $db->loadobjectlist();

		return $details;
	}

	/**
	 * Function to get param table names
	 *
	 * @param   string  $tablename  table name
	 *
	 * @return  Boolean
	 *
	 * @since  1.6
	 */
	public static function getTableColumns($tablename)
	{
		$db       = JFactory::getDBO();
		$app      = JFactory::getApplication();
		$dbprefix = $app->getCfg('dbprefix');

		// Use of $dbprefix is important here
		$query = "SHOW TABLES LIKE '" . $dbprefix . $tablename . "'";
		$db->setQuery($query);
		$isTableExist = $db->loadResult();

		$paramlist = array();

		if ($isTableExist)
		{
			$query_to_get_column = "SHOW COLUMNS FROM #__" . $tablename;
			$db->setQuery($query_to_get_column);
			$paramlist = $db->loadColumn();
		}

		return $paramlist;
	}

	/**
	 * This will load any javascript only once
	 *
	 * @param   string  $script  script name
	 *
	 * @return  Boolean
	 *
	 * @since  1.6
	 */
	public static function loadScriptOnce($script)
	{
		$doc = JFactory::getDocument();
		$flg = 0;

		foreach ($doc->_scripts as $name => $ar)
		{
			if ($name == $script)
			{
				$flg = 1;
			}
		}

		if ($flg == 0)
		{
			$doc->addScript($script);
		}
	}

	/**
	 * Function to check social integration is install or not
	 *
	 * @return  Boolean
	 *
	 * @since  1.6
	 */
	public static function checkForSocialIntegration()
	{
		$params = JComponentHelper::getParams('com_socialads');

		$integration = $params->get('social_integration');

		if ($integration == 'Community Builder')
		{
			$integration = "CB";
		}
		elseif($integration == 'JomSocial')
		{
			$integration = "JS";
		}
		elseif($integration == 'EasySocial')
		{
			$integration = "ES";
		}

		switch ($integration)
		{
			case 'CB':
				$cbpath = JPATH_ROOT . DS . 'components' . DS . 'com_comprofiler';

				if (JFolder::exists($cbpath))
				{
					return 1;
				}
				else
				{
					return '';
				}
				break;

			case 'JS':
				$jspath = JPATH_ROOT . DS . 'components' . DS . 'com_community';

				if (file_exists($jspath))
				{
					return 1;
				}
				else
				{
					return '';
				}

			break;

			case 'ES':
				$jspath = JPATH_ROOT . DS . 'components' . DS . 'com_easysocial';

				if (file_exists($jspath))
				{
					return 1;
				}
				else
				{
					return '';
				}
			break;
		}
	}

	/**
	 * Returns months to current month from last year
	 *
	 * @return  list of months
	 *
	 * @since   2.2
	 */
	public static function getAllmonths()
	{
		$date2 = date('Y-m-d');

		// Lets time travel, back to previous year, same month, same day!
		$date  = strtotime($date2 . ' -1 year');
		$date1 = date('Y-m-d', $date);

		// Convert dates to UNIX timestamp
		$time1 = strtotime($date1);
		$time2 = strtotime($date2);
		$tmp   = date('mY', $time2);

		// ** Line below results into fetching 13 months instead of 12
		// $months[] = array("month" => date('M', $time1), "digitmonth" => date('m', $time1),"amount" => 0);

		while ($time1 < $time2)
		{
			$time1 = strtotime(date('Y-m-d', $time1) . ' +1 month');

			if (date('mY', $time1) != $tmp && ($time1 < $time2))
			{
				$months[] = array("month" => date('M', $time1),
					"digitmonth" => date('m', $time1),
					"amount" => 0
				);
			}
		}

		$months[] = array("month" => date('M', $time2),"digitmonth" => date('m', $time2),"amount" => 0);

		return $months;
	}

	/**
	 * Get all jtext for javascript
	 *
	 * @return   void
	 *
	 * @since   1.0
	 */
	public static function getLanguageConstant()
	{
		// For number valiation
		JText::script('COM_SOCIALADS_PAYMENT_MIN_AMT_SHOULD_GREATER_MSG');
		JText::script('COM_SOCIALAD_PAYMENT_ENTER_NUMERICS');
		JText::script('COM_SOCIALADS_PAYMENT_ENTER_CORRECT_AMT');
		JText::script('COM_SOCIALAD_PAYMENT_ENTER_COUPON_CODE');
		JText::script('COM_SOCIALAD_PAYMENT_COUPON_CODE_IN_PERCENT');
		JText::script('COM_SOCIALADS_PAYMENT_COUPON_NOT_EXISTS');
		JText::script('COM_SOCIALADS_SUBMIT');
		JText::script('COM_SOCIALADS_PAYMENT_ENTER_CORRECT_AMT');
		JText::script('COM_SOCIALAD_PAYMENT_GATEWAY_LOADING_MSG');

		// Create ad
		JText::script('COM_SOCIALADS_ERR_MSG_FILE_BIG_JS');
		JText::script('COM_SOCIALADS_ERR_MSG_FILE_ALLOW');
		JText::script('COM_SOCIALADS_SELECT_CAMPAIGN');
		JText::script('COM_SOCIALADS_SOCIAL_ESTIMATED_REACH_HEAD');
		JText::script('COM_SOCIALADS_SOCIAL_ESTIMATED_REACH_END');
		JText::script('COM_SOCIALADS_CANCEL_AD');
		JText::script('COM_SOCIALADS_URL_VALID');
		JText::script('COM_SOCIALADS_TITLE_VALID');
		JText::script('COM_SOCIALADS_BODY_VALID');
		JText::script('COM_SOCIALADS_MEDIA_VALID');
		JText::script('COM_SOCIALADS_RATE_PER_CLICK');
		JText::script('COM_SOCIALADS_RATE_PER_IMP');
		JText::script('COM_SOCIALADS_ENTER_COP_COD');
		JText::script('COM_SOCIALADS_COP_EXISTS');
		JText::script('SA_RENEW_RECURR');
		JText::script('SA_RENEW_NO_RECURR');
		JText::script('COM_SOCIALADS_AD_CHARGE_TOTAL_DAYS_FOR_RENEWAL');
		JText::script('TOTAL');
		JText::script('POINTS_AVAILABLE');
		JText::script('POINT');
		JText::script('COM_SOCIALADS_TOTAL_SHOULDBE_VALID_VALUE');
		JText::script('COM_SOCIALADS_AD_NUMBER_OF');
		JText::script('COM_SOCIALADS_AD_SELECT_CAMPAIGN');
		JText::script('COM_SOCIALADS_AD_ENTER_CAMPAIGN');
		JText::script('COM_SOCIALADS_AD_ALLOWED_BUDGET');

		// Ads
		JText::script('COM_SOCIALADS_SA_MAKE_SEL');
		JText::script('COM_SOCIALADS_DELETE_AD');

		// Campaigns
		JText::script('COM_SOCIALADS_CAMPAIGNS_DELETE_CONFIRM');
		JText::script('COM_SOCIALADS_DELETE_MESSAGE');
		JText::script('COM_SOCIALADS_AD_PRICING_OPTION');

		// Wallet
		JText::script('COM_SOCIALADS_WALLET_COUPON_ADDED_SUCCESS');
	}

	/**
	 * Get currency symbol
	 *
	 * @param   string  $currency  Currency
	 *
	 * @return  currency symbol
	 *
	 * @since       1.7
	 */
	public static function getCurrencySymbol($currency = '')
	{
		$params   = JComponentHelper::getParams('com_socialads');
		$currencySymbol = $params->get('currency_symbol');

		if (empty($currencySymbol))
		{
			$currencySymbol = $params->get('currency');
		}

		return $currencySymbol;
	}

	/**
	 * Push to activity stream
	 *
	 * @param   float   $price  Amount
	 * @param   string  $curr   Currency
	 *
	 * @return formatted price-currency string
	 *
	 * @since       1.7
	 */
	public static function getFormattedPrice($price, $curr = null)
	{
		$price                      = number_format($price, 2);
		$currencySymbol             = self::getCurrencySymbol();
		$params                     = JComponentHelper::getParams('com_socialads');
		$currency                   = $params->get('currency');
		$currencyDisplayFormat    = $params->get('currency_display_format');
		$currencyDisplayFormatStr = '';
		$currencyDisplayFormatStr = str_replace('{AMOUNT}', "&nbsp;" . $price, $currencyDisplayFormat);
		$currencyDisplayFormatStr = str_replace('{CURRENCY_SYMBOL}', "&nbsp;" . $currencySymbol, $currencyDisplayFormatStr);
		$currencyDisplayFormatStr = str_replace('{CURRENCY}', "&nbsp;" . $currency, $currencyDisplayFormatStr);
		$html                       = '';
		$html                       = "<span>" . $currencyDisplayFormatStr . " </span>";

		return $html;
	}

	/**
	 * Get sites/administrator default template
	 *
	 * @param   mixed  $client  0 for site and 1 for admin template
	 *
	 * @return  json
	 *
	 * @since   1.5
	 */
	public static function getSiteDefaultTemplate($client = 0)
	{
		try
		{
			$db    = JFactory::getDBO();

			// Get current status for Unset previous template from being default
			// For front end => client_id=0
			$query = $db->getQuery(true)
						->select('template')
						->from($db->quoteName('#__template_styles'))
						->where('client_id=' . $client)
						->where('home=1');
			$db->setQuery($query);

			return $db->loadResult();
		}
		catch (Exception $e)
		{
			return '';
		}
	}

	/**
	 * Get ad creator name
	 *
	 * @return  array
	 *
	 * @since   1.5
	 */
	public static function getAdCreators()
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		// $query = "SELECT created_by FROM #__ad_data  WHERE state=1 AND ad_approved=1 GROUP BY ad_creator";
		$query->select('created_by');
		$query->from($db->quoteName('#__ad_data'));
		$query->where($db->quoteName('state') . "=1");
		$query->where($db->quoteName('ad_approved') . "=1");
		$db->setQuery($query);
		$adcreators = $db->loadColumn();

		return $adcreators;
	}

	/**
	 * Get sites/administrator default template
	 *
	 * @param   integer  $user_id  User ID
	 *
	 * @return  json
	 *
	 * @since   1.5
	 */
	public static function statsForPieInMail($user_id = '')
	{
		$db = JFactory::getDBO();
		$statsforpie = array();
		$socialads_from_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' - 7 days'));
		$socialads_end_date = date('Y-m-d');
		$where = '';
		$groupby = '';

		$query_data = "SELECT ad_id  FROM #__ad_data WHERE created_by = $user_id ";
		$db->setQuery($query_data);
		$adids = $db->loadColumn();
		$total_no_ads = count($adids);
		$cnt = 0;

		foreach ($adids as $adid)
		{
			$where = " AND DATE(time) BETWEEN DATE('" . $socialads_from_date . "') AND DATE('" . $socialads_end_date . "') AND ad_id=" . $adid;
			$arch_where = " AND DATE(date) BETWEEN DATE('" . $socialads_from_date . "') AND DATE('" . $socialads_end_date . "')";
			$groupby = "  GROUP BY DATE(time)";
			$query = " SELECT COUNT(id) as value,DAY(time) as day,MONTH(time) as month
					FROM #__ad_stats
					WHERE display_type = 0  " . $where;
			$db->setQuery($query);

			// Impression
			$statsforpie[$cnt][0] = $db->loadObjectList();

			// Query for archive
			$query = " SELECT SUM(impression) as value,DAY(date) as day,MONTH(date) as month
						FROM #__ad_archive_stats
						WHERE  impression<>0 AND ad_id = " . $adid . $arch_where;
			$db->setQuery($query);
			$acrh_imp_statistics = $db->loadObjectList();

			if (isset($acrh_imp_statistics[0]->value) && $acrh_imp_statistics[0]->value)
			{
				$statsforpie[$cnt][0][0]->value += $acrh_imp_statistics[0]->value;
			}

			// EOC for archive*/

			$query = "SELECT COUNT(id) as value,DAY(time) as day,MONTH(time) as month
				FROM #__ad_stats
				WHERE display_type = 1 " . $where;
			$db->setQuery($query);

			// Clicks
			$statsforpie[$cnt][1] = $db->loadObjectList();

			// Query for archive
			$query = " SELECT SUM(click) as value,DAY(date) as day,MONTH(date) as month,YEAR(date) as year
						FROM #__ad_archive_stats
						WHERE  click<>0 AND ad_id = " . $adid . $arch_where;
			$db->setQuery($query);
			$acrh_clk_statistics = $db->loadObjectList();

			if (isset($acrh_clk_statistics[0]->value) && $acrh_clk_statistics[0]->value)
			{
				$statsforpie[$cnt][1][0]->value += $acrh_clk_statistics[0]->value;
			}

			// Eoc for archive*/

			$statsforpie[$cnt][2] = $adid;

			$cnt++;
		}

		return $statsforpie;
	}

	/**
	 * Get user details
	 *
	 * @param   integer  $userid   User ID
	 * @param   array    $details  Details required
	 *
	 * @return  json
	 *
	 * @since   1.5
	 */
	public static function getUserDetails($userid,$details)
	{
		$db = JFactory::getDBO();
		$query = 'SELECT ' . $details
		. ' FROM #__users'
		. ' WHERE '
		. '  id=' . $userid;
		$db->setQuery($query);
		$info = $db->loadObjectList();

		return $info;
	}
}
