<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @deprecated 10358cd2cfb74b21957bb8d6d4db44e5
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view');

require_once(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'glossary.php');
require_once(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'multicol.php');

class FssViewFaq extends FSSView
{
	function display($tpl = null)
	{
		if (!FSS_Permission::auth("fss.view", "com_fss.faq"))
		return FSS_Helper::NoPerm();	
		
		require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'content'.DS.'faqs.php');
		$this->content = new FSS_ContentEdit_FAQs();
		$this->content->Init();
		
		$model = $this->getModel();
		$model->content = $this->content;
		
		$mainframe = JFactory::getApplication();
		
		$faqid = FSS_Input::getInt('faqid'); 
		
		$aparams = FSS_Settings::GetViewSettingsObj('faqs');
		
		if ($faqid > 0)
		{
			$tmpl = FSS_Input::getCmd('tmpl'); 
			$this->tmpl = $tmpl;
			$this->setLayout("faq");
			$faq = $this->get("Faq");
			$this->faq = $faq;
			
			if (!$this->faq)
			{
				return JError::raiseWarning(404, JText::_('FAQ_NOT_FOUND'));	
			}
			
			$pathway = $mainframe->getPathway();
			if (FSS_Helper::NeedBaseBreadcrumb($pathway, array( 'view' => 'faq' )))	
			$pathway->addItem(JText::_('FREQUENTLY_ASKED_QUESTIONS'), FSSRoute::_( 'index.php?option=com_fss&view=faq' ) );
			$pathway->addItem($faq['title'], FSSRoute::_( '&limitstart=&layout=&faqid=&catid=' . $faq['faq_cat_id'] ) );// FIX LINK
			$pathway->addItem($faq['question']);
			
			if (FSS_Settings::get('faq_use_content_plugins'))
			{
				// apply plugins to article body
				$dispatcher	= JDispatcher::getInstance();
				JPluginHelper::importPlugin('content');
				$art = new stdClass;
				$art->text = $faq['answer'];
				$art->noglossary = 1;
				
				$this->params = $mainframe->getParams('com_fss');
				
				$results = $dispatcher->trigger('onContentPrepare', array('com_fss.faq', &$art, &$this->params, 0));
				$results = $dispatcher->trigger('onContentBeforeDisplay', array('com_fss.faq', &$art, &$this->params, 0));				
				$this->faq['answer'] = $art->text;
			}

			// load tags
			$db	= JFactory::getDBO();
			
			$qry = "SELECT * FROM #__fss_faq_tags WHERE faq_id IN (" . FSSJ3Helper::getEscaped($db, $faqid) .") GROUP BY tag ORDER BY tag";
			$db->setQuery($qry);
			$rows = $db->loadObjectList();
			
			$this->tags = array();
			foreach ($rows as &$row)
			{
				$id = $row->faq_id;
				
				$this->tags[] = "<a href='" . FSSRoute::_('index.php?option=com_fss&view=faq&tag=' . urlencode($row->tag) . '&Itemid=' . FSS_Input::getInt('Itemid')) . "'>{$row->tag}</a>";
			}		
			//$document = JFactory::getDocument();
			//$document->setTitle(JText::_("FAQS") . ' - ' . $faq['title']);
			
			if (FSS_Input::getCmd('tmpl') == "component") return parent::display("popup");
			
			parent::display();    
			return;
		}  
		
		$pathway = $mainframe->getPathway();
		if (FSS_Helper::NeedBaseBreadcrumb($pathway, array( 'view' => 'faq' )))	
		$pathway->addItem(JText::_('FREQUENTLY_ASKED_QUESTIONS'), FSSRoute::_( 'index.php?option=com_fss&view=faq' ) );
		
		$hide_allfaqs = $aparams->get('hide_allfaqs',0);
		$show_featured = $aparams->get('show_featured',0);
		$hide_tags = $aparams->get('hide_tags',0);
		$hide_search = $aparams->get('hide_search',0);
		$view_mode = $aparams->get('view_mode','questionwithpopup');
		$view_mode_cat = $aparams->get('view_mode_cat','list');
		$view_mode_incat = $aparams->get('view_mode_incat','list');
		$enable_pages = $aparams->get('enable_pages',1);
		
