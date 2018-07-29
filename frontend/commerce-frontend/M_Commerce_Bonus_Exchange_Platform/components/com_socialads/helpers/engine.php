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

jimport('joomla.filesystem.file');

/**
 * Helper class for ads preview.
 *
 * @since  1.6
 */
class SaAdEngineHelper
{
	static $staticvar = array();

	static $ad_entry_number = 1;

	static $_resultads = array();

	public static $_my = null;

	public static $_fromemail = null;

	// Will show debug for geo
	public static $_geodebug = 0;

	// Will show debug for geo
	public static $_contextdebug = 0;

	public static $_contextmainquery = '';

	public static $_contextquery = '';

	public static $_params = '';

	/**
	 *
	 * @var SaAdEngineHelper
	 */
	private static $instance;

	public static $_engineType = '';

	/**
	 * Constructor
	 *
	 * @param   string   $engineType  Engine type
	 * @param   integer  $userid      User id
	 * @param   integer  $extra       Extra param
	 *
	 * @since  3.1
	 */
	public function __construct($engineType = 'local', $userid = 0, $extra = 0)
	{
		/*self::$_my = ($user == 0) ? (JFactory::getUser()) : (JFactory::getUser($user));*/

		if ($userid == 0)
		{
			self::$_my = JFactory::getUser();
		}
		elseif ($userid == -1)
		{
			self::$_my->id = 0;
		}
		else
		{
			self::$_my = JFactory::getUser($userid);
		}

		self::$_fromemail = $extra;

		self::$_params = JComponentHelper::getParams('com_socialads');

		self::$_engineType = $engineType;

		// Load all required classes
		$saInitClassPath = JPATH_SITE . '/components/com_socialads/init.php';

		if (!class_exists('SaInit'))
		{
			JLoader::register('SaInit', $saInitClassPath);
			JLoader::load('SaInit');
		}

		// Define autoload function
		spl_autoload_register('SaInit::autoLoadHelpers');
	}

	/**
	 * Get Instance
	 *
	 * @param   STRING  $engineType  Engine Type
	 * @param   INT     $userid      User Id
	 * @param   INT     $extra       Extra param
	 *
	 * @return  Object  SaAdEngineHelper object
	 */
	public static function getInstance($engineType = 'local', $userid = 0, $extra = 0)
	{
		if (is_null(self::$instance))
		{
			self::$instance = new self($engineType, $userid, $extra);
		}

		return self::$instance;
	}

	/*function getnumberofAds($params,$module_id,$this)*/
	/**
	 * Fetch matching ads for specified zone
	 *
	 * @param   array    $params    Current module paramas
	 * @param   integer  $moduleId  Module Id
	 *
	 * @return  array
	 */
	public static function getAdsForZone($params, $moduleId)
	{
		$session = JFactory::getSession();

		// $this = new adRetriever();

		/*$number = self::getParam($params,'num_ads');
		$zone_id=  self::getParam($params,'zone');
		self::getMatchAds($params,$this);*/

		$number  = self::getParam($params, 'num_ads');
		$zone_id = self::getParam($params, 'zone');

		// NOTE - removed function getMatchAds and added its code here
		/*self::getMatchAds($params);*/

		$debug = self::getParam($params, 'debug');

		if ($debug == 1)
		{
			self::$_geodebug     = 1;
			self::$_contextdebug = 1;
		}

		// $ads = self::fillslots($params, $this);
		$ads = self::fillSlots($params);

		$i = 1;
		$ret_ads = array();
		$temp    = array();

		/*self::checkIfAdspresent();*/

		$temp = self::$_resultads;
		$session->set('SA_resultads', $temp);
		$resultads_session_ads = array();
		$resultads_session_ads = $session->get('SA_resultads', array());

		foreach ($resultads_session_ads[$zone_id] as $key => $ad)
		{
			if (!empty($ad))
			{
				/*self::$ad_array[$ad_id]= array();*/

				if (empty($ad->seen))
				{
					// $statue_adcharge = self::getad_status($ad->ad_id);
					$statue_adcharge = self::getAdStatus($ad->ad_id);

					// Reduce the credits
					$resultads_session_ads[$zone_id][$key]->impression_done	= 0;

					if ($statue_adcharge['status_ads'] == 1)
					{
						$resultads_session_ads[$zone_id][$key]->seen = $moduleId;

						// @TODO - manoj, dj - this server side impression count, needs to be initiated via client side
						// Count impressions, 0 - is for impressions

						/*self::reduceCredits($ad->ad_id, 0, $statue_adcharge['ad_charge'], $moduleId);*/
						SaCreditsHelper::reduceCredits($ad->ad_id, 0, $statue_adcharge['ad_charge'], $moduleId);
						$resultads_session_ads[$zone_id][$key]->impression_done = 1;

						$i++;
						$ret_ads[] = $ad;
					}
					else
					{
						unset($resultads_session_ads[$zone_id][$key]);
					}

					if ($i > $number)
					{
						break;
					}
				}
				else
				{
					continue;
				}
			}
		}

		if (self::$_geodebug == '1')
		{
			echo '<br><br><b>Total static ads in getnumberofAds b4 seess </b>';
			print_r($resultads_session_ads);
		}

		$session->set('SA_resultads', $resultads_session_ads);

		if (self::$_geodebug == '1')
		{
			echo '</pre>';
		}

		return $ret_ads;
	}

