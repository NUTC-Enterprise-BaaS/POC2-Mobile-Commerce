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
 * Helper for mod_socialads
 *
 * @package     SocialAds
 * @subpackage  mod_socialads
 * @since       1.0
 */
abstract class ModSocialadsHelper
{
	/**
	 * Function to get debug values
	 *
	 * @param   array  $addata  Ad data
	 *
	 * @return  void
	 *
	 * @since  1.6
	 **/
	public static function getdebuggingValues($addata)
	{
		$db = JFactory::getDbo();
		$query = "Select a.*,b.* FROM #__ad_data as a , #__ad_fields as b WHERE a.ad_id = b.adfield_ad_id AND a.ad_id = $addata->ad_id ";
		$db->setQuery($query);
		$result = $db->loadObjectList();

		if ($result)
		{
			echo "created_by =>" . $result[0]->created_by;
			echo " ad_published =>" . $result[0]->ad_published;
			echo " ad_approved =>" . $result[0]->ad_approved;
			echo " ad_alternative =>" . $result[0]->ad_alternative;
			echo " ad_noexpiry =>" . $result[0]->ad_noexpiry;
			echo " ad_field_gender =>" . $result[0]->field_gender;
			echo " ad_birthdaylow =>" . $result[0]->field_birthday_low;
			echo " ad_birthdayhigh =>" . $result[0]->field_birthday_high;
			echo " ad_graduationlow =>" . $result[0]->field_graduation_low;
			echo " ad_graduationhigh =>" . $result[0]->field_graduation_high;
			echo " ad_country =>" . $result[0]->field_country;
			echo " ad_city =>" . $result[0]->field_city;
			echo " ad_state =>" . $result[0]->field_state;
			echo " relevance =>" . $addata->relevance;
		}
		else
		{
			$query = "Select a.* FROM #__ad_data as a  WHERE a.ad_id = $addata->ad_id ";
			$db->setQuery($query);
			$result = $db->loadObjectList();
			echo "created_by =>" . $result[0]->created_by;
			echo " ad_published =>" . $result[0]->ad_published;
			echo " ad_approved =>" . $result[0]->ad_approved;
			echo " ad_alternative =>" . $result[0]->ad_alternative;
			echo " ad_guest =>" . $result[0]->ad_guest;
			echo " ad_noexpiry =>" . $result[0]->ad_noexpiry;
		}
	}

	/**
	 * Added for sa_jbolo integration
	 *
	 * @param   integer  $adid  Ad id
	 *
	 * @return  array
	 *
	 * @since  1.6
	 **/
	public static function getAdcreator($adid)
	{
		$db = JFactory::getDbo();
		$query = "Select a.created_by FROM #__ad_data as a  WHERE a.ad_id = $adid ";
		$db->setQuery($query);
		$result = $db->loadResult();

		return $result;
	}

	/**
	 * To check ad charges type clicks, impression or day
	 *
	 * @param   integer  $adid  Ad id
	 *
	 * @return  array
	 *
	 * @since  1.6
	 **/
	public static function getAdChargeType($adid)
	{
		$db = JFactory::getDbo();
		$query = "Select ad_payment_type FROM #__ad_data   WHERE ad_id = $adid ";
		$db->setQuery($query);
		$result = $db->loadResult();

		return $result;
	}

	/**
	 * To check ad payment status
	 *
	 * @param   integer  $userid  user id
	 *
	 * @return  array
	 *
	 * @since  1.6
	 **/
	public static function isOnline($userid)
	{
		$db = JFactory::getDbo();
		$db->setQuery("SELECT userid FROM #__session WHERE userid = $userid AND client_id=0");
		$result = $db->loadResult();

		if ($result)
		{
			return 1;
		}
		else
		{
			return 0;
		}
	}

	// @TODO - not used anywhere - remove this
	/**
	 * To Build ad layout
	 *
	 * @param   integer  $ad_id  ad_id
	 *
	 * @return  void
	 *
	 * @since  1.6
	 **/
	public static function buildAdLayout($ad_id)
	{
		$adRetriever	=	new adRetriever;
		$adRetriever->getAdHtml($addata);
	}

	/**
	 * To get a ad zone type
	 *
	 * @param   integer  $zone_id  zone id of a selected zone
	 *
	 * @return  array
	 *
	 * @since  1.6
	 **/
	public static function getAdtypebyZone($zone_id)
	{
		$db = JFactory::getDbo();
		$query = "SELECT az.ad_type FROM #__ad_zone as az WHERE az.id =" . $zone_id;
		$db->setQuery($query);
		$result = $db->loadResult();

		return $result;
	}
	// End added for sa_jbolo integration
}
// End Functions required by the module
