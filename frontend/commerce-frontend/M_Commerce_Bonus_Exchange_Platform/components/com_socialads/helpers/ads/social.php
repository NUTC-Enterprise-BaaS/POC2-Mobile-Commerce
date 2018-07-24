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
class SaAdsHelperSocial extends SaAdsHelper
{
	// @function getSocialData($params,$adRetriever)
	/**
	 * Get the targeted ads
	 *
	 * @param   string  $params      Module parameters
	 * @param   string  $adType      Ad type
	 * @param   string  $engineType  Engine to fetch ad
	 *
	 * @return  array
	 *
	 * @since  1.6
	 **/
	public static function getAdTargetData($params, $adType, $engineType = 'local')
	{
		$saParams = JComponentHelper::getParams('com_socialads');

		$userData = array();

		if (SaAdEngineHelper::$_my->id)
		{
			// Get the user data according to the targetted fields
			$userData = self::getUserData($saParams->get('social_integration'));
		}

		return $userData;
	}

	// @function getSocialData($params,$adRetriever)
	/**
	 * Get the remote targeted ads
	 *
	 * @param   string  $params      Module parameters
	 * @param   string  $adType      Ad type
	 * @param   string  $engineType  Engine to fetch ad
	 *
	 * @return  array
	 *
	 * @since  1.6
	 **/
	public static function getAdTargetDataRemote($params, $adType, $engineType = 'remote')
	{
		$saParams = JComponentHelper::getParams('com_socialads');

		$userData = array();

		if (SaAdEngineHelper::$_my->id)
		{
			// Get the user data according to the targetted fields
			$userData = self::getUserData($saParams->get('social_integration'), $engineType);
		}

		return $userData;
	}