	/**
	 * Get params
	 * This is needed for Ads in Email
	 * This returns accesses plugin param either from config or from SA email data tag
	 *
	 * @param   object  $params      Component paramters
	 * @param   string  $paramindex  Component param option
	 *
	 * @return  mixed
	 */
	public static function getParam($params, $paramindex)
	{
		if (self::$_fromemail == 0)
		{
			/*return $params->get($paramindex, 1);*/

			// ^ Manoj v3.1
			if (is_object($params))
			{
				return $params->get($paramindex, 1);
			}
			else
			{
				return $params[$paramindex];
			}
		}
		else
		{
			return $params[$paramindex];
		}
	}

	/*function fillslots($params,$this)*/
	//
	/**
	 * FillSlots @TODO - this function is useless, this code can be moved inside getMatchAds if not called from anywhere else
	 *
	 * @param   object  $params  Module paramters
	 *
	 * @return  array
	 */
	public static function fillSlots($params)
	{
		// $resultads = self::getpriorityAds($params, $this);

		$resultAds = self::getAdsByPriority($params);

		if (self::$_geodebug == '1')
		{
			echo '<br><br><b>Total static ads variable </b><pre>';
		}

		// Do not change the return type since it is used by jma_socialads plugin
		return $resultAds;
	}

	/*function getpriorityAds($params,$this)*/
	/**
	 * Get Ads By Priority
	 *
	 * @param   object  $params  Module paramters
	 *
	 * @return  [type]           [description]
	 */
	public static function getAdsByPriority($params)
	{
		$zone_id = self::getParam($params, 'zone');
		$remain  = self::getParam($params, 'num_ads');

		if (empty(self::$_resultads[$zone_id]))
		{
			self::$_resultads[$zone_id] = array();
		}

		$ad_rotation = self::getParam($params, 'ad_rotation');
		$displayPriority = self::$_params->get('display_priority');

		if ($ad_rotation == 1)
		{
			// @TODO logic to increase total number of ads for that module.
			$remain *= 6;
		}

		if (self::$_geodebug == '1')
		{
			echo '<br>Start debug => ';
		}

		// If display_priority not set, randomize it

		if (!($displayPriority))
		{
			$func_list = array('Context', 'Social', 'Geo');
			shuffle($func_list);
		}
		// If display_priority is set, convert it into readable names in array format
		// These readable names are used later as a prefix for common function names
		else
		{
			$i = 0;

			foreach (self::$_params->get('priority') as $key => $value)
			{
				if ($value == 0)
				{
					$valuestr = 'Social';
				}
				elseif ($value == 1)
				{
					$valuestr = 'Geo';
				}
				elseif ($value == 2)
				{
					$valuestr = 'Context';
				}

				$func_list[$i] = $valuestr;
				$i++;
			}
		}

		// @TODO - may be move these in component options, let users decide this?
		$func_list[] = 'Guest';
		$func_list[] = 'Affiliate';
		$func_list[] = 'Alt';

		/*// For rewrite - comment later @TODO - Manoj
		$func_list = array();
		$func_list[] = 'Geo';
		$func_list[] = 'Context';
		$func_list[] = 'Social';
		$func_list[] = 'Alt';
		$func_list[] = 'Guest';
		$func_list[] = 'Affiliate';*/

		$session = JFactory::getSession();

		foreach ($func_list as $func)
		{
			if ($remain != 0 || $remain == '')
			{
				$ads = array();

				// $func_name = 'get' . $func . 'Ads';

				if (self::$_geodebug == '1')
				{
					echo '<br><br><b>' . $func . ' Ads::</b> ';
				}

				// $data_func_name = 'get' . $func . 'Data';
				$func_data      = array();
				$ads            = $session_ads1 = $session_ads = array();

				if (!($func == 'Guest' || $func == 'Affiliate' || $func == 'Alt'))
				{
					if ($func != 'Context')
					{
						$data = $session->get($func . ' Data', array());

						if (!empty($data))
						{
							$func_data = $data;

							if (self::$_geodebug == '1')
							{
								echo '<br><br>user data from session for ' . $func;
							}
						}
						else
						{
							// $func_data = self::$data_func_name($params, $this);
							$func_data = SaAdsHelper::getAdTargetData($params, $func, self::$_engineType);
							$session->set($func . 'Data', $func_data);
						}
					}
					else
					{
						// $func_data = self::$data_func_name($params, $this);
						$func_data = SaAdsHelper::getAdTargetData($params, $func, self::$_engineType);
					}

					/*echo "ADS DATA FETCHED - "; print_r($func_data);*/

					if (self::$_params->get('enable_caching') == 1 && isset($_COOKIE[$func . '_Ads1']))
					{
						$cooke_ads = json_decode($_COOKIE[$func . '_Ads1'], true);
					}
					else
					{
						$cooke_ads[$zone_id] = array();
					}

					if (self::$_params->get('enable_caching') == 1)
					{
						setcookie($func . '_Ads1', '', -time());
						setcookie($func . '_Ads1', json_encode($cooke_ads), time() + self::$_params->get('cache_time'));
					}

					// REMOVE AD FROM COOKIE ID AD NOT PRESENT
					if (isset($cooke_ads[$zone_id]) && !empty($cooke_ads[$zone_id]))
					{
						foreach ($cooke_ads[$zone_id] as $key => $cookieAd)
						{
							// Get a db connection.
							$db = JFactory::getDbo();

							// Create a new query object.
							$query = $db->getQuery(true);

							// Select all records from the user profile table where key begins with "custom.".
							// Order it by the ordering field.
							$query->select($db->quoteName(array('ad_id')));
							$query->from($db->quoteName('#__ad_data'));
							$query->where($db->quoteName('ad_id') . ' = ' . $db->quote($cookieAd['ad_id']));

							// Reset the query using our newly populated query object.
							$db->setQuery($query);

							// Load the results as a list of stdClass objects (see later for more options on retrieving data).
							$results = $db->loadresult();

							if (empty($results))
							{
								unset($cooke_ads[$zone_id][$key]);
							}
						}
					}
					/*echo " geo data "; print_r($func_data); die;*/

					if (!empty($func_data))
					{
						if ($func != 'Context')
						{
							if (empty($cooke_ads[$zone_id]))
							{
								// $ads = self::$func_name($func_data,$params,$this);

								// $ads = self::$func_name($func_data, $params);
								$ads = SaAdsHelper::getAds($params, $func_data, $func);
							}
						}
						else
						{
							// $ads = self::$func_name($func_data,$params,$this);

							// $ads = self::$func_name($func_data, $params);
							$ads = SaAdsHelper::getAds($params, $func_data, $func);
						}
					}
				}
				else
				{
					if (self::$_params->get('enable_caching') == 1 && isset($_COOKIE[$func . '_Ads1']))
					{
						$cooke_ads = json_decode($_COOKIE[$func . '_Ads1'], true);
					}
					else
					{
						$cooke_ads[$zone_id] = array();
					}

					// REMOVE AD FROM COOKIE ID AD NOT PRESENT
					if (isset($cooke_ads[$zone_id]) && !empty($cooke_ads[$zone_id]))
					{
						foreach ($cooke_ads[$zone_id] as $key => $cookieAd)
						{
							// Get a db connection.
							$db = JFactory::getDbo();

							// Create a new query object.
							$query = $db->getQuery(true);

							// Select all records from the user profile table where key begins with "custom.".
							// Order it by the ordering field.
							$query->select($db->quoteName(array('ad_id')));
							$query->from($db->quoteName('#__ad_data'));
							$query->where($db->quoteName('ad_id') . ' = ' . $db->quote($cookieAd['ad_id']));

							// Reset the query using our newly populated query object.
							$db->setQuery($query);

							// Load the results as a list of stdClass objects (see later for more options on retrieving data).
							$results = $db->loadresult();

							if (empty($results))
							{
								unset($cooke_ads[$zone_id][$key]);
							}
						}
					}

					if (self::$_params->get('enable_caching') == 1)
					{
						setcookie($func . '_Ads1', '', -time());
						setcookie($func . '_Ads1', json_encode($cooke_ads), time() + self::$_params->get('cache_time'));
					}

					if (empty($cooke_ads[$zone_id]))
					{
						/*$ads = self::$func_name($params,$this);
						$ads = self::$func_name($func_data, $params);
						$ads = self::$func_name($params);*/

						// @TODO @manoj check params passsed with DJ
						$ads = SaAdsHelper::getAds($params, $func_data, $func);
					}
				}

				if ($func != 'Context')
				{
					if (empty($cooke_ads[$zone_id]))
					{
						if (self::$_geodebug == '1')
						{
							echo '<br><br>ads from func for ' . $func;
							print_r($ads);
						}

						$oldCookieAds = new stdclass;

						if (self::$_params->get('enable_caching') == 1 && isset($_COOKIE[$func . '_Ads1']))
						{
							$oldCookieAds = json_decode($_COOKIE[$func . '_Ads1']);
						}

						$oldCookieAds->$zone_id = $ads;

						if (self::$_params->get('enable_caching') == 1)
						{
							setcookie($func . '_Ads1', json_encode($oldCookieAds), time() + 3600);

							$_COOKIE[$func . '_Ads1'] = json_encode($oldCookieAds);
						}
					}

					if (self::$_params->get('enable_caching') == 1)
					{
						$session_ads_all = json_decode($_COOKIE[$func . '_Ads1'], true);
					}
					else
					{
						$session_ads_all = json_decode((json_encode($oldCookieAds)));
					}

					if (!empty($session_ads_all))
					{
						$session_ads = json_decode((json_encode($session_ads_all)));
					}
					else
					{
						$session_ads = array();
					}

					if (self::$_geodebug == '1')
					{
						echo '<br><br>ads from session for ' . $func;
						print_r($session_ads);
					}
				}
				else
				{
					$session_ads = new stdclass;
					$session_ads->$zone_id  = $ads;
				}

				/*print_r($session_ads); die;*/

				if (!empty($session_ads))
				{
					foreach ($session_ads as $zone_id => $value)
					{
						$adsArray = new stdclass;
						$adsArray->$zone_id = $value;

						/*$remain_num = self::pushinSlot($value,$remain,$params, $zone_id);*/
						$remain_num = self::pushinSlot($zone_id, $params, $value, $remain+1);

						if ($remain_num != -999)
						{
							$remain = $remain_num;
						}
					}
				}
			}
		}

		return self::$_resultads;
	}

