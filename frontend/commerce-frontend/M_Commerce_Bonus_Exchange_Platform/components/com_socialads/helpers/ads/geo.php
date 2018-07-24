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
 * Ads helper class for Geo ads
 *
 * @package  SocialAds
 * @since    3.1
 */
class SaAdsHelperGeo extends SaAdsHelper
{
	/**
	 * Fetch target data for geo targetting ads
	 *
	 * @param   array   $params      SocialAds module parameters
	 * @param   string  $adType      Ad type - e.g. Geo
	 * @param   string  $engineType  Server to access geo targeting
	 *
	 * @return  array  Geolocation data of loggedin user
	 */
	public static function getAdTargetData($params, $adType, $engineType = 'local')
	{
		$saParams = JComponentHelper::getParams('com_socialads');

		if (SaAdEngineHelper::$_fromemail == 1)
		{
			return;
		}

		if (!($saParams->get('geo_targeting')))
		{
			return array();
		}

		// Get user IP
		// $ip = self::getUserIP();
		$ip = TJGeoLocationHelper::getUserIP();

		// Dev ip hard coded
		// @TODO comment line below later
		if ($ip == '127.0.0.1')
		{
			$ip = $saParams->get('test_ip');
		}

		if (SaAdEngineHelper::$_geodebug == '1')
		{
			echo '<br>IP:: ' . $ip;
		}

		if (!$ip)
		{
			return array();
		}

		// Get user location from IP
		$userLocationData = TJGeoLocationHelper::getUserLocationFromIP($ip);

		if (SaAdEngineHelper::$_geodebug == '1')
		{
			echo '<br>GEO location:: ';
			echo 'formatted====';
			print_r($userLocationData);
		}

		return $userLocationData;
	}

	/**
	 * Fetch geo targetted ads based on geo data collected
	 *
	 * @param   array   $params  SocialAds module parameters
	 * @param   array   $data    Ad target data
	 * @param   string  $adType  Ad type - e.g. Geo
	 *
	 * @return  array  Array of ad ids
	 */
	public static function getAds($params, $data, $adType = '')
	{
		$saParams = JComponentHelper::getParams('com_socialads');

		if (SaAdEngineHelper::$_fromemail == 1)
		{
			return;
		}

		if (!($saParams->get('geo_targeting')))
		{
			return array();
		}

		$userloca = $data;

		if (!$userloca)
		{
			return array();
		}

		$where = array();

		foreach ($userloca as $key => $value)
		{
			// $where[] = "(g.$key LIKE \"%|{$value}|%\" OR g.{$key} = '')";

			$where[] = "(g.$key LIKE \"%|" . $value . "|%\" OR g." . $key . "= '')";
		}

		$where = (count($where) ? ' WHERE ' . implode("\n AND ", $where) : '');
		$debug = "";

		if (SaAdEngineHelper::$_geodebug == '1')
		{
			$debug = " , g.* ";
		}

		$result_ads    = array();
		$function_name = "geo";

		$camp_join     = SaAdEngineHelper::getQueryJoinCampaigns();
		$common_where  = SaAdEngineHelper::getQueryWhereCommon($params, $function_name);
		$common_where  = implode(' AND ', $common_where);

		$db    = JFactory::getDbo();
		$query = "SELECT DISTINCT(g.ad_id) " . $debug . "
		 FROM #__ad_geo_target as g , #__ad_data as a " .
		$camp_join .
		$where . "
		 AND g.ad_id = a.ad_id
		 AND " . $common_where;

		$db->setQuery($query);

		$result_ads = $db->loadObjectList();

		if (SaAdEngineHelper::$_geodebug == '1')
		{
			echo '<br>GEO Ads:: ';
			print_r($result_ads);
		}

		if ($result_ads)
		{
			return $result_ads;
		}
		else
		{
			return array();
		}
	}
}