		$num_cat_colums = $aparams->get('num_cat_colums',1);
		
		if ($num_cat_colums < 1 && !$num_cat_colums) $num_cat_colums = 1;
		if ($view_mode_cat != "list") $num_cat_colums = 1;
		
		$catlist = $this->get("CatList");
		$search = $this->get("Search");
		$curcatdesc = $this->get("CurCatDesc");
		$curcatimage = $this->get("CurCatImage");
		$curcattitle = $this->get("CurCatTitle");

		$curcatid = $this->get("CurCatID");

		// Get data from the model
		
		if ($curcatid == -4)
		return $this->listTags();

		if ($curcatid == -5)
		{
			$curcattitle = JText::_("FEATURED_FAQS");
			$curcatimage = "/components/com_fss/assets/images/featured.png";
		}
		
		$pagination = $this->get('Pagination');
		$model = $this->getModel();
		
		$search = $model->_search;
		if ($search || $curcatid > 0 || FSS_Input::getInt('catid') != "" || FSS_Input::getString('tag') != "")
		$view_mode_cat = "";
		
		if ($search) FSS_Helper::allowBack();
		
		$items = array();
		
		if ($view_mode_cat == "inline" || $view_mode_cat == "accordian")
		{
			//echo "Getting all data!<br>";
			$alldata = $this->get("AllData");
			
			if (FSS_Settings::get('faq_use_content_plugins_list'))
			{
				// apply plugins to article body
				$dispatcher	= JDispatcher::getInstance();
				JPluginHelper::importPlugin('content');
				$art = new stdClass;
				foreach ($alldata as &$item)
				{
					$art->text = $item['answer'];
					$art->noglossary = 1;
					$this->params = $mainframe->getParams('com_fss');
					$results = $dispatcher->trigger('onContentPrepare', array ('com_fss.faq', &$art, &$this->params, 0));
					$results = $dispatcher->trigger('onContentBeforeDisplay', array ('com_fss.faq', &$art, &$this->params, 0));
					$item['answer'] = $art->text;
				}
			}

			foreach ($catlist as &$cat)
			{
				$catid = $cat['id'];
				foreach ($alldata as $faq)
				{
					if ($faq['faq_cat_id'] == $catid)
					{
						$cat['faqs'][] = $faq;
					}	
				}
			}  	
			
			// inline mode, so if we have featured faqs listed, load the data for those
			$this->featured_faqs = $this->get('FeaturedFaqs');
			
		} else {
			
			$items = $this->get('Data');

			if (FSS_Settings::get('faq_use_content_plugins_list'))
			{
				// apply plugins to article body
				$dispatcher	=& JDispatcher::getInstance();
				JPluginHelper::importPlugin('content');
				$art = new stdClass;
				foreach ($items as &$item)
				{
					$art->text = $item['answer'];
					$art->noglossary = 1;
					$this->params =& $mainframe->getParams('com_fss');
					
					$results = $dispatcher->trigger('onContentPrepare', array ('com_fss.faq', & $art, &$this->params, 0));
					$results = $dispatcher->trigger('onContentBeforeDisplay', array ('com_fss.faq', & $art, &$this->params, 0));
					$item['answer'] = $art->text;
				}
			}
		}

		$showfaqs = true;
		$showcats = true;
		
		if (FSS_Input::getString('tag') != "") {
			
			// got tag selected
			$showfaqs = true;
			$showcats = false;
			$curcatid = -2;
			$pathway = $mainframe->getPathway();
			$pathway->addItem(JText::_("TAGS"), FSSRoute::_('index.php?option=com_fss&view=faq&catid=-4&Itemid=' . FSS_Input::getInt('Itemid')));
			$pathway->addItem(FSS_Input::getString('tag'));
			$curcattitle = FSS_Input::getString('tag');
			// do we have a category specified???
			
		} else if (FSS_Input::getInt('catid', '') == '' && FSS_Input::getInt('search', '') == '')
		{
			// no cat specified
			$showfaqs = false;
			$curcatid = -2;

		} else {
			// got a cat specced
			$pathway = $mainframe->getPathway();
			$pathway->addItem($curcattitle);
			$showcats = false;   
		}