	/*function pushinSlot($ads, $no_of_ads='', $params, $this, $zone_id)*/
	/**
	 * Push the ads in the ads slots according to the num of ads limit & if the slot is full return
	 *
	 * @param   integer  $zone_id    Zone id
	 * @param   array    $params     Module params
	 * @param   array    $ads        Fetched ads
	 * @param   string   $no_of_ads  No. of ads
	 *
	 * @return  integer
	 */
	public static function pushInSlot($zone_id, $params, $ads, $no_of_ads = '')
	{
		/*$zone_id =  self::getParam($params,'zone');*/

		if (empty(self::$_resultads[$zone_id]))
		{
			self::$_resultads[$zone_id] = array();
		}

		// Push the ads in the ads slots according to the num of ads limit & if the slot is full return
		if (count(self::$_resultads[$zone_id]))
		{
			// @TODO changed this code during v3.1 rewrite -
			// Changed from

			/*$ad_ids = '';

			if (is_array($ads))
			{
				$ad_ids = array_udiff($ads, self::$_resultads[$zone_id], array('self', 'compareIds'));
			}*/

			// Changed to
			$ad_ids = array_udiff($ads, self::$_resultads[$zone_id], array('self', 'compareIds'));

			// Change ends

			// If there are no ads to show
			if (!($ad_ids))
			{
				return -999;
			}
		}
		else
		{
			$ad_ids = $ads;
		}

		// Randomise the ads at every step
		if (self::getParam($params, 'no_rand') == 1)
		{
			shuffle($ad_ids);
		}

		$ad_ids = array_slice($ad_ids, 0, $no_of_ads);

		if (self::$_geodebug == '1')
		{
			echo '<br><b> Pushed Ads: </b>';
			print_r($ad_ids);
		}

		foreach ($ad_ids as $ad1)
		{
			// $ad_ids[0];
			self::$_resultads[$zone_id][] = $ad1;
		}

		if (count($ad_ids) < $no_of_ads)
		{
			return ($no_of_ads - count($ad_ids));
		}

		return 0;
	}

