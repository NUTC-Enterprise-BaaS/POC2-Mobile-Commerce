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
 * @since    3.1
 */
class SaAdsHelper
{
	public static $_adTypesLocal  = array();
	public static $_adTypesRemote = array();

	public function __construct()
	{
		self::$_adTypesLocal  = array('Geo', 'Context', 'Social', 'Alt', 'Guest', 'Affiliate');
		self::$_adTypesRemote = array('Context', 'Social');
	}

	/**
	 * Load ads helper class for given ad type
	 *
	 * @param   string  $adType  Ad type - e.g. Geo
	 *
	 * @return  string  Class name
	 */
	public static function loadHelperClass($adType)
	{
		// Ad helpers path
		$adsHelpersFolderPath = __DIR__ . '/ads';

		// Current file path
		$helperFilePath = $adsHelpersFolderPath . '/' . strtolower(trim($adType)) . '.php';

		// Derive the class name from the type.
		$className = 'SaAdsHelper' . ucfirst(trim($adType));

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

			// JFactory::getApplication()->enqueueMessage(sprintf('Unable to load class: %s in class SaAdsHelper -> method loadHelperClass', $className), 'error');
		}
	}

	/**
	 * Fetch target data for given ad type
	 *
	 * @param   array   $params  SocialAds module parameters
	 * @param   string  $adType  Ad type - e.g. Geo
	 *
	 * @return  array  Target data of loggedin user
	 */
	public static function getAdTargetData($params, $adType, $engineType = 'local')
	{
		$className = self::loadHelperClass($adType);

		// If the class doesn't exist, return
		if (!class_exists($className))
		{
			return;
		}

		// Get local ad target data from loaded class
		if ($engineType == 'local')
		{
			$adTargetData = $className::getAdTargetData($params, $adType, $engineType);
		}
		// Get remote ad target data from loaded class, if current adType is supported for remote ads
		elseif ($engineType == 'remote' && in_array($adType, self::$_adTypesRemote))
		{
			$adTargetData = $className::getAdTargetDataRemote($params, $adType, $engineType);
		}
		// Get local ad target data from loaded class, if current adType is NOT supported for remote ads
		elseif ($engineType == 'remote' && !in_array($adType, self::$_adTypesRemote))
		{
			$adTargetData = $className::getAdTargetData($params, $adType, $engineType);
		}

		return $adTargetData;
	}

	/**
	 * Function for fetching targetted ads for given ad type
	 *
	 * @param   array   $params  SocialAds module parameters
	 * @param   array   $data    Ad target data
	 * @param   string  $adType  Ad type - e.g. Geo
	 *
	 * @return  array  Array of ad ids
	 */
	public static function getAds($params, $data, $adType)
	{
		$className = self::loadHelperClass($adType);

		// If the class doesn't exist, return
		if (!class_exists($className))
		{
			return;
		}

		// Get ad target data from loaded class
		$ads = $className::getAds($params, $data);

		return $ads;
	}

	/**
	 * Returns ad's targetted URL
	 *
	 * @param   string  $adId  Ad ID
	 *
	 * @return  string
	 *
	 * @since  1.0
	 **/
	public static function getUrl($adId)
	{
		$input = JFactory::getApplication()->input;
		$db    = JFactory::getDBO();

		// $ad_id = $input->get('adid', 0, 'INT');

		$query = "SELECT ad_url1, ad_url2
		 FROM #__ad_data
		 WHERE ad_id = " . $adId;
		$db->setQuery($query);
		$result = $db->loadObject();

		$urlstring = '';
		$urlstring = $result->ad_url1;
		$urlstring .= '://';
		$urlstring .= $result->ad_url2;

		return $urlstring;
	}
}
