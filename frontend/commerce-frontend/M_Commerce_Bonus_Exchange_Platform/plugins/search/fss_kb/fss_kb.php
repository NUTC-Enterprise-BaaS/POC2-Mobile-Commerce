<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

if (!defined("DS")) define('DS', DIRECTORY_SEPARATOR);

if (file_exists(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'helper.php'))
{
	require_once( JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'helper.php' );

	function &plgSearchFSSKBsAreas()
	{
		static $areas = array(
			'KBs' => 'Knowledge Base'
			);
		return $areas;
	}

	function FSSkbs_stripend($text)
	{
		$replace = array();
		$replace[] = "s";
		$replace[] = "ing";
		$replace[] = "ly";

		$words = explode(" ",$text);
		$out = array();
		foreach ($words as $word)
		{
			foreach ($replace as $rep)
				$word = preg_replace("/{$rep}$/","",$word);
			
			$out[] = $word;
		}
		return implode(" ",$out);
	}

	function plgSearchFSSKBs( $text, $phrase='', $ordering='', $areas=null )
	{
		$db            = JFactory::getDBO();
		$user  = JFactory::getUser(); 
		
		if (is_array( $areas )) {
			if (!array_intersect( $areas, array_keys( plgSearchFSSKBsAreas() ) )) {
				return array();
			}
		}
		
		$plugin = JPluginHelper::getPlugin('search', 'fss_kb');
		if ($plugin)
		{
			$pluginParams = new JParameter( $plugin->params );
		} else {
			$pluginParams = new JParameter( null );
		}
		$limit = $pluginParams->def( 'search_limit', 50 );
		$text = trim( $text );

		if ($text == '') {
			return array();
		}

		$text = FSSkbs_stripend($text);

		$wheres = array();
		switch ($phrase) {
			case 'exact':
				$text          = $db->Quote( '%'.FSSJ3Helper::getEscaped($db,  $text, true ).'%', false );
				$wheres2       = array();
				$wheres2[]   = 'LOWER(a.title) LIKE '.$text;
				$wheres2[]   = 'LOWER(a.body) LIKE '.$text;
				$where                 = '(' . implode( ') OR (', $wheres2 ) . ')';
				break;
			
			case 'all':
			case 'any':
			default:
				$words         = explode( ' ', $text );
				$wheres = array();
				foreach ($words as $word)
				{
					$word          = $db->Quote( '%'.FSSJ3Helper::getEscaped($db,  $word, true ).'%', false );
					$wheres2       = array();
					$wheres2[]   = 'LOWER(a.title) LIKE '.$word;
					$wheres2[]   = 'LOWER(a.body) LIKE '.$word;
					$wheres[]    = implode( ' OR ', $wheres2 );
				}
				$where = '(' . implode( ($phrase == 'all' ? ') AND (' : ') OR ('), $wheres ) . ')';
				break;
		}
		
		switch ( $ordering ) {
			case 'alpha':
				$order = 'a.title ASC';
				break;
			
			case 'oldest':
			case 'popular':
			case 'newest':
			default:
				$order = 'a.title ASC';
		}
		
		$searchFAQs = JText::_("KBS");
		
		$query = 'SELECT a.title AS title,'
			. ' a.body as text, c.title as section, '
			. ' a.kb_cat_id, a.id, created, 2 as browsernav'
			. ' FROM #__fss_kb_art AS a'
			. ' LEFT JOIN #__fss_kb_cat as c ON a.kb_cat_id = c.id';
		
		
		$ow = "( ". $where . ")";
		$where = array();
		$where[] = $ow;
		$where[] = "a.published = 1";
		$where[] = "c.published = 1";
		$where[] = 'a.language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ')';
		$user = JFactory::getUser();
		$where[] = 'a.access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ')';				

		if (count($where) > 0)
			$query .= " WHERE " . implode(" AND ",$where);
		$query .= ' ORDER BY '. $order;
		
		$db->setQuery( $query, 0, $limit );
		$rows = $db->loadObjectList();
		
		foreach($rows as $key => $row) {
			$rows[$key]->href = 'index.php?option=com_fss&view=kb&catid='.$row->kb_cat_id.'&kbartid='.$row->id;
		}
		
		return $rows;
	}

	class plgSearchFSS_KB extends JPlugin
	{
		/**
			* Constructor
			*
			* @access      protected
			* @param       object  $subject The object to observe
			* @param       array   $config  An array that holds the plugin configuration
			* @since       1.5
			*/
		public function __construct(& $subject, $config)
		{
			parent::__construct($subject, $config);
			$this->loadLanguage();
		}

		/**
			* @return array An array of search areas
			*/
		function onContentSearchAreas() {
			static $areas = array(
				'KBs' => 'FSS_SEARCH_KB'
				);
			return $areas;
		}

		/**
			* Weblink Search method
			*
			* The sql must return the following fields that are used in a common display
			* routine: href, title, section, created, text, browsernav
			* @param string Target search string
			* @param string mathcing option, exact|any|all
			* @param string ordering option, newest|oldest|popular|alpha|category
			* @param mixed An array if the search it to be restricted to areas, null if search all
			*/
		function onContentSearch($text, $phrase='', $ordering='', $areas=null)
		{
			$db            = JFactory::getDBO();
			$user  = JFactory::getUser(); 
			
			if (is_array( $areas )) {
				if (!array_intersect( $areas, array_keys( plgSearchFSSKBsAreas() ) )) {
					return array();
				}
			}
			
			$limit			= $this->params->def('search_limit',		50);

			$text = trim( $text );

			if ($text == '') {
				return array();
			}

			$text = FSSkbs_stripend($text);
			
			$wheres = array();
			switch ($phrase) {
				case 'exact':
					$text          = $db->Quote( '%'.FSSJ3Helper::getEscaped($db,  $text, true ).'%', false );
					$wheres2       = array();
					$wheres2[]   = 'LOWER(a.title) LIKE '.$text;
					$wheres2[]   = 'LOWER(a.body) LIKE '.$text;
					$where                 = '(' . implode( ') OR (', $wheres2 ) . ')';
					break;
				
				case 'all':
				case 'any':
				default:
					$words         = explode( ' ', $text );
					$wheres = array();
					foreach ($words as $word)
					{
						$word          = $db->Quote( '%'.FSSJ3Helper::getEscaped($db,  $word, true ).'%', false );
						$wheres2       = array();
						$wheres2[]   = 'LOWER(a.title) LIKE '.$word;
						$wheres2[]   = 'LOWER(a.body) LIKE '.$word;
						$wheres[]    = implode( ' OR ', $wheres2 );
					}
					$where = '(' . implode( ($phrase == 'all' ? ') AND (' : ') OR ('), $wheres ) . ')';
					break;
			}
			
			switch ( $ordering ) {
				case 'alpha':
					$order = 'a.title ASC';
					break;
				
				case 'oldest':
				case 'popular':
				case 'newest':
				default:
					$order = 'a.title ASC';
			}
			
			$searchFAQs = JText::_("KBS");
			
			$query = 'SELECT a.title AS title,'
				. ' a.body as text, c.title as section, '
				. ' a.kb_cat_id, a.id, created, 2 as browsernav'
				. ' FROM #__fss_kb_art AS a'
				. ' LEFT JOIN #__fss_kb_cat as c ON a.kb_cat_id = c.id';
			
			$ow = "( ". $where . ")";
			$where = array();
			$where[] = $ow;
			$where[] = "a.published = 1";
			$where[] = "c.published = 1";
			$where[] = 'a.language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ')';
			$user = JFactory::getUser();
			$where[] = 'a.access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ')';				

			if (count($where) > 0)
				$query .= " WHERE " . implode(" AND ",$where);
			$query .= ' ORDER BY '. $order;

			$db->setQuery( $query, 0, $limit );
			$rows = $db->loadObjectList();
			
			foreach($rows as $key => $row) {
				$rows[$key]->href = 'index.php?option=com_fss&view=kb&catid='.$row->kb_cat_id.'&kbartid='.$row->id;
			}
			
			return $rows;
		}
	}

	jimport('joomla.plugin.plugin');	
}