	/*function join_camp()*/
	/**
	 * Get common query string if campaigns mode is on
	 *
	 * @return  string
	 */
	public static function getQueryJoinCampaigns()
	{
		$join = '';

		// If campaign mode is set
		// if (self::$_params->get('select_campaign') == 1)
		if (self::$_params->get('payment_mode') == 'wallet_mode')
		{
			// $join = " INNER JOIN #__ad_campaign as c ON c.camp_id = a.camp_id  ";
			$join = " INNER JOIN #__ad_campaign as c ON c.id = a.camp_id  ";
		}

		return $join;
	}

	/*function query_common($params,$function_name,$this)*/
	/**
	 * Get common query string for fetching ads
	 *
	 * @param   array   $params         Module params
	 * @param   string  $function_name  Targetting function name gor e.g. Geo
	 *
	 * @return  [type]                  [description]
	 */
	public static function getQueryWhereCommon($params, $function_name)
	{
		// @TODO:- flag for(ignore ad,no ads function call)(
		$zone = self::getParam($params, 'zone');

		$date          = date('Y-m-d');
		$no_ads_ids    = self::getShownAds($zone);
		$no_ads        = " a.ad_id NOT IN (" . $no_ads_ids . ") ";
		$comon_query[] = " a.ad_zone =" . $zone;

		// Wallet mode
		// if (self::$_params->get('ad_pay_mode') == 1 && $function_name != "alt" && $function_name != "affiliate")
		if (self::$_params->get('payment_mode') == 'wallet_mode' && $function_name != "alt" && $function_name != "affiliate")
		{
			// $comon_query[] = " c.camp_published = 1";
			$comon_query[] = " c.state = 1";
		}
		// Pay per ad mode
		else
		{
			if ($function_name == "adids" || $function_name == "guest" || $function_name == "contextual")
			{
				$comon_query[] = "
				 (
					a.ad_credits_balance>0  OR a.ad_noexpiry=1 OR
					(
						a.ad_payment_type=2 AND
						(
							a.ad_enddate <>'0000-00-00' AND a.ad_startdate <= CURDATE() AND a.ad_enddate > CURDATE()
						)
					)
				 )";
			}
		}

		// $comon_query[] = " a.ad_published = 1";
		$comon_query[] = " a.state = 1";
		$comon_query[] = " a.ad_approved = 1";
		$comon_query[] = " a.ad_id NOT IN (SELECT adid FROM #__ad_ignore WHERE userid =  " . self::$_my->id . ")";

		if ($no_ads_ids)
		{
			$comon_query[] = $no_ads;
		}

		// For showing user itz own ad
		if (self::$_fromemail == 1)
		{
			$owner_ad = self::getParam($params, 'owner_ad');
		}
		else
		{
			$owner_ad = self::$_params->get('advertisers_ads');
		}

		// Show ad creator his own ads
		if ($owner_ad == 0)
		{
			if ($function_name != "alt")
			{
				// $comon_query[] = " a.ad_creator <> " . self::$_my->id;
				$comon_query[] = " a.created_by <> " . self::$_my->id;
			}
		}

		return $comon_query;
	}

