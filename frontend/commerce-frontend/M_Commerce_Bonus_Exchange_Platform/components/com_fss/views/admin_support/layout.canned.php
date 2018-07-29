<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'support_canned.php');

class FssViewAdmin_Support_Canned extends FssViewAdmin_Support
{
	function display($tpl = NULL)
	{
		FSS_Helper::AddSCEditor();

		$editid = FSS_Input::getInt('cannedid', -2);
		if ($editid != -2)
		{
			if ($editid > 0)
			{
				$db = JFactory::getDBO();
				$qry = "SELECT * FROM #__fss_ticket_fragments WHERE id = " . FSSJ3Helper::getEscaped($db, $editid);
				$db->setQuery($qry);
				$this->canned_item = $db->loadObject();
			} else {
				$this->canned_item = new stdClass();
				$this->canned_item->id = 0;
				$this->canned_item->description = "";
				$this->canned_item->grouping = "";
				$this->canned_item->content = "";		
			}
			return $this->_display("edit");	
		}
		
		// if we are saving, then save
		$saveid = FSS_Input::getInt('saveid', -2);
		
		if ($saveid != -2)
		{
			$description = FSS_Input::getString('description');
			$grouping = FSS_Input::getString('grouping');
			$content = FSS_Input::getHTML('content');
			
			if ($saveid == 0)
			{
				$qry = "INSERT INTO #__fss_ticket_fragments (description, grouping, content, type) VALUES (";
				$qry .= "'" . FSSJ3Helper::getEscaped($db, $description) . "',";
				$qry .= "'" . FSSJ3Helper::getEscaped($db, $grouping) . "',";
				$qry .= "'" . FSSJ3Helper::getEscaped($db, $content) . "', 0)";
				
				$db = JFactory::getDBO();
				$db->setQuery($qry);
				$db->Query();
			} else {
				$qry = "UPDATE #__fss_ticket_fragments SET description = '" . FSSJ3Helper::getEscaped($db, $description) . "', ";
				$qry .= "grouping = '" . FSSJ3Helper::getEscaped($db, $grouping) . "', ";
				$qry .= "content = '" . FSSJ3Helper::getEscaped($db, $content) . "' WHERE id = " . FSSJ3Helper::getEscaped($db, $saveid);
				
				$db = JFactory::getDBO();
				$db->setQuery($qry);
				$db->Query();
			}
			
			$mainframe = JFactory::getApplication();
			$link = JRoute::_('index.php?option=com_fss&view=admin_support&layout=canned&tmpl=component', false);
			$mainframe->redirect($link);
		}
		// if we are editing then show edit
		
		// otherwise show list
		
		$deleteid = FSS_Input::getInt('deleteid');
		if ($deleteid > 0)
		{
			$qry = "DELETE FROM #__fss_ticket_fragments WHERE id = " . FSSJ3Helper::getEscaped($db, $deleteid);	
			$db = JFactory::getDBO();
			$db->setQuery($qry);
			$db->Query();
		}
		
		$search = FSS_Input::getString('search');

		if ($search)
		{
			$db = JFactory::getDBO();
			$qry = "SELECT * FROM #__fss_ticket_fragments WHERE type = 0 AND (description LIKE '%" . $db->escape($search) . "%' OR content LIKE '%" . $db->escape($search) . "%')";
			$db->setQuery($qry);
			$this->canned = $db->loadObjectList();
		} else {
			$this->canned = SupportCanned::GetCannedReplies();
		}
		
		$this->_display("list");
	}
}