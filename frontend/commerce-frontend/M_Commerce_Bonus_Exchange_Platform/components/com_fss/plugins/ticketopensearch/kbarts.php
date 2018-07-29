<?php

class FSS_Plugin_OpenSearch_KBArts extends FSS_Plugin_OpenSearch
{
	var $title = "KB";
	var $description = "Search KB Articles when opening a ticket";
	
	function search($search)
	{
		$prodid = 0;
		$db = JFactory::getDBO();
		$user = JFactory::getUser();

		$mode = "";
		if (FSS_Helper::contains($search, array('*', '+', '-', '<', '>', '(', ')', '~', '"'))) $mode = "IN BOOLEAN MODE";
		$search_sql = " MATCH (title, body) AGAINST ('" . $db->escape($search) . "' $mode) ";
		
		$where1 = array();
		$where2 = array();
		$where = array();
		
		if (FSS_Settings::get('search_extra_like'))
		{
			$new = " ( " . $search_sql . " OR ";
			
			$words = explode(" ", $search);
			$wsearch = array();
			foreach ($words as $word)
			{
				$word = trim($word);
				if (!$word) continue;
				
				$wsearch[] = " title LIKE ('%" . $db->escape($word) . "%') OR body LIKE ('%" . $db->escape($word) . "%') ";
			}			
			$new .= implode(" OR ", $wsearch);
			$new .= " ) ";
			
			$where[] = $new;
		} else {
			$where[] = $search_sql;
		}
		
		// stuff to show extra arts when have edit permission
		if (FSS_Permission::auth("core.edit", "com_fss.kb")) // we have editor so can see all unpublished arts
		{
			
		} else if (FSS_Permission::auth("core.edit.own", "com_fss.kb")){
			$where[] = " ( published = 1 OR author = {$user->id} ) ";	
		} else {
			$where[] = "published = 1";	
		}
		$where[] = 'language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ')';
		$where[] = 'access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ')';				
		
		$base_sql = "SELECT a.*, $search_sql as score ";
		$base_sql .= ", MATCH (title) AGAINST ('" . $db->escape($search) . "' $mode) as score_1, MATCH (body) AGAINST ('" . $db->escape($search) . "' $mode) as score_2";
		$base_sql .= " FROM #__fss_kb_art as a ";

		// per product kb search
		if ($prodid > 0)
		{
			$where1[] = " a.id IN (SELECT kb_art_id FROM #__fss_kb_art_prod WHERE prod_id = " . FSSJ3Helper::getEscaped($db, $prodid) . ") ";
			$query1 = $base_sql;
			
			if (count($where) > 0 || count($where1) > 0) $query1 .= " WHERE " . implode(" AND ",array_merge($where, $where1));

			// all product kb search
			$where2[] = "a.allprods = 1";
			$query2 = $base_sql;
			
			if (count($where) > 0 || count($where2) > 0) $query2 .= " WHERE " . implode(" AND ",array_merge($where, $where2));

			$query = "(" . $query1 . ") UNION (" . $query2 . ") ORDER BY score DESC LIMIT 20";

		} else {
			
			$query = $base_sql;

			if (count($where) > 0) $query .= " WHERE " . implode(" AND ", $where);
			$query .= " ORDER BY score DESC LIMIT 20 ";
		}
		
		$db->setQuery($query);
		$results = $db->loadObjectList();
		
		$final = array();
		
		foreach ($results as $result)
		{
			$output = new FSS_OpenSearch_Result();
			$output->title = $result->title;
			$output->type = JText::_("KB_ARTICLE");
			$output->link = FSSRoute::_( 'index.php?option=com_fss&view=kb&tmpl=component&kbartid=' . $result->id );
			$output->score = $result->score_1 * 100 + $result->score_2 * 25;
			
			$final[] = $output;
		}	
		
		return $final;
	}	
}