	/*function get_shownAds($zone_id)*/
	/**
	 * Get shown ads
	 *
	 * @param   integer  $zone_id  Zone id
	 *
	 * @return  string  Comma separated list of ad ids
	 */
	public static function getShownAds($zone_id)
	{
		$str = '';

		if (!empty($_resultads))
		{
			if (count(self::$_resultads[$zone_id]))
			{
				// Exclude the already shown ads
				$str = array();

				foreach (self::$_resultads[$zone_id] as $val)
				{
					$str[] = $val->ad_id;
				}

				$str = implode(',', $str);
			}
		}
		return $str;
	}

	/*function getad_status($adid)*/
	/**
	 * Get status for an ad which satisfy all condition
	 *
	 * @param   [type]  $adid  [description]
	 *
	 * @return  [type]         [description]
	 */
	public static function getAdStatus($adid)
	{
		if (!$adid)
		{
			return false;
		}

		$db = JFactory::getDbo();

		/* // @TODDO - dipti - Not needed... code done for Moses
		$query = "SELECT bid_value FROM #__ad_data WHERE ad_id =" . $adid;
		$db->setQuery($query);
		$bid = $db->loadresult();
		*/

		$query = "SELECT ad_noexpiry, ad_alternative, ad_affiliate
		 FROM #__ad_data
		 WHERE ad_id =" . $adid;
		$db->setQuery($query);
		$ad_alt_exp = $db->loadObject();

		if ($ad_alt_exp->ad_noexpiry == 1  || $ad_alt_exp->ad_alternative == 1 || $ad_alt_exp->ad_affiliate == 1)
		{
			$statue_adcharge               = array();
			$statue_adcharge['ad_charge']  = 0.00;
			$statue_adcharge['status_ads'] = 1;

			return $statue_adcharge;
		}

		$query = "SELECT ad_payment_type
		 FROM #__ad_data
		 WHERE ad_id =" . $adid;
		$db->setQuery($query);
		$caltype = $db->loadresult();

		/* // Not needed... code done for Moses
		if (!empty($bid))
		{
			$bid_value = self::sendbidvalue($adid);
			$ad_charge = $bid_value['price'];
		}
		else*/
		{
			/*$ad_charge = self::getad_charges($adid,$caltype);*/
			$ad_charge = self::getAdCharges($adid, $caltype);
		}

		if (self::$_params->get('payment_mode') == 'wallet_mode' && $ad_alt_exp->ad_noexpiry == 0  && $ad_alt_exp->ad_alternative == 0 && $ad_alt_exp->ad_affiliate == 0)
		{
			// $status_ads = self::check_balance($adid, $ad_charge);

			$status_ads = SaCreditsHelper::checkBalance($adid, $ad_charge);
		}
		else
		{
			$status_ads = 1;
		}

		$statue_adcharge                = array();
		$statue_adcharge['ad_charge']   = $ad_charge;
		$statue_adcharge['status_ads']  = $status_ads;

		return $statue_adcharge;
	}

	/*function getad_charges($adid,$caltype)*/
	/**
	 * Get charges for add to show
	 *
	 * @param   [type]  $adid     [description]
	 * @param   [type]  $caltype  [description]
	 *
	 * @return  [type]            [description]
	 */
	public static function getAdCharges($adid, $caltype)
	{
		if (self::$_params->get('zone_pricing') == 1)
		{
			$db = JFactory::getDbo();

			// $query = "SELECT a.camp_id, a.ad_zone, s.per_imp, a.ad_creator, s.per_click
			$query = "SELECT a.camp_id, a.ad_zone, s.per_imp, a.created_by, s.per_click
			 FROM `#__ad_data` as a
			 INNER JOIN #__ad_zone as s ON s.id = a.ad_zone
			 WHERE ad_id = " . $adid;
			$db->setQuery($query);
			$camp_zone = $db->loadobjectlist();

			foreach ($camp_zone as $key)
			{
				if ($key->ad_zone)
				{
					if ($caltype == 0)
					{
						$modify_price = " $key->per_imp ";
					}
					else
					{
						$modify_price = " $key->per_click ";
					}
				}
			}
		}
		else
		{
			if ($caltype == 0)
			{
				$modify_price = self::$_params->get('per_impressions');
			}
			else
			{
				$modify_price = self::$_params->get('per_clicks');
			}
		}

		return $modify_price;
	}