	// Returns all the possible matches of the ads
	/**
	 * Get the targeted ads
	 *
	 * @param   string  $params  Module parameters
	 * @param   string  $data    Target value
	 * @param   string  $adType  Ad type
	 *
	 * @return  array
	 *
	 * @since  1.6
	 **/
	public static function getAds($params, $data, $adType = '')
	{
		require JPATH_SITE . "/components/com_socialads/defines.php";

		$db           = JFactory::getDbo();
		$fuzzy_fields = array();
		$getUserData  = '';
		$getUserData  = $data;

		if (!empty($getUserData))
		{
			$paramlist = SaCommonHelper::getTableColumns('ad_fields');

			if (empty($paramlist))
			{
				return array();
			}

			// Sort 'the exact & fuzzy part
			foreach ($getUserData as $values)
			{
				if (in_array($values->mapping_fieldname, $paramlist))
				{
					// Gather all the fuzzy fields
					if ($values->mapping_match == 0)
					{
						/*$fuzzy_fields[] = $values->mapping_fieldname;
						$fuzzy_data[] = $values->value;*/

						if (strlen($values->value) > 4)
						{
							$where[] = "( MATCH ({$values->mapping_fieldname}) AGAINST (" . $db->quote($values->value) . "
							IN BOOLEAN MODE) OR b.{$values->mapping_fieldname} = '')";
						}
						else
						{
							$where[] = " ( b.{$values->mapping_fieldname} = '$values->value'  OR  b.{$values->mapping_fieldname} = '' ) ";
						}
					}
					else
					{
						// Switch to add where conditions for field types
						switch ($values->mapping_fieldtype)
						{
							case 'singleselect':
							case 'gender':
							case 'boolean':
							case 'multiselect':
								$where[] = "(b.{$values->mapping_fieldname} LIKE " . $db->Quote("%|{$values->value}|%") . " OR b.{$values->mapping_fieldname} = '')";
							break;

							case 'textbox':
								$where[] = "(b.{$values->mapping_fieldname} LIKE " . $db->Quote("%|{$values->value}|%") . " OR b.{$values->mapping_fieldname} = '')";
							break;

							case 'date':
								$where[] = "(b.{$values->mapping_fieldname} = " . $db->Quote($values->value) . " OR b.{$values->mapping_fieldname} = '')";
							break;

							case 'daterange':
							case 'numericrange':
								$where[] = "(b.{$values->mapping_fieldname}_low <= {$db->Quote($values->value)}
								AND b.{$values->mapping_fieldname}_high >= 	{$db->Quote($values->value)})";
							break;
						}
					}
				}
			}

			$where[] = " b.adfield_ad_id = a.ad_id";

			/*//if there is any fuzzy targeted field
			if(count($fuzzy_fields))
			{
				$field_names = implode(',', $fuzzy_fields);

				$valueswithqoutesinarray = array();
				foreach ($fuzzy_data as $fuz_value)
				{
					$fuz_value = addslashes($fuz_value);
					TODO: Find an alternative for htmlspecialchars
					$fuzzy_values[] = "'".htmlspecialchars($fuz_value)."'";
				}
				$fuzzy_values = implode(' ', $fuzzy_values);
				$query_fuz = "MATCH ($field_names) AGAINST ( \"$fuzzy_values\" IN BOOLEAN MODE )";
			}*/

			/*if ($query_fuz)
			{
				$query_fuz .= ' AS relevance ';
				$extra ="HAVING relevance >.2 ORDER BY relevance ";
			}
			else
			{
				$query_fuz = " a.ad_id as relevance ";
				$extra = "ORDER BY a.ad_id ";
			}*/

			$extra = "ORDER BY a.ad_id ";

			if ($limit)
			{
				$extra .= " LIMIT $limit";
			}

			// Camp_join if camp enabled in backend
			// $camp_join = self::join_camp();
			$camp_join = SaAdEngineHelper::getQueryJoinCampaigns();

			// Begin composing the query
			$query = "SELECT a.ad_id  ";

			/*if ($query_fuz)
			{
				$query .= ', ' . $query_fuz . "\n";
			}*/

			$query .= " FROM " . (($getUserData) ? " #__ad_fields as b ,": "" ) . " #__ad_data as a $camp_join  \n";

			$function_name = "adids";

			// Common query
			// $common_where = self::query_common($params, $function_name, $adRetriever);
			$common_where = SaAdEngineHelper::getQueryWhereCommon($params, $function_name);
			$common_where = implode(' AND ', $common_where);
			$where[]      = (!SaAdEngineHelper::$_my->id)? " a.ad_guest = 1" : " a.ad_guest <> 1";

			// Start Added by Sheetal
			if (SaAdEngineHelper::$_my->id && ($getUserData))
			{
				// Added by aniket --to call only those plugin who has the entry in ad_fields table
				// @TODO - Add this query in separate function so that it can also be used while creating ad
				JPluginHelper::importPlugin('socialadstargeting');
				$dispatcher = JDispatcher::getInstance();
				$results    = $dispatcher->trigger('OnAfterGetAds', array($paramlist));

				// Get all plugin trigger results
				foreach ($results as $value)
				{
					foreach ($value as $val)
					{
						$where[] = " $val";
					}
				}
			}
			// End Added by Sheetal

			// Commpon where imploded...
			$where = (count($where) ? ' WHERE ' . implode("\n AND ", $where) : '');
			$where = $where . " AND " . $common_where;
			$query .= "\n " . $where . "\n " . $extra;
			$db->setQuery($query);
			$result = $db->loadObjectList();
			$ads = $result;
		}

		if (empty($result))
		{
			return array();
		}

		return $ads;
	}

	// @TODO this is a useless function - use getSocialData directly
	/**
	 * Get the user data according to targeted fields
	 *
	 * @param   string  $int_typ     Social integration type
	 * @param   string  $engineType  Ad fetch engine type
	 *
	 * @return  string   id.
	 *
	 * @since  1.6
	 **/
	public static function getUserData($int_typ, $engineType = 'local')
	{
		// Get data for remote ads
		if ($engineType == 'remote')
		{
			return self::getSocialParams();
		}

		// $socialadshelper = new socialadshelper();SaCommonHelper::

		if ($int_typ == 'Community Builder')
		{
			// $cbchk = $socialadshelper->cbchk();
			$cbchk = SaCommonHelper::checkForSocialIntegration();

			if (!empty($cbchk))
			{
				$ud = self::getCBData();

				return $ud;
			}
		}
		elseif ($int_typ == 'JomSocial')
		{
			// $jschk = $socialadshelper->jschk();
			$jschk = SaCommonHelper::checkForSocialIntegration();

			if (!empty($jschk))
			{
				$ud = self::getJSData();

				return $ud;
			}
		}
		elseif ($int_typ == 'EasySocial')
		{
			// $eschk = $socialadshelper->eschk();
			$eschk = SaCommonHelper::checkForSocialIntegration();

			if (!empty($eschk))
			{
				$ud = self::getESData();

				return $ud;
			}
		}
		// If intregration is set to None...
		elseif ($int_typ == 2)
		{
			return;
		}
	}

	/**
	 * Function to get JS data
	 *
	 * @return  array
	 *
	 * @since  1.6
	 **/
	public static function getJSData()
	{
		$db    = JFactory::getDbo();
		$query = "SELECT cfv.value, afm.mapping_fieldtype, afm.mapping_fieldname, afm.mapping_match
		 FROM #__community_fields_values as cfv
		 JOIN #__ad_fields_mapping as afm ON afm.mapping_fieldid = cfv.field_id
		 LEFT JOIN #__community_fields as cfc ON cfc.id = afm.mapping_fieldid
		 WHERE cfv.user_id=" . SaAdEngineHelper::$_my->id . "
		 ORDER BY cfv.field_id";
		$db->setQuery($query);

		return $values = $db->loadObjectList();
	}

	/**
	 * Function to get ES data
	 *
	 * @return  array
	 *
	 * @since  1.6
	 **/
	public static function getESData()
	{
		require_once JPATH_ADMINISTRATOR . '/components/com_easysocial/includes/foundry.php';

		$db = JFactory::getDBO();
		$uid = JFactory::getUser()->id;
		$query = "SELECT sf.unique_key,afm.mapping_fieldtype,afm.mapping_fieldname,afm.mapping_match
					FROM #__ad_fields_mapping as afm
					LEFT JOIN #__social_fields as sf ON sf.id = afm.mapping_fieldid";

		$db->setQuery($query);
		$values = $db->loadObjectList();

		foreach ($values as $val)
		{
			/* Remove this when replied by stackideas people.
			if($val->mapping_fieldname=='address')
			$val->value='flat no 6 saket app pune maharashtra';
			else
			{*/
				$ES_value = Foundry::user()->getFieldValue($val->unique_key);

				// Special condition for gender fields.
				if ($val->unique_key == 'GENDER')
				{
					$val->value = $ES_value->value->title;
				}
				else
				{
					$val->value = $ES_value->value;
				}

				// If returned value is in array format then get that into a||b format
				if (is_array($val->value))
				{
					$val->value = implode('||', $val->value);
				}
		}

		return	$values;
	}

	/**
	 * Function to get CB data
	 *
	 * @return  array
	 *
	 * @since  1.6
	 **/
	public static function getCBData()
	{
		$db = JFactory::getDBO();
		$query = "SELECT * FROM #__ad_fields_mapping WHERE mapping_fieldtype <> 'targeting_plugin' ORDER BY mapping_id";
		$db->setQuery($query);
		$mapdata = $db->loadObjectlist();

		// Don't go inside if mapdata is empty
		if (!empty($mapdata))
		{
			$i = 0;

			foreach ($mapdata as $map)
			{
				$col_nam[] = $map->mapping_fieldname;
			}

			$col_nam = implode(',', $col_nam);

			$query = "SELECT " . $col_nam . "
						FROM #__comprofiler
						WHERE user_id =  " . SaAdEngineHelper::$_my->id;
			$db->setQuery($query);
			$col_value = $db->loadObjectlist();
			$result = array();

			foreach ($mapdata as $key => $map)
			{
				// Get the field values of the above mapping field names
				$str = $map->mapping_fieldname;

				if (!empty($col_value[0]->$str))
				{
					$result[$i] = new stdClass;
					$result[$i]->value = $col_value[0]->$str;
					$result[$i]->mapping_fieldtype = $map->mapping_fieldtype;
					$result[$i]->mapping_fieldname = $map->mapping_fieldname;
					$result[$i]->mapping_match = $map->mapping_match;
					$i++;
				}
			}

			return $result;
		}

		// Mapdata empty condition
	}

	/**
	 * Function to get social params
	 *
	 * @return  array
	 *
	 * @since  1.6
	 **/
	public static function getSocialParams()
	{
		$db    = JFactory::getDBO();
		$query = "SELECT * FROM #__ad_fields_mapping ORDER BY mapping_id";
		$db->setQuery($query);
		$mapdata = $db->loadObjectlist();

		// Dont go inside if mapdata is empty
		if (!empty($mapdata))
		{
			$i         = 0;
			$session   = JFactory::getSession();
			$userData  = $session->get('userData', array());
			$col_value = $userData['social_params'];

			$result = array();

			foreach ($mapdata as $key => $map)
			{
				if (!empty($col_value[$map->mapping_fieldname]))
				{
					// Get the field values of the above mapping field names
					$str                           = $map->mapping_fieldname;
					$result[$i]                    = new stdClass;
					$result[$i]->value             = $col_value[$map->mapping_fieldname];
					$result[$i]->mapping_fieldtype = $map->mapping_fieldtype;
					$result[$i]->mapping_fieldname = $map->mapping_fieldname;
					$result[$i]->mapping_match     = $map->mapping_match;
					$i++;
				}
			}

			return $result;
		}
	}
}
