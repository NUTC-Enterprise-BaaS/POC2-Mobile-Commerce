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
 * Ads helper class for Alternate ads
 *
 * @package  SocialAds
 * @since    3.1
 */
class SaAdsHelperAffiliate extends SaAdsHelper
{
	/**
	 * Fetch target data for alternative ads [if no ads present, these ads are used]
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
	 * Fetch alternative ads[if no ads present, these ads are used] based on alternative ads data collected
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

		$db = JFactory::getDbo();

		$function_name = "affiliate";
		$common_where  = SaAdEngineHelper::getQueryWhereCommon($params, $function_name);
		$common_where  = implode(' AND ', $common_where);

		$query = "SELECT a.ad_id
		 FROM #__ad_data as a
		 WHERE a.ad_affiliate = 1
		 AND " . $common_where . "
		 ORDER by a.ad_created_date ";
		$db->setQuery($query);
		$result = $db->loadObjectList();

		return $result;
	}
}
