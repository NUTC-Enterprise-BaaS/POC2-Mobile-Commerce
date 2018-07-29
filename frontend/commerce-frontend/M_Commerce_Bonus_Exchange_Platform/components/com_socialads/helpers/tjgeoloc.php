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
class TJGeoLocationHelper
{
	/**
	 * Load geo location helper class for given geo-location provider
	 *
	 * @param   string  $geoLocationProvider  Ad type - e.g. maxmind
	 *
	 * @return  string  Class name
	 */
	public static function loadHelperClass($geoLocationProvider)
	{
		// Ad helpers path
		$geoLocationHelpersFolderPath = __DIR__ . '/geolocation';

		// Current file path
		$helperFilePath = $geoLocationHelpersFolderPath . '/' . strtolower(trim($geoLocationProvider)) . '.php';

		// Derive the class name from the type.
		$className = 'TJGeoLocationHelper' . ucfirst(trim($geoLocationProvider));

		// Load class
		if (!class_exists($className))
		{
			JLoader::register($className, $helperFilePath);
			JLoader::load($className);
		}

		if (class_exists($className))
		{
			return $className;
		}
		else
		{
			throw new RuntimeException(sprintf('Unable to load class: %s', $className));

			// JFactory::getApplication()->enqueueMessage(sprintf('Unable to load class: %s in class TJGeoLocationHelper -> method loadHelperClass', $className), 'error');
		}
	}

	/**
	 * Returns user's machine IP address
	 *
	 * @return  string
	 *
	 * @since  1.0
	 **/
	public static function getUserIP()
	{
		if (isset($_SERVER))
		{
			if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
			{
				$ip = $_SERVER["HTTP_X_FORWARDED_FOR"];

				if ($ip != '' && strtolower($ip) != 'unknown')
				{
					$addresses = explode(',', $ip);

					return $addresses[ count($addresses) - 1 ];
				}
			}

			if (isset($_SERVER["HTTP_CLIENT_IP"]) && $_SERVER["HTTP_CLIENT_IP"] != '')
			{
				return $_SERVER["HTTP_CLIENT_IP"];
			}

			return $_SERVER["REMOTE_ADDR"];
		}

		if ($ip = getenv('HTTP_X_FORWARDED_FOR'))
		{
			if (strtolower($ip) != 'unknown')
			{
				$addresses = explode(',', $ip);

				return $addresses[count($addresses) - 1];
			}
		}

		if ($ip = getenv('HTTP_CLIENT_IP'))
		{
			return $ip;
		}

		return getenv('REMOTE_ADDR');
	}

	/**
	 * Returns user's geo location from his/her IP address
	 *
	 * @param   string  $ip  IP address
	 *
	 * @return  array
	 *
	 * @since  1.0
	 **/
	public static function getUserLocationFromIP($ip)
	{
		// @TODO - add config for geo location provder selection
		// For now ue maxmind as hardcoded
		$geoLocationProvider = 'maxmind';

		$className = self::loadHelperClass($geoLocationProvider);

		// If the class doesn't exist, return
		if (!class_exists($className))
		{
			return;
		}

		// Get geo location data from loaded class
		$geoLocationData = $className::getUserLocationFromIP($ip);

		// @TODO - once we start using more than one geo location providers, we need a commmon format for data to be returned from here

		return $geoLocationData;
	}
}