	/**
	 * Get details of the ad
	 *
	 * @param   integer  $ad  Ad id
	 *
	 * @return  object
	 */
	public static function getAdDetails($ad)
	{
		$addata = array();

		if (!empty($ad) && isset($ad->ad_id) && !empty($ad->ad_id))
		{
			$db = JFactory::getDbo();

			$query = "SELECT ad.ad_id, ad.ad_title, ad.ad_image, ad.ad_body, ad.layout, az.id as zone_name, az.ad_type
			 FROM #__ad_data as ad
			 LEFT JOIN #__ad_zone as az ON ad.ad_zone = az.id
			 WHERE ad_id =" . $ad->ad_id;

			/**
			 * Jugad for not showing flash/video Ads in mails
			 * @TODO remove when add HTML5 support
			 */
			if (self::$_fromemail == 1)
			{
				$query .= " AND (ad.ad_image NOT LIKE '%.flv' AND ad.ad_image NOT LIKE '%.swf' AND ad.ad_image NOT LIKE '%.mp4' )";
			}

			$db->setQuery($query);
			$addata = $db->loadObject();
		}

		return $addata;
	}

	/**
	 * Method to get HTML preview of ad
	 *
	 * @param   String  $addata      Ad data
	 *
	 * @param   String  $adseen      Ad shown earlier?
	 *
	 * @param   String  $adrotation  Ad body text
	 *
	 * @param   String  $widget      link to ad
	 *
	 * @return  ad html
	 *
	 * @since   1.6
	 *
	 */
	public static function getAdHtml($addata, $adseen = 0, $adrotation = 0, $widget = '')
	{
		/*jimport('joomla.application.module.helper');*/
		require_once JPATH_SITE . '/components/com_socialads/defines.php';

		global $mainframe;
		$mainframe = JFactory::getApplication();
		$db = JFactory::getDbo();
		$document = JFactory::getDocument();

		$zone_data = '';

		if ($adseen == 1)
		{
			if ($addata == 0)
			{
				$buildadsession = JFactory::getSession();
				$adses          = $buildadsession->get('ad_data');
				$addata         = new stdClass;
				$pluginlist = $buildadsession->get('addatapluginlist');

				// Extra code for zone pricing
				$addata->layout   = $buildadsession->get('layout');
				$addata->ad_image = str_replace(JUri::base(), '', $buildadsession->get('upimg'));

				if (!($buildadsession->get('adzone')))
				{
					$adzone = 1;
				}
				else
				{
					$adzone = $buildadsession->get('adzone');
				}

				$addata->ad_title = $adses[2]['ad_title'];
				$addata->ad_body  = $adses[3]['ad_body'];

				$query = "SELECT orientation, max_title, max_des, img_width, img_height
				 FROM #__ad_zone
				 WHERE id =" . $adzone;

				$db->setQuery($query);
				$zone_data = $db->loadObjectList();
			}
			else
			{
				// Ad preview for the lightbox, showad , adsummary view
				$query = "SELECT *
				 FROM #__ad_data
				 WHERE ad_id =" . $addata;
				$db->setQuery($query);
				$addata = $db->loadObject();
			}

			$addata->link = '#';
		}

		if ($zone_data == '' )
		{
			$query = "SELECT az.id AS zone_id,az.orientation,az.max_title,
				az.max_des,az.img_width,az.img_height FROM #__ad_data as ad LEFT JOIN #__ad_zone as az ON ad.ad_zone=az.id WHERE ad_id =" . $addata->ad_id;

			$db->setQuery($query);
			$zone_data = $db->loadObjectList();

			// Added in 2.7.5 stable
			$adzone = $zone_data[0]->zone_id;
		}

		$tit              = $addata->ad_title;
		$addata->ad_title = mb_substr($tit, 0, $zone_data[0]->max_title, 'UTF-8');

		if ($addata->layout != 'layout6')
		{
			$bod             = $addata->ad_body;
			$addata->ad_body = mb_substr($bod, 0, $zone_data[0]->max_des, 'UTF-8');
		}

		$addata->ignore = "";
		$upload_area = '';

		if ($zone_data[0]->orientation == 1 && $adseen == 0)
		{
			// For the orientation of the Ad ie Horizontal or Vertical
		}

		// $style = 'style="' . $float_style . '"';

		$saad_entry_number = 0;

		if ($adrotation	== 1)
		{
			$saad_entry_number = self::$ad_entry_number;
			++self::$ad_entry_number;
		}

		$mod_sfx = "";

		/* $addata = self::getAdDetails($ad_id);*/
		$module = JModuleHelper::getModule('mod_socialads');

		/* if (($mainframe->isSite()) && !empty($addata))*/
		if ($mainframe->isSite() && $adseen == 1 && !empty($addata))
		{
			$moduleParams = json_decode($module->params);

			if (isset($moduleParams->moduleclass_sfx))
			{
				$mod_sfx      = $moduleParams->moduleclass_sfx;
			}
		}

		$class = "";

		if ($addata->layout == "layout6")
		{
			$class = "affiliate_div_style";
		}

		@$html = '<div class = "ad_prev_main' . $mod_sfx . ' ' . $class . '" preview_for= "' .

		$addata->ad_id . '" ad_entry_number="' . $saad_entry_number . '">';

		if ($adseen == 0)
		{
			if (self::$_my->id == 1)
			{
				// $widget = '';

				if (!empty($_SERVER['HTTP_REFERER']))
				{
					$parse = parse_url($_SERVER['HTTP_REFERER']);

					if ($widget != "")
					{
						$widget = '&widget=' . $parse['host'] . '|' . $widget;
					}
				}
			}
			else
			{
				$widget = '&widget=' . $widget;
			}

			$addata->link = JUri::root() . substr(
				JRoute::_(
					'index.php?option=com_socialads&task=track.redirect&id=' .
					$addata->ad_id . "&caltype=1" . $widget, false
				), strlen(JUri::base(true)) + 1
			);

			// Show ignore button
			if (self::$_params->get('ignore_ads') != 0 && self::$_my->id != 0 && self::$_fromemail == 0)
			{
				if (self::$_my->id != 1)
				{
					$addata->ignore = "saRender.ignore(this," . $addata->ad_id . "," . self::$_params->get('feedback_on_ignore') . ");";
				}
			}
		}

		$plugin = 'plug_' . $addata->layout;

		$document->addStyleSheet(JUri::root(true) . '/media/com_sa/css/helper.css');
		$document->addScript(JUri::root(true) . '/media/com_sa/js/render.js');

		// $adRetriever = new adRetriever();

		// START changed by manoj 2.7.5b2
		// No passing zone id all time changed in 2.7.5 stable

		if (!$adseen)
		{
			$adHtmlTyped = self::getAdHtmlByMedia(
				$upload_area,
				$addata->ad_image,
				$addata->ad_body,
				$addata->link,
				$addata->layout,
				$adzone,
				$track = 1,
				$addata->ad_id
			);
		}
		else
		{
			$adHtmlTyped = self::getAdHtmlByMedia($upload_area, $addata->ad_image, $addata->ad_body, $addata->link, $addata->layout, $adzone, $track = 0);
		}

		// END changed by manoj 2.7.5b2

		$layout = JPATH_SITE . '/plugins/socialadslayout/' . $plugin . '/' . $plugin . '/layout.php';

		if (JFile::exists($layout))
		{
			$document->addStyleSheet(
				JUri::root(true) . '/plugins/socialadslayout/' . $plugin . '/' .
				$plugin . '/layout.css', 'text/css', '', array("id" => $addata->layout . 'css')
			);

			ob_start();
				include $layout;
				$html .= ob_get_contents();
			ob_end_clean();
		}
		else
		{
			/*Ad title starts here...*/
			$html .= '<!--div for preview ad-title-->
					<div class="ad_prev_first">';

			if ($adseen == 0)
			{
				$html .= '<a class="ad_prev_anchor" href="' . $addata->link . '" target="_blank">' . $addata->ad_title . '</a>';
			}
			else
			{
				$html .= $addata->ad_title;
			}

			$html .= '</div>';
			/*Ad title ends here*/

			/*Ad image starts here...*/
			// Check it image exists
			if ($addata->ad_image != '')
			{
				$html .= '<!--div for preview ad-image-->
						<div class="ad_prev_second">';

				if ($adseen == 0)
				{
					$html .= '<a href="' . $addata->link . ' " target="_blank">';
				}

				$html .= '<img class="ad_prev_img"  src="' . JUri::root() . $addata->ad_image . '" border="0" />';

				if ($adseen == 0)
				{
					$html .= '</a>';
				}

				$html .= '</div>';
			}
			/*Ad image ends here*/

			/*Ad description starts here...*/
			$html .= '<!--div for preview ad-descrip-->
					<div class="ad_prev_third">' . $addata->ad_body . '</div>';
			/*Ad description ends here*/
		}

		$html .= '</div>';

		return $html;
	}

