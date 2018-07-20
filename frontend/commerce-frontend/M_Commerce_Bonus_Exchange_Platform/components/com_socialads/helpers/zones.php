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
 * Zones Helper class
 *
 * @package  SocialAds
 * @since    3.1
 */
class SaZonesHelper
{
	/**
	 * Extra code for zone to Check if only one entry of zones while instlalling components
	 *
	 * @param   array  $special_access  access details
	 *
	 * @return  string
	 *
	 * @since  1.6
	 */
	public static function getAllowedAdTypes($special_access)
	{
		$sa_params = JComponentHelper::getParams('com_socialads');
		$db    = JFactory::getDBO();
		$query = "SELECT id, ad_type FROM #__ad_zone WHERE state=1";
		$db->setQuery($query);
		$count = $db->loadobjectlist();

		if ($count)
		{
			$publish_mod = self::getZoneModule();
			$results = array_unique($publish_mod);
			$text_img_flag = $img_flag = $text_flag = $affiliate_flag = 0;

			foreach ($results as $publish_asign_zones)
			{
				if ($text_img_flag == 1 and $img_flag == 1 and $text_flag == 1 and $affiliate_flag = 1)
				{
					break;
				}

				foreach ($count as $zoneids)
				{
					if ($publish_asign_zones == $zoneids->id)
					{
						$query1 = "SELECT ad_type FROM #__ad_zone WHERE id=" . $publish_asign_zones . " AND state=1 group by ad_type";
						$db->setQuery($query1);

						// Jugad code
						$rawresult = str_replace('||', ',', $db->loadResult());
						$rawresult = str_replace('|', '', $rawresult);
						$ad_type1 = explode(",", $rawresult);

						// Jugad code end

						$adtype_default = array();
						$adtype_default[] = 'text_media';
						$adtype_default[] = 'text';
						$adtype_default[] = 'media';
						$ad_type = $ad_type1[0];

						if ($ad_type)
						{
							if ($ad_type == 'text_media' && in_array('text_media', $sa_params->get('ad_type_allowed', $adtype_default)) )
							{
								if ($text_img_flag == 0)
								{
									$text_img_flag = 1;
								}
							}

							if ($ad_type == 'media' && in_array('media', $sa_params->get('ad_type_allowed', $adtype_default)) )
							{
								if ($img_flag == 0)
								{
									$img_flag = 1;
								}
							}

							if ($ad_type == 'text' && in_array('text', $sa_params->get('ad_type_allowed', $adtype_default)) )
							{
								if ($text_flag == 0)
								{
									$text_flag = 1;
								}
							}
						}

						// ADDED for affiliate ads to show only when zone is present for it
						if (!empty($ad_type1[1]))
						{
							$ad_type_affiliate = $ad_type1[1];

							if ($ad_type_affiliate == 'affiliate')
							{
								if ($affiliate_flag == 0)
								{
									$affiliate_flag = 1;

									// $published_zone_type[]='affiliate';
								}
							}
						}
					}
				}
			}
		}

		$adtype_select = array();

		if ($text_img_flag)
		{
			$published_zone_type[] = 'text_media';
			$adtype_select[] = JHtml::_('select.option', 'text_media',  JText::_('COM_SOCIALADS_AD_TYP_TXT_IMG'));
		}

		if ($img_flag)
		{
			$published_zone_type[] = 'media';
			$adtype_select[] = JHtml::_('select.option', 'media',  JText::_('COM_SOCIALADS_AD_TYP_IMG'));
		}

		if ($text_flag)
		{
			$published_zone_type[] = 'text';
			$adtype_select[] = JHtml::_('select.option', 'text',  JText::_('COM_SOCIALADS_AD_TYP_TXT'));
		}

		if ($affiliate_flag && $special_access)
		{
			$published_zone_type[] = 'affiliate';
			$adtype_select[] = JHtml::_('select.option', 'affiliate', JText::_('COM_SOCIALADS_AD_TYP_AFFI'));
		}

		return $adtype_select;
	}

	/**
	 * Function to get module for a zone
	 *
	 * @return  array
	 *
	 * @since  1.6
	 */
	public static function getZoneModule()
	{
		$db = JFactory::getDBO();
		$query = "SELECT params FROM #__modules WHERE published = 1 AND module LIKE '%mod_socialads%'";
		$db->setQuery($query);
		$params = $db->loadObjectList();
		$module = array();

		foreach ($params as $params)
		{
			$params1 = str_replace('"', '', $params->params);

			if (JVERSION >= '1.6.0')
			{
				$single = explode(",", $params1);
			}
			else
			{
				$single = explode("\n", $params1);
			}

			foreach ($single as $single)
			{
				if (JVERSION >= '1.6.0')
				{
					$name = explode(":", $single);
				}
				else
				{
					$name = explode("=", $single);
				}

				if ($name[0] == 'zone')
				{
					$module[] = $name[1];
				}
			}
		}

		return $module;
	}

}
