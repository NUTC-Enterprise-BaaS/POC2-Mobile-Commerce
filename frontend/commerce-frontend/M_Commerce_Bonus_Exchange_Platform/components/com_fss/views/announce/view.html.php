<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @deprecated bf5f0846b4d37d040eff1580e8ce12a5
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view');
jimport('joomla.utilities.date');

require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'comments.php');
require_once(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'glossary.php');

class FssViewAnnounce extends FSSView
{
    function display($tpl = null)
    {
		if (!FSS_Permission::auth("fss.view", "com_fss.announce"))
			return FSS_Helper::NoPerm();	
		
		$mainframe = JFactory::getApplication();
        $aparams = $mainframe->getPageParameters('com_fss');
        
		$announceid = FSS_Input::getInt('announceid'); 
		
 		require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'content'.DS.'announce.php');
		$this->content = new FSS_ContentEdit_Announce();
		$this->content->Init();
 		
		$model = $this->getModel();
		$model->content = $this->content;
      
        if ($announceid)
        {
			$tmpl = FSS_Input::getCmd('tmpl'); 
            $this->tmpl = $tmpl ;
            $this->setLayout("announce");
            $this->announce = $this->get("Announce");
            
			if (!$this->announce)
			{
				$mainframe->redirect(FSSRoute::_("index.php?option=com_fss&view=announce", false));
			}
			
            $pathway = $mainframe->getPathway();
			if (FSS_Helper::NeedBaseBreadcrumb($pathway, array( 'view' => 'announce' )))	
				$pathway->addItem(JText::_("ANNOUNCEMENTS"), FSSRoute::_( '&limitstart=announceid=' ) ); // FIX LINK
            $pathway->addItem($this->announce['title']);
 
  			$this->comments = new FSS_Comments('announce',$announceid);
			$this->comments->PerPage(FSS_Settings::Get('announce_comments_per_page'));
			if ($this->comments->Process())
				return;			

			if (FSS_Settings::get('announce_use_content_plugins'))
			{
				// apply plugins to article body
				$dispatcher	= JDispatcher::getInstance();
				JPluginHelper::importPlugin('content');
				$art = new stdClass;
				$art->text = $this->announce['body'];
				$art->noglossary = 1;
				
				$this->params = $mainframe->getParams('com_fss');
				
				$results = $dispatcher->trigger('onContentPrepare', array ('com_fss.announce', &$art, &$this->params, 0));
				$results = $dispatcher->trigger('onContentBeforeDisplay', array ('com_fss.announce', &$art, &$this->params, 0));
				
				$this->announce['body'] = $art->text;
				if ($this->announce['fulltext'])
				{
					$art->text = $this->announce['fulltext'];
					$art->noglossary = 1;
					$results = $dispatcher->trigger('onContentPrepare', array ('com_fss.announce.fulltext', &$art, &$this->params, 0));
					$results = $dispatcher->trigger('onContentBeforeDisplay', array ('com_fss.announce.fulltext', &$art, &$this->params, 0));
					$this->announce['fulltext'] = $art->text;
				}
			}
			
			$this->parser = new FSSParser();
			$type = FSS_Settings::Get('announcesingle_use_custom') ? 2 : 3;
			$this->parser->Load("announcesingle", $type); 

        	parent::display($tpl);
        	return;
		}
	
        $pathway = $mainframe->getPathway();
		if (FSS_Helper::NeedBaseBreadcrumb($pathway, array( 'view' => 'announce' )))
			$pathway->addItem(JText::_("ANNOUNCEMENTS"));

        $this->announces = $this->get('Announces');
        $this->pagination = $this->get('Pagination');
  
		if (FSS_Settings::get('announce_use_content_plugins_list'))
		{
			// apply plugins to article body
			$dispatcher	= JDispatcher::getInstance();
			JPluginHelper::importPlugin('content');
			$art = new stdClass;
			
			foreach ($this->announces as &$item)
			{
				$art->text = $item['body'];
				$art->noglossary = 1;
				$this->params = $mainframe->getParams('com_fss');
				$results = $dispatcher->trigger('onContentPrepare', array ('com_fss.announce', & $art, &$this->params, 0));
				$results = $dispatcher->trigger('onContentBeforeDisplay', array ('com_fss.announce', & $art, &$this->params, 0));
				$item['body'] = $art->text;

			}
		}     
		
		$this->comments = new FSS_Comments('announce',null,$this->announces);

		$this->parser = new FSSParser();
		$type = FSS_Settings::Get('announce_use_custom') ? 2 : 3;
		$this->parser->loadTemplate("announce", $type); 
		
		if (FSS_Input::getCmd('feed') == "rss")
		{
			//header("Content-Type: text/xml");
			header("Content-Type: application/xml; charset=UTF-8");
			parent::display("rss");
			exit;
		} else {
			parent::display($tpl);
		}
    }
}

