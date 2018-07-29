<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view');

class FssViewAdmin_Support_ListHandlers extends FssViewAdmin_Support
{
	function display($tpl = NULL)
	{
		$this->parseRequest();
		
		$this->loadHandlers();
		$this->loadPDC();
		
		$this->loadHandlerDetails();
		$this->loadHandlerTickets();
		
		$this->_display();
	}
	
	function parseRequest()
	{
		$this->prodid = FSS_Input::getInt('prodid');
		$this->deptid = FSS_Input::getInt('deptid');
		$this->catid = FSS_Input::getInt('catid');
		$this->mode = FSS_Input::getInt('mode');
		
		$def_open = FSS_Ticket_Helper::GetStatusID("def_open");
		$this->status = FSS_Input::getCmd('status', $def_open);
	}
	
	function loadHandlers()
	{		
		$allownoauto = false;
		$assign_ticket = true;
		
		if ($this->mode == 0)
		{
			$allownoauto = true;
			$assign_ticket = false;	
		}
		
		$this->handlers = SupportUsers::getHandlersTicket($this->prodid, $this->deptid, $this->catid, $allownoauto, $assign_ticket, false, false);	
	}
	
	function loadHandlerDetails()
	{
		$this->handler_details = array();
		
		foreach ($this->handlers as $handler_id)
		{
			$handler = SupportUsers::getHandler($handler_id, false, false);
			$handler->open_tickets = 0;
			$handler->status_count = 0;
			
			if ($handler)
				$this->handler_details[] = $handler;
		}
	}
	
	function loadHandlerTickets()
	{
		$ids = array();
		foreach ($this->handler_details as $handler)
			$ids[] = $handler->id;

		if (count($ids) < 1)
			return;
		
		$status_list = FSS_Ticket_Helper::GetStatusIDs("is_closed", true); // Get all open ticket status
		if (count($status_list) < 1)
			return;
	
		// load all open status counts
		$qry = "SELECT admin_id, count(*) as cnt FROM #__fss_ticket_ticket WHERE ";
		$qry .= " admin_id IN (" . implode(", ", $ids) . ")";
		$qry .= " AND ticket_status_id IN (" . implode(", ", $status_list) . ")";
		$qry .= " GROUP BY admin_id";
		
		$db = JFactory::getDBO();
		$db->setQuery($qry);
		
		$data = $db->loadObjectList("admin_id");
		
		foreach ($this->handler_details as $handler)
		{
			if (array_key_exists($handler->id, $data))
				$handler->open_tickets = $data[$handler->id]->cnt;	
		}
		
		// load specific status counts
		$qry = "SELECT admin_id, count(*) as cnt FROM #__fss_ticket_ticket WHERE ";
		$qry .= " admin_id IN (" . implode(", ", $ids) . ")";
		$qry .= " AND ticket_status_id = " . (int)$this->status;
		$qry .= " GROUP BY admin_id";
		
		$db = JFactory::getDBO();
		$db->setQuery($qry);
		
		$data = $db->loadObjectList("admin_id");
		
		foreach ($this->handler_details as $handler)
		{
			if (array_key_exists($handler->id, $data))
				$handler->status_count = $data[$handler->id]->cnt;	
		}
	}
	
	function loadPDC()
	{
		$this->products = SupportHelper::getProducts();
		$this->departments = SupportHelper::getDepartments();
		$this->categories = SupportHelper::getCategories();
		
		$prods = array();
		$prods[] = JHTML::_('select.option', '0', JText::_("SELECT_PRODUCT"), 'id', 'title');
		$prods = array_merge($prods, $this->products);
		$this->products_select = JHTML::_('select.genericlist',  $prods, 'prodid', 'class="input-medium" size="1" onchange="document.mainform.submit( );"', 'id', 'title', $this->prodid);
		
		$depts = array();
		$depts[] = JHTML::_('select.option', '0', JText::_("SELECT_DEPARTMENT"), 'id', 'title');
		$depts = array_merge($depts, $this->departments);
		$this->departments_select = JHTML::_('select.genericlist',  $depts, 'deptid', 'class="input-medium" size="1" onchange="document.mainform.submit( );"', 'id', 'title', $this->deptid);
		
		$cats = array();
		$cats[] = JHTML::_('select.option', '0', JText::_("SELECT_CATEGORY"), 'id', 'title');
		$cats = array_merge($cats, $this->categories);
		$this->categories_select = JHTML::_('select.genericlist',  $cats, 'catid', 'class="input-medium" size="1" onchange="document.mainform.submit( );"', 'id', 'title', $this->catid);
		
		$modes = array();
		$modes[] = JHTML::_('select.option', '0', JText::_("VIEW_TICKETS"), 'id', 'title');
		$modes[] = JHTML::_('select.option', '1', JText::_("ASSIGN_TICKETS"), 'id', 'title');
		$this->mode_select = JHTML::_('select.genericlist',  $modes, 'mode', 'class="input-medium" size="1" onchange="document.mainform.submit( );"', 'id', 'title', $this->mode);	
		
		$this->statuss = SupportHelper::getStatuss();
		FSS_Translate_Helper::Tr($this->statuss);
		$this->status_select = JHTML::_('select.genericlist',  $this->statuss, 'status', 'class="input-medium hide" size="1" onchange="document.mainform.submit( );" id="cur_status"', 'id', 'title', $this->status);	
		
		$this->status_obj = FSS_Ticket_Helper::GetStatusByID($this->status);
		FSS_Translate_Helper::TrSingle($this->status_obj);
	}
}