<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view');

class FssViewAdmin_Support_New extends FssViewAdmin_Support
{
	function display($tpl = NULL)
	{
		$type = FSS_Input::getCmd('type');
		
		$session = JFactory::getSession();
		$session->clear('admin_create');
		$session->clear('admin_create_user_id');
		$session->clear('ticket_email');
		$session->clear('ticket_name');	
		$session->clear('ticket_reference');
		
		if ($type == "registered")
			return $this->displayRegistered();
		
		if ($type == "unregistered")
			return $this->displayUnRegistered();
		
		$this->_display();
	}
	
	function displayRegistered()
	{
		if (FSS_Settings::get('support_no_admin_for_user_open'))
			JFactory::getApplication()->redirect("index.php?option=com_fss&view=admin_support");

		FSS_Helper::IncludeModal();
		$this->_display("registered");	    
	}
	
	function displayUnRegistered()
	{
		if (FSS_Settings::get('support_no_admin_for_user_open'))
			JFactory::getApplication()->redirect("index.php?option=com_fss&view=admin_support");

		$this->_display("unregistered");	    
	}	
}	 	 	    	 	 