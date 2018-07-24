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
 * Ads helper class for Contextual ads
 *
 * @package  SocialAds
 * @since    3.1
 */
class SaAdsHelperContext extends SaAdsHelper
{
	/**
	 * Fetch target data for contextual targeted ads
	 *
	 * @param   array   $params      SocialAds module parameters
	 * @param   string  $adType      Ad type - e.g. Context
	 * @param   string  $engineType  Engine Type
	 *
	 * @return  array  Geolocation data of loggedin user
	 */
	public static function getAdTargetData($params, $adType, $engineType = 'local')
	{
		$link_id = '';
		$db      = JFactory::getDbo();

		$saParams = JComponentHelper::getParams('com_socialads');

		if (!($saParams->get('contextual_targeting')))
		{
			return array();
		}

		$input  = JFactory::getApplication()->input;
		$query1 = $query2 = $querylink = '';
		$searchPages = $saParams->get('search_pages');
		$context_target_param = $saParams->get('query_strings');
		$smartSearch = $saParams->get('smart_search');
		$meatKeyword = $saParams->get('meta_keywords');

		// If Contextual Ads For Search Pages are enabled & search page urls are set
		if (!empty($context_target_param) and !empty($searchPages))
		{
			$Context_searchData = self::getContextSearchData();
			$searchkeywords     = strtolower($Context_searchData['search']);
		}

		if (!empty($searchkeywords))
		{
			$search_terms = explode(',', $searchkeywords);
		}

		if (!empty($smartSearch) and empty($searchkeywords))
		{
			$vv = JUri::getInstance()->toString();
			$vv = str_replace(JUri::base() . '', '', $vv);
			$vv = str_replace(JUri::base() . '', '', $vv);

			$id     = $input->get('id', 0, 'INT');
			$option = $input->get('option', '', 'STRING');
			$view   = $input->get('view', '', 'STRING');
			$Itemid = $input->get('Itemid', 0, 'INT');

			$query1   = 'index.php?option=' . $option . '&view=' . $view . '&id=' . $id;
			$where1[] = " url LIKE '$query1%'";
			$where1[] = " route LIKE '$query1%'";

			$cond2 = $cond21 = '';

			if (!empty($where1))
			{
				$cond1 = implode(' OR ', $where1);
				$cond1 = " WHERE " . $cond1;
			}

			$doc = JFactory::getDocument();

			if (JVERSION >= '2.5.0')
			{
				$querylink = " SELECT DISTINCT(link_id)
				 FROM #__finder_links " . $cond1;
				$db->setQuery($querylink);
				$link_id = $db->loadResult();

				if (!empty($link_id))
				{
					$linkcondition = " link_id IN('" . $link_id . "')";
					$query = "SELECT term, weight
					 FROM #__ad_contextual_terms
					 WHERE " . $linkcondition . "
					 AND term<>''
					 ORDER BY weight DESC
					 LIMIT 100";
					$db->setQuery($query);
					$terms = $db->loadObjectList();

					if (!empty($terms))
					{
						foreach ($terms as $term)
						{
							if (!empty($term->term))
							{
								$term->term = trim($term->term);

								if ($term->term)
								{
									$pagekeywords[] = $search_terms[] = trim(strtolower($term->term));
								}
							}
							// TODO: Find an alternative for htmlspecialchars
						}
					}
				}
			}
		}

		if (empty($searchkeywords))
		{
			$metakeywords = '';
			$doc          = JFactory::getDocument();
			$metakeywords = $doc->getMetaData('keywords');

			if ($metakeywords)
			{
				$metaarr = explode(',', $metakeywords);

				foreach ($metaarr as $metadt)
				{
					$search_terms_meta[] = trim(strtolower($metadt));
					$search_terms[]      = trim(strtolower($metadt));
				}
			}
		}

		if (empty($meatKeyword) and empty($searchkeywords))
		{
			if (!empty($search_terms_meta))
			{
				$search_terms = array_diff($search_terms, $search_terms_meta);
			}
		}

		if (SaAdEngineHelper::$_contextdebug == '1')
		{
			echo '<br><br>Contextual Debug Info:: ';

			if (!empty($searchkeywords))
			{
				echo "<br><br>searchkeywords==" . $searchkeywords;
			}
			else
			{
				echo "<br><br>link1====" . $query1;
				echo "<br><br>link_ids in #__finder_links====" . $link_id;

				if (!empty($metakeywords))
				{
					echo "<br><br>metakeywords==" . $metakeywords;
				}
				else
				{
					echo "<br><br>metakeywords not found";
				}

				if (!empty($pagekeywords))
				{
					echo "<br><br>pagekeywords==" . implode(',', $pagekeywords);
				}
				else
				{
					echo "<br><br>pagekeywords not found";
				}

				if (!empty($search_terms))
				{
					echo "<br><br>finalkeywords==" . implode(',', $search_terms);
				}
				else
				{
					echo "<br><br>finalkeywords not found";
				}
			}
		}

		if (!empty($search_terms))
		{
			return $search_terms;
		}
		else
		{
			return array();
		}
	}

	/**
	 * Get Ads remote targeted data
	 *
	 * @param   array   $params      SocialAds module parameters
	 * @param   string  $adType      Ad type - e.g. Context
	 * @param   string  $engineType  Engine Type
	 *
	 * @return  array  Geolocation data of loggedin user
	 */
	public static function getAdTargetDataRemote($params, $adType, $engineType = 'remote')
	{
		$session  = JFactory::getSession();
		$userData = $session->get('userData', array());

		if (!empty($userData['context_params']['keys']))
		{
			$keywords = explode(",", $userData['context_params']['keys']);

			return $keywords;
		}

		return array();
	}

