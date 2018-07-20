<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @deprecated c2ad275f393506d14de7f584aefcbd9c
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view');
jimport('joomla.utilities.date');

class FssViewGlossary extends FSSView
{
    function display($tpl = null)
    {
		if (!FSS_Permission::auth("fss.view", "com_fss.glossary"))
			return FSS_Helper::NoPerm();	

		$mainframe = JFactory::getApplication();
        
        $db = JFactory::getDBO();

        $aparams = FSS_Settings::GetViewSettingsObj('glossary');
		$this->use_letter_bar = $aparams->get('use_letter_bar',0);
		$this->show_search = $aparams->get('show_search',0);
		$this->long_desc = $aparams->get('long_desc',0);
		$this->subtitle = null;
		
		$this->search = JRequest::getVar('search');
		
		if ($this->search) return $this->doSearch();
	
		if ($this->use_letter_bar)
		{
			$this->letters = array();
			if (FSS_Settings::get('glossary_all_letters'))
			{
				$letters = array(
					'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M',
					'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'
					);
				foreach ($letters as $letter)
					$this->letters[$letter] = 0;				
			}
			
			$qry = "SELECT UPPER(SUBSTR(word,1,1)) as letter FROM #__fss_glossary";
			$where = array();
		
			$where[] = "published = 1";
			$where[] = 'language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ')';
			$user = JFactory::getUser();
			$where[] = 'access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ')';				

			if (count($where) > 0)
				$qry .= " WHERE " . implode(" AND ",$where);
		
			$qry .= " GROUP BY letter ORDER BY letter";
			$db->setQuery($qry);
			$letters = $db->loadObjectList();
			
			foreach ($letters as $letter)
				$this->letters[$letter->letter] = 1;
	
