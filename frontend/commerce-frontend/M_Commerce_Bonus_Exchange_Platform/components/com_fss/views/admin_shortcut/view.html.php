<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @deprecated 3075a3b6515e2328e1f63bf99ddf1d4b
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view');

class FssViewAdmin_Shortcut extends FSSView
{

	function display($tpl = null)
	{
		FSS_Helper::noBots();
		FSS_Helper::noCache();
		
		$sc = FSS_Input::getCmd('shortcut');
		$status = FSS_Input::getCmd('status');
		
		$link = "index.php?option=com_fss&view=admin";
		
		switch ($sc)
		{
			case "create.registered":
				$link = "index.php?option=com_fss&view=admin_support&layout=new&type=registered";
				break;
			case "create.unregistered":
				$link = "index.php?option=com_fss&view=admin_support&layout=new&type=unregistered";
				break;
				
			// Lookup status from advanced tab for these!
			case "tickets.mine":
				$link = "index.php?option=com_fss&view=admin_support&tickets=-1&what=search&searchtype=advanced&showbasic=1&handler=-1&status=$status";
				break;
				
			case "tickets.other":
				$link = "index.php?option=com_fss&view=admin_support&tickets=-1&what=search&searchtype=advanced&showbasic=1&handler=-2&status=$status";
				break;
				
			case "tickets.unassigned":
				$link = "index.php?option=com_fss&view=admin_support&tickets=-1&what=search&searchtype=advanced&showbasic=1&handler=-3&status=$status";
				break;
				
			case "tickets.status":
				$link = "index.php?option=com_fss&view=admin_support&tickets=$status";
				break;
				
			case "myadminsettings":
				$link = "index.php?option=com_fss&view=admin_support&layout=settings";
				break;
			
			case "content.announcements":
				$link = "index.php?option=com_fss&view=admin_content&type=announce";
				break;
			
			case "content.faqs":
				$link = "index.php?option=com_fss&view=admin_content&type=faqs";
				break;
			
			case "content.kb":
				$link = "index.php?option=com_fss&view=admin_content&type=kb";
				break;
		
			case "content.glossary":
				$link = "index.php?option=com_fss&view=admin_content&type=glossary";
				break;		
		}

		$link = FSSRoute::_($link, false);
		//$link = JRoute::_($link, false);
		
		$mainframe = JFactory::getApplication();
		$mainframe->redirect($link);

	}

}