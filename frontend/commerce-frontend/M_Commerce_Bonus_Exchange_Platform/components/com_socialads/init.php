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

class SaInit
{
	/**
	 * Autoload class
	 */
	static function autoLoadHelpers($classname)
	{
		// Load component helper files
		$classes = array(
			/*Root folder*/
			'socialadsFrontendhelper' => 'helper.php',

			/*Helpers*/
			'SaAdsHelper'             => 'helpers/ads.php',
			'SaCommonHelper'          => 'helpers/common.php',
			'SaCreditsHelper'         => 'helpers/credits.php',
			'SaAdEngineHelper'        => 'helpers/engine.php',
			'SaStatsHelper'           => 'helpers/stats.php',
			'SaZonesHelper'           => 'helpers/zones.php',
			'TJGeoLocationHelper'     => 'helpers/tjgeoloc.php',
			'SaIntegrationsHelper'    => 'helpers/integrations.php'
		);

		if (array_key_exists($classname, $classes))
		{
			if (!class_exists($classname))
			{
				require_once JPATH_SITE . '/components/com_socialads/' . $classes[$classname];
			}
		}
	}
}
