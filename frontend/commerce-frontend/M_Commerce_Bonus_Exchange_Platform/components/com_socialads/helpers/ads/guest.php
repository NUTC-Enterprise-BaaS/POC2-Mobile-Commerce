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
 * Ads helper class for Guest ads
 *
 * @package  SocialAds
 * @since    3.1
 */
class SaAdsHelperGuest extends SaAdsHelper
{
	/**
	 * Fetch target data for guest ads [non targetted ads]
	 *
	 * @param   array   $params  SocialAds module parameters
	 * @param   string  $adType  Ad type - e.g. Alt
	 *
	 * @return  void
	 */
	public static function getAdTargetData($params, $adType, $engineType = 'local')
	{
		return;
	}

	/**
	 * Fetch alternative guest ads [non targetted ads] based on guest ads data collected
	 *
	 * @param   array   $params  SocialAds module parameters
	 * @param   array   $data    Ad target data
	 * @param   string  $adType  Ad type - e.g. Alt
	 *
	 * @return  array  Array of ad ids
	 */
	public static function getAds($params, $data, $adType = '')
	{
		$saParams = JComponentHelper::getParams('com_socialads');

		$db            = JFactory::getDbo();
		$camp_join     = SaAdEngineHelper::getQueryJoinCampaigns();
		$function_name = "guest";
		$common_where  = SaAdEngineHelper::getQueryWhereCommon($params, $function_name);
		$common_where  = implode(' AND ', $common_where);

		$query = "SELECT a.ad_id
		 FROM #__ad_data as a " .
		$camp_join . "
		 WHERE a.ad_guest = 1
		 AND " . $common_where;

		if ($saParams->get('geo_targeting'))
		{
			$query .= " AND a.ad_id NOT IN (SELECT ad_id
			 FROM #__ad_geo_target
			 ) ";
		}

		$query .= " ORDER by a.ad_created_date ";

		$db->setQuery($query);
		$result = $db->loadObjectList();

		return $result;
	}
}