			if (count($this->letters) == 0)
			{
				return parent::display("empty");	
			}
		}
				
		$this->curletter = "";
		
		// if we are showing on a per letter basis only

		if ($this->use_letter_bar == 2)
		{
			reset($this->letters);
			$this->curletter = FSS_Input::getString('letter',key($this->letters));	
		}
		
		$this->subtitle = strtoupper($this->curletter);

		if (FSS_Input::getCmd('layout') == "word")	
			return $this->showWord();
			
		$where = array();
		$where[] = "published = 1";
        $query = "SELECT * FROM #__fss_glossary";
		if ($this->curletter)
		{
			$where[] = "SUBSTR(word,1,1) = '".FSSJ3Helper::getEscaped($db, $this->curletter)."'";
		}
		
		$where[] = 'language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ')';
		$user = JFactory::getUser();
		$where[] = 'access IN (' . implode(',', $user->getAuthorisedViewLevels()) . ')';				
		
		if (count($where) > 0)
			$query .= " WHERE " . implode(" AND ",$where);
	
		$query .= " ORDER BY word";
        $db->setQuery($query);
        $this->rows = $db->loadObjectList();
  
        $pathway = $mainframe->getPathway();
		if (FSS_Helper::NeedBaseBreadcrumb($pathway, array( 'view' => 'glossary' )))
			$pathway->addItem("Glossary");

		if (FSS_Settings::get('glossary_use_content_plugins'))
		{
			// apply plugins to article body
			$dispatcher	= JDispatcher::getInstance();
			JPluginHelper::importPlugin('content');
			$art = new stdClass;

			$this->params = $mainframe->getParams('com_fss');
			foreach ($this->rows as &$row)
			{
				if ($row->description)
				{
					$art->text = $row->description;
					$art->noglossary = 1;
					
					$results = $dispatcher->trigger('onContentPrepare', array ('com_fss.glossary', &$art, &$this->params, 0));
					$results = $dispatcher->trigger('onContentBeforeDisplay', array ('com_fss.glossary', &$art, &$this->params, 0));
					$row->description = $art->text;
				}
				if ($row->longdesc)
				{
					$art->text = $row->longdesc;
					$art->noglossary = 1;
					$results = $dispatcher->trigger('onContentPrepare', array ('com_fss.glossary.long', & $art, &$this->params, 0));
					$results = $dispatcher->trigger('onContentBeforeDisplay', array ('com_fss.glossary.long', & $art, &$this->params, 0));
					$row->longdesc = $art->text;
				}
			}
		}    
		
		FSS_Helper::IncludeModal();
		   	
  		parent::display($tpl);
    }
	
	function showWord()
	{
		$db = JFactory::getDBO();

		$word_id = FSS_Input::getString('word');
		if (is_numeric($word_id))
		{
			$qry = "SELECT * FROM #__fss_glossary WHERE id = '" . $db->escape($word_id) . "'";
		} else {
			$word = FSS_Input::getString('word');
			$word = urldecode($word);
			$qry = "SELECT * FROM #__fss_glossary WHERE word = '" . $word . "'";
		}

		$db->setQuery($qry);
		$this->glossary = $db->loadObject();
			
		if (FSS_Input::getCmd('tmpl') == 'component')
		{
			parent::display('modal');	
		} else {
			parent::display();	
		}
	}
	
	function doSearch()
	{
		$db = JFactory::getDBO();

		$this->fields = array();
		$this->order = "";
		$filter = $this->createWeightedSearchFilter(array('word' => 100, 'altwords' => 50, 'description' => 10, 'longdesc' => 5), $this->search, $this, false);	
	
		$query = "SELECT *";
		foreach ($this->fields as $field)
			$query .= ", $field ";
		$query .= " FROM #__fss_glossary WHERE ";
		
		$wherebits[] = $filter;
		$wherebits[] = " published = 1 ";
		$wherebits[] = ' language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ') ';
		$wherebits[].= ' access IN (' . implode(',', JFactory::getUser()->getAuthorisedViewLevels()) . ') ';				

		$query .= implode(" AND ", $wherebits);
		
		$query .= " ORDER BY " . $this->order;
	
		//echo $query . "<Br />";
		$db->setQuery($query);
		$this->rows = $db->loadObjectList();

		if (count($this->rows) == 0)
		{
			// try fallback	

			$this->fields = array();
			$this->order = "";
			$filter = $this->createWeightedSearchFilter(array('word' => 100, 'altwords' => 50, 'description' => 10, 'longdesc' => 5), $this->search, $this, true);	
			
			$query = "SELECT *";
			foreach ($this->fields as $field)
				$query .= ", $field ";
			$query .= " FROM #__fss_glossary WHERE ";
			
			$wherebits = array();
			
			$wherebits[] = $filter;
			$wherebits[] = " published = 1 ";
			$wherebits[] = ' language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ') ';
			$wherebits[].= ' access IN (' . implode(',', JFactory::getUser()->getAuthorisedViewLevels()) . ') ';				

			$query .= implode(" AND ", $wherebits);
			
			$query .= " ORDER BY word";
			
			//echo $query . "<Br />";
			$db->setQuery($query);
			$this->rows = $db->loadObjectList();
		}
		
		if (FSS_Settings::get('glossary_use_content_plugins'))
		{
			// apply plugins to article body
			$mainframe = JFactory::getApplication();
	
			$dispatcher	= JDispatcher::getInstance();
			JPluginHelper::importPlugin('content');
			$art = new stdClass;

			$this->params = $mainframe->getParams('com_fss');
			foreach ($this->rows as &$row)
			{
				if ($row->description)
				{
					$art->text = $row->description;
					$art->noglossary = 1;
					
					$results = $dispatcher->trigger('onContentPrepare', array ('com_fss.glossary', &$art, &$this->params, 0));
					$results = $dispatcher->trigger('onContentBeforeDisplay', array ('com_fss.glossary', &$art, &$this->params, 0));
					$row->description = $art->text;
				}
				if ($row->longdesc)
				{
					$art->text = $row->longdesc;
					$art->noglossary = 1;
					$results = $dispatcher->trigger('onContentPrepare', array ('com_fss.glossary.long', & $art, &$this->params, 0));
					$results = $dispatcher->trigger('onContentBeforeDisplay', array ('com_fss.glossary.long', & $art, &$this->params, 0));
					$row->longdesc = $art->text;
				}
			}
		}  
		
		$this->use_letter_bar = 0;
		$this->subtitle = JText::_("Search");
		
		FSS_Helper::IncludeModal();
		
		parent::display();
	}
	
	static function createWeightedSearchFilter($fields, $search, &$model, $fallback = false, $mode = 'any')
	{
		$db = JFactory::getDBO();
		
		if (!$fallback) // full text search on $fields
		{		
			//echo "Using FT<br>";
			// add score as a field so we can sort by it
			
			$flist = array();
			
			$relevance = array();
			
			foreach ($fields as $fieldname => $weight)
			{	
				$flist[] = $fieldname;
				$field_key = self::makeFieldKey($fieldname);
				$model->fields[] = "MATCH (" . $fieldname . ") AGAINST ('" . $db->escape($search) . "') AS search_score_$field_key";		
				
				$relevance[] = "(search_score_" . $field_key . " * " . $weight . ")";	
			}
			
			$model->order = implode(" + ", $relevance) . " DESC";

			return "MATCH (" . implode(", ", $flist). ") AGAINST ('" . $db->escape($search) . "')";
		} else {	
			//echo "Using Like<br>";
			
			// put newest results first
			$model->order = "modified DESC";
			
			$words = explode(" ", $search);
			
			$parts = array();
			foreach ($words as $word)
			{
				$word = trim($word);
				if (!$word) continue;
				
				$field_bits = array();
				
				foreach ($fields as $field => $weight)
				{
					$field_bits[] = "(" . $field . " LIKE '%" . $db->escape($word) . "%' )";
				}
				
				$parts[] = 	"( " . implode(" OR ", $field_bits) . " )";
			}
			
			if (count($parts) < 1) return " 0 ";	
			
			return " ( " . implode(" AND ", $parts) . " ) ";	
		}
	}
	
	static function makeFieldKey($name)
	{
		return preg_replace("/[^A-Za-z0-9]/", '', $name);
	}
}

