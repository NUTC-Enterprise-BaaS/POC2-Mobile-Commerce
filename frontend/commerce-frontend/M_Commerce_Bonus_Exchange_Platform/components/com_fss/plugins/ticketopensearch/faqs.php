<?php

class FSS_Plugin_OpenSearch_FAQs extends FSS_Plugin_OpenSearch
{
	var $title = "FAQs";
	var $description = "Search FAQs when opening a ticket";
	
	function search($search)
	{
		$db = JFactory::getDBO();
		
		$mode = "";
		if (FSS_Helper::contains($search, array('*', '+', '-', '<', '>', '(', ')', '~', '"'))) $mode = "IN BOOLEAN MODE";
		$search_sql = "MATCH (question, answer) AGAINST ('" . $db->escape($search) . "' $mode)";

		//$query = "SELECT *, $search_sql as score FROM #__fss_faq_faq";
		$query = "SELECT *, MATCH (question) AGAINST ('" . $db->escape($search) . "' $mode) as score_1, MATCH (answer) AGAINST ('" . $db->escape($search) . "' $mode) as score_2 FROM #__fss_faq_faq";
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
				
				$wsearch[] = " question LIKE ('%" . $db->escape($word) . "%') OR answer LIKE ('%" . $db->escape($word) . "%') ";
			}			
			$new .= implode(" OR ", $wsearch);
			$new .= " ) ";
			
			$where[] = $new;
		} else {
			$where[] = $search_sql;
		}
		
		$user = JFactory::getUser();
		
		if (FSS_Permission::auth("core.edit", "com_fss.faq")) // we have editor so can see all unpublished arts
		{
			
		} else if (FSS_Permission::auth("core.edit.own", "com_fss.faq") && $user->id > 0){
			$where[] = " ( published = 1 OR author = " . $user->id . " ) ";	
		} else {
			$where[] = "published = 1";	
		}
		
		$where[] = 'language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ')';
		$user = JFactory::getUser();
		$where[] = 'access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ')';				

		if (count($where) > 0) $query .= " WHERE " . implode(" AND ",$where);

		$query .= " ORDER BY ordering";

		$db->setQuery($query);
		$results = $db->loadObjectList();
		
		$final = array();
		
		foreach ($results as $result)
		{
			$output = new FSS_OpenSearch_Result();
			$output->title = $result->question;
			$output->type = JText::_("FAQ");
			$output->link =  FSSRoute::_( 'index.php?option=com_fss&view=faq&tmpl=component&faqid=' . $result->id );
			$output->score = $result->score_1 * 100 + $result->score_2 * 25;
			
			$final[] = $output;
		}	
		
		return $final;	
	}	
}