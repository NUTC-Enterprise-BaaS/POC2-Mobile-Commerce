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
 * @package     SocialAds
 * @since       3.1
 */
class SaStatsHelper
{
	// Update the stats table for the ad
	// function putStats($adid,$type,$ad_charge,$widget="")
	public static function putStats($adid, $type, $ad_charge, $widget = "")
	{
		$db   = JFactory::getDbo();
		$user = JFactory::getUser();
		$insertstat               = new stdClass;
		$insertstat->id           = '';
		$insertstat->ad_id        = $adid;
		$insertstat->user_id      = $user->id;
		$insertstat->display_type = $type;
		$insertstat->ip_address   = $_SERVER["REMOTE_ADDR"];
		$insertstat->spent        = $ad_charge;

		if (!empty($_SERVER['HTTP_REFERER']))
		{
			$parse = parse_url($_SERVER['HTTP_REFERER']);

			if ($parse['host'] == $_SERVER['HTTP_HOST'] && $type==1)
			{
				$insertstat->referer = $widget;

			}
			else
			{
				if ($widget != "")
				{
					$insertstat->referer = $parse['host'] . "|" . $widget;
				}
				else
				{
					$insertstat->referer = $parse['host'];
				}
			}
		}

		if (!$db->insertObject( '#__ad_stats', $insertstat, 'id'))
		{
			echo $db->stderr();

			return false;
		}
	}

	/*increment stats in the ad_data table for the ad
	 * adid = id of the Ad
	 * type= 0 imprs;type =1 clks;
	 */
	// function incrementStats($adid,$type,$qty=1)
	public static function incrementStats($adid, $type, $qty = 1)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		// Fields to update.
		if ($type === 1)
		{
			$fields = array(
				// $db->quoteName('ad_clicks') . ' = ' . $db->quoteName('ad_clicks') . ' + ' . $qty

				$db->quoteName('clicks') . ' = ' . $db->quoteName('clicks') . ' + ' . $qty
			);
		}
		else
		{
			$fields = array(
				//$db->quoteName('ad_impressions') . ' = ' . $db->quoteName('ad_impressions') . ' + ' . $qty

				$db->quoteName('impressions') . ' = ' . $db->quoteName('impressions') . ' + ' . $qty
			);
		}

		// Conditions for which records should be updated.
		$conditions = array(
			$db->quoteName('ad_id') . ' = '.(int)$adid
		);

		$query->update($db->quoteName('#__ad_data'))->set($fields)->where($conditions);
		$db->setQuery($query);
		$result = $db->execute();

		return;
	}
}