	/**
	 * Fetch geo targetted ads based on contextual data collected
	 *
	 * @param   array   $params  SocialAds module parameters
	 * @param   array   $data    Ad target data
	 * @param   string  $adType  Ad type - e.g. Context
	 *
	 * @return  array  Array of ad ids
	 */
	public static function getAds($params, $data, $adType = '')
	{
		$search_terms = $data;
		$db           = JFactory::getDbo();
		$search_terms = array_unique($search_terms);
		$where        = array();

		foreach ($search_terms as $fuz_value)
		{
			if (!empty($fuz_value))
			{
				$fuz_value       = trim($fuz_value);
				$fuz_value       = $db->escape($fuz_value);
				$search_values[] = "" . htmlspecialchars($fuz_value) . "";
			}

			// TODO: Find an alternative for htmlspecialchars
		}

		// For common query function userd as flag
		$function_name = "contextual";

		// $camp_join = SaAdEngineHelper::join_camp();
		$camp_join = SaAdEngineHelper::getQueryJoinCampaigns();

		// $common_where = SaAdEngineHelper::query_common($params, $function_name, $adRetriever);
		$common_where = SaAdEngineHelper::getQueryWhereCommon($params, $function_name);
		$common_where = implode(' AND ', $common_where);

		if (empty($search_values))
		{
			return;
		}

		$search_valuestr = implode(' ', $search_values);
		$search_valuestr = strtolower($search_valuestr);

		$where[] = "MATCH (keywords) AGAINST ( '" . $search_valuestr . "' IN BOOLEAN MODE ) ";
		$where   = (count($where) ? ' WHERE ' . implode("\n AND ", $where) : '');
		$debug   = "";

		if (SaAdEngineHelper::$_contextdebug == '1')
		{
			$debug = " g.*, ";
		}

		$result_ads = array();

		$query = "SELECT  DISTINCT(g.ad_id), MATCH (keywords) AGAINST ( '" . $search_valuestr . "' IN BOOLEAN MODE ) AS relevance
		 FROM #__ad_contextual_target AS g, #__ad_data AS a " .
		$camp_join .
		$where . "
		 AND g.ad_id = a.ad_id
		 AND keywords<>''";

		$query .= " AND " . $common_where . "
		 HAVING relevance>.2
		 ORDER BY relevance DESC";
		SaAdEngineHelper::$_contextquery = $query;

		try
		{
			$db->setQuery($query);
			$result_ads = $db->loadObjectList();
		}
		catch (Exception $e)
		{
			// Catch any database errors.
			JErrorPage::render($e);
		}

		if (SaAdEngineHelper::$_contextdebug == '1')
		{
			echo "<br><br>";

			if (!empty($result_ads))
			{
				print_r($result_ads);
			}
		}

		return $result_ads;
	}

	/**
	 * Get Context Search Data
	 *
	 * @return  array  All contextual data
	 */
	public static function getContextSearchData()
	{
		$saParams = JComponentHelper::getParams('com_socialads');
		$searchPages = $saParams->get('search_pages');
		$context_target_param = $saParams->get('query_strings');
		$input                = JFactory::getApplication()->input;

		// If Contextual Ads For Search Pages are enabled & search page urls are set
		if (!empty($context_target_param) and !empty($searchPages))
		{
			$final_query          = array();
			$context_target_param = trim($context_target_param);
			$comp_parmas_arrs     = explode("\n", $context_target_param);
			$i = 0;

			foreach ($comp_parmas_arrs as $comp_parmas)
			{
				$comp_parmas_inarr  = explode("|", $comp_parmas);
				$querystring        = $comp_parmas_inarr[0];
				$querystring_params = explode("&", $querystring);

				foreach ($querystring_params as $querystringf)
				{
					$querystring_values                = explode("=", $querystringf);
					$final_query_key                   = $querystring_values[0];
					$final_query_value                 = $querystring_values[1];
					$final_query[$i][$final_query_key] = $final_query_value;
				}

				$final_query[$i]['search'] = $comp_parmas_inarr[1];
				$searchword                = $comp_parmas_inarr[1];
				$i++;
			}
		}

		$option     = $input->get('option', '', 'STRING');
		$view       = $input->get('view', '', 'STRING');
		$layout     = $input->get('layout');
		$controller = $input->get('controller', '', 'STRING');
		$task       = $input->get('task', '', 'STRING');

		if (!empty($option))
		{
			$matchdata['option'] = $option;
		}

		if (!empty($view))
		{
			$matchdata['view'] = $view;
		}

		if (!empty($layout))
		{
			$matchdata['layout'] = $layout;
		}

		if (!empty($controller))
		{
			$matchdata['controller'] = $controller;
		}

		if (!empty($task))
		{
			$matchdata['task'] = $task;
		}

		$flag                = 0;
		$finaldata['search'] = '';
		$finaldata['flag']   = 0;

		foreach ($final_query as $queries)
		{
			$search = trim($queries['search']);
			unset($queries['search']);
			$result = array_diff($matchdata, $queries);

			if (!$result)
			{
				$finaldata['search'] = $input->get($search, '', 'STRING');
				$finaldata['flag']   = 1;
				break;
			}

			if ($finaldata['flag'] == 1)
			{
				break;
			}
		}

		return $finaldata;
	}
}