	/**
	 * Method to get HTML according to media type
	 *
	 * @param   String   $upload_area  area in which image should be uploaded
	 *
	 * @param   String   $ad_image     image for ad
	 *
	 * @param   String   $ad_body      ad body text
	 *
	 * @param   String   $ad_link      link to ad
	 *
	 * @param   integer  $ad_layout    position where the ad should display
	 *
	 * @param   integer  $adzone       ad zone
	 *
	 * @param   integer  $track        track
	 *
	 * @param   integer  $ad_id        ad id
	 *
	 * @return  ad html
	 *
	 * @since 1.6
	 */
	public static function getAdHtmlByMedia($upload_area, $ad_image, $ad_body, $ad_link, $ad_layout, $adzone, $track = 0, $ad_id = '')
	{
		$obj = new stdclass;
		$obj->ad_image = $ad_image;
		$obj->ad_link = $ad_link;
		$obj->ad_layout = $ad_layout;
		$obj->ad_body = $ad_body;
		$obj->ad_layout = $ad_layout;
		$obj->adzone = $adzone;
		$obj->track = $track;
		$obj->ad_id = $ad_id;

		$adHtmlTyped = '';

		// Layout2 or layout4 are for text ads & layout6 is for affiliate ads
		if ($ad_layout == 'layout2' || $ad_layout == 'layout4' || $ad_layout == 'layout6')
		{
			$ad_type = 'text';
			$adHtmlTyped .= $ad_body;
		}
		else
		{
			require_once JPATH_SITE . '/components/com_socialads/helpers/media.php';
			$media           = new sa_mediaHelper;
			$fpath           = JUri::root() . $ad_image;
			$fextension      = JFile::getExt($fpath);
			$ad_type         = $media->get_ad_type($fextension);

			/*
			@TODO use image/zone dimensions here?
			$media_d=$media->check_media_resizing_needed($zone_d,$fpath);
			$opti_d=$media->get_new_dimensions($zone_d->img_width, $zone_d->img_height, 'auto');
			*/
			switch ($ad_type)
			{
				case "image":
					$saLayout = new JLayoutFile('adhtml_image', $rootPath = JPATH_ROOT . '/layouts/adhtml');

					$adHtmlTyped = '';
					$adHtmlTyped = $saLayout->render($obj);
				break;

				case "flash":
					$zone_d = $media->get_adzone_media_dimensions($adzone);
					$obj->zone_d = $zone_d;

					$saLayout = new JLayoutFile('adhtml_flash', $rootPath = JPATH_ROOT . '/layouts/adhtml');

					$adHtmlTyped = '';
					$adHtmlTyped = $saLayout->render($obj);
				break;

				case "video":
					$zone_d = $media->get_adzone_media_dimensions($adzone);
					$obj->zone_d = $zone_d;

					$saLayout = new JLayoutFile('adhtml_video', $rootPath = JPATH_ROOT . '/layouts/adhtml');

					$adHtmlTyped = '';
					$adHtmlTyped = $saLayout->render($obj);
				break;
			}
		}

		$adHtmlTypedwithad_type = "<div class='adtype' adtype='" . $ad_type . "'>" . $adHtmlTyped . "</div>";

		return $adHtmlTypedwithad_type;
	}

