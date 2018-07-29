<?php
/*
# SP News Highlighter Module by JoomShaper.com
# --------------------------------------------
# Author    JoomShaper http://www.joomshaper.com
# Copyright (C) 2010 - 2014 JoomShaper.com. All Rights Reserved.
# License - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomshaper.com
*/

// no direct access
defined('_JEXEC') or die('Restricted access');
$k2route = JPATH_SITE.'/components/com_k2/helpers/route.php';
$k2utilities = JPATH_SITE.'/components/com_k2/helpers/utilities.php';
if (file_exists($k2route))
	require_once($k2route);
	
if (file_exists($k2utilities))
	require_once($k2utilities);
	
abstract class modNewsHighlighterHelper {

	static function getList($params) {
	
			//Parameters
			$catids								= $params->get('k2catids', array());
			$count 								= $params->get('count',3);
			$ordering							= $params->get('ordering', 'a.ordering');
			$ordering_direction					= $params->get('ordering_direction', 'ASC');
			$user_id							= $params->get('user_id');
			$show_featured						= $params->get('show_featured');			

			$user 		= JFactory::getUser();
			$aid 		= $user->get('aid');
			$db 		= JFactory::getDBO();

			$jnow 		= JFactory::getDate();
			$now 		= $jnow->toSql();
			$nullDate 	= $db->getNullDate();

			$query = "SELECT a.*, c.name as categoryname,c.id as categoryid, c.alias as categoryalias, c.params as categoryparams".
			" FROM #__k2_items as a".
			" LEFT JOIN #__k2_categories c ON c.id = a.catid";
			$query .= " WHERE a.published = 1 AND a.access IN(".implode(',', $user->getAuthorisedViewLevels()).") AND a.trash = 0 AND c.published = 1 AND c.access IN(".implode(',', $user->getAuthorisedViewLevels()).")  AND c.trash = 0";
			
			// User filter
			$userId = JFactory::getUser()->get('id');
			switch ($params->get('user_id'))
			{
				case 'by_me':
					$query .= ' AND (a.created_by = ' . (int) $userId . ' OR a.modified_by = ' . (int) $userId . ')';
					break;
				case 'not_me':
					$query .= ' AND (a.created_by <> ' . (int) $userId . ' AND a.modified_by <> ' . (int) $userId . ')';
					break;

				case '0':
					break;

				default:
					$query .= ' AND (a.created_by = ' . (int) $userId . ' OR a.modified_by = ' . (int) $userId . ')';
					break;				
			}

			//Added Category
			if (!is_null($catids)) {
				if (is_array($catids)) {
					JArrayHelper::toInteger($catids);
					$query .= " AND a.catid IN(".implode(',', $catids).")";
				} else {
					$query .= " AND a.catid=".(int)$catids;
				}
			}	

			//  Featured items filter
			if ($show_featured == '0')
			$query .= " AND a.featured != 1";

			if ($show_featured == '1')
			$query .= " AND a.featured = 1";

			// ensure should be published
			$query .= " AND ( a.publish_up = ".$db->Quote($nullDate)." OR a.publish_up <= ".$db->Quote($now)." )";
			$query .= " AND ( a.publish_down = ".$db->Quote($nullDate)." OR a.publish_down >= ".$db->Quote($now)." )";
			
			//Ordering
			$orderby = $ordering . ' ' . $ordering_direction;

			$query .= " ORDER BY ".$orderby;
			$db->setQuery($query, 0, $count);
			$items = $db->loadObjectList();
			
			require_once (JPATH_SITE.'/components/com_k2/models/item.php');
			$model = new K2ModelItem;

			if (count($items)) {
				foreach ($items as $item) {
					$item->title = htmlspecialchars($item->title);
					$item->date = $item->created;
					$item->link = urldecode(JRoute::_(K2HelperRoute::getItemRoute($item->id.':'.urlencode($item->alias), $item->catid.':'.urlencode($item->categoryalias))));
					$rows[] = $item;
				}
				return $rows;
			}
	}
	
	public static function getText($text, $limit, $type) {//Function to clean text
		$text = JFilterOutput::cleanText($text);
		switch ($type) {
			case 0 :
				$text = explode(' ',$text);
				$text=implode(' ', array_slice($text,0,$limit));
				break;
			case 1 :
				$text=utf8_substr($text,0,$limit);
				break;
			case 2 :
				$text = $text;
				break;
			default :
				$text = explode(' ',$text);
				$text=implode(' ', array_slice($text,0,$limit));
				break;
		}
		return $text;
	}
}