<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'task.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'support_canned.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'support_ticket.php');

/**
 * Stuff related to archiving and deleting tickets
 **/

class Task_Signature extends Task_Helper
{
	function delete()
	{
		$deleteid = FSS_Input::getInt('deleteid');
		if ($deleteid > 0)
		{
			$qry = "DELETE FROM #__fss_ticket_fragments WHERE id = " . FSSJ3Helper::getEscaped($db, $deleteid);	
			$db = JFactory::getDBO();
			$db->setQuery($qry);
			$db->Query();
		}
		
		$mainframe = JFactory::getApplication();
		$link = JRoute::_('index.php?option=com_fss&view=admin_support&layout=signature&tmpl=component');
		$mainframe->redirect($link);	
	}
	
	function save()
	{	
		// if we are saving, then save
		$saveid = FSS_Input::getInt('saveid', -1);
		
		if ($saveid != -1)
		{
			$description = FSS_Input::getString('description');
			$is_personal = FSS_Input::getInt('personal');
			$content = FSS_Input::getHTML('content');
			
			$params = array();
			
			if ($is_personal)
				$params['userid'] = JFactory::getUser()->id;
			
			$params = json_encode($params);
			
			if ($saveid == 0)
			{
				$qry = "INSERT INTO #__fss_ticket_fragments (description, params, content, type) VALUES (";
				$qry .= "'" . FSSJ3Helper::getEscaped($db, $description) . "',";
				$qry .= "'" . FSSJ3Helper::getEscaped($db, $params) . "',";
				$qry .= "'" . FSSJ3Helper::getEscaped($db, $content) . "', 1)";
				
				$db = JFactory::getDBO();
				$db->setQuery($qry);
				$db->Query();
			} else {
				$qry = "UPDATE #__fss_ticket_fragments SET description = '" . FSSJ3Helper::getEscaped($db, $description) . "', ";
				$qry .= "params = '" . FSSJ3Helper::getEscaped($db, $params) . "', ";
				$qry .= "content = '" . FSSJ3Helper::getEscaped($db, $content) . "' WHERE id = " . FSSJ3Helper::getEscaped($db, $saveid);
				
				$db = JFactory::getDBO();
				$db->setQuery($qry);
				$db->Query();
			}
		}
		
		$mainframe = JFactory::getApplication();
		$link = JRoute::_('index.php?option=com_fss&view=admin_support&layout=signature&tmpl=component', false);
		$mainframe->redirect($link);
	}
	
	function setdefault()
	{
		$sigid = FSS_Input::getInt('sigid');
		
		SupportUsers::updateSingleSetting("default_sig", $sigid);
		
		$mainframe = JFactory::getApplication();
		$link = JRoute::_('index.php?option=com_fss&view=admin_support&layout=signature&tmpl=component', false);
		$mainframe->redirect($link);
	}
	
	function dropdown()
	{
		include $this->view->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin_support'.DS.'snippet'.DS.'_signature_dropdown.php');
		exit;
	}
	
	function preview()
	{
		$this->ticketid = FSS_Input::getInt('ticketid');
		$this->sigid = FSS_Input::getInt('sigid');
		
		$ticket = new SupportTicket();
		$ticket->load($this->ticketid);
		$ticket->loadAll();
		
		$this->ticket = $ticket;

		$this->signature = SupportCanned::AppendSig($this->sigid, $this->ticket);
	
		include $this->view->snippet(JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'views'.DS.'admin_support'.DS.'snippet'.DS.'_signature_preview.php');
		return true;
	}
}