	/**
	 * Callback function for array_udiff
	 *
	 * @param   array  $a  [description]
	 * @param   array  $b  [description]
	 *
	 * @return  array
	 */
	public static function compareIds($a, $b)
	{
		return ($a->ad_id - $b->ad_id);
	}

	/*function checkIfAdsAvailable($ad_id,$module_id,$zone_id)*/
	/**
	 * Check if ads are available for rotation
	 *
	 * @param   int  $ad_id      Ad id
	 * @param   int  $module_id  Module id
	 * @param   int  $zone_id    Zone id
	 *
	 * @return  mixed
	 */
	public function checkIfAdsAvailable($ad_id, $module_id, $zone_id)
	{
		$session   = JFactory::getSession();
		$resultads = $session->get('SA_resultads');
		$ret       = new stdClass;

		// For getting random ads in ad rotation
		shuffle($resultads[$zone_id]);

		$is_ad = 0;

		foreach ($resultads[$zone_id] as $key => $ad_obj)
		{
			if (empty($ad_obj->seen))
			{
				$is_ad                           =	1;
				$ret                             = $ad_obj;
				$resultads[$zone_id][$key]->seen = $module_id;

				if (empty($ad_obj->impression_done))
				{
					// $status_adcharge = $adRetriever->getad_status($ad_obj->ad_id);
					$status_adcharge = self::getAdStatus($ad_obj->ad_id);

					// Reduce the credits
					if ($status_adcharge['status_ads'] == 1)
					{
						/*$adRetriever->reduceCredits($ad_obj->ad_id, 0, $status_adcharge['ad_charge'], $module_id);*/
						SaCreditsHelper::reduceCredits($ad_obj->ad_id, 0, $status_adcharge['ad_charge'], $module_id);

						$resultads[$zone_id][$key]->impression_done	= 1;
					}
					else
					{
						unset($resultads[$zone_id][$key]);
					}
				}

				break;
			}
		}

		if ($is_ad == 1)
		{
			foreach ($resultads[$zone_id] as $key => $ad_obj)
			{
				if ($ad_obj->ad_id == $ad_id)
				{
					$resultads[$zone_id][$key]->seen = '';
				}
			}

			$session->set('SA_resultads', $resultads);

			if ($ret->ad_id != '')
			{
				return $ret;
			}
		}

		return false;
	}
}