		// load tags
		$faqids = array();
		if ($items && is_array($items))
		foreach ($items as &$item) 
			$faqids[] = FSSJ3Helper::getEscaped($db,  $item['id']);
		$db	= JFactory::getDBO();
		
		$this->tags = array();
		if (count($faqids) > 0)
		{
			$qry = "SELECT * FROM #__fss_faq_tags WHERE faq_id IN (" . implode(", ",$faqids) .") GROUP BY tag ORDER BY tag";
			$db->setQuery($qry);
			$rows = $db->loadObjectList();
			
			foreach ($rows as $row)
			{
				$id = $row->faq_id;
				
				if (!array_key_exists($id, $this->tags))
				$this->tags[$id] = array();
				
				$this->tags[$id][] = "<a href='" . FSSRoute::_('index.php?option=com_fss&view=faq&tag=' . urlencode($row->tag) . '&Itemid=' . FSS_Input::getInt('Itemid')) . "'>{$row->tag}</a>";
			}
		}
		
		// hide tags if none have been set
		$qry = "SELECT count(*) as cnt FROM #__fss_faq_tags";
		$db->setQuery($qry);
		$row = $db->loadObject();
		if ($row->cnt == 0)
		$hide_tags = true;

		$this->catlist = $catlist;
		$this->search = $search;     
		$this->curcattitle = $curcattitle;
		$this->curcatimage = $curcatimage;
		$this->curcatdesc = $curcatdesc;

		// push data into the template
		
		$this->pagination = $pagination;
		$this->items = $items;
		
		$this->assign( 'curcatid', $curcatid );
		
		$this->assign('showcats', $showcats);
		$this->assign('showfaqs', $showfaqs);
		$this->assign('hide_allfaqs', $hide_allfaqs);
		$this->assign('show_featured', $show_featured);
		$this->assign('hide_tags', $hide_tags);
		$this->assign('hide_search', $hide_search);
		$this->assign('view_mode', $view_mode);
		$this->assign('num_cat_colums', $num_cat_colums);
		$this->assign('view_mode_cat', $view_mode_cat);
		$this->assign('view_mode_incat', $view_mode_incat);
		$this->assign('enable_pages', $enable_pages);

		
		$db	= JFactory::getDBO();
		$qry = "SELECT tag FROM #__fss_faq_tags ";
		$qry .= ' WHERE language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ')';
		$qry .= "GROUP BY tag ORDER BY tag";
		$db->setQuery($qry);
		
		$this->all_tags = $db->loadObjectList();

		FSS_Helper::IncludeModal();
		
		parent::display($tpl);
	}
	
	function listTags()
	{
		$mainframe = JFactory::getApplication();
		$aparams = FSS_Settings::GetViewSettingsObj('faqs');
		
		$pathway = $mainframe->getPathway();
		$pathway->addItem(JText::_("TAGS"));
		
		$db	= JFactory::getDBO();
		$qry = "SELECT tag FROM #__fss_faq_tags ";
		$qry .= ' WHERE language in (' . $db->Quote(JFactory::getLanguage()->getTag()) . ',' . $db->Quote('*') . ')';
		$qry .= "GROUP BY tag ORDER BY tag";
		$db->setQuery($qry);
		
		$this->tags = $db->loadObjectList();
		
		parent::display("tags");		
	}	

	function notEnoughArticles()
	{
		if (FSS_Settings::get('search_extra_like')) return false;
		
		$db = JFactory::getDBO();
		$sql = "SELECT count(*) as cnt FROM #__fss_faq_faq";
		$db->setQuery($sql);
		$result = $db->loadObject();
		if ($result->cnt < 4) return true;
		
		return false;
	}
}
