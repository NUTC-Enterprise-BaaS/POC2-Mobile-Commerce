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
 * Helper for TJ Geo Location class
 *
 * @package  SocialAds
 *
 * @since    3.1
 */
class TJGeoLocationHelperMaxmind extends TJGeoLocationHelper
{
	/**
	 * Returns user's geo location from his/her IP address using Maxmind Legacy database
	 *
	 * @param   string  $ip  IP address
	 *
	 * @return  array
	 *
	 * @since  1.0
	 **/
	public static function getUserLocationFromIP($ip)
	{
		// Refer to https://github.com/maxmind/geoip-api-php
		require_once JPATH_SITE . '/components/com_socialads/classes/geolocation/maxmind/geoipcity.inc';
		require JPATH_SITE . '/components/com_socialads/classes/geolocation/maxmind/geoipregionvars.php';

		$dbfile = JPATH_SITE . '/components/com_socialads/classes/geolocation/maxmind/GeoLiteCity.dat';

		$formatted_data = array();
		$formatted_data['country'] = $formatted_data['region'] = $formatted_data['city'] = '';

		if (!JFile::exists($dbfile))
		{
			return $formatted_data;
		}

		$gi   = geoip_open($dbfile, GEOIP_STANDARD);
		$data = geoip_record_by_addr($gi, $ip);

		if ($data || isset($data))
		{
			if (isset($data->country_name))
			{
				$formatted_data['country'] = $data->country_name;
			}

			if (isset($data->region))
			{
				$formatted_data['region'] = $GEOIP_REGION_NAME[$data->country_code][$data->region];
			}

			if (isset($data->city))
			{
				$formatted_data['city'] = $data->city;
			}
		}

		geoip_close($gi);

		return $formatted_data;
	}
}
