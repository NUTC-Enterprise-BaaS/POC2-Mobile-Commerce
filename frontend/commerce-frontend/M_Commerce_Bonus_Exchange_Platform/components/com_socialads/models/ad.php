<?php
/**
 * @version    SVN:<SVN_ID>
 * @package    Com_Socialads
 * @author     Techjoomla <extensions@techjoomla.com>
 * @copyright  Copyright (c) 2009-2015 TechJoomla. All rights reserved
 * @license    GNU General Public License version 2, or later
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.model');

/**
 * ad helper class
 *
 * @since  1.6
 */
class SocialadsModelAd extends JModelLegacy
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
	public function getAd_Types($special_access)
	{
		$sa_params = JComponentHelper::getParams('com_socialads');
		$db = JFactory::getDBO();
		$query = "SELECT id,ad_type FROM #__ad_zone WHERE state=1";
		$db->setQuery($query);
		$count = $db->loadobjectlist();

		if ($count)
		{
			$publish_mod = $this->getZoneModule();
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

						/*jugad code*/
						$rawresult = str_replace('||', ',', $db->loadResult());
						$rawresult = str_replace('|', '', $rawresult);
						$ad_type1 = explode(",", $rawresult);
						/*jugad code*/

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

						/* ADDED for affiliate ads to show only when zone is present for it   */
						if (!empty($ad_type1[1]))
						{
							$ad_type_affiliate = $ad_type1[1];

							if ($ad_type_affiliate == 'affiliate')
							{
								if ($affiliate_flag == 0)
								{
									$affiliate_flag = 1;
								}

								// $published_zone_type[]='affiliate';
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
			$adtype_select[] = JHtml::_('select.option', 'text_media', JText::_('COM_SOCIALADS_AD_TYP_TXT_IMG'));
		}

		if ($img_flag)
		{
			$published_zone_type[] = 'media';
			$adtype_select[] = JHtml::_('select.option', 'media', JText::_('COM_SOCIALADS_AD_TYP_IMG'));
		}

		if ($text_flag)
		{
			$published_zone_type[] = 'text';
			$adtype_select[] = JHtml::_('select.option', 'text', JText::_('COM_SOCIALADS_AD_TYP_TXT'));
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
	public function getZoneModule()
	{
		$db = JFactory::getDBO();
		$query = "SELECT params FROM #__modules WHERE published = 1 AND module LIKE '%mod_socialads%'";
		$db->setQuery($query);
		$params = $db->loadObjectList();
		$module = array();

		foreach ($params as $params)
		{
			$params1 = str_replace('"', '', $params->params);
			$single = explode(",", $params1);

			foreach ($single as $single)
			{
				$name = explode(":", $single);

				if ($name[0] == 'zone')
				{
					$module[] = $name[1];
				}
			}
		}

		return $module;
	}

	/**
	 * Function to delete record
	 *
	 * @param   string  $table_name         zone type
	 * @param   string  $where_field_name   where column name
	 * @param   string  $where_field_value  column value
	 *
	 * @return  void
	 *
	 * @since  1.6
	 */
	public function deleteData($table_name,$where_field_name,$where_field_value)
	{
		$db = JFactory::getDBO();

		$app = JFactory::getApplication();
		$dbprefix = $app->getCfg('dbprefix');

		$tbexist_query = "SHOW TABLES LIKE '" . $dbprefix . $table_name . "'";
		$db->setQuery($tbexist_query);
		$isTableExist = $db->loadResult();

		$paramlist = array();

		if ($isTableExist)
		{
			$query = "DELETE FROM #__" . $table_name . "
					 WHERE " . $where_field_name . " = " . $where_field_value;

			$db->setQuery($query);
			$db->execute();
		}
	}

	/**
	 * IF admin has selected alternate ad then delete other data
	 *
	 * @param   integer  $ad_id  Ad id
	 *
	 * @return  boolean
	 *
	 * @since  1.6
	 */
	public function deleteDataAlternateAd($ad_id)
	{
		$db = JFactory::getDBO();

		// Delete __ad_contextual_target data
		$this->deleteData('ad_contextual_target', 'ad_id', $ad_id);

		// Delete __ad_geo_target data
		$this->deleteData('ad_geo_target', 'ad_id', $ad_id);

		// Delete __ad_fields data
		$this->deleteData('ad_fields', 'adfield_ad_id', $ad_id);

		/*
		Delete Price data if already exist
		$query = $db->getQuery(true);
		$query->delete('pi.*, o.*  ');
		$query->from('#__ad_payment_info AS pi');
		$query->join('INNER','#__ad_orders AS o ON pi.order_id=o.id');
		$query->where('pi.ad_id = ' . $ad_id);
		$query->where('o.status = "P"');
		*/

		$query = "Delete pi.*, o.* from #__ad_payment_info AS pi INNER JOIN #__ad_orders AS o ON pi.order_id=o.id where pi.ad_id = "
		. $ad_id . " AND o.status = 'P' ";
		$db->setQuery($query);

		if (!$db->execute())
		{
			$this->setError($this->_db->getErrorMsg());

			return 0;
		}
	}



	/**
	 * This fetching all inserted details from DB
	 *
	 * @param   integer  $ad_id  ad ID
	 *
	 * @return  integer
	 *
	 * @since  3.0
	 **/
	public function getData($ad_id)
	{
		require_once JPATH_SITE . '/components/com_socialads/helpers/createad.php';
		$db = JFactory::getDBO();
		$query = "SELECT a.* FROM #__ad_data AS a WHERE a.ad_id='$ad_id'";
		$db->setQuery($query);
		$addata = $db->loadObject();
		$count = 0;
		$createAdHelper = new createAdHelper;
		$adfields = $createAdHelper->chkadfields();

		if ($adfields != '')
		{
			$query = "SELECT COUNT(*) FROM #__ad_fields AS f WHERE f.adfield_ad_id=" . $ad_id;
			$db->setQuery($query);
			$count = $db->loadResult();

			if ($addata->ad_alternative == 0 && $count > 0)
			{
				$query = "SELECT a.*, f.* FROM #__ad_data AS a, #__ad_fields AS f WHERE a.ad_id='$ad_id' AND f.adfield_ad_id='$ad_id'";
				$db->setQuery($query);
				$addata = $db->loadObject();
			}
		}

		$addata_result[0] = $count;
		$addata_result[1] = $addata;

		return $addata_result;
	}

	/**
	 * Function to get zone
	 *
	 * @param   integer  $ad_id  ad ID
	 *
	 * @return  integer
	 *
	 * @since  3.0
	 **/
	public function getzone($ad_id)
	{
		$db = JFactory::getDBO();

		$query = "SELECT az.id,az.zone_name, az.state,az.orientation,az.ad_type,az.max_title,az.max_des,az.img_width,
				az.img_height,az.per_click,az.per_imp,az.per_day,az.layout
				FROM #__ad_data as ad LEFT JOIN #__ad_zone as az ON ad.ad_zone = az.id
				WHERE ad.ad_id =" . $ad_id;
		$db->setQuery($query);
		$zone = $db->loadObject();

		return $zone;
	}

	/**
	 * Fetching all inserted details from DB for geo targeting
	 *
	 * @param   integer  $ad_id  ad ID
	 *
	 * @return  integer
	 *
	 * @since  3.0
	 **/
	public function getData_geo_target($ad_id)
	{
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$input = JFactory::getApplication()->input;

		// $ad_id=$input->get('adid',0,'INT');
		$query = "SELECT a.* FROM #__ad_geo_target AS a WHERE a.ad_id='$ad_id'";
		$db->setQuery($query);
		$addata = $db->loadAssocList();

		if (!empty($addata[0]))
		{
			return $addata[0];
		}
		else
		{
			return $addata;
		}
	}

	/**
	 * Fetching all inserted details from DB for geo targeting
	 *
	 * @param   integer  $ad_id  ad ID
	 *
	 * @return  array
	 *
	 * @since  3.0
	 **/
	public function getData_context_target($ad_id)
	{
		$db = JFactory::getDBO();
		$user = JFactory::getUser();
		$input = JFactory::getApplication()->input;

		// $ad_id=$input->get('adid',0,'INT');
		$query = "SELECT a.keywords FROM #__ad_contextual_target AS a WHERE a.ad_id='$ad_id'";
		$db->setQuery($query);
		$addata = $db->loadColumn();

		if (!empty($addata))
		{
			return $addata[0];
		}
	}

	/**
	 * Functin to get pricing data
	 *
	 * @param   integer  $ad_id  ad ID
	 *
	 * @return  integer
	 *
	 * @since  3.0
	 **/
	public function getpricingData($ad_id)
	{
		$db = JFactory::getDBO();
		$query = "
			SELECT pi.ad_credits_qty, ad.ad_payment_type, ad.ad_startdate, o.original_amount
			FROM `#__ad_data` AS ad
			LEFT JOIN `#__ad_payment_info` AS pi ON pi.ad_id=ad.ad_id
			LEFT JOIN `#__ad_orders` AS o ON o.id=pi.order_id
			WHERE ad.ad_id = " . $ad_id . "
			AND o.status = 'P' ";

		$db->setQuery($query);
		$result = $db->loadObject();

		return $result;
	}

}
